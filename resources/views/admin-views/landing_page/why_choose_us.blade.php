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
    <!-- Choose -->

    @php($default_lang = str_replace('_', '-', app()->getLocale()))
    @if($language)
    <ul class="nav nav-tabs mb-4 border-0">
        <li class="nav-item">
            <a class="nav-link lang_link active" href="#" id="default-link">{{translate('messages.default')}}</a>
        </li>
        @foreach (json_decode($language) as $lang)
        <li class="nav-item">
            <a class="nav-link lang_link" href="#" id="{{ $lang }}-link">{{ \App\CentralLogics\Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')' }}</a>
        </li>
        @endforeach
    </ul>
    @endif
    <div class="d-flex justify-content-between __gap-12px mb-3">
        <h5 class="card-title d-flex align-items-center">
            <span class="card-header-icon mr-2">
                <img src="{{asset('public/assets/admin/img/react_header.png')}}" alt="" class="mw-100">
            </span>
            {{translate('Title_&_Subtitle_Section')}}
        </h5>
        {{-- <div class="text--primary-2 py-1 d-flex flex-wrap align-items-center" type="button" data-toggle="modal" data-target="#section_view">
                        <strong class="mr-2">{{translate('Section View')}}</strong>
        <div>
            <i class="tio-intersect"></i>
        </div>
    </div> --}}
</div>
<div class="card">
    <form action="{{ route('admin.landing_page.settings', 'why-choose-us-data') }}" method="post">
        @csrf
        <div class="card-body">
            <div class="row g-3 lang_form" id="default-form">
                <input type="hidden" name="lang[]" value="default">
                <div class="col-sm-6">
                    <label class="form-label">{{translate('Title')}}
                        <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_title_within_20_characters') }}">
                            <i class="tio-info-outined"></i>
                        </span>

                    </label>
                    <input type="text" maxlength="20" name="why_choose_us_title[]" value="{{ $why_choose_us_title?->getRawOriginal('value') ?? null}}" class="form-control" placeholder="{{translate('Enter Title')}}" >
                </div>
                <div class="col-sm-6">
                    <label class="form-label">{{translate('Subtitle')}}
                        <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_subtitle_within_70_characters') }}">
                            <i class="tio-info-outined"></i>
                        </span>

                    </label>
                    <input type="text" maxlength="70" name="why_choose_us_sub_title[]" value="{{ $why_choose_us_sub_title?->getRawOriginal('value') ?? null}}" class="form-control" placeholder="{{translate('Enter Title')}}" >
                </div>
            </div>
            @if($language)
            @foreach(json_decode($language) as $lang)
            <?php
                        if($why_choose_us_title?->translations && count($why_choose_us_title?->translations)){
                                $why_choose_us_title_translate = [];
                                foreach($why_choose_us_title->translations as $t)
                                {
                                    if($t->locale == $lang && $t->key=='why_choose_us_title'){
                                        $why_choose_us_title_translate[$lang]['value'] = $t->value;
                                    }
                                }
                            }
                        if($why_choose_us_sub_title?->translations && count($why_choose_us_sub_title?->translations)){
                                $why_choose_us_sub_title_translate = [];
                                foreach($why_choose_us_sub_title->translations as $t)
                                {
                                    if($t->locale == $lang && $t->key=='why_choose_us_sub_title'){
                                        $why_choose_us_sub_title_translate[$lang]['value'] = $t->value;
                                    }
                                }
                            }
                            ?>

            <div class="row g-3 d-none lang_form" id="{{$lang}}-form">
                <input type="hidden" name="lang[]" value="{{$lang}}">
                <div class="col-sm-6">
                    <label class="form-label">{{translate('Title')}} ({{strtoupper($lang)}})
                        <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_title_within_20_characters') }}">
                            <i class="tio-info-outined"></i>
                        </span>
                    </label>
                    <input type="text" maxlength="20" name="why_choose_us_title[]" value="{{ $why_choose_us_title_translate[$lang]['value'] ?? '' }}" class="form-control" placeholder="{{translate('Enter Title')}}">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">{{translate('Subtitle')}} ({{strtoupper($lang)}})
                        <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_subtitle_within_70_characters') }}">
                            <i class="tio-info-outined"></i>
                        </span>
                    </label>
                    <input type="text" maxlength="70" name="why_choose_us_sub_title[]" value="{{ $why_choose_us_sub_title_translate[$lang]['value'] ?? '' }}" class="form-control" placeholder="{{translate('Enter Title')}}">
                </div>
            </div>
            @endforeach
            @endif
            <div class="btn--container justify-content-end mt-3">
                <button type="reset" class="btn btn--reset">{{translate('Reset')}}</button>
                <button type="submit" onclick="" class="btn btn--primary mb-2">{{translate('Save')}}</button>
            </div>
    </form>
