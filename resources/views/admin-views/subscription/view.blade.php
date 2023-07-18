@extends('layouts.admin.app')
@section('title', translate('messages.package_details'))
@push('css_or_js')
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Heading -->

        <div class="page-header">
            <h1 class="page-header-title">
                <img src="{{asset('/public/assets/admin/img/entypo_shop.png')}}" class="page-header-icon w-20px mr-1" alt="">
                {{ translate('Subcription Package') }}
            </h1>
            <ul class="nav nav-tabs page-header-tabs mb-0 mt-3">
                <li class="nav-item">
                    <a class="nav-link active font-bold" href="#">{{ translate('messages.package_details') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.subscription.transcation_list',$package->id) }}" >{{ translate('messages.transactions') }}</a>
                </li>
            </ul>
        </div>


        <div class="card mb-3">
            <div class="card-header border-0 pb-0">
                <div class="btn--container flex-grow-1 justify-content-between align-items-center">
                    <h5 class="card-title mb-2">
                        <span>{{translate('Overview')}}</span>
                    </h5>
                    <div class="mb-2">
                        <div class="d-flex flex-wrap statistics-btn-grp">
                            <label>
                                <input type="radio" name="order__statistics" hidden="" value="all_data" checked="checked">
                                <span>{{ translate('messages.All') }}</span>
                            </label>
                            <label>
                                <input type="radio" name="order__statistics" value="this_month" hidden="">
                                <span>{{ translate('messages.this_month') }}</span>
                            </label>
                            <label>
                                <input type="radio" name="order__statistics" value="this_year" hidden="">
                                <span>{{ translate('messages.this_year') }}</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">

                <!-- Resturent Card Wrapper -->
                <div id="all_data" class="row g-3 mb-3 desc">
                    <div class="col-xl-3 col-sm-6">
                        <div class="resturant-card bg--1">
                            <h4 class="title">{{$total_on_package}}</h4>
                            <span class="subtitle">{{translate('messages.Total')}} {{translate('messages.Subscribed_users')}} </span>
                            <img class="resturant-icon" src="{{asset('/public/assets/admin/img/subscription-list/1.svg')}}" alt="resturant">
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="resturant-card bg--2">

                            <h4 class="title">{{$active}}</h4>
                            <span class="subtitle">{{translate('messages.Active')}} {{translate('messages.Subscription')}}</span>
                            <img class="resturant-icon" src="{{asset('/public/assets/admin/img/subscription-list/2.svg')}}" alt="resturant">
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="resturant-card bg--3">

                            <h4 class="title">{{$deactive}}</h4>
                            <span class="subtitle"> {{ translate('messages.Expired')}} {{translate('messages.Subscription')}}</span>
                            <img class="resturant-icon" src="{{asset('/public/assets/admin/img/subscription-list/3.svg')}}" alt="resturant">
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="resturant-card bg--4">
                            <h4 class="title">{{$expire_soon}}</h4>
                            <span class="subtitle">{{translate('Expiring Soon')}}</span>
                            <img class="resturant-icon" src="{{asset('/public/assets/admin/img/subscription-list/4.svg')}}" alt="resturant">
                        </div>
                    </div>


                    <div class="col-sm-6 col-lg-3">
                        <!-- Card -->
                        <a class="order--card h-100" href="#0">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="card-subtitle d-flex justify-content-between m-0 align-items-center">
                                    <img src="{{asset('/public/assets/admin/img/report/2.svg')}}" alt="dashboard" class="oder--card-icon w-20px">
                                    <span>{{translate('total')}} {{translate('messages.Subscriptions')}}</span>
                                </h6>
                                <span class="card-title text-success">
                                    {{ $package->transactions_count }}
                                </span>
                            </div>
                        </a>
                        <!-- End Card -->
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <!-- Card -->
                        <a class="order--card h-100" href="#0">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="card-subtitle d-flex justify-content-between m-0 align-items-center">
                                    <img src="{{asset('/public/assets/admin/img/report/4.svg')}}" alt="dashboard" class="oder--card-icon w-20px">
                                    <span>{{translate('messages.free_trail')}}</span>
                                </h6>
                                <span class="card-title text-success">
                                    {{$on_trail}}
                                </span>
                            </div>
                        </a>
                        <!-- End Card -->
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <a class="order--card h-100" href="#0">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="card-subtitle d-flex justify-content-between m-0 align-items-center">
                                    <img src="{{asset('/public/assets/admin/img/report/1.svg')}}" alt="dashboard" class="oder--card-icon w-20px">
                                    <span>{{translate('Total Renewed')}}</span>
                                </h6>
                                <span class="card-title text-info">
                                    {{$total_renewed}}
                                </span>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <!-- Card -->
                        <a class="order--card h-100" href="#0">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="card-subtitle d-flex justify-content-between m-0 align-items-center">
                                    <img src="{{asset('/public/assets/admin/img/report/3.svg')}}" alt="dashboard" class="oder--card-icon w-20px">
                                    <span>{{translate('total')}} {{translate('messages.earning')}}</span>
                                </h6>
                                <span class="card-title text-success">
                                    {{\App\CentralLogics\Helpers::format_currency($transcation_sum)}}
                                </span>
                            </div>
                        </a>
                        <!-- End Card -->
                    </div>

                </div>
                <!-- Resturent Card Wrapper -->

                <!-- Resturent Card Wrapper -->
                <div  id="this_month" class="row g-3 mb-3 desc"   style="display: none;">
                    <div class="col-xl-3 col-sm-6">
                        <div class="resturant-card bg--1">
                            <h4 class="title">{{$this_month_total_on_package}}</h4>
                            <span class="subtitle">{{translate('messages.Total')}} {{translate('messages.Subscribed_users')}} </span>
                            <img class="resturant-icon" src="{{asset('/public/assets/admin/img/subscription-list/1.svg')}}" alt="resturant">
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="resturant-card bg--2">

                            <h4 class="title">{{$this_month_active}}</h4>
                            <span class="subtitle">{{translate('messages.Active')}} {{translate('messages.Subscription')}}</span>
                            <img class="resturant-icon" src="{{asset('/public/assets/admin/img/subscription-list/2.svg')}}" alt="resturant">
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="resturant-card bg--3">

                            <h4 class="title">{{$this_month_deactive}}</h4>
                            <span class="subtitle"> {{ translate('messages.Expired')}} {{translate('messages.Subscription')}}</span>
                            <img class="resturant-icon" src="{{asset('/public/assets/admin/img/subscription-list/3.svg')}}" alt="resturant">
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="resturant-card bg--4">
                            <h4 class="title">{{$expire_soon}}</h4>
                            <span class="subtitle">{{translate('Expiring Soon')}}</span>
                            <img class="resturant-icon" src="{{asset('/public/assets/admin/img/subscription-list/4.svg')}}" alt="resturant">
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-3">
                        <!-- Card -->
                        <a class="order--card h-100" href="#0">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="card-subtitle d-flex justify-content-between m-0 align-items-center">
                                    <img src="{{asset('/public/assets/admin/img/report/2.svg')}}" alt="dashboard" class="oder--card-icon w-20px">
                                    <span>{{translate('total')}} {{translate('messages.Subscriptions')}}</span>
                                </h6>
                                <span class="card-title text-success">
                                    {{$this_month_total_sub}}
                                </span>
                            </div>
                        </a>
                        <!-- End Card -->
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <!-- Card -->
                        <a class="order--card h-100" href="#0">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="card-subtitle d-flex justify-content-between m-0 align-items-center">
                                    <img src="{{asset('/public/assets/admin/img/report/4.svg')}}" alt="dashboard" class="oder--card-icon w-20px">
                                    <span>{{translate('messages.free_trail')}}</span>
                                </h6>
                                <span class="card-title text-success">
                                    {{$his_month_on_trail}}
                                </span>
                            </div>
                        </a>
                        <!-- End Card -->
                    </div>

                    <div class="col-xl-3 col-sm-6">
                        <a class="order--card h-100" href="#0">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="card-subtitle d-flex justify-content-between m-0 align-items-center">
                                    <img src="{{asset('/public/assets/admin/img/report/1.svg')}}" alt="dashboard" class="oder--card-icon w-20px">
                                    <span>{{translate('Total Renewed')}}</span>
                                </h6>
                                <span class="card-title text-info">
                                    {{$this_month_total_renewed}}
                                </span>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <!-- Card -->
                        <a class="order--card h-100" href="#0">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="card-subtitle d-flex justify-content-between m-0 align-items-center">
                                    <img src="{{asset('/public/assets/admin/img/report/3.svg')}}" alt="dashboard" class="oder--card-icon w-20px">
                                    <span>{{translate('total')}} {{translate('messages.earning')}}</span>
                                </h6>
                                <span class="card-title text-success">
                                    {{\App\CentralLogics\Helpers::format_currency($transcation_sum_month)}}
                                </span>
                            </div>
                        </a>
                        <!-- End Card -->
                    </div>



                </div>
                <!-- Resturent Card Wrapper -->
                <!-- Resturent Card Wrapper -->
                <div id="this_year" class="row g-3 mb-3 desc "  style="display: none;">
                    <div class="col-xl-3 col-sm-6">
                        <div class="resturant-card bg--1">
                            <h4 class="title">{{$this_year_total_on_package}}</h4>
                            <span class="subtitle">{{translate('messages.Total')}} {{translate('messages.Subscribed_users')}} </span>
                            <img class="resturant-icon" src="{{asset('/public/assets/admin/img/subscription-list/1.svg')}}" alt="resturant">
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="resturant-card bg--2">

                            <h4 class="title">{{$this_year_active}}</h4>
                            <span class="subtitle">{{translate('messages.Active')}} {{translate('messages.Subscription')}}</span>
                            <img class="resturant-icon" src="{{asset('/public/assets/admin/img/subscription-list/2.svg')}}" alt="resturant">
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="resturant-card bg--3">

                            <h4 class="title">{{$this_year_deactive}}</h4>
                            <span class="subtitle"> {{ translate('messages.Expired')}} {{translate('messages.Subscription')}}</span>
                            <img class="resturant-icon" src="{{asset('/public/assets/admin/img/subscription-list/3.svg')}}" alt="resturant">
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="resturant-card bg--4">
                            <h4 class="title">{{$expire_soon}}</h4>
                            <span class="subtitle">{{translate('Expiring Soon')}}</span>
                            <img class="resturant-icon" src="{{asset('/public/assets/admin/img/subscription-list/4.svg')}}" alt="resturant">
                        </div>
                    </div>


                    <div class="col-sm-6 col-lg-3">
                        <!-- Card -->
                        <a class="order--card h-100" href="#0">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="card-subtitle d-flex justify-content-between m-0 align-items-center">
                                    <img src="{{asset('/public/assets/admin/img/report/2.svg')}}" alt="dashboard" class="oder--card-icon w-20px">
                                    <span>{{translate('total')}} {{translate('messages.Subscriptions')}}</span>
                                </h6>
                                <span class="card-title text-success">
                                    {{$this_year_total_sub}}
                                </span>
                            </div>
                        </a>
                        <!-- End Card -->
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <!-- Card -->
                        <a class="order--card h-100" href="#0">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="card-subtitle d-flex justify-content-between m-0 align-items-center">
                                    <img src="{{asset('/public/assets/admin/img/report/4.svg')}}" alt="dashboard" class="oder--card-icon w-20px">
                                    <span>{{translate('messages.free_trail')}}</span>
                                </h6>
                                <span class="card-title text-success">
                                    {{$his_year_on_trail}}
                                </span>
                            </div>
                        </a>
                        <!-- End Card -->
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <a class="order--card h-100" href="#0">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="card-subtitle d-flex justify-content-between m-0 align-items-center">
                                    <img src="{{asset('/public/assets/admin/img/report/1.svg')}}" alt="dashboard" class="oder--card-icon w-20px">
                                    <span>{{translate('Total Renewed')}}</span>
                                </h6>
                                <span class="card-title text-info">
                                    {{$this_year_total_renewed}}
                                </span>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <!-- Card -->
                        <a class="order--card h-100" href="#0">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="card-subtitle d-flex justify-content-between m-0 align-items-center">
                                    <img src="{{asset('/public/assets/admin/img/report/3.svg')}}" alt="dashboard" class="oder--card-icon w-20px">
                                    <span>{{translate('total')}} {{translate('messages.earning')}}</span>
                                </h6>
                                <span class="card-title text-success">
                                    {{\App\CentralLogics\Helpers::format_currency($transcation_sum_year)}}
                                </span>
                            </div>
                        </a>
                        <!-- End Card -->
                    </div>


                </div>


            </div>
        </div>




        <div class="card __billing-subscription mb-3">
            <div class="card-header border-0 align-items-center">
                <h4 class="card-title">
                    <span class="card-header-icon">
                        <img class="w-20px" src="{{asset('public/assets/admin/img/subscription-plan.png')}}" alt="">
                    </span>
                    <span>{{ translate('messages.Package') }} {{ translate('messages.details') }}</span>
                </h4>
                <div class="d-flex align-items-center justify-content-center">
                    <label class="toggle-switch toggle-switch-sm" for="stocksCheckbox{{$package->id}}"> {{ translate('messages.Status') }}:&nbsp;
                        <input type="checkbox" onclick="status_change_alert('{{route('admin.subscription.package_status',[$package->id,$package->status?0:1])}}',
                        '{{$package->status?translate('Do You Want To Disable This Package'):translate('Do you want to Active This Package')}}', event)"
                        class="toggle-switch-input" id="stocksCheckbox{{$package->id}}" {{$package->status?'checked':''}}>
                        <span class="toggle-switch-label">
                            <span class="toggle-switch-indicator"></span>
                        </span>
                    </label> &nbsp;&nbsp;
                    <a class="btn btn--primary py-2" href="{{ route('admin.subscription.package_edit',$package->id) }}" title="{{translate('messages.edit')}} {{translate('messages.Package')}}"><i class="tio-edit"> </i> {{ translate('messages.edit') }}

                    </a>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="bg-FCFCFC __plan-details">
                    <div class="d-flex flex-wrap flex-md-nowrap justify-content-between __plan-details-top">
                        <div class="left">
                            <h3 class="name">{{ $package->package_name }}</h3>
                            <div class="font-medium text--title"> {{ $package->text }}
                                </div>
                        </div>
                        <h3 class="right">{{ \App\CentralLogics\Helpers::format_currency($package->price) }}  / <small class="font-medium text--title">{{ $package->validity }} {{ translate('messages.days') }}</small></h3>
                    </div>


                <div class="check--item-wrapper">
                    <div class="check-item">
                        <div class="form-group form-check form--check">
                            <input type="checkbox" class="form-check-input " checked>
                            <label class="form-check-label ml-2 ml-sm-3  text-dark" for="account">
                                @if ($package->max_order == 'unlimited')
                                    {{ translate('messages.unlimited') }} {{ translate('messages.orders') }}
                                @else
                                    {{ $package->max_order }} {{ translate('messages.Order') }}
                            </label>
                            @endif
                        </div>
                    </div>
                    <div class="check-item">
                        <div class="form-group form-check form--check">
                            <input type="checkbox" {{ $package->pos == 1 ? 'checked' : '' }}
                                class="form-check-input ">
                            <label class="form-check-label ml-2 ml-sm-3 qcont text-dark" for="account">POS
                            </label>
                        </div>
                    </div>
                    <div class="check-item">
                        <div class="form-group form-check form--check">
                            <input type="checkbox"class="form-check-input "
                                {{ $package->mobile_app == 1 ? 'checked' : '' }}>
                            <label class="form-check-label ml-2 ml-sm-3 qcont text-dark"
                                for="account">{{ translate('messages.Mobile App') }}</label>
                        </div>
                    </div>


                    <div class="check-item">
                        <div class="form-group form-check form--check">
                            <input type="checkbox"class="form-check-input "
                                {{ $package->self_delivery == 1 ? 'checked' : '' }}>
                            <label class="form-check-label ml-2 ml-sm-3  text-dark"
                                for="account">{{ translate('messages.self_delivery') }}</label>
                        </div>
                    </div>
                </div>

                <div class="check--item-wrapper mb-0">

                    <div class="check-item">
                        <div class="form-group form-check form--check">
                            <input type="checkbox" class="form-check-input " checked>
                            <label class="form-check-label ml-2 ml-sm-3  text-dark" for="account">
                                @if ($package->max_product == 'unlimited')
                                    {{ translate('messages.unlimited') }} {{ translate('messages.product') }}
                                    {{ translate('Upload') }}
                                @else
                                    {{ $package->max_product }} {{ translate('messages.product') }}
                                    {{ translate('Upload') }}
                            </label>
                            @endif

                        </div>
                    </div>
                    <div class="check-item">
                        <div class="form-group form-check form--check">
                            <input type="checkbox" class="form-check-input "
                                {{ $package->chat == 1 ? 'checked' : '' }}>
                            <label class="form-check-label ml-2 ml-sm-3 text-dark"
                                for="account">{{ translate('messages.chat') }}</label>
                        </div>
                    </div>


                    <div class="check-item">
                        <div class="form-group form-check form--check">
                            <input type="checkbox" class="form-check-input "
                                {{ $package->review == 1 ? 'checked' : '' }}>
                            <label class="form-check-label ml-2 ml-sm-3  text-dark"
                                for="account">{{ translate('messages.review') }}</label>
                        </div>
                    </div>
                </div>
            </div>
            </div>

        </div>

    </div>

@endsection

@push('script_2')
    <script>

$(document).ready(function() {
    // $("div.desc").hide();
    $("input[name$='order__statistics']").click(function() {
        var test = $(this).val();
        $("div.desc").hide();
        // alert(test);
        $("#" + test).show();
    });
});

        function status_change_alert(url, message, e) {
            e.preventDefault();
            Swal.fire({
                title: '{{ translate('Are you sure?') }}',
                text: message,
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#FC6A57',
                cancelButtonText: '{{ translate('no') }}',
                confirmButtonText: '{{ translate('yes') }}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    location.href = url;
                }
            })
        }
        $(document).on('ready', function() {
            // INITIALIZATION OF DATATABLES
            // =======================================================
            var datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));

            $('#column1_search').on('keyup', function() {
                datatable
                    .columns(1)
                    .search(this.value)
                    .draw();
            });

            $('#column2_search').on('keyup', function() {
                datatable
                    .columns(2)
                    .search(this.value)
                    .draw();
            });

            $('#column3_search').on('keyup', function() {
                datatable
                    .columns(3)
                    .search(this.value)
                    .draw();
            });

            $('#column4_search').on('keyup', function() {
                datatable
                    .columns(4)
                    .search(this.value)
                    .draw();
            });


            // INITIALIZATION OF SELECT2
            // =======================================================
            // $('.js-select2-custom').each(function () {
            //     var select2 = $.HSCore.components.HSSelect2.init($(this));
            // });
        });
    </script>

    <script>
        $('#search-form').on('submit', function() {
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.subscription.transcation_search') }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(data) {
                    $('#set-rows').html(data.view);
                    $('#itemCount').html(data.total);
                    $('.page-area').hide();
                },
                complete: function() {
                    $('#loading').hide();
                },
            });
        });
    </script>
@endpush
