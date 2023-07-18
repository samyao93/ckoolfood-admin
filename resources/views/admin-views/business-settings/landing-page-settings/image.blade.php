@extends('layouts.admin.app')

@section('title',translate('messages.landing_page_image_settings'))

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
            @php($landing_page_images = \App\Models\BusinessSetting::where(['key'=>'landing_page_images'])->first())
            @php($landing_page_images = isset($landing_page_images->value)?json_decode($landing_page_images->value, true):null)

            <div class="row gy-3">
                <div class="col-sm-6 col-xl-3">
                    <form action="{{route('admin.business-settings.landing-page-settings', 'image')}}" method="POST" enctype="multipart/form-data" class="d-flex flex-column h-100">
                        @csrf
                        <div class="form-group">
                            <span class="input-label text-center mb-3 d-block">{{translate('messages.top_content_background_image')}}<small class="text-danger">* ( {{translate('messages.size')}}: 1241 X 1755 px )</small></span>
                            <label class="d-block">
                                <div class="custom-file d-none">
                                    <input type="file" name="top_content_image" id="customFileEg1" class="custom-file-input"
                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" >
                                </div>
                                <center id="image-viewer-section">
                                    <img class="initial-7" id="viewer"
                                            src="{{asset('public/assets/landing')}}/image/{{isset($landing_page_images['top_content_image'])?$landing_page_images['top_content_image']:'double_screen_image.png'}}"
                                            onerror="this.src='{{asset('public/assets/admin/img/400x400/img2.jpg')}}'"
                                            alt=""/>
                                </center>
                            </label>
                        </div>
                        <div class="form-group text-center mb-0 mt-auto">
                            <div class="landing--page-btns btn--container justify-content-center">
                                <label class="btn btn--reset" for="customFileEg1">{{ translate('Change Image') }}</label>
                                <button type="submit" class="btn btn--primary">{{translate('messages.upload')}}</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-sm-6 col-xl-3">
                    <form action="{{route('admin.business-settings.landing-page-settings', 'image')}}" method="POST" enctype="multipart/form-data" class="d-flex flex-column h-100">
                        @csrf
                        <div class="form-group">
                            <span class="input-label text-center mb-3 d-block" >{{translate('messages.top_content_image')}}<small class="text-danger">* ( {{translate('messages.size')}}: 772 X 899 px )</small></span>
                            <label class="d-block">
                                <div class="custom-file d-none">
                                    <input type="file" name="feature_section_image" id="customFileEg3" class="custom-file-input"
                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" >
                                </div>

                                <center id="image-viewer-section3" >
                                    <img class="initial-7" id="viewer3"
                                            src="{{asset('public/assets/landing')}}/image/{{isset($landing_page_images['feature_section_image'])?$landing_page_images['feature_section_image']:'feature_section_image.png'}}"
                                            onerror="this.src='{{asset('public/assets/admin/img/160x160/img2.png')}}'"
                                            alt=""/>
                                </center>
                            </label>
                        </div>
                        <div class="form-group text-center mb-0 mt-auto">
                            <div class="landing--page-btns btn--container justify-content-center">
                                <label class="btn btn--reset" for="customFileEg3">{{ translate('Change Image') }}</label>
                                <button type="submit" class="btn btn--primary">{{translate('messages.upload')}}</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-sm-6 col-xl-3">
                    <form action="{{route('admin.business-settings.landing-page-settings', 'image')}}" method="POST" enctype="multipart/form-data" class="d-flex flex-column h-100">
                        @csrf
                        <div class="form-group">
                            <span class="input-label text-center mb-3 d-block" >{{translate('messages.about_us_image')}}<small class="text-danger">* ( {{translate('messages.size')}}: 772 X 899 px )</small></span>
                            <label class="d-block">
                                <div class="custom-file d-none">
                                    <input type="file" name="about_us_image" id="customFileEg2" class="custom-file-input"
                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" >
                                </div>

                                <center id="image-viewer-section2" >
                                    <img class="initial-7" id="viewer2"
                                            src="{{asset('public/assets/landing')}}/image/{{isset($landing_page_images['about_us_image'])?$landing_page_images['about_us_image']:'about_us_image.png'}}"
                                            onerror="this.src='{{asset('public/assets/admin/img/160x160/img2.png')}}'"
                                            alt=""/>
                                </center>
                            </label>
                        </div>
                        <div class="form-group text-center mb-0 mt-auto">
                            <div class="landing--page-btns btn--container justify-content-center">
                                <label class="btn btn--reset" for="customFileEg2">{{ translate('Change Image') }}</label>
                                <button type="submit" class="btn btn--primary">{{translate('messages.upload')}}</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-sm-6 col-xl-3">
                    <form action="{{route('admin.business-settings.landing-page-settings', 'image')}}" method="POST" enctype="multipart/form-data" class="d-flex flex-column h-100">
                            @csrf
                        <div class="form-group">
                            <span class="input-label text-center mb-3 d-block" >{{translate('messages.join_us_image')}}<small class="text-danger">* ( {{translate('messages.size')}}: 772 X 899 px )</small></span>
                            <label class="d-block">
                                <div class="custom-file d-none">
                                    <input type="file" name="mobile_app_section_image" id="customFileEg4" class="custom-file-input"
                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" >
                                </div>

                                <center id="image-viewer-section4" >
                                    <img class="initial-7" id="viewer4"
                                            src="{{asset('public/assets/landing')}}/image/{{isset($landing_page_images['mobile_app_section_image'])?$landing_page_images['mobile_app_section_image']:'our_app_image.png.png'}}"
                                            onerror="this.src='{{asset('public/assets/admin/img/160x160/img2.png')}}'"
                                            alt=""/>
                                </center>
                            </label>
                        </div>
                        <div class="form-group text-center mb-0 mt-auto">
                            <div class="landing--page-btns btn--container justify-content-center">
                                <label class="btn btn--reset" for="customFileEg4">{{ translate('Change Image') }}</label>
                                <button type="submit" class="btn btn--primary">{{translate('messages.upload')}}</button>
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

                reader.onload = function (e) {
                    $('#'+viewer).attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function () {
            readURL(this, 'viewer');
            $('#image-viewer-section').show(1000);
        });


        $("#customFileEg2").change(function () {
            readURL(this ,'viewer2');
            $('#image-viewer-section2').show(1000);
        });

        $("#customFileEg3").change(function () {
            readURL(this ,'viewer3');
            $('#image-viewer-section3').show(1000);
        });

        $("#customFileEg4").change(function () {
            readURL(this ,'viewer4');
            $('#image-viewer-section4').show(1000);
        });

    </script>
@endpush
