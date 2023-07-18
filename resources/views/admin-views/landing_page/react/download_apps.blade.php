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
                    {{ translate('React Landing Page') }}
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
            @include('admin-views.landing_page.top_menu.react_landing_menu')
        </div>
    </div>



        <div class="d-flex justify-content-between __gap-12px mt-3 mb-2">
            <h5 class="title mr-2 d-flex align-items-center">
                {{-- <span class="card-header-icon mr-2">
                    <img src="{{asset('public/assets/admin/img/seller.png')}}" alt="" class="mw-100">
                </span> --}}
                <span class="card-header-icon mr-2">
                    <i class="tio-settings-outlined"></i>
                </span>
                {{translate('Fixed_Banner_Section')}}
            </h5>
            {{-- <div class="text--primary-2 py-1 d-flex flex-wrap align-items-center" type="button" data-toggle="modal" data-target="#section_view">
                <strong class="mr-2">{{translate('Section View')}}</strong>
                <div>
                    <i class="tio-intersect"></i>
                </div>
            </div> --}}
        </div>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.react_landing_page.settings', 'react-download-apps-banner-image') }}" enctype="multipart/form-data"     method="post">
                    @csrf
                    <label class="form-label d-block mb-2">
                        {{ translate('Banner_Image') }} <span class="text--primary">(1200x250)</span>
                    </label>
                    <div class="position-relative d-inline-block">
                        <label class="upload-img-3 upload-image-5 border--dashed border-1px m-0 rounded border-9EADC1">
                            <div class="img">
                                <img src="{{asset('storage/app/public/react_download_apps_image')}}/{{$react_download_apps_banner_image?->value}}" onerror='this.src="{{asset('/public/assets/admin/img/upload.png')}}"' alt="">
                            </div>
                            <input type="file" required name="react_download_apps_banner_image" hidden>
                        </label>
                            @if ($react_download_apps_banner_image?->value)
                            <span id="remove_image" class="remove_image_button"
                                onclick="toogleStatusModal(event,'remove_image','mail-success','mail-warninh','{{translate('Important!')}}','{{translate('Warning!')}}',`<p>{{translate('Are_you_sure_you_want_to_remove_this_image')}}</p>`,`<p>{{translate('Are_you_sure_you_want_to_remove_this_image.')}}</p>`)"
                                > <i class="tio-clear"></i></span>
                            @endif

                    </div>


                <div class="btn--container justify-content-end mt-3">
                    <button type="reset" class="btn btn--reset">{{translate('Reset')}}</button>
                    <button type="submit" onclick="" class="btn btn--primary mb-2">{{translate('Save')}}</button>
                </div>
                </form>
            </div>
        </div>

        <div class="d-flex justify-content-between __gap-12px  mt-5 mb-2">
            <h5 class="card-title d-flex align-items-center">
                {{-- <span class="card-header-icon mr-2">
                    <img src="{{asset('public/assets/admin/img/seller.png')}}" alt="" class="mw-100">
                </span> --}}
                <span class="card-header-icon mr-2">
                    <i class="tio-settings-outlined"></i>
                </span>
                {{translate('Customer_App_Download_Section')}}
            </h5>
            {{-- <div class="text--primary-2 py-1 d-flex flex-wrap align-items-center" type="button" data-toggle="modal" data-target="#section_view">
                <strong class="mr-2">{{translate('Section View')}}</strong>
                <div>
                    <i class="tio-intersect"></i>
                </div>
            </div> --}}
        </div>
        @php($default_lang = str_replace('_', '-', app()->getLocale()))
        @if($language)
            <ul class="nav nav-tabs mb-4 border-0">
                <li class="nav-item">
                    <a class="nav-link lang_link active"
                    href="#"
                    id="default-link">{{translate('messages.default')}}</a>
                </li>
                @foreach (json_decode($language) as $lang)
                    <li class="nav-item">
                        <a class="nav-link lang_link"
                            href="#"
                            id="{{ $lang }}-link">{{ \App\CentralLogics\Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')' }}</a>
                    </li>
                @endforeach
            </ul>
        @endif

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.react_landing_page.settings', 'react-download-apps') }}" enctype="multipart/form-data"     method="post">
                    @csrf

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="lang_form default-form">
                                <div class="row g-3">
                                        <input type="hidden" name="lang[]" value="default">
                                        <div class="col-12">
                                            <label class="form-label">{{translate('Title')}}  ({{ translate('default') }})
                                                     <span class="input-label-secondary text--title" data-toggle="tooltip"
                                        data-placement="right"
                                        data-original-title="{{ translate('Write_the_title_within_30_characters') }}">
                                            <i class="tio-info-outined"></i>
                                        </span>
                                            </label>
                                            <input type="text" maxlength="30" class="form-control" placeholder="{{translate('messages.Enter_Title...')}}" name="react_download_apps_title[]"   value="{{ $react_download_apps_title?->getRawOriginal('value') ?? '' }}" >
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">{{translate('Subtitle')}}  ({{ translate('default') }})
                                                  <span class="input-label-secondary text--title" data-toggle="tooltip"
                                        data-placement="right"
                                        data-original-title="{{ translate('Write_the_subtitle_within_100_characters') }}">
                                            <i class="tio-info-outined"></i>
                                        </span>
                                            </label>
                                            <input type="text" maxlength="100" class="form-control" placeholder="{{translate('Enter Sub Title')}}" name="react_download_apps_sub_title[]"  value="{{ $react_download_apps_sub_title?->getRawOriginal('value') ?? '' }}">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">{{translate('Tag_line')}}  ({{ translate('default') }})
                                                <span class="input-label-secondary text--title" data-toggle="tooltip"
                                                data-placement="right"
                                                data-original-title="{{ translate('Write_the_Tagline_within_100_characters') }}">
                                                    <i class="tio-info-outined"></i>
                                                </span>
                                            </label>
                                            <input type="text" maxlength="100" class="form-control" placeholder="{{translate('Enter_Tag_line')}}" name="react_download_apps_tag[]"  value="{{ $react_download_apps_tag?->getRawOriginal('value') ?? '' }}">
                                        </div>
                                    </div>
                                </div>

                            @if ($language)
                                @forelse(json_decode($language) as $lang)
                                    <?php
                                        if($react_download_apps_title?->translations){
                                                $react_download_apps_title_translate = [];
                                                foreach($react_download_apps_title->translations as $t)
                                                {
                                                    if($t->locale == $lang && $t->key=='react_download_apps_title'){
                                                        $react_download_apps_title_translate[$lang]['value'] = $t->value;
                                                    }
                                                }
                                            }
                                        if($react_download_apps_sub_title?->translations){
                                                $react_download_apps_sub_title_translate = [];
                                                foreach($react_download_apps_sub_title->translations as $t)
                                                {
                                                    if($t->locale == $lang && $t->key=='react_download_apps_sub_title'){
                                                        $react_download_apps_sub_title_translate[$lang]['value'] = $t->value;
                                                    }
                                                }
                                            }
                                        if($react_download_apps_tag?->translations){
                                                $react_download_apps_tag_translate = [];
                                                foreach($react_download_apps_tag->translations as $t)
                                                {
                                                    if($t->locale == $lang && $t->key=='react_download_apps_tag'){
                                                        $react_download_apps_tag_translate[$lang]['value'] = $t->value;
                                                    }
                                                }
                                            }
                                    ?>
                                    <input type="hidden" name="lang[]" value="{{$lang}}">
                                    <div class="d-none lang_form" id="{{$lang}}-form1">
                                        <div class="row g-3">
                                                <div class="col-12">
                                                    <label class="form-label">{{translate('Title')}} ({{strtoupper($lang)}})
                                                         <span class="input-label-secondary text--title" data-toggle="tooltip"
                                        data-placement="right"
                                        data-original-title="{{ translate('Write_the_title_within_30_characters') }}">
                                            <i class="tio-info-outined"></i>
                                        </span>
                                                    </label>
                                                    <input type="text" maxlength="30" class="form-control" name="react_download_apps_title[]" placeholder="{{translate('messages.Enter_Title...')}}" value="{{ $react_download_apps_title_translate[$lang]['value'] ?? ''}}">
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label">{{translate('Subtitle')}} ({{strtoupper($lang)}})
                                                      <span class="input-label-secondary text--title" data-toggle="tooltip"
                                        data-placement="right"
                                        data-original-title="{{ translate('Write_the_subtitle_within_100_characters') }}">
                                            <i class="tio-info-outined"></i>
                                        </span>
                                                    </label>
                                                    <input type="text" maxlength="100" class="form-control" placeholder="{{translate('Enter Sub Title')}}" name="react_download_apps_sub_title[]" value="{{ $react_download_apps_sub_title_translate[$lang]['value'] ?? ''}}">
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label">{{translate('Tag_line')}} ({{strtoupper($lang)}})
                                                        <span class="input-label-secondary text--title" data-toggle="tooltip"
                                                        data-placement="right"
                                                        data-original-title="{{ translate('Write_the_Tagline_within_100_characters') }}">
                                                            <i class="tio-info-outined"></i>
                                                        </span>
                                                    </label>
                                                    <input type="text" maxlength="100" class="form-control" placeholder="{{translate('Enter_Tag_line')}}" name="react_download_apps_tag[]" value="{{ $react_download_apps_tag_translate[$lang]['value'] ?? ''}}">
                                                </div>
                                        </div>
                                    </div>
                                    @empty
                                @endforelse
                            @endif
                        </div>

                            <div class="col-md-6">
                                <label class="form-label d-block mb-2">
                                {{ translate('Banner') }}  <span class="text--primary">({{ translate('1:1') }} )</span>
                                </label>
                                <div class="position-relative d-inline-block">
                                    <label class="upload-img-3 m-0">
                                        <div class="img">
                                            <img src="{{asset('storage/app/public/react_download_apps_image')}}/{{$react_download_apps_image?->value}}" onerror='this.src="{{asset('/public/assets/admin/img/upload-3.png')}}"' class="vertical-img max-w-187px" alt="">
                                        </div>
                                        <input type="file" name="react_download_apps_image" hidden>
                                    </label>

                                    @if ($react_download_apps_image?->value)
                                    <span id="remove_image_1" class="remove_image_button"
                                        onclick="toogleStatusModal(event,'remove_image_1','mail-success','mail-warninh','{{translate('Important!')}}','{{translate('Warning!')}}',`<p>{{translate('Are_you_sure_you_want_to_remove_this_image')}}</p>`,`<p>{{translate('Are_you_sure_you_want_to_remove_this_image.')}}</p>`)"
                                        > <i class="tio-clear"></i></span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mt-3">
                            <div class="col-md-6">
                                <h5 class="card-title mb-3">
                                    <img src="http://localhost/stackfood/public/assets/admin/img/andriod.png" class="mr-2" alt="">
                                    {{ translate('messages.Playstore_Button') }}
                                </h5>
                                <div class="__bg-F8F9FC-card">
                                    <div class="form-group mb-md-0">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                <label class="form-label text-capitalize m-0">
                                                    {{translate('messages.Download_Link')}}
                                                    <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Add_the_Customer_app_download_address_(Play_Store)_where_the_button_will_redirect') }}">
                                                        <i class="tio-info-outined"></i>
                                                    </span>
                                                </label>
                                            <label class="toggle-switch toggle-switch-sm m-0">
                                                <input type="checkbox" class="status toggle-switch-input" id="react_download_apps_button_status"
                                                onclick="toogleModal(event,'react_download_apps_button_status','play-store-on.png','play-store-off.png',' <strong>{{translate('Want_to_enable_the_Customer_App_Download_button_here')}}</strong>','<strong>{{translate('Want_to_disable_the_Customer_App_Download_button')}}</strong>',`<p>{{translate('If_enabled,_everyone_can_see_the_button_on_the_landing_page')}}</p>`,`<p>{{translate('If_disabled,_it_will_be_hidden_from_the_landing_page')}}</p>`)"  name="react_download_apps_button_status" value="1"  {{ $react_download_apps_button_status?->value ?? null  == 1 ? 'checked': ''  }}>
                                                <span class="toggle-switch-label text mb-0">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                        </div>
                                        <input type="url" placeholder="{{translate('Ex: https://www.apple.com/app-store/')}}" class="form-control h--45px" name="react_download_apps_button_name" value="{{ $react_download_apps_button_name?->getRawOriginal('value') ?? null }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5 class="card-title mb-3">
                                    <img src="http://localhost/stackfood/public/assets/admin/img/apple.png" class="mr-2" alt="">
                                    {{ translate('messages.App_Store_Button') }}
                                </h5>
                                <div class="__bg-F8F9FC-card">
                                    <div class="form-group mb-md-0">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <label class="form-label text-capitalize m-0">
                                                {{translate('messages.Download_Link')}}
                                                <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Add_the_Customer_app_download_address_(App_Store)_where_the_button_will_redirect') }}">
                                                    <i class="tio-info-outined"></i>
                                                </span>
                                            </label>
                                        <label class="toggle-switch toggle-switch-sm m-0">
                                            <input type="checkbox" class="status toggle-switch-input"
                                            id="react_download_apps_link_status"
                                                    onclick="toogleModal(event,'react_download_apps_link_status','apple-on.png','apple-off.png',' <strong>{{translate('Want_to_enable_the_Customer_App_Download_button_here')}}</strong>','<strong>{{translate('Want_to_disable_the_Customer_App_Download_button')}}</strong>',`<p>{{translate('If_enabled,_everyone_can_see_the_button_on_the_landing_page')}}</p>`,`<p>{{translate('If_disabled,_it_will_be_hidden_from_the_landing_page')}}</p>`)" name="react_download_apps_link_status" value="1"  {{ $react_download_apps_link_data['react_download_apps_link_status'] ?? null  == 1 ? 'checked': ''  }}>
                                            <span class="toggle-switch-label text mb-0">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </div>
                                        <input type="url" placeholder="{{translate('Ex: https://www.apple.com/app-store/')}}" class="form-control h--45px" name="react_download_apps_link" value="{{ $react_download_apps_link_data['react_download_apps_link']  ?? '' }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                    <div class="btn--container justify-content-end mt-3">
                        <button type="reset" class="btn btn--reset">{{translate('Reset')}}</button>
                        <button type="submit" onclick="" class="btn btn--primary mb-2">{{translate('Save')}}</button>
                    </div>
            </div>
            </form>
        </div>
        </div>

        </div>
    <!-- Earn Money -->
    </div>