</div>
</div>
<br>
<div class="d-flex justify-content-between __gap-12px mb-3">
    <h5 class="card-title d-flex align-items-center">
        <span class="card-header-icon mr-2"><i class="tio-settings-outlined"></i></span>

        {{translate('messages.First_image')}}
    </h5>
    {{-- <div class="text--primary-2 py-1 d-flex flex-wrap align-items-center" type="button" data-toggle="modal" data-target="#section_view">
                        <strong class="mr-2">{{translate('Section View')}}</strong>
    <div>
        <i class="tio-intersect"></i>
    </div>
</div> --}}
</div>
<div class="card">
    <form action="{{ route('admin.landing_page.settings', 'why-choose-us-data-1') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="max-w-555px">
                        <div class="form-group lang_form default-form">
                            <label class="form-label">{{translate('Title')}} <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_title_within_30_characters') }}">
                                    <i class="tio-info-outined"></i>
                                </span> </label>
                            <input type="text" name="title[]" maxlength="30" class="form-control" placeholder="{{translate('Enter Title')}}" value="{{ $data_1?->getRawOriginal('value') ?? ''}}" >
                            <input type="hidden" name="lang[]" value="default">
                        </div>
                        <input type="hidden" value="why_choose_us_title_1" name="key">

                        <input type="hidden" value="why_choose_us_image_1" name="key_image">

                        @if($language)
                        @forelse(json_decode($language) as $lang)
                        <input type="hidden" name="lang[]" value="{{$lang}}">
                        <?php
                                    if($data_1?->translations && count($data_1?->translations)){
                                            $why_choose_us_title_1_translate = [];
                                            foreach($data_1->translations as $t)
                                            {
                                                if($t->locale == $lang && $t->key=='why_choose_us_title_1'){
                                                    $why_choose_us_title_1_translate[$lang]['value'] = $t->value;
                                                }
                                            }
                                        }
                                        ?>

                        <div class="form-group d-none lang_form" id="{{$lang}}-form1">
                            <label class="form-label">{{translate('Title')}} ({{strtoupper($lang)}}) <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_title_within_30_characters') }}">
                                    <i class="tio-info-outined"></i>
                                </span></label>
                            <input type="text" name="title[]" maxlength="30" class="form-control" placeholder="{{translate('Enter Title')}}" value="{{ $why_choose_us_title_1_translate[$lang]['value'] ?? '' }}">
                        </div>
                        @empty
                        @endforelse
                        @endif
                        <div class="d-flex flex-column">
                            <label class="form-label d-block mb-2">
                                {{translate('messages.Section_Background_Image')}} <span class="text--primary">(1600x1700px)</span>
                            </label>
                            <div class="position-relative">

                                <label class="upload-img-3 m-0 d-block my-auto">
                                    <div class="img">
                                        <img src="{{asset('storage/app/public/why_choose_us_image')}}/{{$data_1_image?->value}}" onerror="this.src='{{asset("/public/assets/admin/img/upload-5.png")}}'" class="vertical-img max-w-555px" alt="">
                                    </div>
                                    <input type="file" name="image" hidden="">
                                </label>
                                @if ($data_1_image?->value)
                                <span id="remove_image_1" class="remove_image_button" onclick="toogleStatusModal(event,'remove_image_1','mail-success','mail-warninh','{{translate('Important!')}}','{{translate('Warning!')}}',`<p>{{translate('Are_you_sure_you_want_to_remove_this_image')}}</p>`,`<p>{{translate('Are_you_sure_you_want_to_remove_this_image.')}}</p>`)"> <i class="tio-clear"></i></span>
                                @endif
                            </div>
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
<br>
<div class="d-flex justify-content-between __gap-12px mb-3">
    <h5 class="card-title d-flex align-items-center">
        <span class="card-header-icon mr-2"><i class="tio-settings-outlined"></i></span>
        {{translate('messages.Second_image')}}
    </h5>
    {{-- <div class="text--primary-2 py-1 d-flex flex-wrap align-items-center" type="button" data-toggle="modal" data-target="#section_view">
                        <strong class="mr-2">{{translate('Section View')}}</strong>
    <div>
        <i class="tio-intersect"></i>
    </div>
</div> --}}
</div>
<div class="card">
    <form action="{{ route('admin.landing_page.settings', 'why-choose-us-data-1') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="max-w-555px">
                        <div class="form-group lang_form default-form">
                            <label class="form-label">{{translate('Title')}} <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_title_within_30_characters') }}">
                                    <i class="tio-info-outined"></i>
                                </span> </label>
                            <input type="text" name="title[]" maxlength="30" class="form-control" placeholder="{{translate('Enter Title')}}" value="{{ $data_2?->getRawOriginal('value') ?? ''}}" >
                            <input type="hidden" name="lang[]" value="default">
                        </div>
                        <input type="hidden" value="why_choose_us_title_2" name="key">

                        <input type="hidden" value="why_choose_us_image_2" name="key_image">

                        @if($language)
                        @forelse(json_decode($language) as $lang)
                        <input type="hidden" name="lang[]" value="{{$lang}}">

                        <?php
                                    if($data_2?->translations && count($data_2?->translations)){
                                            $why_choose_us_title_2_translate = [];
                                            foreach($data_2->translations as $t)
                                            {
                                                if($t->locale == $lang && $t->key=='why_choose_us_title_2'){
                                                    $why_choose_us_title_2_translate[$lang]['value'] = $t->value;
                                                }
                                            }
                                        }
                                        ?>

                        <div class="form-group d-none lang_form" id="{{$lang}}-form2">
                            <label class="form-label">{{translate('Title')}} ({{strtoupper($lang)}}) <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_title_within_30_characters') }}">
                                    <i class="tio-info-outined"></i>
                                </span></label>
                            <input type="text" name="title[]" maxlength="30" class="form-control" placeholder="{{translate('Enter Title')}}" value="{{ $why_choose_us_title_2_translate[$lang]['value'] ?? '' }}">
                        </div>
                        @empty
                        @endforelse
                        @endif
                        <div class="d-flex flex-column">
                            <label class="form-label d-block mb-2">
                                {{translate('messages.Section_Background_Image')}} <span class="text--primary">(1600x1700px)</span>
                            </label>
                            <div class="position-relative">

                                <label class="upload-img-3 m-0 d-block my-auto">
                                    <div class="img">
                                        <img src="{{asset('storage/app/public/why_choose_us_image')}}/{{$data_2_image?->value}}" onerror="this.src='{{asset("/public/assets/admin/img/upload-5.png")}}'" class="vertical-img max-w-555px" alt="">
                                    </div>
                                    <input type="file" name="image" hidden="">
                                </label>
                                @if ($data_2_image?->value)
                                <span id="remove_image_2" class="remove_image_button" onclick="toogleStatusModal(event,'remove_image_2','mail-success','mail-warninh','{{translate('Important!')}}','{{translate('Warning!')}}',`<p>{{translate('Are_you_sure_you_want_to_remove_this_image')}}</p>`,`<p>{{translate('Are_you_sure_you_want_to_remove_this_image.')}}</p>`)"> <i class="tio-clear"></i></span>
                                @endif
                            </div>
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
<br>
<div class="d-flex justify-content-between __gap-12px mb-3">
    <h5 class="card-title d-flex align-items-center">
        <span class="card-header-icon mr-2"><i class="tio-settings-outlined"></i></span>

        {{translate('messages.Third_image')}}
    </h5>
    {{-- <div class="text--primary-2 py-1 d-flex flex-wrap align-items-center" type="button" data-toggle="modal" data-target="#section_view">
                        <strong class="mr-2">{{translate('Section View')}}</strong>
    <div>
        <i class="tio-intersect"></i>
    </div>
</div> --}}
</div>
<div class="card">
    <form action="{{ route('admin.landing_page.settings', 'why-choose-us-data-1') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="max-w-555px">
                        <div class="form-group lang_form default-form">
                            <label class="form-label">{{translate('Title')}} <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_title_within_30_characters') }}">
                                    <i class="tio-info-outined"></i>
                                </span> </label>
                            <input type="text" name="title[]" maxlength="30" class="form-control" placeholder="{{translate('Enter Title')}}" value="{{ $data_3?->getRawOriginal('value')?? ''}}" >
                            <input type="hidden" name="lang[]" value="default">
                        </div>
                        <input type="hidden" value="why_choose_us_title_3" name="key">

                        <input type="hidden" value="why_choose_us_image_3" name="key_image">

                        @if($language)
                        @forelse(json_decode($language) as $lang)
                        <input type="hidden" name="lang[]" value="{{$lang}}">
                        <?php
                                    if($data_3?->translations && count($data_3?->translations)){
                                            $why_choose_us_title_3_translate = [];
                                            foreach($data_3->translations as $t)
                                            {
                                                if($t->locale == $lang && $t->key=='why_choose_us_title_3'){
                                                    $why_choose_us_title_3_translate[$lang]['value'] = $t->value;
                                                }
                                            }
                                        }
                                        ?>

                        <div class="form-group d-none lang_form" id="{{$lang}}-form3">
                            <label class="form-label">{{translate('Title')}} ({{strtoupper($lang)}}) <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_title_within_30_characters') }}">
                                    <i class="tio-info-outined"></i>
                                </span></label>
                            <input type="text" name="title[]" maxlength="30" class="form-control" placeholder="{{translate('Enter Title')}}" value="{{ $why_choose_us_title_3_translate[$lang]['value'] ?? '' }}">
                        </div>
                        @empty
                        @endforelse
                        @endif

                        <div class="d-flex flex-column">
                            <label class="form-label d-block mb-3">
                                {{translate('messages.Section_Background_Image')}} <span class="text--primary">(1600x1700px)</span>
                            </label>
                            <div class="position-relative">

                                <label class="upload-img-3 m-0 d-block my-auto">
                                    <div class="img">
                                        <img src="{{asset('storage/app/public/why_choose_us_image')}}/{{$data_3_image?->value}}" onerror="this.src='{{asset("/public/assets/admin/img/upload-5.png")}}'" class="vertical-img max-w-555px" alt="">
                                    </div>
                                    <input type="file" name="image" hidden="">
                                </label>
                                @if ($data_3_image?->value)
                                <span id="remove_image_3" class="remove_image_button" onclick="toogleStatusModal(event,'remove_image_3','mail-success','mail-warninh','{{translate('Important!')}}','{{translate('Warning!')}}',`<p>{{translate('Are_you_sure_you_want_to_remove_this_image')}}</p>`,`<p>{{translate('Are_you_sure_you_want_to_remove_this_image.')}}</p>`)"> <i class="tio-clear"></i></span>
                                @endif
                            </div>
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
<br>
<div class="d-flex justify-content-between __gap-12px mb-3">
    <h5 class="card-title d-flex align-items-center">
        <span class="card-header-icon mr-2"><i class="tio-settings-outlined"></i></span>

        {{translate('messages.Forth_image')}}
    </h5>
    {{-- <div class="text--primary-2 py-1 d-flex flex-wrap align-items-center" type="button" data-toggle="modal" data-target="#section_view">
                        <strong class="mr-2">{{translate('Section View')}}</strong>
    <div>
        <i class="tio-intersect"></i>
    </div>
</div> --}}
</div>
<div class="card">
    <form action="{{ route('admin.landing_page.settings', 'why-choose-us-data-1') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="max-w-555px">
                        <div class="form-group lang_form default-form">
                            <label class="form-label">{{translate('Title')}} <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_title_within_30_characters') }}">
                                    <i class="tio-info-outined"></i>
                                </span> </label>
                            <input type="text" name="title[]" maxlength="30" class="form-control" placeholder="{{translate('Enter Title')}}" value="{{ $data_4?->getRawOriginal('value') ?? ''}}" >
                            <input type="hidden" name="lang[]" value="default">
                        </div>
                        <input type="hidden" value="why_choose_us_title_4" name="key">

                        <input type="hidden" value="why_choose_us_image_4" name="key_image">

                        @if($language)
                        @forelse(json_decode($language) as $lang)
                        <input type="hidden" name="lang[]" value="{{$lang}}">
                        {{-- {{ dd($data_4?->translations) }} --}}
                        <?php
                                    if($data_4?->translations && count($data_4?->translations)){
                                            $why_choose_us_title_4_translate = [];
                                            foreach($data_4->translations as $t)
                                            {
                                                if($t->locale == $lang && $t->key=='why_choose_us_title_4'){
                                                    $why_choose_us_title_4_translate[$lang]['value'] = $t->value;
                                                }
                                            }
                                        }
                                        ?>

                        <div class="form-group d-none lang_form" id="{{$lang}}-form4">
                            <label class="form-label">{{translate('Title')}} ({{strtoupper($lang)}}) <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_title_within_30_characters') }}">
                                    <i class="tio-info-outined"></i>
                                </span></label>
                            <input type="text" name="title[]" maxlength="30" class="form-control" placeholder="{{translate('Enter Title')}}" value="{{ $why_choose_us_title_4_translate[$lang]['value'] ?? '' }}">
                        </div>
                        @empty
                        @endforelse
                        @endif
                        <div class="d-flex flex-column">
                            <label class="form-label d-block mb-3">
                                {{translate('messages.Section_Background_Image')}} <span class="text--primary">(1600x1700px)</span>
                            </label>
                            <div class="position-relative">

                                <label class="upload-img-3 m-0 d-block my-auto">
                                    <div class="img">
                                        <img src="{{asset('storage/app/public/why_choose_us_image')}}/{{$data_4_image?->value}}" onerror="this.src='{{asset("/public/assets/admin/img/upload-5.png")}}'" class="vertical-img max-w-555px" alt="">
                                    </div>
                                    <input type="file" name="image" hidden="">
                                </label>
                                @if ($data_4_image?->value)
                                <span id="remove_image_4" class="remove_image_button" onclick="toogleStatusModal(event,'remove_image_4','mail-success','mail-warninh','{{translate('Important!')}}','{{translate('Warning!')}}',`<p>{{translate('Are_you_sure_you_want_to_remove_this_image')}}</p>`,`<p>{{translate('Are_you_sure_you_want_to_remove_this_image.')}}</p>`)"> <i class="tio-clear"></i></span>
                                @endif
                            </div>
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

