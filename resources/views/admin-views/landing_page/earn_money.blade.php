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
    <form action="{{ route('admin.landing_page.settings', 'earn-money-data') }}" method="post">
        @csrf
        <div class="card-body">
            <div class="row g-3 lang_form default-form" id="default-form">
                <input type="hidden" name="lang[]" value="default">
                <div class="col-sm-6">
                    <label class="form-label">{{translate('Title')}}
                        <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_title_within_40_characters') }}">
                            <i class="tio-info-outined"></i>
                        </span>
                    </label>
                    <input maxlength="40" type="text" name="earn_money_title[]" value="{{ $earn_money_title?->getRawOriginal('value') ?? null}}" class="form-control" placeholder="{{translate('Enter Title')}}">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">{{translate('Subtitle')}}
                        <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_subtitle_within_70_characters') }}">
                            <i class="tio-info-outined"></i>
                        </span>
                    </label>
                    <input maxlength="70" type="text" name="earn_money_sub_title[]" value="{{ $earn_money_sub_title?->getRawOriginal('value') ?? null}}" class="form-control" placeholder="{{translate('Enter Title')}}">
                </div>
            </div>
            @if($language)
            @forelse(json_decode($language) as $lang)
            <?php
                    if($earn_money_title?->translations){
                            $earn_money_title_translate = [];
                            foreach($earn_money_title->translations as $t)
                            {
                                if($t->locale == $lang && $t->key=='earn_money_title'){
                                    $earn_money_title_translate[$lang]['value'] = $t->value;
                                }
                            }
                        }
                    if($earn_money_sub_title?->translations){
                            $earn_money_sub_title_translate = [];
                            foreach($earn_money_sub_title->translations as $t)
                            {
                                if($t->locale == $lang && $t->key=='earn_money_sub_title'){
                                    $earn_money_sub_title_translate[$lang]['value'] = $t->value;
                                }
                            }
                        }
                        ?>

            <div class="row g-3 d-none lang_form" id="{{$lang}}-form">
                <input type="hidden" name="lang[]" value="{{$lang}}">
                <div class="col-sm-6">
                    <label class="form-label">{{translate('Title')}} ({{strtoupper($lang)}})
                        <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_title_within_40_characters') }}">
                            <i class="tio-info-outined"></i>
                        </span>
                    </label>
                    <input type="text" maxlength="40" name="earn_money_title[]" value="{{ $earn_money_title_translate[$lang]['value'] ?? '' }}" class="form-control" placeholder="{{translate('Enter Title')}}">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">{{translate('Subtitle')}} ({{strtoupper($lang)}})
                        <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_subtitle_within_70_characters') }}">
                            <i class="tio-info-outined"></i>
                        </span>
                    </label>
                    <input type="text" maxlength="70" name="earn_money_sub_title[]" value="{{ $earn_money_sub_title_translate[$lang]['value'] ?? '' }}" class="form-control" placeholder="{{translate('Enter Title')}}">
                </div>
            </div>
            @empty
            @endforelse
            @endif
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
        {{translate('Registration Section')}}
    </h5>
    {{-- <div class="text--primary-2 py-1 d-flex flex-wrap align-items-center" type="button" data-toggle="modal" data-target="#section_view">
                    <strong class="mr-2">{{translate('Section View')}}</strong>
    <div>
        <i class="tio-intersect"></i>
    </div>
</div> --}}
</div>



