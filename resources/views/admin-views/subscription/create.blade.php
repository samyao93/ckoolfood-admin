@extends('layouts.admin.app')
@section('title', translate('messages.Subscription'))
@push('css_or_js')
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header pb-0">
            <div class="d-flex flex-wrap justify-content-between align-items-start">
                <div class="d-flex align-items-start __gap-12px">
                <img src="{{ asset('/public/assets/admin/img/subscription-plan.png') }}" alt="" class="w-24 mr-2">
                    <div>
                        <h1 class="page-header-title text-capitalize">
                            {{ translate('Create Subscription Package') }}
                        </h1>
                        <p>
                            {{translate('Create Subscriptions Packages for Subscription Business Model')}}
                        </p>
                    </div>
                </div>
                <div class="text--primary-2 py-1 d-flex flex-wrap align-items-center" type="button" data-toggle="modal" data-target="#how-it-works">
                    <strong class="mr-2">{{translate('See_how_it_works')}}</strong>
                    <div>
                        <i class="tio-info-outined"></i>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Page Header -->


        <!-- Content Row -->
        <form action="{{ route('admin.subscription.subscription_store') }}" method="post">
            @csrf
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title d-flex align-items-center font-medium">
                        <span class="card-header-icon mr-1">
                            <img src="{{ asset('/public/assets/admin/img/bill.svg') }}"
                                alt="">
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
                    @if($language)
                    <ul class="nav nav-tabs mb-4">
                        <li class="nav-item">
                            <a class="nav-link lang_link active" href="#" id="default-link">{{ translate('Default') }}</a>
                        </li>
                        @foreach(json_decode($language) as $lang)
                        <li class="nav-item">
                            <a class="nav-link lang_link"  href="#" id="{{$lang}}-link">{{\App\CentralLogics\Helpers::get_language_name($lang).'('.strtoupper($lang).')'}}</a>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                    <div class="row g-3">




                        <div class="col-md-4 lang_form" id="default-form">
                            <div class="form-group mb-0">
                                <label class="form-label input-label"
                                for="name">{{ translate('Package Name') }} ({{ translate('Default') }})</label>
                                <input type="text" name="package_name[]" class="form-control" id="name"
                                placeholder="{{ translate('Package Name') }}" 
                                >
                            <input type="hidden" name="lang[]" value="default">
                            </div>
                        </div>

                        @if($language)
                                @foreach(json_decode($language) as $lang)
                                <div class="col-md-4  d-none lang_form" id="{{$lang}}-form">
                                    <div class="form-group mb-0">
                                        <label class="form-label input-label"
                                        for="{{$lang}}_title">{{ translate('Package Name') }} ({{strtoupper($lang)}})</label>
                                        <input type="text" name="package_name[]" class="form-control" id="{{$lang}}_title"
                                        placeholder="{{ translate('Package Name') }}"
                                        >
                                        <input type="hidden" name="lang[]" value="{{$lang}}">
                                    </div>
                                </div>
                                @endforeach
                        @endif
                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label class="form-label input-label"
                                    for="package_price">{{ translate('Package Price') }}
                                    {{ \App\CentralLogics\Helpers::currency_symbol() }}</label>

                                <input type="number" name="package_price" class="form-control" id="package_price"
                                    min="1" step="0.01" aria-describedby="emailHelp"
                                    placeholder="{{ translate('Package price') }}" required
                                    value="{{ old('package_price') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label class="form-label input-label  "
                                    for="package_validity">{{ translate('Package Validity') }}
                                    {{ translate('Days') }}</label>
                                <input type="number" name="package_validity" class="form-control" id="package_validity"
                                    aria-describedby="emailHelp" placeholder="{{ translate('Package Validity') }}"
                                    min="1" ,step="1" required value="{{ old('package_validity') }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3">

                        <div class="col-md-4 lang_form default-form" >
                            <div class="form-group">
                                <label class="form-label input-label   text-capitalize"
                                    for="package_info">{{ translate('messages.package_info') }}</label>
                                <textarea class="form-control" placeholder="{{ translate('EX:_Value_for_money') }}"  name="text[]" id="package_info"></textarea>
                                {{-- <input type="hidden" name="lang[]" value="default"> --}}
                            </div>
                        </div>

                        @if($language)
                        @foreach(json_decode($language) as $lang)
                        <div class="col-md-4 d-none lang_form" id="{{$lang}}-form1">
                            <div class="form-group">
                                <label class="form-label input-label   text-capitalize"
                                    for="package_info">{{ translate('messages.package_info') }} ({{strtoupper($lang)}})</label>
                                <textarea class="form-control" name="text[]" placeholder="{{ translate('EX:_Value_for_money') }}" id="package_info"></textarea>
                            </div>
                        </div>
                        {{-- <input type="hidden" name="lang[]" value="{{$lang}}"> --}}
                        @endforeach
                        @endif
                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <div class="col-sm-4">
                                    <label class="form-label input-label   text-capitalize"
                                        for="package_price">{{ translate('messages.choose_colour') }}</label>
                                    <input name="colour" type="color" class="form-control form-control-color w-100"
                                        value="{{ old('colour') ?? '#ed9d24' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-3">
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
                        <input type="checkbox" name="features[]" value="account" class="form-check-input" id="select-all">
                        <label class="form-check-label ml-2" for="select-all">{{ translate('Select All') }}</label>
                    </div>
                </div>
                <div class="card-body">
                    <div class="check--item-wrapper mt-0">
                        <div class="check-item">
                            <div class="form-group form-check form--check">
                                <input type="checkbox" name="pos_system" value="1" class="form-check-input"
                                    id="pos_system">
                                <label class="form-check-label ml-2 ml-sm-3   text-dark"
                                    for="pos_system">{{ translate('messages.pos_system') }}</label>
                            </div>
                        </div>

                        <div class="check-item">
                            <div class="form-group form-check form--check">
                                <input type="checkbox" name="self_delivery" value="1" class="form-check-input"
                                    id="self_delivery">
                                <label class="form-check-label ml-2 ml-sm-3   text-dark"
                                    for="self_delivery">{{ translate('messages.self_delivery') }}</label>
                            </div>
                        </div>

                        <div class="check-item">
                            <div class="form-group form-check form--check">
                                <input type="checkbox" name="mobile_app" value="1" class="form-check-input"
                                    id="mobile_app">
                                <label class="form-check-label ml-2 ml-sm-3   text-dark"
                                    for="mobile_app">{{ translate('messages.Mobile App') }}</label>
                            </div>
                        </div>
                        <div class="check-item">
                            <div class="form-group form-check form--check">
                                <input type="checkbox" name="review" value="1" class="form-check-input"
                                    id="review">
                                <label class="form-check-label ml-2 ml-sm-3   text-dark"
                                    for="review">{{ translate('messages.review') }}</label>
                            </div>
                        </div>


                        <div class="check-item">
                            <div class="form-group form-check form--check">
                                <input type="checkbox" name="chat" value="1" class="form-check-input"
                                    id="chat">
                                <label class="form-check-label ml-2 ml-sm-3   text-dark"
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
                            {{ translate('Set Limit') }}
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
                                            id="Maximum_Order_Limit_unlimited" onclick="hide_order_input()"  checked value="option1">
                                        <label class="form-check-label text-dark m-0"
                                            for="Maximum_Order_Limit_unlimited">{{ translate('Unlimited') }}
                                            ({{ translate('messages.default') }})</label>
                                    </div>
                                    <div class="form-check form-check-inline py-2">
                                        <input class="form-check-input" type="radio" name="Maximum_Order_Limited"
                                            id="Maximum_Order_Limited" onclick="show_order_input()" value="option2">
                                        <label class="form-check-label text-dark m-0"
                                            for="Maximum_Order_Limited">{{ translate('Use_Limit') }}</label>
                                    </div>
                                    <input type="number" name="max_order" min="1" step="1"  hidden id="max_o" class="form-control w-auto"
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
                                                id="Maximum_product_Limit_unlimited" onclick="hide_product_input()"  checked >
                                            <label class="form-check-label text-dark m-0"
                                                for="Maximum_product_Limit_unlimited">{{ translate('Unlimited') }}
                                                ({{ translate('messages.default') }})</label>
                                        </div>
                                        <div class="form-check form-check-inline py-2">
                                            <input class="form-check-input" type="radio" name="Maximum_product_Limit"
                                                id="Maximum_Product_Limited" onclick="show_product_input()" >
                                            <label class="form-check-label text-dark m-0"
                                                for="Maximum_Product_Limited">{{ translate('Use_Limit') }}</label>
                                        </div>
                                        <input type="number" hidden name="max_product" min="1" step="1" class="form-control w-auto" id="max_p"
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

        <!-- How it Works -->
        <div class="modal fade" id="how-it-works">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h3>{{translate('Subscription Packages')}}</h3>
                        <p>
                            {{translate('Here you can view all the data placements in a package card in the subscription UI in the user app and website')}}
                        </p>
                        <img src="{{asset('/public/assets/admin/img/modal/subscription.png')}}" class="mw-100" alt="">
                    </div>
                </div>
            </div>
        </div>

    </div>


@endsection

@push('script_2')
    <script>

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
