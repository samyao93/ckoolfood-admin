@extends('layouts.admin.app')

@section('title',translate('messages.landing_page_settings'))

@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{asset('public/assets/admin/css/croppie.css')}}" rel="stylesheet">
@endpush

@section('content')
<div class="content container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <!-- Page Header -->
        <h1 class="page-header-title text-capitalize">
            <div class="card-header-icon d-inline-flex mr-2 img">
                <img src="{{asset('/public/assets/admin/img/landing-page.png')}}" class="mw-26px" alt="public">
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
    </div>
    <!-- End Page Header -->
    <!-- Page Heading -->
    <div class="card my-2">
        <div class="card-body">
            <form action="{{ route('admin.business-settings.landing-page-settings', 'text') }}" method="POST">
                @php($landing_page_text = \App\Models\BusinessSetting::where(['key' => 'landing_page_text'])->first())
                @php($landing_page_text = isset($landing_page_text->value) ? json_decode($landing_page_text->value, true) : null)
                @csrf
                <div class="form-group">
                    <label class="form-label" for="header_title_1">{{ translate('Top Header Title') }}</label>
                    <input type="text" id="header_title_1" name="header_title_1" class="form-control h--45px"
                        value="{{ isset($landing_page_text) ? $landing_page_text['header_title_1'] : '' }}" placeholder="{{ translate('Ex: Stackfood App') }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="header_title_2">{{ translate('Top Header Sub Title 2') }}</label>
                    <input type="text" id="header_title_2" name="header_title_2" class="form-control h--45px"
                        value="{{ isset($landing_page_text) ? $landing_page_text['header_title_2'] : '' }}" placeholder="{{ translate('Ex: Why stay hungry when you can order from StackFood') }}">
                </div>

                <div class="form-group">
                    <label class="form-label" for="header_title_3">{{ translate('Top Header Sub Title 3') }}</label>
                    <input type="text" id="header_title_3" name="header_title_3" class="form-control h--45px"
                        value="{{ isset($landing_page_text) ? $landing_page_text['header_title_3'] : '' }}" placeholder="{{ translate('Ex: 10% off !') }}">
                </div>

                <div class="form-group">
                    <label class="form-label" for="about_title">{{ translate('About Section Title') }}</label>
                    <input type="text" id="about_title" name="about_title" class="form-control h--45px"
                        value="{{ isset($landing_page_text) ? $landing_page_text['about_title'] : '' }}" placeholder="{{ translate('Ex: StackFood is Best Delivery Service Near You') }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="feature_section_title">{{ translate('messages.feature_section_title') }}</label>
                    <input type="text" id="feature_section_title" name="feature_section_title" class="form-control h--45px"
                        value="{{ isset($landing_page_text['feature_section_title']) ? $landing_page_text['feature_section_title'] : '' }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="feature_section_description">{{ translate('messages.feature_section_description') }}</label>
                    <textarea id="feature_section_description" name="feature_section_description" class="form-control" cols="30" rows="5"
                        placeholder="{{ translate('Feature section description') }}">
                        {{ isset($landing_page_text['feature_section_description']) ? $landing_page_text['feature_section_description'] : '' }}
                    </textarea>
                </div>
                {{-- <div class="form-group">
                    <label class="form-label" for="mobile_app_section_heading">{{ translate('Mobile App Section Title') }}</label>
                    <input type="text" id="mobile_app_section_heading" name="mobile_app_section_heading" class="form-control h--45px"
                    value="{{ isset($landing_page_text['mobile_app_section_heading']) ? $landing_page_text['mobile_app_section_heading'] : '' }}">

                </div> --}}
                {{-- <div class="form-group">
                    <label class="form-label" for="mobile_app_section_text">{{ translate('Mobile App Section Short Description') }}</label>
                    <input type="text" id="mobile_app_section_text" name="mobile_app_section_text" class="form-control h--45px"
                    value="{{ isset($landing_page_text['mobile_app_section_text']) ? $landing_page_text['mobile_app_section_text'] : '' }}" placeholder="{{ translate('Mobile App Section Text') }}">
                </div> --}}
                <div class="form-group">
                    <label class="form-label" for="why_choose_us">{{ translate('Why Choose Us Section Title') }}</label>
                    <input type="text" id="why_choose_us" name="why_choose_us" class="form-control h--45px"
                        value="{{ isset($landing_page_text) ? $landing_page_text['why_choose_us'] : '' }}" placeholder="{{ translate('Ex: Why choose us') }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="why_choose_us_title">{{ translate('Why Choose Us Short Description') }}</label>
                    <input type="text" id="why_choose_us_title" name="why_choose_us_title" class="form-control h--45px"
                        value="{{ isset($landing_page_text) ? $landing_page_text['why_choose_us_title'] : '' }}" placeholder="{{ translate('Ex: description') }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="testimonial_title">{{ translate('Testimonial Section Title') }}</label>
                    <input type="text" id="testimonial_title" name="testimonial_title" class="form-control h--45px"
                        value="{{ isset($landing_page_text) ? $landing_page_text['testimonial_title'] : '' }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="join_us_title">{{ translate('Join Us Section Title') }}</label>
                    <input type="text" id="join_us_title" name="join_us_title" class="form-control h--45px"
                        value="{{ isset($landing_page_text['join_us_title']) ? $landing_page_text['join_us_title'] : '' }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="join_us_article">{{ translate('messages.join_us_sub_title') }}</label>
                    <textarea type="text" id="join_us_article" name="join_us_article"
                        class="form-control">{{ isset($landing_page_text['join_us_article']) ? $landing_page_text['join_us_article'] : '' }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label" for="join_us_sub_title">{{ translate('Join Us Article') }}</label>
                    <input type="text" id="join_us_sub_title" name="join_us_sub_title" class="form-control h--45px"
                        value="{{ isset($landing_page_text['join_us_sub_title']) ? $landing_page_text['join_us_sub_title'] : '' }}">
                </div>

                <div class="form-group">
                    <label class="form-label" for="our_platform_title">{{ translate('our_platform_title') }}</label>
                    <input type="text" id="our_platform_title" name="our_platform_title" class="form-control h--45px"
                        value="{{ isset($landing_page_text['our_platform_title']) ? $landing_page_text['our_platform_title'] : '' }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="our_platform_article">{{ translate('messages.our_platform_article') }}</label>
                    <textarea type="text" id="our_platform_article" name="our_platform_article"
                        class="form-control">{{ isset($landing_page_text['our_platform_article']) ? $landing_page_text['our_platform_article'] : '' }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label" for="newsletter_title">{{ translate('Newsletter_title') }}</label>
                    <input type="text" id="newsletter_title" name="newsletter_title" class="form-control h--45px"
                        value="{{ isset($landing_page_text['newsletter_title']) ? $landing_page_text['newsletter_title'] : '' }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="newsletter_article">{{ translate('messages.Newsletter_article') }}</label>
                    <textarea type="text" id="newsletter_article" name="newsletter_article"
                        class="form-control">{{ isset($landing_page_text['newsletter_article']) ? $landing_page_text['newsletter_article'] : '' }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label" for="footer_article">{{ translate('messages.footer_article') }}</label>
                    <textarea type="text" id="footer_article" name="footer_article"
                        class="form-control">{{ isset($landing_page_text['footer_article']) ? $landing_page_text['footer_article'] : '' }}</textarea>
                </div>
                <div class="form-group mb-0">
                    <div class="btn--container justify-content-end">
                        <button type="reset" class="btn btn--reset">{{ translate('messages.reset') }}</button>
                        <button type="submit" class="btn btn--primary">{{ translate('messages.submit') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('script_2')
<script>
    $('document').ready(function() {
        $('textarea').each(function() {
            $(this).val($(this).val().trim());
        });
    });
</script>
@endpush