</div>

<!--  Section View -->
<div class="modal fade" id="section_view">
    <div class="modal-dialog modal-lg warning-modal">
        <div class="modal-content">
            <div class="modal-body">
                <div class="mb-3">
                    <h3 class="modal-title mb-3">{{translate(' Special Criteria')}}</h3>
                </div>
                <img src="{{asset('/public/assets/admin/img/zone-instruction.png')}}" alt="admin/img" class="w-100">
            </div>
        </div>
    </div>
</div>
<!-- How it Works -->
<div class="modal fade" id="how-it-works">
    <div class="modal-dialog status-warning-modal">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true" class="tio-clear"></span>
                </button>
            </div>
            <div class="modal-body pb-5 pt-0">
                <div class="single-item-slider owl-carousel">
                    <div class="item">
                        <div class="max-349 mx-auto mb-20 text-center">
                            <img src="{{asset('/public/assets/admin/img/landing-how.png')}}" alt="" class="mb-20">
                            <h5 class="modal-title">{{translate('Notice!')}}</h5>
                            <p>
                                {{translate("If you want to disable or turn off any section please leave that section empty, don’t make any changes there!")}}
                            </p>
                        </div>
                    </div>
                    <div class="item">
                        <div class="max-349 mx-auto mb-20 text-center">
                            <img src="{{asset('/public/assets/admin/img/notice-2.png')}}" alt="" class="mb-20">
                            <h5 class="modal-title">{{translate('If You Want to Change Language')}}</h5>
                            <p>
                                {{translate("Change the language on tab bar and input your data again!")}}
                            </p>
                        </div>
                    </div>
                    <div class="item">
                        <div class="max-349 mx-auto mb-20 text-center">
                            <img src="{{asset('/public/assets/admin/img/notice-3.png')}}" alt="" class="mb-20">
                            <h5 class="modal-title">{{translate('Let’s See The Changes!')}}</h5>
                            <p>
                                {{translate('Visit landing page to see the changes you made in the settings option!')}}
                            </p>
                            <div class="btn-wrap">
                                <button type="submit" class="btn btn--primary w-100" data-dismiss="modal">Visit Now</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center">
                    <div class="slide-counter"></div>
                </div>
            </div>
        </div>
    </div>
