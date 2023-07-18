@php
$max_processing_time = $order->restaurant?explode('-', $order->restaurant['delivery_time'])[0]:0;
@endphp
@extends('layouts.admin.app')


    @if($order->scheduled == 1)
        @section('scheduled')
    @elseif( in_array($order->order_status, ['confirmed','processing','handover']) )
        @section('processing')
    @elseif( in_array($order->order_status, ['refunded','refund_requested','refund_request_canceled']) )
        @section('refunded')
    @else
        @section($order->order_status)
    @endif

    active
    @endsection

@section('title', translate('Order Details'))
<style>
    .select2-container--open {
    z-index: 99999999999999;
}
</style>
@section('content')
    <?php $campaign_order = isset($order->details[0]->campaign) ? true : false;
        $subscription = isset($order->subscription_id) ? true : false;
        $reasons=\App\Models\OrderCancelReason::where('status', 1)->where('user_type' ,'admin' )->get();
        $tax_included =0;
    ?>

    <div class="content container-fluid initial-39">
        <!-- Page Header -->
        <div class="page-header d-print-none">

            <h1 class="page-header-title text-capitalize">
                <div class="card-header-icon d-inline-flex mr-2 img">
                    <img src="{{asset('/public/assets/admin/img/orders.png')}}" alt="public">
                </div>
                <span>
                    {{translate('messages.order')}} {{translate('messages.details')}}
                </span>
                <div class="d-flex ml-auto">
                    <a class="btn btn-icon btn-sm badge-soft-primary rounded-circle justify-content-center mr-1"
                        href="{{ route('admin.order.details', [$order['id'] - 1]) }}" data-toggle="tooltip"
                        data-placement="top" title="{{ translate('Previous order') }}">
                        <i class="tio-chevron-left m-0"></i>
                    </a>
                    <a class="btn btn-icon btn-sm badge-soft-primary rounded-circle justify-content-center"
                        href="{{ route('admin.order.details', [$order['id'] + 1]) }}" data-toggle="tooltip"
                        data-placement="top" title="{{ translate('Next order') }}">
                        <i class="tio-chevron-right m-0"></i>
                    </a>
                </div>
            </h1>

            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <div class="mt-2">
                        @php
                        $refund_amount = $order->order_amount - $order->delivery_charge - $order->dm_tips;
                        $refund= \App\Models\BusinessSetting::where(['key'=>'refund_active_status'])->first()->value
                        @endphp
                    </div>
                </div>
            </div>
        </div>
        <!-- End Page Header -->

        <div class="row g-1" id="printableArea">
            <div class="col-lg-8 order-print-area-left">
                <!-- Card -->
                <div class="card mb-3 mb-lg-5">
                    <!-- Header -->
                    <div class="card-header border-0 align-items-start flex-wrap">
                        <div class="order-invoice-left">
                            <h1 class="page-header-title mt-2">
                                <span class="font--max-sm">{{ translate('messages.order') }} {{translate('id')}} {{translate('#')}}{{ $order['id'] }}</span>
                                <!-- Static -->
                                {{-- <span class="badge badge-soft-primary px-2 ml-2">{{translate('pos')}}</span> --}}
                                @if ($order->edited)
                                    <span class="badge badge-soft-danger text-capitalize my-2 ml-2">
                                        {{ translate('messages.edited') }}
                                    </span>
                                @endif
                                <!-- Static -->
                                <div class="d-sm-none d-flex flex-wrap ml-auto align-items-center justify-content-end initial-39-2">
                                    @if (!$subscription && !$editing && in_array($order->order_status, ['pending', 'confirmed', 'processing', 'accepted']))
                                        @if ($order->restaurant)
                                            <button class="btn bn--primary btn-outline-primary m-1 print--btn" type="button" onclick="edit_order()">
                                                <i class="tio-edit"></i> <span>{{ translate('messages.edit') }} {{ translate('messages.order') }}</span>
                                            </button>
                                        @endif
                                    @endif
                                    <a class="btn btn--primary m-1 print--btn" href={{ route('admin.order.generate-invoice', [$order['id']]) }}>
                                        <i class="tio-print mr-1"></i> <span>{{ translate('messages.print') }} {{ translate('messages.invoice') }}</span>
                                    </a>
                                </div>
                            </h1>
                            <span class="mt-2 d-block">
                                <i class="tio-date-range"></i>
                                {{-- {{  Carbon\Carbon::parse($order['created_at'])->locale(app()->getLocale())->translatedFormat('d M Y ' . config('timeformat')) }} --}}

                                {{ date('d M Y ' . config('timeformat'), strtotime($order['created_at'])) }}

                            </span>
                            @if ($subscription)
                            <span>
                                <strong class="text-primary"> {{ translate('messages.subscription_order') }}</strong>
                            </span>
                            <br>
                            @endif
                            @if ($order->schedule_at && ($order->scheduled || $subscription))
                                <span>
                                    <span>{{ translate('messages.scheduled_at') }} :</span>
                                    <strong class="text-warning">
                                        {{-- {{  Carbon\Carbon::parse($order['schedule_at'])->locale(app()->getLocale())->translatedFormat('d M Y ' . config('timeformat')) }} --}}
                                        {{ date('d M Y ' . config('timeformat'), strtotime($order['schedule_at'])) }}
                                    </strong>
                                </span>
                            @endif
                            @if (isset($order->restaurant))
                                <h6 class="mt-2 pt-1 mb-2">
                                    <i class="tio-shop"></i>
                                    {{ translate('messages.restaurant') }} : <label
                                        class="badge badge-soft-info font-regular m-0">{{ Str::limit($order->restaurant->name, 25, '...') }}</label>
                                </h6>
                            @else
                                <h6 class="mt-2 pt-1 mb-2">
                                    <i class="tio-shop"></i>
                                    {{ translate('messages.restaurant') }} : <label
                                        class="badge badge-soft-danger font-regular m-0">{{ Str::limit(translate('messages.Restaurant deleted!'), 25, '...') }}</label>
                                </h6>
                            @endif
                                <h6 class="m-0">
                            @if ($campaign_order)
                                    <span class="badge badge-soft-primary ml-sm-3">
                                        {{ translate('messages.campaign_order') }}
                                    </span>
                            @endif
                                </h6>
                                <div class="hs-unfold mt-2">
                                    <button class="btn order--details-btn-sm btn--primary btn-outline-primary btn--sm" data-toggle="modal" data-target="#locationModal"><i
                                            class="tio-poi-outlined"></i> <span class="ml-1">{{ translate('messages.show_locations_on_map') }}</span> </button>
                                            {{-- @if ( $refund == true &&  $order->payment_status == 'paid' && $order->order_status != 'refunded' && isset($order->restaurant) )
                                        <button class="btn order--details-btn-sm btn--warning btn-outline-warning btn--sm mt-2"
                                            onclick="route_alert('{{ route('admin.order.status', ['id' => $order['id'], 'order_status' => 'refunded']) }}','{{ translate('messages.you_want_to_refund_this_order', ['amount' => $refund_amount . ' ' . \App\CentralLogics\Helpers::currency_code()]) }}', '{{ translate('messages.are_you_sure_want_to_refund') }}')"><i
                                                class="tio-money"></i> <span class="ml-1">{{ translate('messages.refund_this_order') }}</span> </button>
                                    @endif --}}
                                </div>
                            <div class="order--note mt-3">
                                @if($order['cancellation_reason'])
                                    <h6 class="text-capitalize my-2 ml-2">
                                        <span class="text-danger">{{ translate('messages.Cancelled_By') }} :</span>
                                        {{ $order['canceled_by'] }}
                                    </h6>
                                    <h6 class="my-2 ml-2">
                                        <span class="text-danger">{{ translate('messages.order_cancellation_reason') }} :</span>
                                        {{ $order['cancellation_reason'] }}
                                    </h6>
                                @endif
                                @if($order['cancellation_note'] )
                                <h6 class="my-2 ml-2">
                                    <strong class="text--title">{{ translate('messages.cancellation_note') }} :</strong>
                                    <span>{{ $order['cancellation_note'] }}</span>
                                </h6>
                                @endif
                                @if($order['order_note'])
                                <h6 class="my-2 ml-2">
                                    <strong class="text--title">{{ translate('messages.note') }} :</strong>
                                    <span>{{ $order['order_note'] }}</span>
                                </h6>
                                @endif

                                @if ($order['unavailable_item_note'])
                                <h6 class="my-2 ml-2">
                                    <span class="text--title">
                                        {{ translate('messages.if_item_is_not_available') }} :
                                    </span>
                                    {{ $order['unavailable_item_note'] }}
                                </h6>
                                @endif
                                @if ($order['delivery_instruction'])
                                    <h6 class="my-2 ml-2">
                                        <span class="text--title">
                                            {{ translate('messages.order') }} {{ translate('messages.delivery_instruction') }} :
                                        </span>
                                        {{ $order['delivery_instruction'] }}
                                    </h6>
                                @endif


                            </div>
                        </div>
                        <div class="order-invoice-right">
                            <div class="d-none d-sm-flex flex-wrap ml-auto align-items-center justify-content-end initial-39-1">
                                @if (!$subscription && !$editing && in_array($order->order_status, ['pending', 'confirmed', 'processing', 'accepted']))
                                    @if ($order->restaurant)
                                        <button class="btn bn--primary btn-outline-primary m-2 print--btn" type="button" onclick="edit_order()">
                                            <i class="tio-edit"></i> <span>{{ translate('messages.edit') }} {{ translate('messages.order') }}</span>
                                        </button>
                                    @endif
                                @endif
                                <a class="btn btn--primary m-2 print--btn" href={{ route('admin.order.generate-invoice', [$order['id']]) }}>
                                    <i class="tio-print mr-1"></i> <span>{{ translate('messages.print') }} {{ translate('messages.invoice') }}</span>
                                </a>
                            </div>
                            <div class="text-right mt-3 order-invoice-right-contents text-capitalize">
                                @if (isset($order->subscription))
                                        <h6>
                                            <span>{{ translate('messages.Subscription_status') }} :</span>
                                                @if ($order->subscription->status == 'active')
                                                <span class="badge badge-soft-success ">
                                                <span class="legend-indicator bg-success"></span>{{translate('messages.'.$order->subscription->status)}}
                                                </span>
                                                @elseif ($order->subscription->status == 'paused')
                                                <span class="badge badge-soft-primary">
                                                <span class="legend-indicator bg-danger"></span>{{translate('messages.'.$order->subscription->status)}}
                                                </span>
                                                @else
                                                <span class="badge badge-soft-primary ">
                                                <span class="legend-indicator bg-info"></span>{{translate('messages.'.$order->subscription->status)}}
                                                </span>
                                                @endif
                                        </h6>
                                @endif

                                <h6>
                                    @if (isset($order->subscription) && $order->subscription->status != 'canceled' )
                                        @php
                                        $order->order_status = $order->subscription_log ? $order->subscription_log->order_status : $order->order_status;
                                        @endphp
                                    @endif

                                    <span>{{ translate('messages.status') }} :</span>
                                    @if ($order['order_status'] == 'pending')
                                        <span class="badge badge-soft-success ml-2 ml-sm-3 text-capitalize font-medium">
                                            {{ translate('messages.pending') }}
                                        </span>
                                    @elseif($order['order_status'] == 'confirmed')
                                        <span class="badge badge-soft-success ml-2 ml-sm-3 text-capitalize font-medium">
                                            {{ translate('messages.confirmed') }}
                                        </span>
                                    @elseif($order['order_status'] == 'processing')
                                        <span class="badge badge-soft-warning ml-2 ml-sm-3 text-capitalize font-medium">
                                            {{ translate('messages.processing') }}
                                        </span>
                                    @elseif($order['order_status'] == 'picked_up')
                                        <span class="badge badge-soft-warning ml-2 ml-sm-3 text-capitalize font-medium">
                                            {{ translate('messages.out_for_delivery') }}
                                        </span>
                                    @elseif($order['order_status'] == 'delivered')
                                        <span class="badge badge-soft-success ml-2 ml-sm-3 text-capitalize font-medium">
                                            {{ translate('messages.delivered') }}
                                        </span>
                                    @elseif($order['order_status'] == 'failed')
                                        <span class="badge badge-soft-danger ml-2 ml-sm-3 text-capitalize font-medium">
                                            {{ translate('messages.payment') }}
                                            {{ translate('messages.failed') }}
                                        </span>
                                    @else
                                        <span class="badge badge-soft-danger ml-2 ml-sm-3 text-capitalize font-medium">
                                            {{ str_replace('_', ' ', $order['order_status']) }}
                                        </span>
                                    @endif



                                </h6>
                                <h6>
                                    <span>{{ translate('messages.payment') }} {{ translate('messages.method') }} :</span>
                                    <strong>{{   translate(str_replace('_', ' ', $order['payment_method']))  }}</strong>
                                </h6>
                                <h6>
                                    @if ($order['transaction_reference'] == null)
                                        <span>{{ translate('messages.reference') }} {{ translate('messages.code') }} :</span>
                                        @if (isset($order->restaurant))
                                            <button class="btn btn-outline-primary btn--primary btn-sm add--referal" data-toggle="modal"
                                                data-target=".bd-example-modal-sm">
                                                {{ translate('messages.add') }}
                                            </button>
                                        @endif
                                    @else
                                        <span>{{ translate('messages.reference') }} {{ translate('messages.code') }} :</span>
                                        <strong>{{ $order['transaction_reference'] }}</strong>
                                    @endif
                                </h6>
                                <h6>
                                    <span>{{ translate('messages.order') }} {{ translate('messages.type') }} :</span>
                                    <strong>{{   translate(str_replace('_', ' ', $order['order_type'])) }}</strong>
                                </h6>
                                @if ($order->coupon)
                                    <h6>
                                        <span>{{ translate('messages.coupon') }}</span>
                                        <label class="text-info">{{ $order->coupon_code }}
                                            ({{ translate('messages.' . $order->coupon->coupon_type) }})</label>
                                    </h6>
                                @endif
                                <h6>
                                    <span>{{translate('messages.payment')}} {{translate('messages.status')}} :</span>
                                    @if ($order['payment_status'] == 'paid')
                                        <strong class="text-success">{{ translate('messages.paid') }}</strong>
                                    @else
                                        <strong class="text-danger">{{ translate('messages.unpaid') }}</strong>
                                    @endif
                                </h6>
                                @if ($order->cutlery)
                                <h6>
                                    <span>{{ translate('cutlery') }}</span> <span>:</span>
                                        <span class="badge badge-soft-success ml-sm-3">
                                            {{ translate('messages.yes') }}
                                        </span>
                                </h6>
                                @else
                                <h6>
                                    <span>{{ translate('cutlery') }}</span> <span>:</span>
                                        <span class="badge badge-soft-danger ml-sm-3">
                                            {{ translate('messages.No') }}
                                        </span>
                                </h6>

                                @endif
                            </div>
                        </div>
                    </div>
                    <div>
                        <!-- food cart -->
                        @if ($editing && !$campaign_order)
                            <div class="mx-3 border-top">
                                <div class="my-3">
                                    <div class="search--button-wrapper">
                                        <div class="card-title"></div>
                                        <form id="search-form" class="search-form header-item min-240px">
                                            <!-- Search -->
                                            <div class="input--group input-group input-group-merge input-group-flush">
                                                <input id="datatableSearch" type="search"
                                                    value="{{ $keyword ? $keyword : '' }}" name="search"
                                                    class="form-control" placeholder="{{ translate('messages.Ex : Search Food Name') }}" aria-label="Search here">
                                                <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                                            </div>
                                            <!-- End Search -->
                                        </form>
                                        <div>
                                            <div class="input-group header-item">
                                                <select name="category" id="category" class="form-control js-select2-custom mx-1"
                                                    title="{{ translate('messages.select') }} {{ translate('messages.category') }}"
                                                    onchange="set_category_filter(this.value)">
                                                    <option value="">{{ translate('messages.all') }} {{ translate('messages.categories') }}
                                                    </option>
                                                    @foreach ($categories as $item)
                                                        <option value="{{ $item->id }}"
                                                            {{ $category == $item->id ? 'selected' : '' }}>{{ $item->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="row g-3" id="items">
                                    @foreach ($products as $product)
                                        <div class="order--item-box item-box col-auto">
                                            @include('admin-views.order.partials._single_product', [
                                                'product' => $product,
                                                'restaurant_data' => $order->restaurant,
                                            ])
                                            {{-- <hr class="d-sm-none"> --}}
                                        </div>
                                    @endforeach
                                </div>
                                <br>
                                {!! $products->withQueryString()->links() !!}
                            </div>
                        @endif
                    </div>
                    <!-- End Header -->

                    <!-- Body -->
                    <div class="card-body px-0">
                            <?php
                                $coupon = null;
                                $total_addon_price = 0;
                                $product_price = 0;
                                $restaurant_discount_amount = 0;
                                $del_c = $order['delivery_charge'];
                                if ($editing) {
                                    $del_c = $order['original_delivery_charge'];
                                }
                                if ($order->coupon_code) {
                                    $coupon = \App\Models\Coupon::where(['code' => $order['coupon_code']])->first();
                                    if ($editing && $coupon->coupon_type == 'free_delivery') {
                                        $del_c = 0;
                                        $coupon = null;
                                    }
                                }
                                $details = $order->details;
                                if ($editing) {
                                    $details = session('order_cart');
                                    // dd($details);

                                } else {
                                    foreach ($details as $key => $item) {
                                        $details[$key]->status = true;
                                    }
                                }
                            ?>
                    <div class="table-responsive">
                        <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table dataTable no-footer mb-0">
                            <thead class="thead-light">
                                <th>{{translate('sl')}}</th>
                                <th>{{translate('item_details')}}</th>
                                <th>{{translate('addons')}}</th>
                                <th class="text-right">{{translate('price')}}</th>
                            </thead>
                            <tbody>
                                @forelse ($details as $key => $detail)
                                @if (isset($detail->food_id) && $detail->status )
                                    <?php
                                    if (!$editing) {
                                        $deleted_food = $detail->food == null?1:0;
                                        $detail->food = json_decode($detail->food_details, true);
                                    }
                                    ?>
                                    <!-- Media -->
                                    <tr>
                                        <td>
                                            <!-- Static Count Number -->
                                            <div>
                                                {{$key+1}}
                                            </div>
                                            <!-- Static Count Number -->
                                        </td>
                                        <td>
                                            <div class="media media--sm">
                                            @if($editing )
                                                        @if($detail->food == null)
                                                            <div class="avatar avatar-xl mr-3 cursor-pointer">
                                                                <img class="img-fluid rounded"
                                                                src="{{ asset('public/assets/admin/img/100x100/food-default-image.png') }}"
                                                                onerror="this.src='{{ asset('public/assets/admin/img/100x100/food-default-image.png') }}'"
                                                                alt="Image Description">
                                                            @else
                                                            <div class="avatar avatar-xl mr-3 cursor-pointer"
                                                                onclick="quick_view_cart_item({{ $key }})"
                                                                title="{{ translate('messages.click_to_edit_this_item') }}">
                                                            <span class="avatar-status avatar-lg-status avatar-status-dark"><i
                                                                    class="tio-edit"></i></span>
                                                                <img class="img-fluid rounded"
                                                                src="{{ asset('storage/app/public/product') }}/{{ $detail->food['image'] }}"
                                                                onerror="this.src='{{ asset('public/assets/admin/img/100x100/food-default-image.png') }}'"
                                                                alt="Image Description">
                                                        @endif
                                                        </div>
                                                @else
                                                        @if(!$deleted_food)
                                                                <a class="avatar avatar-xl mr-3"
                                                                href="{{ route('admin.food.view', $detail->food['id']) }}">
                                                                    <img class="img-fluid rounded"
                                                                        src="{{ asset('storage/app/public/product') }}/{{ $detail->food['image'] }}"
                                                                        onerror="this.src='{{ asset('public/assets/admin/img/100x100/food-default-image.png') }}'"
                                                                        alt="Image Description">
                                                                    </a>
                                                            @else
                                                                <div class="avatar avatar-xl mr-3">
                                                                    <img class="img-fluid rounded"
                                                                        src="{{ asset('public/assets/admin/img/100x100/food-default-image.png') }}"
                                                                        onerror="this.src='{{ asset('public/assets/admin/img/100x100/food-default-image.png') }}'"
                                                                        alt="Image Description">
                                                                    </div>
                                                            @endif
                                                @endif
                                                <div class="media-body">
                                                    <div>
                                                        <strong class="line--limit-1"> {{ $detail->food == null?'Not Found':$detail->food['name'] }}</strong>
                                                        @if (isset($detail['variation']) ? json_decode($detail['variation'], true) : [] )
                                                            @foreach(json_decode($detail['variation'],true) as  $variation)
                                                                @if (isset($variation['name'])  && isset($variation['values']))
                                                                        <span class="d-block text-capitalize">
                                                                            <strong>
                                                                                {{  $variation['name']}} -
                                                                            </strong>
                                                                        </span>
                                                                        @foreach ($variation['values'] as $value)
                                                                                <span class="d-block text-capitalize">
                                                                                    &nbsp;   &nbsp; {{ $value['label']}} :
                                                                                    <strong>{{\App\CentralLogics\Helpers::format_currency( $value['optionPrice'])}}</strong>
                                                                                </span>
                                                                        @endforeach
                                                                @else
                                                                            @if (isset(json_decode($detail['variation'],true)[0]))
                                                                                <strong><u> {{  translate('messages.Variation') }} : </u></strong>
                                                                                @foreach(json_decode($detail['variation'],true)[0] as $key1 =>$variation)
                                                                                    <div class="font-size-sm text-body">
                                                                                        <span>{{$key1}} :  </span>
                                                                                        <span class="font-weight-bold">{{$variation}}</span>
                                                                                    </div>
                                                                                @endforeach
                                                                            @endif
                                                                @endif
                                                            @endforeach
                                                        @endif

                                                        <h6>
                                                            {{translate('qty')}} : {{ $detail['quantity'] }}
                                                        </h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                @foreach (json_decode($detail['add_ons'], true) as $key2 => $addon)
                                                    <div class="font-size-sm text-body">
                                                        <span>{{ Str::limit($addon['name'], 20, '...') }} : </span>
                                                        <span class="font-weight-bold">
                                                            {{ $addon['quantity'] }} x
                                                            {{ \App\CentralLogics\Helpers::format_currency($addon['price']) }}
                                                        </span>

                                                    </div>
                                                    @php($total_addon_price += $addon['price'] * $addon['quantity'])
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="text-right">
                                            <div>
                                                @php($amount = $detail['price'] * $detail['quantity'])
                                                <h5>{{ \App\CentralLogics\Helpers::format_currency($amount) }}</h5>
                                            </div>
                                        </td>
                                    </tr>
                                    @php($product_price += $amount)
                                    @php($restaurant_discount_amount += $detail['discount_on_food'] * $detail['quantity'])
                                    <!-- End Media -->
                                @elseif(isset($detail->item_campaign_id) && $detail->status)
                                {{-- {{ dd($detail) }} --}}
                                    <?php
                                    if (!$editing) {
                                        $deleted_food = $detail->campaign == null?1:0;
                                        $detail->campaign = json_decode($detail->food_details, true);
                                    }
                                    ?>
                                    <!-- Media -->
                                    <tr>
                                        <td>
                                            <!-- Static Count Number -->
                                            <div>
                                                {{$key+1}}
                                            </div>
                                            <!-- Static Count Number -->
                                        </td>
                                        <td>
                                            <div class="media media--sm">
                                                @if ($editing)
                                                @if($detail->campaign == null)
                                                <div class="avatar avatar-xl mr-3  cursor-pointer">
                                                            <img class="img-fluid rounded"
                                                            src="{{ asset('public/assets/admin/img/100x100/food-default-image.png') }}"
                                                            onerror="this.src='{{ asset('public/assets/admin/img/100x100/food-default-image.png') }}'"
                                                            alt="Image Description">
                                                            @else
                                                            <div class="avatar avatar-xl mr-3  cursor-pointer"
                                                            onclick="quick_view_cart_item({{ $key }})"
                                                            title="{{ translate('messages.click_to_edit_this_item') }}">
                                                            <span class="avatar-status avatar-lg-status avatar-status-dark">
                                                            <i class="tio-edit"></i></span>
                                                            <img class="img-fluid"
                                                            src="{{ asset('storage/app/public/campaign') }}/{{ $detail->campaign['image'] }}"
                                                            onerror="this.src='{{ asset('public/assets/admin/img/100x100/food-default-image.png') }}'"
                                                            alt="Image Description">
                                                            @endif
                                                    </div>
                                                @else
                                                    @if(!$deleted_food)
                                                    <a class="avatar avatar-xl mr-3"
                                                    href="{{ route('admin.campaign.view', ['item', $detail->campaign['id']]) }}">
                                                        <img class="img-fluid rounded"
                                                            src="{{ asset('storage/app/public/campaign') }}/{{ $detail->campaign['image'] }}"
                                                            onerror="this.src='{{ asset('public/assets/admin/img/100x100/food-default-image.png') }}'"
                                                            alt="Image Description">
                                                        </a>
                                                    @else
                                                        <div class="avatar avatar-xl mr-3">
                                                            <img class="img-fluid"
                                                                src="{{ asset('public/assets/admin/img/100x100/food-default-image.png') }}"
                                                                onerror="this.src='{{ asset('public/assets/admin/img/100x100/food-default-image.png') }}'"
                                                                alt="Image Description">
                                                        </div>
                                                    @endif
                                                @endif
                                                <div class="media-body">
                                                    <div>
                                                        <strong class="line--limit-1"> {{ $detail->campaign == null?'Not Found':$detail->campaign['name'] }}</strong>
                                                        @if (count(json_decode($detail['variation'], true)) > 0)
                                                        @foreach(json_decode($detail['variation'],true) as  $variation)
                                                        @if ( isset($variation['name'])  && isset($variation['values']))
                                                            <span class="d-block text-capitalize">
                                                                    <strong>
                                                                {{  $variation['name']}} -
                                                                    </strong>
                                                            </span>
                                                            @foreach ($variation['values'] as $value)
                                                            <span class="d-block text-capitalize">
                                                                &nbsp;   &nbsp; {{ $value['label']}} :
                                                                <strong>{{\App\CentralLogics\Helpers::format_currency( $value['optionPrice'])}}</strong>
                                                                </span>
                                                            @endforeach
                                                        @else
                                                            @if (isset(json_decode($detail['variation'],true)[0]))
                                                            <strong><u> {{  translate('messages.Variation') }} : </u></strong>
                                                                @foreach(json_decode($detail['variation'],true)[0] as $key1 =>$variation)
                                                                    <div class="font-size-sm text-body">
                                                                        <span>{{$key1}} :  </span>
                                                                        <span class="font-weight-bold">{{$variation}}</span>
                                                                    </div>
                                                                @endforeach
                                                            @endif
                                                                @break
                                                        @endif
                                                                @endforeach
                                                    @endif
                                                        <h6>
                                                            {{ $detail['quantity'] }} x {{ \App\CentralLogics\Helpers::format_currency($detail['price']) }}
                                                        </h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                @foreach (json_decode($detail['add_ons'], true) as $key2 => $addon)
                                                    @if ($key2 == 0)
                                                        <strong><u>{{ translate('messages.addons') }} : </u></strong>
                                                    @endif
                                                    <div class="font-size-sm text-body">
                                                        <span class="font-weight-bold">
                                                            {{ $addon['quantity'] }} x
                                                            {{ \App\CentralLogics\Helpers::format_currency($addon['price']) }}
                                                        </span>
                                                        <span>{{ Str::limit($addon['name'], 20, '...') }} : </span>
                                                    </div>
                                                    @php($total_addon_price += $addon['price'] * $addon['quantity'])
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="text-right">
                                            <div>
                                                @php($amount = $detail['price'] * $detail['quantity'])
                                                <h5>{{ \App\CentralLogics\Helpers::format_currency($amount) }}</h5>
                                            </div>
                                        </td>
                                    </tr>
                                    @php($product_price += $amount)
                                    @php($restaurant_discount_amount += $detail['discount_on_food'] * $detail['quantity'])
                                    <!-- End Media -->
                                @endif
                                    @empty
                                        <tr>
                                            <td>
                                                {{ translate('Food_was_deleted') }}

                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                        </table>
                        </div>
                        <hr class="mt-0">

                        <?php
                        $coupon_discount_amount = $order['coupon_discount_amount'];
                        $total_price = $product_price + $total_addon_price - $restaurant_discount_amount - $coupon_discount_amount;
                        $total_tax_amount = $order['total_tax_amount'];
                        $deliverman_tips = $order['dm_tips'];
                        $tax_a=$total_tax_amount;
                        if($order->tax_status == 'included'){
                            $tax_a=0;
                        }

                        if ($editing) {
                            $restaurant_discount = \App\CentralLogics\Helpers::get_restaurant_discount($order->restaurant);
                            if (isset($restaurant_discount)) {
                                if ($product_price + $total_addon_price < $restaurant_discount['min_purchase']) {
                                    $restaurant_discount_amount = 0;
                                }
                                if ($restaurant_discount_amount > $restaurant_discount['max_discount']) {
                                    $restaurant_discount_amount = $restaurant_discount['max_discount'];
                                }
                            }
                            $coupon_discount_amount = $coupon ? \App\CentralLogics\CouponLogic::get_discount($coupon, $product_price + $total_addon_price - $restaurant_discount_amount) : $order['coupon_discount_amount'];
                            $tax = $order->restaurant->tax;

                            $total_price = $product_price + $total_addon_price - $restaurant_discount_amount - $coupon_discount_amount;
                            $total_tax_amount = $tax > 0 ? ($total_price * $tax) / 100 : 0;
                            $total_tax_amount = round($total_tax_amount, 2);
                            $tax_a=$total_tax_amount;
                            $tax_included = \App\Models\BusinessSetting::where(['key'=>'tax_included'])->first() ?  \App\Models\BusinessSetting::where(['key'=>'tax_included'])->first()->value : 0;
                            if ($tax_included ==  1){
                                $tax_a=0;
                            }

                            $restaurant_discount_amount = round($restaurant_discount_amount, 2);
                            if ($order->restaurant->free_delivery) {
                                $del_c = 0;
                            }

                            $free_delivery_over = \App\Models\BusinessSetting::where('key', 'free_delivery_over')->first()->value;
                            if (isset($free_delivery_over)) {
                                if ($free_delivery_over <= $product_price + $total_addon_price - $coupon_discount_amount - $restaurant_discount_amount) {
                                    $del_c = 0;
                                }
                            }
                        } else {
                            $restaurant_discount_amount = $order['restaurant_discount_amount'];
                        }

                        ?>

                        <div class="row justify-content-end mb-3 px-4">
                            <div class="col-md-9 col-lg-8 initial-39-3">
                                <dl class="row text-sm-right">
                                    <dt class="col-6 text-capitalize">{{ translate('messages.items') }}
                                        {{ translate('messages.price') }}:</dt>
                                    <dd class="col-6 text-right">
                                        {{ \App\CentralLogics\Helpers::format_currency($product_price) }}</dd>
                                    <dt class="col-6">{{ translate('messages.addon') }}
                                        {{ translate('messages.cost') }}:
                                    </dt>
                                    <dd class="col-6 text-right">
                                        {{ \App\CentralLogics\Helpers::format_currency($total_addon_price) }}
                                        <hr>
                                    </dd>

                                    <dt class="col-6">{{ translate('messages.subtotal') }}
                                    @if ($order->tax_status == 'included' ||  $tax_included ==  1)
                                    ({{ translate('messages.TAX_Included') }})
                                    @endif
                                        :</dt>
                                    <dd class="col-6 text-right">
                                        {{ \App\CentralLogics\Helpers::format_currency($product_price + $total_addon_price) }}
                                    </dd>
                                    <dt class="col-6">{{ translate('messages.discount') }}:</dt>
                                    <dd class="col-6 text-right">
                                        - {{ \App\CentralLogics\Helpers::format_currency($restaurant_discount_amount) }}
                                    </dd>
                                    <dt class="col-6">{{ translate('messages.coupon') }}
                                        {{ translate('messages.discount') }}:</dt>
                                    <dd class="col-6 text-right">
                                        - {{ \App\CentralLogics\Helpers::format_currency($coupon_discount_amount) }}
                                    </dd>
                                    @if ($order->tax_status == 'excluded' || $order->tax_status == null  )
                                    {{-- @php($tax_a=0) --}}
                                    <dt class="col-6">{{ translate('messages.vat/tax') }}:</dt>
                                    <dd class="col-6 text-right">
                                        +
                                        {{ \App\CentralLogics\Helpers::format_currency($tax_a) }}
                                    </dd>
                                    @endif
                                    <dt class="col-6">{{ translate('DM Tips') }}</dt>
                                    <dd class="col-6 text-right">
                                        + {{ \App\CentralLogics\Helpers::format_currency($deliverman_tips) }}</dd>
                                    <dt class="col-6">{{ translate('Delivery Fee') }}</dt>
                                    <dd class="col-6 text-right">
                                        + {{ \App\CentralLogics\Helpers::format_currency($del_c) }}</dd>
                                    <dt class="col-6">{{ translate('messages.total') }}:</dt>
                                    <dd class="col-6 text-right">
                                        {{ \App\CentralLogics\Helpers::format_currency($product_price + $del_c + $tax_a + $total_addon_price + $deliverman_tips - $coupon_discount_amount - $restaurant_discount_amount) }}
                                    </dd>
                                </dl>
                                <!-- End Row -->
                            </div>
                            @if ($editing)
                            <div class="col-12 mt-3">
                                <div class="btn--container justify-content-end">
                                    <button class="btn btn-sm btn--danger" type="button"
                                        onclick="cancle_editing_order()">{{ translate('messages.cancel') }}</button>
                                    <button class="btn btn-sm btn--primary" type="button"
                                        onclick="update_order()">{{ translate('messages.submit') }}</button>
                                </div>
                            </div>
                            @endif
                        </div>
                        <!-- End Row -->
                    </div>
                    <!-- End Body -->
                </div>
                <!-- End Card -->
            </div>

            <div class="col-lg-4 order-print-area-right">

                @php ($refund= \App\Models\BusinessSetting::where(['key'=>'refund_active_status'])->first())

                @if (!empty($order->refund))
                    @if ( $order->order_status == 'refund_requested' || $order->order_status == 'refunded' || $order->order_status == 'refund_request_canceled')
                    <div class="card mb-2">
                        <div class="card-header border-0 d-block text-center pb-0">
                            <h4 class="m-0" >{{ translate('messages.Refund Request') }} </h4>
                            <span>
                                {{-- {{  Carbon\Carbon::parse($order->refund->created_at)->locale(app()->getLocale())->translatedFormat('d M Y ' . config('timeformat')) }} --}}
                                {{ date('d M Y ' . config('timeformat'), strtotime($order->refund->created_at))  }}
                            </span>

                            @if ($order->order_status == 'refund_requested')
                                <span class="badge __badge badge-primary __badge-abs">{{ translate('messages.pending') }}</span>
                                @elseif($order->order_status == 'refunded')
                                <span class="badge __badge badge-info __badge-abs">{{ translate('messages.refunded') }}</span>
                                @elseif($order->refund->order_status == 'refund_request_canceled')
                                <span class="badge __badge-pill badge-danger __badge-abs">{{ translate('messages.rejected') }}</span>
                            @endif

                        </div>
                        <div class="card-body pt-2">
                            <label class="input-label" for="exampleFormControlInput1">{{translate('messages.image')}} : </label>

                            <div class="row g-3">
                                @php( $data=  (isset($order->refund->image)) ? json_decode($order->refund->image,true)  : 0 )
                                @if ($data)
                                    @foreach($data as $key=>$img)
                                    <div class="col-3">
                                    <img class="img__aspect-1 rounded border w-100" data-toggle="modal" data-target="#imagemodal{{ $key }}" onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}"
                                    src="{{asset('storage/app/public/refund').'/'.$img}}">
                                </div>
                                    <div class="modal fade" id="imagemodal{{ $key }}" tabindex="-1" role="dialog"
                                    aria-labelledby="myModalLabel{{ $key }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="myModalLabel{{ $key }}">
                                                    {{ translate('Refund Image') }}</h4>
                                                <button type="button" class="close" data-dismiss="modal"><span
                                                        aria-hidden="true">&times;</span><span
                                                        class="sr-only">{{ translate('messages.cancel') }}</span></button>
                                            </div>
                                            <div class="modal-body">
                                                <img src="{{asset('storage/app/public/refund').'/'.$img}}"
                                                    class="initial--22 w-100">
                                            </div>
                                            <div class="modal-footer">
                                                <a class="btn btn-primary"
                                                    href="{{ route('admin.file-manager.download', base64_encode('public/refund/' . $img)) }}"><i
                                                        class="tio-download"></i> {{ translate('messages.download') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                    @endforeach
                                    @else
                                    <div class="col-3">
                                        <img class="img__aspect-1 rounded border w-100" onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}"
                                        src="{{ asset('public/assets/admin/img/160x160/img2.jpg') }}">
                                    </div>
                                @endif
                            </div>
                            <hr>


                            <ul class="delivery--information-single mt-3">
                                <li>
                                    <span class="name">{{ translate('Reason') }} </span>
                                    <span class="info">  {{ $order->refund->customer_reason }} </span>
                                </li>
                                <li>
                                    <span class="name">{{ translate('amount') }} </span>
                                    <span class="info"> {{ $order->refund->refund_amount }}</span>
                                </li>
                                <li>
                                    <span class="name">{{ translate('Method') }} </span>
                                    <span class="info"> {{ $order->refund->refund_method }}</span>
                                </li>
                                <li>
                                    <span class="name"> {{ translate('Status') }} </span>
                                    <span class="info"> {{ $order->refund->refund_status }}</span>
                                </li>
                                @if (isset($order->refund->admin_note))
                                <li>
                                    <span class="name">   {{ translate('Admin Note') }} </span>
                                    <span class="info">  {{ $order->refund->admin_note ?? 'No Note'}}</span>
                                </li>
                                @endif
                                @if (isset($order->refund->customer_note))
                                <li>
                                    <span class="name">  {{ translate('Customer Note') }} </span>
                                    <span class="info">   {{ $order->refund->customer_note ?? 'No Note' }}</span>
                                </li>
                                @endif
                                <hr class="w-100">
                            </ul>

                            <div class="btn--container refund--btn">
                                @if ($refund && $refund->value == true &&  $order->order_status == 'refund_requested')
                                <button type="button" class="btn btn--danger btn-outline-danger" data-toggle="modal" data-target="#refund_cancelation_note">
                                <i class="tio-money"></i> <span class="ml-1">{{ translate('messages.Cancel Refund') }}</span> </button>
                                @endif
                             @if ( $refund && $refund->value == true &&  $order->payment_status == 'paid' && $order->order_status != 'refunded'  )
                                    @if ($order->order_status == 'refunded' )

                                    @else
                                    <button class="btn btn--primary btn--sm"
                                    onclick="route_alert('{{ route('admin.order.status', ['id' => $order['id'],
                                    // 'refund_method'=> 'bank',
                                    'order_status' => 'refunded']) }}','{{ translate('messages.you_want_to_refund_this_order', ['amount' => $refund_amount . ' ' . \App\CentralLogics\Helpers::currency_code()]) }}', '{{ translate('messages.are_you_sure_want_to_refund') }}')"><i
                                        class="tio-money"></i> <span class="ml-1">{{ translate('messages.Refund') }}</span> </button>

                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                @endif
                <div class="row g-1">
                    @if (isset($order->restaurant))
                        <div class="col-12">
                            <!-- Card -->
                            <div class="card">
                                <!-- Header -->
                                    @if(empty($order->refund))
                                        @if (!in_array($order['order_status'], ['delivered','take_away','refund_requested','canceled','refunded','refund_request_canceled']) )
                                                <div class="card-header border-0 justify-content-center pt-4 pb-0">
                                                    <h4 class="card-header-title">{{translate('order_setup')}}</h4>
                                                </div>
                                                <!-- End Header -->
                                                <!-- Body -->
                                                <div class="card-body">
                                                <label class="form-label">{{translate('change_order_status')}}</label>
                                                <!-- Unfold -->
                                                <div>
                                                    <div class="dropdown">
                                                        @if (isset($order->restaurant))
                                                            <button class="form-control h--45px dropdown-toggle d-flex justify-content-between align-items-center" type="button"
                                                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false">
                                                                {{ translate('messages.status') }}
                                                            </button>
                                                        @endif
                                                            @php($order_delivery_verification = (bool) \App\Models\BusinessSetting::where(['key' => 'order_delivery_verification'])->first()->value)
                                                            <div class="dropdown-menu text-capitalize" aria-labelledby="dropdownMenuButton">
                                                                <a class="dropdown-item {{ $order['order_status'] == 'pending' ? 'active' : '' }}"
                                                                    onclick="route_alert('{{ route('admin.order.status', ['id' => $order['id'], 'order_status' => 'pending']) }}','{{ translate('Change status to pending ?') }}')"
                                                                    href="javascript:">{{ translate('messages.pending') }}</a>
                                                                <a class="dropdown-item {{ $order['order_status'] == 'confirmed' ? 'active' : '' }}"
                                                                    onclick="route_alert('{{ route('admin.order.status', ['id' => $order['id'], 'order_status' => 'confirmed']) }}','{{ translate('Change status to confirmed ?') }}')"
                                                                    href="javascript:">{{ translate('messages.confirmed') }}</a>

                                                                <a class="dropdown-item {{ $order['order_status'] == 'processing' ? 'active' : '' }}"
                                                                    onclick="route_alert('{{ route('admin.order.status', ['id' => $order['id'], 'order_status' => 'processing']) }}', '{{ translate('Change status to processing ?') }}','{{ translate('Are you sure?') }}', '{{ $max_processing_time }}')"
                                                                    href="javascript:">
                                                                    {{ translate('messages.processing') }}</a>

                                                                <a class="dropdown-item {{ $order['order_status'] == 'handover' ? 'active' : '' }}"
                                                                    onclick="route_alert('{{ route('admin.order.status', ['id' => $order['id'], 'order_status' => 'handover']) }}','{{ translate('Change status to handover ?') }}')"
                                                                    href="javascript:">{{ translate('messages.handover') }}</a>
                                                                <a class="dropdown-item {{ $order['order_status'] == 'picked_up' ? 'active' : '' }}"
                                                                    onclick="route_alert('{{ route('admin.order.status', ['id' => $order['id'], 'order_status' => 'picked_up']) }}','{{ translate('Change status to out for delivery ?') }}')"
                                                                    href="javascript:">{{ translate('messages.out_for_delivery') }}</a>
                                                                <a class="dropdown-item {{ $order['order_status'] == 'delivered' ? 'active' : '' }}"
                                                                    onclick="route_alert('{{ route('admin.order.status', ['id' => $order['id'], 'order_status' => 'delivered']) }}','{{ translate('Change status to delivered (payment status will be paid if not)?') }}')"
                                                                    href="javascript:">{{ translate('messages.delivered') }}</a>
                                                                <a class="dropdown-item {{ $order['order_status'] == 'canceled' ? 'active' : '' }}"
                                                                    onclick="cancelled_status()">{{ translate('messages.canceled') }}</a>
                                                            </div>
                                                    </div>
                                                </div>

                                                <!-- End Unfold -->
                                                <!-- Static -->
                                                @if ($order['order_type'] !=  'take_away' && !$order->delivery_man &&
                                                    (isset($order->restaurant) &&   ($order->restaurant->restaurant_model == 'commission'
                                                    && !$order->restaurant->self_delivery_system ) ||  ($order->restaurant->restaurant_model == 'subscription'
                                                    && isset($order->restaurant->restaurant_sub) && $order->restaurant->restaurant_sub->self_delivery == 0)))
                                                    <div class="w-100 text-center mt-4">
                                                        <button type="button" class="btn w-100 btn--primary font-regular" data-toggle="modal"
                                                            data-target="#myModal" data-lat='21.03' data-lng='105.85'>
                                                            <i class="tio-bike"></i> {{ translate('messages.assign_delivery_mam_manually') }}
                                                        </button>
                                                    </div>
                                                @endif
                                        @endif
                                    @endif
                                </div>

                                <!-- End Body -->
                            </div>
                            <!-- End Card -->
                        </div>
                    @endif
                    {{-- @if (($order['order_type'] != 'take_away' && $order->restaurant && !$order->restaurant->self_delivery_system && !$subscription) || $order->subscription_log) --}}

                            @if ($order->delivery_man && $order->delivery_man->type == 'zone_wise')
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body pt-2">
                                            <div class="mb-3 d-flex justify-content-between align-items-center">
                                                <h5 class="card-title">
                                                    <span class="card-header-icon">
                                                        <i class="tio-user"></i>
                                                    </span>
                                                    <span>
                                                        {{translate('deliveryman')}}
                                                    </span>
                                                </h5>
                                                @if ( !in_array($order['order_status'], ['delivered','refund_requested','canceled','refunded','refund_request_canceled']) &&  $order['order_type'] !=  'take_away' &&
                                                (isset($order->restaurant) && ($order->restaurant->restaurant_model == 'commission'
                                                        && !$order->restaurant->self_delivery_system ) ||  ($order->restaurant->restaurant_model == 'subscription'
                                                            && isset($order->restaurant->restaurant_sub) && $order->restaurant->restaurant_sub->self_delivery == 0)))

                                                    <span class="ml-auto text--primary position-relative p-2 cursor-pointer" data-toggle="modal" data-target="#myModal">
                                                        {{ translate('messages.change') }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="w-100 text-right initial-39-4">
                                            </div>
                                            <a class="media align-items-center  deco-none customer--information-single"
                                                href="{{ route('admin.delivery-man.preview', [$order->delivery_man['id']]) }}">
                                                <div class="avatar avatar-circle">
                                                    <img class="avatar-img w-75px" onerror="this.src='{{ asset('public/assets/admin/img/160x160/img3.png') }}'"
                                                        src="{{ asset('storage/app/public/delivery-man/' . $order->delivery_man->image) }}"
                                                        alt="Image Description">
                                                </div>
                                                <div class="media-body">
                                                    <strong class="d-block text--title">
                                                        {{ $order->delivery_man['f_name'] . ' ' . $order->delivery_man['l_name'] }}
                                                    </strong>
                                                    <span>
                                                        <strong class="text--title font-semibold">
                                                            {{ $order->delivery_man->orders_count }}
                                                        </strong>
                                                        {{ translate('messages.orders_delivered') }}
                                                    </span>
                                                    <span class="text--title font-semibold d-block">
                                                        <i class="tio-call-talking-quiet"></i> {{ $order->delivery_man['phone'] }}
                                                    </span>
                                                    <span class="text--title text-lowercase">
                                                        <i class="tio-email"></i> {{ $order->delivery_man['email'] }}
                                                    </span>
                                                </div>
                                            </a>
                                            <hr>
                                            @php($address = $order->dm_last_location)
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h5>{{ translate('messages.last') }} {{ translate('messages.location') }}</h5>
                                            </div>
                                            @if (isset($address))
                                                <span class="d-block">
                                                    <a target="_blank"
                                                        href="http://maps.google.com/maps?z=12&t=m&q=loc:{{ $address['latitude'] }}+{{ $address['longitude'] }}">
                                                        <i class="tio-poi"></i> {{ $address['location'] }}<br>
                                                    </a>
                                                </span>
                                            @else
                                                <span class="d-block text-lowercase qcont">
                                                    {{ translate('messages.location') . ' ' . translate('messages.not_found') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        {{-- @endif --}}

                        <div class="col-12">
                        <!-- Customer Card -->
                            <div class="card">
                                <div class="card-body pt-3">
                                    <!-- Header -->
                                    <h5 class="card-title mb-3">
                                        <span class="card-header-icon">
                                            <i class="tio-user"></i>
                                        </span>
                                        <span>{{ translate('messages.customer') }} {{ translate('messages.info') }}</span>
                                    </h5>
                                    <!-- End Header -->
                                        @if ($order->customer)
                                            <a class="media align-items-center deco-none customer--information-single"
                                                href="{{ route('admin.customer.view', [$order->customer['id']]) }}">
                                                <div class="avatar avatar-circle">
                                                    <img class="avatar-img"
                                                        onerror="this.src='{{ asset('public/assets/admin/img/160x160/img1.png') }}'"
                                                        src="{{ asset('storage/app/public/profile/' . $order->customer->image) }}"
                                                        alt="Image Description">

                                                </div>
                                                <div class="media-body">
                                                    <span
                                                        class="fz--14px text--title font-semibold text-hover-primary d-block">
                                                        {{ $order->customer['f_name'] . ' ' . $order->customer['l_name'] }}
                                                    </span>
                                                    <span>
                                                        <strong class="text--title font-semibold">{{ $order->customer->orders_count }}</strong>
                                                        {{ translate('messages.orders') }}
                                                    </span>
                                                    <span class="text--title font-semibold d-block">
                                                        <i class="tio-call-talking-quiet"></i> {{ $order->customer['phone'] }}
                                                    </span>
                                                    <span class="text--title">
                                                        <i class="tio-email"></i> {{ $order->customer['email'] }}
                                                    </span>
                                                </div>

                                            </a>
                                        @else
                                            {{translate('messages.customer_not_found')}}
                                        @endif
                                        @if ($order->delivery_address)
                                            <div class="pt-2"></div>
                                                <hr>
                                                @php($address = json_decode($order->delivery_address, true))
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h5 class="card-title">
                                                        <span class="card-header-icon">
                                                            <i class="tio-user"></i>
                                                        </span>
                                                        <span>{{ translate('delivery') }} {{ translate('messages.info') }}</span>
                                                    </h5>
                                                    @if (isset($address) && isset($order->restaurant))
                                                        <a class="link" data-toggle="modal" data-target="#shipping-address-modal" href="javascript:"><i class="tio-edit"></i></a>
                                                    @endif
                                                </div>
                                                @if (isset($address))
                                                    <span class="delivery--information-single mt-3">
                                                        <span class="name">{{ translate('name') }}</span>
                                                        <span class="info">{{ $address['contact_person_name'] }}</span>
                                                        <span class="name">{{ translate('contact') }}</span>
                                                        <a class="deco-none info" href="tel:{{ $address['contact_person_number'] }}">
                                                            <i class="tio-call-talking-quiet"></i>
                                                            {{ $address['contact_person_number'] }}</a>

                                                        <span class="name">{{ translate('Road') }} #</span>
                                                        <span class="info">{{ isset($address['road']) ? $address['road'] : '' }}</span>
                                                        <span class="name">{{ translate('House') }} #</span>
                                                        <span class="info">
                                                            {{ isset($address['house']) ? $address['house'] : '' }}
                                                        </span>
                                                        <span class="name">{{ translate('Floor') }}</span>
                                                        <span class="info">{{ isset($address['floor']) ? $address['floor'] : '' }}</span>

                                                        @if (isset($address['address']))
                                                            @if (empty($address['longitude']) && empty($address['latitude']) && isset($address['latitude']) && isset($address['longitude']))
                                                                <div class="mt-2 d-flex w-100">
                                                                    <a target="_blank"
                                                                        href="http://maps.google.com/maps?z=12&t=m&q=loc:{{ $address['latitude'] }}+{{ $address['longitude'] }}">
                                                                        <span><i class="tio-poi text--title"></i></span>
                                                                        <span class="info pl-2">{{ $address['address'] }}</span>
                                                                    </a>
                                                                </div>
                                                            @else
                                                                <div class="mt-2 d-flex w-100">
                                                                    <span><i class="tio-poi text--title"></i></span>
                                                                    <span class="info pl-2">{{ $address['address'] }}</span>
                                                                </div>
                                                            @endif
                                                        @endif
                                                    </span>
                                                @endif
                                        @endif
                                    </div>
                                <!-- End Body -->
                            </div>
                        </div>
                        <div class="col-12">
                            <!-- End Card -->
                            @if ($order->restaurant)
                                <!-- Restaurant Card -->
                                <div class="card">
                                    <!-- Body -->
                                    <div class="card-body">
                                        <!-- Header -->
                                        <h5 class="card-title mb-3">
                                            <span class="card-header-icon">
                                                <i class="tio-shop"></i>
                                            </span>
                                            <span>{{ translate('messages.restaurant') }} {{ translate('messages.info') }}</span>
                                        </h5>
                                        <!-- End Header -->
                                        <a class="media align-items-center deco-none resturant--information-single"
                                            href="{{ route('admin.restaurant.view', [$order->restaurant['id']]) }}">
                                            <div class="avatar avatar-circle">
                                                <img class="avatar-img w-75px"
                                                    onerror="this.src='{{ asset('public/assets/admin/img/100x100/restaurant-default-image.png') }}'"
                                                    src="{{ asset('storage/app/public/restaurant/' . $order->restaurant->logo) }}"
                                                    alt="Image Description">
                                            </div>
                                            <div class="media-body">
                                                <span class="text-body text-hover-primary text-break"></span>
                                                <span></span>


                                                <span class="fz--14px text--title font-semibold text-hover-primary d-block">
                                                    {{ $order->restaurant->name }}
                                                </span>
                                                <span>
                                                    <strong class="text--title font-semibold">
                                                        {{ $order->restaurant->orders_count }}
                                                    </strong>
                                                    {{ translate('messages.orders_served') }}
                                                </span>
                                                <span class="text--title font-semibold d-block">
                                                    <i class="tio-call-talking-quiet"></i> {{ $order->restaurant['phone'] }}
                                                </span>
                                                <span class="text--title">
                                                    <i class="tio-poi"></i> {{ $order->restaurant['address'] }}
                                                </span>
                                            </div>
                                        </a>
                                    </div>
                                    <!-- End Body -->
                                </div>
                            @endif
                            <!-- End Card -->
                        </div>
                </div>
            </div>
        </div>
        <!-- End Row -->
    </div>


    <!-- Modal -->
    <div class="modal fade" id="refund_cancelation_note" tabindex="-1" role="dialog" aria-labelledby="refund_cancelation_note_l" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="refund_cancelation_note_l">{{ translate('messages.add') }}
            {{ translate('Order Rejection') }} {{ translate('messages.Note') }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.refund.order_refund_rejection') }}" method="post">
                    @method('PUT')
                    @csrf
                    <input type="hidden" name="order_id" value="{{ $order->id }}" >
            <input type="text" class="form-control" name="admin_note" value="{{ old('admin_note') }}" placeholder="Fake Order">
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ translate('messages.Close') }} </button>
            <button type="submit" class="btn btn-primary"> {{ translate('messages.Submit') }} </button>
            </form>
            </div>
        </div>
        </div>
    </div>



    <!-- Modal -->
    <div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h4" id="mySmallModalLabel">{{ translate('messages.reference') }}
                        {{ translate('messages.code') }} {{ translate('messages.add') }}</h5>
                    <button type="button" class="btn btn-xs btn-icon btn-ghost-secondary" data-dismiss="modal"
                        aria-label="Close">
                        <i class="tio-clear tio-lg"></i>
                    </button>
                </div>

                <form action="{{ route('admin.order.add-payment-ref-code', [$order['id']]) }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <!-- Input Group -->
                        <div class="form-group">
                            <input type="text" name="transaction_reference" class="form-control"
                                placeholder="{{ translate('messages.Ex :') }} Code123" required>
                        </div>
                        <!-- End Input Group -->
                        <button class="btn btn-primary">{{ translate('messages.submit') }}</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <!-- End Modal -->

    <!-- Modal -->
    <div id="shipping-address-modal" class="modal fade" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalTopCoverTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <!-- Header -->
                <div class="modal-top-cover bg-dark text-center">
                    <figure class="position-absolute right-0 bottom-0 left-0 mb-n-1">
                        <svg preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
                            viewBox="0 0 1920 100.1">
                            <path fill="#fff" d="M0,0c0,0,934.4,93.4,1920,0v100.1H0L0,0z" />
                        </svg>
                    </figure>

                    <div class="modal-close">
                        <button type="button" class="btn btn-icon btn-sm btn-ghost-light" data-dismiss="modal"
                            aria-label="Close">
                            <svg width="16" height="16" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg">
                                <path fill="currentColor"
                                    d="M11.5,9.5l5-5c0.2-0.2,0.2-0.6-0.1-0.9l-1-1c-0.3-0.3-0.7-0.3-0.9-0.1l-5,5l-5-5C4.3,2.3,3.9,2.4,3.6,2.6l-1,1 C2.4,3.9,2.3,4.3,2.5,4.5l5,5l-5,5c-0.2,0.2-0.2,0.6,0.1,0.9l1,1c0.3,0.3,0.7,0.3,0.9,0.1l5-5l5,5c0.2,0.2,0.6,0.2,0.9-0.1l1-1 c0.3-0.3,0.3-0.7,0.1-0.9L11.5,9.5z" />
                            </svg>
                        </button>
                    </div>
                </div>
                <!-- End Header -->

                <div class="modal-top-cover-icon">
                    <span class="icon icon-lg icon-light icon-circle icon-centered shadow-soft">
                        <i class="tio-location-search"></i>
                    </span>
                </div>

                @if (isset($address))
                    <form action="{{ route('admin.order.update-shipping', [$order['id']]) }}" method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="row mb-3">
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    {{ translate('messages.type') }}
                                </label>
                                <div class="col-md-10 js-form-message">
                                    <input type="text" class="form-control" name="address_type"
                                        value="{{ isset($address['address_type']) ? $address['address_type'] : '' }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    {{ translate('messages.contact') }}
                                </label>
                                <div class="col-md-10 js-form-message">
                                    <input type="text" class="form-control" name="contact_person_number"
                                        value="{{ $address['contact_person_number'] }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    {{ translate('messages.name') }}
                                </label>
                                <div class="col-md-10 js-form-message">
                                    <input type="text" class="form-control" name="contact_person_name"
                                        value="{{ $address['contact_person_name'] }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    {{ translate('House') }}
                                </label>
                                <div class="col-md-10 js-form-message">
                                    <input type="text" class="form-control" name="house"
                                        value="{{ isset($address['house']) ? $address['house'] : '' }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    {{ translate('Floor') }}
                                </label>
                                <div class="col-md-10 js-form-message">
                                    <input type="text" class="form-control" name="floor"
                                        value="{{ isset($address['floor']) ? $address['floor'] : '' }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    {{ translate('Road') }}
                                </label>
                                <div class="col-md-10 js-form-message">
                                    <input type="text" class="form-control" name="road"
                                        value="{{ isset($address['road']) ? $address['road'] : '' }}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    {{ translate('address') }}
                                </label>
                                <div class="col-md-10 js-form-message">
                                    <input type="text" class="form-control" name="address"
                                        value="{{ $address['address'] }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    {{ translate('latitude') }}
                                </label>
                                <div class="col-md-4 js-form-message">
                                    <input type="text" class="form-control" name="latitude" id="latitude"
                                        value="{{ $address['latitude'] }}">
                                </div>
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    {{ translate('messages.longitude') }}
                                </label>
                                <div class="col-md-4 js-form-message">
                                    <input type="text" class="form-control" name="longitude" id="longitude"
                                        value="{{ $address['longitude'] }}">
                                </div>
                            </div>
                            <div class="mb-3">
                                <input id="pac-input" class="controls rounded initial-8"
                                title="{{ translate('messages.search_your_location_here') }}" type="text"
                                placeholder="{{ translate('messages.search_here') }}" />
                            <div class="mb-2 h-200px" id="map"></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-white"
                                data-dismiss="modal">{{ translate('messages.close') }}</button>
                            <button type="submit" class="btn btn-primary">{{ translate('messages.save') }}
                                {{ translate('messages.changes') }}</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
    <!-- End Modal -->

    <!--Dm assign Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">{{ translate('messages.assign') }}
                        {{ translate('messages.deliveryman') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-5 my-2">
                            <ul class="list-group overflow-auto max-height-400">
                                @foreach ($deliveryMen as $dm)
                                    <li class="list-group-item">
                                        <span class="dm_list" role='button' data-id="{{ $dm['id'] }}">
                                            <img class="avatar avatar-sm avatar-circle mr-1"
                                                onerror="this.src='{{ asset('public/assets/admin/img/160x160/img1.jpg') }}'"
                                                src="{{ asset('storage/app/public/delivery-man') }}/{{ $dm['image'] }}"
                                                alt="{{ $dm['name'] }}">
                                            {{ $dm['name'] }}
                                        </span>

                                        <a class="btn btn-primary btn-xs float-right"
                                            onclick="addDeliveryMan({{ $dm['id'] }})">{{ translate('messages.assign') }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="col-md-7 modal_body_map">
                            <div class="location-map" id="dmassign-map">
                                <div id="map_canvas"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->

    <!--Show locations on map Modal -->
    <div class="modal fade" id="locationModal" tabindex="-1" role="dialog" aria-labelledby="locationModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="locationModalLabel">{{ translate('messages.location') }}
                        {{ translate('messages.data') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 modal_body_map">
                            <div class="location-map" id="location-map">
                                <div id="location_map_canvas"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->

    <div class="modal fade" id="quick-view" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" id="quick-view-modal">

            </div>
        </div>
    </div>
@endsection

@push('script_2')
    <script>
        $('#search-form').on('submit', function(e) {
            e.preventDefault();
            var keyword = $('#datatableSearch').val();
            var nurl = new URL('{!! url()->full() !!}');
            nurl.searchParams.set('keyword', keyword);
            location.href = nurl;
        });

        function set_category_filter(id) {
            var nurl = new URL('{!! url()->full() !!}');
            nurl.searchParams.set('category_id', id);
            location.href = nurl;
        }

        function addon_quantity_input_toggle(e) {
            var cb = $(e.target);
            if (cb.is(":checked")) {
                cb.siblings('.addon-quantity-input').css({
                    'visibility': 'visible'
                });
            } else {
                cb.siblings('.addon-quantity-input').css({
                    'visibility': 'hidden'
                });
            }
        }

        function quick_view_cart_item(key) {
            $.get({
                url: '{{ route('admin.order.quick-view-cart-item') }}',
                dataType: 'json',
                data: {
                    key: key,
                    order_id: '{{ $order->id }}',
                },
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(data) {
                    $('#quick-view').modal('show');
                    $('#quick-view-modal').empty().html(data.view);
                },
                complete: function() {
                    $('#loading').hide();
                },
            });
        }

        function quickView(product_id) {
            $.get({
                url: '{{ route('admin.order.quick-view') }}',
                dataType: 'json',
                data: {
                    product_id: product_id,
                    order_id: '{{ $order->id }}',
                },
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(data) {
                    console.log("success...")
                    $('#quick-view').modal('show');
                    $('#quick-view-modal').empty().html(data.view);
                },
                complete: function() {
                    $('#loading').hide();
                },
            });
        }

        function cartQuantityInitialize() {
            $('.btn-number').click(function(e) {
                e.preventDefault();

                var fieldName = $(this).attr('data-field');
                var type = $(this).attr('data-type');
                var input = $("input[name='" + fieldName + "']");
                var currentVal = parseInt(input.val());

                if (!isNaN(currentVal)) {
                    if (type == 'minus') {

                        if (currentVal > input.attr('min')) {
                            input.val(currentVal - 1).change();
                        }
                        if (parseInt(input.val()) == input.attr('min')) {
                            $(this).attr('disabled', true);
                        }

                    } else if (type == 'plus') {

                        if (currentVal < input.attr('max')) {
                            input.val(currentVal + 1).change();
                        }
                        if (parseInt(input.val()) == input.attr('max')) {
                            $(this).attr('disabled', true);
                        }

                    }
                } else {
                    input.val(0);
                }
            });

            $('.input-number').focusin(function() {
                $(this).data('oldValue', $(this).val());
            });

            $('.input-number').change(function() {

                minValue = parseInt($(this).attr('min'));
                maxValue = parseInt($(this).attr('max'));
                valueCurrent = parseInt($(this).val());

                var name = $(this).attr('name');
                if (valueCurrent >= minValue) {
                    $(".btn-number[data-type='minus'][data-field='" + name + "']").removeAttr('disabled')
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Cart',
                        text: 'Sorry, the minimum value was reached'
                    });
                    $(this).val($(this).data('oldValue'));
                }
                if (valueCurrent <= maxValue) {
                    $(".btn-number[data-type='plus'][data-field='" + name + "']").removeAttr('disabled')
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Cart',
                        text: 'Sorry, stock limit exceeded.'
                    });
                    $(this).val($(this).data('oldValue'));
                }
            });
            $(".input-number").keydown(function(e) {
                // Allow: backspace, delete, tab, escape, enter and .
                if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
                    // Allow: Ctrl+A
                    (e.keyCode == 65 && e.ctrlKey === true) ||
                    // Allow: home, end, left, right
                    (e.keyCode >= 35 && e.keyCode <= 39)) {
                    // let it happen, don't do anything
                    return;
                }
                // Ensure that it is a number and stop the keypress
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            });
        }

        function getVariantPrice() {
            if ($('#add-to-cart-form input[name=quantity]').val() > 0) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '{{ route('admin.pos.variant_price') }}',
                    data: $('#add-to-cart-form').serializeArray(),
                    success: function(data) {
                        $('#add-to-cart-form #chosen_price_div').removeClass('d-none');
                        $('#add-to-cart-form #chosen_price_div #chosen_price').html(data.price);
                    }
                });
            }
        }

        function update_order_item(form_id = 'add-to-cart-form') {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.order.add-to-cart') }}',
                data: $('#' + form_id).serializeArray(),
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(data) {
                    if (data.data == 1) {
                        Swal.fire({
                            icon: 'info',
                            title: 'Cart',
                            text: "{{ translate('messages.product_already_added_in_cart') }}"
                        });
                        return false;
                    } else if (data.data == 0) {
                        toastr.success('{{ translate('messages.product_has_been_added_in_cart') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        location.reload();
                        return false;
                    }
                    else if (data.data == 'variation_error') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Cart',
                            text: data.message
                        });
                        return false;
                    }
                    $('.call-when-done').click();

                    toastr.success('{{ translate('messages.order_updated_successfully') }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                    location.reload();
                },
                complete: function() {
                    $('#loading').hide();
                }
            });
        }

        function removeFromCart(key) {
            Swal.fire({
                title: '{{ translate('messages.are_you_sure') }}',
                text: '{{ translate('messages.you_want_to_remove_this_order_item') }}',
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#FC6A57',
                cancelButtonText: '{{ translate('messages.no') }}',
                confirmButtonText: '{{ translate('messages.yes') }}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.post('{{ route('admin.order.remove-from-cart') }}', {
                        _token: '{{ csrf_token() }}',
                        key: key,
                        order_id: '{{ $order->id }}'
                    }, function(data) {
                        if (data.errors) {
                            for (var i = 0; i < data.errors.length; i++) {
                                toastr.error(data.errors[i].message, {
                                    CloseButton: true,
                                    ProgressBar: true
                                });
                            }
                        } else {
                            toastr.success('{{ translate('messages.item_has_been_removed_from_cart') }}', {
                                CloseButton: true,
                                ProgressBar: true
                            });
                            location.reload();
                        }

                    });
                }
            })

        }
        @if ($order->restaurant)
            function edit_order() {
                Swal.fire({
                    title: '{{ translate('messages.are_you_sure') }}',
                    text: '{{ translate('messages.you_want_to_edit_this_order') }}',
                    type: 'warning',
                    showCancelButton: true,
                    cancelButtonColor: 'default',
                    confirmButtonColor: '#FC6A57',
                    cancelButtonText: '{{ translate('messages.no') }}',
                    confirmButtonText: '{{ translate('messages.yes') }}',
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        location.href = '{{ route('admin.order.edit', $order->id) }}';
                    }
                })
            }
        @endif

        function cancle_editing_order() {
            Swal.fire({
                title: '{{ translate('messages.are_you_sure') }}',
                text: '{{ translate('messages.you_want_to_cancel_editing') }}',
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#FC6A57',
                cancelButtonText: '{{ translate('messages.no') }}',
                confirmButtonText: '{{ translate('messages.yes') }}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    location.href = '{{ route('admin.order.edit', $order->id) }}?cancle=true';
                }
            })
        }

        function update_order() {
            Swal.fire({
                title: '{{ translate('messages.are_you_sure') }}',
                text: '{{ translate('messages.you_want_to_submit_all_changes_for_this_order') }}',
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#FC6A57',
                cancelButtonText: '{{ translate('messages.no') }}',
                confirmButtonText: '{{ translate('messages.yes') }}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    // alert(ok);
                    location.href = '{{ route('admin.order.update', $order->id) }}';
                }
            })
        }
    </script>

    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ \App\Models\BusinessSetting::where('key', 'map_api_key')->first()->value }}&libraries=places&callback=initMap&v=3.45.8">
    </script>
    <script>
        // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function () {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        function addDeliveryMan(id) {
            $.ajax({
                type: "GET",
                url: '{{ url('/') }}/admin/order/add-delivery-man/{{ $order['id'] }}/' + id,
                success: function(data) {
                    location.reload();
                    console.log(data)
                    toastr.success('Successfully added', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                error: function(response) {
                    console.log(response);
                    toastr.error(response.responseJSON.message, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        }

        function last_location_view() {
            toastr.warning('{{ translate('Only available when order is out for delivery!') }}', {
                CloseButton: true,
                ProgressBar: true
            });
        }




        function cancelled_status() {
            Swal.fire({
                title: '{{ translate('messages.are_you_sure') }}',
                text: '{{ translate('messages.Change status to canceled ?') }}',
                type: 'warning',
                html:
                `   <select class="form-control js-select2-custom mx-1" name="reason" id="reason">
                    <option value=" ">
                            {{  translate('select_cancellation_reason') }}
                        </option>
                    @foreach ($reasons as $r)
                        <option value="{{ $r->reason }}">
                            {{ $r->reason }}
                        </option>
                    @endforeach

                    </select>`,
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#FC6A57',
                cancelButtonText: '{{ translate('messages.no') }}',
                confirmButtonText: '{{ translate('messages.yes') }}',
                reverseButtons: true,
                onOpen: function () {
                        $('.js-select2-custom').select2({
                            minimumResultsForSearch: 5,
                            width: '100%',
                            placeholder: "Select Reason",
                            language: "en",
                        });
                    }
            }).then((result) => {
                if (result.value) {
                    // console.log(result);
                    var reason = document.getElementById('reason').value;
                    location.href = '{!! route('admin.order.status', ['id' => $order['id'],'order_status' => 'canceled']) !!}&reason='+reason,'{{ translate('Change status to canceled ?') }}';
                }
            })
        }
    </script>
    <script>
        var deliveryMan = <?php echo json_encode($deliveryMen); ?>;
        var map = null;

        var myLatlng = new google.maps.LatLng({{ isset($order->restaurant) ? $order->restaurant->latitude : 0 }},
            {{ isset($order->restaurant) ? $order->restaurant->longitude : 0 }});
        var dmbounds = new google.maps.LatLngBounds(null);
        var locationbounds = new google.maps.LatLngBounds(null);
        var dmMarkers = [];
        dmbounds.extend(myLatlng);
        locationbounds.extend(myLatlng);
        var myOptions = {
            center: myLatlng,
            zoom: 13,
            mapTypeId: google.maps.MapTypeId.ROADMAP,

            panControl: true,
            mapTypeControl: false,
            panControlOptions: {
                position: google.maps.ControlPosition.RIGHT_CENTER
            },
            zoomControl: true,
            zoomControlOptions: {
                style: google.maps.ZoomControlStyle.LARGE,
                position: google.maps.ControlPosition.RIGHT_CENTER
            },
            scaleControl: false,
            streetViewControl: false,
            streetViewControlOptions: {
                position: google.maps.ControlPosition.RIGHT_CENTER
            }
        };

        function initializeGMap() {

            map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

            var infowindow = new google.maps.InfoWindow();

            @if ($order->restaurant)
                var Restaurantmarker = new google.maps.Marker({
                    position: new google.maps.LatLng(
                        {{ isset($order->restaurant) ? $order->restaurant->latitude : 0 }},
                        {{ isset($order->restaurant) ? $order->restaurant->longitude : 0 }}),
                    map: map,
                    title: "{{ isset($order->restaurant) ? Str::limit($order->restaurant->name, 15, '...') : '' }}",
                    icon: "{{ asset('public/assets/admin/img/restaurant_map.png') }}"
                });

                google.maps.event.addListener(Restaurantmarker, 'click', (function(Restaurantmarker) {
                    return function() {
                        infowindow.setContent(
                            "<div class='float--left'><img class='js--design-1' src='{{ asset('storage/app/public/restaurant/' . $order->restaurant->logo) }}'></div><div class='text-break float--right p--10px'><b>{{ Str::limit($order->restaurant->name, 15, '...') }}</b><br/> {{ $order->restaurant->address }}</div>"
                        );
                        infowindow.open(map, Restaurantmarker);
                    }
                })(Restaurantmarker));
            @endif
            map.fitBounds(dmbounds);
            for (var i = 0; i < deliveryMan.length; i++) {
                if (deliveryMan[i].lat) {
                    // var contentString = "<div class='float--left'><img class='js--design-1' src='{{ asset('storage/app/public/delivery-man') }}/"+deliveryMan[i].image+"'></div><div class=' float--right p--10px'><b>"+deliveryMan[i].name+"</b><br/> "+deliveryMan[i].location+"</div>";
                    var point = new google.maps.LatLng(deliveryMan[i].lat, deliveryMan[i].lng);
                    dmbounds.extend(point);
                    map.fitBounds(dmbounds);
                    var marker = new google.maps.Marker({
                        position: point,
                        map: map,
                        title: deliveryMan[i].location,
                        icon: "{{ asset('public/assets/admin/img/delivery_boy_map.png') }}"
                    });
                    dmMarkers[deliveryMan[i].id] = marker;
                    google.maps.event.addListener(marker, 'click', (function(marker, i) {
                        return function() {
                            infowindow.setContent(
                                "<div class='float--left'><img class='js--design-1' src='{{ asset('storage/app/public/delivery-man') }}/" +
                                deliveryMan[i].image +
                                "'></div><div class='float--right p--10px'><b>" + deliveryMan[i]
                                .name + "</b><br/> " + deliveryMan[i].location + "</div>");
                            infowindow.open(map, marker);
                        }
                    })(marker, i));
                }

            };
        }

        function initMap() {
            let map = new google.maps.Map(document.getElementById("map"), {
                zoom: 13,
                center: {
                    lat: {{ isset($order->restaurant) ? $order->restaurant->latitude : '23.757989' }},
                    lng: {{ isset($order->restaurant) ? $order->restaurant->longitude : '90.360587' }}
                }
            });

            let zonePolygon = null;

            //get current location block
            let infoWindow = new google.maps.InfoWindow();
            // Try HTML5 geolocation.
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        myLatlng = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude,
                        };
                        infoWindow.setPosition(myLatlng);
                        infoWindow.setContent("Location found.");
                        infoWindow.open(map);
                        map.setCenter(myLatlng);
                    },
                    () => {
                        handleLocationError(true, infoWindow, map.getCenter());
                    }
                );
            } else {
                // Browser doesn't support Geolocation
                handleLocationError(false, infoWindow, map.getCenter());
            }
            //-----end block------
            const input = document.getElementById("pac-input");
            const searchBox = new google.maps.places.SearchBox(input);
            map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);
            let markers = [];
            const bounds = new google.maps.LatLngBounds();
            searchBox.addListener("places_changed", () => {
                const places = searchBox.getPlaces();

                if (places.length == 0) {
                    return;
                }
                // Clear out the old markers.
                markers.forEach((marker) => {
                    marker.setMap(null);
                });
                markers = [];
                // For each place, get the icon, name and location.
                places.forEach((place) => {
                    if (!place.geometry || !place.geometry.location) {
                        console.log("Returned place contains no geometry");
                        return;
                    }
                    console.log(place.geometry.location);
                    if(!google.maps.geometry.poly.containsLocation(
                        place.geometry.location,
                        zonePolygon
                    )){
                        toastr.error('{{ translate('messages.out_of_coverage') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        return false;
                    }

                    document.getElementById('latitude').value = place.geometry.location.lat();
                    document.getElementById('longitude').value = place.geometry.location.lng();

                    const icon = {
                        url: place.icon,
                        size: new google.maps.Size(71, 71),
                        origin: new google.maps.Point(0, 0),
                        anchor: new google.maps.Point(17, 34),
                        scaledSize: new google.maps.Size(25, 25),
                    };
                    // Create a marker for each place.
                    markers.push(
                        new google.maps.Marker({
                            map,
                            icon,
                            title: place.name,
                            position: place.geometry.location,
                        })
                    );

                    if (place.geometry.viewport) {
                        // Only geocodes have viewport.
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);
            });
            @if ($order->restaurant)
                $.get({
                    url: '{{ url('/') }}/admin/zone/get-coordinates/{{ $order->restaurant->zone_id }}',
                    dataType: 'json',
                    success: function(data) {
                        zonePolygon = new google.maps.Polygon({
                            paths: data.coordinates,
                            strokeColor: "#FF0000",
                            strokeOpacity: 0.8,
                            strokeWeight: 2,
                            fillColor: 'white',
                            fillOpacity: 0,
                        });
                        zonePolygon.setMap(map);
                        zonePolygon.getPaths().forEach(function(path) {
                            path.forEach(function(latlng) {
                                bounds.extend(latlng);
                                map.fitBounds(bounds);
                            });
                        });
                        map.setCenter(data.center);
                        google.maps.event.addListener(zonePolygon, 'click', function(mapsMouseEvent) {
                            infoWindow.close();
                            // Create a new InfoWindow.
                            infoWindow = new google.maps.InfoWindow({
                                position: mapsMouseEvent.latLng,
                                content: JSON.stringify(mapsMouseEvent.latLng.toJSON(), null,
                                    2),
                            });
                            var coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);
                            var coordinates = JSON.parse(coordinates);

                            document.getElementById('latitude').value = coordinates['lat'];
                            document.getElementById('longitude').value = coordinates['lng'];
                            infoWindow.open(map);
                        });
                    },
                });
            @endif

        }
        initMap();

        $(document).ready(function() {

            // Re-init map before show modal
            $('#myModal').on('shown.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                $("#dmassign-map").css("width", "100%");
                $("#map_canvas").css("width", "100%");
            });

            // Trigger map resize event after modal shown
            $('#myModal').on('shown.bs.modal', function() {
                initializeGMap();
                google.maps.event.trigger(map, "resize");
                map.setCenter(myLatlng);
            });

            $('#shipping-address-modal').on('shown.bs.modal', function() {
                initMap();
            });


            function initializegLocationMap() {
                map = new google.maps.Map(document.getElementById("location_map_canvas"), myOptions);

                var infowindow = new google.maps.InfoWindow();

                @if ($order->customer && isset($address))
                    var marker = new google.maps.Marker({
                        position: new google.maps.LatLng({{ $address['latitude'] }},
                            {{ $address['longitude'] }}),
                        map: map,
                        title: "{{ $order->customer->f_name }} {{ $order->customer->l_name }}",
                        icon: "{{ asset('public/assets/admin/img/customer_location.png') }}"
                    });

                    google.maps.event.addListener(marker, 'click', (function(marker) {
                        return function() {
                            infowindow.setContent(
                                "<div class='float--left'><img class='js--design-1' src='{{ asset('storage/app/public/profile/' . $order->customer->image) }}'></div><div class='float--right p--10px'><b>{{ $order->customer->f_name }} {{ $order->customer->l_name }}</b><br/>{{ $address['address'] }}</div>"
                            );
                            infowindow.open(map, marker);
                        }
                    })(marker));
                    locationbounds.extend(marker.getPosition());
                @endif
                @if ($order->delivery_man && $order->dm_last_location)
                    var dmmarker = new google.maps.Marker({
                        position: new google.maps.LatLng({{ $order->dm_last_location['latitude'] }},
                            {{ $order->dm_last_location['longitude'] }}),
                        map: map,
                        title: "{{ $order->delivery_man->f_name }}  {{ $order->delivery_man->l_name }}",
                        icon: "{{ asset('public/assets/admin/img/delivery_boy_map.png') }}"
                    });

                    google.maps.event.addListener(dmmarker, 'click', (function(dmmarker) {
                        return function() {
                            infowindow.setContent(
                                "<div class='float--left'><img class='js--design-1' src='{{ asset('storage/app/public/delivery-man/' . $order->delivery_man->image) }}'></div><div class='float--right p--10px'><b>{{ $order->delivery_man->f_name }}  {{ $order->delivery_man->l_name }}</b><br/> {{ $order->dm_last_location['location'] }}</div>"
                            );
                            infowindow.open(map, dmmarker);
                        }
                    })(dmmarker));
                    locationbounds.extend(dmmarker.getPosition());
                @endif

                @if ($order->restaurant)
                    var Retaurantmarker = new google.maps.Marker({
                        position: new google.maps.LatLng({{ $order->restaurant->latitude }},
                            {{ $order->restaurant->longitude }}),
                        map: map,
                        title: "{{ Str::limit($order->restaurant->name, 15, '...') }}",
                        icon: "{{ asset('public/assets/admin/img/restaurant_map.png') }}"
                    });

                    google.maps.event.addListener(Retaurantmarker, 'click', (function(Retaurantmarker) {
                        return function() {
                            infowindow.setContent(
                                "<div class='float--left'><img class='js--design-1' src='{{ asset('storage/app/public/restaurant/' . $order->restaurant->logo) }}'></div><div class='float--right p--10px'><b>{{ Str::limit($order->restaurant->name, 15, '...') }}</b><br/> {{ $order->restaurant->address }}</div>"
                            );
                            infowindow.open(map, Retaurantmarker);
                        }
                    })(Retaurantmarker));
                    locationbounds.extend(Retaurantmarker.getPosition());
                @endif

                google.maps.event.addListenerOnce(map, 'idle', function() {
                    map.fitBounds(locationbounds);
                });
            }

            // Re-init map before show modal
            $('#locationModal').on('shown.bs.modal', function(event) {
                initializegLocationMap();
            });


            $('.dm_list').on('click', function() {
                var id = $(this).data('id');
                map.panTo(dmMarkers[id].getPosition());
                map.setZoom(13);
                dmMarkers[id].setAnimation(google.maps.Animation.BOUNCE);
                window.setTimeout(() => {
                    dmMarkers[id].setAnimation(null);
                }, 3);
            });
        })






    </script>
@endpush
