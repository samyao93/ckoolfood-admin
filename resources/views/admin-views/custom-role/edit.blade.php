@extends('layouts.admin.app')
@section('title',translate('messages.custom_role'))
@push('css_or_js')

@endpush

@section('content')
<div class="content container-fluid">

    <!-- Page Heading -->
    <div class="page-header">
        <h1 class="page-header-title mb-2 text-capitalize">
            <div class="card-header-icon d-inline-flex mr-2 img">
                <img src="{{asset('/public/assets/admin/img/role.png')}}" alt="public">
            </div>
            <span>
                {{translate('messages.employee')}} {{translate('messages.Role')}}
            </span>
        </h1>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('admin.custom-role.update',[$role['id']])}}" method="post">
                        @csrf
                        @php($language=\App\Models\BusinessSetting::where('key','language')->first())
                        @php($language = $language->value ?? null)
                        @php($default_lang = str_replace('_', '-', app()->getLocale()))
                        @if($language)
                            <ul class="nav nav-tabs mb-4">
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
                            <div class="lang_form" id="default-form">
                                <div class="form-group">
                                    <label class="input-label " for="name">{{translate('messages.role_name')}} ({{ translate('messages.default') }})</label>
                                    <input type="text" name="name[]" class="form-control" id="name" value="{{$role?->getRawOriginal('name')}}"
                                        placeholder="{{translate('messages.role_name')}}" >
                                </div>
                                <input type="hidden" name="lang[]" value="default">
                            </div>




                            @if($language)
                                @foreach(json_decode($language) as $lang)
                                        <?php
                                            if(count($role['translations'])){
                                                $translate = [];
                                                foreach($role['translations'] as $t)
                                                {
                                                    if($t->locale == $lang && $t->key=="name"){
                                                        $translate[$lang]['name'] = $t->value;
                                                    }
                                                }
                                            }
                                        ?>
                                        <div class="d-none lang_form" id="{{$lang}}-form">
                                            <div class="form-group">
                                                <label class="input-label" for="{{$lang}}_title">{{translate('messages.role_name')}} ({{strtoupper($lang)}})</label>
                                                <input type="text" name="name[]" id="{{$lang}}_title" class="form-control" placeholder="{{translate('role_name_example')}}" value="{{$translate[$lang]['name']??''}}" oninvalid="document.getElementById('en-link').click()">
                                            </div>
                                            <input type="hidden" name="lang[]" value="{{$lang}}">
                                        </div>
                                    @endforeach
                            @endif




                        <label class="input-label " for="name">{{translate('messages.module_permission')}} : </label>
                        <hr>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group form-check">
                                    <input type="checkbox" name="modules[]" value="account" class="form-check-input"
                                           id="account"  {{in_array('account',(array)json_decode($role['modules']))?'checked':''}}>
                                    <label class="form-check-label  text-dark" for="account">{{translate('messages.collect')}} {{translate('messages.cash')}}</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-check">
                                    <input type="checkbox" name="modules[]" value="addon" class="form-check-input"
                                           id="addon"  {{in_array('addon',(array)json_decode($role['modules']))?'checked':''}}>
                                    <label class="form-check-label  text-dark" for="addon">{{translate('messages.addon')}}</label>
                                </div>
                            </div>
                            {{-- <div class="col-md-3">
                                <div class="form-group form-check">
                                    <input type="checkbox" name="modules[]" value="attribute" class="form-check-input"
                                           id="attribute"  {{in_array('attribute',(array)json_decode($role['modules']))?'checked':''}}>
                                    <label class="form-check-label  text-dark" for="attribute">{{translate('messages.attribute')}}</label>
                                </div>
                            </div> --}}
                            <div class="col-md-3">
                                <div class="form-group form-check">
                                    <input type="checkbox" name="modules[]" value="banner" class="form-check-input"
                                           id="banner"  {{in_array('banner',(array)json_decode($role['modules']))?'checked':''}}>
                                    <label class="form-check-label  text-dark" for="banner">{{translate('messages.banner')}}</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-check">
                                    <input type="checkbox" name="modules[]" value="campaign" class="form-check-input"
                                           id="campaign"  {{in_array('campaign',(array)json_decode($role['modules']))?'checked':''}}>
                                    <label class="form-check-label  text-dark" for="campaign">{{translate('messages.campaign')}}</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-check">
                                    <input type="checkbox" name="modules[]" value="category" class="form-check-input"
                                           id="category"  {{in_array('category',(array)json_decode($role['modules']))?'checked':''}}>
                                    <label class="form-check-label  text-dark" for="category">{{translate('messages.category')}}</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-check">
                                    <input type="checkbox" name="modules[]" value="coupon" class="form-check-input"
                                           id="coupon"  {{in_array('coupon',(array)json_decode($role['modules']))?'checked':''}}>
                                    <label class="form-check-label  text-dark" for="coupon">{{translate('messages.coupon')}}</label>
                                </div>
                            </div>
                            {{-- <div class="col-md-3">
                                <div class="form-group form-check">
                                    <input type="checkbox" name="modules[]" value="custom_role" class="form-check-input"
                                           id="custom_role"  {{in_array('custom_role',(array)json_decode($role['modules']))?'checked':''}}>
                                    <label class="form-check-label  text-dark" for="custom_role">{{ translate('messages.employee') }} {{ translate('messages.Role') }}</label>
                                </div>
                            </div> --}}
                            <div class="col-md-3">
                                <div class="form-group form-check">
                                    <input type="checkbox" name="modules[]" value="customerList" class="form-check-input"
                                           id="customerList"  {{in_array('customerList',(array)json_decode($role['modules']))?'checked':''}}>
                                    <label class="form-check-label  text-dark" for="customerList">{{translate('messages.customers')}}  {{translate('messages.section')}}</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-check">
                                    <input type="checkbox" name="modules[]" value="customer_wallet" class="form-check-input"
                                           id="customer_wallet"  {{in_array('customer_wallet',(array)json_decode($role['modules']))?'checked':''}}>
                                    <label class="form-check-label  text-dark" for="customer_wallet">{{translate('messages.customer')}}  {{translate('messages.Wallet')}}</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-check">
                                    <input type="checkbox" name="modules[]" value="deliveryman" class="form-check-input"
                                           id="deliveryman"  {{in_array('deliveryman',(array)json_decode($role['modules']))?'checked':''}}>
                                    <label class="form-check-label  text-dark" for="deliveryman">{{translate('messages.deliveryman')}}</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-check">
                                    <input type="checkbox" name="modules[]" value="provide_dm_earning" class="form-check-input"
                                           id="provide_dm_earning"  {{in_array('provide_dm_earning',(array)json_decode($role['modules']))?'checked':''}}>
                                    <label class="form-check-label  text-dark" for="provide_dm_earning">{{translate('messages.deliverymen_earning_provide')}}</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-check">
                                    <input type="checkbox" name="modules[]" value="employee" class="form-check-input"
                                           id="employee"  {{in_array('employee',(array)json_decode($role['modules']))?'checked':''}}>
                                    <label class="form-check-label  text-dark" for="employee">{{translate('messages.Employee')}}</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-check">
                                    <input type="checkbox" name="modules[]" value="food" class="form-check-input"
                                           id="food"  {{in_array('food',(array)json_decode($role['modules']))?'checked':''}}>
                                    <label class="form-check-label  text-dark" for="food">{{translate('messages.food')}}</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-check">
                                    <input type="checkbox" name="modules[]" value="notification" class="form-check-input"
                                           id="notification"  {{in_array('notification',(array)json_decode($role['modules']))?'checked':''}}>
                                    <label class="form-check-label  text-dark" for="notification">{{translate('messages.push')}} {{translate('messages.notification')}} </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-check">
                                    <input type="checkbox" name="modules[]" value="order" class="form-check-input"
                                           id="order"  {{in_array('order',(array)json_decode($role['modules']))?'checked':''}}>
                                    <label class="form-check-label  text-dark" for="order">{{translate('messages.order')}}</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-check">
                                    <input type="checkbox" name="modules[]" value="restaurant" class="form-check-input"
                                           id="restaurant"  {{in_array('restaurant',(array)json_decode($role['modules']))?'checked':''}}>
                                    <label class="form-check-label  text-dark" for="restaurant">{{translate('messages.restaurants')}}</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-check">
                                    <input type="checkbox" name="modules[]" value="report" class="form-check-input"
                                            id="report"  {{in_array('report',(array)json_decode($role['modules']))?'checked':''}}>
                                    <label class="form-check-label  text-dark" for="report">{{translate('messages.report')}}</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-check">
                                    <input type="checkbox" name="modules[]" value="settings" class="form-check-input"
                                           id="settings"  {{in_array('settings',(array)json_decode($role['modules']))?'checked':''}}>
                                    <label class="form-check-label  text-dark" for="settings">{{translate('messages.business')}} {{translate('messages.settings')}}</label>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group form-check">
                                    <input type="checkbox" name="modules[]" value="withdraw_list" class="form-check-input"
                                            id="withdraw_list"  {{in_array('withdraw_list',(array)json_decode($role['modules']))?'checked':''}}>
                                    <label class="form-check-label  text-dark" for="withdraw_list">{{translate('messages.restaurant')}} {{translate('messages.withdraws')}}</label>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group form-check">
                                    <input type="checkbox" name="modules[]" value="pos" class="form-check-input"
                                            id="pos"  {{in_array('pos',(array)json_decode($role['modules']))?'checked':''}}>
                                    <label class="form-check-label  text-dark" for="pos">{{translate('messages.pos_system')}}</label>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group form-check">
                                    <input type="checkbox" name="modules[]" value="zone" class="form-check-input"
                                           id="zone"  {{in_array('zone',(array)json_decode($role['modules']))?'checked':''}}>
                                    <label class="form-check-label  text-dark" for="zone">{{translate('messages.zone')}}</label>
                                </div>
                            </div>
                            {{-- <div class="col-md-3">
                                <div class="form-group form-check ">
                                    <input type="checkbox" name="modules[]" value="subscription" class="form-check-input"
                                            id="subscription"  {{in_array('subscription',(array)json_decode($role['modules']))?'checked':''}}>
                                    <label class="form-check-label  text-dark" for="subscription">{{translate('messages.subscription')}}</label>
                                </div>
                            </div> --}}
                            <div class="col-md-3">
                                <div class="form-group form-check">
                                    <input type="checkbox" name="modules[]" value="contact_message" class="form-check-input"
                                           id="contact_message"  {{in_array('contact_message',(array)json_decode($role['modules']))?'checked':''}}>
                                    <label class="form-check-label  text-dark" for="contact_message">{{translate('messages.contact_message')}}</label>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">{{translate('messages.update')}}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
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
        if(lang == '{{$default_lang}}')
        {
            $(".from_part_2").removeClass('d-none');
        }
        else
        {
            $(".from_part_2").addClass('d-none');
        }
    });
</script>
@endpush
