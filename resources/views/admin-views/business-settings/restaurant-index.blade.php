@extends('layouts.admin.app')

@section('title', translate('Restaurant_setup'))


@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex flex-wrap justify-content-between align-items-start">
                <h1 class="page-header-title mr-3">
                    <span class="page-header-icon">
                        <img src="{{ asset('public/assets/admin/img/business.png') }}" class="w--20" alt="">
                    </span>
                    <span>
                        {{ translate('messages.business') }} {{ translate('messages.setup') }}
                    </span>
                </h1>
                <div class="d-flex flex-wrap justify-content-end align-items-center flex-grow-1">
                    <div class="blinkings active">
                        <i class="tio-info-outined"></i>
                        <div class="business-notes">
                            <h6><img src="{{asset('/public/assets/admin/img/notes.png')}}" alt=""> {{translate('Note')}}</h6>
                            <div>
                                {{translate('Don’t_forget_to_click_the_respective_‘Save_Information’_buttons_below_to_save_changes')}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('admin-views.business-settings.partials.nav-menu')
        </div>
        <form action="{{ route('admin.business-settings.update-restaurant') }}" method="post" enctype="multipart/form-data">
            @csrf
            @php($name = \App\Models\BusinessSetting::where('key', 'business_name')->first())

            <div class="row g-3">
                @php($default_location = \App\Models\BusinessSetting::where('key', 'default_location')->first())
                @php($default_location = $default_location->value ? json_decode($default_location->value, true) : 0)
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row g-3 align-items-end">
                                <div class="col-lg-4 col-sm-6">
                                    @php($canceled_by_restaurant = \App\Models\BusinessSetting::where('key', 'canceled_by_restaurant')->first())
                                    @php($canceled_by_restaurant = $canceled_by_restaurant ? $canceled_by_restaurant->value : 0)
                                    <div class="form-group mb-0">
                                        <label class="input-label text-capitalize d-flex alig-items-center"><span
                                                class="line--limit-1">{{ translate('Can_a_Restaurant_Cancel_Order') }}
                                            </span><span class="input-label-secondary text--title" data-toggle="tooltip"
                                                data-placement="right"
                                                data-original-title="{{ translate('Admin_can_enable/disable_restaurants’_order_cancellation_option.') }}">
                                                <i class="tio-info-outined"></i>
                                            </span></label>
                                        <div class="resturant-type-group border">
                                            <label class="form-check form--check mr-2 mr-md-4">
                                                <input class="form-check-input" type="radio" value="1"
                                                    name="canceled_by_restaurant" id="canceled_by_restaurant"
                                                    {{ $canceled_by_restaurant == 1 ? 'checked' : '' }}>
                                                <span class="form-check-label">
                                                    {{ translate('yes') }}
                                                </span>
                                            </label>
                                            <label class="form-check form--check mr-2 mr-md-4">
                                                <input class="form-check-input" type="radio" value="0"
                                                    name="canceled_by_restaurant" id="canceled_by_restaurant2"
                                                    {{ $canceled_by_restaurant == 0 ? 'checked' : '' }}>
                                                <span class="form-check-label">
                                                    {{ translate('no') }}
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6">
                                    @php($restaurant_self_registration = \App\Models\BusinessSetting::where('key', 'toggle_restaurant_registration')->first())
                                    @php($restaurant_self_registration = $restaurant_self_registration ? $restaurant_self_registration->value : 0)
                                    <div class="form-group mb-0">

                                        <label
                                            class="toggle-switch h--45px toggle-switch-sm d-flex justify-content-between border rounded px-3 py-0 form-control">
                                            <span class="pr-1 d-flex align-items-center switch--label">
                                                <span class="line--limit-1">
                                                    {{ translate('messages.restaurant_self_registration') }}
                                                </span>
                                                <span class="form-label-secondary text-danger d-flex"
                                                    data-toggle="tooltip" data-placement="right"
                                                    data-original-title="{{ translate('If_enabled,_a_restaurant_can_send_a_registration_request_through_their_restaurant_or_customer_app,_website,_or_admin_landing_page._The_admin_will_receive_an_email_notification_and_can_accept_or_reject_the_request') }}"><img
                                                        src="{{ asset('/public/assets/admin/img/info-circle.svg') }}"
                                                        alt="{{ translate('messages.restaurant_self_registration') }}"> *
                                                </span>
                                            </span>
                                            <input type="checkbox" onclick="toogleModal(event,'restaurant_self_registration1','store-self-reg-on.png','store-self-reg-off.png','{{translate('Want_to_enable')}} <strong>{{translate('restaurant Self Registration')}}</strong> ?','{{translate('Want_to_disable')}} <strong>{{translate('restaurant Self Registration')}}</strong>  ?',`<p>{{translate('If_enabled,_restaurants_can_do_self-registration_from_the_restaurant_or_customer_app_or_website')}}</p>`,`<p>{{translate('If_disabled,_the_restaurant_Self-Registration_feature_will_be_hidden_from_the_restaurant_or_customer_app,_website,_and_admin_landing_page')}}</p>`)" class="toggle-switch-input" value="1"
                                                name="restaurant_self_registration" id="restaurant_self_registration1"
                                                {{ $restaurant_self_registration == 1 ? 'checked' : '' }}>
                                            <span class="toggle-switch-label text">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="btn--container justify-content-end mt-3">
                                <button type="reset" class="btn btn--reset">{{ translate('messages.reset') }}</button>
                                <button type="{{ env('APP_MODE') != 'demo' ? 'submit' : 'button' }}"
                                    onclick="{{ env('APP_MODE') != 'demo' ? '' : 'call_demo()' }}"
                                    class="btn btn--primary">{{ translate('save_information') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </form>
    </div>

@endsection

@push('script_2')

@endpush
