<?php

namespace App\Http\Controllers\Admin;

use App\Models\Food;
use App\Models\Zone;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Refund;
use App\Models\Category;
use App\Mail\RefundRequest;
use App\Models\DeliveryMan;
use App\Models\OrderDetail;
use App\Models\Translation;
use App\Mail\RefundRejected;
use App\Models\ItemCampaign;
use App\Models\RefundReason;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\BusinessSetting;
use App\Scopes\RestaurantScope;
use App\CentralLogics\OrderLogic;
use App\CentralLogics\CouponLogic;
use Illuminate\Support\Facades\DB;
use App\CentralLogics\CustomerLogic;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Mail;
use Rap2hpoutre\FastExcel\FastExcel;
use MatanYadaev\EloquentSpatial\Objects\Point;

class OrderController extends Controller
{
    public function list($status, Request $request)
    {
        if (session()->has('zone_filter') == false) {
            session()->put('zone_filter', 0);
        }

        if (session()->has('order_filter')) {
            $request = json_decode(session('order_filter'));
        }

        Order::where(['checked' => 0])->update(['checked' => 1]);

        $orders = Order::with(['customer', 'restaurant'])
            ->when(isset($request->zone), function ($query) use ($request) {
                return $query->whereHas('restaurant', function ($q) use ($request) {
                    return $q->whereIn('zone_id', $request->zone);
                });
            })
            ->when($status == 'scheduled', function ($query) {
                return $query->whereRaw('created_at <> schedule_at');
            })
            ->when($status == 'searching_for_deliverymen', function ($query) {
                return $query->SearchingForDeliveryman();
            })
            ->when($status == 'pending', function ($query) {
                return $query->Pending();
            })
            ->when($status == 'accepted', function ($query) {
                return $query->AccepteByDeliveryman();
            })
            ->when($status == 'processing', function ($query) {
                return $query->Preparing();
            })
            ->when($status == 'food_on_the_way', function ($query) {
                return $query->FoodOnTheWay();
            })
            ->when($status == 'delivered', function ($query) {
                return $query->Delivered();
            })
            ->when($status == 'canceled', function ($query) {
                return $query->Canceled();
            })
            ->when($status == 'failed', function ($query) {
                return $query->failed();
            })
            ->when($status == 'requested', function ($query) {
                return $query->Refund_requested();
            })
            ->when($status == 'rejected', function ($query) {
                return $query->Refund_request_canceled();
            })
            ->when($status == 'refunded', function ($query) {
                return $query->Refunded();
            })
            ->when($status == 'scheduled', function ($query) {
                return $query->Scheduled();
            })
            ->when($status == 'on_going', function ($query) {
                return $query->Ongoing();
            })
            ->when(($status != 'all' && $status != 'scheduled' && $status != 'canceled' && $status != 'refund_requested' && $status != 'refunded' && $status != 'delivered' && $status != 'failed'), function ($query) {
                return $query->OrderScheduledIn(30);
            })
            ->when(isset($request->vendor), function ($query) use ($request) {
                return $query->whereHas('restaurant', function ($query) use ($request) {
                    return $query->whereIn('id', $request->vendor);
                });
            })
            ->when(isset($request->orderStatus) && $status == 'all', function ($query) use ($request) {
                return $query->whereIn('order_status', $request->orderStatus);
            })
            ->when(isset($request->scheduled) && $status == 'all', function ($query) {
                return $query->scheduled();
            })
            ->when(isset($request->order_type), function ($query) use ($request) {
                return $query->where('order_type', $request->order_type);
            })
            ->when($request?->from_date != null && $request?->to_date != null, function ($query) use ($request) {
                return $query->whereBetween('created_at', [$request->from_date . " 00:00:00", $request->to_date . " 23:59:59"]);
            })
            ->Notpos()
            ->hasSubscriptionToday()
            ->orderBy('schedule_at', 'desc')
            ->paginate(config('default_pagination'));

        $orderstatus = $request?->orderStatus ?? [];
        $scheduled =  $request?->scheduled ?? 0;
        $vendor_ids =  $request?->vendor ?? [];
        $zone_ids = $request?->zone ?? [];
        $from_date =  $request?->from_date ?? null;
        $to_date = $request?->to_date ?? null;
        $order_type =  $request?->order_type ?? null;
        $total = $orders->total();

        return view('admin-views.order.list', compact('orders', 'status', 'orderstatus', 'scheduled', 'vendor_ids', 'zone_ids', 'from_date', 'to_date', 'total', 'order_type'));
    }

