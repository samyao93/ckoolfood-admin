<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Food;
use App\Models\Zone;
use App\Models\Admin;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Refund;
use App\Mail\PlaceOrder;
use App\Models\Restaurant;
use App\Mail\RefundRequest;
use App\Models\OrderDetail;
use App\Models\ItemCampaign;
use App\Models\RefundReason;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\BusinessSetting;
use App\CentralLogics\OrderLogic;
use App\Models\OrderCancelReason;
use App\CentralLogics\CouponLogic;
use Illuminate\Support\Facades\DB;
use App\Mail\OrderVerificationMail;
use App\CentralLogics\CustomerLogic;
use App\Http\Controllers\Controller;
use App\Models\SubscriptionSchedule;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use MatanYadaev\EloquentSpatial\Objects\Point;

class OrderController extends Controller
{
    public function track_order(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $order = Order::with(['restaurant','restaurant.restaurant_sub', 'refund', 'delivery_man', 'delivery_man.rating','subscription'])->withCount('details')->where(['id' => $request['order_id'], 'user_id' => $request->user()->id])->Notpos()->first();
        if($order){
            $order['restaurant'] = $order['restaurant'] ? Helpers::restaurant_data_formatting($order['restaurant']): $order['restaurant'];
            $order['delivery_address'] = $order['delivery_address']?json_decode($order['delivery_address']):$order['delivery_address'];
            $order['delivery_man'] = $order['delivery_man']?Helpers::deliverymen_data_formatting([$order['delivery_man']]):$order['delivery_man'];
            unset($order['details']);
        } else{
            return response()->json([
                'errors' => [
                    ['code' => 'schedule_at', 'message' => translate('messages.not_found')]
                ]
            ], 404);
        }
        return response()->json($order, 200);
    }