<div class="card">
    <form action="{{ route('admin.landing_page.settings', 'earn-money-data-reg-section') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="row g-3 ">
                <div class="col-sm-6">
                    <div class="form-group lang_form default-form">
                        <input type="hidden" name="lang[]" value="default">

                        <label class="form-label">{{translate('Title')}}

                            <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_title_within_40_characters') }}">
                                <i class="tio-info-outined"></i>
                            </span>
                        </label>
                        <input type="text" maxlength="40" name="earn_money_reg_title[]" value="{{ $earn_money_reg_title?->getRawOriginal('value') ?? null }}" class="form-control" placeholder="{{translate('Enter Title')}}">
                    </div>

                    @if($language)
                    {{-- {{ dd($earn_money_reg_title?->translations) }} --}}
                    @forelse(json_decode($language) as $lang)
                    <input type="hidden" name="lang[]" value="{{$lang}}">
                    <?php
                                            if($earn_money_reg_title?->translations){
                                                    $earn_money_reg_title_translate = [];
                                                    foreach($earn_money_reg_title->translations as $t)
                                                    {
                                                        if($t->locale == $lang && $t->key=='earn_money_reg_title'){
                                                            $earn_money_reg_title_translate[$lang]['value'] = $t->value;
                                                        }
                                                    }
                                                }

                                            ?>

                    <div class="form-group d-none lang_form" id="{{$lang}}-form1">
                        <label class="form-label">{{translate('Title')}} ({{strtoupper($lang)}})

                            <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Write_the_title_within_40_characters') }}">
                                <i class="tio-info-outined"></i>
                            </span>
                        </label>
                        <input type="text" maxlength="40" name="earn_money_reg_title[]" class="form-control" placeholder="{{translate('Enter Title')}}" value="{{  $earn_money_reg_title_translate[$lang]['value'] ?? '' }}">
                    </div>

                    @empty
                    @endforelse
                    @endif

                    <div class="d-flex gap-40px">
                        <div class="d-flex flex-column">
                            <label class="form-label d-block mb-2">
                                {{translate('Feature Icon *')}} <span class="text--primary">(2:1)</span>
                            </label>
                            <div class="position-relative">

                                <label class="upload-img-3 m-0 d-block my-auto">
                                    <div class="img">
                                        <img src="{{asset('storage/app/public/earn_money')}}/{{ $earn_money_reg_image?->getRawOriginal('value') ?? null }}" onerror="this.src='{{asset("/public/assets/admin/img/upload-3.png")}}'" class="img__aspect-unset mw-100 min-w-187px" alt="">
                                    </div>
                                    <input type="file" name="earn_money_reg_image" hidden="">
                                </label>
                                @if ($earn_money_reg_image?->value)
                                <span id="remove_image_1" class="remove_image_button" onclick="toogleStatusModal(event,'remove_image_1','mail-success','mail-warninh','{{translate('Important!')}}','{{translate('Warning!')}}',`<p>{{translate('Are_you_sure_you_want_to_remove_this_image')}}</p>`,`<p>{{translate('Are_you_sure_you_want_to_remove_this_image.')}}</p>`)"> <i class="tio-clear"></i></span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">{{translate('Restaurant Registration Button')}}</label>
                            <div class="__bg-F8F9FC-card">
                                <div class="form-group lang_form default-form">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <label class="form-label text-capitalize m-0">
                                            {{translate('Button Name')}}
                                            <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{translate('Write_the_button_name_within_15_characters')}}">
                                                <i class="tio-info-outined"></i>
                                            </span>
                                        </label>

                                    </div>
                                    <input type="text" maxlength="15" placeholder="{{translate('Ex: Order now')}}" class="form-control h--45px" name="earn_money_restaurant_req_button_name[]" value="{{ $earn_money_restaurant_req_button_name?->getRawOriginal('value') ?? '' }}">
                                </div>

                                @if($language)
                                @forelse(json_decode($language) as $lang)
                                <?php
                                                        if($earn_money_restaurant_req_button_name?->translations){
                                                            $earn_money_restaurant_req_button_name_translate = [];
                                                            foreach($earn_money_restaurant_req_button_name->translations as $t)
                                                            {
                                                                if($t->locale == $lang && $t->key=='earn_money_restaurant_req_button_name'){
                                                                    $earn_money_restaurant_req_button_name_translate[$lang]['value'] = $t->value;
                                                                }
                                                            }
                                                        }

                                                    ?>

                                <div class="form-group d-none lang_form" id="{{$lang}}-form2">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <label class="form-label text-capitalize m-0">
                                            {{translate('Button Name')}} ({{strtoupper($lang)}})
                                            <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{translate('Write_the_button_name_within_15_characters')}}">
                                                <i class="tio-info-outined"></i>
                                            </span>
                                        </label>
                                    </div>
                                    <input type="text" maxlength="15" placeholder="{{translate('Ex: Order now')}}" class="form-control h--45px" name="earn_money_restaurant_req_button_name[]" value="{{ $earn_money_restaurant_req_button_name_translate[$lang]['value'] ?? '' }}">
                                </div>

                                @empty
                                @endforelse
                                @endif

                                <div class="form-group mb-md-0">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <label class="form-label text-capitalize m-0">
                                            {{translate('Redirect Link')}}
                                            <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{translate('Add_the_link/address_where_the_Restaurant_Registration_button_will_redirect.')}}">
                                                <i class="tio-info-outined"></i>
                                            </span>
                                        </label>
                                        <label class="toggle-switch toggle-switch-sm m-0">
                                            <input type="checkbox" class="status toggle-switch-input" id="earn_money_restaurant_req_button_status" onclick="toogleModal(event,'earn_money_restaurant_req_button_status','mail-success.png','mail-warning.png',' <strong>{{translate('Want_to_enable_the_Restaurant_Registration_button_here')}}</strong>','<strong>{{translate('Want_to_disable_the_Restaurant_Registration_button_here')}}</strong>',`<p>{{translate('If_enabled,_everyone_can_see_the_Restaurant_Registration_button_on_the_landing_page')}}</p>`,`<p>{{translate('If_disabled,_Restaurant_Registration_button_will_be_hidden_from_the_landing_page')}}</p>`)" name="earn_money_restaurant_req_button_status" value="1" {{ $earn_money_restaurant_req_button_status?->value == 1 ? 'checked': ''  }}>
                                            <span class="toggle-switch-label text mb-0">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>

                                    </div>
                                    <input type="url" placeholder="{{translate('Ex: https://www.apple.com/app-store/')}}" class="form-control h--45px" name="earn_money_restaurant_req_button_link" value="{{ $earn_money_restaurant_req_button_link  ?? '' }} ">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">{{translate('DeliveryMan_Registration_Button')}}</label>
                            <div class="__bg-F8F9FC-card">
                                <div class="form-group lang_form default-form">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <label class="form-label text-capitalize m-0">
                                            {{translate('Button Name')}}
                                            <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{translate('Write_the_button_name_within_15_characters')}}">
                                                <i class="tio-info-outined"></i>
                                            </span>
                                        </label>

                                    </div>
                                    <input type="text" maxlength="15" placeholder="{{translate('Ex: Order now')}}" class="form-control h--45px" name="earn_money_delivety_man_req_button_name[]" value="{{  $earn_money_delivety_man_req_button_name->value ?? '' }}">
                                </div>

                                @if($language)
                                @forelse(json_decode($language) as $lang)
                                <?php
                                                        if($earn_money_delivety_man_req_button_name?->translations){
                                                                $earn_money_delivety_man_req_button_name_translate = [];
                                                                foreach($earn_money_delivety_man_req_button_name->translations as $t)
                                                                {
                                                                    if($t->locale == $lang && $t->key=='earn_money_delivety_man_req_button_name'){
                                                                        $earn_money_delivety_man_req_button_name_translate[$lang]['value'] = $t->value;
                                                                    }
                                                                }
                                                            }

                                                    ?>

                                <div class="form-group d-none lang_form" id="{{$lang}}-form3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <label class="form-label text-capitalize m-0">
                                            {{translate('Button Name')}} ({{strtoupper($lang)}})
                                            <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{translate('Write_the_button_name_within_15_characters')}}">
                                                <i class="tio-info-outined"></i>
                                            </span>
                                        </label>
                                    </div>
                                    <input type="text" maxlength="15" placeholder="{{translate('Ex: Order now')}}" class="form-control h--45px" name="earn_money_delivety_man_req_button_name[]" value="{{  $earn_money_delivety_man_req_button_name_translate[$lang]['value']?? '' }}">
                                </div>

                                @empty
                                @endforelse
                                @endif

                                <div class="form-group mb-md-0">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <label class="form-label text-capitalize m-0">
                                            {{translate('Redirect Link')}}
                                            <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{translate('Add_the_link/address_where_the_Deliveryman_Registration_button_will_redirect.')}}">
                                                <i class="tio-info-outined"></i>
                                            </span>
                                        </label>
                                        <label class="toggle-switch toggle-switch-sm m-0">
                                            <input type="checkbox" class="status toggle-switch-input" id="earn_money_delivery_man_req_button_status" onclick="toogleModal(event,'earn_money_delivery_man_req_button_status','mail-success.png','mail-warning.png',' <strong>{{translate('Want_to_enable_the_Deliveryman_Registration_button_here')}}</strong>','<strong>{{translate('Want_to_disable_the_Deliveryman_Registration_button_here')}}</strong>',`<p>{{translate('If_enabled,_everyone_can_see_the_Deliveryman_Registration_button_on_the_landing_page')}}</p>`,`<p>{{translate('If_disabled,_Deliveryman_Registration_button_will_be_hidden_from_the_landing_page')}}</p>`)" name="earn_money_delivery_man_req_button_status" value="1" {{ $earn_money_delivery_man_req_button_status?->value   == 1 ? 'checked': ''  }}>

                                            <span class="toggle-switch-label text mb-0">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>

                                    </div>
                                    <input type="url" placeholder="{{translate('Ex: https://www.apple.com/app-store/')}}" class="form-control h--45px" name="earn_money_delivery_req_button_link" value="{{ $earn_money_delivery_man_req_button_link ?? '' }}">
                                </div>
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





<form id="remove_image_1_form" action="{{ route('admin.remove_image') }}" method="post">
    @csrf
    <input type="hidden" name="id" value="{{  $earn_money_reg_image?->id}}">
    <input type="hidden" name="model_name" value="DataSetting">
    <input type="hidden" name="image_path" value="earn_money">
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