    public function export_orders($status, $type, Request $request)
    {
        if (session()->has('zone_filter') == false) {
            session()->put('zone_filter', 0);
        }

        if (session()->has('order_filter')) {
            $request = json_decode(session('order_filter'));
        }

        $orders = Order::with(['customer', 'restaurant'])
            ->when(isset($request->zone), function ($query) use ($request) {
                return $query->whereHas('restaurant', function ($q) use ($request) {
                    return $q->whereIn('zone_id', $request->zone);
                });
            })
            ->when($status == 'scheduled', function ($query) {
                return $query->whereRaw('created_at <> schedule_at');
            })
            ->when($status == 'searching_for_deliverymen', function ($query) {
                return $query->SearchingForDeliveryman();
            })
            ->when($status == 'pending', function ($query) {
                return $query->Pending();
            })
            ->when($status == 'accepted', function ($query) {
                return $query->AccepteByDeliveryman();
            })
            ->when($status == 'processing', function ($query) {
                return $query->Preparing();
            })
            ->when($status == 'food_on_the_way', function ($query) {
                return $query->FoodOnTheWay();
            })
            ->when($status == 'delivered', function ($query) {
                return $query->Delivered();
            })
            ->when($status == 'canceled', function ($query) {
                return $query->Canceled();
            })
            ->when($status == 'failed', function ($query) {
                return $query->failed();
            })
            ->when($status == 'requested', function ($query) {
                return $query->Refund_requested();
            })
            ->when($status == 'rejected', function ($query) {
                return $query->Refund_request_canceled();
            })
            ->when($status == 'refunded', function ($query) {
                return $query->Refunded();
            })
            ->when($status == 'requested', function ($query) {
                return $query->Refund_requested();
            })
            ->when($status == 'rejected', function ($query) {
                return $query->Refund_request_canceled();
            })
            ->when($status == 'scheduled', function ($query) {
                return $query->Scheduled();
            })
            ->when($status == 'on_going', function ($query) {
                return $query->Ongoing();
            })
            ->when(($status != 'all' && $status != 'scheduled' && $status != 'canceled' && $status != 'refund_requested' && $status != 'refunded' && $status != 'delivered' && $status != 'failed'), function ($query) {
                return $query->OrderScheduledIn(30);
            })
            ->when(isset($request->vendor), function ($query) use ($request) {
                return $query->whereHas('restaurant', function ($query) use ($request) {
                    return $query->whereIn('id', $request->vendor);
                });
            })
            ->when(isset($request->orderStatus) && $status == 'all', function ($query) use ($request) {
                return $query->whereIn('order_status', $request->orderStatus);
            })
            ->when(isset($request->scheduled) && $status == 'all', function ($query) {
                return $query->scheduled();
            })
            ->when(isset($request->order_type), function ($query) use ($request) {
                return $query->where('order_type', $request->order_type);
            })
            ->when($request?->from_date != null && $request?->to_date != null, function ($query) use ($request) {
                return $query->whereBetween('created_at', [$request->from_date . " 00:00:00", $request->to_date . " 23:59:59"]);
            })
            ->Notpos()
            ->orderBy('schedule_at', 'desc')
            ->get();

            if($type == 'csv') {
                return (new FastExcel(OrderLogic::format_export_data($orders)))->download('Orders.csv');
            }

        return (new FastExcel(OrderLogic::format_export_data($orders)))->download('Orders.xlsx');
    }

