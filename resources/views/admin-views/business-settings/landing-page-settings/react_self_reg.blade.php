@extends('layouts.admin.app')

@section('title', translate('messages.landing_page_settings'))

@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{ asset('public/assets/admin/css/croppie.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <!-- Page Header -->
            <h1 class="page-header-title text-capitalize">
                <div class="card-header-icon d-inline-flex mr-2 img">
                    <img src="{{ asset('/public/assets/admin/img/landing-page.png') }}" class="mw-26px" alt="public">
                </div>
                <span>
                    {{ translate('messages.landing_page_settings') }}
                </span>
            </h1>
            <!-- End Page Header -->
            <!-- Nav Scroller -->
            <div class="js-nav-scroller hs-nav-scroller-horizontal">
                <!-- Nav -->
                @include('admin-views.business-settings.landing-page-settings.top-menu-links.top-menu-links')
                <!-- End Nav -->
            </div>
            <!-- End Nav Scroller -->
            <div class="js-nav-scroller hs-nav-scroller-horizontal">
                <!-- Nav -->
                <ul class="nav nav-tabs page-header-tabs">
                    <li class="nav-item">
                        <a class="nav-link "
                            href="{{ route('admin.business-settings.landing-page-settings', 'react') }}"
                            aria-disabled="true">{{ translate('React Landing Page') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link "
                            href="{{ route('admin.business-settings.landing-page-settings', 'react-half-banner') }}"
                            aria-disabled="true">{{ translate('React Half Banner Section ') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link "
                            href="{{ route('admin.business-settings.landing-page-settings', 'react-feature') }}"
                            aria-disabled="true">{{ translate('React Landing Page Features') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active"
                            href="{{ route('admin.business-settings.landing-page-settings', 'react-self-registration') }}"
                            aria-disabled="true">{{ translate('React_Self_Registration') }}</a>
                    </li>
                </ul>
                <!-- End Nav -->
            </div>
        </div>
        <!-- End Page Header -->
        <div class="card my-2">
            <div class="card-body">
                <div>
                    <h1 class="text-center">{{ translate('Reataurant_Self_Registration') }}</h3>
                </div>
                <div>
                    <h3>{{ translate('Reataurant') }}</h3>
                </div>
                <div class="card my-2">
                    <div class="card-body">
                        <form action="{{ route('admin.business-settings.landing-page-settings', 'react-self-registration-restaurant') }}"
                            method="POST" enctype="multipart/form-data">
                            @php($react_self_registration_restaurant = \App\Models\BusinessSetting::where(['key' => 'react_self_registration_restaurant'])->first()?->value)
                            @php($react_self_registration_restaurant = json_decode($react_self_registration_restaurant, true) )

                            @csrf

                            <div class="row gy-3">
                                <div class="col-lg-6">
                                    <div class="form-group ">
                                        <label class="input-label"
                                            for="react_self_registration_restaurant">{{ translate('Title') }}
                                            <span data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('max_24_characters') }}"
                                            class="input-label-secondary"><img
                                                src="{{ asset('/public/assets/admin/img/info-circle.svg') }}"
                                                alt="{{ translate('max_24_characters') }}"></span>

                                        </label>
                                        <input type="text" id="react_self_registration_restaurant" name="title"
                                            value="{{ $react_self_registration_restaurant['title'] ?? null }}"  maxlength="24" required
                                            class="form-control h--45px" placeholder="{{ translate('Ex: Title') }}">
                                    </div>
                                    <div class="form-group ">
                                        <label class="input-label"
                                            for="react_self_registration_restaurant-sub">{{ translate('Sub_Title') }}
                                            <span data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('max_55_characters') }}"
                                            class="input-label-secondary"><img
                                                src="{{ asset('/public/assets/admin/img/info-circle.svg') }}"
                                                alt="{{ translate('max_55_characters') }}"></span>

                                        </label>
                                        <input type="text" id="react_self_registration_restaurant-sub" name="sub_title" maxlength="55"
                                            value="{{ $react_self_registration_restaurant['sub_title'] ?? null }}"
                                            class="form-control h--45px" required
                                            placeholder="{{ translate('Ex: Sub Title') }}">
                                    </div>
                                    <div class="form-group">
                                        <label class="toggle-switch toggle-switch-sm d-flex justify-content-between input-label mb-1" for="button_status">
                                            <span class="form-check-label"> {{translate('messages.button_name')}}
                                                <span data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('max_10_characters') }}"
                                            class="input-label-secondary"><img
                                                src="{{ asset('/public/assets/admin/img/info-circle.svg') }}"
                                                alt="{{ translate('max_10_characters') }}"></span>

                                                </span>
                                                <input type="checkbox" class="toggle-switch-input" name="button_status" id="button_status" value="1" {{(isset($react_self_registration_restaurant) && isset($react_self_registration_restaurant['button_status']))?'checked':''}}>
                                                <span class="toggle-switch-label">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                            <input type="text" id="button_name" maxlength="10" name="button_name" class="form-control h--45px" value="{{isset($react_self_registration_restaurant)?$react_self_registration_restaurant['button_name'] ?? null :''}}">
                                        </div>
                                        <div class="form-group  mb-0">
                                            <label class="input-label"
                                                for="button_link-sub">{{ translate('Button_link') }}
                                            </label>
                                            <input type="text" id="button_link-sub" name="button_link" maxlength="55"
                                                value="{{ $react_self_registration_restaurant['button_link'] ?? null }}"
                                                class="form-control h--45px" required
                                                placeholder="{{ translate('Ex: Sub Title') }}">
                                        </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group h-100 d-flex flex-column mb-0">
                                        <label
                                            class="input-label text-center d-block mt-auto mb-lg-0">{{ translate('Image') }}
                                            {{-- <small
                                                class="text-danger">* (
                                                {{ translate('messages.size') }}: {{ translate('1352 X 250 px') }}
                                                )</small> --}}
                                            </label>

                                                <center id="image-viewer-section" class="pt-2 mt-auto mb-auto">
                                                    <img class="initial-5" id="viewer"
                                                        src="{{ asset('storage/app/public/react_landing') }}/{{ $react_self_registration_restaurant['image'] ?? null }}"
                                                        onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.png') }}'"
                                                        alt="" />
                                                </center>

                                            <div class="custom-file mt-2">
                                                <input type="file" name="image" id="customFileEg1"
                                                    class="custom-file-input"
                                                    accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" >
                                                <label class="custom-file-label"
                                                    for="customFileEg1">{{ translate('messages.choose') }}
                                                    {{ translate('messages.file') }}</label>
                                            </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <div class="btn--container justify-content-end">
                                    <button type="reset" id="reset_btn"
                                        class="btn btn--reset">{{ translate('messages.reset') }}</button>
                                    <button type="submit"
                                        class="btn btn--primary">{{ translate('messages.submit') }}</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="card my-2">
            <div class="card-body">
                <div>
                    <h1 class="text-center">{{ translate('Delivery_Man_Self_Registration') }}</h3>
                </div>
                <div>
                    <h3>{{ translate('Delivery_man') }}</h3>
                </div>
                <div class="card my-2">
                    <div class="card-body">
                        <form action="{{ route('admin.business-settings.landing-page-settings', 'react-self-registration-delivery-man') }}"
                            method="POST" enctype="multipart/form-data">
                            @php($react_self_registration_delivery_man = \App\Models\BusinessSetting::where(['key' => 'react_self_registration_delivery_man'])->first()?->value)
                            @php($react_self_registration_delivery_man = json_decode($react_self_registration_delivery_man, true) )

                            @csrf

                            <div class="row gy-3">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="full_banner_section">{{ translate('Title') }}
                                            <span data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('max_24_characters') }}"
                                            class="input-label-secondary"><img
                                                src="{{ asset('/public/assets/admin/img/info-circle.svg') }}"
                                                alt="{{ translate('max_24_characters') }}"></span>
                                        </label>
                                        <input type="text" id="full_banner_section" name="title"
                                            value="{{ $react_self_registration_delivery_man['title'] ?? null }}" required maxlength="24"
                                            class="form-control h--45px" placeholder="{{ translate('Ex: Title') }}">
                                    </div>
                                    <div class="form-group">
                                        <label class="input-label"
                                            for="full_banner_section-s">{{ translate('Sub Title') }}
                                            <span data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('max_55_characters') }}"
                                            class="input-label-secondary"><img
                                                src="{{ asset('/public/assets/admin/img/info-circle.svg') }}"
                                                alt="{{ translate('max_55_characters') }}"></span>

                                        </label>
                                        <input type="text" id="full_banner_section-s" name="sub_title"
                                            value="{{ $react_self_registration_delivery_man['sub_title'] ?? null }}"
                                            class="form-control h--45px" required  maxlength="55"
                                            placeholder="{{ translate('Ex: Sub title') }}">
                                    </div>

                                    <div class="form-group">
                                        <label class="toggle-switch toggle-switch-sm d-flex justify-content-between input-label mb-1" for="button_status-1">
                                            <span class="form-check-label"> {{translate('messages.button_name')}}
                                                <span data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('max_10_characters') }}"
                                            class="input-label-secondary"><img
                                                src="{{ asset('/public/assets/admin/img/info-circle.svg') }}"
                                                alt="{{ translate('max_10_characters') }}"></span>

                                            </span>
                                            <input type="checkbox" class="toggle-switch-input" name="button_status" id="button_status-1" value="1" {{(isset($react_self_registration_delivery_man) && isset($react_self_registration_delivery_man['button_status']))?'checked':''}}>
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                        <input type="text" id="button_name" maxlength="10" name="button_name" class="form-control h--45px" value="{{isset($react_self_registration_delivery_man)?$react_self_registration_delivery_man['button_name'] ?? null :''}}">
                                    </div>
                                    <div class="form-group  mb-0">
                                        <label class="input-label"
                                            for="button_link-sub">{{ translate('Button_link') }}
                                        </label>
                                        <input type="text" id="button_link-sub" name="button_link" maxlength="55"
                                            value="{{ $react_self_registration_delivery_man['button_link'] ?? null }}"
                                            class="form-control h--45px" required
                                            placeholder="{{ translate('Ex: Sub Title') }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group h-100 d-flex flex-column mb-0">
                                        <label
                                            class="input-label text-center d-block mt-auto mb-lg-0">{{ translate('Image') }}
                                            {{-- <small
                                                class="text-danger">* (
                                                {{ translate('messages.size') }}: {{ translate('1352 X 250 px') }}
                                                )</small> --}}
                                            </label>

                                                <center id="image-viewer-section2" class="pt-2 mt-auto mb-auto">
                                                    <img class="initial-5" id="viewer2"
                                                        src="{{ asset('storage/app/public/react_landing') }}/{{ $react_self_registration_delivery_man['image'] ?? null }}"
                                                        onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.png') }}'"
                                                        alt="" />
                                                </center>

                                            <div class="custom-file mt-2">
                                                <input type="file" name="image" id="customFileEg2"
                                                    class="custom-file-input"
                                                    accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" >
                                                <label class="custom-file-label"
                                                    for="customFileEg2">{{ translate('messages.choose') }}
                                                    {{ translate('messages.file') }}</label>
                                            </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <div class="btn--container justify-content-end">
                                    <button type="reset" id="reset_btn"
                                        class="btn btn--reset">{{ translate('messages.reset') }}</button>
                                    <button type="submit"
                                        class="btn btn--primary">{{ translate('messages.submit') }}</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>



    </div>
@endsection

@push('script_2')
    <script>
        function readURL(input, viewer) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#' + viewer).attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function() {
            readURL(this, 'viewer');
            $('#image-viewer-section').show(1000);
        });
        $("#customFileEg2").change(function() {
            readURL(this, 'viewer2');
            $('#image-viewer-section3').show(1000);
        });
    </script>
@endpush
