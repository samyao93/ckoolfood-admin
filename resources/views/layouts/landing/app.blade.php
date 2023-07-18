<!DOCTYPE html>

<?php
    if(session()->get('landing_site_direction') ){
        $site_direction = session()->get('landing_site_direction');
    } else{
        $site_direction = session()->get('site_direction');
    }
    // if (env('APP_MODE') == 'demo') {
    //     $site_direction = session()->get('site_direction');
    // }else{
    //     $site_direction = \App\Models\BusinessSetting::where('key', 'site_direction')->first();
    //     $site_direction = $site_direction->value ?? 'ltr';
    // }
?>
@php( $direction= ( $site_direction === 'rtl') ? "true" : "false")

@php($landing_data =\App\Models\DataSetting::where('type', 'admin_landing_page')->whereIn('key', ['news_letter_title','news_letter_sub_title','footer_data','shipping_policy_status','refund_policy_status','cancellation_policy_status'])->pluck('value','key')->toArray())

<html dir="{{ $site_direction }}" lang="{{ str_replace('_', '-', app()->getLocale()) }}" >

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>@yield('title','Landing Page | ')</title>

    <link rel="stylesheet" href="{{asset('/public/assets/landing/assets_new/css/bootstrap.min.css')}}" />
    <link rel="stylesheet" href="{{asset('/public/assets/landing/assets_new/css/animate.css')}}" />
    <link rel="stylesheet" href="{{asset('/public/assets/landing/assets_new/css/all.min.css')}}" />
    <link rel="stylesheet" href="{{asset('/public/assets/landing/assets_new/css/owl.min.css')}}" />
    <link rel="stylesheet" href="{{asset('/public/assets/landing/assets_new/css/main.css')}}" />
    <link rel="stylesheet" href="{{ asset('public/assets/admin') }}/css/toastr.css">
    @php($icon = \App\CentralLogics\Helpers::get_settings('icon'))
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('storage/app/public/business/'. $icon ?? '') }}">


    @php($landing_page_links = \App\Models\BusinessSetting::where(['key' => 'landing_page_links'])->first())
    @php($landing_page_links = isset($landing_page_links->value) ? json_decode($landing_page_links->value, true) : null)
    @php($backgroundChange = \App\Models\BusinessSetting::where(['key' => 'backgroundChange'])->first())
    @php($backgroundChange = isset($backgroundChange) && \App\Models\BusinessSetting::where(['key' => 'backgroundChange'])->first()->value ? json_decode(\App\Models\BusinessSetting::where(['key' => 'backgroundChange'])->first()->value,true):'')
    @if (isset($backgroundChange['primary_1_hex']) && isset($backgroundChange['primary_2_hex']))
        <style>
            :root {
                --base-1: <?php echo $backgroundChange['primary_1_hex']; ?>;
                --base-rgb: <?php echo $backgroundChange['primary_1_rgb']; ?>;
                --base-2: <?php echo $backgroundChange['primary_2_hex']; ?>;
                --base-rgb-2:<?php echo $backgroundChange['primary_2_rgb']; ?>;
            }
        </style>
    @endif


    @stack('css_or_js')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
        <!-- ==== Preloader ==== -->
        <div id="landing-loader"></div>
        <!-- ==== Preloader ==== -->

    <header>
        <div class="container">
            <div class="header-wrapper">
                @php($logo = \App\CentralLogics\Helpers::get_settings('logo'))
                <div class="logo">
                    <a href="/">
                        <img src="{{ asset('storage/app/public/business/' . $logo) }}" alt="">
                    </a>
                </div>
                <div class="nav-toggle d-lg-none">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <ul class="menu">
                    <li>
                        <a href="{{ route('home') }}" class="@yield('home')">
                            <svg width="25" height="8" viewBox="0 0 25 8" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M25 6.9176L17.1875 5.7514L17.1875 4.8764L25 4.2926L25 3.7088L17.1875 3.125L17.1875 2.25L25 1.0838L25 0.5L14.8438 0.5C14.6366 0.5 14.4379 0.57375 14.2914 0.705025C14.1448 0.836301 14.0625 1.01435 14.0625 1.2L14.0625 3.0228L1.55946 2.3354C1.35875 2.32241 1.15722 2.34688 0.967606 2.40725C0.77799 2.46762 0.604418 2.56259 0.457861 2.68614C0.311302 2.80969 0.194948 2.95915 0.116154 3.12505C0.037361 3.29096 -0.00216116 3.46971 9.1415e-05 3.65L9.13844e-05 4.35C9.13508e-05 5.12 0.70165 5.7108 1.55946 5.6646L14.0625 4.9772L14.0625 6.8C14.0625 6.98565 14.1448 7.1637 14.2914 7.29497C14.4379 7.42625 14.6366 7.5 14.8438 7.5L25 7.5L25 6.9162L25 6.9176Z"
                                    fill="#FB5607" />
                                <path
                                    d="M25 6.9176L17.1875 5.7514L17.1875 4.8764L25 4.2926L25 3.7088L17.1875 3.125L17.1875 2.25L25 1.0838L25 0.5L14.8438 0.5C14.6366 0.5 14.4379 0.57375 14.2914 0.705025C14.1448 0.836301 14.0625 1.01435 14.0625 1.2L14.0625 3.0228L1.55946 2.3354C1.35875 2.32241 1.15722 2.34688 0.967606 2.40725C0.77799 2.46762 0.604418 2.56259 0.457861 2.68614C0.311302 2.80969 0.194948 2.95915 0.116154 3.12505C0.037361 3.29096 -0.00216116 3.46971 9.1415e-05 3.65L9.13844e-05 4.35C9.13508e-05 5.12 0.70165 5.7108 1.55946 5.6646L14.0625 4.9772L14.0625 6.8C14.0625 6.98565 14.1448 7.1637 14.2914 7.29497C14.4379 7.42625 14.6366 7.5 14.8438 7.5L25 7.5L25 6.9162L25 6.9176Z"
                                    fill="#FB5607" />
                            </svg>
                            {{ translate('messages.home') }}
                        </a>
                    </li>
                    @if ($landing_page_links['web_app_url_status'])

                    <li>
                        <a href="{{ $landing_page_links['web_app_url'] }}">
                            <svg width="25" height="8" viewBox="0 0 25 8" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M25 6.9176L17.1875 5.7514L17.1875 4.8764L25 4.2926L25 3.7088L17.1875 3.125L17.1875 2.25L25 1.0838L25 0.5L14.8438 0.5C14.6366 0.5 14.4379 0.57375 14.2914 0.705025C14.1448 0.836301 14.0625 1.01435 14.0625 1.2L14.0625 3.0228L1.55946 2.3354C1.35875 2.32241 1.15722 2.34688 0.967606 2.40725C0.77799 2.46762 0.604418 2.56259 0.457861 2.68614C0.311302 2.80969 0.194948 2.95915 0.116154 3.12505C0.037361 3.29096 -0.00216116 3.46971 9.1415e-05 3.65L9.13844e-05 4.35C9.13508e-05 5.12 0.70165 5.7108 1.55946 5.6646L14.0625 4.9772L14.0625 6.8C14.0625 6.98565 14.1448 7.1637 14.2914 7.29497C14.4379 7.42625 14.6366 7.5 14.8438 7.5L25 7.5L25 6.9162L25 6.9176Z"
                                    fill="#FB5607" />
                                <path
                                    d="M25 6.9176L17.1875 5.7514L17.1875 4.8764L25 4.2926L25 3.7088L17.1875 3.125L17.1875 2.25L25 1.0838L25 0.5L14.8438 0.5C14.6366 0.5 14.4379 0.57375 14.2914 0.705025C14.1448 0.836301 14.0625 1.01435 14.0625 1.2L14.0625 3.0228L1.55946 2.3354C1.35875 2.32241 1.15722 2.34688 0.967606 2.40725C0.77799 2.46762 0.604418 2.56259 0.457861 2.68614C0.311302 2.80969 0.194948 2.95915 0.116154 3.12505C0.037361 3.29096 -0.00216116 3.46971 9.1415e-05 3.65L9.13844e-05 4.35C9.13508e-05 5.12 0.70165 5.7108 1.55946 5.6646L14.0625 4.9772L14.0625 6.8C14.0625 6.98565 14.1448 7.1637 14.2914 7.29497C14.4379 7.42625 14.6366 7.5 14.8438 7.5L25 7.5L25 6.9162L25 6.9176Z"
                                    fill="#FB5607" />
                            </svg>
                            {{ translate('messages.browse_web') }}
                        </a>
                    </li>
                    @endif
                    <li>
                        <a href="{{ route('about-us') }}"  class="@yield('about')">
                            <svg width="25" height="8" viewBox="0 0 25 8" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M25 6.9176L17.1875 5.7514L17.1875 4.8764L25 4.2926L25 3.7088L17.1875 3.125L17.1875 2.25L25 1.0838L25 0.5L14.8438 0.5C14.6366 0.5 14.4379 0.57375 14.2914 0.705025C14.1448 0.836301 14.0625 1.01435 14.0625 1.2L14.0625 3.0228L1.55946 2.3354C1.35875 2.32241 1.15722 2.34688 0.967606 2.40725C0.77799 2.46762 0.604418 2.56259 0.457861 2.68614C0.311302 2.80969 0.194948 2.95915 0.116154 3.12505C0.037361 3.29096 -0.00216116 3.46971 9.1415e-05 3.65L9.13844e-05 4.35C9.13508e-05 5.12 0.70165 5.7108 1.55946 5.6646L14.0625 4.9772L14.0625 6.8C14.0625 6.98565 14.1448 7.1637 14.2914 7.29497C14.4379 7.42625 14.6366 7.5 14.8438 7.5L25 7.5L25 6.9162L25 6.9176Z"
                                    fill="#FB5607" />
                                <path
                                    d="M25 6.9176L17.1875 5.7514L17.1875 4.8764L25 4.2926L25 3.7088L17.1875 3.125L17.1875 2.25L25 1.0838L25 0.5L14.8438 0.5C14.6366 0.5 14.4379 0.57375 14.2914 0.705025C14.1448 0.836301 14.0625 1.01435 14.0625 1.2L14.0625 3.0228L1.55946 2.3354C1.35875 2.32241 1.15722 2.34688 0.967606 2.40725C0.77799 2.46762 0.604418 2.56259 0.457861 2.68614C0.311302 2.80969 0.194948 2.95915 0.116154 3.12505C0.037361 3.29096 -0.00216116 3.46971 9.1415e-05 3.65L9.13844e-05 4.35C9.13508e-05 5.12 0.70165 5.7108 1.55946 5.6646L14.0625 4.9772L14.0625 6.8C14.0625 6.98565 14.1448 7.1637 14.2914 7.29497C14.4379 7.42625 14.6366 7.5 14.8438 7.5L25 7.5L25 6.9162L25 6.9176Z"
                                    fill="#FB5607" />
                            </svg>
                            {{ translate('messages.about') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('privacy-policy') }}"  class="@yield('privacy-policy')" >
                            <svg width="25" height="8" viewBox="0 0 25 8" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M25 6.9176L17.1875 5.7514L17.1875 4.8764L25 4.2926L25 3.7088L17.1875 3.125L17.1875 2.25L25 1.0838L25 0.5L14.8438 0.5C14.6366 0.5 14.4379 0.57375 14.2914 0.705025C14.1448 0.836301 14.0625 1.01435 14.0625 1.2L14.0625 3.0228L1.55946 2.3354C1.35875 2.32241 1.15722 2.34688 0.967606 2.40725C0.77799 2.46762 0.604418 2.56259 0.457861 2.68614C0.311302 2.80969 0.194948 2.95915 0.116154 3.12505C0.037361 3.29096 -0.00216116 3.46971 9.1415e-05 3.65L9.13844e-05 4.35C9.13508e-05 5.12 0.70165 5.7108 1.55946 5.6646L14.0625 4.9772L14.0625 6.8C14.0625 6.98565 14.1448 7.1637 14.2914 7.29497C14.4379 7.42625 14.6366 7.5 14.8438 7.5L25 7.5L25 6.9162L25 6.9176Z"
                                    fill="#FB5607" />
                                <path
                                    d="M25 6.9176L17.1875 5.7514L17.1875 4.8764L25 4.2926L25 3.7088L17.1875 3.125L17.1875 2.25L25 1.0838L25 0.5L14.8438 0.5C14.6366 0.5 14.4379 0.57375 14.2914 0.705025C14.1448 0.836301 14.0625 1.01435 14.0625 1.2L14.0625 3.0228L1.55946 2.3354C1.35875 2.32241 1.15722 2.34688 0.967606 2.40725C0.77799 2.46762 0.604418 2.56259 0.457861 2.68614C0.311302 2.80969 0.194948 2.95915 0.116154 3.12505C0.037361 3.29096 -0.00216116 3.46971 9.1415e-05 3.65L9.13844e-05 4.35C9.13508e-05 5.12 0.70165 5.7108 1.55946 5.6646L14.0625 4.9772L14.0625 6.8C14.0625 6.98565 14.1448 7.1637 14.2914 7.29497C14.4379 7.42625 14.6366 7.5 14.8438 7.5L25 7.5L25 6.9162L25 6.9176Z"
                                    fill="#FB5607" />
                            </svg>
                            {{ translate('messages.privacy_policy') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('contact-us') }}" class="@yield('contact')">
                            <svg width="25" height="8" viewBox="0 0 25 8" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M25 6.9176L17.1875 5.7514L17.1875 4.8764L25 4.2926L25 3.7088L17.1875 3.125L17.1875 2.25L25 1.0838L25 0.5L14.8438 0.5C14.6366 0.5 14.4379 0.57375 14.2914 0.705025C14.1448 0.836301 14.0625 1.01435 14.0625 1.2L14.0625 3.0228L1.55946 2.3354C1.35875 2.32241 1.15722 2.34688 0.967606 2.40725C0.77799 2.46762 0.604418 2.56259 0.457861 2.68614C0.311302 2.80969 0.194948 2.95915 0.116154 3.12505C0.037361 3.29096 -0.00216116 3.46971 9.1415e-05 3.65L9.13844e-05 4.35C9.13508e-05 5.12 0.70165 5.7108 1.55946 5.6646L14.0625 4.9772L14.0625 6.8C14.0625 6.98565 14.1448 7.1637 14.2914 7.29497C14.4379 7.42625 14.6366 7.5 14.8438 7.5L25 7.5L25 6.9162L25 6.9176Z"
                                    fill="#FB5607" />
                                <path
                                    d="M25 6.9176L17.1875 5.7514L17.1875 4.8764L25 4.2926L25 3.7088L17.1875 3.125L17.1875 2.25L25 1.0838L25 0.5L14.8438 0.5C14.6366 0.5 14.4379 0.57375 14.2914 0.705025C14.1448 0.836301 14.0625 1.01435 14.0625 1.2L14.0625 3.0228L1.55946 2.3354C1.35875 2.32241 1.15722 2.34688 0.967606 2.40725C0.77799 2.46762 0.604418 2.56259 0.457861 2.68614C0.311302 2.80969 0.194948 2.95915 0.116154 3.12505C0.037361 3.29096 -0.00216116 3.46971 9.1415e-05 3.65L9.13844e-05 4.35C9.13508e-05 5.12 0.70165 5.7108 1.55946 5.6646L14.0625 4.9772L14.0625 6.8C14.0625 6.98565 14.1448 7.1637 14.2914 7.29497C14.4379 7.42625 14.6366 7.5 14.8438 7.5L25 7.5L25 6.9162L25 6.9176Z"
                                    fill="#FB5607" />
                            </svg>
                            {{ translate('messages.contact') }}
                        </a>
                    </li>
                </ul>

                @php( $local = session()->has('landing_local')?session('landing_local'):'en')
                @php($lang = \App\Models\BusinessSetting::where('key', 'system_language')->first())
                @if ($lang)
                    <div class="dropdown--btn-hover position-relative">
                        <a class=" text-dark dropdown--btn border-0 px-3 header--btn text-capitalize d-flex" href="javascript:void(0)">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="20">
                                <path d="M16.555 5.412a8.028 8.028 0 00-3.503-2.81 14.899 14.899 0 011.663 4.472 8.547 8.547 0 001.84-1.662zM13.326 7.825a13.43 13.43 0 00-2.413-5.773 8.087 8.087 0 00-1.826 0 13.43 13.43 0 00-2.413 5.773A8.473 8.473 0 0010 8.5c1.18 0 2.304-.24 3.326-.675zM6.514 9.376A9.98 9.98 0 0010 10c1.226 0 2.4-.22 3.486-.624a13.54 13.54 0 01-.351 3.759A13.54 13.54 0 0110 13.5c-1.079 0-2.128-.127-3.134-.366a13.538 13.538 0 01-.352-3.758zM5.285 7.074a14.9 14.9 0 011.663-4.471 8.028 8.028 0 00-3.503 2.81c.529.638 1.149 1.199 1.84 1.66zM17.334 6.798a7.973 7.973 0 01.614 4.115 13.47 13.47 0 01-3.178 1.72 15.093 15.093 0 00.174-3.939 10.043 10.043 0 002.39-1.896zM2.666 6.798a10.042 10.042 0 002.39 1.896 15.196 15.196 0 00.174 3.94 13.472 13.472 0 01-3.178-1.72 7.973 7.973 0 01.615-4.115zM10 15c.898 0 1.778-.079 2.633-.23a13.473 13.473 0 01-1.72 3.178 8.099 8.099 0 01-1.826 0 13.47 13.47 0 01-1.72-3.178c.855.151 1.735.23 2.633.23zM14.357 14.357a14.912 14.912 0 01-1.305 3.04 8.027 8.027 0 004.345-4.345c-.953.542-1.971.981-3.04 1.305zM6.948 17.397a8.027 8.027 0 01-4.345-4.345c.953.542 1.971.981 3.04 1.305a14.912 14.912 0 001.305 3.04z" />
                            </svg>
                            @foreach(json_decode($lang['value'],true) as $data)
                            @if($data['code']==$local)
                                        <span class="me-1">{{$data['code']}}</span>
                                    @endif
                                @endforeach
                        </a>
                        <ul class="dropdown-list py-0" style="min-width:120px; top:100%">
                            @foreach(json_decode($lang['value'],true) as $key =>$data)
                            @if($data['status']==1)
                                <li class="py-0">
                                    <a class="" href="{{route('lang',[$data['code']])}}">
                                        {{$data['code']}}
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider my-0">
                                </li>
                            @endif
                        @endforeach
                        </ul>
                    </div>
                @endif
                @if ($toggle_dm_registration || $toggle_restaurant_registration)
                <div class="position-relative">
                    <a class="dropdown--btn btn-base" href="javascript:void(0)">
                        <span>{{ translate('Join us') }}</span>
                        <svg width="12" height="7" viewBox="0 0 12 7" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M6.00224 5.46105L1.33333 0.415128C1.21002 0.290383 1 0.0787335 1 0.0787335C1 0.0787335 0.708488 -0.0458817 0.584976 0.0788632L0.191805 0.475841C0.0680976 0.600389 7.43292e-08 0.766881 7.22135e-08 0.9443C7.00978e-08 1.12172 0.0680976 1.28801 0.191805 1.41266L5.53678 6.80682C5.66068 6.93196 5.82624 7.00049 6.00224 7C6.17902 7.00049 6.34439 6.93206 6.46839 6.80682L11.8082 1.41768C11.9319 1.29303 12 1.12674 12 0.949223C12 0.771804 11.9319 0.605509 11.8082 0.480765L11.415 0.0838844C11.1591 -0.174368 10.9225 0.222512 10.6667 0.480765L6.00224 5.46105Z"
                                fill="white" />
                        </svg>
                    </a>
                    <ul class="dropdown-list">
                        @if ($toggle_restaurant_registration)
                        <li>
                            <a href="{{ route('restaurant.create') }}"> {{ translate('messages.join_as_restaurant') }}</a>
                        </li>
                        @endif
                        @if ($toggle_dm_registration)
                        <li>
                            <a href="{{ route('deliveryman.create') }}">{{ translate('messages.join_as_deliveryman') }}</a>
                        </li>
                        @endif
                    </ul>
                </div>
                @endif
            </div>
        </div>
    </header>

    @yield('content')


        <!-- ======= Footer Section ======= -->
        <footer class="footer-bg">
            <div class="newsletter-section">
                <div class="container">
                    <div class="position-relative">

                    @if (isset($landing_data['news_letter_title']) )
                        <div class="newsletter-content position-relative">
                            <h3 class="title">
                                {{ $landing_data['news_letter_title'] ?? translate('messages.news_letter_signup') }}
                                </h3>
                            <div class="text">
                                {{ $landing_data['news_letter_sub_title'] ??  translate('messages.news_letter_signup_text') }}
                            </div>
                            <form method="post" action="{{ route('newsletter.subscribe') }}">
                               @csrf
                                <div class="input--grp">
                                    <input type="email" name="email" class="form-control" required placeholder="{{ translate('Enter your email address') }}" value="{{ old('email') }}">
                                    <button class="search-btn" type="submit">
                                        <svg width="46" height="47" viewBox="0 0 46 47" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <rect y="0.349609" width="46" height="46" rx="23"
                                                fill="url(#paint0_radial_246_199)" />
                                            <path
                                                d="M25.9667 23.3466L19.3001 29.5718C19.1353 29.7363 18.8556 30.0163 18.8556 30.0163C18.8556 30.0163 18.691 30.405 18.8558 30.5696L19.3803 31.0939C19.5448 31.2588 19.7648 31.3496 19.9992 31.3496C20.2336 31.3496 20.4533 31.2588 20.618 31.0939L27.7448 23.9672C27.9101 23.802 28.0006 23.5813 28 23.3466C28.0006 23.1109 27.9102 22.8904 27.7448 22.7251L20.6246 15.6053C20.46 15.4404 20.2403 15.3496 20.0057 15.3496C19.7713 15.3496 19.5516 15.4404 19.3868 15.6053L18.8624 16.1296C18.5212 16.4708 19.0456 16.7863 19.3868 17.1274L25.9667 23.3466Z"
                                                fill="white" />
                                            <defs>
                                                <radialGradient id="paint0_radial_246_199" cx="0" cy="0" r="1"
                                                    gradientUnits="userSpaceOnUse"
                                                    gradientTransform="translate(23 23.3496) rotate(90) scale(23)">
                                                    <stop stop-color="#FFBE0B" />
                                                    <stop offset="1" stop-color="#FB5607" />
                                                </radialGradient>
                                            </defs>
                                        </svg>
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="newsletter-bg"></div>

                        @else

                        <br> <br>
                        @endif

                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="container">
                    <div class="footer-wrapper ps-xl-5">
                        <div class="footer-widget">
                            <div class="footer-logo">
                                @php($logo = \App\CentralLogics\Helpers::get_settings('logo'))
                                <a href="{{ route('home') }}">
                                    <img src="{{ asset('storage/app/public/business/' . $logo) }}" alt="">
                                </a>
                            </div>
                            <div class="txt">
                                {{ isset($landing_data['footer_data']) ? $landing_data['footer_data'] : '' }}
                            </div>
                            <ul class="social-icon">

                                @php($social_media = \App\Models\SocialMedia::where('status', 1)->get())
                                @if (isset($social_media))
                                    @foreach ($social_media as $social)
                                    <li>
                                        <a class="social-btn text-white" target="_blank" href="{{ $social->link }}">
                                            <i class="fab fa-{{ $social->name }} fa-2x" aria-hidden="true"></i>
                                        </a>
                                    </li>
                                    @endforeach
                                @endif
                            </ul>
                            @if ($landing_page_links['app_url_android_status'] !== null || $landing_page_links['app_url_ios_status'] !== null)
                            <div class="app-btn-grp">
                                @if ($landing_page_links['app_url_android_status'])
                                <a href="{{ $landing_page_links['app_url_android'] }}">
                                    <img src="{{asset('/public/assets/landing/assets_new/img/google.svg')}}" alt="">
                                </a>
                                @endif
                            @if ($landing_page_links['app_url_ios_status'])
                                <a href="{{ $landing_page_links['app_url_ios'] }}">
                                    <img src="{{asset('/public/assets/landing/assets_new/img/apple.svg')}}" alt="">
                                </a>
                            </div>
                            @endif
                            @endif
                        </div>
                        <div class="footer-widget widget-links">
                            <h5 class="subtitle mt-2 text-white">{{ translate('messages.quick_links') }}</h5>
                            <ul>
                                <li>
                                    <a href="{{ route('about-us') }}">{{ translate('messages.about_us') }}</a>
                                </li>
                                <li>
                                    <a href="{{ route('contact-us') }}">{{ translate('messages.contact_us') }}</a>
                                </li>
                                <li>
                                    <a href="{{ route('privacy-policy') }}">{{ translate('messages.privacy_policy') }}</a>
                                </li>
                                <li>
                                    <a
                                        href="{{ route('terms-and-conditions') }}">{{ translate('messages.terms_and_condition') }}</a>
                                </li>
                                @if (isset($landing_data['shipping_policy_status']) && $landing_data['shipping_policy_status']  == 1)
                                <li>
                                    <a
                                        href="{{ route('shipping-policy') }}">{{ translate('messages.shipping_policy') }}</a>
                                </li>
                                @endif
                                @if (isset($landing_data['refund_policy_status']) && $landing_data['refund_policy_status']  == 1)
                                <li>
                                    <a
                                        href="{{ route('refund-policy') }}">{{ translate('messages.refund_policy') }}</a>
                                </li>
                                @endif
                                @if (isset($landing_data['cancellation_policy_status']) && $landing_data['cancellation_policy_status']  == 1)
                                <li>
                                    <a
                                        href="{{ route('cancellation-policy') }}">{{ translate('messages.cancellation_policy') }}</a>
                                </li>
                                @endif

                            </ul>
                        </div>
                        <div class="footer-widget widget-links">
                            <h5 class="subtitle mt-2 text-white">{{ translate('messages.contact_us') }}</h5>
                            <ul>
                                <li>
                                    @php($address=\App\CentralLogics\Helpers::get_settings('address')  )

                                    <a target="blank" href="http://maps.google.com/?q={{ $address }}">
                                        <svg width="16" height="16" viewBox="0 0 12 16" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M10.2379 2.73992C9.26683 1.06417 7.54208 0.0403906 5.62411 0.00126563C5.54223 -0.000421875 5.45983 -0.000421875 5.37792 0.00126563C3.45998 0.0403906 1.73523 1.06417 0.764169 2.73992C-0.228393 4.4528 -0.25555 6.5103 0.691513 8.24376L4.65911 15.5059C4.66089 15.5091 4.66267 15.5123 4.66451 15.5155C4.83908 15.8189 5.15179 16 5.50108 16C5.85033 16 6.16304 15.8189 6.33757 15.5155C6.33942 15.5123 6.3412 15.5091 6.34298 15.5059L10.3106 8.24376C11.2576 6.5103 11.2304 4.4528 10.2379 2.73992ZM5.50101 7.25002C4.26036 7.25002 3.25101 6.24067 3.25101 5.00002C3.25101 3.75936 4.26036 2.75002 5.50101 2.75002C6.74167 2.75002 7.75101 3.75936 7.75101 5.00002C7.75101 6.24067 6.7417 7.25002 5.50101 7.25002Z"
                                                fill="white" />
                                        </svg>
                                        {{ $address }}
                                    </a>
                                </li>
                                <li>
                                    @php($email_address=\App\CentralLogics\Helpers::get_settings('email_address')  )
                                    <a href="mailto:{{ $email_address }}">
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M0.333768 2.97362C2.52971 4.83334 6.38289 8.10516 7.51539 9.12531C7.66742 9.263 7.83049 9.333 7.99977 9.333C8.16871 9.333 8.33149 9.26366 8.48317 9.12663C9.61664 8.10547 13.4698 4.83334 15.6658 2.97362C15.8025 2.85806 15.8234 2.65494 15.7127 2.51366C15.4568 2.18719 15.0753 2 14.6664 2H1.33311C0.924268 2 0.542737 2.18719 0.286893 2.51369C0.176205 2.65494 0.197049 2.85806 0.333768 2.97362Z"
                                                fill="white" />
                                            <path
                                                d="M15.8067 3.98127C15.6885 3.92627 15.5495 3.94546 15.4512 4.02946C13.0159 6.0939 9.90788 8.74008 8.93 9.62124C8.38116 10.1167 7.61944 10.1167 7.06931 9.62058C6.027 8.68146 2.53675 5.71433 0.548813 4.02943C0.449844 3.94543 0.310531 3.9269 0.193344 3.98124C0.0755312 4.03596 0 4.1538 0 4.28368V12.6665C0 13.4019 0.597969 13.9998 1.33334 13.9998H14.6667C15.402 13.9998 16 13.4019 16 12.6665V4.28368C16 4.1538 15.9245 4.03565 15.8067 3.98127Z"
                                                fill="white" />
                                        </svg>
                                        {{ $email_address}}
                                    </a>
                                </li>
                                <li>
                                    @php($phone=\App\CentralLogics\Helpers::get_settings('phone')  )
                                    <a href="tel:{{$phone }}">
                                        <svg width="16" height="14" viewBox="0 0 14 14" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M13.6043 10.2746L11.6505 8.32085C10.9528 7.62308 9.76655 7.90222 9.48744 8.80928C9.27812 9.4373 8.58035 9.78618 7.95236 9.6466C6.55683 9.29772 4.67287 7.48353 4.32398 6.01822C4.11465 5.39021 4.53331 4.69244 5.1613 4.48314C6.0684 4.20403 6.3475 3.01783 5.64974 2.32007L3.696 0.366327C3.13778 -0.122109 2.30047 -0.122109 1.81203 0.366327L0.486277 1.69208C-0.839476 3.08761 0.62583 6.78576 3.90533 10.0653C7.18482 13.3448 10.883 14.8799 12.2785 13.4843L13.6043 12.1586C14.0927 11.6003 14.0927 10.763 13.6043 10.2746Z"
                                                fill="white" />
                                        </svg>
                                        {{ $phone }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="copyright text-center mt-3">
                        {{ \App\CentralLogics\Helpers::get_settings('footer_text') }}
                        {{ translate('By') }} {{ \App\CentralLogics\Helpers::get_settings('business_name') }}
                    </div>
                </div>
            </div>
        </footer>
        <!-- ======= Footer Section ======= -->



        <script src="{{asset('/public/assets/landing/assets_new/js/jquery-3.6.0.min.js')}}"></script>
        <script src="{{asset('/public/assets/landing/assets_new/js/bootstrap.min.js')}}"></script>
        <script src="{{asset('/public/assets/landing/assets_new/js/viewport.jquery.js')}}"></script>
        <script src="{{asset('/public/assets/landing/assets_new/js/wow.min.js')}}"></script>
        <script src="{{asset('/public/assets/landing/assets_new/js/owl.min.js')}}"></script>
        <script src="{{asset('/public/assets/landing/assets_new/js/main.js')}}"></script>
        <script src="{{ asset('/public/assets/admin') }}/js/toastr.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        {!! Toastr::message() !!}
        @stack('script_2')

        <script>
            (function ($) {
                "user strict";
                $(document).ready(function () {

                    var owl = $(".feature-slider").owlCarousel({
                        loop: true,
                        margin: 10,
                        responsiveClass: true,
                        nav: false,
                        dots: false,
                        loop: false,
                        autoplay: true,
                        autoplayTimeout: 1500,
                        autoplayHoverPause: true,
                        rtl: {{$direction}},
                        responsive: {
                            0: {
                                items: 1.2,
                            },
                            500: {
                                items: 2,
                            },
                            768: {
                                items: 2.6,
                            },
                            992: {
                                items: 2.7,
                                margin: 30,
                            },
                            1200: {
                                items: 4,
                                margin: 30,
                            },
                        },
                    });

                    var sync1 = $("#sync1");
                    var sync2 = $("#sync2");
                    var thumbnailItemClass = ".owl-item";
                    var slides = sync1
                        .owlCarousel({
                            startPosition: 12,
                            items: 1,
                            loop: false,
                            margin: 0,
                            mouseDrag: false,
                            touchDrag: false,
                            pullDrag: false,
                            scrollPerPage: true,
                            autoplayHoverPause: false,
                            nav: false,
                            dots: false,
                            center: true,
                        })
                        .on("changed.owl.carousel", syncPosition);

                    function syncPosition(el) {
                        $owl_slider = $(this).data("owl.carousel");
                        var loop = $owl_slider.options.loop;

                        if (loop) {
                            var count = el.item.count - 1;
                            var current = Math.round(el.item.index - el.item.count / 2 - 0.5);
                            if (current < 0) {
                                current = count;
                            }
                            if (current > count) {
                                current = 0;
                            }
                        } else {
                            var current = el.item.index;
                        }

                        var owl_thumbnail = sync2.data("owl.carousel");
                        var itemClass = "." + owl_thumbnail.options.itemClass;

                        var thumbnailCurrentItem = sync2
                            .find(itemClass)
                            .removeClass("synced")
                            .eq(current);
                        thumbnailCurrentItem.addClass("synced");

                        if (!thumbnailCurrentItem.hasClass("active")) {
                            var duration = 500;
                            sync2.trigger("to.owl.carousel", [current, duration, true]);
                        }
                    }
                    var thumbs = sync2
                        .owlCarousel({
                            startPosition: 12,
                            items: 3,
                            loop: false,
                            margin: 10,
                            autoplay: false,
                            nav: false,
                            dots: false,
                            center: true,
                            mouseDrag: false,
                            touchDrag: false,
                            responsive: {
                                500: {
                                    items: 3,
                                },
                                768: {
                                    items: 5,
                                },
                            },
                            onInitialized: function (e) {
                                var thumbnailCurrentItem = $(e.target)
                                    .find(thumbnailItemClass)
                                    .eq(this._current);
                                thumbnailCurrentItem.addClass("synced");
                            },
                        })
                        .on("click", thumbnailItemClass, function (e) {
                            e.preventDefault();
                            var duration = 500;
                            var itemIndex = $(e.target).parents(thumbnailItemClass).index();
                            sync1.trigger("to.owl.carousel", [itemIndex, duration, true]);
                        })
                        .on("changed.owl.carousel", function (el) {
                            var number = el.item.index;
                            $owl_slider = sync1.data("owl.carousel");
                            $owl_slider.to(number, 500, true);
                        });
                    sync1.owlCarousel();

                    sync2.children().each(function (index) {
                        $(this).attr("data-position", index);
                    });

                    $(".owl-item:not(':first-child') .img").on("click", function () {
                        sync2.trigger("to.owl.carousel", [$(this).data("position")]);
                        $(".img").removeClass("trns");
                    });
                    $(".owl-item:first-child .img").on("click", function () {
                        sync1.trigger("prev.owl.carousel", [0]);
                        sync2.trigger("prev.owl.carousel", [0]);
                        sync1.trigger("prev.owl.carousel", [0]);
                        sync2.trigger("prev.owl.carousel", [0]);
                        $(".img").removeClass("trns");
                    });

                    // Go to the next item
                    $(".client-next").on("click", function () {
                        sync1.trigger("next.owl.carousel");
                        sync2.trigger("next.owl.carousel");
                        $(".img").addClass("trns");
                    });
                    // Go to the previous item
                    $(".client-prev").on("click", function () {
                        $(".img").addClass("trns");
                        sync1.trigger("prev.owl.carousel");
                        sync2.trigger("prev.owl.carousel");
                    });
                    $(".owl-prev").html('<i class="fas fa-angle-left">');
                    $(".owl-next").html('<i class="fas fa-angle-right">');
                });
            })(jQuery);
        </script>


    </body>

    </html>