    public function dispatch_list($status, Request $request)
    {

        if (session()->has('order_filter')) {
            $request = json_decode(session('order_filter'));
            $zone_ids =  $request?->zone ?? 0;
        }
        $key = explode(' ', $request['search']);

        Order::where(['checked' => 0])->update(['checked' => 1]);

        $orders = Order::with(['customer', 'restaurant'])
            ->when(isset($request->zone), function ($query) use ($request) {
                return $query->whereHas('restaurant', function ($query) use ($request) {
                    return $query->whereIn('zone_id', $request->zone);
                });
            })
            ->when($status == 'searching_for_deliverymen', function ($query) {
                return $query->SearchingForDeliveryman();
            })
            ->when($status == 'on_going', function ($query) {
                return $query->Ongoing();
            })
            ->when(isset($request->vendor), function ($query) use ($request) {
                return $query->whereHas('restaurant', function ($query) use ($request) {
                    return $query->whereIn('id', $request->vendor);
                });
            })
            ->when(isset($key),function($query) use($key){
                $query->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('id', 'like', "%{$value}%")
                            ->orWhere('order_status', 'like', "%{$value}%")
                            ->orWhere('transaction_reference', 'like', "%{$value}%");
                    }
                });
            })
            ->when($request?->from_date != null && $request?->to_date != null, function ($query) use ($request) {
                return $query->whereBetween('created_at', [$request->from_date . " 00:00:00", $request->to_date . " 23:59:59"]);
            })

            ->Notpos()
            ->OrderScheduledIn(30)
            ->orderBy('schedule_at', 'desc')
            ->paginate(config('default_pagination'));

        $orderstatus = $request?->orderStatus ?? [];
        $scheduled =   $request?->scheduled ?? 0;
        $vendor_ids =  $request?->vendor ?? [];
        $zone_ids =   $request?->zone ?? [];
        $from_date =   $request?->from_date ?? null;
        $to_date =   $request?->to_date ?? null;
        $total = $orders->total();

        return view('admin-views.order.distaptch_list', compact('orders', 'status', 'orderstatus', 'scheduled', 'vendor_ids', 'zone_ids', 'from_date', 'to_date', 'total'));
    }

    public function refund_settings()
    {
        $refund_active_status = BusinessSetting::where(['key'=>'refund_active_status'])->first();
        $reasons=RefundReason::orderBy('id', 'desc')
        ->paginate(config('default_pagination'));

        return view('admin-views.refund.index', compact('refund_active_status','reasons'));
    }

    public function refund_reason(Request $request)
    {
        $request->validate([
            'reason' => 'required|max:191',
        ]);

        if($request->reason[array_search('default', $request->lang)] == '' ){
            Toastr::error(translate('default_reason_is_required'));
            return back();
            }


        $reason = new RefundReason();
        $reason->reason = $request->reason[array_search('default', $request->lang)];
        $reason->save();
        $data = [];
        $default_lang = str_replace('_', '-', app()->getLocale());
        foreach ($request->lang as $index => $key) {
            if($default_lang == $key && !($request->reason[$index])){
                if ($key != 'default') {
                    array_push($data, array(
                        'translationable_type' => 'App\Models\RefundReason',
                        'translationable_id' => $reason->id,
                        'locale' => $key,
                        'key' => 'reason',
                        'value' => $reason->reason,
                    ));
                }
            }else{
                if ($request->reason[$index] && $key != 'default') {
                    array_push($data, array(
                        'translationable_type' => 'App\Models\RefundReason',
                        'translationable_id' => $reason->id,
                        'locale' => $key,
                        'key' => 'reason',
                        'value' => $request->reason[$index],
                    ));
                }
            }
        }
        Translation::insert($data);
        Toastr::success(translate('Refund Reason Added Successfully'));
        return back();
    }
    public function reason_edit(Request $request)
    {
        $request->validate([
            'reason' => 'required|max:100',
        ]);
        if($request->reason[array_search('default', $request->lang1)] == '' ){
            Toastr::error(translate('default_reason_is_required'));
            return back();
            }
        $refund_reason = RefundReason::findOrFail($request->reason_id);
        $refund_reason->reason = $request->reason[array_search('default', $request->lang1)];
        $refund_reason->save();

        $default_lang = str_replace('_', '-', app()->getLocale());
        foreach ($request->lang1 as $index => $key) {
            if($default_lang == $key && !($request->reason[$index])){
                if ($key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\RefundReason',
                            'translationable_id' => $refund_reason->id,
                            'locale' => $key,
                            'key' => 'reason'
                        ],
                        ['value' => $refund_reason->reason]
                    );
                }
            }else{
                if ($request->reason[$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\RefundReason',
                            'translationable_id' => $refund_reason->id,
                            'locale' => $key,
                            'key' => 'reason'
                        ],
                        ['value' => $request->reason[$index]]
                    );
                }
            }
        }


        Toastr::success(translate('Refund Reason Updated Successfully'));
        return back();
    }
    public function reason_status(Request $request)
    {
        $refund_reason = RefundReason::findOrFail($request->id);
        $refund_reason->status = $request->status;
        $refund_reason->save();
        Toastr::success(translate('messages.status_updated'));
        return back();
    }
    public function reason_delete(Request $request)
    {
        $refund_reason = RefundReason::findOrFail($request->id);
        $refund_reason?->translations()?->delete();
        $refund_reason->delete();
        Toastr::success(translate('Refund Reason Deleted Successfully'));
        return back();
    }

    public function order_refund_rejection(Request $request){

        $request->validate([
            'order_id' => 'required',
            'admin_note' => 'nullable|string|max:65535',
        ]);
            Refund::where('order_id', $request->order_id)->update([
                'order_status' => 'refund_request_canceled',
                'admin_note'=>$request?->admin_note ?? null,
                'refund_status'=>'rejected',
                'refund_method'=>'canceled',
            ]);

            $order = Order::Notpos()->find($request->order_id);
            $order->order_status = 'refund_request_canceled';
            $order->refund_request_canceled = now();
            $order->save();


            try {
                $mail_status = Helpers::get_mail_status('refund_request_deny_mail_status_user');
                if(config('mail.status') && $order?->customer?->email && $mail_status == '1'){
                    Mail::to($order->customer->email)->send(new RefundRejected($order->id));
                }
            } catch (\Throwable $th) {
                info($th->getMessage());
                Toastr::error(translate('messages.Failed_to_send_mail'));
            }

            Toastr::success(translate('Refund Rejection Successfully'));

            if (!Helpers::send_order_notification($order)) {
                Toastr::warning(translate('messages.push_notification_faild'));
            }
            return back();

    }


    public function refund_mode()
    {
        $refund_mode = BusinessSetting::where('key', 'refund_active_status')->first();
            BusinessSetting::updateOrCreate(
            ['key' => 'refund_active_status'],
            [ 'value' => $refund_mode?->value == 1 ? 0 : 1, ]
        );

        if ( $refund_mode?->value){
            Toastr::success(translate('messages.Maintenance_is_off'));
            return back();
        }
        Toastr::success(translate('messages.Maintenance_is_on'));
        return back();
    }



    public function details(Request $request, $id)
    {
        $order = Order::with(['subscription','subscription.schedule_today','details', 'refund','restaurant' => function ($query) {
            return $query->withCount('orders');
        }, 'customer' => function ($query) {
            return $query->withCount('orders');
        }, 'delivery_man' => function ($query) {
            return $query->withCount('orders');
        }, 'details.food' => function ($query) {
            return $query->withoutGlobalScope(RestaurantScope::class);
        }, 'details.campaign' => function ($query) {
            return $query->withoutGlobalScope(RestaurantScope::class);
        }])->where(['id' => $id])->Notpos()->first();
        if (isset($order)) {
            if (($order?->restaurant?->self_delivery_system && $order?->restaurant?->restaurant_model == 'commission') ||
            ($order?->restaurant?->restaurant_model == 'subscription' &&   $order?->restaurant?->restaurant_sub?->self_delivery == 1)  ) {
                $deliveryMen = DeliveryMan::where('restaurant_id', $order->restaurant_id)->available()->active()->get();

            } else {
                if($order->restaurant !== null){
                    $deliveryMen = DeliveryMan::where('zone_id', $order->restaurant->zone_id)->where(function($query)use($order){
                            $query->where('vehicle_id',$order->vehicle_id)->orWhereNull('vehicle_id');
                    })
                    ->available()->active()->get();
                } else{
                    $deliveryMen = DeliveryMan::where('zone_id', '=', NULL)->where('vehicle_id',$order->vehicle_id)->active()->get();
                }
            }

            $category = $request->query('category_id', 0);
            // $sub_category = $request->query('sub_category', 0);
            $categories = Category::active()->get();
            $keyword = $request->query('keyword', false);
            $key = explode(' ', $keyword);
            $products = Food::withoutGlobalScope(RestaurantScope::class)->where('restaurant_id', $order->restaurant_id)
                ->when($category, function ($query) use ($category) {
                    $query->whereHas('category', function ($q) use ($category) {
                        return $q->whereId($category)->orWhere('parent_id', $category);
                    });
                })
                ->when($keyword, function ($query) use ($key) {
                    return $query->where(function ($q) use ($key) {
                        foreach ($key as $value) {
                            $q->orWhere('name', 'like', "%{$value}%");
                        }
                    });
                })
                ->latest()->paginate(10);
            $editing = false;
            if ($request->session()->has('order_cart')) {
                $cart = session()->get('order_cart');
                if (count($cart) > 0 && $cart[0]->order_id == $order->id) {
                    $editing = true;
                } else {
                    session()->forget('order_cart');
                }
            }

            $deliveryMen = Helpers::deliverymen_list_formatting($deliveryMen);

            return view('admin-views.order.order-view', compact('order', 'deliveryMen', 'categories', 'products', 'category', 'keyword', 'editing'));
        } else {
            Toastr::info(translate('messages.no_more_orders'));
            return back();
        }
    }

    public function search(Request $request)
    {
        $key = explode(' ', $request['search']);
        $orders = Order::where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('id', 'like', "%{$value}%")
                    ->orWhere('order_status', 'like', "%{$value}%")
                    ->orWhere('transaction_reference', 'like', "%{$value}%");
            }
        })->Notpos()->limit(50)->get();
        return response()->json([
            'view' => view('admin-views.order.partials._table', compact('orders'))->render()
        ]);
    }

    public function status(Request $request)
    {
        $request->validate([
            'reason'=>'required_if:order_status,canceled'
        ]);
        $order = Order::Notpos()->find($request->id);
        if (in_array($order->order_status, ['refunded', 'failed'])) {
            Toastr::warning(translate('messages.you_can_not_change_the_status_of_a_completed_order'));
            return back();
        }


        if (in_array($order->order_status, ['refund_requested']) && BusinessSetting::where(['key'=>'refund_active_status'])->first()?->value == false)  {
            Toastr::warning(translate('Refund Option is not active. Please active it from Refund Settings'));
            return back();
        }

        if ($order['delivery_man_id'] == null && $request->order_status == 'out_for_delivery') {
            Toastr::warning(translate('messages.please_assign_deliveryman_first'));
            return back();
        }

        if ($request->order_status == 'delivered' && $order['transaction_reference'] == null && !in_array($order['payment_method'],['cash_on_delivery','wallet'])) {
            Toastr::warning(translate('messages.add_your_paymen_ref_first'));
            return back();
        }

        if ($request->order_status == 'delivered') {

            if ($order->transaction  == null || isset($order->subscription_id)) {
                if ($order->payment_method == "cash_on_delivery") {
                    if ($order->order_type == 'take_away') {
                        $ol = OrderLogic::create_transaction(order: $order, received_by: 'restaurant', status: null);
                    } else if ($order->delivery_man_id) {
                        $ol =  OrderLogic::create_transaction(order: $order, received_by: 'deliveryman', status: null);
                    } else if ($order->user_id) {
                        $ol =  OrderLogic::create_transaction(order: $order, received_by: false, status: null);
                    }
                } else {
                    $ol = OrderLogic::create_transaction(order: $order, received_by: 'admin', status: null);
                }
                if (!$ol) {
                    Toastr::warning(translate('messages.faield_to_create_order_transaction'));
                    return back();
                }
            } else if ($order->delivery_man_id) {
                $order->transaction->update(['delivery_man_id' => $order->delivery_man_id]);
            }

            $order->payment_status = 'paid';
            if ($order->delivery_man) {
                $dm = $order->delivery_man;
                $dm->increment('order_count');
                $dm->current_orders = $dm->current_orders > 1 ? $dm->current_orders - 1 : 0;
                $dm->save();
            }
            $order->details->each(function ($item, $key) {
                if ($item->food) {
                    $item->food->increment('order_count');
                }
            });
            $order->customer ?  $order->customer->increment('order_count') : '';
            $order->restaurant->increment('order_count');
            }


            else if ($request->order_status == 'refunded' && BusinessSetting::where('key', 'refund_active_status')->first()?->value == 1) {
                    if ($order->payment_status == "unpaid") {
                        Toastr::warning(translate('messages.you_can_not_refund_a_cod_order'));
                        return back();
                    }
                    if (isset($order->delivered)) {
                        $rt = OrderLogic::refund_order($order);

                        if (!$rt) {
                            Toastr::warning(translate('messages.faield_to_create_order_transaction'));
                            return back();
                        }
                    }
                    $refund_method= $request->refund_method  ?? 'manual';
                    $wallet_status= BusinessSetting::where('key','wallet_status')->first()?->value;
                $refund_to_wallet= BusinessSetting::where('key', 'wallet_add_refund')->first()?->value;

                if ($order->payment_status == "paid" && $wallet_status == 1 && $refund_to_wallet==1) {
                    $refund_amount = round($order->order_amount - $order->delivery_charge - $order->dm_tips, config('round_up_to_digit'));
                    CustomerLogic::create_wallet_transaction(user_id:$order->user_id, amount:$refund_amount,transaction_type: 'order_refund',referance: $order->id);
                    Toastr::info(translate('Refunded amount added to customer wallet'));
                    $refund_method='wallet';
                }else{
                        Toastr::warning(translate('Customer Wallet Refund is not active.Plase Manage the Refund Amount Manually'));
                        $refund_method=$request->refund_method  ?? 'manual';
                    }

                    Refund::where('order_id', $order->id)->update([
                        'order_status' => 'refunded',
                        'admin_note'=>$request->admin_note ?? null,
                        'refund_status'=>'approved',
                        'refund_method'=>$refund_method,
                    ]);

                    Helpers::increment_order_count($order->restaurant);

                    if ($order->delivery_man) {
                        $dm = $order->delivery_man;
                        $dm->current_orders = $dm->current_orders > 1 ? $dm->current_orders - 1 : 0;
                        $dm->save();
                    }



                    try {
                        $mail_status = Helpers::get_mail_status('refund_order_mail_status_user');
                        if(config('mail.status') && $order?->customer?->email && $mail_status == '1'){
                            Mail::to($order->customer->email)->send(new \App\Mail\RefundedOrderMail($order->id));
                        }
                    } catch (\Throwable $th) {
                        info($th->getMessage());
                        Toastr::error(translate('messages.Failed_to_send_mail'));
                    }

            }

        else if ($request->order_status == 'canceled') {
            if (in_array($order->order_status, ['delivered', 'canceled', 'refund_requested', 'refunded', 'failed', 'picked_up']) || $order->picked_up) {
                Toastr::warning(translate('messages.you_can_not_cancel_a_completed_order'));
                return back();
            }
            // if(isset($order->confirmed) && isset($order->subscription_id)){
            //     Toastr::warning(translate('messages.you_can_not_cancel_this_subscription_order_because_it_is_already_confirmed'));
            //     return back();
            // }
            if(isset($order->subscription_id)){
                $order->subscription()->update(['status' => 'canceled']);
                if($order?->subscription->log){
                    $order->subscription->log()->update([
                        'order_status' => $request->status,
                        'canceled' => now(),
                        ]);
                }
            }
            $order->cancellation_reason = $request->reason;
            $order->canceled_by = 'admin';

            if ($order->delivery_man) {
                $dm = $order->delivery_man;
                $dm->current_orders = $dm->current_orders > 1 ? $dm->current_orders - 1 : 0;
                $dm->save();
            }

            Helpers::increment_order_count($order->restaurant);
            OrderLogic::refund_before_delivered($order);
        }
        $order->order_status = $request->order_status;
        if($request->order_status == 'processing') {
            $order->processing_time = ($request?->processing_time) ? $request->processing_time : explode('-', $order['restaurant']['delivery_time'])[0];
        }
        $order[$request->order_status] = now();
        $order->save();

        OrderLogic::update_subscription_log($order);
        if (!Helpers::send_order_notification($order)) {
            Toastr::warning(translate('messages.push_notification_faild'));
        }
        Toastr::success(translate('messages.order') . translate('messages.status_updated'));
        return back();
    }

    public function add_delivery_man($order_id, $delivery_man_id)
    {
        if ($delivery_man_id == 0) {
            return response()->json(['message' => translate('messages.deliveryman') . ' ' . translate('messages.not_found')], 404);
        }
        $order = Order::Notpos()->with(['subscription.schedule_today'])->find($order_id);
        $deliveryman = DeliveryMan::where('id', $delivery_man_id)->available()->active()->first();
        if ($order->delivery_man_id == $delivery_man_id) {
            return response()->json(['message' => translate('messages.order_already_assign_to_this_deliveryman')], 400);
        }
        if ($deliveryman) {
            if ($deliveryman->current_orders >= config('dm_maximum_orders')) {
                return response()->json(['message' => translate('messages.dm_maximum_order_exceed_warning')], 400);
            }
            $cash_in_hand = $deliveryman?->wallet?->collected_cash ?? 0;
            $dm_max_cash=BusinessSetting::where('key','dm_max_cash_in_hand')->first();
            $value=  $dm_max_cash?->value ?? 0;
            if($order->payment_method == "cash_on_delivery" && (($cash_in_hand+$order->order_amount) >= $value)){
                return response()->json(['message'=>translate('delivery man max cash in hand exceeds')], 400);
            }
            if ($order->delivery_man) {
                $dm = $order->delivery_man;
                $dm->current_orders = $dm->current_orders > 1 ? $dm->current_orders - 1 : 0;
                // $dm->decrement('assigned_order_count');
                $dm->save();

                $data = [
                    'title' => translate('messages.order_push_title'),
                    'description' => translate('messages.you_are_unassigned_from_a_order'),
                    'order_id' => '',
                    'image' => '',
                    'type' => 'assign'
                ];
                Helpers::send_push_notif_to_device($dm->fcm_token, $data);

                DB::table('user_notifications')->insert([
                    'data' => json_encode($data),
                    'delivery_man_id' => $dm->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            $order->delivery_man_id = $delivery_man_id;
            $order->order_status = in_array($order->order_status, ['pending', 'confirmed']) ? 'accepted' : $order->order_status;
            $order->accepted = now();
            $order->save();
            OrderLogic::update_subscription_log($order);
            $deliveryman->current_orders = $deliveryman->current_orders + 1;
            $deliveryman->save();
            $deliveryman->increment('assigned_order_count');
            $value = Helpers::order_status_update_message('accepted');
            try {
                if ($value && $order->customer) {
                    $fcm_token = $order->customer->cm_firebase_token;
                    $data = [
                        'title' => translate('messages.order_push_title'),
                        'description' => $value,
                        'order_id' => $order['id'],
                        'image' => '',
                        'type' => 'order_status'
                    ];
                    Helpers::send_push_notif_to_device($fcm_token, $data);

                    DB::table('user_notifications')->insert([
                        'data' => json_encode($data),
                        'user_id' => $order->customer->id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
                $data = [
                    'title' => translate('messages.order_push_title'),
                    'description' => translate('messages.you_are_assigned_to_a_order'),
                    'order_id' => $order['id'],
                    'image' => '',
                    'type' => 'assign'
                ];
                Helpers::send_push_notif_to_device($deliveryman->fcm_token, $data);
                DB::table('user_notifications')->insert([
                    'data' => json_encode($data),
                    'delivery_man_id' => $deliveryman->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } catch (\Exception $e) {
                info($e->getMessage());
                Toastr::warning(translate('messages.push_notification_faild'));
            }
            return response()->json([], 200);
        }
        return response()->json(['message' => translate('Deliveryman not available!')], 400);
    }
    public function update_shipping(Request $request, Order $order)
    {

        $request->validate([
            'contact_person_name' => 'required',
            'address_type' => 'required',
            'contact_person_number' => 'required',
        ]);
        if ($request->latitude && $request->longitude) {
            $zone = Zone::where('id', $order->restaurant->zone_id)->whereContains('coordinates', new Point($request->latitude, $request->longitude, POINT_SRID))->first();
            if (!$zone) {
                Toastr::error(translate('messages.out_of_coverage'));
                return back();
            }
        }
        $address = [
            'contact_person_name' => $request->contact_person_name,
            'contact_person_number' => $request->contact_person_number,
            'address_type' => $request->address_type,
            'address' => $request->address,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
            'floor' => $request->floor,
            'house' => $request->house,
            'road' => $request->road,
        ];

        $order->delivery_address = json_encode($address);
        $order->save();
        Toastr::success(translate('messages.delivery_address_updated'));
        return back();
    }

    public function generate_invoice($id)
    {
        $order = Order::Notpos()->where('id', $id)->first();
        return view('admin-views.order.invoice', compact('order'));
    }

    public function add_payment_ref_code(Request $request, $id)
    {
        $request->validate([
            'transaction_reference' => 'max:30'
        ]);
        Order::Notpos()->where(['id' => $id])->update([
            'transaction_reference' => $request['transaction_reference']
        ]);

        Toastr::success(translate('messages.payment_reference_code_is_added'));
        return back();
    }

    public function restaurnt_filter($id)
    {
        session()->put('restaurnt_filter', $id);
        return back();
    }

    public function filter(Request $request)
    {
        $request->validate([
            'from_date' => 'required_if:to_date,true',
            'to_date' => 'required_if:from_date,true',
        ]);
        session()->put('order_filter', json_encode($request->all()));
        return back();
    }
    public function filter_reset(Request $request)
    {
        session()->forget('order_filter');
        return back();
    }

    public function add_to_cart(Request $request)
    {

        if ($request->item_type == 'food') {
            $product = Food::withOutGlobalScope(RestaurantScope::class)->find($request->id);
        } else {
            $product = ItemCampaign::withOutGlobalScope(RestaurantScope::class)->find($request->id);
        }

        $data = new OrderDetail();
        if ($request->order_details_id) {
            $data['id'] = $request->order_details_id;
        }

        $data['food_id'] = $request->item_type == 'food' ? $product->id : null;
        $data['item_campaign_id'] = $request->item_type == 'campaign' ? $product->id : null;
        $data['food'] = $request->item_type == 'food' ? $product : null;
        $data['item_campaign'] = $request->item_type == 'campaign' ? $product : null;
        $data['order_id'] = $request->order_id;
        $variations = [];
        $price = 0;
        $addon_price = 0;
        $variation_price=0;

        $product_variations = json_decode($product->variations, true);
        if ($request->variations && count($product_variations)) {
            foreach($request->variations  as $key=> $value ){

                if($value['required'] == 'on' &&  isset($value['values']) == false){
                    return response()->json([
                        'data' => 'variation_error',
                        'message' => translate('Please select items from') . ' ' . $value['name'],
                    ]);
                }
                if(isset($value['values'])  && $value['min'] != 0 && $value['min'] > count($value['values']['label'])){
                    return response()->json([
                        'data' => 'variation_error',
                        'message' => translate('Please select minimum ').$value['min'].translate(' For ').$value['name'].'.',
                    ]);
                }
                if(isset($value['values']) && $value['max'] != 0 && $value['max'] < count($value['values']['label'])){
                    return response()->json([
                        'data' => 'variation_error',
                        'message' => translate('Please select maximum ').$value['max'].translate(' For ').$value['name'].'.',
                    ]);
                }
            }
            $variation_data = Helpers::get_varient($product_variations, $request->variations);
            $variation_price = $variation_data['price'];
            $variations = $variation_data['variations'];

        }
        $price = $product->price + $variation_price;
        $data['variation'] = json_encode($variations);
        $data['variant'] = '';
        // $data['variation_price'] = $variation_price;
        $data['quantity'] = $request['quantity'];
        $data['price'] = $price;
        $data['status'] = true;
        $data['discount_on_food'] = Helpers::product_discount_calculate(product:$product, price:$price,restaurant: $product->restaurant);
        $data["discount_type"] = "discount_on_product";
        $data["tax_amount"] = Helpers::tax_calculate(food:$product,price:$price);
        $add_ons = [];
        $add_on_qtys = [];

        if ($request['addon_id']) {
            foreach ($request['addon_id'] as $id) {
                $addon_price += $request['addon-price' . $id] * $request['addon-quantity' . $id];
                $add_on_qtys[] = $request['addon-quantity' . $id];
            }
            $add_ons = $request['addon_id'];
        }

        $addon_data = Helpers::calculate_addon_price(addons:\App\Models\AddOn::withOutGlobalScope(App\Scopes\RestaurantScope::class)->whereIn('id', $add_ons)->get(), add_on_qtys:$add_on_qtys);
        $data['add_ons'] = json_encode($addon_data['addons']);
        $data['total_add_on_price'] = $addon_data['total_add_on_price'];
        $cart = $request->session()->get('order_cart', collect([]));

        if (isset($request->cart_item_key)) {
            $cart[$request->cart_item_key] = $data;
            return response()->json([
                'data' => 2
            ]);
        } else {
            $cart->push($data);
        }

        return response()->json([
            'data' => 0
        ]);
    }

    public function remove_from_cart(Request $request)
    {
        $cart = $request->session()->get('order_cart', collect([]));
        $cart[$request->key]->status = false;
        $request->session()->put('order_cart', $cart);

        return response()->json([], 200);
    }

    public function edit(Request $request, Order $order)
    {
        $order = Order::withoutGlobalScope(RestaurantScope::class)->with(['details', 'restaurant' => function ($query) {
            return $query->withCount('orders');
        }, 'customer' => function ($query) {
            return $query->withCount('orders');
        }, 'delivery_man' => function ($query) {
            return $query->withCount('orders');
        }, 'details' => function ($query) {
            return $query->with(['food'=> function ($q) {
                return $q->withoutGlobalScope(RestaurantScope::class);
            },'campaign' => function ($q) {
                return $q->withoutGlobalScope(RestaurantScope::class);
            }]);
        }])->where(['id' => $order->id])->Notpos()->first();
        if ($request->cancle) {
            if ($request->session()->has(['order_cart'])) {
                session()->forget(['order_cart']);
            }
            return back();
        }
        $cart = collect([]);
        foreach ($order->details as $details) {
            unset($details['food_details']);
            $details['status'] = true;
            $cart->push($details);
        }

        if ($request->session()->has('order_cart')) {
            session()->forget('order_cart');
        } else {
            $request->session()->put('order_cart', $cart);
        }
        return back();
    }

    public function update(Request $request, Order $order)
    {
        $order = Order::with(['details', 'restaurant' => function ($query) {
            return $query->withCount('orders');
        }, 'customer' => function ($query) {
            return $query->withCount('orders');
        }, 'delivery_man' => function ($query) {
            return $query->withCount('orders');
        }, 'details.food' => function ($query) {
            return $query->withoutGlobalScope(RestaurantScope::class);
        }, 'details.campaign' => function ($query) {
            return $query->withoutGlobalScope(RestaurantScope::class);
        }])->where(['id' => $order->id])->Notpos()->first();

        if (!$request->session()->has('order_cart')) {
            Toastr::error(translate('messages.order_data_not_found'));
            return back();
        }
        $cart = $request->session()->get('order_cart', collect([]));
        $restaurant = $order?->restaurant;
        $coupon = null;
        $total_addon_price = 0;
        $product_price = 0;
        $restaurant_discount_amount = 0;
        if ($order->coupon_code) {
            $coupon = Coupon::where(['code' => $order->coupon_code])->first();
        }
        foreach ($cart as $c) {

            if ($c['status'] == true) {
                unset($c['food']);
                unset($c['status']);
                unset($c['item_campaign']);
                if ($c['item_campaign_id'] != null) {
                    $product = ItemCampaign::find($c['item_campaign_id']);
                    if ($product) {

                        $price = $c['price'];

                        $product = Helpers::product_data_formatting($product);

                        $c->food_details = json_encode($product);
                        $c->updated_at = now();
                        // $variation_data = Helpers::get_varient($product->variations, $c['variations']);
                        // $variations = $variation_data['variations'];
                        if (isset($c->id)) {
                            OrderDetail::where('id', $c->id)->update(
                                [
                                    'food_id' => $c->food_id,
                                    'item_campaign_id' => $c->item_campaign_id,
                                    'food_details' => $c->food_details,
                                    'quantity' => $c->quantity,
                                    'price' => $c->price,
                                    'tax_amount' => $c->tax_amount,
                                    'discount_on_food' => $c->discount_on_food,
                                    'discount_type' => $c->discount_type,
                                    'variant' => $c->variant,
                                    'variation' => $c->variation,
                                    'add_ons' => $c->add_ons,
                                    'total_add_on_price' => $c->total_add_on_price,
                                    'updated_at' => $c->updated_at
                                ]
                            );
                        } else {
                            $c->save();
                        }

                        $total_addon_price += $c['total_add_on_price'];
                        $product_price += $price * $c['quantity'];
                        $restaurant_discount_amount += $c['discount_on_food'] * $c['quantity'];
                    } else {
                        Toastr::error(translate('messages.food_not_found'));
                        return back();
                    }
                } else {
                    $product = Food::find($c['food_id']);
                    if ($product) {

                        $price = $c['price'];

                        $product = Helpers::product_data_formatting($product);

                        $c->food_details = json_encode($product);
                        $c->updated_at = now();
                        if (isset($c->id)) {
                            OrderDetail::where('id', $c->id)->update(
                                [
                                    'food_id' => $c->food_id,
                                    'item_campaign_id' => $c->item_campaign_id,
                                    'food_details' => $c->food_details,
                                    'quantity' => $c->quantity,
                                    'price' => $c->price,
                                    'tax_amount' => $c->tax_amount,
                                    'discount_on_food' => $c->discount_on_food,
                                    'discount_type' => $c->discount_type,
                                    'variant' => $c->variant,
                                    'variation' => $c->variation,
                                    'add_ons' => $c->add_ons,
                                    'total_add_on_price' => $c->total_add_on_price,
                                    'updated_at' => $c->updated_at
                                ]
                            );
                        } else {
                            $c->save();
                        }
                        $total_addon_price += $c['total_add_on_price'];
                        $product_price += $price * $c['quantity'];
                        $restaurant_discount_amount += $c['discount_on_food'] * $c['quantity'];
                    } else {
                        Toastr::error(translate('messages.food_not_found'));
                        return back();
                    }
                }
            } else {
                $c->delete();
            }
        }

        $restaurant_discount = Helpers::get_restaurant_discount($restaurant);
        if (isset($restaurant_discount)) {
            if ($product_price + $total_addon_price < $restaurant_discount['min_purchase']) {
                $restaurant_discount_amount = 0;
            }

            if ($restaurant_discount_amount > $restaurant_discount['max_discount']) {
                $restaurant_discount_amount = $restaurant_discount['max_discount'];
            }
        }
        $order->delivery_charge = $order->original_delivery_charge;
        if ($coupon) {
            if ($coupon->coupon_type == 'free_delivery') {
                $order->delivery_charge = 0;
                $coupon = null;
            }
        }

        if ($order->restaurant->free_delivery) {
            $order->delivery_charge = 0;
        }

        $coupon_discount_amount = $coupon ? CouponLogic::get_discount(coupon:$coupon, order_amount: $product_price + $total_addon_price - $restaurant_discount_amount) : 0;
        $total_price = $product_price + $total_addon_price - $restaurant_discount_amount - $coupon_discount_amount;

        $tax = $restaurant->tax;
        $total_tax_amount = ($tax > 0) ? (($total_price * $tax) / 100) : 0;
        if ($restaurant->minimum_order > $product_price + $total_addon_price) {
            Toastr::error(translate('messages.you_need_to_order_at_least', ['amount' => $restaurant->minimum_order . ' ' . Helpers::currency_code()]));
            return back();
        }

        $free_delivery_over = BusinessSetting::where('key', 'free_delivery_over')->first()?->value;
        if (isset($free_delivery_over)) {
            if ($free_delivery_over <= $product_price + $total_addon_price - $coupon_discount_amount - $restaurant_discount_amount) {
                $order->delivery_charge = 0;
            }
        }
        $total_order_ammount = $total_price + $total_tax_amount + $order->delivery_charge;
        $adjustment = $order->order_amount - $total_order_ammount;

        $order->coupon_discount_amount = $coupon_discount_amount;
        $order->restaurant_discount_amount = $restaurant_discount_amount;
        $order->total_tax_amount = $total_tax_amount;
        $order->order_amount = $total_order_ammount;
        $order->adjusment = $adjustment;
        $order->edited = true;
        $order->save();
        session()->forget('order_cart');
        Toastr::success(translate('messages.order_updated_successfully'));
        return back();
    }

    public function quick_view(Request $request)
    {
        $product = $product = Food::withOutGlobalScope(RestaurantScope::class)->findOrFail($request->product_id);
        $item_type = 'food';
        $order_id = $request->order_id;

        return response()->json([
            'success' => 1,
            'view' => view('admin-views.order.partials._quick-view', compact('product', 'order_id', 'item_type'))->render(),
        ]);
    }

    public function quick_view_cart_item(Request $request)
    {
        $cart_item = session('order_cart')[$request->key];
        $order_id = $request->order_id;
        $item_key = $request->key;
        $product = $cart_item->food ? $cart_item->food : $cart_item->campaign;
        $item_type = $cart_item->food ? 'food' : 'campaign';

        return response()->json([
            'success' => 1,
            'view' => view('admin-views.order.partials._quick-view-cart-item', compact('order_id', 'product', 'cart_item', 'item_key', 'item_type'))->render(),
        ]);
    }

    public function orders_export(Request $request, $type, $restaurant_id){

        $orders = Order::with('customer')->where(['restaurant_id'=>$restaurant_id])->get();
        if($type == 'csv'){
            return (new FastExcel(Helpers::export_restaurant_orders($orders)))->download('Orders.csv');
        }
        return (new FastExcel(Helpers::export_restaurant_orders($orders)))->download('Orders.xlsx');

    }

    public function restaurant_order_search(Request $request){
        $key = explode(' ', $request['search']);
        $orders = Order::where(['restaurant_id'=>$request->restaurant_id])
                        ->where(function($q) use($key){
                            foreach ($key as $value){
                                $q->orWhere('id', 'like', "%{$value}%");

                            }
                        })
                        ->whereHas('customer', function($q) use($key){
                            foreach($key as $value){
                                $q->orWhere('f_name', 'like', "%{$value}%")
                                ->orWhere('l_name', 'like', "%{$value}%");
                            }
                        })->get();

        return response()->json([
            'view'=> view('admin-views.vendor.view.partials._orderTable', compact('orders'))->render()
        ]);
    }
}
