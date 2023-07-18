@extends('layouts.admin.app')

@section('title',$restaurant->name)

@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{asset('public/assets/admin/css/croppie.css')}}" rel="stylesheet">
@endpush

@section('content')
<div class="content container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex flex-wrap justify-content-between align-items-center">
            <h1 class="page-header-title text-break me-2">
                <i class="tio-shop"></i> <span>{{$restaurant->name}}</span>
            </h1>
            @if($restaurant->vendor->status)
            <a href="{{route('admin.restaurant.edit',[$restaurant->id])}}" class="btn btn--primary my-2">
                <i class="tio-edit mr-2"></i> {{translate('messages.edit')}} {{translate('messages.restaurant')}}
            </a>
            @else
                <div>
                    @if(!isset($restaurant->vendor->status))
                    <a class="btn btn--danger text-capitalize my-2"
                    onclick="request_alert('{{route('admin.restaurant.application',[$restaurant['id'],0])}}','{{translate('messages.you_want_to_deny_this_application')}}')"
                        href="javascript:">{{translate('messages.deny')}}</a>
                    @endif
                    <a class="btn btn--primary text-capitalize my-2"
                    onclick="request_alert('{{route('admin.restaurant.application',[$restaurant['id'],1])}}','{{translate('messages.you_want_to_approve_this_application')}}')"
                        href="javascript:">{{translate('messages.approve')}}</a>
                </div>
            @endif
        </div>
        @if($restaurant->vendor->status)
        <!-- Nav Scroller -->
        <div class="js-nav-scroller hs-nav-scroller-horizontal">
            <!-- Nav -->
            <ul class="nav nav-tabs page-header-tabs">
                <li class="nav-item">
                    <a class="nav-link active" href="{{route('admin.restaurant.view', $restaurant->id)}}">{{translate('messages.overview')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('admin.restaurant.view', ['restaurant'=>$restaurant->id, 'tab'=> 'order'])}}"  aria-disabled="true">{{translate('messages.orders')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('admin.restaurant.view', ['restaurant'=>$restaurant->id, 'tab'=> 'product'])}}"  aria-disabled="true">{{translate('messages.foods')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('admin.restaurant.view', ['restaurant'=>$restaurant->id, 'tab'=> 'reviews'])}}"  aria-disabled="true">{{translate('messages.reviews')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('admin.restaurant.view', ['restaurant'=>$restaurant->id, 'tab'=> 'discount'])}}"  aria-disabled="true">{{translate('discounts')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('admin.restaurant.view', ['restaurant'=>$restaurant->id, 'tab'=> 'transaction'])}}"  aria-disabled="true">{{translate('messages.transactions')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('admin.restaurant.view', ['restaurant'=>$restaurant->id, 'tab'=> 'settings'])}}"  aria-disabled="true">{{translate('messages.settings')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('admin.restaurant.view', ['restaurant'=>$restaurant->id, 'tab'=> 'conversations'])}}"  aria-disabled="true">{{translate('messages.conversations')}}</a>
                </li>
                @if ($restaurant->restaurant_model != 'none' && $restaurant->restaurant_model != 'commission' )
                <li class="nav-item">
                    <a class="nav-link" href="{{route('admin.restaurant.view', ['restaurant'=>$restaurant->id, 'tab'=> 'subscriptions'])}}"  aria-disabled="true">{{translate('messages.subscription')}}</a>
                </li>
                @endif
            </ul>
            <!-- End Nav -->
        </div>
        <!-- End Nav Scroller -->
        @endif
    </div>
        <!-- End Page Header -->
    <!-- Page Heading -->
    <div class="row my-2 g-3">
        <!-- Earnings (Monthly) Card Example -->
        <div class="for-card col-md-4">
            <div class="card bg--1 h-100">
                <div class="card-body text-center d-flex flex-column justify-content-center align-items-center">
                    <div class="cash--subtitle">
                        {{translate('messages.collected_cash_by_restaurant')}}
                    </div>
                    <div class="d-flex align-items-center justify-content-center mt-3">
                        <img class="cash-icon mr-3" src="{{asset('/public/assets/admin/img/transactions/cash.png')}}" alt="transactions">
                        <h2

                            class="cash--title">{{\App\CentralLogics\Helpers::format_currency($wallet->collected_cash)}}
                        </h2>
                    </div>
                </div>
                <div class="card-footer pt-0 bg-transparent">
                    <a class="btn btn-- bg--title h--45px w-100" href="{{route('admin.account-transaction.index')}}" title="{{translate('messages.goto')}} {{translate('messages.account_transaction')}}">{{translate('messages.collect_cash_from_restaurant')}}</a>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="row g-3">
                <!-- Panding Withdraw Card Example -->
                <div class="col-sm-6">
                    <div class="resturant-card  bg--2">
                        <h4 class="title">{{\App\CentralLogics\Helpers::format_currency($wallet->pending_withdraw)}}</h4>
                        <span class="subtitle">{{translate('messages.pending')}} {{translate('messages.withdraw')}}</span>
                        <img class="resturant-icon" src="{{asset('/public/assets/admin/img/transactions/pending.png')}}" alt="transactions">
                    </div>
                </div>

                <!-- Earnings (Monthly) Card Example -->
                <div class="col-sm-6">
                    <div class="resturant-card  bg--3">
                        <h4 class="title">{{\App\CentralLogics\Helpers::format_currency($wallet->total_withdrawn)}}</h4>
                        <span class="subtitle">{{translate('messages.total')}} {{translate('messages.withdrawn')}} {{translate('messages.amount')}}</span>
                        <img class="resturant-icon" src="{{asset('/public/assets/admin/img/transactions/withdraw-amount.png')}}" alt="transactions">
                    </div>
                </div>

                <!-- Collected Cash Card Example -->
                <div class="col-sm-6">
                    <div class="resturant-card  bg--5">
                        <h4 class="title">{{\App\CentralLogics\Helpers::format_currency($wallet->balance)}}</h4>
                        <span class="subtitle">{{translate('messages.withdraw_able_balance')}}</span>
                        <img class="resturant-icon" src="{{asset('/public/assets/admin/img/transactions/withdraw-balance.png')}}" alt="transactions">
                    </div>
                </div>

                <!-- Pending Requests Card Example -->
                <div class="col-sm-6">
                    <div class="resturant-card  bg--1">
                        <h4 class="title">{{\App\CentralLogics\Helpers::format_currency($wallet->total_earning)}}</h4>
                        <span class="subtitle">{{translate('messages.total_earning')}}</span>
                        <img class="resturant-icon" src="{{asset('/public/assets/admin/img/transactions/earning.png')}}" alt="transactions">
                    </div>
                </div>

            </div>

        </div>
    </div>
    <div class="mt-4">
        <div id="restaurant_details" class="row g-3">
            <div class="col-lg-12">
                <div class="card mt-2">
                    <div class="card-header">
                        <h5 class="card-title m-0 d-flex align-items-center">
                            <span class="card-header-icon mr-2">
                                <i class="tio-shop-outlined"></i>
                            </span>
                            <span class="ml-1">{{translate('messages.restaurant')}} {{translate('messages.info')}}</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center g-3">
                            <div class="col-lg-6">
                                <div class="resturant--info-address">
                                    <div class="logo">
                                        <img onerror="this.src='{{asset('public/assets/admin/img/100x100/restaurant-default-image.png')}}'"
                                            src="{{asset('storage/app/public/restaurant')}}/{{$restaurant->logo}}">
                                    </div>
                                    <ul class="address-info list-unstyled list-unstyled-py-3 text-dark">
                                        <li>
                                            <h5 class="name">
                                                {{$restaurant->name}}
                                            </h5>
                                        </li>
                                        <li>
                                            <i class="tio-city nav-icon"></i>
                                            <span class="pl-1">
                                                {{translate('messages.address')}} : {{$restaurant->address}}
                                            </span>
                                        </li>

                                        <li>
                                            <i class="tio-call-talking nav-icon"></i>
                                            <span class="pl-1">
                                                {{translate('messages.phone')}} : {{$restaurant->phone}}
                                            </span>
                                        </li>
                                        <li>
                                            <i class="tio-email nav-icon"></i>
                                            <span class="pl-1">
                                                {{translate('messages.email')}} : {{$restaurant->email}}
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div id="map" class="single-page-map"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title m-0 d-flex align-items-center">
                            <span class="card-header-icon mr-2">
                                <i class="tio-user"></i>
                            </span>
                            <span class="ml-1">{{translate('messages.owner')}} {{translate('messages.info')}}</span>
                        </h5>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-center">
                        <div class="resturant--info-address">
                            <div class="avatar avatar-xxl avatar-circle avatar-border-lg">
                                <img class="avatar-img" onerror="this.src='{{asset('public/assets/admin/img/160x160/img1.jpg')}}'"
                            src="{{asset('storage/app/public/vendor')}}/{{$restaurant->vendor->image}}" alt="Image Description">
                            </div>
                            <ul class="address-info address-info-2 list-unstyled list-unstyled-py-3 text-dark">
                                <li>
                                    <h5 class="name">
                                        {{$restaurant->vendor->f_name}} {{$restaurant->vendor->l_name}}
                                    </h5>
                                </li>
                                <li>
                                    <i class="tio-call-talking nav-icon"></i>
                                    <span class="pl-1">
                                        {{$restaurant->vendor->phone}}
                                    </span>
                                </li>
                                <li>
                                    <i class="tio-email nav-icon"></i>
                                    <span class="pl-1">
                                        {{$restaurant->vendor->email}}
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title m-0 d-flex align-items-center">
                            <span class="card-header-icon mr-2">
                                <i class="tio-museum"></i>
                            </span>
                            <span class="ml-1">{{translate('messages.bank_info')}}</span>
                        </h5>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-center">
                        <ul class="list-unstyled list-unstyled-py-3 text-dark">
                            @if($restaurant->vendor->bank_name)
                            <li class="pb-1 pt-1">
                                <strong class="text--title">{{translate('messages.bank_name')}}:</strong> {{$restaurant->vendor->bank_name ? $restaurant->vendor->bank_name : 'No Data found'}}
                            </li>
                            <li class="pb-1 pt-1">
                                <strong class="text--title">{{translate('messages.branch')}}  :</strong> {{$restaurant->vendor->branch ? $restaurant->vendor->branch : 'No Data found'}}
                            </li>
                            <li class="pb-1 pt-1">
                                <strong class="text--title">{{translate('messages.holder_name')}} :</strong> {{$restaurant->vendor->holder_name ? $restaurant->vendor->holder_name : 'No Data found'}}
                            </li>
                            <li class="pb-1 pt-1">
                                <strong class="text--title">{{translate('messages.account_no')}}  :</strong> {{$restaurant->vendor->account_no ? $restaurant->vendor->account_no : 'No Data found'}}
                            </li>
                            @else
                            <li class="my-auto">
                                <center class="card-subtitle">{{translate('messages.no_data_found')}}</center>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title m-0 d-flex align-items-center">
                            <span class="card-header-icon mr-2">
                                <i class="tio-crown"></i>
                            </span>
                            <span class="ml-1">{{translate('messages.Restaurant')}} {{translate('messages.Model')}} : {{ translate($restaurant->restaurant_model ?? 'None') }}</span>
                        </h5>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-center">
                        <div class="resturant--info-address">

                            <ul class="address-info address-info-2 list-unstyled list-unstyled-py-3 text-dark">

                                    @if (isset($restaurant->restaurant_sub) )
                                    <li>
                                        <span class="pl-1">
                                           {{ translate('messages.Package Name') }} : {{$restaurant->restaurant_sub->package->package_name}}
                                        </span>
                                    </li>
                                    <li>
                                    <li>
                                        <span class="pl-1">
                                        {{ translate('messages.Package_price') }} : {{\App\CentralLogics\Helpers::format_currency($restaurant->restaurant_sub->package->price)}}
                                        </span>
                                    </li>
                                    <li>
                                        <span class="pl-1">
                                            {{ translate('messages.Expire_Date') }} :   {{$restaurant->restaurant_sub->expiry_date->format('d M Y')}}
                                        </span>
                                    </li>
                                    <li>
                                        @if ($restaurant->restaurant_sub->status == 1)
                                            <span class="badge badge-soft-success">
                                                {{ translate('messages.Status') }} : {{ translate('messages.active') }}</span>
                                            @else
                                            <span class="badge badge-soft-danger">
                                                {{ translate('messages.Status') }} : {{ translate('messages.inactive') }}</span>
                                        @endif
                                    </li>
                                    @elseif(!isset($restaurant->restaurant_sub) && $restaurant->restaurant_model == 'unsubscribed'  )
                                    <li>
                                        <span class="pl-1">
                                            {{ translate('messages.Not_subscribed_to_any_package') }}
                                        </span>
                                    </li>
                                    @else


                                    @endif

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script_2')
    <!-- Page level plugins -->
    <script>
        // Call the dataTables jQuery plugin
        $(document).ready(function () {
            $('#dataTable').DataTable();
        });
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{\App\Models\BusinessSetting::where('key', 'map_api_key')->first()->value}}&callback=initMap&v=3.45.8" ></script>
    <script>
        const myLatLng = { lat: {{$restaurant->latitude}}, lng: {{$restaurant->longitude}} };
        let map;
        initMap();
        function initMap() {
                 map = new google.maps.Map(document.getElementById("map"), {
                zoom: 15,
                center: myLatLng,
            });
            new google.maps.Marker({
                position: myLatLng,
                map,
                title: "{{$restaurant->name}}",
            });
        }
    </script>
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


            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function () {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });

        function request_alert(url, message) {
            Swal.fire({
                title: "{{translate('messages.are_you_sure')}}",
                text: message,
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#FC6A57',
                cancelButtonText: "{{translate('messages.no')}}",
                confirmButtonText: "{{translate('messages.yes')}}",
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    location.href = url;
                }
            })
        }
    </script>
@endpush
