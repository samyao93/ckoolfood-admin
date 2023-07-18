@php
$theme_value = \App\Models\BusinessSetting::where('key', 'theme')->first()->value;
@endphp
@extends('layouts.admin.app')
@section('title', translate('themes'))
@push('css_or_js')
    <link rel="stylesheet" href="{{ asset('public/assets/admin/css/radio-image.css') }}">
@endpush
@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title text-capitalize">
                <div class="card-header-icon d-inline-flex mr-2 img">
                    <img src="{{asset('/public/assets/admin/img/theme.png')}}" alt="public">
                </div>
                <span>
                    {{ translate('messages.change_theme_for_user_app') }}
                </span>
            </h1>
        </div>
        <!-- End Page Header -->
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.business-settings.theme-settings-update') }}" method="post"
                    enctype="multipart/form-data" class="pt-md-5">
                    @csrf
                    <div class="form-group" id="user_app_theme">

                        <div class="row">
                            <div class='col-md-3 col-sm-6 col-12 text-center'>
                                <input type="radio" name="theme" require id="img1" class="d-none imgbgchk" value="1"
                                    {{ $theme_value == 1 ? 'checked' : '' }}>

                                <label for="img1">
                                    <img class="img-thumbnail rounded"
                                        src="{{ asset('public/assets/admin/img/Theme-1.png') }}" alt="Image 1">
                                </label>
                            </div>
                            <div class='col-md-3 col-sm-6 col-12 text-center'>
                                <input type="radio" name="theme" require id="img2" class="d-none imgbgchk" value="2" {{ $theme_value == 2 ? 'checked' : '' }}>
                                <label for="img2">
                                    <img class="img-thumbnail rounded"
                                        src="{{ asset('public/assets/admin/img/Theme-2.png') }}" alt="Image 2">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group pt-2">
                        <div class="btn--container justify-content-end">
                            <button type="submit" class="btn btn--primary">{{translate('messages.apply')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('script_2')
@endpush
