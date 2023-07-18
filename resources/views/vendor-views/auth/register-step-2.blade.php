{{-- @extends('layouts.landing.app') --}}
@extends('layouts.landing.app')
@section('title', translate('messages.restaurant_registration'))
@push('css_or_js')
<link rel="stylesheet" href="{{ asset('public/assets/landing') }}/css/style.css" />

@endpush
@section('content')
        <!-- Page Header Gap -->
        <div class="h-148px"></div>
        <!-- Page Header Gap -->

    <section class="m-0 landing-inline-1 section-gap">
        <div class="container">
            <!-- Page Header -->
            <div class="step__header">
                <h4 class="title">{{ translate('messages.Restaurant_registration_application') }}</h4>
                <div class="step__wrapper">
                    <div class="step__item active">
                        <span class="shapes"></span>
                        {{translate('General Information')}}
                    </div>
                    <div class="step__item  current">
                        <span class="shapes"></span>
                        {{translate('Business Plan')}}
                    </div>
                    <div class="step__item">
                        <span class="shapes"></span>
                        {{translate('Complete')}}
                    </div>
                </div>
            </div>
            <!-- End Page Header -->
            <div class="card __card">
                <div class="card-body overflow-hidden">
                    <h4 class="register--title text-center mb-40px"> {{ translate('messages.business_plans') }}</h4>
                <form action="{{ route('restaurant.business_plan') }}" method="post">
                    @csrf
                    @method('post')
                    <input type="hidden"  name="restaurant_id" value="{{ $restaurant_id }}" >
                    <input type="hidden"  name="type" value="{{ $type ?? null }}" >
                    <div class="row g-3">
                        @php($business_model = \App\Models\BusinessSetting::where('key', 'business_model')->first())
                        @php($business_model = isset($business_model->value) ? json_decode($business_model->value, true) : [
                            'commission'        =>  1,
                            'subscription'     =>  0,
                        ])
                            @if ($business_model['commission'] == 1)

                            <div class="col-md-6">
                                <label class="business-plan">
                                    <input type="radio" name="business_plan"  value="commission-base" checked hidden>
                                    <div class="business-plan-card">
                                        <span class="checkicon"></span>
                                        <h4 class="title">{{ translate('messages.comission_base') }}</h4>
                                        <div>
                                            {{ translate('messages.restaurant_will_pay') }}
                                            {{ $admin_commission }}%  {{ translate('messages.commission_to') }} {{ $business_name }}
                                            {{ translate('messages.from_each_order_You_will_get_access_of_all_the_features_and_options_in_restaurant_panel_,_app_and_interaction_with_user.') }}
                                        </div>
                                    </div>
                                </label>
                            </div>
                            @endif
                            @if ($business_model['subscription'] == 1)
                        <div class="col-md-6">
                            <label class="business-plan">
                                <input type="radio" name="business_plan" value="subscription-base" hidden>
                                <div class="business-plan-card">
                                    <span class="checkicon"></span>
                                    <h4 class="title">{{ translate('messages.subscription_base') }}</h4>
                                    <div>
                                        {{ translate('messages.Run restaurant by puchasing subsciption  packages. You will have access the features of in restaurant panel , app and interaction with user according to the subscription packages.
                                        ') }}
                                    </div>
                                </div>
                            </label>
                        </div>
                        @endif
                    </div>

                    <!-- Subscription Based -->
                    <div class="plan-wrapper">
                        <div class="pt-5">
                            <h4 class="register--title text-center mb-40px mb-md-5"> {{ translate('messages.choose_subscription_package') }}</h4>
                            <div class="plan-slider owl-theme owl-carousel">
                            @forelse ($packages as $key=> $package)

                            <div class="plan-item">
                                <!-- Value Selector -->
                                <label class="plan-selector">
                                    <input type="radio" name="package_id"  value="{{ $package->id}}" hidden>
                                    <div class="checkicon"></div>
                                </label>
                                <div class="plan-header">

                                    <svg viewBox="0 0 265 159" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.05" d="M265 43.0336V123.572C231.934 145.379 184.808 159 132.491 159C80.2092 159 33.0832 145.379 0 123.588V43.0336C0 36.9472 5.33203 32 11.9311 32H253.087C259.65 32 265 36.9472 265 43.0336Z" fill="{{ $package->colour }}"/>
                                        <path opacity="0.2" d="M265 27.0336V107.572C231.934 129.379 184.808 143 132.491 143C80.2092 143 33.0832 129.379 0 107.588V27.0336C0 20.9472 5.33203 16 11.9311 16H253.087C259.65 16 265 20.9472 265 27.0336Z" fill="{{ $package->colour }}"/>
                                        <path d="M265 10.9467V90.8511C231.934 112.486 184.808 126 132.491 126C80.2092 126 33.0832 112.486 0 90.8673V10.9467C0 4.90825 5.33203 0 11.9311 0H253.087C259.65 0 265 4.90825 265 10.9467Z" fill="{{ $package->colour }}"/>
                                        <path d="M253.399 0H12.8995C6.33546 0 1 4.90273 1 10.9705V86C15.0407 81.7769 31.2577 81.6312 46.2637 82.505C70.2381 83.9127 96.1783 86.356 116.537 74.5927C128.893 67.4732 137.405 55.9849 147.725 46.5031C162.591 32.8143 181.686 23.0897 202.168 18.7533C219.227 15.1289 236.989 15.2098 254.031 11.6015C257.716 10.8248 261.402 9.80546 265 8.55955C263.807 3.65682 259.068 0 253.399 0Z" fill="{{ $package->colour }}"/>
                                        <path d="M238 0C231.454 4.56081 222.903 7.03529 214.615 7.97333C202.369 9.36421 189.893 8.13506 177.629 9.54212C162.901 11.2241 148.719 16.7715 137.212 25.3756C129.223 31.3434 122.343 38.8154 113.105 42.988C97.8851 49.8778 79.8495 46.4491 63.1159 43.8775C46.3824 41.306 27.2382 40.368 14.5341 50.7026C6.38726 57.3335 2.72734 67.1991 0 77V10.9653C0 4.91661 5.33151 0 11.9299 0H238Z" fill="{{ $package->colour }}"/>
                                        <defs>
                                        <linearGradient id="paint0_linear_319_16938" x1="-0.00879873" y1="95.5023" x2="264.991" y2="95.5023" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#F24C88"/>
                                        <stop offset="0.2326" stop-color="#F25285"/>
                                        <stop offset="0.5489" stop-color="#F3647D"/>
                                        <stop offset="0.9118" stop-color="#F58071"/>
                                        <stop offset="1" stop-color="#F5886D"/>
                                        </linearGradient>
                                        <linearGradient id="paint1_linear_319_16938" x1="-0.00879873" y1="79.5023" x2="264.991" y2="79.5023" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#F24C88"/>
                                        <stop offset="0.2326" stop-color="#F25285"/>
                                        <stop offset="0.5489" stop-color="#F3647D"/>
                                        <stop offset="0.9118" stop-color="#F58071"/>
                                        <stop offset="1" stop-color="#F5886D"/>
                                        </linearGradient>
                                        <linearGradient id="paint2_linear_319_16938" x1="59.5955" y1="109.719" x2="167.654" y2="1.89619" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#F24C88"/>
                                        <stop offset="0.2326" stop-color="#F25285"/>
                                        <stop offset="0.5489" stop-color="#F3647D"/>
                                        <stop offset="0.9118" stop-color="#F58071"/>
                                        <stop offset="1" stop-color="#F5886D"/>
                                        </linearGradient>
                                        <linearGradient id="paint3_linear_319_16938" x1="133" y1="0.00226529" x2="133" y2="86.0018" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#F24C88"/>
                                        <stop offset="0.2326" stop-color="#F25285"/>
                                        <stop offset="0.5489" stop-color="#F3647D"/>
                                        <stop offset="0.9118" stop-color="#F58071"/>
                                        <stop offset="1" stop-color="#F5886D"/>
                                        </linearGradient>
                                        <linearGradient id="paint4_linear_319_16938" x1="120.159" y1="-10.032" x2="118.063" y2="23.0226" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#F24C88"/>
                                        <stop offset="0.3286" stop-color="#F45284"/>
                                        <stop offset="0.7743" stop-color="#F8637B"/>
                                        <stop offset="1" stop-color="#FB6F74"/>
                                        </linearGradient>
                                        </defs>
                                    </svg>


                                    <h3 class="title">{{ $package->package_name}}</h3>
                                </div>
                                <h2 class="price">{{ \App\CentralLogics\Helpers::format_currency($package->price)}}</h2>
                                <div class="duration">
                                    <span>{{ $package->validity}} {{ translate('messages.Days') }}</span>
                                    <span class="shape-bg"></span>
                                </div>
                                <ul class="plan-info">
                                    @if ($package->pos)
                                    <li>
                                        <img class="plan-info-icon" src="{{asset('/public/assets/landing/img/check.svg')}}" alt="">
                                        <span>{{ translate('POS') }}</span>
                                    </li>
                                    @endif
                                    @if ($package->mobile_app)
                                    <li>
                                        <img class="plan-info-icon" src="{{asset('/public/assets/landing/img/check.svg')}}" alt="">
                                        <span>{{ translate('messages.mobile_app') }}</span>
                                    </li>
                                    @endif
                                    @if ($package->chat)
                                    <li>
                                        <img class="plan-info-icon" src="{{asset('/public/assets/landing/img/check.svg')}}" alt="">
                                        <span>{{ translate('messages.chatting_options') }}</span>
                                    </li>
                                    @endif
                                    @if ($package->review)
                                    <li>
                                        <img class="plan-info-icon" src="{{asset('/public/assets/landing/img/check.svg')}}" alt="">
                                        <span>{{ translate('messages.review_section') }}</span>
                                    </li>
                                    @endif
                                    @if ($package->self_delivery)
                                    <li>
                                        <img class="plan-info-icon" src="{{asset('/public/assets/landing/img/check.svg')}}" alt="">
                                        <span>{{ translate('messages.self_delivery') }}</span>
                                    </li>
                                    @endif
                                    @if ($package->max_order == 'unlimited')
                                    <li>
                                        <img class="plan-info-icon" src="{{asset('/public/assets/landing/img/check.svg')}}" alt="">
                                        <span>{{ translate('messages.Unlimited') }} {{ translate('messages.Orders') }}</span>
                                    </li>
                                    @else
                                    <li>
                                        <img class="plan-info-icon" src="{{asset('/public/assets/landing/img/check.svg')}}" alt="">
                                        <span>{{ $package->max_order }} {{ translate('messages.Orders') }}</span>
                                    </li>
                                    @endif
                                    @if ($package->max_product == 'unlimited')
                                    <li>
                                        <img class="plan-info-icon" src="{{asset('/public/assets/landing/img/check.svg')}}" alt="">
                                        <span>{{ translate('messages.Unlimited') }} {{ translate('messages.uploads') }}</span>
                                    </li>
                                    @else
                                    <li>
                                        <img class="plan-info-icon" src="{{asset('/public/assets/landing/img/check.svg')}}" alt="">
                                        <span>{{ $package->max_product }} {{ translate('messages.uploads') }}</span>
                                    </li>
                                    @endif
                                </ul>

                                <button class="btn btn-primary py-2 px-5 mt-3"  type="button" >{{ translate('messages.Select_Package') }}</button>
                            </div>

                            @empty
                                <div>
                                    {{ translate('messages.no_package_found') }}
                                </div>
                            @endforelse
                            </div>
                        </div>
                    </div>
                    <!-- Subscription Based -->

                    <div class="btn--container justify-content-end mt-4 mt-md-5 mt-lg-3">
                        <button type="reset" class="btn btn--reset">{{ translate('messages.reset') }}</button>
                        <button type="submit" class="btn btn--primary submitBtn">{{ translate('messages.next') }}</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </section>

    @endsection
    @push('script_2')



    <!-- Script For Plan Collapse -->
    <script>
        if($('input[value="subscription-base"]').is(':checked')) {
            $('.plan-wrapper').slideDown(300)
        }else {
            $('.plan-wrapper').slideUp(300)
        }
        $('input[name="business_plan"]').on('change', function(){
            if($('input[value="subscription-base"]').is(':checked')) {
                $('.plan-wrapper').slideDown(300)
            }else {
                $('.plan-wrapper').slideUp(300)
            }
        })

        // Plan Slider
        $('.plan-slider').owlCarousel({
            loop: false,
            margin: 30,
            responsiveClass:true,
            nav:false,
            dots:false,
            items: 3,
            center: true,
            autoplay:true,
            autoplayTimeout:2500,
            autoplayHoverPause:true,

            responsive:{
                0: {
                    items:1.1,
                    margin: 10,
                },
                375: {
                    items:1.3,
                    margin: 30,
                },
                576: {
                    items:1.7,
                },
                768: {
                    items:2.2,
                    margin: 50,
                },
                992: {
                    items: 3,
                    margin: 50,
                },
                1200: {
                    items: 3,
                    margin: 52,
                }
            }
        })


    </script>
    <!-- Script For Plan Collapse -->



        <script src="{{ asset('public/assets/admin') }}/js/toastr.js"></script>


    @endpush
