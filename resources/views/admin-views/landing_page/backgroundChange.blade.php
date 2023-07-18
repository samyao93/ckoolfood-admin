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
            <div class="d-flex flex-wrap justify-content-between align-items-start">
                <!-- Page Header -->
                <h1 class="page-header-title text-capitalize">
                    <div class="card-header-icon d-inline-flex mr-2 img">
                        <img src="{{ asset('/public/assets/admin/img/landing-page.png') }}" class="mw-26px" alt="public">
                    </div>
                    <span>
                        {{ translate('Admin Landing Page') }}
                    </span>
                </h1>
                <div class="text--primary-2 py-1 d-flex flex-wrap align-items-center" type="button" data-toggle="modal" data-target="#how-it-works">
                    <strong class="mr-2">{{translate('See_how_it_works')}}</strong>
                    <div>
                        <i class="tio-info-outined"></i>
                    </div>
                </div>
            </div>
            <!-- End Nav Scroller -->
            <div class="js-nav-scroller hs-nav-scroller-horizontal">
                @include('admin-views.landing_page.top_menu.admin_landing_menu')
            </div>
        </div>
        <!-- Page Heading -->

        <div class="card my-2">
            <div class="card-body">
                <form action="{{ route('admin.business-settings.landing-page-settings', 'background-change') }}"
                    method="POST">

                    @csrf
                    <div class="row">
                        <div class="col-sm-4">
                            <label class="form-label d-block text-center">{{ translate('Primary Color 1') }}</label>
                            <input name="header-bg" type="color" class="form-control form-control-color" value="{{ isset($backgroundChange['primary_1_hex']) ? $backgroundChange['primary_1_hex'] : '#EF7822' }}" required>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label d-block text-center">{{ translate('Primary Color 2') }}</label>
                            <input name="footer-bg" type="color" class="form-control form-control-color" value="{{ isset($backgroundChange['primary_2_hex']) ? $backgroundChange['primary_2_hex'] :'#333E4F'}}" required>
                        </div>

                    </div>
                    <div class="form-group text-right mt-3 mb-0">
                        <button type="submit" class="btn btn--primary">{{ translate('messages.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection
@push('script_2')
@endpush
