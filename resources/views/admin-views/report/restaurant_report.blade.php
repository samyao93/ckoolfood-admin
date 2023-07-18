@extends('layouts.admin.app')

@section('title', translate('messages.restaurant_report'))

@push('css_or_js')
@endpush

@section('content')

    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title">
                <i class="tio-filter-list"></i>
                <span>
                    {{ translate('messages.restaurant_report') }}
                    @if ($from && $to)
                        <span class="h6 mb-0 badge badge-soft-success ml-2">
                            ( {{ $from }} - {{ $to }} )</span>
                    @endif
                </span>
            </h1>
        </div>
        <!-- End Page Header -->

        <div class="card mb-20">
            <div class="card-body">
                <h4 class="">{{ translate('Search Data') }}</h4>
                <form method="get">
                    <div class="row g-3">
                        <div class="col-sm-6 col-md-3 ">
                            <select name="zone_id" class="form-control js-select2-custom"
                                onchange="set_zone_filter('{{ url()->full() }}',this.value)" id="zone">
                                <option value="all">{{ translate('messages.All Zones') }}</option>
                                @foreach (\App\Models\Zone::orderBy('name')->get() as $z)
                                    <option value="{{ $z['id'] }}"
                                        {{ isset($zone) && $zone->id == $z['id'] ? 'selected' : '' }}>
                                        {{ $z['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6 col-md-3 ">

                            @php($typ= request()->restaurant_model)
                                <select name="restaurant_model"
                                onchange="set_filter('{{url()->full()}}',this.value, 'restaurant_model')"
                                data-placeholder="{{translate('messages.all')}}" class="form-control js-select2-custom">
                                    {{-- <option selected disabled>{{translate('messages.restaurant_model')}}</option> --}}
                                    <option value="all" {{$typ=='all'?'selected':''}}>{{translate('messages.all')}}</option>
                                    <option value="commission" {{$typ=='commission'?'selected':''}}>{{translate('messages.Commission')}}</option>
                                    <option value="subscribed" {{$typ=='subscribed'?'selected':''}}>{{translate('messages.Subscribed')}}</option>
                                    <option value="unsubscribed" {{$typ=='unsubscribed'?'selected':''}}>{{translate('messages.Unsubscribed')}}</option>

                                </select>
                        </div>
                        <div class="col-sm-6 col-md-3 ">
                            @php($type= request()->type)

                            <!-- Veg/NonVeg filter -->
                            <select name="type"
                            onchange="set_filter('{{url()->full()}}',this.value, 'type')"
                            data-placeholder="{{translate('messages.select_type')}}" class="form-control js-select2-custom">
                                <option value="all" {{$type=='all'?'selected':''}}>{{translate('messages.all_types')}}</option>
                                @if ($toggle_veg_non_veg)
                                <option value="veg" {{$type=='veg'?'selected':''}}>{{translate('messages.veg')}}</option>
                                <option value="non_veg" {{$type=='non_veg'?'selected':''}}>{{translate('messages.non_veg')}}</option>
                                @endif
                            </select>

                        <!-- End Veg/NonVeg filter -->
                        </div>
                        <div class="col-sm-6 col-md-3 ">
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
                                    placeholder="{{ translate('Start Date') }}" value={{ $from ? $from : '' }} required>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <input type="date" name="to" id="to_date" class="form-control"
                                    placeholder="{{ translate('End Date') }}" value={{ $to ? $to : '' }} required>
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

        <!-- End Stats -->


        <div class="row gx-2 gx-lg-3">
            <div class="col-lg-12 mb-3 mb-lg-12">
                <!-- Card -->
                <div class="card h-100">
                    <div class="card-header flex-wrap justify-content-evenly justify-content-lg-between border-0">
                        <h4 class="card-title my-2 my-md-0">
                            <i class="tio-chart-bar-4"></i>
                            {{ translate('messages.Order_Statistics') }}
                        </h4>
                        <div class="d-flex flex-wrap my-2 my-md-0 justify-content-center align-items-center">
                            <span class="h5 m-0 fz--11 d-flex align-items-center mb-2 mb-md-0">
                                {{-- <span class="legend-indicator bg-0661CB"></span> --}}
                                {{ translate('messages.Average_Order_Value') }} :
                                {{ \App\CentralLogics\Helpers::format_currency(array_sum($data_avg)) }}
                            </span>
                        </div>
                    </div>
                    <!-- Body -->
                    <div class="card-body">
                        <!-- Bar Chart -->
                        <div class="d-flex align-items-center">
                            <div class="chart--extension">
                                {{ \App\CentralLogics\Helpers::currency_symbol() }}({{ translate('messages.currency') }})
                            </div>
                            <div class="chartjs-custom w-75 flex-grow-1 h-20rem">
                                <canvas id="chart1">
                                </canvas>
                            </div>
                        </div>
                        <!-- End Bar Chart -->
                    </div>
                    <!-- End Body -->
                </div>
                <!-- End Card -->
            </div>
        </div>
    <!-- Card -->
    <div class="row gx-2 gx-lg-3">
            <!-- Card -->
            <div class="col-12">
                <div class="card h-100">
                    <!-- Header -->
                    <div class="card-header border-0 py-2">
                        <div class="search--button-wrapper">
                            <h3 class="card-title">
                                {{ translate('restaurant report table') }}<span class="badge badge-soft-secondary"
                                    id="countrestaurants">{{ $restaurants->total() }}</span>
                            </h3>
                            <form class="search-form">
                                <!-- Search -->
                                <div class="input--group input-group">
                                    <input id="datatableSearch" name="search" type="search" class="form-control"
                                        placeholder="{{ translate('ex_:_search_restaurant_name') }}" value="{{ request()->search ?? null }}"
                                        aria-label="{{ translate('messages.search_here') }}">
                                    <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                                </div>
                                <!-- End Search -->
                            </form> <!-- Unfold -->
                            <div class="hs-unfold ml-3">
                                <a class="js-hs-unfold-invoker btn btn-sm btn-white dropdown-toggle min-height-40" href="javascript:;"
                                    data-hs-unfold-options='{
                                                "target": "#usersExportDropdown",
                                                "type": "css-animation"
                                            }'>
                                    <i class="tio-download-to mr-1"></i> {{ translate('messages.export') }}
                                </a>

                                <div id="usersExportDropdown"
                                    class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-sm-right">
                                    <span class="dropdown-header">{{ translate('messages.download') }}
                                        {{ translate('messages.options') }}</span>
                                    <a id="export-excel" class="dropdown-item"
                                        href="{{ route('admin.report.restaurant-wise-report-export', ['type' => 'excel', request()->getQueryString()]) }}">
                                        <img class="avatar avatar-xss avatar-4by3 mr-2"
                                            src="{{ asset('public/assets/admin') }}/svg/components/excel.svg" alt="Image Description">
                                        {{ translate('messages.excel') }}
                                    </a>
                                    <a id="export-csv" class="dropdown-item"
                                        href="{{ route('admin.report.restaurant-wise-report-export', ['type' => 'csv', request()->getQueryString()]) }}">
                                        <img class="avatar avatar-xss avatar-4by3 mr-2"
                                            src="{{ asset('public/assets/admin') }}/svg/components/placeholder-csv-format.svg"
                                            alt="Image Description">
                                        .{{ translate('messages.csv') }}
                                    </a>
                                </div>
                            </div>


                            <!-- End Unfold -->
                        </div>
                        <!-- End Row -->
                    </div>
                    <!-- End Header -->
                    <div class="card-body">
                    <!-- Table -->
                        <div class="table-responsive datatable-custom" id="table-div">
                            <table id="datatable" class="table table-borderless table-thead-bordered table-nowrap card-table"
                                data-hs-datatables-options='{
                                        "columnDefs": [{
                                            "targets": [],
                                            "width": "5%",
                                            "orderable": false
                                        }],
                                        "order": [],
                                        "info": {
                                        "totalQty": "#datatableWithPaginationInfoTotalQty"
                                        },

                                        "entries": "#datatableEntries",

                                        "isResponsive": false,
                                        "isShowPaging": false,
                                        "paging":false
                                    }'>
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{ translate('sl') }}</th>
                                        <th class="w--2">{{ translate('messages.restaurant') }}</th>
                                        <th>{{ translate('messages.total_food') }}</th>
                                        <th>{{ translate('messages.total_order') }}</th>
                                        <th>{{ translate('messages.total_order') }} {{ translate('messages.amount') }}</th>
                                        <th>{{ translate('messages.total_discount_given') }}</th>
                                        <th>{{ translate('messages.total_admin_commission') }}</th>
                                        <th>{{ translate('messages.total_vat_tax') }}</th>
                                        <th>{{ translate('messages.average_ratings') }}</th>
                                    </tr>
                                </thead>

                                <tbody id="set-rows">

                                    @foreach ($restaurants as $key => $restaurant)
                                        <tr>
                                            <td>{{ $key + $restaurants->firstItem() }}</td>
                                            <td>
                                                <a href="{{ route('admin.restaurant.view', $restaurant->id) }}" alt="view restaurant"
                                                    class="table-rest-info">
                                                    <img onerror="this.src='{{ asset('public/assets/admin/img/100x100/food-default-image.png') }}'"
                                                        src="{{ asset('storage/app/public/restaurant') }}/{{ $restaurant['logo'] }}">
                                                    <div class="info">
                                                        <span class="d-block text-body">
                                                            {{ Str::limit($restaurant->name, 20, '...') }}<br>
                                                        </span>
                                                    </div>
                                                </a>
                                            </td>

                                            <td>
                                                {{ $restaurant->foods_count }}
                                            </td>
                                            <td>
                                                {{$restaurant->without_refund_total_orders_count }}
                                            </td>
                                            <td>
                                                {{ \App\CentralLogics\Helpers::format_currency($restaurant->transaction_sum_order_amount) }}
                                            </td>
                                            <td>
                                                {{\App\CentralLogics\Helpers::format_currency( $restaurant->transaction_sum_restaurant_expense)}}
                                            </td>
                                            <td>
                                                {{\App\CentralLogics\Helpers::format_currency( $restaurant->transaction_sum_admin_commission)}}
                                            </td>
                                            <td>
                                                {{\App\CentralLogics\Helpers::format_currency( $restaurant->transaction_sum_tax)}}
                                            </td>
                                            <td>
                                                <div class="info">
                                                            <!-- Rating -->
                                                            <span class="rating">
                                                                    @if ($restaurant->reviews_count)
                                                                    @php($reviews_count = $restaurant->reviews_count)
                                                                    @php($reviews = $reviews_count)
                                                                    @else
                                                                    @php($reviews = 0)
                                                                    @php($reviews_count = 1)
                                                                    @endif
                                                                <i class="tio-star"></i> {{ round($restaurant->reviews_sum_rating /$reviews_count,1) }}
                                                                 ({{ $reviews }})
                                                            </span>

                                                            <!-- Rating -->
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @if (count($restaurants) !== 0)
                                <hr>
                            @endif
                            <div class="page-area px-4 pb-3">
                                {!! $restaurants->links() !!}
                            </div>
                            @if (count($restaurants) === 0)
                                <div class="empty--data">
                                    <img src="{{ asset('/public/assets/admin/svg/illustrations/sorry.svg') }}" alt="public">
                                    <h5>
                                        {{ translate('no_data_found') }}
                                    </h5>
                                </div>
                            @endif
                        </div>
                    <!-- End Table -->
                    </div>
                </div>
            </div>
        </div>
    <!-- End Card -->
    </div>


