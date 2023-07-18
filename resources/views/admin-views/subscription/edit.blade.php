@extends('layouts.admin.app')
@section('title', translate('Update Package') )
@push('css_or_js')
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Heading -->
        <div class="page-header">
        <div class="page-header pb-0">
            <div class="d-flex flex-wrap justify-content-between align-items-start">
                <div class="d-flex align-items-start __gap-12px">
                    <img src="{{ asset('/public/assets/admin/img/subscription-plan.png') }}" alt="" class="w-24 mr-2">                    <div>
                        <h1 class="page-header-title text-capitalize">
                            {{ translate('messages.edit') }}
                {{ translate('Package') }} : {{ $package->package_name }}
                        </h1>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Page Heading -->
        <br>
        <!-- Content Row -->
        <form action="{{ route('admin.subscription.subscription_update') }}" method="post">
            @csrf
            <input type="hidden" name="id" value="{{ $package->id }}">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title d-flex align-items-center font-medium">
                        <span class="card-header-icon mr-1">
                            {{-- <img src="{{asset('/public/assets/admin/img/ion_information-circle-sharp.svg')}}" alt=""> --}}
                        </span>
                        <span>
                            {{ translate('Package Information') }}
                        </span>
                    </h5>
                </div>
                <div class="card-body">
                    @php($language=\App\Models\BusinessSetting::where('key','language')->first())
                    @php($language = $language->value ?? null)
                    @php($default_lang = str_replace('_', '-', app()->getLocale()))
                    <ul class="nav nav-tabs mb-3">
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

                    <div class="row g-3">


                        <div class="col-md-4 lang_form" id="default-form">
                            <div class="form-group mb-0">
                                <input type="hidden" name="lang[]" value="default">

                                <label class="form-label input-label   text-capitalize"
                                    for="name">{{ translate('messages.Package Name') }} ({{ translate('Default') }}) </label>
                                <input type="text" name="package_name[]" class="form-control" id="name"
                                    placeholder="{{ translate('Package Name') }}" 
                                    value="{{ $package->getRawOriginal('package_name')  ?? ''}}">
                            </div>
                        </div>
                        @if ($language)
                        @forelse(json_decode($language) as $lang)
                        <?php
                            if($package?->translations){
                                $translate = [];
                                foreach($package?->translations as $t)
                                {
                                    if($t->locale == $lang && $t->key=="package_name"){
                                        $translate[$lang]['package_name'] = $t->value;
                                    }
                                    // if($t->locale == $lang && $t->key=="text"){
                                    //     $translate[$lang]['text'] = $t->value;
                                    // }
                                }
                            }

                            ?>
                            <div class="col-md-4 d-none lang_form" id="{{$lang}}-form">
                                <div class="form-group mb-0">
                                    <label class="form-label input-label   text-capitalize"
                                        for="name">{{ translate('messages.Package Name') }} ({{strtoupper($lang)}})</label>
                                    <input type="text" name="package_name[]" class="form-control" id="name"
                                        placeholder="{{ translate('Package Name') }}"
                                        value="{{ $translate[$lang]['package_name']  ?? ''}}">
                                </div>
                            </div>
                            <input type="hidden" name="lang[]" value="{{$lang}}">

                            @endforeach
                            @endif


                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label class="form-label input-label    text-capitalize"
                                    for="package_price">{{ translate('messages.Package price') }} {{ \App\CentralLogics\Helpers::currency_symbol() }}</label>
                                <input type="text" name="package_price" min="1" step="0.01" class="form-control" id="package_price" aria-describedby="emailHelp" placeholder="{{ translate('Package price') }}" required value="{{ $package->price }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label class="form-label input-label    text-capitalize"
                                    for="package_validity">{{ translate('messages.Package Validity') }} {{ translate('Days') }}</label>
                                <div class="input-group mb-2">
                                    <input type="number" name="package_validity" min="1" step="1" class="form-control" id="package_validity"
                                        aria-describedby="emailHelp" placeholder="{{ translate('Package Validity') }}"
                                        required value="{{ $package->validity }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row g-3">



                        <div class="col-md-4 lang_form default-form">
                            <div class="form-group ">
                                <label class="form-label input-label    text-capitalize"
                                    for="package_info">{{ translate('messages.package_info') }} ({{ translate('Default') }})</label>
                                    <textarea  class="form-control" name="text[]" id="package_info" placeholder="{{ translate('EX:_Value_for_money') }}"  >{{ $package->getRawOriginal('text')  ?? '' }}</textarea>
                            </div>
                        </div>


                        @if ($language)
                        @forelse(json_decode($language) as $lang)
                        <?php
                            if($package?->translations){
                                $translate = [];
                                foreach($package?->translations as $t)
                                {
                                    if($t->locale == $lang && $t->key=="text"){
                                        $translate[$lang]['text'] = $t->value;
                                    }
                                }
                            }

                            ?>



                    <div class="col-md-4 d-none lang_form" id="{{$lang}}-form1">
                        <div class="form-group ">
                            <label class="form-label input-label   text-capitalize"
                                for="package_info">{{ translate('messages.package_info') }} ({{strtoupper($lang)}})</label>
                                <textarea  class="form-control" name="text[]" id="package_info" placeholder="{{ translate('EX:_Value_for_money') }}" >{{ $translate[$lang]['text']  ?? '' }}</textarea>
                        </div>
                    </div>

                        @endforeach
                        @endif
                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <div class="col-sm-4">
                            <label class="form-label input-label    text-capitalize"
                            for="package_price">{{ translate('messages.choose_colour') }}</label>
                            <input name="colour" type="color" class="form-control form-control-color w-100"
                                value="{{ isset($package->colour) ? $package->colour : '#ed9d24' }}">
                            </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title d-flex align-items-center font-medium">
                        <span class="card-header-icon mr-1">
                            <img src="{{ asset('/public/assets/admin/img/package.png') }}" alt="">
                        </span>
                        <span>
                            {{ translate('Package Available Features') }}
                        </span>
                    </h5>
                    <div class="form-group form-check form--check m-0 ml-2 mr-auto">
                        <input type="checkbox" class="form-check-input"
                            id="select-all">
                        <label class="form-check-label ml-2" for="select-all">{{ translate('Select All') }}</label>
                    </div>
                </div>
                <div class="card-body">
                    <div class="check--item-wrapper mt-0">
                        <div class="check-item">
                            <div class="form-group form-check form--check">

                                <input type="checkbox" name="pos_system" value="1" class="form-check-input "
                                    {{ $package->pos == 1 ? 'checked' : '' }} id="pos_system">
                                <label class="form-check-label ml-2 ml-sm-3    text-dark"
                                    for="pos_system">{{ translate('messages.pos_system') }}</label>
                            </div>
                        </div>

                        <div class="check-item">
                            <div class="form-group form-check form--check">
                                <input type="checkbox" name="self_delivery" value="1" class="form-check-input"
                                    {{ $package->self_delivery == 1 ? 'checked' : '' }} id="self_delivery">
                                <label class="form-check-label ml-2 ml-sm-3    text-dark"
                                    for="self_delivery">{{ translate('messages.self_delivery') }}</label>
                            </div>
                        </div>

                        <div class="check-item">
                            <div class="form-group form-check form--check">
                                <input type="checkbox" name="mobile_app" value="1" class="form-check-input"
                                    {{ $package->mobile_app == 1 ? 'checked' : '' }} id="mobile_app">
                                <label class="form-check-label ml-2 ml-sm-3    text-dark"
                                    for="mobile_app">{{ translate('messages.Mobile App') }}</label>
                            </div>
                        </div>
                        <div class="check-item">
                            <div class="form-group form-check form--check">
                                <input type="checkbox" name="review" value="1" class="form-check-input"
                                    {{ $package->review == 1 ? 'checked' : '' }} id="review">
                                <label class="form-check-label ml-2 ml-sm-3    text-dark"
                                    for="review">{{ translate('messages.review') }}</label>
                            </div>
                        </div>


                        <div class="check-item">
                            <div class="form-group form-check form--check">
                                <input type="checkbox" name="chat" value="1" class="form-check-input"
                                    {{ $package->chat == 1 ? 'checked' : '' }} id="chat">
                                <label class="form-check-label ml-2 ml-sm-3    text-dark"
                                    for="chat">{{ translate('messages.chat') }}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <br>
            <div class="card mt-md-5">
                <div class="card-header">
                    <h5 class="card-title d-flex align-items-center font-medium">
                        <span class="card-header-icon mr-1">
                            <img src="{{ asset('/public/assets/admin/img/package.png') }}" alt="">
                        </span>
                        <span>
                            {{translate('Set Limit')}}
                        </span>
                    </h5>
                </div>


                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-sm col-lg-4">
                            <div class="form-group  m-0">
                                <label class="form-label text-capitalize input-label font-medium"
                                    for="name">{{ translate('messages.Maximum_Order_Limit') }}</label>
                                    <div class="d-flex flex-wrap __gap-15px">
                                            <div class="form-check form-check-inline py-2">
                                                <input class="form-check-input" type="radio" name="Maximum_Order_Limited"
                                                    id="Maximum_Order_Limit_unlimited" onclick="hide_order_input()"  {{  ($package->max_order  == 'unlimited') ? 'checked' : '' }}  value="option1">
                                                <label class="form-check-label text-dark m-0"
                                                    for="Maximum_Order_Limit_unlimited">{{ translate('Unlimited') }}
                                                    ({{ translate('messages.default') }})</label>
                                            </div>
                                            <div class="form-check form-check-inline py-2">
                                                <input class="form-check-input" type="radio" name="Maximum_Order_Limited"
                                                    id="Maximum_Order_Limited" onclick="show_order_input()" {{  ($package->max_order  != 'unlimited') ? 'checked' : '' }} value="option2">
                                                <label class="form-check-label text-dark m-0"
                                                    for="Maximum_Order_Limited">{{ translate('Use_Limit') }}</label>
                                            </div>

                                        <input type="number" name="max_order" {{  ($package->max_order  == 'unlimited') ? 'hidden' : '' }}
                                            value="{{ ($package->max_order  != 'unlimited') ? $package->max_order : null }}"  min="1" step="1" id="max_o" class="form-control  w-auto"
                                            placeholder="{{ translate('messages.Ex :') }} 1000 ">
                                    </div>
                            </div>
                        </div>
                        <div class="col-md-sm col-lg-4">
                            <div class="form-group m-0">
                                <label class="form-label text-capitalize input-label font-medium"
                                    for="name">{{ translate('Maximum product Limit') }}</label>
                                    <div class="d-flex flex-wrap __gap-15px">
                                        <div class="form-check form-check-inline py-2">
                                            <input class="form-check-input" type="radio" name="Maximum_product_Limit"
                                                id="Maximum_product_Limit_unlimited" onclick="hide_product_input()"  {{  ($package->max_product  == 'unlimited') ? 'checked' : '' }} >
                                            <label class="fform-check-label text-dark m-0"
                                                for="Maximum_product_Limit_unlimited">{{ translate('Unlimited') }}
                                                ({{ translate('messages.default') }})</label>
                                        </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="Maximum_product_Limit"
                                            id="Maximum_Product_Limited" onclick="show_product_input()" {{  ($package->max_product  != 'unlimited') ? 'checked' : '' }}>
                                        <label class="fform-check-label text-dark m-0"
                                            for="Maximum_Product_Limited">{{ translate('Use_Limit') }}</label>
                                    </div>

                                <input type="number" {{  ($package->max_product  == 'unlimited') ? 'hidden' : '' }} name="max_product" min="1" step="1" class="form-control w-auto" id="max_p"
                                value="{{ ($package->max_product  != 'unlimited') ? $package->max_product : null }}"
                                    placeholder="{{ translate('messages.Ex :') }} 1000 ">
                            </div>
                            </div>
                        </div>
                    </div>
                </div>






            </div>

            <div class="mt-4 pb-3">
                <div class="btn--container justify-content-end">
                    <button type="reset" id="reset_btn" class="btn btn--reset">
                        {{ translate('messages.reset') }}
                    </button>
                    <button type="submit" class="btn btn--primary">{{ translate('messages.submit') }}</button>
                </div>
            </div>
        </form>

    </div>




@endsection

@push('script_2')
    <script>
        $('#select-all').on('change', function() {
            if (this.checked === true) {
                $('.check--item-wrapper .check-item .form-check-input').attr('checked', true)
            } else {
                $('.check--item-wrapper .check-item .form-check-input').attr('checked', false)
            }
        })

        $('#reset_btn').click(function() {
            location.reload(true);
        })
        function show_order_input(){
            $('#max_o').removeAttr("hidden");
        }
    function hide_order_input(){
            $('#max_o').attr("hidden","true");
            $('#max_o').val(null).trigger('change');
        }
    function show_product_input(){
            $('#max_p').removeAttr("hidden");
        }
    function hide_product_input(){
            $('#max_p').attr("hidden","true");
            $('#max_p').val(null).trigger('change');
        }

    </script>
    <script>
        $(document).ready(function(){
          $('#show_button_1').click(function(){
            $('#show_1').toggle();
            $('#show_button_1').hide();
          });
        });
        $(document).ready(function(){
          $('#show_button_2').click(function(){
            $('#show_2').toggle();
            $('#show_button_2').hide();
          });
        });

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
