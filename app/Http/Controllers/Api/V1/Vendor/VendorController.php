<?php

namespace App\Http\Controllers\Api\V1\Vendor;

use App\Models\Food;
use App\Models\Order;
use App\Models\Vendor;
use App\Models\Campaign;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\WithdrawRequest;
use App\Models\UserNotification;
use App\Models\WithdrawalMethod;
use App\CentralLogics\OrderLogic;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\CentralLogics\RestaurantLogic;
use App\Models\Restaurant;
use App\Models\RestaurantSubscription;
use Illuminate\Support\Facades\Config;
use App\Models\SubscriptionTransaction;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class VendorController extends Controller
{
    public function get_profile(Request $request)
    {
        $vendor = $request['vendor'];
        $restaurant = Helpers::restaurant_data_formatting(data:$vendor->restaurants[0], multi_data:false);
        $discount=Helpers::get_restaurant_discount(restaurant: $vendor->restaurants[0]);
        unset($restaurant['discount']);
        $restaurant['discount']=$discount;
        $restaurant['schedules']=$restaurant?->schedules()?->get();

        $vendor['order_count'] =$vendor?->orders?->count();
        $vendor['todays_order_count'] =$vendor?->todaysorders?->count();
        $vendor['this_week_order_count'] =$vendor?->this_week_orders?->count();
        $vendor['this_month_order_count'] =$vendor?->this_month_orders?->count();
        $vendor['member_since_days'] =$vendor?->created_at?->diffInDays();
        $vendor['cash_in_hands'] =(float)$vendor?->wallet?->collected_cash ?? 0;
        $vendor['balance'] = (float)$vendor?->wallet?->balance ?? 0;
        $vendor['total_earning']  = (float)$vendor?->wallet?->total_earning ?? 0;
        $vendor['todays_earning'] =(float)$vendor?->todays_earning()?->sum('restaurant_amount');
        $vendor['this_week_earning'] =(float)$vendor?->this_week_earning()?->sum('restaurant_amount');
        $vendor['this_month_earning'] =(float)$vendor?->this_month_earning()?->sum('restaurant_amount');
        $vendor["restaurants"] = $restaurant;
        $vendor['userinfo'] = $vendor?->userinfo;

        $st = Restaurant::withoutGlobalScope('translate')->findOrFail($restaurant['id']);
        $vendor["translations"] = $st->translations;

        unset($vendor['orders']);
        unset($vendor['rating']);
        unset($vendor['todaysorders']);
        unset($vendor['this_week_orders']);
        unset($vendor['wallet']);
        unset($vendor['todaysorders']);
        unset($vendor['this_week_orders']);
        unset($vendor['this_month_orders']);


        if($restaurant->restaurant_model == 'subscription'){
            if(isset($restaurant?->restaurant_sub)){
                if($restaurant->restaurant_sub->max_product== 'unlimited' ){
                    $max_product_uploads= -1;
                }
                else{
                    $max_product_uploads= $restaurant?->restaurant_sub?->max_product - $restaurant?->foods?->count();
                    if($max_product_uploads > 0){
                        $max_product_uploads ?? 0;
                    }elseif($max_product_uploads < 0) {
                        $max_product_uploads = 0;
                    }
                }
                $vendor['subscription'] =RestaurantSubscription::where('restaurant_id',$restaurant->id)->with('package')->latest()->first();
                $vendor['subscription_other_data'] =  [
                    'total_bill'=>  (float) SubscriptionTransaction::where('restaurant_id', $restaurant->id)->where('package_id', $vendor['subscription']?->package?->id)->sum('paid_amount'),
                    'max_product_uploads' => (int) $max_product_uploads];
                }
            }
        return response()->json($vendor, 200);
    }

    public function active_status(Request $request)
    {
        $restaurant = $request?->vendor?->restaurants[0];
        $restaurant->active = $restaurant->active?0:1;
        $restaurant?->save();
        return response()->json(['message' => $restaurant->active?translate('messages.restaurant_opened'):translate('messages.restaurant_temporarily_closed')], 200);
    }

    public function get_earning_data(Request $request)
    {
        $vendor = $request['vendor'];
        $data= RestaurantLogic::get_earning_data(vendor_id:$vendor->id);
        return response()->json($data, 200);
    }

    public function update_profile(Request $request)
    {
        $vendor = $request['vendor'];
        $validator = Validator::make($request->all(), [
            'f_name' => 'required',
            'l_name' => 'required',
            'phone' => 'required|unique:vendors,phone,'.$vendor->id,
            'password' => ['nullable', Password::min(8)->mixedCase()->letters()->numbers()->symbols()->uncompromised()],

            'image' => 'nullable|max:2048',
        ], [
            'f_name.required' => translate('messages.first_name_is_required'),
            'l_name.required' => translate('messages.Last name is required!'),
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        if ($request->has('image')) {
            $imageName = Helpers::update(dir:'vendor/', old_image: $vendor->image, format: 'png', image: $request->file('image'));
        } else {
            $imageName = $vendor->image;
        }
        if ($request['password'] != null) {
            $pass = bcrypt($request['password']);
        } else {
            $pass = $vendor->password;
        }
        $vendor->f_name = $request->f_name;
        $vendor->l_name = $request->l_name;
        $vendor->phone = $request->phone;
        $vendor->image = $imageName;
        $vendor->password = $pass;
        $vendor->updated_at = now();
        $vendor->save();

        return response()->json(['message' => translate('messages.profile_updated_successfully')], 200);
    }

    public function get_current_orders(Request $request)
    {
        $vendor = $request['vendor'];

        $restaurant=$vendor?->restaurants[0];
        $data =0;
        if (($restaurant?->restaurant_model == 'subscription' && $restaurant?->restaurant_sub?->self_delivery == 1)  || ($restaurant?->restaurant_model == 'commission' &&  $restaurant?->self_delivery_system == 1) ){
         $data =1;
        }

        $orders = Order::whereHas('restaurant.vendor', function($query) use($vendor){
            $query->where('id', $vendor->id);
        })
        ->with('customer')
        ->where(function($query)use($data){
            if(config('order_confirmation_model') == 'restaurant' || $data)
            {
                $query->whereIn('order_status', ['accepted','pending','confirmed', 'processing', 'handover','picked_up'])
                ->hasSubscriptionInStatus(['accepted','pending','confirmed', 'processing', 'handover','picked_up']);
            }
            else
            {
                $query->whereIn('order_status', ['confirmed', 'processing', 'handover','picked_up'])
                ->hasSubscriptionInStatus(['accepted','pending','confirmed', 'processing', 'handover','picked_up'])
                ->orWhere(function($query){
                    $query->where('payment_status','paid')->where('order_status', 'accepted');
                })
                ->orWhere(function($query){
                    $query->where('order_status','pending')->where('order_type', 'take_away');
                });
            }
        })
        ->Notpos()
        ->orderBy('schedule_at', 'desc')
        ->get();
        $orders= Helpers::order_data_formatting($orders, true);
        return response()->json($orders, 200);
    }

    public function get_completed_orders(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required',
            'offset' => 'required',
            'status' => 'required|in:all,refunded,delivered',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $vendor = $request['vendor'];
        $paginator = Order::whereHas('restaurant.vendor', function($query) use($vendor){
            $query->where('id', $vendor->id);
        })
        ->with('customer','refund')
        ->when($request->status == 'all', function($query){
            return $query->whereIn('order_status', ['refunded','refund_requested','refund_request_canceled', 'delivered']);
        })
        ->when($request->status != 'all', function($query)use($request){
            return $query->where('order_status', $request->status);
        })
        ->Notpos()
        ->latest()
        ->paginate($request['limit'], ['*'], 'page', $request['offset']);
        $orders= Helpers::order_data_formatting($paginator->items(), true);
        $data = [
            'total_size' => $paginator->total(),
            'limit' => $request['limit'],
            'offset' => $request['offset'],
            'orders' => $orders
        ];
        return response()->json($data, 200);
    }

    public function update_order_status(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'reason' =>'required_if:status,canceled',
            'status' => 'required|in:confirmed,processing,handover,delivered,canceled'
        ]);

        $validator->sometimes('otp', 'required', function ($request) {
            return (Config::get('order_delivery_verification')==1 && $request['status']=='delivered');
        });

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $vendor = $request['vendor'];
        $order = Order::whereHas('restaurant.vendor', function($query) use($vendor){
            $query->where('id', $vendor->id);
        })
        ->where('id', $request['order_id'])
        ->Notpos()
        ->first();

        if(!$order)
        {
            return response()->json([
                'errors' => [
                    ['code' => 'order', 'message' => translate('messages.Order_not_found')]
                ]
            ], 403);
        }

        if($request['order_status']=='canceled')
        {
            if(!config('canceled_by_restaurant'))
            {
                return response()->json([
                    'errors' => [
                        ['code' => 'status', 'message' => translate('messages.you_can_not_cancel_a_order')]
                    ]
                ], 403);
            }
            else if($order->confirmed)
            {
                return response()->json([
                    'errors' => [
                        ['code' => 'status', 'message' => translate('messages.you_can_not_cancel_after_confirm')]
                    ]
                ], 403);
            }
        }

        $restaurant=$vendor?->restaurants[0];
        $data =0;
        if (($restaurant?->restaurant_model == 'subscription' &&  $restaurant?->restaurant_sub?->self_delivery == 1)  || ($restaurant?->restaurant_model == 'commission' &&  $restaurant?->self_delivery_system == 1) ){
         $data =1;
        }

        if($request['status'] =="confirmed" && !$data && config('order_confirmation_model') == 'deliveryman' && $order->order_type != 'take_away' && $order->subscription_id == null)
        {
            return response()->json([
                'errors' => [
                    ['code' => 'order-confirmation-model', 'message' => translate('messages.order_confirmation_warning')]
                ]
            ], 403);
        }

        if($order->picked_up != null)
        {
            return response()->json([
                'errors' => [
                    ['code' => 'status', 'message' => translate('messages.You_can_not_change_status_after_picked_up_by_delivery_man')]
                ]
            ], 403);
        }

        if($request['status']=='delivered' && $order->order_type != 'take_away' && !$data)
        {
            return response()->json([
                'errors' => [
                    ['code' => 'status', 'message' => translate('messages.you_can_not_delivered_delivery_order')]
                ]
            ], 403);
        }
        if(Config::get('order_delivery_verification')==1 && $request['status']=='delivered' && $order->otp != $request['otp'])
        {
            return response()->json([
                'errors' => [
                    ['code' => 'otp', 'message' => 'Not matched']
                ]
            ], 403);
        }

        if ($request->status == 'delivered' && ($order->transaction == null || isset($order->subscription_id))) {
            if($order->payment_method == 'cash_on_delivery')
            {
                $ol = OrderLogic::create_transaction( order:$order, received_by:'restaurant', status: null);
            }
            else
            {
                $ol = OrderLogic::create_transaction( order:$order, received_by:'admin', status: null);
            }

            if(!$ol){
                return response()->json([
                    'errors' => [
                        ['code' => 'error', 'message' => translate('messages.faield_to_create_order_transaction')]
                    ]
                ], 406);
            }

            $order->payment_status = 'paid';
        }

        if($request->status == 'delivered')
        {
            $order?->details?->each(function($item, $key){
                $item?->food?->increment('order_count');
            });
            $order?->customer?->increment('order_count') ;
            $order?->restaurant?->increment('order_count');

            if($order?->delivery_man)
            {
                $dm = $order->delivery_man;
                $dm->current_orders = $dm->current_orders>1?$dm->current_orders-1:0;
                $dm->save();
            }
        }


        if($request->status == 'canceled')
        {
            if($order?->delivery_man)
            {
                $dm = $order->delivery_man;
                $dm->current_orders = $dm->current_orders>1?$dm->current_orders-1:0;
                $dm->save();
            }
            if(!isset($order->confirmed) && isset($order->subscription_id)){
                $order?->subscription()?->update(['status' => 'canceled']);
                    if($order?->subscription?->log){
                        $order?->subscription?->log()?->update([
                            'order_status' => $request->status,
                            'canceled' => now(),
                            ]);
                    }
            }
            $order->cancellation_reason=$request->reason;
            $order->canceled_by='restaurant';
        }

        if($request->status == 'processing') {
            $order->processing_time = isset($request->processing_time) ? $request->processing_time : explode('-', $order['restaurant']['delivery_time'])[0];
        }
        $order->order_status = $request['status'];
        $order[$request['status']] = now();
        $order->save();
        Helpers::send_order_notification($order);

        return response()->json(['message' => 'Status updated'], 200);
    }

    public function get_order_details(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $vendor = $request['vendor'];

        $order = Order::whereHas('restaurant.vendor', function($query) use($vendor){
            $query->where('id', $vendor->id);
        })
        ->with(['customer','details','delivery_man','subscription'])
        ->where('id', $request['order_id'])
        ->Notpos()
        ->first();
        $details = $order?->details;
        $order['details'] = Helpers::order_details_data_formatting($details);
        return response()->json(['order' => $order],200);
    }

    public function get_order(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $vendor = $request['vendor'];

        $order = Order::whereHas('restaurant.vendor', function($query) use($vendor){
            $query->where('id', $vendor->id);
        })
        ->with(['customer','details','delivery_man'])
        ->where('id', $request['order_id'])
        ->Notpos()
        ->first();

        return response()->json(Helpers::order_data_formatting($order),200);
    }

    public function get_all_orders(Request $request)
    {
        $vendor = $request['vendor'];
        $orders = Order::whereHas('restaurant.vendor', function($query) use($vendor){
            $query->where('id', $vendor?->id);
        })
        ->with('customer')
        ->Notpos()
        ->orderBy('schedule_at', 'desc')
        ->get();
        $orders= Helpers::order_data_formatting(data:$orders,multi_data: true);
        return response()->json($orders, 200);
    }

    public function update_fcm_token(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fcm_token' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $vendor = $request['vendor'];

        Vendor::where(['id' => $vendor?->id])->update([
            'firebase_token' => $request['fcm_token']
        ]);

        return response()->json(['message'=>'successfully updated!'], 200);
    }

    public function get_notifications(Request $request){
        $vendor = $request['vendor'];

        $notifications = Notification::active()->where(function($q) use($vendor){
            $q->whereNull('zone_id')->orWhere('zone_id', $vendor->restaurants[0]->zone_id);
        })->where('tergat', 'restaurant')->where('created_at', '>=', \Carbon\Carbon::today()->subDays(7))->get();

        $notifications->append('data');

        $user_notifications = UserNotification::where('vendor_id', $vendor->id)->where('created_at', '>=', \Carbon\Carbon::today()->subDays(7))->get();

        $notifications =  $notifications->merge($user_notifications);

        try {
            return response()->json($notifications, 200);
        } catch (\Exception $e) {
            return response()->json([$e->getMessage()], 200);
        }
    }

    public function get_basic_campaigns(Request $request)
    {
        $vendor = $request['vendor'];
        $campaigns=Campaign::with('restaurants')->active()->running()->latest()->get();
        $data = [];
        $restaurant_id = $vendor?->restaurants[0]?->id;
        foreach ($campaigns as $item) {
            $restaurant_ids = count($item->restaurants)?$item->restaurants->pluck('id')->toArray():[];
            $restaurant_joining_status = count($item->restaurants)?$item->restaurants->pluck('pivot')->toArray():[];
            if($item->start_date)
            {
                $item['available_date_starts']=$item->start_date->format('Y-m-d');
                unset($item['start_date']);
            }
            if($item->end_date)
            {
                $item['available_date_ends']=$item->end_date->format('Y-m-d');
                unset($item['end_date']);
            }

            if (count($item['translations'])>0 ) {
                $translate = array_column($item['translations']->toArray(), 'value', 'key');
                $item['title'] = $translate['title'];
                $item['description'] = $translate['description'];
            }
            $item['vendor_status'] = null;
            foreach($restaurant_joining_status as $status){
                if($status['restaurant_id'] == $restaurant_id){
                    $item['vendor_status'] =  $status['campaign_status'];
                }

            }
            $item['is_joined'] = in_array($restaurant_id, $restaurant_ids)?true:false;
            unset($item['restaurants']);
            array_push($data, $item);
        }
        return response()->json($data, 200);
    }

    public function remove_restaurant(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'campaign_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $campaign = Campaign::where('status', 1)->find($request->campaign_id);
        if(!$campaign)
        {
            return response()->json([
                'errors'=>[
                    ['code'=>'campaign', 'message'=>'Campaign not found or upavailable!']
                ]
            ]);
        }
        $restaurant = $request['vendor']?->restaurants[0];
        $campaign?->restaurants()?->detach($restaurant);
        $campaign?->save();
        return response()->json(['message'=>translate('messages.you_are_successfully_removed_from_the_campaign')], 200);
    }

    public function addrestaurant(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'campaign_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $campaign = Campaign::where('status', 1)->find($request->campaign_id);
        if(!$campaign)
        {
            return response()->json([
                'errors'=>[
                    ['code'=>'campaign', 'message'=>'Campaign not found or upavailable!']
                ]
            ]);
        }
        $restaurant = $request['vendor']?->restaurants[0];
        $campaign?->restaurants()?->attach($restaurant);
        $campaign?->save();
        return response()->json(['message'=>translate('messages.you_are_successfully_joined_to_the_campaign')], 200);
    }

    public function get_products(Request $request)
    {
        $limit=$request->limit?$request->limit:25;
        $offset=$request->offset?$request->offset:1;

        $type = $request->query('type', 'all');

        $paginator = Food::type($type)->where('restaurant_id', $request['vendor']->restaurants[0]->id)->latest()->paginate($limit, ['*'], 'page', $offset);
        $data = [
            'total_size' => $paginator->total(),
            'limit' => $limit,
            'offset' => $offset,
            'products' => Helpers::product_data_formatting(data:$paginator->items(), multi_data: true, trans:true, local:app()->getLocale())
        ];

        return response()->json($data, 200);
    }

    public function update_bank_info(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bank_name' => 'required|max:191',
            'branch' => 'required|max:191',
            'holder_name' => 'required|max:191',
            'account_no' => 'required|max:191'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $bank = $request['vendor'];
        $bank->bank_name = $request->bank_name;
        $bank->branch = $request->branch;
        $bank->holder_name = $request->holder_name;
        $bank->account_no = $request->account_no;
        $bank?->save();

        return response()->json(['message'=>translate('messages.bank_info_updated_successfully'),200]);
    }

    public function withdraw_list(Request $request)
    {
        $withdraw_req = WithdrawRequest::where('vendor_id', $request['vendor']->id)->latest()->get();
        $temp = [];
        $status = [
            0=>'Pending',
            1=>'Approved',
            2=>'Denied'
        ];
        foreach($withdraw_req as $item)
        {
            $item['status'] = $status[$item->approved];
            $item['requested_at'] = $item->created_at->format('Y-m-d H:i:s');
            $item['bank_name'] = $item->method ? $item->method->method_name :  $request['vendor']->bank_name;
            $item['detail']=json_decode($item->withdrawal_method_fields,true);

            unset($item['created_at']);
            unset($item['approved']);
            $temp[] = $item;
        }
        return response()->json($temp, 200);
    }

    public function request_withdraw(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0.01',
            'id'=> 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $method = WithdrawalMethod::find($request['id']);
        $fields = array_column($method->method_fields, 'input_name');
        $values = $request->all();

        $method_data = [];
        foreach ($fields as $field) {
            if(key_exists($field, $values)) {
                $method_data[$field] = $values[$field];
            }
        }


        $w = $request['vendor']?->wallet;
        if ($w?->balance >= $request['amount']) {
            $data = [
                'vendor_id' => $w?->vendor_id,
                'amount' => $request['amount'],
                'transaction_note' => null,
                'withdrawal_method_id' => $request['id'],
                'withdrawal_method_fields' => json_encode($method_data),
                'approved' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ];
            try
            {
                DB::table('withdraw_requests')->insert($data);
                $w?->increment('pending_withdraw', $request['amount']);
                $mail_status = Helpers::get_mail_status('withdraw_request_mail_status_admin');
                if(config('mail.status') && $mail_status == '1') {
                    $wallet_transaction = WithdrawRequest::where('vendor_id',$w->vendor_id)->latest()->first();
                    $admin= \App\Models\Admin::where('role_id', 1)->first();
                    Mail::to($admin->email)->send(new \App\Mail\WithdrawRequestMail('admin_mail',$wallet_transaction));
                }
                return response()->json(['message'=>translate('messages.withdraw_request_placed_successfully')],200);
            }
            catch(\Exception $e)
            {
                info($e->getMessage());
                return response()->json($e);
            }
        }
        return response()->json([
            'errors'=>[
                ['code'=>'amount', 'message'=>translate('messages.insufficient_balance')]
            ]
        ],403);
    }

    public function remove_account(Request $request)
    {
        $vendor = $request['vendor'];

        if(Order::where('restaurant_id', $vendor?->restaurants[0]?->id)->whereIn('order_status', ['pending','accepted','confirmed','processing','handover','picked_up'])->count())
        {
            return response()->json(['errors'=>[['code'=>'on-going', 'message'=>translate('messages.user_account_delete_warning')]]],203);
        }

        if($vendor?->wallet && $vendor?->wallet?->collected_cash > 0)
        {
            return response()->json(['errors'=>[['code'=>'on-going', 'message'=>translate('messages.user_account_wallet_delete_warning')]]],203);
        }

        if (Storage::disk('public')->exists('vendor/' . $vendor['image'])) {
            Storage::disk('public')->delete('vendor/' . $vendor['image']);
        }
        if (Storage::disk('public')->exists('restaurant/' . $vendor?->restaurants[0]?->logo)) {
            Storage::disk('public')->delete('restaurant/' . $vendor?->restaurants[0]?->logo);
        }

        if (Storage::disk('public')->exists('restaurant/cover/' . $vendor?->restaurants[0]?->cover_photo)) {
            Storage::disk('public')->delete('restaurant/cover/' . $vendor?->restaurants[0]?->cover_photo);
        }

        $vendor?->restaurants()?->delete();
        $vendor?->userinfo?->delete();
        $vendor?->delete();
        return response()->json([]);
    }

    public function withdraw_method_list(){
        $wi=WithdrawalMethod::where('is_active',1)->get();
        return response()->json($wi,200);
    }

}
