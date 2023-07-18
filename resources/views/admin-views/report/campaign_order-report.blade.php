
@extends('layouts.admin.app')

@section('title', translate('messages.Camapign_Order_Report'))

@push('css_or_js')
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{asset('/public/assets/admin/img/report/report.png')}}" class="w--22" alt="">
                </span>
                <span>
                    {{ translate('messages.Camapign_Order_Report') }}
                </span>
            </h1>
        </div>
        <!-- End Page Header -->

        <div class="card mb-20">
            <div class="card-body">
                <h4 class="">{{ translate('Search Data') }}</h4>
                <form  method="get">
                    <div class="row g-3">
                        <div class="col-sm-6 col-md-3">
                            <select name="campaign_id" class="form-control js-select2-custom"
                                onchange="set_filter('{{ url()->full() }}',this.value,'campaign_id')" id="campaign_id">
                                <option value="all">{{ translate('messages.All Campaignes') }}</option>
                                @foreach (\App\Models\ItemCampaign::orderBy('title')->get(['id','title']) as $z)
                                    <option value="{{ $z['id'] }}"
                                        {{ isset($campaign_id) && $campaign_id == $z['id'] ? 'selected' : '' }}>
                                        {{ $z['title'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <select name="restaurant_id" onchange="set_restaurant_filter('{{ url()->full() }}',this.value)"
                                data-placeholder="{{ translate('messages.select') }} {{ translate('messages.restaurant') }}"
                                class="js-data-example-ajax form-control">
                                @if (isset($restaurant))
                                    <option value="{{ $restaurant->id }}" selected>{{ $restaurant->name }}</option>
                                @else
                                    <option value="all" selected>{{ translate('messages.all') }} {{ translate('messages.restaurants') }}</option>
                                @endif
                            </select>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <select name="customer_id" onchange="set_customer_filter('{{ url()->full() }}',this.value)"
                                data-placeholder="{{ translate('messages.select') }} {{ translate('messages.customer') }}"
                                class="js-data-example-ajax-2 form-control">
                                @if (isset($customer))
                                    <option value="{{ $customer->id }}" selected>{{ $customer->f_name . ' ' .$customer->l_name }}</option>
                                @else
                                    <option value="all" selected>{{ translate('messages.all') }} {{ translate('messages.customers') }}</option>
                                @endif
                            </select>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <select class="form-control" name="filter"
                                onchange="set_time_filter('{{ url()->full() }}',this.value)">
                                <option value="all_time" {{ isset($filter) && $filter == 'all_time' ? 'selected' : '' }}>
                                    {{ translate('messages.All Time') }}</option>
                                <option value="this_year" {{ isset($filter) && $filter == 'this_year' ? 'selected' : '' }}>
                                    {{ translate('messages.This Year') }}</option>
                                <option value="previous_year"
                                    {{ isset($filter) && $filter == 'previous_year' ? 'selected' : '' }}>
                                    {{ translate('messages.Previous Year') }}</option>
                                <option value="this_month"
                                    {{ isset($filter) && $filter == 'this_month' ? 'selected' : '' }}>
                                    {{ translate('messages.This Month') }}</option>
                                <option value="this_week" {{ isset($filter) && $filter == 'this_week' ? 'selected' : '' }}>
                                    {{ translate('messages.This Week') }}</option>
                                <option value="custom" {{ isset($filter) && $filter == 'custom' ? 'selected' : '' }}>
                                    {{ translate('messages.Custom') }}</option>
                            </select>
                        </div>
                        @if (isset($filter) && $filter == 'custom')
                            <div class="col-sm-6 col-md-3">
                                <input type="date" name="from" id="from_date" class="form-control"
                                    placeholder="{{ translate('Start Date') }}"
                                    value={{ $from ? $from  : '' }} required>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <input type="date" name="to" id="to_date" class="form-control"
                                    placeholder="{{ translate('End Date') }}"
                                    value={{ $to ? $to  : '' }} required>
                            </div>
                        @endif
                        <div class="col-sm-6 col-md-3 ml-auto">
                            <button type="submit"
                                class="btn btn-primary btn-block">{{ translate('Filter') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="mb-20">
            <div class="row g-4">
                <div class="col-lg-3">
                    <a class="__card-1 h-100" href="#">
                        <img src="{{asset('/public/assets/admin/img/report/new/total.png')}}" class="icon" alt="report/new">
                        <h3 class="title">{{$total_orders}}</h3>
                        <h6 class="subtitle">{{translate('messages.total_orders')}}</h6>
                    </a>
                </div>
                <div class="col-lg-9">
                    <div class="row g-3">
                        <div class="col-sm-6 col-md-4">
                            <a class="__card-2 __bg-1" href="#">
                            <h4 class="title">{{$total_progress_count}}</h4>
                            <span class="subtitle">{{translate('messages.in_progress_orders')}} <span data-toggle="tooltip" data-placement="right" data-original-title="{{translate('Including accepted and processing orders')}}"><img src="{{asset('public/assets/admin/img/info-circle.svg')}}" alt="{{translate('messages.in_progress_orders')}}"></span></span>
                            <img src="{{asset('/public/assets/admin/img/report/new/progress-report.png')}}" alt="report/new" class="card-icon">
                            </a>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <a class="__card-2 __bg-2" href="#">
                            <h4 class="title">{{$total_on_the_way_count}}</h4>
                            <span class="subtitle">{{translate('messages.on_the_way')}}</span>
                            <img src="{{asset('/public/assets/admin/img/report/new/on-the-way.png')}}" alt="report/new" class="card-icon">
                            </a>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <a class="__card-2 __bg-3" href="#">
                            <h4 class="title">{{$total_delivered_count}}</h4>
                            <span class="subtitle">{{ translate('Delivered_Orders') }}</span>
                            <img src="{{asset('/public/assets/admin/img/report/new/delivered.png')}}" alt="report/new" class="card-icon">
                            </a>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <a class="__card-2 __bg-4" href="#">
                            <h4 class="title">{{$total_failed_count}}</h4>
                            <span class="subtitle">{{translate('messages.failed_orders')}}</span>
                            <img src="{{asset('/public/assets/admin/img/report/new/failed.png')}}" alt="report/new" class="card-icon">
                            </a>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <a class="__card-2 __bg-5" href="#">
                            <h4 class="title">{{$total_refunded_count}}</h4>
                            <span class="subtitle">{{translate('messages.refunded_orders')}}</span>
                            <img src="{{asset('/public/assets/admin/img/report/new/refunded.png')}}" alt="report/new" class="card-icon">
                            </a>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <a class="__card-2 __bg-6" href="#">
                            <h4 class="title">{{$total_canceled_count}}</h4>
                            <span class="subtitle">{{translate('messages.canceled_orders')}}</span>
                            <img src="{{asset('/public/assets/admin/img/report/new/canceled.png')}}" alt="report/new" class="card-icon">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- End Stats -->
        <!-- Card -->
        <div class="card mt-3">
            <!-- Header -->
            <div class="card-header border-0 py-2">
                <div class="search--button-wrapper">
                    <h3 class="card-title">
                        {{ translate('messages.Total Orders') }} <span
                            class="badge badge-soft-secondary" id="countItems">{{ $orders->total() }}</span>
                    </h3>
                    <form  class="search-form">
                        <!-- Search -->
                        <div class="input--group input-group input-group-merge input-group-flush">
                            <input name="search"  type="search" class="form-control" value="{{ request()->search ?? null }}"
                             placeholder="{{ translate('Search by Order ID or Restaurant Name') }}">
                            <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                        </div>
                        <!-- End Search -->
                    </form>
                    <!-- Static Export Button -->
                    <div class="hs-unfold ml-3">
                        <a class="js-hs-unfold-invoker btn btn-sm btn-white dropdown-toggle btn export-btn font--sm"
                            href="javascript:;"
                            data-hs-unfold-options="{
                                &quot;target&quot;: &quot;#usersExportDropdown&quot;,
                                &quot;type&quot;: &quot;css-animation&quot;
                            }"
                            data-hs-unfold-target="#usersExportDropdown" data-hs-unfold-invoker="">
                            <i class="tio-download-to mr-1"></i> {{ translate('export') }}
                        </a>

                        <div id="usersExportDropdown"
                            class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-sm-right hs-unfold-content-initialized hs-unfold-css-animation animated hs-unfold-reverse-y hs-unfold-hidden">

                            <span class="dropdown-header">{{ translate('download_options') }}</span>
                            <a id="export-excel" class="dropdown-item"
                                href="{{ route('admin.report.campaign_report_export', ['type' => 'excel', request()->getQueryString()]) }}">
                                <img class="avatar avatar-xss avatar-4by3 mr-2"
                                    src="{{ asset('public/assets/admin/svg/components/excel.svg') }}"
                                    alt="Image Description">
                                {{ translate('messages.excel') }}
                            </a>
                            <a id="export-csv" class="dropdown-item"
                                href="{{ route('admin.report.campaign_report_export', ['type' => 'csv', request()->getQueryString()]) }}">
                                <img class="avatar avatar-xss avatar-4by3 mr-2"
                                    src="{{ asset('public/assets/admin/svg/components/placeholder-csv-format.svg') }}"
                                    alt="Image Description">
                                .{{ translate('messages.csv') }}
                            </a>

                        </div>
                    </div>
                    <!-- Static Export Button -->
                </div>
            </div>
            <!-- End Header -->

            <!-- Body -->
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-borderless middle-align __txt-14px">
                        <thead class="thead-light white--space-false">
                            <tr>
                                <th class="border-top border-bottom">{{ translate('messages.sl') }}</th>
                                <th class="border-top border-bottom word-nobreak">{{ translate('messages.order_id') }}</th>
                                <th class="border-top border-bottom">{{ translate('messages.restaurant') }}</th>
                                <th class="border-top border-bottom">{{ translate('messages.customer_name') }}</th>
                                <th class="border-top border-bottom min-w-120">{{ translate('messages.total_item_amount') }}</th>
                                <th class="border-top border-bottom">{{ translate('messages.item_discount') }}</th>
                                <th class="border-top border-bottom">{{ translate('messages.coupon_discount') }}</th>
                                <th class="border-top border-bottom">{{ translate('messages.discounted_amount') }}</th>
                                <th class="border-top border-bottom text-center">{{ translate('messages.tax') }}</th>
                                <th class="border-top border-bottom">{{ translate('messages.delivery_charge') }}</th>
                                <th class="border-top border-bottom">{{ translate('messages.order_amount') }}</th>
                                <th class="border-top border-bottom min-w-140">{{ translate('messages.amount_received_by') }}</th>
                                <th class="border-top border-bottom">{{ translate('messages.payment_method') }}</th>
                                <th class="border-top border-bottom">{{ translate('messages.order_status') }}</th>
                                <th class="border-top border-bottom text-center">{{ translate('messages.action') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody id="set-rows">
                            @foreach ($orders as $key => $order)
                                <tr class="status-{{ $order['order_status'] }} class-all">
                                    <td class="">
                                        {{ $key + $orders->firstItem() }}
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.order.details', ['id' => $order['id']]) }}">{{ $order['id'] }}</a>
                                    </td>
                                    <td  class="text-capitalize">
                                        @if($order->restaurant)
                                            {{Str::limit($order->restaurant->name,25,'...')}}
                                        @else
                                            <label class="badge badge-danger">{{ translate('messages.invalid') }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($order->customer)
                                            <a class="text-body text-capitalize"
                                                href="{{ route('admin.customer.view', [$order['user_id']]) }}">
                                                <strong>{{ $order->customer['f_name'] . ' ' . $order->customer['l_name'] }}</strong>
                                            </a>
                                        @else
                                            <label class="badge badge-danger">{{ translate('messages.invalid') }}
                                                {{ translate('messages.customer') }}
                                                {{ translate('messages.data') }}</label>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="text-right mw--85px  mr-3">
                                            <div>
                                                {{ \App\CentralLogics\Helpers::number_format_short($order['order_amount']-$order['dm_tips']-$order['total_tax_amount']-$order['delivery_charge']+$order['coupon_discount_amount'] + $order['restaurant_discount_amount']) }}
                                            </div>
                                            @if ($order->payment_status == 'paid')
                                                <strong class="text-success">
                                                    {{ translate('messages.paid') }}
                                                </strong>
                                            @else
                                                <strong class="text-danger">
                                                    {{ translate('messages.unpaid') }}
                                                </strong>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-center mw--85px">
                                        {{ \App\CentralLogics\Helpers::number_format_short($order->details->sum('discount_on_food')) }}
                                    </td>
                                    <td class="text-center mw--85px">
                                        {{ \App\CentralLogics\Helpers::number_format_short($order['coupon_discount_amount']) }}
                                    </td>
                                    <td class="text-center mw--85px">
                                        {{ \App\CentralLogics\Helpers::number_format_short($order['coupon_discount_amount'] + $order['restaurant_discount_amount']) }}
                                    </td>
                                    <td class="text-center mw--85px white-space-nowrap">
                                        {{ \App\CentralLogics\Helpers::number_format_short($order['total_tax_amount']) }}
                                    </td>
                                    <td class="text-center mw--85px">
                                        {{ \App\CentralLogics\Helpers::number_format_short($order['delivery_charge']) }}
                                    </td>
                                    <td>
                                        <div class="text-right mw--85px">
                                            <div>
                                                {{ \App\CentralLogics\Helpers::number_format_short($order['order_amount']) }}
                                            </div>
                                            @if ($order->payment_status == 'paid')
                                                <strong class="text-success">
                                                    {{ translate('messages.paid') }}
                                                </strong>
                                            @else
                                                <strong class="text-danger">
                                                    {{ translate('messages.unpaid') }}
                                                </strong>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-center mw--85px text-capitalize">
                                        {{isset($order->transaction) ? $order->transaction->received_by : translate('messages.not_received_yet')}}
                                    </td>
                                    <td class="text-center mw--85px text-capitalize">
                                            {{ translate(str_replace('_', ' ', $order['payment_method'])) }}
                                    </td>
                                    <td class="text-center mw--85px text-capitalize">
                                        @if($order['order_status']=='pending')
                                                <span class="badge badge-soft-info">
                                                  {{translate('messages.pending')}}
                                                </span>
                                            @elseif($order['order_status']=='confirmed')
                                                <span class="badge badge-soft-info">
                                                  {{translate('messages.confirmed')}}
                                                </span>
                                            @elseif($order['order_status']=='processing')
                                                <span class="badge badge-soft-warning">
                                                  {{translate('messages.processing')}}
                                                </span>
                                            @elseif($order['order_status']=='picked_up')
                                                <span class="badge badge-soft-warning">
                                                  {{translate('messages.out_for_delivery')}}
                                                </span>
                                            @elseif($order['order_status']=='delivered')
                                                <span class="badge badge-soft-success">
                                                  {{translate('messages.delivered')}}
                                                </span>
                                            @elseif($order['order_status']=='failed')
                                                <span class="badge badge-soft-danger">
                                                  {{translate('messages.payment')}}  {{translate('messages.failed')}}
                                                </span>
                                            @elseif($order['order_status']=='handover')
                                                <span class="badge badge-soft-danger">
                                                  {{translate('messages.handover')}}
                                                </span>
                                            @elseif($order['order_status']=='canceled')
                                                <span class="badge badge-soft-danger">
                                                  {{translate('messages.canceled')}}
                                                </span>
                                            @elseif($order['order_status']=='accepted')
                                                <span class="badge badge-soft-danger">
                                                  {{translate('messages.accepted')}}
                                                </span>
                                            @else
                                                <span class="badge badge-soft-danger">
                                                  {{translate(str_replace('_',' ',$order['order_status']))}}
                                                </span>
                                            @endif

                                    </td>


                                    <td>
                                        <div class="btn--container justify-content-center">
                                            <a class="ml-2 btn btn-sm btn--warning btn-outline-warning action-btn"
                                                href="{{ route('admin.order.details', ['id' => $order['id']]) }}">
                                                <i class="tio-invisible"></i>
                                            </a>
                                            <a class="ml-2 btn btn-sm btn--primary btn-outline-primary action-btn"
                                                href="{{ route('admin.order.generate-invoice', ['id' => $order['id']]) }}">
                                                <i class="tio-print"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- End Table -->


            </div>
            <!-- End Body -->
            @if (count($orders) !== 0)
                <hr>
            @endif
            <div class="page-area px-4 pb-3">
                {!! $orders->links() !!}
            </div>
            @if (count($orders) === 0)
                <div class="empty--data">
                    <img src="{{ asset('/public/assets/admin/svg/illustrations/sorry.svg') }}" alt="public">
                    <h5>
                        {{ translate('no_data_found') }}
                    </h5>
                </div>
            @endif
        </div>
        <!-- End Card -->
    </div>
@endsection

@push('script_2')
    <script>
        $(document).on('ready', function() {
            $('.js-data-example-ajax').select2({
                ajax: {
                    url: '{{ url('/') }}/admin/restaurant/get-restaurants',
                    data: function(params) {
                        return {
                            q: params.term, // search term
                            // all:true,
                            @if (isset($zone))
                                zone_ids: [{{ $zone->id }}],
                            @endif
                            page: params.page
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    __port: function(params, success, failure) {
                        var $request = $.ajax(params);

                        $request.then(success);
                        $request.fail(failure);
                        return $request;
                    }
                }
            });

            $('.js-data-example-ajax-2').select2({
                ajax: {
                    url: '{{ url('/') }}/admin/customer/select-list',
                    data: function(params) {
                        return {
                            q: params.term, // search term
                            // all:true,
                            @if (isset($zone))
                                zone_ids: [{{ $zone->id }}],
                            @endif
                            @if (request('restaurant_id'))
                                restaurant_id: {{ request('restaurant_id') }},
                            @endif
                            page: params.page
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    __port: function(params, success, failure) {
                        var $request = $.ajax(params);

                        $request.then(success);
                        $request.fail(failure);

                        return $request;
                    }
                }
            });
        });
    </script>

    <script>
        $('#from_date,#to_date').change(function() {
            let fr = $('#from_date').val();
            let to = $('#to_date').val();
            if (fr != '' && to != '') {
                if (fr > to) {
                    $('#from_date').val('');
                    $('#to_date').val('');
                    toastr.error('Invalid date range!', Error, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            }
        })

    </script>
@endpush