</div>




<form  id="remove_image_form" action="{{ route('admin.remove_image') }}" method="post">
    @csrf
    <input type="hidden" name="id" value="{{  $react_download_apps_banner_image?->id}}" >
    <input type="hidden" name="model_name" value="DataSetting" >
    <input type="hidden" name="image_path" value="react_download_apps_image" >
    <input type="hidden" name="field_name" value="value" >
</form>
<form  id="remove_image_1_form" action="{{ route('admin.remove_image') }}" method="post">
    @csrf
    <input type="hidden" name="id" value="{{  $react_download_apps_image?->id}}" >
    <input type="hidden" name="model_name" value="DataSetting" >
    <input type="hidden" name="image_path" value="react_download_apps_image" >
    <input type="hidden" name="field_name" value="value" >
</form>


@endsection

@push('script_2')
<script>
    $(".lang_link").click(function(e){
        e.preventDefault();
        $(".lang_link").removeClass('active');
        $(".lang_form").addClass('d-none');
        $(this).addClass('active');
        let form_id = this.id;
        let lang = form_id.substring(0, form_id.length - 5);
        console.log(lang);
        $("#"+lang+"-form").removeClass('d-none');
        $("#"+lang+"-form1").removeClass('d-none');
        $("#"+lang+"-form2").removeClass('d-none');
        $("#"+lang+"-form3").removeClass('d-none');
        if(lang == '{{$default_lang}}')
        {
            $(".from_part_2").removeClass('d-none');
        }
        if(lang == 'default')
        {
            $(".default-form").removeClass('d-none');
        }
        else
        {
            $(".from_part_2").addClass('d-none');
        }
    });
</script>
@endpush
