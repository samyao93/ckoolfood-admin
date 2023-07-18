@extends('layouts.admin.app')

@section('title',ucfirst(str_replace('_',' ',$status).' '.translate('messages.orders')))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title mb-2 text-capitalize">
                <div class="card-header-icon d-inline-flex mr-2 img">
                    <img src="{{asset('/public/assets/admin/img/orders.png')}}" alt="">
                </div>
                {{translate('messages.'.$status)}} {{translate('messages.orders')}} <span class="badge badge-soft-dark ml-2">{{$total}}</span>
            </h1>
        </div>

        <!-- End Page Header -->

        <!-- Card -->
        <div class="card">
            <!-- Header -->
            <div class="card-header py-2">
                <div class="search--button-wrapper">
                    <h5 class="card-title"></h5>
                    <form method="GET">
                        <!-- Search -->
                        <div class="input--group input-group input-group-merge input-group-flush">
                            <input id="datatableSearch_" type="search" name="search" class="form-control"
                                    placeholder="{{translate('search_by_order_id')}}" aria-label="{{translate('messages.search')}}" value="{{ request()->search ?? null }}">
                            <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>

                        </div>
                        <!-- End Search -->
                    </form>
                    <div class="d-sm-flex justify-content-sm-end align-items-sm-center">
                        <div class="hs-unfold mr-2">
                            <a class="js-hs-unfold-invoker btn btn-sm btn-white dropdown-toggle" href="javascript:;"
                                data-hs-unfold-options='{
                                    "target": "#usersExportDropdown",
                                    "type": "css-animation"
                                }'>
                                <i class="tio-download-to mr-1"></i> {{translate('messages.export')}}
                            </a>

                            <div id="usersExportDropdown"
                                    class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-sm-right">
                                <div class="dropdown-divider"></div>
                                <span class="dropdown-header">{{translate('messages.download')}} {{translate('messages.options')}}</span>
                                <a id="export-excel" class="dropdown-item" href="javascript:;">
                                    <img class="avatar avatar-xss avatar-4by3 mr-2"
                                            src="{{asset('public/assets/admin')}}/svg/components/excel.svg"
                                            alt="Image Description">
                                    {{translate('messages.excel')}}
                                </a>
                                <a id="export-csv" class="dropdown-item" href="javascript:;">
                                    <img class="avatar avatar-xss avatar-4by3 mr-2"
                                            src="{{asset('public/assets/admin')}}/svg/components/placeholder-csv-format.svg"
                                            alt="Image Description">
                                    .{{translate('messages.csv')}}
                                </a>
                            </div>
                        </div>
                        <!-- End Unfold -->
                        <!-- Unfold -->
                        <div class="hs-unfold mr-2">
                            <a class="js-hs-unfold-invoker btn btn-sm btn-white" href="javascript:;"
                                onclick="$('#datatableFilterSidebar,.hs-unfold-overlay').show(500)">
                                <i class="tio-filter-list mr-1"></i> {{translate('messages.filters')}} <span class="badge badge-success badge-pill ml-1" id="filter_count"></span>
                            </a>
                        </div>
                        <!-- End Unfold -->
                        <!-- Unfold -->
                        <div class="hs-unfold">
                            <a class="js-hs-unfold-invoker btn btn-sm btn-white" href="javascript:;"
                                data-hs-unfold-options='{
                                    "target": "#showHideDropdown",
                                    "type": "css-animation"
                                }'>
                                <i class="tio-table mr-1"></i> {{translate('messages.columns')}}
                            </a>

                            <div id="showHideDropdown"
                                    class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-right dropdown-card">
                                <div class="card card-sm">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="mr-2">{{translate('messages.order')}}</span>

                                            <!-- Checkbox Switch -->
                                            <label class="toggle-switch toggle-switch-sm" for="toggleColumn_order">
                                                <input type="checkbox" class="toggle-switch-input"
                                                        id="toggleColumn_order" checked>
                                                <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                            <!-- End Checkbox Switch -->
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="mr-2">{{translate('messages.date')}}</span>

                                            <!-- Checkbox Switch -->
                                            <label class="toggle-switch toggle-switch-sm" for="toggleColumn_date">
                                                <input type="checkbox" class="toggle-switch-input"
                                                        id="toggleColumn_date" checked>
                                                <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                            <!-- End Checkbox Switch -->
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="mr-2">{{translate('messages.customer')}}</span>

                                            <!-- Checkbox Switch -->
                                            <label class="toggle-switch toggle-switch-sm"
                                                    for="toggleColumn_customer">
                                                <input type="checkbox" class="toggle-switch-input"
                                                        id="toggleColumn_customer" checked>
                                                <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                            <!-- End Checkbox Switch -->
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="mr-2">{{translate('messages.restaurant')}}</span>

                                            <!-- Checkbox Switch -->
                                            <label class="toggle-switch toggle-switch-sm"
                                                    for="toggleColumn_restaurant">
                                                <input type="checkbox" class="toggle-switch-input"
                                                        id="toggleColumn_restaurant" checked>
                                                <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                            <!-- End Checkbox Switch -->
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="mr-2">{{translate('messages.total')}}</span>

                                            <!-- Checkbox Switch -->
                                            <label class="toggle-switch toggle-switch-sm" for="toggleColumn_total">
                                                <input type="checkbox" class="toggle-switch-input"
                                                        id="toggleColumn_total" checked>
                                                <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                            <!-- End Checkbox Switch -->
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="mr-2">{{translate('messages.order')}} {{translate('messages.status')}}</span>

                                            <!-- Checkbox Switch -->
                                            <label class="toggle-switch toggle-switch-sm" for="toggleColumn_order_status">
                                                <input type="checkbox" class="toggle-switch-input"
                                                        id="toggleColumn_order_status" checked>
                                                <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                            <!-- End Checkbox Switch -->
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="mr-2">{{translate('messages.actions')}}</span>

                                            <!-- Checkbox Switch -->
                                            <label class="toggle-switch toggle-switch-sm"
                                                    for="toggleColumn_actions">
                                                <input type="checkbox" class="toggle-switch-input"
                                                        id="toggleColumn_actions" checked>
                                                <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                            <!-- End Checkbox Switch -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Unfold -->
                    </div>
                </div>
                <!-- End Row -->
            </div>
            <!-- End Header -->

            <!-- Table -->
            <div class="table-responsive datatable-custom fz--14px">
                <table id="datatable"
                       class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                       data-hs-datatables-options='{
                     "columnDefs": [{
                        "targets": [0],
                        "orderable": false
                      }],
                     "order": [],
                     "info": {
                       "totalQty": "#datatableWithPaginationInfoTotalQty"
                     },
                     "search": "#datatableSearch",
                     "entries": "#datatableEntries",
                     "paging": false
                   }'>
                    <thead class="thead-light">
                    <tr>
                        <th class="">
                            {{ translate('messages.sl') }}
                        </th>
                        <th class="table-column-pl-0">{{translate('messages.order')}}</th>
                        <th>{{translate('messages.date')}}</th>
                        <th class="w-140px">{{translate('messages.customer')}}</th>
                        <th>{{translate('messages.restaurant')}}</th>
                        <th>{{translate('messages.total')}} {{translate('messages.amount')}}</th>
                        <th class="text-center">{{translate('messages.order')}} {{translate('messages.status')}}</th>
                        <th class="text-center">{{translate('messages.actions')}}</th>
                    </tr>
                    </thead>

                    <tbody id="set-rows">
                    @foreach($orders as $key=>$order)

                        <tr class="status-{{$order['order_status']}} class-all">
                            <td class="">
                                {{$key+$orders->firstItem()}}
                            </td>
                            <td class="table-column-pl-0">
                                <a href="{{route('admin.order.details',['id'=>$order['id']])}}">{{$order['id']}}</a>
                            </td>
                            <td class="text-uppercase">
                                <div>
                                    {{-- {{date('d M Y',strtotime($order['created_at']))}} --}}
                                    {{  Carbon\Carbon::parse($order['created_at'])->locale(app()->getLocale())->translatedFormat('d M Y ') }}
                                </div>
                                <div>
                                    
                                    {{  Carbon\Carbon::parse($order['created_at'])->locale(app()->getLocale())->translatedFormat(config('timeformat')) }}
                                    {{-- {{date(config('timeformat'),strtotime($order['created_at']))}} --}}
                                </div>
                            </td>
                            <td>
                                @if($order->customer)
                                    <a class="text-body text-capitalize"
                                       href="{{route('admin.customer.view',[$order['user_id']])}}">{{$order->customer['f_name'].' '.$order->customer['l_name']}}</a>
                                       <p class="phone">
                                        {{$order->customer['phone']}}
                                   </p>
                                @else
                                    <label class="badge badge-danger">{{translate('messages.invalid')}} {{translate('messages.customer')}} {{translate('messages.data')}}</label>
                                @endif
                            </td>
                            <td>
                                {{$order->restaurant?$order->restaurant->name:'Restaurant deleted!'}}
                            </td>
                            <td>
                                <div class="text-right mw-85">
                                    <div>
                                        {{\App\CentralLogics\Helpers::format_currency($order['order_amount'])}}
                                    </div>
                                    @if($order->payment_status=='paid')
                                        <strong class="text-success">{{translate('messages.paid')}}</strong>
                                    @else
                                        <strong class="text-danger">{{translate('messages.unpaid')}}</strong>
                                    @endif
                                </div>
                            </td>
                            <td class="text-capitalize text-center">
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
                                @else
                                    <span class="badge badge-soft-danger">
                                      {{translate(str_replace('_',' ',$order['order_status']))}}
                                    </span>
                                @endif

                                <div class="mt-1 opacity-7">
                                    @if($order['order_type']=='take_away')
                                    <span>
                                        {{translate('messages.take_away')}}
                                    </span>
                                    @else
                                    <span>
                                        {{translate('messages.home')}} {{translate('messages.delivery')}}
                                    </span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="btn--container justify-content-center">
                                    <a class="btn btn-sm btn--warning btn-outline-warning action-btn" href="{{route('admin.order.details',['id'=>$order['id']])}}"><i class="tio-visible"></i></a>
                                    <a class="btn btn-sm btn--primary btn-outline-primary action-btn" target="_blank" href="{{route('admin.order.generate-invoice',[$order['id']])}}"><i class="tio-print"></i></a>
                                </div>
                            </td>
                        </tr>

                    @endforeach
                    </tbody>
                </table>
            </div>
            {{-- {{ dd($orders) }} --}}
            @if (count($orders) === 0)
            <div class="empty--data">
                <img src="{{asset('/public/assets/admin/img/empty.png')}}" alt="public">
                <h5>
                    {{translate('no_data_found')}}
                </h5>
            </div>
            @endif
            <!-- End Table -->

            <!-- Start Footer -->
            <div class="card-footer p-0 border-0">
                <!-- Pagination -->
                <div class="page-area px-4 pb-3">
                    <div class="d-flex align-items-center justify-content-end">
                        <div>
                            {!! $orders->links() !!}
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Footer -->

        </div>
        <!-- End Card -->
        <!-- Order Filter Modal -->
        <div id="datatableFilterSidebar" class="hs-unfold-content_ sidebar sidebar-bordered sidebar-box-shadow initial-hidden">
            <div class="card card-lg sidebar-card sidebar-footer-fixed">
                <div class="card-header">
                    <h4 class="card-header-title">{{translate('messages.order')}} {{translate('messages.filter')}}</h4>

                    <!-- Toggle Button -->
                    <a class="js-hs-unfold-invoker_ btn btn-icon btn-xs btn-ghost-dark ml-2" href="javascript:;"
                    onclick="$('#datatableFilterSidebar,.hs-unfold-overlay').hide(500)">
                        <i class="tio-clear tio-lg"></i>
                    </a>
                    <!-- End Toggle Button -->
                </div>

                <!-- Body -->
                <form class="card-body sidebar-body sidebar-scrollbar" action="{{route('admin.order.filter')}}" method="POST" id="order_filter_form">
                    @csrf
                    <small class="text-cap mb-3">{{translate('messages.zone')}}</small>

                    <div class="mb-2 initial-36">
                        <select name="zone[]" id="zone_ids" class="form-control js-select2-custom" multiple="multiple">
                        @foreach(\App\Models\Zone::all() as $zone)
                            <option value="{{$zone->id}}" {{isset($zone_ids)?(in_array($zone->id, $zone_ids)?'selected':''):''}}>{{$zone->name}}</option>
                        @endforeach
                        </select>
                    </div>
                    <hr class="my-4">
                    <small class="text-cap mb-3">{{translate('messages.restaurant')}}</small>
                    <div class="mb-2 initial-36">
                        <select name="vendor[]" id="vendor_ids" class="form-control js-select2-custom" multiple="multiple">
                        @foreach(\App\Models\Restaurant::whereIn('id', $vendor_ids)->get() as $restaurant)
                            <option value="{{$restaurant->id}}" selected >{{$restaurant->name}}</option>
                        @endforeach
                        </select>
                    </div>

                    <hr class="my-4">
                    @if($status == 'all')
                    <small class="text-cap mb-3">{{translate('messages.order')}} {{translate('messages.status')}}</small>

                    <!-- Custom Checkbox -->
                    <div class="custom-control custom-radio mb-2">
                        <input type="checkbox" id="orderStatus2" name="orderStatus[]" class="custom-control-input" {{isset($orderstatus)?(in_array('pending', $orderstatus)?'checked':''):''}} value="pending">
                        <label class="custom-control-label" for="orderStatus2">{{translate('messages.pending')}}</label>
                    </div>
                    <div class="custom-control custom-radio mb-2">
                        <input type="checkbox" id="orderStatus1" name="orderStatus[]" class="custom-control-input" value="confirmed" {{isset($orderstatus)?(in_array('confirmed', $orderstatus)?'checked':''):''}}>
                        <label class="custom-control-label" for="orderStatus1">{{translate('messages.confirmed')}}</label>
                    </div>
                    <div class="custom-control custom-radio mb-2">
                        <input type="checkbox" id="orderStatus3" name="orderStatus[]" class="custom-control-input" value="processing" {{isset($orderstatus)?(in_array('processing', $orderstatus)?'checked':''):''}}>
                        <label class="custom-control-label" for="orderStatus3">{{translate('messages.processing')}}</label>
                    </div>
                    <div class="custom-control custom-radio mb-2">
                        <input type="checkbox" id="orderStatus4" name="orderStatus[]" class="custom-control-input" value="picked_up" {{isset($orderstatus)?(in_array('picked_up', $orderstatus)?'checked':''):''}}>
                        <label class="custom-control-label" for="orderStatus4">{{translate('messages.out_for_delivery')}}</label>
                    </div>
                    <div class="custom-control custom-radio mb-2">
                        <input type="checkbox" id="orderStatus5" name="orderStatus[]" class="custom-control-input" value="delivered" {{isset($orderstatus)?(in_array('delivered', $orderstatus)?'checked':''):''}}>
                        <label class="custom-control-label" for="orderStatus5">{{translate('messages.delivered')}}</label>
                    </div>
                    <div class="custom-control custom-radio mb-2">
                        <input type="checkbox" id="orderStatus6" name="orderStatus[]" class="custom-control-input" value="returned" {{isset($orderstatus)?(in_array('returned', $orderstatus)?'checked':''):''}}>
                        <label class="custom-control-label" for="orderStatus6">{{translate('messages.returned')}}</label>
                    </div>
                    <div class="custom-control custom-radio mb-2">
                        <input type="checkbox" id="orderStatus7" name="orderStatus[]" class="custom-control-input" value="failed" {{isset($orderstatus)?(in_array('failed', $orderstatus)?'checked':''):''}}>
                        <label class="custom-control-label" for="orderStatus7">{{translate('messages.failed')}}</label>
                    </div>
                    <div class="custom-control custom-radio mb-2">
                        <input type="checkbox" id="orderStatus8" name="orderStatus[]" class="custom-control-input" value="canceled" {{isset($orderstatus)?(in_array('canceled', $orderstatus)?'checked':''):''}}>
                        <label class="custom-control-label" for="orderStatus8">{{translate('messages.canceled')}}</label>
                    </div>
                    <div class="custom-control custom-radio mb-2">
                        <input type="checkbox" id="orderStatus9" name="orderStatus[]" class="custom-control-input" value="refund_requested" {{isset($orderstatus)?(in_array('refund_requested', $orderstatus)?'checked':''):''}}>
                        <label class="custom-control-label" for="orderStatus9">{{translate('messages.refundRequest')}}</label>
                    </div>
                    <div class="custom-control custom-radio mb-2">
                        <input type="checkbox" id="orderStatus10" name="orderStatus[]" class="custom-control-input" value="refunded" {{isset($orderstatus)?(in_array('refunded', $orderstatus)?'checked':''):''}}>
                        <label class="custom-control-label" for="orderStatus10">{{translate('messages.refunded')}}</label>
                    </div>

                    <hr class="my-4">

                    <div class="custom-control custom-radio mb-2">
                        <input type="checkbox" id="scheduled" name="scheduled" class="custom-control-input" value="1" {{isset($scheduled)?($scheduled==1?'checked':''):''}}>
                        <label class="custom-control-label text-uppercase" for="scheduled">{{translate('messages.scheduled')}}</label>
                    </div>
                    <hr class="my-4">
                    <small class="text-cap mb-3">{{translate('messages.order')}} {{translate('messages.type')}}</small>
                    <div class="custom-control custom-radio mb-2">
                        <input type="radio" id="take_away" name="order_type" class="custom-control-input" value="take_away" {{isset($order_type)?($order_type=='take_away'?'checked':''):''}}>
                        <label class="custom-control-label text-uppercase" for="take_away">{{translate('messages.take_away')}}</label>
                    </div>
                    <div class="custom-control custom-radio mb-2">
                        <input type="radio" id="delivery" name="order_type" class="custom-control-input" value="delivery" {{isset($order_type)?($order_type=='delivery'?'checked':''):''}}>
                        <label class="custom-control-label text-uppercase" for="delivery">{{translate('messages.delivery')}}</label>
                    </div>
                    <hr class="my-4">
                    @endif
                    <small class="text-cap mb-3">{{translate('messages.date')}} {{translate('messages.between')}}</small>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group mb-0">
                                <input type="date" name="from_date" class="form-control" id="date_from" value="{{isset($from_date)?$from_date:''}}">
                            </div>
                        </div>
                        <div class="col-12 text-center">{{translate('messages.----to----')}}</div>
                        <div class="col-12">
                            <div class="form-group">
                                <input type="date" name="to_date" class="form-control" id="date_to" value="{{isset($to_date)?$to_date:''}}">
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="card-footer sidebar-footer">
                        <div class="row gx-2">
                            <div class="col">
                                <button type="reset" class="btn btn-block btn-white" id="reset">{{translate('messages.clear_all_filters')}}</button>
                            </div>
                            <div class="col">
                                <button type="submit" class="btn btn-block btn-primary">{{translate('messages.save')}}</button>
                            </div>
                        </div>
                    </div>
                    <!-- End Footer -->
                </form>
            </div>
        </div>
        <!-- End Order Filter Modal -->
