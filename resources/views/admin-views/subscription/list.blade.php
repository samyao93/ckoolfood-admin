@extends('layouts.admin.app')

@section('title',translate('Subscribed Restaurants List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')

    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title"><i class="tio-filter-list"></i> {{translate('messages.Subscribed Restaurants List')}} <span class="badge badge-soft-dark ml-2" id="itemCount">{{$restaurants->total()}}</span></h1>
            <div class="page-header-select-wrapper">
                {{-- @if ($toggle_veg_non_veg)
                <div class="select-item">
                <!-- Veg/NonVeg filter -->
                    <select name="category_id" onchange="set_filter('{{url()->full()}}',this.value, 'type')" data-placeholder="{{translate('messages.all')}}" class="form-control w--sm-unset ml-auto">
                        <option value="all" {{$type=='all'?'selected':''}}>{{translate('messages.all')}}</option>
                        <option value="veg" {{$type=='veg'?'selected':''}}>{{translate('messages.veg')}}</option>
                        <option value="non_veg" {{$type=='non_veg'?'selected':''}}>{{translate('messages.non_veg')}}</option>
                    </select>
                <!-- End Veg/NonVeg filter -->
                </div>
                @endif --}}
                @if(!isset(auth('admin')->user()->zone_id))
                    <div class="select-item">
                        <select name="zone_id" class="form-control js-select2-custom"
                                onchange="set_zone_filter('{{route('admin.subscription.subscription_list')}}',this.value)">
                            <option selected disabled>{{translate('messages.select_zone')}}</option>
                            <option value="all">{{translate('messages.all_zones')}}</option>
                            @foreach(\App\Models\Zone::orderBy('name')->get() as $z)
                                <option
                                    value="{{$z['id']}}" {{isset($zone) && $zone->id == $z['id']?'selected':''}}>
                                    {{$z['name']}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>
        </div>
        <!-- End Page Header -->
        <!-- Resturent Card Wrapper -->
        <div class="row g-3 mb-3">
            <div class="col-xl-3 col-sm-6">
                <a href="{{route('admin.subscription.subscription_list','type=all')}}"  class="text-body d-block">
                <div class="resturant-card bg--1">
                    <h4 class="title">{{$total_restaurant}}</h4>
                    <span class="subtitle">{{translate('messages.Total')}} {{translate('messages.Subscription')}} </span>
                    <img class="resturant-icon" src="{{asset('/public/assets/admin/img/resturant/map-pin.png')}}" alt="resturant">
                </div>
                </a>
            </div>
            <div class="col-xl-3 col-sm-6">
                <a href="{{route('admin.subscription.subscription_list','type=subscribed')}}"  class="text-body d-block">
                <div class="resturant-card bg--2">
                    <h4 class="title">{{$total_active_subscription}}</h4>
                    <span class="subtitle">{{translate('messages.Active')}} {{translate('messages.Subscription')}}</span>
                    <img class="resturant-icon" src="{{asset('/public/assets/admin/img/resturant/active-rest.png')}}" alt="resturant">
                </div>
            </a>
            </div>
            <div class="col-xl-3 col-sm-6">
                <a href="{{route('admin.subscription.subscription_list','type=unsubscribed')}}"  class="text-body d-block">
                <div class="resturant-card bg--3">
                    <h4 class="title">{{$total_inactive_subscription}}</h4>
                    <span class="subtitle"> {{ translate('messages.Expired')}} {{translate('messages.Subscription')}}</span>
                    <img class="resturant-icon" src="{{asset('/public/assets/admin/img/resturant/inactive-rest.png')}}" alt="resturant">
                </div>
            </a>
            </div>
            <div class="col-xl-3 col-sm-6">
                <a href="{{route('admin.subscription.subscription_list','type=expire_soon')}}" class="text-body d-block">
                    <div class="resturant-card bg--4">
                        <h4 class="title">{{$expire_soon}}</h4>
                        <span class="subtitle">{{translate('Expiring Soon')}}</span>
                        <img class="resturant-icon" src="{{asset('/public/assets/admin/img/resturant/new-rest.png')}}" alt="resturant">
                    </div>
                </a>
            </div>
        </div>
        <!-- Resturent Card Wrapper -->
        <!-- Transaction Information -->
        <ul class="transaction--information text-uppercase">
            <li class="text--info">
                <i class="tio-document-text-outlined"></i>
                <div>
                    <span>{{translate('messages.total_transactions')}}</span> <strong>{{$sub_transcations}}</strong>
                </div>
            </li>
            <li class="seperator"></li>
            <li class="text--success">
                <i class="tio-checkmark-circle-outlined success--icon"></i>
                <div>
                    <span>{{translate('total')}} {{translate('messages.earning')}}</span> <strong>{{\App\CentralLogics\Helpers::format_currency($total_earning)}}</strong>
                </div>
            </li>
            <li class="seperator"></li>
            <li class="text--warning">
                <i class="tio-atm"></i>
                <div>
                    <span>{{translate('messages.This Month')}}</span> <strong>{{\App\CentralLogics\Helpers::format_currency($this_month)}}</strong>
                </div>
            </li>
        </ul>
        <!-- Transaction Information -->
        <!-- Resturent List -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <!-- Card -->
                <div class="card">
                    <!-- Card Header -->

                    <div class="card-header py-2 border-0">
                        <div class="search--button-wrapper">
                            <h3 class="card-title">{{translate('messages.restaurants')}} {{translate('messages.list')}}</h3>
                            <div class="select-item">
                                <select name="subscription_list" class="form-control js-select2-custom"
                                onchange="set_filter('{{url()->full()}}',this.value, 'type')">
                                    {{-- <option selected disabled>{{translate('messages.select_zone')}}</option> --}}
                                    <option {{$type=='all'?'selected':''}} value="all">{{translate('messages.all_restaurants')}}</option>
                                    <option {{$type=='subscribed'?'selected':''}} value="subscribed">{{translate('Subscribed')}}</option>
                                    <option {{$type=='unsubscribed'?'selected':''}} value="unsubscribed">{{translate('Unsubscribed')}}</option>
                                    <option {{$type=='expire_soon'?'selected':''}} value="expire_soon">{{translate('messages.Expiring Soon')}}</option>
                                </select>
                            </div>

                            <form action="javascript:" id="search-form" class="my-2 ml-auto mr-sm-2 mr-xl-4 ml-sm-auto flex-grow-1 flex-grow-sm-0">
                                <!-- Search -->
                                @csrf
                                <div class="input--group input-group input-group-merge input-group-flush">
                                    <input id="datatableSearch" type="search" name="search" class="form-control"
                                    placeholder="{{ translate('Ex : search by Restaurant name of Phone number') }}" aria-label="{{translate('messages.search')}}" value="{{ request('key') }}">

                                    <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                                </div>
                                <!-- End Search -->
                            </form>

                        </div>
                    </div>
                    <!-- Card Header -->

                    <!-- Table -->
                    <div class="table-responsive datatable-custom resturant-list-table">
                        <table id="columnSearchDatatable"
                               class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                               data-hs-datatables-options='{
                                 "order": [],
                                 "orderCellsTop": true,
                                 "paging":false

                               }'>
                            <thead class="thead-light">
                            <tr>
                                <th class="text-uppercase w-90px">{{translate('messages.sl')}}</th>
                                <th class="initial-58">{{translate('messages.restaurant')}} {{translate('messages.info')}}</th>
                                <th class="w-230px text-center">{{translate('messages.Package Name')}} </th>
                                <th class="w-130px">{{translate('messages.Package price')}}</th>
                                <th class="w-130px">{{translate('Exp Date')}}</th>
                                <th class="w-100px">{{translate('messages.status')}}</th>
                                <th class="text-center w-60px">{{translate('messages.action')}}</th>
                            </tr>
                            </thead>

                            <tbody id="set-rows">
                                @include('admin-views.subscription.partials._subs_table' , ['restaurants' => $restaurants])
                            </tbody>
                        </table>
                        @if(count($restaurants) === 0)
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
                                    {!! $restaurants->appends(request()->all())->links() !!}
                                    {{-- {!! $restaurants->links() !!} --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Table -->
                </div>
                <!-- End Card -->
            </div>
        </div>
        <!-- Resturent List -->
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
                    .columns(1)
                    .search(this.value)
                    .draw();
            });

            $('#column2_search').on('keyup', function () {
                datatable
                    .columns(2)
                    .search(this.value)
                    .draw();
            });

            $('#column3_search').on('keyup', function () {
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


            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function () {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
    </script>

    <script>
            $('#search-form').on('submit', function (e) {
            e.preventDefault();
            var nurl = new URL("{!! url()->full() !!}");
            nurl.searchParams.set('key', $('#datatableSearch').val());

            location.href = nurl;

            // var formData = new FormData(this);
            // $.ajaxSetup({
            //     headers: {
            //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //     }
            // });
            // $.post({
            //     url: '{{route('admin.subscription.subscription_search')}}',
            //     data: formData,
            //     cache: false,
            //     contentType: false,
            //     processData: false,
            //     beforeSend: function () {
            //         $('#loading').show();
            //     },
            //     success: function (data) {
            //         $('#set-rows').html(data.view);
            //         $('#itemCount').html(data.total);
            //         $('.page-area').hide();
            //     },
            //     complete: function () {
            //         $('#loading').hide();
            //     },
            // });
        });
    </script>
@endpush
