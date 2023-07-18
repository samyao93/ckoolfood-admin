<?php

namespace App\Http\Controllers\Api\V1;

ini_set('memory_limit', '-1');

use App\Models\Order;
use App\Models\Shift;
use App\Models\DeliveryMan;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\BusinessSetting;
use App\Models\DeliveryHistory;
use App\Models\UserNotification;
use App\CentralLogics\OrderLogic;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class DeliverymanController extends Controller
{

    public function get_profile(Request $request)
    {
        $dm = DeliveryMan::with(['rating','userinfo','dm_shift'])->where(['auth_token' => $request['token']])->first();
        $dm['avg_rating'] = (double)($dm?->rating[0]?->average ?? 0);
        $dm['rating_count'] = (double)($dm?->rating[0]?->rating_count ?? 0);
        $dm['order_count'] =(integer)$dm?->orders->count();
        $dm['todays_order_count'] =(integer)$dm->todaysorders->count();
        $dm['this_week_order_count'] =(integer)$dm->this_week_orders->count();
        $dm['member_since_days'] =(integer)$dm->created_at->diffInDays();
        $dm['cash_in_hands'] =(float) ($dm?->wallet?->collected_cash ?? 0);
        $dm['balance'] = (float) ($dm?->wallet?->total_earning - $dm?->wallet?->total_withdrawn ?? 0);
        $dm['todays_earning'] =(float)($dm?->todays_earning()?->sum('original_delivery_charge') + $dm?->todays_earning()?->sum('dm_tips'));
        $dm['this_week_earning'] =(float)($dm?->this_week_earning()?->sum('original_delivery_charge') + $dm?->this_week_earning()?->sum('dm_tips'));
        $dm['this_month_earning'] =(float)($dm?->this_month_earning()?->sum('original_delivery_charge') + $dm?->this_month_earning()?->sum('dm_tips'));
        $dm['total_incentive_earning']= (float) ($dm?->incentives()?->where('status','approved')->sum('incentive'));
        $dm['incentive_list']= $dm?->zone?->incentives ?? [];
        $dm['shift_name']= $dm?->dm_shift?->name ?? null;
        $dm['shift_start_time']= $dm?->dm_shift?->start_time ?? null;
        $dm['shift_end_time']= $dm?->dm_shift?->end_time ?? null;
        unset($dm['dm_shift']);
        unset($dm['zone']);
        unset($dm['orders']);
        unset($dm['rating']);
        unset($dm['todaysorders']);
        unset($dm['this_week_orders']);
        unset($dm['wallet']);
        return response()->json($dm, 200);
    }

    public function update_profile(Request $request)
    {
        $dm = DeliveryMan::with(['rating'])->where(['auth_token' => $request['token']])->first();
        $validator = Validator::make($request->all(), [
            'f_name' => 'required',
            'l_name' => 'required',
            'email' => 'required|unique:delivery_men,email,'.$dm->id,
            'password' => ['nullable', Password::min(8)->mixedCase()->letters()->numbers()->symbols()->uncompromised()],

            'image' => 'nullable|max:2048',
        ], [
            'f_name.required' => 'First name is required!',
            'l_name.required' => 'Last name is required!',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        if ($request->has('image')) {
            $imageName = Helpers::update(dir:'delivery-man/', old_image:$dm->image,format: 'png',image: $request->file('image'));
        } else {
            $imageName = $dm->image;
        }

        if ($request['password'] != null && strlen($request['password']) > 5) {
            $pass = bcrypt($request['password']);
        } else {
            $pass = $dm->password;
        }

        $dm->vehicle_id = $request?->vehicle_id ??  $dm?->vehicle_id ?? null;

        $dm->f_name = $request->f_name;
        $dm->l_name = $request->l_name;
        $dm->email = $request->email;
        $dm->image = $imageName;
        $dm->password = $pass;
        $dm->updated_at = now();
        $dm->save();

        if($dm->userinfo) {
            $userinfo = $dm->userinfo;
            $userinfo->f_name = $request->f_name;
            $userinfo->l_name = $request->l_name;
            $userinfo->email = $request->email;
            $userinfo->image = $imageName;
            $userinfo->save();
        }
        return response()->json(['message' => 'successfully updated!'], 200);
    }

    public function activeStatus(Request $request)
    {
        $shift_id =$request->shift_id ?? Null;
        $dm = DeliveryMan::with(['rating'])->where(['auth_token' => $request['token']])->first();
        $dm->active = $dm->active?0:1;
        $dm->shift_id =$shift_id;
        $dm->save();
        Helpers::set_time_log(user_id:$dm->id,date: date('Y-m-d') ,online: ($dm->active ? now() : null),offline: ($dm->active ? null : now()) , shift_id:$shift_id);
        return response()->json(['message' => translate('messages.active_status_updated')], 200);
    }

    public function get_current_orders(Request $request)
    {
        $dm = DeliveryMan::where(['auth_token' => $request['token']])->first();
        $orders = Order::with(['customer', 'restaurant'])
        ->whereIn('order_status', ['accepted','confirmed','pending', 'processing', 'picked_up', 'handover'])
        ->where(['delivery_man_id' => $dm['id']])
        ->orderBy('accepted')
        ->orderBy('schedule_at', 'desc')
        ->Notpos()
        ->get();
        $orders= Helpers::order_data_formatting($orders, true);
        return response()->json($orders, 200);
    }

    public function get_latest_orders(Request $request)
    {
        $dm = DeliveryMan::where(['auth_token' => $request['token']])->first();

        $orders = Order::with(['customer', 'restaurant']);

        if($dm->type == 'zone_wise')
            {
                $orders = $orders
                ->whereHas('restaurant', function($q) use($dm){
                    $q->where('restaurant_model','subscription')->where('zone_id', $dm->zone_id)->whereHas('restaurant_sub', function($q1){
                        $q1->where('self_delivery', 0);
                    });
                })
                ->orWhereHas('restaurant', function($qu) use($dm) {
                    $qu->where('restaurant_model','commission')->where('zone_id', $dm->zone_id)->where('self_delivery_system', 0);
                });
            }
            else
            {
                $orders = $orders->where('restaurant_id', $dm->restaurant_id);
            }

        if(config('order_confirmation_model') == 'deliveryman' && $dm->type == 'zone_wise')
        {
            $orders = $orders->whereIn('order_status', ['pending', 'confirmed','processing','handover']);
        }
        else
        {
            $orders = $orders->where(function($query){
                $query->where(function($query){
                    $query->where('order_status', 'pending')->whereNotNull('subscription_id');
                })->orWhereIn('order_status', ['confirmed','processing','handover']);
            });
        }

        if(isset($dm->vehicle_id )){
            $orders = $orders->where('vehicle_id',$dm->vehicle_id);
        }
        $orders = $orders->delivery()
        ->OrderScheduledIn(30)
        ->whereNull('delivery_man_id')
        ->orderBy('schedule_at', 'desc')
        ->Notpos()
        ->get();
        $orders= Helpers::order_data_formatting($orders, true);
        return response()->json($orders, 200);
    }

    public function accept_order(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $dm=DeliveryMan::where(['auth_token' => $request['token']])->first();
        $order = Order::where('id', $request['order_id'])
        ->whereNull('delivery_man_id')
        ->Notpos()
        ->first();
        if(!$order)
        {
            return response()->json([
                'errors' => [
                    ['code' => 'order', 'message' => translate('messages.can_not_accept')]
                ]
            ], 404);
        }
        if($dm->current_orders >= config('dm_maximum_orders'))
        {
            return response()->json([
                'errors'=>[
                    ['code' => 'dm_maximum_order_exceed', 'message'=> translate('messages.dm_maximum_order_exceed_warning')]
                ]
            ], 405);
        }

        $cash_in_hand =$dm?->wallet?->collected_cash ?? 0;


        $value=  BusinessSetting::where('key','dm_max_cash_in_hand')->first()?->value ?? 0;

        if($order->payment_method == "cash_on_delivery" && (($cash_in_hand+$order->order_amount) >= $value)){
            return response()->json(['errors' => Helpers::error_formater('dm_max_cash_in_hand',translate('delivery man max cash in hand exceeds'))], 203);
        }

        $order->order_status = in_array($order->order_status, ['pending', 'confirmed'])?'accepted':$order->order_status;
        $order->delivery_man_id = $dm->id;
        $order->accepted = now();
        $order->save();

        $dm->current_orders = $dm->current_orders+1;
        $dm->save();

        $dm->increment('assigned_order_count');

        $fcm_token=$order?->customer?->cm_firebase_token;

        $value = Helpers::order_status_update_message('accepted');
        OrderLogic::update_subscription_log($order);
        try {
            if($value)
            {
                $data = [
                    'title' =>translate('messages.order_push_title'),
                    'description' => $value,
                    'order_id' => $order['id'],
                    'image' => '',
                    'type'=> 'order_status'
                ];
                Helpers::send_push_notif_to_device($fcm_token, $data);
            }

        } catch (\Exception $e) {
            info($e->getMessage());
        }

        return response()->json(['message' => 'Order accepted successfully'], 200);

    }

    public function record_location_data(Request $request)
    {
        $dm = DeliveryMan::where(['auth_token' => $request['token']])->first();
        DB::table('delivery_histories')->insert([
            'delivery_man_id' => $dm['id'],
            'longitude' => $request['longitude'],
            'latitude' => $request['latitude'],
            'time' => now(),
            'location' => $request['location'],
            'created_at' => now(),
            'updated_at' => now()
        ]);
        return response()->json(['message' => 'location recorded'], 200);
    }

    public function get_order_history(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $dm = DeliveryMan::where(['auth_token' => $request['token']])->first();

        $history = DeliveryHistory::where(['order_id' => $request['order_id'], 'delivery_man_id' => $dm['id']])->get();
        return response()->json($history, 200);
    }

    public function update_order_status(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'status' => 'required|in:confirmed,canceled,picked_up,delivered',
            'reason' =>'required_if:status,canceled',
        ]);

        $validator->sometimes('otp', 'required', function ($request) {
            return (Config::get('order_delivery_verification')==1 && $request['status']=='delivered');
        });

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $dm = DeliveryMan::where(['auth_token' => $request['token']])->first();
        $order = Order::where('id', $request['order_id'])->first();
        if(!isset($order)){
            return response()->json([
                'errors' => [
                    ['code' => 'order-not-found', 'message' => translate('messages.order_not_found')]
                ]
            ], 403);
        }

        if($request['status'] =="confirmed" && config('order_confirmation_model') == 'restaurant')
        {
            return response()->json([
                'errors' => [
                    ['code' => 'order-confirmation-model', 'message' => translate('messages.order_confirmation_warning')]
                ]
            ], 403);
        }

        if($request['status'] == 'canceled' && !config('canceled_by_deliveryman'))
        {
            return response()->json([
                'errors' => [
                    ['code' => 'status', 'message' => translate('messages.you_can_not_cancel_a_order')]
                ]
            ], 403);
        }

        if(isset($order->confirmed ) && $request['status'] == 'canceled')
        {
            return response()->json([
                'errors' => [
                    ['code' => 'delivery-man', 'message' => translate('messages.order_can_not_cancle_after_confirm')]
                ]
            ], 403);
        }

        if(Config::get('order_delivery_verification')==1 && $request['status']=='delivered' && $order->otp != $request['otp'])
        {
            return response()->json([
                'errors' => [
                    ['code' => 'otp', 'message' => 'Not matched']
                ]
            ], 406);
        }
        if ($request->status == 'delivered' || isset($order->subscription_id))
        {
            if($order->transaction == null)
            {
                $reveived_by = $order->payment_method == 'cash_on_delivery'?($dm->type != 'zone_wise'?'restaurant':'deliveryman'):'admin';

                if(OrderLogic::create_transaction(order:$order,received_by: $reveived_by, status:null))
                {
                    $order->payment_status = 'paid';
                }
                else
                {
                    return response()->json([
                        'errors' => [
                            ['code' => 'error', 'message' => translate('messages.faield_to_create_order_transaction')]
                        ]
                    ], 406);
                }
            }
            if($order->transaction)
            {
                $order->transaction->update(['delivery_man_id'=>$dm->id]);
            }

            $order?->details->each(function($item, $key){
                if($item->food)
                {
                    $item->food->increment('order_count');
                }
            });
            $order?->customer?->increment('order_count') ?? '';

            $dm->current_orders = $dm->current_orders>1?$dm->current_orders-1:0;
            $dm->save();

            $dm->increment('order_count');
            $order->restaurant->increment('order_count');

        }
        else if($request->status == 'canceled')
        {
            if($order->delivery_man)
            {
                $dm = $order->delivery_man;
                $dm->current_orders = $dm->current_orders>1?$dm->current_orders-1:0;
                $dm->save();
            }


            if(!isset($order->confirmed) && isset($order->subscription_id)){
                $order?->subscription()?->update(['status' => 'canceled']);
                if($order?->subscription?->log){
                    $order->subscription->log()->update([
                        'order_status' => $request->status,
                        'canceled' => now(),
                        ]);
                }
            }

            $order->cancellation_reason = $request->reason;
            $order->canceled_by = 'deliveryman';
        }

        if($request->status == 'confirmed' &&  $order->delivery_man_id == null){
            $order->delivery_man_id = $dm->id;
        }
        // dd($request['status']);
        $order->order_status = $request['status'];
        $order[$request['status']] = now();
        $order->save();
        try {
            Helpers::send_order_notification($order);
        } catch (\Exception $th) {
            info($th->getMessage());
        }

        OrderLogic::update_subscription_log($order);
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
        // OrderLogic::create_subscription_log($request->order_id);
        $dm = DeliveryMan::where(['auth_token' => $request['token']])->first();
        $order = Order::with(['details'])->where('id',$request['order_id'])->where(function($query) use($dm){
            $query->whereNull('delivery_man_id')
                ->orWhere('delivery_man_id', $dm['id']);
        })->Notpos()->first();
        if(!$order)
        {
            return response()->json([
                'errors' => [
                    ['code' => 'order', 'message' => translate('messages.not_found')]
                ]
            ], 404);
        }
        $details = Helpers::order_details_data_formatting($order->details);
        return response()->json($details, 200);
    }

    public function get_order(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $dm = DeliveryMan::where(['auth_token' => $request['token']])->first();

        $order = Order::with(['customer', 'restaurant','details'])->where('id', $request['order_id'])
        ->where(function($q)use($dm){
            $q->where('delivery_man_id', $dm['id'])
            ->orWhereHas('subscription_logs',function($query)use($dm){
                $query->where('delivery_man_id', $dm['id'])->whereIn('order_status', ['delivered','canceled','refund_requested','refunded','refund_request_canceled','failed']);
            });
        })
        ->Notpos()->first();
        if(!$order)
        {
            return response()->json([
                'errors' => [
                    ['code' => 'order', 'message' => translate('messages.not_found')]
                ]
            ], 404);
        }
        return response()->json(Helpers::order_data_formatting($order), 200);
    }

    public function get_all_orders(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required',
            'offset' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $dm = DeliveryMan::where(['auth_token' => $request['token']])->first();


        $paginator = Order::with(['customer', 'restaurant'])
        ->whereIn('order_status', ['delivered','canceled','refund_requested','refunded','refund_request_canceled','failed'])
        ->where(function($q)use($dm){
            $q->where('delivery_man_id', $dm['id'])
            ->orWhereHas('subscription_logs',function($query)use($dm){
                $query->where('delivery_man_id', $dm['id'])->whereIn('order_status', ['delivered','canceled','refund_requested','refunded','refund_request_canceled','failed']);
            });
        })

        ->orderBy('schedule_at', 'desc')
        ->Notpos()
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

    public function get_last_location(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $last_data = DeliveryHistory::whereHas('delivery_man.orders', function($query) use($request){
            return $query->where('id',$request->order_id);
        })->latest()->first();
        return response()->json($last_data, 200);
    }

    public function order_payment_status_update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'status' => 'required|in:paid'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $dm = DeliveryMan::where(['auth_token' => $request['token']])->first();

        if (Order::where(['delivery_man_id' => $dm['id'], 'id' => $request['order_id']])->Notpos()->first()) {
            Order::where(['delivery_man_id' => $dm['id'], 'id' => $request['order_id']])->update([
                'payment_status' => $request['status']
            ]);
            return response()->json(['message' => 'Payment status updated'], 200);
        }
        return response()->json([
            'errors' => [
                ['code' => 'order', 'message' => 'not found!']
            ]
        ], 404);
    }

    public function update_fcm_token(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fcm_token' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $dm = DeliveryMan::where(['auth_token' => $request['token']])->first();

        DeliveryMan::where(['id' => $dm['id']])->update([
            'fcm_token' => $request['fcm_token']
        ]);

        return response()->json(['message'=>'successfully updated!'], 200);
    }

    public function get_notifications(Request $request){

        $dm = DeliveryMan::where(['auth_token' => $request['token']])->first();

        $notifications = Notification::active()->where(function($q) use($dm){
                $q->whereNull('zone_id')->orWhere('zone_id', $dm->zone_id);
            })->where('tergat', 'deliveryman')->where('created_at', '>=', \Carbon\Carbon::today()->subDays(7))->get();

        $user_notifications = UserNotification::where('delivery_man_id', $dm->id)->where('created_at', '>=', \Carbon\Carbon::today()->subDays(7))->get();

        $notifications->append('data');

        $notifications =  $notifications->merge($user_notifications);
        try {
            return response()->json($notifications, 200);
        } catch (\Exception $e) {
            return response()->json([], 200);
        }
    }

    public function remove_account(Request $request)
    {
        $dm = DeliveryMan::where(['auth_token' => $request['token']])->first();

        if(Order::where('delivery_man_id', $dm->id)->whereIn('order_status', ['pending','accepted','confirmed','processing','handover','picked_up'])->count())
        {
            return response()->json(['errors'=>[['code'=>'on-going', 'message'=>translate('messages.user_account_delete_warning')]]],203);
        }

        if($dm->wallet && $dm->wallet->collected_cash > 0)
        {
            return response()->json(['errors'=>[['code'=>'on-going', 'message'=>translate('messages.user_account_wallet_delete_warning')]]],203);
        }

        if (Storage::disk('public')->exists('delivery-man/' . $dm['image'])) {
            Storage::disk('public')->delete('delivery-man/' . $dm['image']);
        }

        foreach (json_decode($dm['identity_image'], true) as $img) {
            if (Storage::disk('public')->exists('delivery-man/' . $img)) {
                Storage::disk('public')->delete('delivery-man/' . $img);
            }
        }
        if($dm->userinfo){
            $dm->userinfo->delete();
        }

        $dm->delete();
        return response()->json([]);
    }

    public function dm_shift(Request $request){
        $shift= Shift::where('status',1)
            ->latest()
            ->get();
            return response()->json($shift, 200);
        }
    // public function assign_vehicle(Request $request){
    //     $validator = Validator::make($request->all(), [
    //         'vehicle_id' => 'required',
    //     ]);
    //     if ($validator->fails()) {
    //         return response()->json(['errors' => Helpers::error_processor($validator)], 403);
    //     }

    //     $dm = DeliveryMan::where(['auth_token' => $request['token']])->first();
    //     $dm->vehicle_id = $request['vehicle_id'];
    //     $dm->save();
    //     return response()->json(['message'=>'successfully updated!'], 200);
    // }

}