@endsection

@push('script')
    <script src="{{ asset('public/assets/admin') }}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{ asset('public/assets/admin') }}/vendor/chart.js.extensions/chartjs-extensions.js"></script>
    <script
        src="{{ asset('public/assets/admin') }}/vendor/chartjs-plugin-datalabels/dist/chartjs-plugin-datalabels.min.js">
    </script>
@endpush

@push('script_2')


    <script>
        // INITIALIZATION OF CHARTJS
        // =======================================================
        Chart.plugins.unregister(ChartDataLabels);

        $('.js-chart').each(function() {
            $.HSCore.components.HSChartJS.init($(this));
        });

        var updatingChart = $.HSCore.components.HSChartJS.init($("#updatingData"));
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

        function getRequest(route, id) {
            $.get({
                url: route,
                dataType: 'json',
                success: function(data) {
                    $('#' + id).empty().append(data.options);
                },
            });
        }

    </script>
        {{-- // labels: ["Jan","Feb","Mar","April","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"], --}}
        {{-- // var footerLine1 = [{{ $total_vendor_count[1] }},{{ $total_vendor_count[2] }},{{ $total_vendor_count[3] }},{{ $total_vendor_count[4] }},{{ $total_vendor_count[5] }},{{ $total_vendor_count[6] }},{{ $total_vendor_count[7] }},{{ $total_vendor_count[8] }},{{ $total_vendor_count[9] }},{{ $total_vendor_count[10] }},{{ $total_vendor_count[11] }},{{ $total_vendor_count[12] }}];

        // var footerLine2 = [{{ $total_admin_commission[1] }},{{ $total_admin_commission[2] }},{{ $total_admin_commission[3] }},{{ $total_admin_commission[4] }},{{ $total_admin_commission[5] }},{{ $total_admin_commission[6] }},{{ $total_admin_commission[7] }},{{ $total_admin_commission[8] }},{{ $total_admin_commission[9] }},{{ $total_admin_commission[10] }},{{ $total_admin_commission[11] }},{{ $total_admin_commission[12] }}];

        // var footerLine3 = [{{ $total_tax_amount[1] }},{{ $total_tax_amount[2] }},{{ $total_tax_amount[3] }},{{ $total_tax_amount[4] }},{{ $total_tax_amount[5] }},{{ $total_tax_amount[6] }},{{ $total_tax_amount[7] }},{{ $total_tax_amount[8] }},{{ $total_tax_amount[9] }},{{ $total_tax_amount[10] }},{{ $total_tax_amount[11] }},{{ $total_tax_amount[12] }}]; --}}
    <script>
        var ctx = document.getElementById('chart1').getContext("2d");
        var data = {

            labels:[{!! implode(',',$label) !!}],

        datasets: [
            {
                label: "{{  translate('total_order_amount') }}",
                fill: false,
                lineTension: 0.1,
                // backgroundColor: "rgba(75,192,192,0.4)",
                // borderColor: "rgba(75,192,192,1)",
                // borderCapStyle: 'butt',
                borderDash: [],
                borderDashOffset: 0.0,
                borderJoinStyle: 'miter',
                // pointBorderColor: "rgba(75,192,192,1)",
                // pointBackgroundColor: "#fff",
                pointBorderWidth: 1,
                pointHoverRadius: 5,
                // pointHoverBackgroundColor: "rgba(75,192,192,1)",
                // pointHoverBorderColor: "rgba(220,220,220,1)",
                pointHoverBorderWidth: 2,
                pointRadius: 1,
                pointHitRadius: 10,
                // data: [65, 59, 80, 81, 56, 55, 40],
                spanGaps: false,
            data:[{{ implode(',', $data) }}],
            backgroundColor: "#7ECAFF",
            hoverBackgroundColor: "#7ECAFF",
            borderColor: "#7ECAFF",
            }
        ]
        };



        var options = {
            responsive: true,
            maintainAspectRatio: false,
            title: {
                display: true,
                position: "top",
                // text: 'anything',
                fontSize: 18,
                fontColor: "#111"
            },
            legend: {
                display: true,
                position: "bottom",
                labels: {
                    fontColor: "#333",
                    fontSize: 16
                }
            },
            cornerRadius: 5,

            tooltips: {
                    enabled: true,
                    hasIndicator: true,
                    // mode: 'single',
                    mode: "index",
                    intersect: false,

                },

        scales: {
            yAxes: [{
            gridLines: {
                color: "#e7eaf3",
                drawBorder: false,
                zeroLineColor: "#e7eaf3"
            },
            ticks: {
                beginAtZero: true,
                stepSize: {{ceil((array_sum($data)/10000))*2000}},
                fontSize: 12,
                fontColor: "#97a4af",
                fontFamily: "Open Sans, sans-serif",
                padding: 10
            }
            }],
            xAxes: [{
            gridLines: {
                display: false,
                drawBorder: false
            },
            ticks: {
                fontSize: 12,
                fontColor: "#97a4af",
                fontFamily: "Open Sans, sans-serif",
                padding: 5
            },
            categoryPercentage: 0.3,
            maxBarThickness: "10"
            }]
        },

        hover: {
            mode: "nearest",
            intersect: true,
            },
            };

    var myLineChart = new Chart(ctx, {
        type: 'bar',
        data: data,
        options: options
    });

    </script>

@endpush