@endsection

@push('script_2')
    <!-- <script src="{{asset('public/assets/admin')}}/js/bootstrap-select.min.js"></script> -->
    <script>
        <?php
            $filter_count=0;
            if(isset($zone_ids) && count($zone_ids) > 0) $filter_count += 1;
            if(isset($vendor_ids) && count($vendor_ids)>0) $filter_count += 1;
            if($status=='all')
            {
                if(isset($orderstatus) && count($orderstatus) > 0) $filter_count += 1;
                if(isset($scheduled) && $scheduled == 1) $filter_count += 1;
            }

            if(isset($from_date) && isset($to_date)) $filter_count += 1;
            if(isset($order_type)) $filter_count += 1;

        ?>

            @if($filter_count>0)
            $('#filter_count').html({{$filter_count}});
            @endif

        function filter_zone_orders(id) {
            location.href = '{{url('/')}}/admin/order/zone-filter/' + id;
        }
        $(document).on('ready', function () {
            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function () {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });

            var zone_id = [];
            $('#zone_ids').on('change', function(){
                if($(this).val())
                {
                    zone_id = $(this).val();
                }
                else
                {
                    zone_id = [];
                }
            });


            $('#vendor_ids').select2({
                ajax: {
                    url: '{{url('/')}}/admin/restaurant/get-restaurants',
                    data: function (params) {
                        return {
                            q: params.term, // search term
                            zone_ids: zone_id,
                            page: params.page
                        };
                    },
                    processResults: function (data) {
                        return {
                        results: data
                        };
                    },
                    __port: function (params, success, failure) {
                        var $request = $.ajax(params);

                        $request.then(success);
                        $request.fail(failure);

                        return $request;
                    }
                }
            });

            // INITIALIZATION OF DATATABLES
            // =======================================================
            var datatable = $.HSCore.components.HSDatatables.init($('#datatable'), {
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'copy',
                        className: 'd-none'
                    },
                    {
                        extend: 'excel',
                        className: 'd-none'
                    },
                    {
                        extend: 'csv',
                        className: 'd-none'
                    },
                    {
                        extend: 'pdf',
                        className: 'd-none'
                    },
                    {
                        extend: 'print',
                        className: 'd-none'
                    },
                ],
                select: {
                    style: 'multi',
                    selector: 'td:first-child input[type="checkbox"]',
                    classMap: {
                        checkAll: '#datatableCheckAll',
                        counter: '#datatableCounter',
                        counterInfo: '#datatableCounterInfo'
                    }
                },
                language: {
                    zeroRecords: '<div class="text-center p-4">' +
                        '<img class="mb-3 w-7rem" src="{{asset('public/assets/admin')}}/svg/illustrations/sorry.svg" alt="Image Description">' +
                        '<p class="mb-0">{{ translate('No data to show') }}</p>' +
                        '</div>'
                }
            });

            $('#export-copy').click(function () {
                datatable.button('.buttons-copy').trigger()
            });

            $('#export-excel').click(function () {
                datatable.button('.buttons-excel').trigger()
            });

            $('#export-csv').click(function () {
                datatable.button('.buttons-csv').trigger()
            });

            $('#export-pdf').click(function () {
                datatable.button('.buttons-pdf').trigger()
            });

            $('#export-print').click(function () {
                datatable.button('.buttons-print').trigger()
            });

            $('#datatableSearch').on('mouseup', function (e) {
                var $input = $(this),
                    oldValue = $input.val();

                if (oldValue == "") return;

                setTimeout(function () {
                    var newValue = $input.val();

                    if (newValue == "") {
                        // Gotcha
                        datatable.search('').draw();
                    }
                }, 1);
            });

            $('#toggleColumn_order').change(function (e) {
                datatable.columns(1).visible(e.target.checked)
            })

            $('#toggleColumn_date').change(function (e) {
                datatable.columns(2).visible(e.target.checked)
            })

            $('#toggleColumn_customer').change(function (e) {
                datatable.columns(3).visible(e.target.checked)
            })
            $('#toggleColumn_restaurant').change(function (e) {
                datatable.columns(4).visible(e.target.checked)
            })

/*             $('#toggleColumn_payment_status').change(function (e) {
                datatable.columns(5).visible(e.target.checked)
            }) */

            $('#toggleColumn_total').change(function (e) {
                datatable.columns(5).visible(e.target.checked)
            })

            $('#toggleColumn_order_status').change(function (e) {
                datatable.columns(6).visible(e.target.checked)
            })

            $('#toggleColumn_actions').change(function (e) {
                datatable.columns(7).visible(e.target.checked)
            })
            // INITIALIZATION OF TAGIFY
            // =======================================================
            $('.js-tagify').each(function () {
                var tagify = $.HSCore.components.HSTagify.init($(this));
            });

            $("#date_from").on("change", function () {
                $('#date_to').attr('min',$(this).val());
            });

            $("#date_to").on("change", function () {
                $('#date_from').attr('max',$(this).val());
            });
        });

        $('#reset').on('click', function(){
            // e.preventDefault();
            location.href = '{{url('/')}}/admin/order/filter/reset';
        });
    </script>


@endpush
