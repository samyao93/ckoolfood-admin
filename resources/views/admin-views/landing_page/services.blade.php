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
    <form action="{{ route('admin.landing_page.settings', 'services-data') }}" method="post">
        @csrf
        <div class="card-body">
            <div class="row g-3 lang_form default-form" id="default-form">
                <input type="hidden" name="lang[]" value="default">
                <div class="col-sm-6">
                    <label class="form-label">{{translate('Title')}}
                        <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_title_within_20_characters') }}">
                            <i class="tio-info-outined"></i>
                        </span>
                    </label>
                    <input type="text" maxlength="20" name="services_title[]" value="{{ $services_title?->getRawOriginal('value') ?? null}}" class="form-control" placeholder="{{translate('Enter Title')}}">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">{{translate('Subtitle')}}
                        <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_subtitle_within_70_characters') }}">
                            <i class="tio-info-outined"></i>
                        </span>
                    </label>
                    <input type="text" maxlength="70" name="services_sub_title[]" value="{{ $services_sub_title?->getRawOriginal('value') ?? null}}" class="form-control" placeholder="{{translate('Enter Title')}}">
                </div>
            </div>

            @forelse(json_decode($language) as $lang)
            <?php
                    if($services_title?->translations){
                            $services_title_translate = [];
                            foreach($services_title->translations as $t)
                            {
                                if($t->locale == $lang && $t->key=='services_title'){
                                    $services_title_translate[$lang]['value'] = $t->value;
                                }
                            }
                        }
                    if($services_sub_title?->translations){
                            $services_sub_title_translate = [];
                            foreach($services_sub_title->translations as $t)
                            {
                                if($t->locale == $lang && $t->key=='services_sub_title'){
                                    $services_sub_title_translate[$lang]['value'] = $t->value;
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
                    <input type="text" maxlength="20" name="services_title[]" value="{{ $services_title_translate[$lang]['value'] ?? '' }}" class="form-control" placeholder="{{translate('Enter Title')}}">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">{{translate('Subtitle')}} ({{strtoupper($lang)}})
                        <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_subtitle_within_70_characters') }}">
                            <i class="tio-info-outined"></i>
                        </span>
                    </label>
                    <input type="text" maxlength="70" name="services_sub_title[]" value="{{ $services_sub_title_translate[$lang]['value'] ?? '' }}" class="form-control" placeholder="{{translate('Enter Title')}}">
                </div>
            </div>
            @empty
            @endforelse

            <div class="btn--container justify-content-end mt-3">
                <button type="reset" class="btn btn--reset">{{translate('Reset')}}</button>
                <button type="submit" onclick="" class="btn btn--primary mb-2">{{translate('Save')}}</button>
            </div>
    </form>
</div>
</div>
<br>
<br>


<div class="d-flex justify-content-between __gap-12px mb-3">
    <h5 class="card-title d-flex align-items-center">
        <span class="card-header-icon mr-2"><i class="tio-settings-outlined"></i></span>
        {{translate('Order_Food_Tab')}}
    </h5>
    {{-- <div class="text--primary-2 py-1 d-flex flex-wrap align-items-center" type="button" data-toggle="modal" data-target="#section_view">
                    <strong class="mr-2">{{translate('Section View')}}</strong>
    <div>
        <i class="tio-intersect"></i>
    </div>
</div> --}}
</div>


<div class="card">
    <form action="{{ route('admin.landing_page.settings', 'services-order-data') }}" method="post">
        @csrf
        <div class="card-body ">
            <div class="lang_form default-form">
                <input type="hidden" name="lang[]" value="default">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <label class="form-label">{{translate('Title_1')}}
                            <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_title_within_20_characters') }}">
                                <i class="tio-info-outined"></i>
                            </span>

                        </label>
                        <input type="text" maxlength="20" class="form-control" placeholder="{{translate('Enter Title')}}" name="services_order_title_1[]" value="{{ $services_order_title_1?->getRawOriginal('value') ?? '' }}">
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">{{translate('Title_2')}}
                            <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_title_within_20_characters') }}">
                                <i class="tio-info-outined"></i>
                            </span>

                        </label>
                        <input type="text" class="form-control" placeholder="{{translate('Enter Title')}}" maxlength="20" name="services_order_title_2[]" value="{{ $services_order_title_2?->getRawOriginal('value') ?? '' }}">
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">{{translate('Short Description_1')}}

                            <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_short_description_within_120_characters') }}">
                                <i class="tio-info-outined"></i>
                            </span>
                        </label>
                        <input type="text" class="form-control" maxlength="120" placeholder="{{translate('Enter_Short_Description')}}" name="services_order_description_1[]" value="{{ $services_order_description_1?->getRawOriginal('value') ?? '' }}">
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">{{translate('Short Description_2')}}

                            <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_short_description_within_120_characters') }}">
                                <i class="tio-info-outined"></i>
                            </span>
                        </label>
                        <input type="text" class="form-control" maxlength="120" placeholder="{{translate('Enter_Short_Description')}}" name="services_order_description_2[]" value="{{ $services_order_description_2?->getRawOriginal('value') ?? '' }}">
                    </div>
                </div>
                <br>
                <label class="form-label">
                    {{ translate('Button_Content') }}
                </label>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="__bg-F8F9FC-card">
                            <div class="form-group mb-md-0">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-label text-capitalize m-0">
                                        {{translate('Button Name')}}
                                        <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{translate('Write_the_button_name_within_15_characters')}}">
                                            <i class="tio-info-outined"></i>
                                        </span>
                                    </label>

                                </div>
                                <input type="text" maxlength="15" placeholder="{{translate('Ex: Order now')}}" class="form-control h--45px" name="services_order_button_name[]" value="{{ $services_order_button_name?->getRawOriginal('value')  ?? null }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="__bg-F8F9FC-card">
                            <div class="form-group mb-md-0">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-label text-capitalize m-0">
                                        {{translate('Redirect Link')}}
                                        <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{translate('Add_the_link/address_where_the_button_will_redirect.')}}">
                                            <i class="tio-info-outined"></i>
                                        </span>
                                    </label>
                                    <label class="toggle-switch toggle-switch-sm m-0">
                                        <input type="checkbox" class="status toggle-switch-input" id="services_order_button_status" onclick="toogleModal(event,'services_order_button_status','mail-success.png','mail-warning.png',' <strong>{{translate('Want_to_enable_the_Order_Food_tab_button_here')}}</strong>','<strong>{{translate('Want_to_disable_the_Order_Food_tab_button_here')}}</strong>',`<p>{{translate('If_enabled,_everyone_can_see_the_Order_Food_tab_button_on_the_landing_page')}}</p>`,`<p>{{translate('If_disabled,_Order_Food_tab_button_will_be_hidden_from_the_landing_page')}}</p>`)" name="services_order_button_status" value="1" {{ $services_order_button_status?->value ?? null  == 1 ? 'checked': ''  }}>
                                        <span class="toggle-switch-label text mb-0">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                    </label>
                                </div>
                                <input type="url" placeholder="{{translate('Ex: https://www.apple.com/app-store/')}}" class="form-control h--45px" name="services_order_button_link" value="{{ $services_order_button_link  ?? '' }} ">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @forelse(json_decode($language) as $lang)
            <?php
                        if($services_order_title_1?->translations){
                                $services_order_title_1_translate = [];
                                foreach($services_order_title_1->translations as $t)
                                {
                                    if($t->locale == $lang && $t->key=='services_order_title_1'){
                                        $services_order_title_1_translate[$lang]['value'] = $t->value;
                                    }
                                }
                            }
                        if($services_order_title_2?->translations){
                                $services_order_title_2_translate = [];
                                foreach($services_order_title_2->translations as $t)
                                {
                                    if($t->locale == $lang && $t->key=='services_order_title_2'){
                                        $services_order_title_2_translate[$lang]['value'] = $t->value;
                                    }
                                }
                            }
                        if($services_order_description_1?->translations){
                                $services_order_description_1_translate = [];
                                foreach($services_order_description_1->translations as $t)
                                {
                                    if($t->locale == $lang && $t->key=='services_order_description_1'){
                                        $services_order_description_1_translate[$lang]['value'] = $t->value;
                                    }
                                }
                            }
                        if($services_order_description_2?->translations){
                                $services_order_description_2_translate = [];
                                foreach($services_order_description_2->translations as $t)
                                {
                                    if($t->locale == $lang && $t->key=='services_order_description_2'){
                                        $services_order_description_2_translate[$lang]['value'] = $t->value;
                                    }
                                }
                            }
                        if($services_order_button_name?->translations){
                            $services_order_button_name_translate = [];
                            foreach($services_order_button_name->translations as $t)
                            {
                                if($t->locale == $lang && $t->key=='services_order_button_name'){
                                    $services_order_button_name_translate[$lang]['value'] = $t->value;
                                }
                            }
                        }
                    ?>
            <input type="hidden" name="lang[]" value="{{$lang}}">

            <div class="d-none lang_form" id="{{$lang}}-form1">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <label class="form-label">{{translate('Title_1')}} ({{strtoupper($lang)}})
                            <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_title_within_20_characters') }}">
                                <i class="tio-info-outined"></i>
                            </span>
                        </label>
                        <input type="text" maxlength="20" class="form-control" placeholder="{{translate('Enter Title')}}" name="services_order_title_1[]" value="{{ $services_order_title_1_translate[$lang]['value'] ?? '' }}">
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">{{translate('Title_2')}} ({{strtoupper($lang)}})
                            <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_title_within_20_characters') }}">
                                <i class="tio-info-outined"></i>
                            </span>
                        </label>
                        <input type="text" class="form-control" placeholder="{{translate('Enter Title')}}" maxlength="20" name="services_order_title_2[]" value="{{ $services_order_title_2_translate[$lang]['value'] ?? '' }}">
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">{{translate('Short Description_1')}} ({{strtoupper($lang)}})
                            <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_short_description_within_120_characters') }}">
                                <i class="tio-info-outined"></i>
                            </span>
                        </label>
                        <input type="text" maxlength="120" class="form-control" placeholder="{{translate('Enter_Short_Description')}}" name="services_order_description_1[]" value="{{ $services_order_description_1_translate[$lang]['value']  ?? '' }}">
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">{{translate('Short Description_2')}} ({{strtoupper($lang)}})
                            <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_short_description_within_120_characters') }}">
                                <i class="tio-info-outined"></i>
                            </span>
                        </label>
                        <input type="text" maxlength="120" class="form-control" placeholder="{{translate('Enter_Short_Description')}}" name="services_order_description_2[]" value="{{  $services_order_description_2_translate[$lang]['value'] ?? '' }}">
                    </div>
                </div>
                <br>
                <label class="form-label">
                    {{ translate('Button_Content') }}
                </label>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="__bg-F8F9FC-card">
                            <div class="form-group mb-md-0">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-label text-capitalize m-0">
                                        {{translate('Button Name')}} ({{strtoupper($lang)}})

                                        <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{translate('Write_the_button_name_within_15_characters')}}">
                                            <i class="tio-info-outined"></i>
                                        </span>
                                    </label>
                                </div>
                                <input type="text" maxlength="15" placeholder="{{translate('Ex: Order now')}}" class="form-control h--45px" name="services_order_button_name[]" value="{{ $services_order_button_name_translate[$lang]['value'] ?? null }}">
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            @empty
            @endforelse


            <div class="btn--container justify-content-end mt-3">
                <button type="reset" class="btn btn--reset">{{translate('Reset')}}</button>
                <button type="submit" onclick="" class="btn btn--primary mb-2">{{translate('Save')}}</button>
            </div>
        </div>
    </form>
