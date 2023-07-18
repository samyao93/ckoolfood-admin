@extends('layouts.admin.app')

@section('title',translate('messages.visitor_logs'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title">
                        {{-- <i class="tio-car"></i> --}}
                        {{translate('messages.visitor_logs')}} <span class="badge badge-soft-dark ml-2" id="itemCount">{{$logs->total()}}</span></h1>
                </div>

                {{-- <div class="col-sm-auto">
                    <a class="btn btn--primary" href="{{route('admin.vehicle.create')}}">
                        <i class="tio-add"></i> {{translate('messages.add_vehicle_category')}}
                    </a>
                </div> --}}
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <!-- Card -->
                <div class="card">
                    <div class="card-header py-2 border-0">
                        <div class="search--button-wrapper">
                            <h5 class="card-title"></h5>
                            {{-- <form id="search-form">
                                <!-- Search -->
                                <div class="input--group input-group input-group-merge input-group-flush">
                                    <input id="datatableSearch" type="search" name="search" class="form-control" placeholder="{{ translate('Ex: Search by type...') }}" aria-label="Search here">
                                    <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                                </div>
                                <!-- End Search -->
                            </form> --}}

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
                        </div>
                    </div>
                    <!-- Table -->
                    <div class="table-responsive datatable-custom">
                        <table id="columnSearchDatatable"
                                class="font-size-sm table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                                data-hs-datatables-options='{
                                    "order": [],
                                    "orderCellsTop": true,
                                    "paging":false
                                }'>
                            <thead class="thead-light">
                            <tr>
                                <th>{{ translate('messages.sl') }}</th>
                                <th >{{translate('messages.Customer_name')}}</th>
                                <th >{{translate('messages.reastaurant')}}  </th>
                                <th >{{translate('messages.category')}} </th>
                                <th >{{translate('messages.Visit_count')}} </th>
                                <th >{{translate('messages.Order_count')}} </th>
                            </tr>
                            </thead>

                            <tbody id="set-rows">
                            @foreach($logs as $key=>$log)
                                <tr>
                                    <td>{{$key+$logs->firstItem()}}</td>
                                    <td>
                                        @if ( isset($log->users) )
                                            <span class="d-block text-body">
                                                <a href="{{route('admin.customer.view',[$log->user_id])}}" class="text--title text-hover">
                                                    {{ $log->users->f_name .' '. $log->users->l_name }}
                                                </a>
                                            </span>
                                        @else
                                        <span class="bg-gradient-light">
                                            {{ translate('messages.no_data_found')}}
                                        </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ( $log->visitor_log_type == 'App\Models\Restaurant')
                                            <span class="bg-gradient-light text-dark">

                                                <a class="text--title text-hover"  href="{{route('admin.restaurant.view', $log->visitor_log->id)  }}">
                                                    {{ $log->visitor_log->name }}
                                                </a>
                                            </span>
                                        @else
                                            <span class="bg-gradient-light">
                                                {{ translate('messages.no_data_found')}}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ( $log->visitor_log_type == 'App\Models\Category')
                                            <span class="bg-gradient-light text-dark">
                                                {{ $log->visitor_log->name }}
                                            </span>
                                        @else
                                            <span class="bg-gradient-light">
                                                {{ translate('messages.no_data_found')}}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="bg-gradient-light text-dark">
                                            {{ $log->visit_count }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="bg-gradient-light text-dark">
                                            {{ $log->order_count }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @if(count($logs) === 0)
                        <div class="empty--data">
                            <img src="{{asset('/public/assets/admin/img/empty.png')}}" alt="public">
                            <h5>
                                {{translate('no_data_found')}}
                            </h5>
                        </div>
                        @endif
                        <div class="page-area px-4 pb-3">
                            <div class="d-flex align-items-center justify-content-end">
                                <div>
                                    {!! $logs->links() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Table -->
                </div>
                <!-- End Card -->
            </div>
        </div>
    </div>

@endsection

@push('script_2')
    <script>
        $(document).on('ready', function () {
            // INITIALIZATION OF DATATABLES
            // =======================================================
            var datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));

            $('#column1_search').on('keyup', function () {
                datatable
                    .search(this.value)
                    .draw();
            });

            $('#column2_search').on('keyup', function () {
                datatable
                    .columns(2)
                    .search(this.value)
                    .draw();
            });

            $('#column3_search').on('change', function () {
                datatable
                    .columns(3)
                    .search(this.value)
                    .draw();
            });

            $('#column4_search').on('keyup', function () {
                datatable
                    .columns(4)
                    .search(this.value)
                    .draw();
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
@endpush
