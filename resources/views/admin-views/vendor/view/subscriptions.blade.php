@extends('layouts.admin.app')

@section('title', $restaurant->name . "'s" . translate('messages.subscription'))

@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{ asset('public/assets/admin/css/croppie.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('/public/assets/landing/owl/dist/assets/owl.carousel.css')}}">

   <style>
        p.start {
            /* font-family: 'Open Sans', sans-serif;
                    font-size: 10px;
                    line-height: 28px; */
            text-align: justify;
            display: inline;
        }

        h1.start {
            /* font-family: 'Open Sans', sans-serif;
                    font-size: 16px;
                    line-height: 28px; */
            margin: 0;
            display: inline-block;
        }
    </style>
@endpush

@section('content')



    <div class="content container-fluid">
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
                        @endif
                        @if (isset($rest_subscription) &&  $rest_subscription->expiry_date <= Carbon\Carbon::today()->addDays('10'))
                        <button class="btn btn--warning my-2" data-toggle="modal" data-target="#subscription-modal">
                            <span class="ml-1">{{ translate('messages.renew_now') }}</span> </button>
                        @endif
                    @endif

            </div>
            </div>

            <!-- Nav Scroller -->
            <div class="js-nav-scroller hs-nav-scroller-horizontal">
                <span class="hs-nav-scroller-arrow-prev initial-hidden">
                    <a class="hs-nav-scroller-arrow-link" href="javascript:;">
                        <i class="tio-chevron-left"></i>
                    </a>
                </span>

                <span class="hs-nav-scroller-arrow-next initial-hidden">
                    <a class="hs-nav-scroller-arrow-link" href="javascript:;">
                        <i class="tio-chevron-right"></i>
                    </a>
                </span>

                <!-- Nav -->
                <ul class="nav nav-tabs page-header-tabs">
                    <li class="nav-item">
                        <a class="nav-link"
                            href="{{ route('admin.restaurant.view', $restaurant->id) }}">{{ translate('messages.overview') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link "
                            href="{{ route('admin.restaurant.view', ['restaurant' => $restaurant->id, 'tab' => 'order']) }}"
                            aria-disabled="true">{{ translate('messages.orders') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                            href="{{ route('admin.restaurant.view', ['restaurant' => $restaurant->id, 'tab' => 'product']) }}"
                            aria-disabled="true">{{ translate('messages.foods') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                            href="{{ route('admin.restaurant.view', ['restaurant' => $restaurant->id, 'tab' => 'reviews']) }}"
                            aria-disabled="true">{{ translate('messages.reviews') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                            href="{{ route('admin.restaurant.view', ['restaurant' => $restaurant->id, 'tab' => 'discount']) }}"
                            aria-disabled="true">{{ translate('discounts') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                            href="{{ route('admin.restaurant.view', ['restaurant' => $restaurant->id, 'tab' => 'transaction']) }}"
                            aria-disabled="true">{{ translate('messages.transactions') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                            href="{{ route('admin.restaurant.view', ['restaurant' => $restaurant->id, 'tab' => 'settings']) }}"
                            aria-disabled="true">{{ translate('messages.settings') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                            href="{{ route('admin.restaurant.view', ['restaurant' => $restaurant->id, 'tab' => 'conversations']) }}"
                            aria-disabled="true">{{ translate('messages.conversations') }}</a>
                    </li>
                    @if ($restaurant->restaurant_model != 'none' && $restaurant->restaurant_model != 'commission')
                    <li class="nav-item">
                        <a class="nav-link active"
                            href="{{ route('admin.restaurant.view', ['restaurant' => $restaurant->id, 'tab' => 'subscriptions']) }}"
                            aria-disabled="true">{{ translate('messages.subscription') }}</a>
                    </li>
                    @endif
                </ul>
                <ul class="nav nav-tabs page-header-tabs mb-0 mt-3">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('admin.restaurant.view', ['restaurant' => $restaurant->id, 'tab' => 'subscriptions']) }}">{{ translate('messages.subscription_details') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="{{ route('admin.restaurant.view', ['restaurant' => $restaurant->id, 'tab' => 'subscriptions-transactions']) }}">{{ translate('messages.transactions') }}</a>
                    </li>
                </ul>
                <!-- End Nav -->
            </div>
            <!-- End Nav Scroller -->
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
                                <div class="font-medium text--title">{{$rest_subscription->package->text }}</div>
                            </div>
                            <h3 class="right">{{ \App\CentralLogics\Helpers::format_currency($rest_subscription->package->price) }} / <small class="font-medium text--title">{{ $rest_subscription->package->validity }} {{ translate('messages.days') }}</small></h3>
                        </div>

                        <div class="check--item-wrapper mx-0 mb-0">
                            <div class="check-item">
                                <div class="form-group form-check form--check">
                                    <input type="checkbox" class="form-check-input" checked>
                                    <label class="form-check-label  text-dark" for="account">
                                        @if ($rest_subscription->max_order == 'unlimited')
                                            {{ translate('messages.unlimited') }} {{ translate('messages.orders') }}
                                        @else
                                            {{ $rest_subscription->max_order }} {{ translate('messages.Order') }}
                                    </label> <small style="color: {{ $rest_subscription->max_order < 10 ? 'red' : '' }}">
                                        (
                                        @if ($rest_subscription->max_order  > 0)
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
                                        class="form-check-input">
                                    <label class="form-check-label qcont text-dark" for="account">{{ translate('messages.POS') }}
                                    </label>
                                </div>
                            </div>
                            <div class="check-item">
                                <div class="form-group form-check form--check">
                                    <input type="checkbox"class="form-check-input"
                                        {{ $rest_subscription->mobile_app == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label qcont text-dark"
                                        for="account">{{ translate('messages.Mobile App') }}</label>
                                </div>
                            </div>
                            <div class="check-item">
                                <div class="form-group form-check form--check">
                                    <input type="checkbox"class="form-check-input"
                                        {{ $rest_subscription->self_delivery == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label  text-dark"
                                        for="account">{{ translate('messages.self_delivery') }}</label>
                                </div>
                            </div>
                            <div class="check-item">
                                <div class="form-group form-check form--check">
                                    <input type="checkbox" class="form-check-input" checked>
                                    <label class="form-check-label  text-dark" for="account">
                                        @if ($rest_subscription->max_product == 'unlimited')
                                            {{ translate('messages.unlimited') }} {{ translate('messages.product') }}
                                            {{ translate('Upload') }}
                                        @else
                                            {{ $rest_subscription->max_product }} {{ translate('messages.product') }}
                                            {{ translate('Upload') }}
                                    </label>
                                    @php($total_food=  $restaurant->foods()->withoutGlobalScope(\App\Scopes\RestaurantScope::class)->count() ?? 0 )
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
                                    <input type="checkbox" class="form-check-input"
                                        {{ $rest_subscription->chat == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label text-dark"
                                        for="account">{{ translate('messages.chat') }}</label>
                                </div>
                            </div>
                            <div class="check-item">
                                <div class="form-group form-check form--check">
                                    <input type="checkbox" class="form-check-input"
                                        {{ $rest_subscription->review == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label  text-dark"
                                        for="account">{{ translate('messages.review') }}</label>
                                </div>
                            </div>
                        </div>


                    </div>

                    <div class="__btn-container btn--container justify-content-end">
                                @if ($rest_subscription->status == 1)
                                <button class="btn btn--danger h--45px"href="javascript:"
                                onclick="form_alert('subscription-{{ $restaurant->id }}','{{ translate('messages.You want to Cancel the Plan for') }} {{ $restaurant->name }}')"
                                >  <span class="ml-1">{{ translate('Cancel Subscription') }}</span>
                            </button>
                            <form action="{{ route('admin.subscription.package_cancel', [$restaurant->id]) }}" method="post"
                                id="subscription-{{ $restaurant->id }}">
                                @csrf @method('delete')
                                <input type="hidden" name="id" value="{{ $restaurant->id }}" >
                            </form>
                            @endif
                            @if (\App\CentralLogics\Helpers::subscription_check())
                            <button class="btn btn--primary" data-toggle="modal" data-target="#subscription-modal">
                            <span class="ml-1">{{ translate('Change / Renew Subscription Plan') }}</span> </button>
                            @endif
                        </div>

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
                <div class="data_package" id="data_package">
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="myInput" value="{{ $restaurant->id }}">
        @endsection

@push('script_2')
<script type="text/javascript" src="{{asset('/public/assets/landing/owl/dist/owl.carousel.min.js')}}"></script>
<script>
    $(document).on('click', '.package_detail', function () {
        var id = $(this).attr('data-id');
        var restaurant_id = $("#myInput").val();

        $.ajax({
                        url: '{{url('/')}}/admin/subscription/package_selected/'+id+'/'+restaurant_id,
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
        // center: true,
        loop: false,
        margin: 30,
        responsiveClass:true,
        nav:false,
        dots:false,
        items: 3,
        autoplay: true,
        autoplayTimeout:1500,
        autoplayHoverPause:true,
        rtl: {{ Session::get('direction') === 'rtl' ? 'true' : 'false'}},
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
</script>

@endpush