</div>
<br>
<br>
<div class="d-flex justify-content-between __gap-12px mb-3">
    <h5 class="card-title d-flex align-items-center">
        <span class="card-header-icon mr-2"><i class="tio-settings-outlined"></i></span>
        {{translate('Manage_Restaurant_Tab')}}
    </h5>
    {{-- <div class="text--primary-2 py-1 d-flex flex-wrap align-items-center" type="button" data-toggle="modal" data-target="#section_view">
                    <strong class="mr-2">{{translate('Section View')}}</strong>
    <div>
        <i class="tio-intersect"></i>
    </div>
</div> --}}
</div>
<div class="card">
    <form action="{{ route('admin.landing_page.settings', 'services-manage-restaurant-data') }}" method="post">
        @csrf
        <div class="card-body ">
            <div class="lang_form default-form">
                <input type="hidden" name="lang[]" value="default">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <label class="form-label">{{translate('Title_1')}}
                            <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_title_within_20_characters') }}">
                                <i class="tio-info-outined"></i>
                            </span>
                        </label>
                        <input type="text" maxlength="20" class="form-control" placeholder="{{translate('Enter Title')}}" name="services_manage_restaurant_title_1[]" value="{{ $services_manage_restaurant_title_1?->getRawOriginal('value')  ?? '' }}">
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">{{translate('Title_2')}}
                            <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_title_within_20_characters') }}">
                                <i class="tio-info-outined"></i>
                            </span>
                        </label>
                        <input type="text" maxlength="20" class="form-control" placeholder="{{translate('Enter Title')}}" name="services_manage_restaurant_title_2[]" value="{{ $services_manage_restaurant_title_2?->getRawOriginal('value')  ?? '' }}">
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">{{translate('Short Description_1')}}
                            <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_short_description_within_120_characters') }}">
                                <i class="tio-info-outined"></i>
                            </span>
                        </label>
                        <input type="text" maxlength="120" class="form-control" placeholder="{{translate('Enter_Short_Description')}}" name="services_manage_restaurant_description_1[]" value="{{ $services_manage_restaurant_description_1?->getRawOriginal('value')  ?? '' }}">
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">{{translate('Short Description_2')}}
                            <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_short_description_within_120_characters') }}">
                                <i class="tio-info-outined"></i>
                            </span>
                        </label>
                        <input type="text" maxlength="120" class="form-control" placeholder="{{translate('Enter_Short_Description')}}" name="services_manage_restaurant_description_2[]" value="{{ $services_manage_restaurant_description_2?->getRawOriginal('value')  ?? '' }}">
                    </div>
                </div>
                <br>
                <label class="form-label">
                    {{ translate('Button_Content') }}
                </label>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="__bg-F8F9FC-card">
                            <div class="form-group mb-md-0">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-label text-capitalize m-0">
                                        {{translate('Button Name')}}

                                        <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{translate('Write_the_button_name_within_15_characters')}}">
                                            <i class="tio-info-outined"></i>
                                        </span>

                                    </label>

                                </div>
                                <input type="text" maxlength="15" placeholder="{{translate('Ex: Order now')}}" class="form-control h--45px" name="services_manage_restaurant_button_name[]" value="{{ $services_manage_restaurant_button_name?->getRawOriginal('value')  ?? null }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="__bg-F8F9FC-card">
                            <div class="form-group mb-md-0">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-label text-capitalize m-0">
                                        {{translate('Redirect Link')}}

                                        <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{translate('Add_the_link/address_where_the_button_will_redirect.')}}">
                                            <i class="tio-info-outined"></i>
                                        </span>

                                    </label>
                                    <label class="toggle-switch toggle-switch-sm m-0">
                                        <input type="checkbox" class="status toggle-switch-input" id="services_manage_restaurant_button_status" onclick="toogleModal(event,'services_manage_restaurant_button_status','mail-success.png','mail-warning.png',' <strong>{{translate('Want_to_enable_the_Manage_Restaurant_tab_button_here')}}</strong>','<strong>{{translate('Want_to_disable_the_Manage_Restaurant_tab_button_here')}}</strong>',`<p>{{translate('If_enabled,_everyone_can_see_the_Manage_Restaurant_tab_button_on_the_landing_page')}}</p>`,`<p>{{translate('If_disabled,_Manage_Restaurant_tab_button_will_be_hidden_from_the_landing_page')}}</p>`)" name="services_manage_restaurant_button_status" value="1" {{ $services_manage_restaurant_button_status?->value ?? null  == 1 ? 'checked': ''  }}>
                                        <span class="toggle-switch-label text mb-0">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                    </label>
                                </div>
                                <input type="url" placeholder="{{translate('Ex: https://www.apple.com/app-store/')}}" class="form-control h--45px" name="services_manage_restaurant_button_link" value="{{ $services_manage_restaurant_button_link  ?? '' }} ">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @forelse(json_decode($language) as $lang)
            <?php
                        if($services_manage_restaurant_title_1?->translations){
                                $services_manage_restaurant_title_1_translate = [];
                                foreach($services_manage_restaurant_title_1->translations as $t)
                                {
                                    if($t->locale == $lang && $t->key=='services_manage_restaurant_title_1'){
                                        $services_manage_restaurant_title_1_translate[$lang]['value'] = $t->value;
                                    }
                                }
                            }
                        if($services_manage_restaurant_title_2?->translations){
                                $services_manage_restaurant_title_2_translate = [];
                                foreach($services_manage_restaurant_title_2->translations as $t)
                                {
                                    if($t->locale == $lang && $t->key=='services_manage_restaurant_title_2'){
                                        $services_manage_restaurant_title_2_translate[$lang]['value'] = $t->value;
                                    }
                                }
                            }
                        if($services_manage_restaurant_description_1?->translations){
                                $services_manage_restaurant_description_1_translate = [];
                                foreach($services_manage_restaurant_description_1->translations as $t)
                                {
                                    if($t->locale == $lang && $t->key=='services_manage_restaurant_description_1'){
                                        $services_manage_restaurant_description_1_translate[$lang]['value'] = $t->value;
                                    }
                                }
                            }
                        if($services_manage_restaurant_description_2?->translations){
                                $services_manage_restaurant_description_2_translate = [];
                                foreach($services_manage_restaurant_description_2->translations as $t)
                                {
                                    if($t->locale == $lang && $t->key=='services_manage_restaurant_description_2'){
                                        $services_manage_restaurant_description_2_translate[$lang]['value'] = $t->value;
                                    }
                                }
                            }
                        if($services_manage_restaurant_button_name?->translations){
                            $services_manage_restaurant_button_name_translate = [];
                            foreach($services_manage_restaurant_button_name->translations as $t)
                            {
                                if($t->locale == $lang && $t->key=='services_manage_restaurant_button_name'){
                                    $services_manage_restaurant_button_name_translate[$lang]['value'] = $t->value;
                                }
                            }
                        }
                    ?>
            <input type="hidden" name="lang[]" value="{{$lang}}">

            <div class="d-none lang_form" id="{{$lang}}-form2">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <label class="form-label">{{translate('Title_1')}} ({{strtoupper($lang)}})
                            <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_title_within_20_characters') }}">
                                <i class="tio-info-outined"></i>
                            </span>

                        </label>
                        <input type="text" maxlength="20" class="form-control" placeholder="{{translate('Enter Title')}}" name="services_manage_restaurant_title_1[]" value="{{ $services_manage_restaurant_title_1_translate[$lang]['value'] ?? '' }}">
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">{{translate('Title_2')}} ({{strtoupper($lang)}})
                            <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_title_within_20_characters') }}">
                                <i class="tio-info-outined"></i>
                            </span>

                        </label>
                        <input type="text" maxlength="20" class="form-control" placeholder="{{translate('Enter Title')}}" name="services_manage_restaurant_title_2[]" value="{{ $services_manage_restaurant_title_2_translate[$lang]['value'] ?? '' }}">
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">{{translate('Short Description_1')}} ({{strtoupper($lang)}})
                            <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_short_description_within_120_characters') }}">
                                <i class="tio-info-outined"></i>
                            </span>

                        </label>
                        <input type="text" maxlength="120" class="form-control" placeholder="{{translate('Enter_Short_Description')}}" name="services_manage_restaurant_description_1[]" value="{{ $services_manage_restaurant_description_1_translate[$lang]['value']  ?? '' }}">
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">{{translate('Short Description_2')}} ({{strtoupper($lang)}})
                            <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_short_description_within_120_characters') }}">
                                <i class="tio-info-outined"></i>
                            </span>

                        </label>
                        <input type="text" maxlength="120" class="form-control" placeholder="{{translate('Enter_Short_Description')}}" name="services_manage_restaurant_description_2[]" value="{{  $services_manage_restaurant_description_2_translate[$lang]['value'] ?? '' }}">
                    </div>
                </div>
                <br>
                <label class="form-label">
                    {{ translate('Button_Content') }}
                </label>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="__bg-F8F9FC-card">
                            <div class="form-group mb-md-0">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-label text-capitalize m-0">
                                        {{translate('Button Name')}} ({{strtoupper($lang)}})
                                        <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{translate('Write_the_button_name_within_15_characters')}}">
                                            <i class="tio-info-outined"></i>
                                        </span>
                                    </label>
                                </div>
                                <input type="text" maxlength="15" placeholder="{{translate('Ex: Order now')}}" class="form-control h--45px" name="services_manage_restaurant_button_name[]" value="{{ $services_manage_restaurant_button_name_translate[$lang]['value'] ?? null }}">
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            @empty
            @endforelse


            <div class="btn--container justify-content-end mt-3">
                <button type="reset" class="btn btn--reset">{{translate('Reset')}}</button>
                <button type="submit" onclick="" class="btn btn--primary mb-2">{{translate('Save')}}</button>
            </div>
        </div>
    </form>