<!-- Choose -->
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

</div>

<form id="remove_image_1_form" action="{{ route('admin.remove_image') }}" method="post">
    @csrf
    <input type="hidden" name="id" value="{{  $data_1_image?->id}}">
    <input type="hidden" name="model_name" value="DataSetting">
    <input type="hidden" name="image_path" value="why_choose_us_image">
    <input type="hidden" name="field_name" value="value">
</form>
<form id="remove_image_2_form" action="{{ route('admin.remove_image') }}" method="post">
    @csrf
    <input type="hidden" name="id" value="{{  $data_2_image?->id}}">
    <input type="hidden" name="model_name" value="DataSetting">
    <input type="hidden" name="image_path" value="why_choose_us_image">
    <input type="hidden" name="field_name" value="value">
</form>
<form id="remove_image_3_form" action="{{ route('admin.remove_image') }}" method="post">
    @csrf
    <input type="hidden" name="id" value="{{  $data_3_image?->id}}">
    <input type="hidden" name="model_name" value="DataSetting">
    <input type="hidden" name="image_path" value="why_choose_us_image">
    <input type="hidden" name="field_name" value="value">
</form>
<form id="remove_image_4_form" action="{{ route('admin.remove_image') }}" method="post">
    @csrf
    <input type="hidden" name="id" value="{{  $data_4_image?->id}}">
    <input type="hidden" name="model_name" value="DataSetting">
    <input type="hidden" name="image_path" value="why_choose_us_image">
    <input type="hidden" name="field_name" value="value">
</form>
@endsection

@push('script_2')
<script>
    $(".lang_link").click(function(e) {
        e.preventDefault();
        $(".lang_link").removeClass('active');
        $(".lang_form").addClass('d-none');
        $(this).addClass('active');
        let form_id = this.id;
        let lang = form_id.substring(0, form_id.length - 5);
        console.log(lang);
        $("#" + lang + "-form").removeClass('d-none');
        $("#" + lang + "-form1").removeClass('d-none');
        $("#" + lang + "-form2").removeClass('d-none');
        $("#" + lang + "-form3").removeClass('d-none');
        $("#" + lang + "-form4").removeClass('d-none');
        if (lang == '{{$default_lang}}') {
            $(".from_part_2").removeClass('d-none');
        }
        if (lang == 'default') {
            $(".default-form").removeClass('d-none');
        } else {
            $(".from_part_2").addClass('d-none');
        }
    });

</script>

@endpush
