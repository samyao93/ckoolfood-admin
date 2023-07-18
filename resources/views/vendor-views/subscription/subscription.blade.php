@extends('layouts.vendor.app')

@section('title', translate('messages.my_subscription'))
@push('css_or_js')
<link rel="stylesheet" href="{{asset('/public/assets/landing/owl/dist/assets/owl.carousel.css')}}">
@endpush
@section('content')

    <div class="content container-fluid">

        @if (isset($rest_subscription) &&  $rest_subscription->status == 1 && $rest_subscription->expiry_date <= Carbon\Carbon::today()->addDays('7'))
        <div class="__alert alert alert-dismissible fade show" role="alert">
            <div class="d-flex">
                <div class="__warning-icon">
                    <i class="tio-warning-outlined"></i>
                </div>
                <div class="w-0 flex-grow pl-3">
                    <h6> {{  translate('messages.attention') }}</h6>
                    <div>
                        {{  translate('messages.Your_Subcription_is_Ending_Soon._Please_Renew_Before') }}
                        {{ $rest_subscription->expiry_date->format('d M Y') }}
                        {{ translate('messages.Otherwise_All_Your_Activities_will_Turn_Off_Automatically_After_That.') }}
                    </div>
                </div>
            </div>

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <i class="tio-clear"></i>
            </button>
        </div>
        @endif
        @if (isset($rest_subscription) && $rest_subscription->status == 0 )
        <div style="background: red"  class="__alert alert alert-dismissible fade show" role="alert">
            <div class="d-flex">
                <div class="__warning-icon">
                    <i class="tio-danger-outlined"></i>
                </div>
                <div class="w-0 flex-grow pl-3">
                    <h6> {{  translate('messages.attention') }}</h6>
                    <div>
                        {{  translate('messages.Your_Subcription_has_expired_on') }}
                        {{ $rest_subscription->expiry_date->format('d M Y') }}
                        {{ translate('messages.All_Your_Activities_has_been_Turn_Off_Automatically_To_Continue_Your_Activities_Please_Select_a_Package.') }}
                    </div>
                </div>
            </div>

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <i class="tio-clear"></i>
            </button>
        </div>
        @endif

        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                <h1 class="page-header-title text-break">
                    <i class="tio-museum"></i> <span>{{ $restaurant->name }}'s
                        {{ translate('messages.subscription') }}</span>
                </h1>
                <div class="btn--container justify-content-end">
                    @if (\App\CentralLogics\Helpers::subscription_check())
                    @if(empty($rest_subscription))
                    <button class="btn btn--primary" data-toggle="modal" data-target="#subscription-modal">
                        <span class="ml-1">{{ translate('Add Subscription Package') }}</span> </button>
                        {{-- <button  class="btn btn--primary h--45px"
                        onclick="route_alert('{{  route('vendor.subscription.package_renew_change', [$restaurant->id]) }}','{{ translate('You want to Add New Subscription Package') }}', '{{ translate('Are you sure ?') }}')">
                        <span class="ml-1">{{ translate('Add New Subscription Plan') }}</span> </button> --}}
                    @endif
                    @if (isset($rest_subscription) &&  $rest_subscription->expiry_date <= Carbon\Carbon::today()->addDays('10'))
                    {{-- <button class="btn btn--warning my-2"
                    onclick="route_alert('{{  route('vendor.subscription.package_renew_change', [$restaurant->id]) }}','{{ translate('You want to Renew this Package') }}', '{{ translate('Are you sure ?') }}')">
                    <span class="ml-1">{{ translate('messages.renew_now') }}</span> </button> --}}
                    <button class="btn btn--warning my-2" data-toggle="modal" data-target="#subscription-modal">
                        <span class="ml-1">{{ translate('messages.renew_now') }}</span> </button>
                    @endif
                    @endif

                </div>
            </div>
            <ul class="nav nav-tabs page-header-tabs mb-0 mt-3">
                <li class="nav-item">
                    <a class="nav-link font-bold active" href="#" aria-disabled="true">{{ translate('messages.subscription_details') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('vendor.subscription.transcation') }}"  aria-disabled="true"> {{ translate('messages.transactions') }}</a>
                </li>
            </ul>

        </div>
        <!-- End Page Header -->
        @if (isset($rest_subscription))

        <div class="card __billing-subscription">
            <div class="card-body">
                <h4 class="main-title">{{translate('Billing')}}</h4>
                <div class="bg-FCFCFC d-flex flex-wrap">
                    <div class="__billing-item">
                        <img src="{{asset('/public/assets/admin/img/subscription/1.png')}}" alt="img/subscription">
                        <div class="w-0 flex-grow pl-3 pl-sm-4">
                            <div class="info">{{ translate('messages.expire_date') }}</div>

                            @if ($rest_subscription->status == 0)
                            <h4 class="subtitle" style="color: red"> {{ translate('messages.Package_Expired') }}</h4>
                            <span>{{ $rest_subscription->expiry_date->format('d M Y') }}</span>
                            @else
                            <h4 class="subtitle">{{ $rest_subscription->expiry_date->format('d M Y') }}</h4>
                            @endif
                        </div>
                    </div>
                    <div class="__billing-item">
                        <img src="{{asset('/public/assets/admin/img/subscription/2.png')}}" alt="img/subscription">
                        <div class="w-0 flex-grow pl-3 pl-sm-4">
                            <div class="info">{{ translate('messages.Total_bill') }}</div>
                            <h4 class="subtitle">{{ \App\CentralLogics\Helpers::format_currency($total_bill) }}</h4>
                        </div>
                    </div>
                    <div class="__billing-item">
                        <img src="{{asset('/public/assets/admin/img/subscription/3.png')}}" alt="img/subscription">
                        <div class="w-0 flex-grow pl-3 pl-sm-4">
                            <div class="info">{{ translate('messages.number_of_uses') }}</div>
                            <h4 class="subtitle">{{ $rest_subscription->total_package_renewed+1 }}</h4>
                        </div>
                    </div>
                </div>
                <div class="mt-4 pt-2">
                    <h4 class="card-title mb-4">
                        <span class="card-header-icon">
                            <img class="w-20px" src="{{asset('/public/assets/admin/img/subscription-plan.png')}}" alt="">
                        </span>
                        <span>{{translate('Subscription Plan')}}</span>
                    </h4>
                    <div class="bg-FCFCFC __plan-details">
                        <div class="d-flex flex-wrap flex-md-nowrap justify-content-between __plan-details-top">
                            <div class="left">
                                <h3 class="name">{{ $rest_subscription->package->package_name }}</h3>
                                <div class="font-medium text--title"> {{$rest_subscription->package->text }}</div>
                            </div>
                            <h3 class="right">{{ \App\CentralLogics\Helpers::format_currency($rest_subscription->package->price ) }} / <small class="font-medium text--title">{{ $rest_subscription->package->validity }} {{ translate('messages.days') }}</small></h3>
                        </div>

                        <div class="check--item-wrapper mx-0 mb-0">
                            <div class="check-item">
                                <div class="form-group form-check form--check">
                                    <input type="checkbox" class="form-check-input  " checked>
                                    <label class="form-check-label  text-dark" for="account">
                                        @if ($rest_subscription->max_order == 'unlimited')
                                            {{ translate('messages.unlimited') }} {{ translate('messages.orders') }}
                                        @else
                                            {{ $rest_subscription->package->max_order }} {{ translate('messages.Order') }}
                                    </label> <small style="color: {{ $rest_subscription->max_order < 10 ? 'red' : '' }}">
                                        (
                                        @if ($rest_subscription->max_order > 0)
                                        {{ $rest_subscription->max_order }}
                                        @else
                                        0
                                        @endif
                                        {{ translate('left') }})
                                    </small>
                                    @endif
                                </div>
                            </div>
                            <div class="check-item">
                                <div class="form-group form-check form--check">
                                    <input type="checkbox" {{ $rest_subscription->pos == 1 ? 'checked' : '' }}
                                        class="form-check-input  ">
                                    <label class="form-check-label qcont text-dark" for="account">{{ translate('messages.POS') }}
                                    </label>
                                </div>
                            </div>
                            <div class="check-item">
                                <div class="form-group form-check form--check">
                                    <input type="checkbox"class="form-check-input  "
                                        {{ $rest_subscription->mobile_app == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label qcont text-dark"
                                        for="account">{{ translate('messages.Mobile App') }}</label>
                                </div>
                            </div>
                            <div class="check-item">
                                <div class="form-group form-check form--check">
                                    <input type="checkbox"class="form-check-input  "
                                        {{ $rest_subscription->self_delivery == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label  text-dark"
                                        for="account">{{ translate('messages.self_delivery') }}</label>
                                </div>
                            </div>
                            <div class="check-item">
                                <div class="form-group form-check form--check">
                                    <input type="checkbox" class="form-check-input  " checked>
                                    <label class="form-check-label  text-dark" for="account">
                                        @if ($rest_subscription->max_product == 'unlimited')
                                            {{ translate('messages.unlimited') }} {{ translate('messages.product') }}
                                            {{ translate('Upload') }}
                                        @else
                                            {{ $rest_subscription->max_product }} {{ translate('messages.product') }}
                                            {{ translate('Upload') }}
                                    </label>
                                    @php($total_food=  $restaurant->foods()->count() ?? 0 )
                                    <small style="color: {{ ($rest_subscription->max_product - $total_food) < 10 ? 'red' : '' }}">
                                        (
                                        @if ($rest_subscription->max_product - $total_food > 0)
                                        {{ $rest_subscription->max_product - $total_food}}
                                        @else
                                        0
                                        @endif
                                        {{ translate('left') }})
                                    </small>
                                        @endif
                                </div>
                            </div>
                            <div class="check-item">
                                <div class="form-group form-check form--check">
                                    <input type="checkbox" class="form-check-input  "
                                        {{ $rest_subscription->chat == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label text-dark"
                                        for="account">{{ translate('messages.chat') }}</label>
                                </div>
                            </div>
                            <div class="check-item">
                                <div class="form-group form-check form--check">
                                    <input type="checkbox" class="form-check-input  "
                                        {{ $rest_subscription->review == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label  text-dark"
                                        for="account">{{ translate('messages.review') }}</label>
                                </div>
                            </div>
                        </div>





                    </div>

                    @if (\App\CentralLogics\Helpers::subscription_check())
                    <div class="__btn-container btn--container justify-content-end">
                        {{-- <button type="button" class="btn btn--reset px-lg-5">{{translate('Cancel Subscription')}}</button> --}}
                        <button class="btn btn--primary" data-toggle="modal" data-target="#subscription-modal">
                        <span class="ml-1">{{ translate('Change / Renew Subscription Plan') }}</span> </button>
                    </div>
                    @endif

                </div>

            </div>

        </div>
        @else
        <div class="empty--data ">
            <img src="{{asset('/public/assets/admin/img/empty.png')}}" alt="public">
            <h5  >
                {{translate('No_subscription_plan_available')}}
            </h5>
        </div>

        @endif


    </div>

    <!-- Subscrition Plan Modal -->
    <div class="modal fade __modal" id="subscription-modal">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <h3 class="modal-title text-center">{{translate('Change Subscription Plan')}}</h3>

                <!-- Modal body -->
                <div class="modal-body overflow-hidden">
                    <div class="plan-slider owl-theme owl-carousel">
                    @forelse ($packages as $package)
                    <div class="__plan-item">
                        <!-- Active Plan Check -->
                        <input type="radio" name="package_id"  value="{{ $package->id }}" id="basic" {{ (isset($rest_subscription) && $rest_subscription->package_id == $package->id ) ?  'checked': ''}}  hidden>
                        <div class="__plan">
                            <div class="plan-header">
                                <h3 class="title">
                                    <span id="div_one_{{ $package->id }}" >{{ $package->package_name }}</span>
                                    <img class="check-plan-icon" src="{{asset('/public/assets/landing/img/check3.svg')}}" alt="">
                                </h3>
                                <div class="duration">
                                    <strong>{{ translate('messages.fee') }} /</strong><span>{{ $package->validity }} {{ translate('messages.days') }}</span>
                                </div>
                                <h2 class="price">{{ \App\CentralLogics\Helpers::format_currency($package->price)}}</h2>
                            </div>
                            <ul class="plan-info">
                                @if ($package->pos)
                                <li>
                                    <img class="plan-info-icon" src="{{asset('/public/assets/landing/img/check2.svg')}}" alt=""> POS
                                </li>
                                @endif
                                @if ($package->mobile_app)
                                <li>
                                    <img class="plan-info-icon" src="{{asset('/public/assets/landing/img/check2.svg')}}" alt=""> {{ translate('messages.mobile_app') }}
                                </li>
                                @endif
                                @if ($package->chat)
                                <li>
                                    <img class="plan-info-icon" src="{{asset('/public/assets/landing/img/check2.svg')}}" alt=""> {{ translate('messages.chatting_options') }}
                                </li>
                                @endif
                                @if ($package->review)
                                <li>
                                    <img class="plan-info-icon" src="{{asset('/public/assets/landing/img/check2.svg')}}" alt=""> {{ translate('messages.review_section') }}
                                </li>
                                @endif
                                @if ($package->self_delivery)
                                <li>
                                    <img class="plan-info-icon" src="{{asset('/public/assets/landing/img/check2.svg')}}" alt=""> {{ translate('messages.self_delivery') }}
                                </li>
                                @endif
                                @if ($package->max_order == 'unlimited')
                                <li>
                                    <img class="plan-info-icon" src="{{asset('/public/assets/landing/img/check2.svg')}}" alt=""> {{ translate('messages.Unlimited') }} {{ translate('messages.Orders') }}
                                </li>
                                @else
                                <li>
                                    <img class="plan-info-icon" src="{{asset('/public/assets/landing/img/check2.svg')}}" alt=""> {{ $package->max_order }} {{ translate('messages.Orders') }}
                                </li>
                                @endif
                                @if ($package->max_product == 'unlimited')
                                <li>
                                    <img class="plan-info-icon" src="{{asset('/public/assets/landing/img/check2.svg')}}" alt=""> {{ translate('messages.Unlimited') }} {{ translate('messages.uploads') }}
                                </li>
                                @else
                                <li>
                                    <img class="plan-info-icon" src="{{asset('/public/assets/landing/img/check2.svg')}}" alt=""> {{ $package->max_product }} {{ translate('messages.uploads') }}
                                </li>
                                @endif



                            </ul>
                            <div class="text-center">
                                    @if (isset($rest_subscription)&& $rest_subscription->package_id == $package->id)
                                    <button data-id="{{ $package->id }}"
                                        data-target="#package_detail" id="package_detail" type="button" class="btn btn--warning text-white renew-btn package_detail">Renew</button>
                                    @else
                                    <button data-id="{{ $package->id }}"
                                        data-target="#package_detail" id="package_detail" type="button" class="btn btn--primary shift-btn package_detail">Shift in this plan</button>
                                    @endif
                                    {{-- <div class="__plan-btns">
                                        <label for="basic" class="inline-block m-0"></label>
                                    </div> --}}
                            </div>
                        </div>
                    </div>
                       @empty


                       <div class="img-responsive center-block d-block mx-auto">
                        <img src="{{asset('/public/assets/admin/img/empty.png')}}" alt="public">
                        <h4  >
                         {{translate('No_subscription_plan_available')}}
                     </h4>
                    </div>
                       @endforelse



                {{--
                        <div class="__plan-item">
                            <!-- Active Plan Check -->
                            <input type="radio" name="plan-name" id="standard" hidden>
                            <div class="__plan">
                                <div class="plan-header">
                                    <h3 class="title">
                                        <span>Standard</span>
                                        <img class="check-plan-icon" src="{{asset('/public/assets/landing/img/check3.svg')}}" alt="">
                                    </h3>
                                    <div class="duration">
                                        <strong>Fee /</strong><span>60 days</span>
                                    </div>
                                    <h2 class="price">$20</h2>
                                </div>
                                <ul class="plan-info">
                                    <li>
                                        <img src="{{asset('/public/assets/landing/img/check2.svg')}}" alt=""> <span>1 User Per Account</span>
                                    </li>
                                    <li>
                                        <img src="{{asset('/public/assets/landing/img/check2.svg')}}" alt=""> <span>100 Products upload</span>
                                    </li>
                                    <li>
                                        <img src="{{asset('/public/assets/landing/img/check2.svg')}}" alt=""> <span>Ecomerce themes</span>
                                    </li>
                                    <li>
                                        <img src="{{asset('/public/assets/landing/img/check2.svg')}}" alt=""> <span>Wordpress themes</span>
                                    </li>
                                    <li>
                                        <img src="{{asset('/public/assets/landing/img/check2.svg')}}" alt=""> <span>Email announcment</span>
                                    </li>
                                </ul>
                                <div class="text-center">
                                    <div class="__plan-btns">
                                        <label for="standard" class="inline-block m-0"></label>
                                        <button class="btn btn--primary shift-btn">Shift in this plan</button>
                                        <button class="btn btn--warning text-white renew-btn">Renew</button>
                                    </div>
                                </div>
                            </div>
                            </div>
                            <div class="__plan-item">
                            <!-- Active Plan Check -->
                            <input type="radio" name="plan-name" id="premium" hidden>
                            <div class="__plan">
                                <!-- Value Selector -->
                                <div class="plan-header">
                                    <h3 class="title">
                                        <span>Premium</span>
                                        <img class="check-plan-icon" src="{{asset('/public/assets/landing/img/check3.svg')}}" alt="">
                                    </h3>
                                    <div class="duration">
                                        <strong>Fee /</strong><span>60 days</span>
                                    </div>
                                    <h2 class="price">$20</h2>
                                </div>
                                <ul class="plan-info">
                                    <li>
                                        <img src="{{asset('/public/assets/landing/img/check2.svg')}}" alt=""> <span>1 User Per Account</span>
                                    </li>
                                    <li>
                                        <img src="{{asset('/public/assets/landing/img/check2.svg')}}" alt=""> <span>100 Products upload</span>
                                    </li>
                                    <li>
                                        <img src="{{asset('/public/assets/landing/img/check2.svg')}}" alt=""> <span>Ecomerce themes</span>
                                    </li>
                                    <li>
                                        <img src="{{asset('/public/assets/landing/img/check2.svg')}}" alt=""> <span>Wordpress themes</span>
                                    </li>
                                    <li>
                                        <img src="{{asset('/public/assets/landing/img/check2.svg')}}" alt=""> <span>Email announcment</span>
                                    </li>
                                </ul>
                                <div class="text-center">
                                    <div class="__plan-btns">
                                        <label for="premium" class="inline-block m-0"></label>
                                        <button class="btn btn--primary shift-btn">Shift in this plan</button>
                                        <button class="btn btn--warning text-white renew-btn">Renew</button>
                                    </div>
                                </div>
                            </div>
                            </div> --}}



                    </div>
                </div>

            </div>
        </div>
    </div>


    <!-- Subscrition Plan Modal 2 -->
    <div class="modal fade __modal" id="subscription-renew-modal">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>


                    {{-- <div class="mb-4 mb-lg-5 subscription__plan-info-wrapper bg-ECEEF1 rounded-20">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="subscription__plan-info">
                                    <div class="info">
                                        Billing Date
                                    </div>
                                    <h4 class="subtitle">Jul 7, 2022</h4>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="subscription__plan-info">
                                    <div class="info">
                                        Bill Amount
                                    </div>
                                    <h4 class="subtitle">$999</h4>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="subscription__plan-info">
                                    <div class="info">
                                        Bill Status
                                    </div>
                                    <h4 class="subtitle">Auto Renewal <sub>(3rd time)</sub></h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="payment__method">
                                <input type="radio" name="payment-method" checked="" hidden="">
                                <div class="payment__method-card">
                                    <span class="checkicon"></span>
                                    <h4 class="title">Online Payment</h4>
                                    <div>
                                         Pay with online payment gateway
                                    </div>
                                </div>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="payment__method">
                                <input type="radio" name="payment-method" hidden="">
                                <div class="payment__method-card">
                                    <span class="checkicon"></span>
                                    <h4 class="title">Pay  from Restaurant Wallet</h4>
                                    <div>
                                        <strong>$7,000 </strong> payable amount in your wallet
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>


                    <div class="__btn-container btn--container justify-content-end mt-5">
                        <button type="button" class="btn btn--reset px-lg-5">{{translate('Cancel Subscription')}}</button>
                        <button class="btn btn--primary">
                        <span class="ml-1">{{ translate('Change / Renew Subscription Plan') }}</span> </button>
                    </div>
            --}}
                <div class="data_package" id="data_package">

                    {{-- @include('vendor-views.subscription._package_selected') --}}

                </div>


            </div>
        </div>
    </div>


    <!-- Subscrition Plan Modal 3 -->
    {{-- <div class="modal fade __modal" id="subscription-change-plan-modal">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <h3 class="modal-title text-center">{{translate('Change Subscription Plan')}}</h3>

                <!-- Modal body -->
                <div class="modal-body">



                </div>

            </div>
        </div>
    </div> --}}


@endsection

@push('script_2')

    <script type="text/javascript" src="{{asset('/public/assets/landing/owl/dist/owl.carousel.min.js')}}"></script>
    <script>

        //Check Data
        $("input[name='package_id']").each(function(){
            if($(this).is(':checked')) {

                $('.__plan-item').find('.shift-btn').show()
                $('.__plan-item').find('.renew-btn').hide()

                $(this).closest('.__plan-item').addClass('active')
                $(this).closest('.__plan-item').find('.shift-btn').hide()
                $(this).closest('.__plan-item').find('.renew-btn').show()

                $($(this)).on('click', function(){
                    $('#subscription-modal').modal('hide')
                    $('#subscription-renew-modal').modal('show')
                })

            } else {
                $($(this)).on('click', function(){
                    $('#subscription-modal').modal('hide')
                    $('#subscription-change-plan-modal').modal('show')

                })
            }
        })


        // Plan Slider
        $('.plan-slider').owlCarousel({
            loop: false,
            margin: 30,
        center: true,
            responsiveClass:true,
            nav:false,
            dots:false,
            items: 3,
            autoplay: true,
            autoplayTimeout:1500,
            autoplayHoverPause:true,

            responsive:{
                0: {
                    items:1.1,
                    margin: 10,
                },
                375: {
                    items: 1.2,
                    margin: 30,
                },
                576: {
                    items:2.2,
                },
                768: {
                    items:2.2,
                    margin: 20,
                },
                992: {
                    items: 3,
                    margin: 30,
                },
                1200: {
                    items: 3,
                    margin: 37,
                }
            }
        })

        // function status_change_alert(url, message, e) {
        //     e.preventDefault();
        //     Swal.fire({
        //         title: '{{ translate('Are you sure?') }}',
        //         text: message,
        //         type: 'warning',
        //         showCancelButton: true,
        //         cancelButtonColor: 'default',
        //         confirmButtonColor: '#FC6A57',
        //         cancelButtonText: '{{ translate('no') }}',
        //         confirmButtonText: '{{ translate('yes') }}',
        //         reverseButtons: true
        //     }).then((result) => {
        //         if (result.value) {
        //             location.href = url;
        //         }
        //     })
        // }
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
        $(document).on('click', '.package_detail', function () {
            var id = $(this).attr('data-id');
            console.log(id);
            $.ajax({
                            url: '{{url('/')}}/restaurant-panel/subscription/package_selected/'+id,
                            method: 'get',
                            beforeSend: function() {
                                        $('#loading').show();
                                        $('#subscription-modal').modal('hide')
                                        },
                            success: function(data){
                                $('#data_package').html(data.view);
                                $('#subscription-renew-modal').modal('show')
                            },
                            complete: function() {
                                    $('#loading').hide();
                                },

                            });
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
                url: '{{ route('vendor.subscription.rest_transcation_search') }}',
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


        $('#package_selected').on('submit', function() {
            var formData = new FormData(this);
            console.log('working');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(data) {
                    $('#data_package').html(data.view);
                    $('#itemCount').html(data.total);
                    // $('.page-area').hide();
                },
                complete: function() {
                    $('#loading').hide();
                },
            });
        });

    </script>

        <script>
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
                        location.href=url;
                    }
                })
            }
            $(document).on('ready', function () {



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
                    toastr.success('{{__("Copied Successfully")}}', {
                                        CloseButton: true,
                                        ProgressBar: true
                                    });
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

                $('#toggleColumn_name').change(function (e) {
                    datatable.columns(1).visible(e.target.checked)
                })

                $('#toggleColumn_price').change(function (e) {
                    datatable.columns(2).visible(e.target.checked)
                })

                $('#toggleColumn_validity').change(function (e) {
                    datatable.columns(3).visible(e.target.checked)
                })

                $('#toggleColumn_total_sell').change(function (e) {
                    datatable.columns(4).visible(e.target.checked)
                })

                $('#toggleColumn_status').change(function (e) {
                    datatable.columns(5).visible(e.target.checked)
                })

                $('#toggleColumn_actions').change(function (e) {
                    datatable.columns(6).visible(e.target.checked)
                })

                // INITIALIZATION OF TAGIFY
                // =======================================================
                // $('.js-tagify').each(function () {
                //     var tagify = $.HSCore.components.HSTagify.init($(this));
                // });
            });
        </script>

@endpush