</div>
<br>
<br>
<div class="d-flex justify-content-between __gap-12px mb-3">
    <h5 class="card-title d-flex align-items-center">
        <span class="card-header-icon mr-2"><i class="tio-settings-outlined"></i></span>
        {{translate('messages.Manage_Delivery_Tab')}}
    </h5>
    {{-- <div class="text--primary-2 py-1 d-flex flex-wrap align-items-center" type="button" data-toggle="modal" data-target="#section_view">
                    <strong class="mr-2">{{translate('Section View')}}</strong>
    <div>
        <i class="tio-intersect"></i>
    </div>
</div> --}}
</div>
<div class="card">
    <form action="{{ route('admin.landing_page.settings', 'services-manage-delivery-data') }}" method="post">
        @csrf
        <div class="card-body ">
            <div class="lang_form default-form">
                <input type="hidden" name="lang[]" value="default">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <label class="form-label">{{translate('Title_1')}}
                            <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_title_within_20_characters') }}">
                                <i class="tio-info-outined"></i>
                            </span>

                        </label>
                        <input type="text" maxlength="20" class="form-control" placeholder="{{translate('Enter Title')}}" name="services_manage_delivery_title_1[]" value="{{ $services_manage_delivery_title_1?->getRawOriginal('value')  ?? '' }}">
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">{{translate('Title_2')}}
                            <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_title_within_20_characters') }}">
                                <i class="tio-info-outined"></i>
                            </span>

                        </label>
                        <input type="text" maxlength="20" class="form-control" placeholder="{{translate('Enter Title')}}" name="services_manage_delivery_title_2[]" value="{{ $services_manage_delivery_title_2?->getRawOriginal('value')  ?? '' }}">
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">{{translate('Short Description_1')}}

                            <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_short_description_within_120_characters') }}">
                                <i class="tio-info-outined"></i>
                            </span>

                        </label>
                        <input type="text" maxlength="120" class="form-control" placeholder="{{translate('Enter_Short_Description')}}" name="services_manage_delivery_description_1[]" value="{{ $services_manage_delivery_description_1?->getRawOriginal('value')  ?? '' }}">
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">{{translate('Short Description_2')}}
                            <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_short_description_within_120_characters') }}">
                                <i class="tio-info-outined"></i>
                            </span>

                        </label>
                        <input type="text" maxlength="120" class="form-control" placeholder="{{translate('Enter_Short_Description')}}" name="services_manage_delivery_description_2[]" value="{{ $services_manage_delivery_description_2?->getRawOriginal('value')  ?? '' }}">
                    </div>
                </div>
                <br>
                <label class="form-label">
                    {{ translate('Button_Content') }}
                </label>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="__bg-F8F9FC-card">
                            <div class="form-group mb-md-0">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-label text-capitalize m-0">
                                        {{translate('Button Name')}}
                                        <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{translate('Write_the_button_name_within_15_characters')}}">
                                            <i class="tio-info-outined"></i>
                                        </span>
                                    </label>

                                </div>
                                <input type="text" maxlength="15" placeholder="{{translate('Ex: Order now')}}" class="form-control h--45px" name="services_manage_delivery_button_name[]" value="{{ $services_manage_delivery_button_name?->getRawOriginal('value')  ?? null }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="__bg-F8F9FC-card">
                            <div class="form-group mb-md-0">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-label text-capitalize m-0">
                                        {{translate('Redirect Link')}}
                                        <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{translate('Add_the_link/address_where_the_button_will_redirect.')}}">
                                            <i class="tio-info-outined"></i>
                                        </span>
                                    </label>
                                    <label class="toggle-switch toggle-switch-sm m-0">
                                        <input type="checkbox" class="status toggle-switch-input" id="services_manage_delivery_button_status" onclick="toogleModal(event,'services_manage_delivery_button_status','mail-success.png','mail-warning.png',' <strong>{{translate('Want_to_enable_the_Manage_Delivery_tab_button_here')}}</strong>','<strong>{{translate('Want_to_disable_the_Manage_Delivery_tab_button_here')}}</strong>',`<p>{{translate('If_enabled,_everyone_can_see_the_Manage_Delivery_tab_button_on_the_landing_page')}}</p>`,`<p>{{translate('If_disabled,_Manage_Delivery_tab_button_will_be_hidden_from_the_landing_page')}}</p>`)" name="services_manage_delivery_button_status" value="1" {{ $services_manage_delivery_button_status?->value ?? null  == 1 ? 'checked': ''  }}>
                                        <span class="toggle-switch-label text mb-0">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                    </label>
                                </div>
                                <input type="url" placeholder="{{translate('Ex: https://www.apple.com/app-store/')}}" class="form-control h--45px" name="services_manage_delivery_button_link" value="{{ $services_manage_delivery_button_link  ?? '' }} ">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @forelse(json_decode($language) as $lang)
            <?php
                        if($services_manage_delivery_title_1?->translations){
                                $services_manage_delivery_title_1_translate = [];
                                foreach($services_manage_delivery_title_1->translations as $t)
                                {
                                    if($t->locale == $lang && $t->key=='services_manage_delivery_title_1'){
                                        $services_manage_delivery_title_1_translate[$lang]['value'] = $t->value;
                                    }
                                }
                            }
                        if($services_manage_delivery_title_2?->translations){
                                $services_manage_delivery_title_2_translate = [];
                                foreach($services_manage_delivery_title_2->translations as $t)
                                {
                                    if($t->locale == $lang && $t->key=='services_manage_delivery_title_2'){
                                        $services_manage_delivery_title_2_translate[$lang]['value'] = $t->value;
                                    }
                                }
                            }
                        if($services_manage_delivery_description_1?->translations){
                                $services_manage_delivery_description_1_translate = [];
                                foreach($services_manage_delivery_description_1->translations as $t)
                                {
                                    if($t->locale == $lang && $t->key=='services_manage_delivery_description_1'){
                                        $services_manage_delivery_description_1_translate[$lang]['value'] = $t->value;
                                    }
                                }
                            }
                        if($services_manage_delivery_description_2?->translations){
                                $services_manage_delivery_description_2_translate = [];
                                foreach($services_manage_delivery_description_2->translations as $t)
                                {
                                    if($t->locale == $lang && $t->key=='services_manage_delivery_description_2'){
                                        $services_manage_delivery_description_2_translate[$lang]['value'] = $t->value;
                                    }
                                }
                            }
                        if($services_manage_delivery_button_name?->translations){
                            $services_manage_delivery_button_name_translate = [];
                            foreach($services_manage_delivery_button_name->translations as $t)
                            {
                                if($t->locale == $lang && $t->key=='services_manage_delivery_button_name'){
                                    $services_manage_delivery_button_name_translate[$lang]['value'] = $t->value;
                                }
                            }
                        }
                    ?>
            <input type="hidden" name="lang[]" value="{{$lang}}">

            <div class="d-none lang_form" id="{{$lang}}-form3">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <label class="form-label">{{translate('Title_1')}} ({{strtoupper($lang)}})
                            <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_title_within_20_characters') }}">
                                <i class="tio-info-outined"></i>
                            </span>
                        </label>
                        <input type="text" maxlength="20" class="form-control" placeholder="{{translate('Enter Title')}}" name="services_manage_delivery_title_1[]" value="{{ $services_manage_delivery_title_1_translate[$lang]['value'] ?? '' }}">
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">{{translate('Title_2')}} ({{strtoupper($lang)}})
                            <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_title_within_20_characters') }}">
                                <i class="tio-info-outined"></i>
                            </span>
                        </label>
                        <input type="text" maxlength="20" class="form-control" placeholder="{{translate('Enter Title')}}" name="services_manage_delivery_title_2[]" value="{{ $services_manage_delivery_title_2_translate[$lang]['value'] ?? '' }}">
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">{{translate('Short Description_1')}} ({{strtoupper($lang)}})
                            <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_short_description_within_120_characters') }}">
                                <i class="tio-info-outined"></i>
                            </span>
                        </label>
                        <input type="text" maxlength="120" class="form-control" placeholder="{{translate('Enter_Short_Description')}}" name="services_manage_delivery_description_1[]" value="{{ $services_manage_delivery_description_1_translate[$lang]['value']  ?? '' }}">
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">{{translate('Short Description_2')}} ({{strtoupper($lang)}})
                            <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_short_description_within_120_characters') }}">
                                <i class="tio-info-outined"></i>
                            </span>
                        </label>
                        <input type="text" maxlength="120" class="form-control" placeholder="{{translate('Enter_Short_Description')}}" name="services_manage_delivery_description_2[]" value="{{  $services_manage_delivery_description_2_translate[$lang]['value'] ?? '' }}">
                    </div>
                </div>
                <br>
                <label class="form-label">
                    {{ translate('Button_Content') }}
                </label>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="__bg-F8F9FC-card">
                            <div class="form-group mb-md-0">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-label text-capitalize m-0">
                                        {{translate('Button Name')}} ({{strtoupper($lang)}})
                                        <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{translate('Write_the_button_name_within_15_characters')}}">
                                            <i class="tio-info-outined"></i>
                                        </span>
                                    </label>
                                </div>
                                <input type="text" maxlength="15" placeholder="{{translate('Ex: Order now')}}" class="form-control h--45px" name="services_manage_delivery_button_name[]" value="{{ $services_manage_delivery_button_name_translate[$lang]['value'] ?? null }}">
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            @empty
            @endforelse


            <div class="btn--container justify-content-end mt-3">
                <button type="reset" class="btn btn--reset">{{translate('Reset')}}</button>
                <button type="submit" onclick="" class="btn btn--primary mb-2">{{translate('Save')}}</button>
            </div>
        </div>
    </form>
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
