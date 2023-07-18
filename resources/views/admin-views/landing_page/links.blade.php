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

    <div class="card my-2">
        <div class="card-body">
            <form action="{{route('admin.business-settings.landing-page-settings', 'links')}}" method="POST">
                @csrf
                <div class="row">
                        <div class="col-md-6">
                            <div class="__bg-F8F9FC-card">
                                <div class="form-group mb-md-0">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                            <label class="form-label text-capitalize m-0">
                                                {{translate('messages.app_url')}} ({{translate('messages.play_store')}})
                                                <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="Lorem ipsum">
                                                    <i class="tio-info-outined"></i>
                                                </span>
                                            </label>
                                        <label class="toggle-switch toggle-switch-sm m-0">
                                            <input type="checkbox" {{(isset($landing_page_links) && $landing_page_links['app_url_android_status'])?'checked':''}} class="status toggle-switch-input" id="button_status" value="1"
                                            onclick="toogleModal(event,'button_status','play-store-on.png','play-store-off.png',' <strong>{{translate('By Turning ON The Button')}}</strong>','<strong>{{translate('By Turning OFF The Button')}}</strong>',`<p>{{translate('This button will be enabled now everyone can use or see the button')}}</p>`,`<p>{{translate('This button will be disabled now no one can use or see the button')}}</p>`)" name="app_url_android_status"
                                            >
                                            <span class="toggle-switch-label text mb-0">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </div>
                                    <input type="text" value="{{isset($landing_page_links)?$landing_page_links['app_url_android']:''}}" placeholder="{{translate('Ex: Order now')}}" class="form-control h--45px" name="app_url_android" >
                                </div>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="__bg-F8F9FC-card">
                                <div class="form-group mb-md-0">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                            <label class="form-label text-capitalize m-0">
                                                {{translate('messages.app_url')}}  ({{translate('messages.app_store')}})
                                                <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="Lorem ipsum">
                                                    <i class="tio-info-outined"></i>
                                                </span>
                                            </label>
                                        <label class="toggle-switch toggle-switch-sm m-0">
                                            <input type="checkbox" {{(isset($landing_page_links) && $landing_page_links['app_url_ios_status'])?'checked':''}} class="status toggle-switch-input" id="app_url_ios_status" value="1"
                                            onclick="toogleModal(event,'app_url_ios_status','apple-on.png','apple-off.png',' <strong>{{translate('By Turning ON The Button')}}</strong>','<strong>{{translate('By Turning OFF The Button')}}</strong>',`<p>{{translate('This button will be enabled now everyone can use or see the button')}}</p>`,`<p>{{translate('This button will be disabled now no one can use or see the button')}}</p>`)" name="app_url_ios_status"
                                            >
                                            <span class="toggle-switch-label text mb-0">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </div>
                                    <input type="text" value="{{isset($landing_page_links)?$landing_page_links['app_url_ios']:''}}" placeholder="{{translate('Ex: Order now')}}" class="form-control h--45px" name="app_url_ios" >
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mt-3">
                            <div class="__bg-F8F9FC-card">
                                <div class="form-group mb-md-0">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                            <label class="form-label text-capitalize m-0">
                                                {{translate('messages.web_app_url')}}
                                                <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="Lorem ipsum">
                                                    <i class="tio-info-outined"></i>
                                                </span>
                                            </label>
                                        <label class="toggle-switch toggle-switch-sm m-0">
                                            <input type="checkbox" {{(isset($landing_page_links) && $landing_page_links['web_app_url_status'])?'checked':''}} class="status toggle-switch-input" id="web_app_url_status" value="1"
                                            onclick="toogleModal(event,'web_app_url_status','promotional-on.png','promotional-off.png',' <strong>{{translate('By Turning ON The Button')}}</strong>','<strong>{{translate('By Turning OFF The Button')}}</strong>',`<p>{{translate('This button will be enabled now everyone can use or see the button')}}</p>`,`<p>{{translate('This button will be disabled now no one can use or see the button')}}</p>`)" name="web_app_url_status"
                                            >
                                            <span class="toggle-switch-label text mb-0">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </div>
                                    <input type="text" value="{{isset($landing_page_links)?$landing_page_links['web_app_url']:''}}" placeholder="{{translate('Ex: Order now')}}" class="form-control h--45px" name="web_app_url" >
                                </div>
                            </div>
                        </div>







                    {{-- <div class="col-sm-6 col-lg-4">
                        <div class="form-group">
                            <label class="toggle-switch toggle-switch-sm d-flex justify-content-between input-label mb-1" for="order_now_url_status">
                                <span class="form-check-label">{{translate('messages.order_now_url')}} </span>
                                <input type="checkbox" class="toggle-switch-input" name="order_now_url_status" id="order_now_url_status" value="1" {{(isset($landing_page_links['order_now_url_status']) && $landing_page_links['order_now_url_status'])?'checked':''}}>
                                <span class="toggle-switch-label">
                                    <span class="toggle-switch-indicator"></span>
                                </span>
                            </label>
                            <input type="text" id="order_now_url" name="order_now_url" class="form-control h--45px" value="{{isset($landing_page_links['order_now_url'])?$landing_page_links['order_now_url']:''}}">
                        </div>
                    </div> --}}
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
        $(document).on('ready', function () {
            // $("#app_url_android_status").on('change', function(){
            //     if($("#app_url_android_status").is(':checked')){
            //         $('#app_url_android').removeAttr('readonly');
            //     } else {
            //         $('#app_url_android').attr('readonly', true);
            //     }
            // });
            // $("#app_url_ios_status").on('change', function(){
            //     if($("#app_url_ios_status").is(':checked')){
            //         $('#app_url_ios').removeAttr('readonly');
            //     } else {
            //         $('#app_url_ios').attr('readonly', true);
            //     }
            // });
            // $("#web_app_url_status").on('change', function(){
            //     if($("#web_app_url_status").is(':checked')){
            //         $('#web_app_url').removeAttr('readonly');
            //     } else {
            //         $('#web_app_url').attr('readonly', true);
            //     }
            // });
        });
    </script>
@endpush