    public function place_order(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_amount' => 'required',
            'payment_method'=>'required|in:cash_on_delivery,digital_payment,wallet',
            'order_type' => 'required|in:take_away,delivery',
            'restaurant_id' => 'required',
            'distance' => 'required_if:order_type,delivery',
            'address' => 'required_if:order_type,delivery',
            'longitude' => 'required_if:order_type,delivery',
            'latitude' => 'required_if:order_type,delivery',
            'dm_tips' => 'nullable|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        if($request->payment_method == 'wallet' && Helpers::get_business_settings('wallet_status', false) != 1)
        {
            return response()->json([
                'errors' => [
                    ['code' => 'payment_method', 'message' => translate('messages.customer_wallet_disable_warning')]
                ]
            ], 203);
        }
        $coupon = null;
        $delivery_charge = null;
        $free_delivery_by = null;
        $coupon_created_by = null;
        $schedule_at = $request->schedule_at?\Carbon\Carbon::parse($request->schedule_at):now();
        $per_km_shipping_charge = 0;
        $minimum_shipping_charge = 0;
        $maximum_shipping_charge =  0;
        $max_cod_order_amount_value=  0;
        $increased=0;
        $distance_data = $request->distance ?? 0;


        $home_delivery = BusinessSetting::where('key', 'home_delivery')->first()?->value ?? null;
        if ($home_delivery == null && $request->order_type == 'delivery') {
            return response()->json([
                'errors' => [
                    ['code' => 'order_type', 'message' => translate('messages.Home_delivery_is_disabled')]
                ]
            ], 403);
        }

        $take_away = BusinessSetting::where('key', 'take_away')->first()?->value ?? null;
        if ($take_away == null && $request->order_type == 'take_away') {
            return response()->json([
                'errors' => [
                    ['code' => 'order_type', 'message' => translate('messages.Take_away_is_disabled')]
                ]
            ], 403);
        }

        $settings =  BusinessSetting::where('key', 'cash_on_delivery')->first();
        $cod = json_decode($settings?->value, true);
        if(isset($cod['status']) &&  $cod['status'] != 1 && $request->payment_method == 'cash_on_delivery'){
            return response()->json([
                'errors' => [
                    ['code' => 'order_time', 'message' => translate('messages.Cash_on_delivery_is_not_active')]
                ]
            ], 403);

        }

        if($request->schedule_at && $schedule_at < now())
        {
            return response()->json([
                'errors' => [
                    ['code' => 'order_time', 'message' => translate('messages.you_can_not_schedule_a_order_in_past')]
                ]
            ], 406);
        }
        $restaurant = Restaurant::with(['discount', 'restaurant_sub'])->selectRaw('*, IF(((select count(*) from `restaurant_schedule` where `restaurants`.`id` = `restaurant_schedule`.`restaurant_id` and `restaurant_schedule`.`day` = '.$schedule_at->format('w').' and `restaurant_schedule`.`opening_time` < "'.$schedule_at->format('H:i:s').'" and `restaurant_schedule`.`closing_time` >"'.$schedule_at->format('H:i:s').'") > 0), true, false) as open')->where('id', $request->restaurant_id)->first();

        if(!$restaurant) {
            return response()->json([
                'errors' => [
                    ['code' => 'order_time', 'message' => translate('messages.restaurant_not_found')]
                ]
            ], 404);
        }


        $rest_sub=$restaurant?->restaurant_sub;
        if ($restaurant->restaurant_model == 'subscription' && isset($rest_sub)) {
            if($rest_sub->max_order != "unlimited" && $rest_sub->max_order <= 0){
                return response()->json([
                    'errors' => [
                        ['code' => 'order-confirmation-error', 'message' => translate('messages.Sorry_the_restaurant_is_unable_to_take_any_order_!')]
                    ]
                ], 403);
            }
        }
        elseif( $restaurant->restaurant_model == 'unsubscribed'){
            return response()->json([
                'errors' => [
                    ['code' => 'order-confirmation-model', 'message' => translate('messages.Sorry_the_restaurant_is_unable_to_take_any_order_!')]
                ]
            ], 403);
        }


        if($request->schedule_at && !$restaurant->schedule_order){
            return response()->json([
                'errors' => [
                    ['code' => 'schedule_at', 'message' => translate('messages.schedule_order_not_available')]
                ]
            ], 406);
        }

        if($restaurant->open == false && !$request->subscription_order){
            return response()->json([
                'errors' => [
                    ['code' => 'order_time', 'message' => translate('messages.restaurant_is_closed_at_order_time')]
                ]
            ], 406);
        }

        if ($request['coupon_code']) {
            $coupon = Coupon::active()->where(['code' => $request['coupon_code']])->first();
            if (isset($coupon)) {
                $staus = CouponLogic::is_valide(coupon: $coupon, user_id: $request->user()->id ,restaurant_id: $request['restaurant_id']);

                $message= match($staus){
                    407 => translate('messages.coupon_expire'),
                    408 => translate('messages.You_are_not_eligible_for_this_coupon'),
                    406 => translate('messages.coupon_usage_limit_over'),
                    404 => translate('messages.not_found'),
                    default => null ,
                };
                if ($message != null) {
                    return response()->json([
                        'errors' => [
                            ['code' => 'coupon', 'message' => $message]
                        ]
                    ], $staus);
                }
                $coupon->increment('total_uses');

                $coupon_created_by =$coupon->created_by;
                if($coupon->coupon_type == 'free_delivery'){
                    $delivery_charge = 0;
                    $free_delivery_by =  $coupon_created_by;
                    $coupon_created_by = null;
                    $coupon = null;
                }

            } else {
                return response()->json([
                    'errors' => [
                        ['code' => 'coupon', 'message' => translate('messages.not_found')]
                    ]
                ], 404);
            }
        }


        $data = Helpers::vehicle_extra_charge(distance_data:$distance_data);
        $extra_charges = (float) (isset($data) ? $data['extra_charge']  : 0);
        $vehicle_id= (isset($data) ? (int) $data['vehicle_id']  : null);

        if($request->latitude && $request->longitude){
            $zone = Zone::where('id', $restaurant->zone_id)->whereContains('coordinates', new Point($request->latitude, $request->longitude, POINT_SRID))->first();            if(!$zone)
            {
                $errors = [];
                array_push($errors, ['code' => 'coordinates', 'message' => translate('messages.out_of_coverage')]);
                return response()->json([
                    'errors' => $errors
                ], 403);
            }
            if( $zone->per_km_shipping_charge && $zone->minimum_shipping_charge ) {
                $per_km_shipping_charge = $zone->per_km_shipping_charge;
                $minimum_shipping_charge = $zone->minimum_shipping_charge;
                $maximum_shipping_charge = $zone->maximum_shipping_charge;
                $max_cod_order_amount_value= $zone->max_cod_order_amount;
                if($zone->increased_delivery_fee_status == 1){
                    $increased=$zone->increased_delivery_fee ?? 0;
                }
            }
        }


        if($request['order_type'] != 'take_away' && !$restaurant->free_delivery &&  !isset($delivery_charge) && ($restaurant->restaurant_model == 'subscription' && isset($restaurant->restaurant_sub) && $restaurant->restaurant_sub->self_delivery == 1  || $restaurant->restaurant_model == 'commission' &&  $restaurant->self_delivery_system == 1 )){
                $per_km_shipping_charge = $restaurant->per_km_shipping_charge;
                $minimum_shipping_charge = $restaurant->minimum_shipping_charge;
                $maximum_shipping_charge = $restaurant->maximum_shipping_charge;
                $extra_charges= 0;
                $vehicle_id=null;
                $increased=0;
        }

        if($restaurant->free_delivery || $free_delivery_by == 'vendor' ){
            $per_km_shipping_charge = $restaurant->per_km_shipping_charge;
            $minimum_shipping_charge = $restaurant->minimum_shipping_charge;
            $maximum_shipping_charge = $restaurant->maximum_shipping_charge;
            $extra_charges= 0;
            $vehicle_id=null;
            $increased=0;
        }

        $original_delivery_charge = ($request->distance * $per_km_shipping_charge > $minimum_shipping_charge) ? $request->distance * $per_km_shipping_charge + $extra_charges  : $minimum_shipping_charge + $extra_charges;

        if($request['order_type'] == 'take_away')
        {
            $per_km_shipping_charge = 0;
            $minimum_shipping_charge = 0;
            $maximum_shipping_charge = 0;
            $extra_charges= 0;
            $distance_data = 0;
            $vehicle_id=null;
            $increased=0;
            $original_delivery_charge =0;
        }

        if ( $maximum_shipping_charge  > $minimum_shipping_charge  && $original_delivery_charge >  $maximum_shipping_charge ){
            $original_delivery_charge = $maximum_shipping_charge;
        }
        else{
            $original_delivery_charge = $original_delivery_charge;
        }

        if(!isset($delivery_charge)){
            $delivery_charge = ($request->distance * $per_km_shipping_charge > $minimum_shipping_charge) ? $request->distance * $per_km_shipping_charge : $minimum_shipping_charge;
            if ( $maximum_shipping_charge  > $minimum_shipping_charge  && $delivery_charge + $extra_charges >  $maximum_shipping_charge ){
                $delivery_charge =$maximum_shipping_charge;
            }
            else{
                $delivery_charge =$extra_charges + $delivery_charge;
            }
        }


        if($increased > 0 ){
            if($delivery_charge > 0){
                $increased_fee = ($delivery_charge * $increased) / 100;
                $delivery_charge = $delivery_charge + $increased_fee;
            }
            if($original_delivery_charge > 0){
                $increased_fee = ($original_delivery_charge * $increased) / 100;
                $original_delivery_charge = $original_delivery_charge + $increased_fee;
            }
        }
        $address = [
            'contact_person_name' => $request->contact_person_name?$request->contact_person_name:$request?->user()?->f_name.' '.$request?->user()?->f_name,
            'contact_person_number' => $request->contact_person_number?$request->contact_person_number:$request?->user()?->phone,
            'address_type' => $request->address_type?$request->address_type:'Delivery',
            'address' => $request->address,
            'floor' => $request->floor,
            'road' => $request->road,
            'house' => $request->house,
            'longitude' => (string)$request->longitude,
            'latitude' => (string)$request->latitude,
        ];

        $total_addon_price = 0;
        $product_price = 0;
        $restaurant_discount_amount = 0;

        $order_details = [];
        $order = new Order();
        $order->id = 100000 + Order::count() + 1;
        if (Order::find($order->id)) {
            $order->id = Order::orderBy('id', 'desc')->first()->id + 1;
        }
        $order->distance = $distance_data;
        $order->user_id = $request->user()->id;
        $order->order_amount = $request['order_amount'];
        $order->payment_status = $request['payment_method']=='wallet'?'paid':'unpaid';
        $order->order_status = $request['payment_method']=='digital_payment'?'pending':($request->payment_method == 'wallet'?'confirmed':'pending');
        $order->coupon_code = $request['coupon_code'];
        $order->payment_method = $request->payment_method;
        $order->transaction_reference = null;
        $order->order_note = $request['order_note'];
        $order->order_type = $request['order_type'];
        $order->restaurant_id = $request['restaurant_id'];
        $order->delivery_charge = round($delivery_charge, config('round_up_to_digit'))??0;
        $order->original_delivery_charge = round($original_delivery_charge, config('round_up_to_digit'));
        $order->delivery_address = json_encode($address);
        $order->schedule_at = $schedule_at;
        $order->scheduled = $request->schedule_at?1:0;
        $order->otp = rand(1000, 9999);
        $order->zone_id = $restaurant->zone_id;
        $dm_tips_manage_status = BusinessSetting::where('key', 'dm_tips_status')->first()->value;
        if ($dm_tips_manage_status == 1) {
            $order->dm_tips = $request->dm_tips ?? 0;
        } else {
            $order->dm_tips = 0;
        }
        $order->vehicle_id = $vehicle_id;
        $order->pending = now();
        $order->confirmed = $request->payment_method == 'wallet' ? now() : null;
        $order->created_at = now();
        $order->updated_at = now();

        $order->cutlery = $request->cutlery ? 1 : 0;
        $order->unavailable_item_note = $request->unavailable_item_note ?? null ;
        $order->delivery_instruction = $request->delivery_instruction ?? null ;
        $order->tax_percentage = $restaurant->tax ;


        foreach ($request['cart'] as $c) {

            if ($c['item_campaign_id'] != null) {
                $product = ItemCampaign::active()->find($c['item_campaign_id']);
                $campaign_id = $c['item_campaign_id'];
                $code = 'campaign';
            } else{
                $product = Food::active()->find($c['food_id']);
                $food_id = $c['food_id'];
                $code = 'food';
            }

            if ($product) {
                $product_variations = json_decode($product->variations, true);
                $variations=[];
                if (count($product_variations)) {
                    $variation_data = Helpers::get_varient($product_variations, $c['variations']);
                    $price = $product['price'] + $variation_data['price'];
                    $variations = $variation_data['variations'];
                } else {
                    $price = $product['price'];
                }

                $product->tax = $restaurant->tax;
                $product = Helpers::product_data_formatting($product, false, false, app()->getLocale());
                $addon_data = Helpers::calculate_addon_price(addons: \App\Models\AddOn::whereIn('id',$c['add_on_ids'])->get(), add_on_qtys: $c['add_on_qtys']);


                $or_d = [
                    'food_id' => $food_id ??  null,
                    'item_campaign_id' => $campaign_id ?? null,
                    'food_details' => json_encode($product),
                    'quantity' => $c['quantity'],
                    'price' => round($price, config('round_up_to_digit')),
                    'tax_amount' => Helpers::tax_calculate(food:$product, price:$price),
                    'discount_on_food' => Helpers::product_discount_calculate(product:$product, price:$price, restaurant:$restaurant),
                    'discount_type' => 'discount_on_product',
                    'variation' => json_encode($variations),
                    'add_ons' => json_encode($addon_data['addons']),
                    'total_add_on_price' => $addon_data['total_add_on_price'],
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                $order_details[] = $or_d;
                $total_addon_price += $or_d['total_add_on_price'];
                $product_price += $price*$or_d['quantity'];
                $restaurant_discount_amount += $or_d['discount_on_food']*$or_d['quantity'];

            } else {
                return response()->json([
                    'errors' => [
                        ['code' => $code ?? null, 'message' => translate('messages.product_unavailable_warning')]
                    ]
                ], 404);
            }
        }


        $order->discount_on_product_by = 'vendor';

        $restaurant_discount = Helpers::get_restaurant_discount(restaurant:$restaurant);
        if(isset($restaurant_discount)){
            $order->discount_on_product_by = 'admin';

            if($product_price + $total_addon_price < $restaurant_discount['min_purchase']){
                $restaurant_discount_amount = 0;
            }

            if($restaurant_discount_amount > $restaurant_discount['max_discount']){
                $restaurant_discount_amount = $restaurant_discount['max_discount'];
            }
        }

        $coupon_discount_amount = $coupon ? CouponLogic::get_discount(coupon:$coupon, order_amount: $product_price + $total_addon_price - $restaurant_discount_amount) : 0;
        $total_price = $product_price + $total_addon_price - $restaurant_discount_amount - $coupon_discount_amount ;

        $tax = ($restaurant->tax > 0)?$restaurant->tax:0;
        $order->tax_status = 'excluded';

        $tax_included =BusinessSetting::where(['key'=>'tax_included'])->first() ?  BusinessSetting::where(['key'=>'tax_included'])->first()->value : 0;
        if ($tax_included ==  1){
            $order->tax_status = 'included';
        }

        $total_tax_amount=Helpers::product_tax(price:$total_price, tax:$tax, is_include:$order->tax_status =='included');

        $tax_a=$order->tax_status =='included'?0:$total_tax_amount;

        if($restaurant->minimum_order > $product_price + $total_addon_price )
        {
            return response()->json([
                'errors' => [
                    ['code' => 'order_time', 'message' => translate('messages.you_need_to_order_at_least', ['amount'=>$restaurant->minimum_order.' '.Helpers::currency_code()])]
                ]
            ], 406);
        }

        $free_delivery_over = BusinessSetting::where('key', 'free_delivery_over')->first()->value;
        if(isset($free_delivery_over))
        {
            if($free_delivery_over <= $product_price + $total_addon_price - $coupon_discount_amount - $restaurant_discount_amount)
            {
                $order->delivery_charge = 0;
                $free_delivery_by = 'admin';
            }
        }

        if($restaurant->free_delivery){
            $order->delivery_charge = 0;
            $free_delivery_by = 'vendor';
        }

        $order->coupon_created_by = $coupon_created_by;
        $order_amount = round($total_price + $tax_a + $order->delivery_charge , config('round_up_to_digit'));
        if($request->payment_method == 'wallet' && $request->user()->wallet_balance < $order_amount)
        {
            return response()->json([
                'errors' => [
                    ['code' => 'order_amount', 'message' => translate('messages.insufficient_balance')]
                ]
            ], 203);
        }
        try {
            $order->coupon_discount_amount = round($coupon_discount_amount, config('round_up_to_digit'));
            $order->coupon_discount_title = $coupon ? $coupon->title : '';
            $order->free_delivery_by = $free_delivery_by;
            $order->restaurant_discount_amount= round($restaurant_discount_amount, config('round_up_to_digit'));
            $order->total_tax_amount= round($total_tax_amount, config('round_up_to_digit'));
            $order->order_amount = $order_amount + $order->dm_tips;

            if( $max_cod_order_amount_value > 0 && $order->payment_method == 'cash_on_delivery' && $order->order_amount > $max_cod_order_amount_value){
            return response()->json([
                'errors' => [
                    ['code' => 'order_amount', 'message' => translate('messages.You can not Order more then ').$max_cod_order_amount_value .Helpers::currency_symbol().' '. translate('messages.on COD order.')]
                ]
            ], 203);
            }

            DB::beginTransaction();

            // new Order Subscription create
            if($request->subscription_order && $request->subscription_quantity){
                $subscription = new Subscription();
                $subscription->status = 'active';
                $subscription->start_at = $request->subscription_start_at;
                $subscription->end_at = $request->subscription_end_at;
                $subscription->type = $request->subscription_type;
                $subscription->quantity = $request->subscription_quantity;
                $subscription->user_id = $request->user()->id;
                $subscription->restaurant_id = $restaurant->id;
                $subscription->save();
                $order->subscription_id = $subscription->id;
                // $subscription_schedules =  Helpers::get_subscription_schedules($request->subscription_type, $request->subscription_start_at, $request->subscription_end_at, json_decode($request->days, true));

                $days = array_map(function($day)use($subscription){
                    $day['subscription_id'] = $subscription->id;
                    $day['type'] = $subscription->type;
                    $day['created_at'] = now();
                    $day['updated_at'] = now();
                    return $day;
                },json_decode($request->subscription_days, true));
                // info(['SubscriptionSchedule_____', $days]);
                SubscriptionSchedule::insert($days);

                // $order->checked = 1;
            }

            $order->save();
            // new Order Subscription logs create for the order
            OrderLogic::create_subscription_log(id:$order->id);
            // End Order Subscription.

            foreach ($order_details as $key => $item) {
                $order_details[$key]['order_id'] = $order->id;
            }
            OrderDetail::insert($order_details);

            $customer = $request->user();
            $customer->zone_id = $restaurant->zone_id;
            $customer->save();

            $restaurant->increment('total_order');

            if ($restaurant->restaurant_model == 'subscription' && isset($rest_sub) && $request['payment_method'] != 'digital_payment'){
                if ($rest_sub->max_order != "unlimited" && $rest_sub->max_order > 0 ) {
                    $rest_sub->decrement('max_order' , 1);
                    }
            }


            Helpers::visitor_log(model: 'restaurant', user_id:$customer->id, visitor_log_id:$restaurant->id, order_count:true);
            if($request->payment_method == 'wallet') CustomerLogic::create_wallet_transaction(user_id:$order->user_id, amount:$order->order_amount, transaction_type:'order_place', referance:$order->id);

            DB::commit();
            //PlaceOrderMail
            $order_mail_status = Helpers::get_mail_status('place_order_mail_status_user');
            $order_verification_mail_status = Helpers::get_mail_status('order_verification_mail_status_user');
            try {
                if ($order->order_status == 'pending' && config('mail.status') && $order_mail_status == '1') {
                    Mail::to($request->user()->email)->send(new PlaceOrder($order->id));
                }
                if ($order->order_status == 'pending' && config('order_delivery_verification') == 1 && $order_verification_mail_status == '1') {
                    Mail::to($request->user()->email)->send(new OrderVerificationMail($order->otp,$request->user()->f_name));
                }
            }catch (\Exception $ex) {
                info($ex);
            }
            return response()->json([
                'message' => translate('messages.order_placed_successfully'),
                'order_id' => $order->id,
                'total_ammount' => $total_price+$order->delivery_charge+$tax_a
            ], 200);
        } catch (\Exception $e) {
            info($e->getMessage());
            return response()->json([$e->getMessage()], 403);
        }

        return response()->json([
            'errors' => [
                ['code' => 'order_time', 'message' => translate('messages.failed_to_place_order')]
            ]
        ], 403);
    }

    public function get_order_list(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required',
            'offset' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $paginator = Order::with(['restaurant', 'delivery_man.rating'])->withCount('details')->where(['user_id' => $request->user()->id])->
        whereIn('order_status', ['delivered','canceled','refund_requested','refund_request_canceled','refunded','failed'])->Notpos()
        ->whereNull('subscription_id')

        ->latest()->paginate($request['limit'], ['*'], 'page', $request['offset']);
        $orders = array_map(function ($data) {
            $data['delivery_address'] = $data['delivery_address']?json_decode($data['delivery_address']):$data['delivery_address'];
            $data['restaurant'] = $data['restaurant']?Helpers::restaurant_data_formatting($data['restaurant']):$data['restaurant'];
            $data['delivery_man'] = $data['delivery_man']?Helpers::deliverymen_data_formatting([$data['delivery_man']]):$data['delivery_man'];
            return $data;
        }, $paginator->items());
        $data = [
            'total_size' => $paginator->total(),
            'limit' => $request['limit'],
            'offset' => $request['offset'],
            'orders' => $orders
        ];
        return response()->json($data, 200);
    }
    public function get_order_subscription_list(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required',
            'offset' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $paginator = Order::with(['restaurant', 'delivery_man.rating'])->withCount('details')->where(['user_id' => $request->user()->id])
        ->Notpos()
        ->whereNotNull('subscription_id')
        ->latest()->paginate($request['limit'], ['*'], 'page', $request['offset']);
        $orders = array_map(function ($data) {
            $data['delivery_address'] = $data['delivery_address']?json_decode($data['delivery_address']):$data['delivery_address'];
            $data['restaurant'] = $data['restaurant']?Helpers::restaurant_data_formatting($data['restaurant']):$data['restaurant'];
            $data['delivery_man'] = $data['delivery_man']?Helpers::deliverymen_data_formatting([$data['delivery_man']]):$data['delivery_man'];
            return $data;
        }, $paginator->items());
        $data = [
            'total_size' => $paginator->total(),
            'limit' => $request['limit'],
            'offset' => $request['offset'],
            'orders' => $orders
        ];
        return response()->json($data, 200);
    }


    public function get_running_orders(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required',
            'offset' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $paginator = Order::with(['restaurant', 'delivery_man.rating'])->withCount('details')->where(['user_id' => $request->user()->id])
        ->whereNull('subscription_id')
        ->whereNotIn('order_status', ['delivered','canceled','refund_requested','refund_request_canceled','refunded','failed'])
        ->Notpos()->latest()->paginate($request['limit'], ['*'], 'page', $request['offset']);

        $orders = array_map(function ($data) {
            $data['delivery_address'] = $data['delivery_address']?json_decode($data['delivery_address']):$data['delivery_address'];
            $data['restaurant'] = $data['restaurant']?Helpers::restaurant_data_formatting($data['restaurant']):$data['restaurant'];
            $data['delivery_man'] = $data['delivery_man']?Helpers::deliverymen_data_formatting([$data['delivery_man']]):$data['delivery_man'];
            return $data;
        }, $paginator->items());
        $data = [
            'total_size' => $paginator->total(),
            'limit' => $request['limit'],
            'offset' => $request['offset'],
            'orders' => $orders
        ];
        return response()->json($data, 200);
    }

    public function get_order_details(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $order = Order::with('details','subscription.schedules')->where('user_id', $request->user()->id)->find($request->order_id);
        $details = $order?->details;

        if ($details != null && $details->count() > 0) {
            $storage = [];
            foreach ($details as $item) {
                $item['add_ons'] = json_decode($item['add_ons']);
                $item['variation'] = json_decode($item['variation']);
                $item['food_details'] = json_decode($item['food_details'], true);
                $item['zone_id'] = (int) (isset($order->zone_id) ? $order->zone_id :  $order->restaurant->zone_id);
                array_push($storage, $item);
            }
            $data = $storage;
            $subscription_schedules =  $order?->subscription?->schedules ;
            return response()->json(['details'=>$data, 'subscription_schedules'=> $subscription_schedules,
            ], 200);
        }

        else {
            return response()->json([
                'errors' => [
                    ['code' => 'order', 'message' => translate('messages.not_found')]
                ]
            ], 200);
        }
    }

    public function cancel_order(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reason' => 'required|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $order = Order::where(['user_id' => $request->user()->id, 'id' => $request['order_id']])->Notpos()->first();
        if(!$order){
                return response()->json([
                    'errors' => [
                        ['code' => 'order', 'message' => translate('messages.not_found')]
                    ]
                ], 404);
        }
        else if ($order->order_status == 'pending' || $order->order_status == 'failed' || $order->order_status == 'canceled'  ) {
            $order->order_status = 'canceled';
            $order->canceled = now();
            $order->cancellation_reason = $request->reason;
            $order->canceled_by = 'customer';
            $order->save();

            //   if(!isset($order->confirmed) && isset($order->subscription_id)){
            //     $order->subscription()->update(['status' => 'canceled']);
            //         if($order->subscription->log){
            //             $order->subscription->log()->update([
            //                 'order_status' => $request->status,
            //                 'canceled' => now(),
            //                 ]);
            //         }

            // }



            Helpers::send_order_notification($order);

            Helpers::increment_order_count($order->restaurant);

            return response()->json(['message' => translate('messages.order_canceled_successfully')], 200);
        }
        return response()->json([
            'errors' => [
                ['code' => 'order', 'message' => translate('messages.you_can_not_cancel_after_confirm')]
            ]
        ], 403);
    }

    public function refund_reasons(){
        $refund_reasons=RefundReason::where('status',1)->get();
        return response()->json([
            'refund_reasons' => $refund_reasons
        ], 200);
    }

    public function refund_request(Request $request)
    {
        if(BusinessSetting::where(['key'=>'refund_active_status'])->first()->value == false){
            return response()->json([
                'errors' => [
                    ['code' => 'order', 'message' => translate('You can not request for a refund')]
                ]
            ], 403);
        }
        $validator = Validator::make($request->all(), [
            'customer_reason' => 'required|string|max:254',
            'refund_method'=>'nullable|string|max:100',
            'customer_note'=>'nullable|string|max:65535',
            'image.*' => 'nullable|max:2048',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $order = Order::where(['user_id' => $request->user()->id, 'id' => $request['order_id']])->Notpos()->first();
        if(!$order){
                return response()->json([
                    'errors' => [
                        ['code' => 'order', 'message' => translate('messages.not_found')]
                    ]
                ], 404);
        }

        else if ($order->order_status == 'delivered' && $order->payment_status == 'paid') {

            $id_img_names = [];
            if ($request->has('image')) {
                foreach ($request->file('image') as $img) {
                    $image_name = Helpers::upload(dir:'refund/', format:'png', image:$img);
                    array_push($id_img_names, $image_name);
                }
                $images = json_encode($id_img_names);
            } else {
                $images = json_encode([]);
                // return response()->json(['message' => 'no_image'], 200);
            }

            $refund_amount = round($order->order_amount - $order->delivery_charge- $order->dm_tips , config('round_up_to_digit'));

            $refund = new Refund();
            $refund->order_id = $order->id;
            $refund->user_id = $order->user_id;
            $refund->order_status= $order->order_status;
            $refund->refund_status= 'pending';
            $refund->refund_method= $request->refund_method ?? 'wallet';
            $refund->customer_reason= $request->customer_reason;
            $refund->customer_note= $request->customer_note;
            $refund->refund_amount= $refund_amount;
            $refund->image = $images;
            $refund->save();

            $order->order_status = 'refund_requested';
            $order->refund_requested = now();
            $order->save();
            // Helpers::send_order_notification($order);



            $admin = Admin::where('role_id',1)->first();
            $mail_status = Helpers::get_mail_status('refund_request_mail_status_admin');
            try {
                if (config('mail.status') && $admin['email'] && $mail_status == '1') {
                    Mail::to($admin['email'])->send(new RefundRequest($order->id));
                }
            } catch (\Exception $ex) {
                info($ex->getMessage());
            }


            return response()->json(['message' => translate('messages.refund_request_placed_successfully')], 200);
        }

        return response()->json([
            'errors' => [
                ['code' => 'order', 'message' => translate('messages.you_can_not_request_for_refund_after_delivery')]
            ]
        ], 403);
    }

    public function update_payment_method(Request $request)
    {
        $config=Helpers::get_business_settings('cash_on_delivery');
        if($config['status']==0)
        {
            return response()->json([
                'errors' => [
                    ['code' => 'cod', 'message' => translate('messages.Cash on delivery order not available at this time')]
                ]
            ], 403);
        }
        $order = Order::where(['user_id' => $request?->user()?->id, 'id' => $request['order_id']])->Notpos()->first();
        if ($order) {
            Order::where(['user_id' => $request?->user()?->id, 'id' => $request['order_id']])->update([
                'payment_method' => 'cash_on_delivery', 'order_status'=>'pending', 'pending'=> now()
            ]);

            // $fcm_token = $request->user()->cm_firebase_token;
            // $value = Helpers::order_status_update_message('pending');
            try {

        $this->order_notification($request,$order->id);
                // if ($value) {
                //     $data = [
                //         'title' =>translate('messages.order_placed_successfully'),
                //         'description' => $value,
                //         'order_id' => $order->id,
                //         'image' => '',
                //         'type'=>'order_status',
                //     ];
                //     Helpers::send_push_notif_to_device($fcm_token, $data);
                //     DB::table('user_notifications')->insert([
                //         'data'=> json_encode($data),
                //         'user_id'=>$request->user()->id,
                //         'created_at'=>now(),
                //         'updated_at'=>now()
                //     ]);
                // }
                // if($order->order_type == 'delivery' && !$order->scheduled)
                // {
                //     $data = [
                //         'title' =>translate('messages.order_placed_successfully'),
                //         'description' => translate('messages.new_order_push_description'),
                //         'order_id' => $order->id,
                //         'image' => '',
                //     ];
                //     Helpers::send_push_notif_to_topic($data, $order->restaurant->zone->deliveryman_wise_topic, 'order_request');
                // }

            } catch (\Exception $e) {
                info($e->getMessage());
            }
            return response()->json(['message' => translate('messages.payment').' '.translate('messages.method').' '.translate('messages.updated_successfully')], 200);
        }
        return response()->json([
            'errors' => [
                ['code' => 'order', 'message' => translate('messages.not_found')]
            ]
        ], 404);
    }

    public function cancellation_reason(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required',
            'offset' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $limit = $request->query('limit', 1);
        $offset = $request->query('offset', 1);

        $reasons = OrderCancelReason::where('status', 1)->when($request->type,function($query) use($request){
        return $query->where('user_type',$request->type);
        })
        ->paginate($limit, ['*'], 'page', $offset);
        $data = [
            'total_size' => $reasons->total(),
            'limit' => $limit,
            'offset' => $offset,
            'reasons' => $reasons->items(),
        ];
        return response()->json($data, 200);
    }


    public function food_list(Request $request){

        $validator = Validator::make($request->all(), [
            'food_id' => 'required',
        ]);

        $food_ids= json_decode($request['food_id'], true);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $product = Food::active()->whereIn('id',$food_ids)->get();
        return response()->json(Helpers::product_data_formatting($product, true, false, app()->getLocale()), 200);
    }


    public function order_notification(Request $request,$order_id){
        $order = Order::where('user_id', $request->user()->id)->where('id',$order_id)->first();
        if($order && $order->payment_method != 'digital_payment'){
            Helpers::send_order_notification($order);
        }
        return response()->json();
    }
}
