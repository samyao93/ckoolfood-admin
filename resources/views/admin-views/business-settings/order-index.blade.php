@extends('layouts.admin.app')

@section('title', translate('Order_Settings'))


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
                                {{translate('Don’t_forget_to_click_the_respective_‘Save_Information’_and_‘Submit’_buttons_below_to_save_changes')}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('admin-views.business-settings.partials.nav-menu')
        </div>

            <form method="post" action="{{ route('admin.business-settings.update-order') }}">
                @csrf
                @php($name = \App\Models\BusinessSetting::where('key', 'business_name')->first())
                <div class="row g-3">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="py-2">
                                    <div class="row g-3 align-items-end">
                                        <div class="col-sm-6 col-lg-4">
                                            @php($odc = \App\Models\BusinessSetting::where('key', 'order_delivery_verification')->first())
                                            @php($odc = $odc ? $odc->value : 0)
                                            <div class="form-group mb-0">

                                                <label
                                                    class="toggle-switch h--45px toggle-switch-sm d-flex justify-content-between border rounded px-3 py-0 form-control">
                                                    <span class="pr-1 d-flex align-items-center switch--label">
                                                        <span class="line--limit-1">
                                                            {{ translate('messages.order') }}
                                                            {{ translate('messages.delivery') }}
                                                            {{ translate('messages.verification') }}
                                                        </span>
                                                        <span class="form-label-secondary text-danger d-flex"
                                                            data-toggle="tooltip" data-placement="right"
                                                            data-original-title="{{ translate('When_a_deliveryman_arrives_for_delivery,_Customers_will_get_a_verification_code_on_the_order_details_section_in_the_Customer_App_and_needs_to_provide_the_code_to_the_delivery_man_to_verify_the_order_delivery') }}"><img
                                                                src="{{ asset('/public/assets/admin/img/info-circle.svg') }}"
                                                                alt="{{ translate('messages.order_varification_toggle') }}">
                                                        </span>
                                                    </span>
                                                    <input type="checkbox" onclick="toogleModal(event,'odc1','order-delivery-verification-on.png','order-delivery-verification-off.png','{{translate('Want_to_enable')}} <strong>{{translate('Delivery_Verification')}}</strong> ?','{{translate('Want_to_disable')}} <strong>{{translate('Delivery_Verification')}}</strong> ?',`<p>{{translate('If_enabled,_the_Deliveryman_has_to_verify_the_order_during_delivery_through_a_4-digit_verification_code')}}</p>`,`<p>{{translate('If_disabled,_Deliveryman_will_deliver_the_food_and_update_the_status_without_using_any_verification_code')}}</p>`)" class="toggle-switch-input" value="1"
                                                        name="odc" id="odc1" {{ $odc == 1 ? 'checked' : '' }}>
                                                    <span class="toggle-switch-label text">
                                                        <span class="toggle-switch-indicator"></span>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-4">
                                            <div class="form-group mb-0">
                                                @php($home_delivery = \App\Models\BusinessSetting::where('key', 'home_delivery')->first())
                                                @php($home_delivery = $home_delivery ? $home_delivery?->value : 0)
                                                <label
                                                    class="toggle-switch h--45px toggle-switch-sm d-flex justify-content-between border rounded px-3 py-0 form-control">
                                                    <span class="pr-1 d-flex align-items-center switch--label">
                                                        <span class="line--limit-1">
                                                            {{ translate('Home Delivery') }}
                                                        </span>
                                                        <span class="form-label-secondary text-danger d-flex"
                                                            data-toggle="tooltip" data-placement="right"
                                                            data-original-title="{{ translate('If_enabled,_customers_can_choose_Home_Delivery_option_from_the_customer_app_and_website') }}"><img
                                                                src="{{ asset('/public/assets/admin/img/info-circle.svg') }}"
                                                                alt="{{ translate('Home Delivery') }}"></span>
                                                    </span>
                                                    <input type="checkbox" onclick="toogleModal(event,'home_delivery','home-delivery-on.png','home-delivery-off.png','{{translate('Want_to_enable')}} <strong>{{translate('Home Delivery')}} ?','{{translate('Want_to_disable')}} <strong>{{translate('Home Delivery')}}</strong> ?',`<p>{{translate('If_enabled,_customers_can_use_Home_Delivery_Option_during_checkout_from_the_Customer_App_or_Website')}}</p>`,`<p>{{translate('If_disabled,_the_Home_Delivery_feature_will_be_hidden_from_the_customer_app_and_website')}}</p>`)" name ="home_delivery" id="home_delivery"
                                                    class="toggle-switch-input" value="1" {{ $home_delivery == 1 ? 'checked' : '' }}>
                                                    <span class="toggle-switch-label text">
                                                        <span class="toggle-switch-indicator"></span>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-4">
                                            <div class="form-group mb-0">
                                                @php($take_away = \App\Models\BusinessSetting::where('key', 'take_away')->first())
                                            @php($take_away = $take_away ? $take_away?->value : 0)
                                                <label
                                                    class="toggle-switch h--45px toggle-switch-sm d-flex justify-content-between border rounded px-3 py-0 form-control">
                                                    <span class="pr-1 d-flex align-items-center switch--label">
                                                        <span class="line--limit-1">
                                                            {{ translate('Takeaway') }}
                                                        </span>
                                                        <span class="form-label-secondary text-danger d-flex"
                                                            data-toggle="tooltip" data-placement="right"
                                                            data-original-title="{{ translate('If_enabled,_customers_can_use_the_Takeaway_feature_during_checkout_from_the_Customer_App_or_Website') }}"><img
                                                                src="{{ asset('/public/assets/admin/img/info-circle.svg') }}"
                                                                alt="{{ translate('Home Delivery') }}"></span>
                                                    </span>
                                                    <input type="checkbox" name="take_away" onclick="toogleModal(event,'take_away','takeaway-on.png','takeaway-off.png','{{translate('Want_to_enable')}} <strong>{{translate('Takeaway')}}</strong> {{translate('feature')}} ?','{{translate('Want_to_disable')}} <strong>{{translate('Takeaway')}}</strong> {{translate('feature')}} ?',`<p>{{translate('If_enabled,_customers_can_use_the_Takeaway_feature_during_checkout_from_the_Customer_App_or_Website.')}}</p>`,`<p>{{translate('If_disabled,_the_Takeaway_feature_will_be_hidden_from_the_Customer_App_or_Website.')}}</p>`)" class="toggle-switch-input" id="take_away"   {{ $take_away == 1 ? 'checked' : '' }} value="1">
                                                    <span class="toggle-switch-label text">
                                                        <span class="toggle-switch-indicator"></span>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-4">
                                            <div class="form-group mb-0">
                                                @php($repeat_order_option = \App\Models\BusinessSetting::where('key', 'repeat_order_option')->first())
                                                @php($repeat_order_option = $repeat_order_option ? $repeat_order_option?->value : 0)
                                                <label
                                                    class="toggle-switch h--45px toggle-switch-sm d-flex justify-content-between border rounded px-3 py-0 form-control" data-toggle="modal" data-target="#repeat-order">
                                                    <span class="pr-1 d-flex align-items-center switch--label">
                                                        <span class="line--limit-1">
                                                            {{ translate('messages.repeat_order_option') }}
                                                        </span>
                                                        <span class="form-label-secondary text-danger d-flex"
                                                            data-toggle="tooltip" data-placement="right"
                                                            data-original-title="{{ translate('If_enabled,_customers_can_re-order_foods_from_their_previous_orders.') }}"><img
                                                                src="{{ asset('/public/assets/admin/img/info-circle.svg') }}"
                                                                alt="{{ translate('messages.repeat_order_option') }}">  </span>
                                                    </span>
                                                    <input type="checkbox" class="toggle-switch-input" id="repeat_order_option"
                                                    name="repeat_order_option" value="1"
                                                    onclick="toogleModal(event,'repeat_order_option','home-delivery-on.png','home-delivery-off.png','{{translate('Want_to_enable')}} <strong>{{translate('repeat_order')}}</strong> {{translate('feature')}} ?','{{translate('Want_to_disable')}} <strong>{{translate('repeat_order')}}</strong> {{translate('feature')}} ?',`<p>{{translate('If_enabled,_customers_can_order_again_from_their_previous_order_history.')}}</p>`,`<p>{{translate('If_disabled,_customers_won’t_find_any_re-order_button_in_the_order_history.')}}</p>`)"
                                                    {{ $repeat_order_option == 1 ? 'checked' : '' }}
                                                    >
                                                    <span class="toggle-switch-label text">
                                                        <span class="toggle-switch-indicator"></span>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-4">
                                            <div class="form-group mb-0">
                                                @php($order_subscription = \App\Models\BusinessSetting::where('key', 'order_subscription')->first())
                                                @php($order_subscription = $order_subscription ? $order_subscription?->value : 0)
                                                <label
                                                    class="toggle-switch h--45px toggle-switch-sm d-flex justify-content-between border rounded px-3 py-0 form-control" data-toggle="modal" data-target="#repeat-order">
                                                    <span class="pr-1 d-flex align-items-center switch--label">
                                                        <span class="line--limit-1">
                                                            {{ translate('messages.subscription_order') }}
                                                        </span>
                                                        <span class="form-label-secondary text-danger d-flex"
                                                            data-toggle="tooltip" data-placement="right"
                                                            data-original-title="{{ translate('If_enabled,_costumes_can_place_orders_on_a_subscription-based.')}}"><img
                                                                src="{{ asset('/public/assets/admin/img/info-circle.svg') }}"
                                                                alt="{{ translate('messages.subscription_order') }}">  </span>
                                                    </span>
                                                    <input type="checkbox" class="toggle-switch-input" id="subscription_order"
                                                    name="order_subscription" value="1"
                                                    onclick="toogleModal(event,'subscription_order','home-delivery-on.png','home-delivery-off.png','{{translate('Want_to_enable')}} <strong>{{translate('Subscription')}}</strong> {{translate('feature')}} ?','{{translate('Want_to_disable')}} <strong>{{translate('Subscription')}}</strong> {{translate('feature')}} ?',`<p>{{translate('If_enabled,_customers_can_order_food_on_a_subscription_basis._Customers_can_select_time_with_the_delivery_slot_from_the_calendar_to_their_preferences.')}}</p>`,`<p>{{translate('If_disabled,_customers_won’t_be_able_to_order_food_on_a_subscription-based.')}}</p>`)"
                                                    {{ $order_subscription == 1 ? 'checked' : '' }}
                                                    >
                                                    <span class="toggle-switch-label text">
                                                        <span class="toggle-switch-indicator"></span>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-4">
                                            @php($schedule_order = \App\Models\BusinessSetting::where('key', 'schedule_order')->first())
                                            @php($schedule_order = $schedule_order ? $schedule_order->value : 0)
                                            <div class="form-group mb-0">
                                                <label
                                                    class="toggle-switch h--45px toggle-switch-sm d-flex justify-content-between border rounded px-3 py-0 form-control">
                                                    <span class="pr-1 d-flex align-items-center switch--label">
                                                        <span class="line--limit-1">
                                                            {{ translate('messages.scheduled') }}
                                                            {{ translate('messages.Delivery') }}
                                                        </span>
                                                        <span class="form-label-secondary text-danger d-flex"
                                                            data-toggle="tooltip" data-placement="right"
                                                            data-original-title="{{ translate('With_this_feature,_customers_can_choose_their_preferred_delivery_time_and_calendar_selection.') }}"><img
                                                                src="{{ asset('/public/assets/admin/img/info-circle.svg') }}"
                                                                alt="{{ translate('messages.customer_varification_toggle') }}">
                                                        </span>
                                                    </span>
                                                    <input type="checkbox" onclick="toogleModal(event,'schedule_order','schedule-on.png','schedule-off.png','{{translate('Want_to_enable')}} <strong>{{translate('Schedule Delivery')}}</strong> ?','{{translate('Want_to_disable')}} <strong>{{translate('Schedule Delivery')}}</strong> ?',`<p>{{translate('If_enabled,_customers_can_choose_a_suitable_delivery_schedule_during_checkout.')}}</p>`,`<p>{{translate('If_disabled,_the_Scheduled_Delivery_feature_will_be_hidden.')}}</p>`)" class="toggle-switch-input" value="1"
                                                        name="schedule_order" id="schedule_order"
                                                        {{ $schedule_order == 1 ? 'checked' : '' }}>
                                                    <span class="toggle-switch-label text">
                                                        <span class="toggle-switch-indicator"></span>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-lg-4">
                                            @php($canceled_by_restaurant = \App\Models\BusinessSetting::where('key', 'canceled_by_restaurant')->first())
                                            @php($canceled_by_restaurant = $canceled_by_restaurant ? $canceled_by_restaurant->value : 0)
                                            <div class="form-group mb-0">
                                                <label class="input-label text-capitalize d-flex alig-items-center"><span class="line--limit-1">{{ translate('restaurant_can_cancel_order') }} </span><span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{translate('If_yes,_restaurants_can_cancel_orders.')}}">
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
                                                        {{ $canceled_by_restaurant == 0 ? 'checked' : '' }} >
                                                        <span class="form-check-label">
                                                            {{ translate('no') }}
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-4">
                                            @php($canceled_by_deliveryman = \App\Models\BusinessSetting::where('key', 'canceled_by_deliveryman')->first())
                                            @php($canceled_by_deliveryman = $canceled_by_deliveryman ? $canceled_by_deliveryman->value : 0)
                                            <div class="form-group mb-0">
                                                <label class="input-label text-capitalize d-flex alig-items-center"><span class="line--limit-1">{{translate('Delivery Man can Cancel Order')}}</span> <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{translate('If_yes,_deliveryman_can_cancel_orders.')}}">
                                                    <i class="tio-info-outined"></i>
                                                    </span></label>
                                                <div class="resturant-type-group border">
                                                    <label class="form-check form--check mr-2 mr-md-4">
                                                        <input class="form-check-input" type="radio" value="1"
                                                        name="canceled_by_deliveryman" id="canceled_by_deliveryman"
                                                        {{ $canceled_by_deliveryman == 1 ? 'checked' : '' }}>
                                                        <span class="form-check-label">
                                                            {{ translate('yes') }}
                                                        </span>
                                                    </label>
                                                    <label class="form-check form--check mr-2 mr-md-4">
                                                        <input class="form-check-input" type="radio" value="0"
                                                        name="canceled_by_deliveryman" id="canceled_by_deliveryman2"
                                                        {{ $canceled_by_deliveryman == 0 ? 'checked' : '' }}>
                                                        <span class="form-check-label">
                                                            {{ translate('no') }}
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-lg-4">
                                            @php($order_confirmation_model = \App\Models\BusinessSetting::where('key', 'order_confirmation_model')->first())
                                            @php($order_confirmation_model = $order_confirmation_model ? $order_confirmation_model?->value : 'deliveryman')
                                            <div class="form-group mb-0">
                                                <label class="input-label text-capitalize d-flex alig-items-center"><span class="line--limit-1">{{ translate('messages.order_confirmation_model') }}</span> <span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="{{translate('The_chosen_confirmation_model_will_confirm_the_order_first._For_example,_if_the_deliveryman_confirmation_model_is_enabled,_deliverymen_will_receive_and_confirm_orders_before_restaurants._After_that,_restaurants_will_get_orders_and_process_them.')}}">
                                                    <i class="tio-info-outined"></i>
                                                    </span></label>
                                                <div class="resturant-type-group border">
                                                    <label class="form-check form--check mr-2 mr-md-4">
                                                        <input class="form-check-input" type="radio" value="restaurant"
                                                        name="order_confirmation_model" id="order_confirmation_model"
                                                        {{ $order_confirmation_model == 'restaurant' ? 'checked' : '' }}>
                                                        <span class="form-check-label">
                                                            {{ translate('messages.restaurant') }}
                                                        </span>
                                                    </label>
                                                    <label class="form-check form--check mr-2 mr-md-4">
                                                        <input class="form-check-input" type="radio" value="deliveryman"
                                                        name="order_confirmation_model" id="order_confirmation_model2"
                                                        {{ $order_confirmation_model == 'deliveryman' ? 'checked' : '' }}>
                                                        <span class="form-check-label">
                                                            {{ translate('messages.deliveryman') }}
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-lg-4">
                                            @php($schedule_order_slot_duration = \App\Models\BusinessSetting::where('key', 'schedule_order_slot_duration')->first())
                                            @php($schedule_order_slot_duration_time_formate = \App\Models\BusinessSetting::where('key', 'schedule_order_slot_duration_time_formate')->first())
                                            <div class="form-group mb-0">
                                                <label class="input-label text-capitalize d-flex alig-items-center"
                                                    for="schedule_order_slot_duration">
                                                    <span class="pr-1 d-flex align-items-center switch--label">
                                                        <span class="line--limit-1">
                                                            {{ translate('Time_Interval_for_Scheduled_Delivery') }}
                                                        </span>
                                                        <span class="form-label-secondary text-danger"
                                                        data-toggle="tooltip" data-placement="right"
                                                        data-original-title="{{ translate('By_activating_this_feature,_customers_can_choose_their_suitable_delivery_slot_according_to_a_30-minute_or_1-hour_interval_set_by_the_Admin') }}"><img
                                                            src="{{ asset('/public/assets/admin/img/info-circle.svg') }}"
                                                            alt="{{ translate('Time_Interval_for_Scheduled_Delivery') }}"></span>
                                                    </span>
                                                </label>
                                                <div class="d-flex">
                                                    <input type="number"  name="schedule_order_slot_duration" class="form-control mr-3"
                                                    id="schedule_order_slot_duration"
                                                    value="{{ $schedule_order_slot_duration?->value ? $schedule_order_slot_duration_time_formate?->value == 'hour' ? $schedule_order_slot_duration?->value /60 : $schedule_order_slot_duration?->value: 0 }}"
                                                    min="0" required>
                                                    <select  name="schedule_order_slot_duration_time_formate" class="custom-select form-control w-90px">
                                                        <option  value="min" {{ $schedule_order_slot_duration_time_formate?->value == 'min'? 'selected' : '' }}>{{ translate('Min') }}</option>
                                                        <option  value="hour" {{ $schedule_order_slot_duration_time_formate?->value == 'hour'? 'selected' : ''}}>{{ translate('Hour') }}</option>
                                                    </select>
                                                </div>
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
                </div>

            </form>


            <form action="{{ route('admin.order-cancel-reasons.store') }}" method="post">
                @csrf
                <div class="mt-4">
                    <h4 class="card-title mb-3">
                        <i class="tio-document-text-outlined mr-1"></i>
                        {{translate('Order Cancelation Messages')}}
                    </h4>
                    <div class="card">
                        <div class="card-body">
                        @php($language=\App\Models\BusinessSetting::where('key','language')->first())
                        @php($language = $language->value ?? null)
                        @php($default_lang = str_replace('_', '-', app()->getLocale()))
                        @if($language)
                        <ul class="nav nav-tabs  mb-3 border-0">
                            <li class="nav-item">
                                <a class="nav-link lang_link1 active"
                                href="#"
                                id="default-link1">{{ translate('Default') }}</a>
                            </li>
                            @foreach (json_decode($language) as $lang)
                                <li class="nav-item">
                                    <a class="nav-link lang_link1"
                                        href="#"
                                        id="{{ $lang }}-link1">{{ \App\CentralLogics\Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')' }}</a>
                                </li>
                            @endforeach
                        </ul>
                        @endif
                        <div class="row g-3">
                                <div class="col-sm-6 lang_form1 default-form1">
                                    <label class="form-label">{{translate('Order Cancellation Reason')}}  ({{translate('messages.default')}})</label>
                                        <input type="text" maxlength="191" class="form-control h--45px" name="reason[]" id="order_cancellation"
                                            placeholder="{{ translate('Ex:_Item_is_Broken') }}" >
                                        <input type="hidden" name="lang[]" value="default">
                                </div>
                                @if ($language)
                                    @foreach(json_decode($language) as $lang)
                                        <div class="col-sm-6 d-none lang_form1" id="{{$lang}}-form1">
                                            <label class="form-label">{{translate('Order Cancellation Reason')}} ({{strtoupper($lang)}})</label>
                                            <input type="text" maxlength="191" class="form-control h--45px" name="reason[]" id="order_cancellation"
                                                    placeholder="{{ translate('Ex:_Item_is_Broken') }}">
                                            <input type="hidden" name="lang[]" value="{{$lang}}">
                                        </div>
                                    @endforeach
                                @endif
                                <div class="col-sm-6">
                                    <label class="form-label">
                                        <span class="line--limit-1">{{translate('User Type')}} </span>
                                            <span class="form-label-secondary text-danger d-flex"
                                            data-toggle="tooltip" data-placement="right"
                                            data-original-title="{{ translate('Choose_different_Customers_for_different_Order_Cancelation_Reasons,_such_as_Customer,_Restaurant,_Deliveryman,_and_Admin') }}"><img
                                                src="{{ asset('/public/assets/admin/img/info-circle.svg') }}"
                                                alt="{{ translate('messages.prescription_order_status') }}"></span>
                                    </label>
                                    <select name="user_type" class="form-control h--45px"
                                        required>
                                        <option value="">{{ translate('messages.select_user_type') }}</option>
                                        <option value="admin">{{ translate('messages.admin') }}</option>
                                        <option value="restaurant">{{ translate('messages.restaurant') }}</option>
                                        <option value="customer">{{ translate('messages.customer') }}</option>
                                        <option value="deliveryman">{{ translate('messages.deliveryman') }}</option>
                                    </select>
                                </div>
                            </div>
                            <p class="mt-2 ml-1">
                                {{ translate('*_PLEASE_NOTE:_Customers_cannot_cancel_an_order_if_the_Admin_does_not_specify_a_cause_for_cancelation,_even_though_they_see_the_Cancel_Order_option._So_Admin_MUST_provide_a_proper_Order_Cancelation_Reason_and_select_the_related_Customer.') }}
                            </p>
                            <div class="btn--container justify-content-end mt-3 mb-4">
                                <button type="reset" class="btn btn--reset">{{ translate('messages.reset') }}</button>
                                <button type="{{ env('APP_MODE') != 'demo' ? 'submit' : 'button' }}"
                                    onclick="{{ env('APP_MODE') != 'demo' ? '' : 'call_demo()' }}"
                                    class="btn btn--primary">{{ translate('Submit') }}</button>
                            </div>
                        </form>
                            <div class="card">
                                <div class="card-body mb-3">
                                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-md-0 mb-3">
                                        <div class="mx-1">
                                            <h5 class="form-label mb-4">
                                                {{ translate('messages.order_cancellation_reason_list') }}
                                            </h5>
                                        </div>
                                    </div>

                                    <!-- Table -->
                                    <div class="card-body p-0">
                                        <div class="table-responsive datatable-custom">
                                            <table id="columnSearchDatatable"
                                                class="table table-borderless table-thead-bordered table-align-middle"
                                                data-hs-datatables-options='{
                                            "isResponsive": false,
                                            "isShowPaging": false,
                                            "paging":false,
                                        }'>
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th class="border-0">{{ translate('messages.SL') }}</th>
                                                        <th class="border-0">{{ translate('messages.Reason') }}</th>
                                                        <th class="border-0">{{ translate('messages.type') }}</th>
                                                        <th class="border-0">{{ translate('messages.status') }}</th>
                                                        <th class="border-0 text-center">{{ translate('messages.action') }}</th>
                                                    </tr>
                                                </thead>

                                                <tbody id="table-div">
                                                    @foreach ($reasons as $key => $reason)
                                                        <tr>
                                                            <td>{{ $key + $reasons->firstItem() }}</td>

                                                            <td>
                                                                <span class="d-block font-size-sm text-body">
                                                                    {{ Str::limit($reason->reason, 25, '...') }}
                                                                </span>
                                                            </td>
                                                            <td>{{ translate($reason->user_type) }}</td>
                                                            <td>
                                                                <label class="toggle-switch toggle-switch-sm"
                                                                    for="stocksCheckbox{{ $reason->id }}">
                                                                    <input type="checkbox"
                                                                        onclick="location.href='{{ route('admin.order-cancel-reasons.status', [$reason['id'], $reason->status ? 0 : 1]) }}'"class="toggle-switch-input"
                                                                        id="stocksCheckbox{{ $reason->id }}"
                                                                        {{ $reason->status ? 'checked' : '' }}>
                                                                    <span class="toggle-switch-label">
                                                                        <span class="toggle-switch-indicator"></span>
                                                                    </span>
                                                                </label>
                                                            </td>

                                                            <td>
                                                                <div class="btn--container justify-content-center">
                                                                    <a class="btn btn-sm btn--primary btn-outline-primary action-btn"
                                                                    title="{{ translate('messages.edit') }}" onclick="edit_reason('{{$reason['id']}}')"
                                                                     data-toggle="modal"   data-target="#add_update_reason_{{$reason->id}}"
                                                                    ><i class="tio-edit"></i>
                                                                    </a>

                                                                    <a class="btn btn-sm btn--danger btn-outline-danger action-btn"
                                                                        href="javascript:"
                                                                        onclick="form_alert('order-cancellation-reason-{{ $reason['id'] }}','{{ translate('messages.If_you_want_to_delete_this_reason,_please_confirm_your_decision.') }}')"
                                                                        title="{{ translate('messages.delete') }}">
                                                                        <i class="tio-delete-outlined"></i>
                                                                    </a>
                                                                    <form
                                                                        action="{{ route('admin.order-cancel-reasons.destroy', [$reason['id']]) }}"
                                                                        method="post" id="order-cancellation-reason-{{ $reason['id'] }}">
                                                                        @csrf @method('delete')
                                                                    </form>
                                                                </div>
                                                            </td>
                                                        </tr>

                                                        <!-- Modal -->
                                                        <div class="modal fade" id="add_update_reason_{{$reason->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLabel">{{ translate('messages.order_cancellation_reason') }}
                                                                            {{ translate('messages.Update') }}</label></h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <form action="{{ route('admin.order-cancel-reasons.update') }}" method="post">
                                                                            @csrf
                                                                            @method('put')

                                                                            @php($reason=  \App\Models\OrderCancelReason::withoutGlobalScope('translate')->with('translations')->find($reason->id))
                                                                            @php($language=\App\Models\BusinessSetting::where('key','language')->first())
                                                                        @php($language = $language->value ?? null)
                                                                        @php($default_lang = str_replace('_', '-', app()->getLocale()))
                                                                        <ul class="nav nav-tabs nav--tabs mb-3 border-0">
                                                                            <li class="nav-item">
                                                                                <a class="nav-link lang_link add_active active"
                                                                                href="#"
                                                                                id="default-link">{{ translate('Default') }}</a>
                                                                            </li>
                                                                            @if($language)
                                                                            @foreach (json_decode($language) as $lang)
                                                                                <li class="nav-item">
                                                                                    <a class="nav-link lang_link"
                                                                                        href="#"
                                                                                        id="{{ $lang }}-link">{{ \App\CentralLogics\Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')' }}</a>
                                                                                </li>
                                                                            @endforeach
                                                                            @endif
                                                                        </ul>
                                                                            <input type="hidden" name="reason_id"  value="{{$reason->id}}" />

                                                                            <div class="form-group mb-3 add_active_2  lang_form" id="default-form_{{$reason->id}}">
                                                                                <label class="form-label">{{translate('Order Cancellation Reason')}} ({{translate('messages.default')}}) </label>
                                                                                <input class="form-control" maxlength="191" name='reason[]' value="{{$reason->getRawOriginal('reason')}}"  type="text">
                                                                                <input type="hidden" name="lang1[]" value="default">
                                                                            </div>
                                                                                            @if($language)
                                                                                                @forelse(json_decode($language) as $lang)
                                                                                                <?php
                                                                                                    if($reason?->translations){
                                                                                                        $translate = [];
                                                                                                        foreach($reason?->translations as $t)
                                                                                                        {
                                                                                                            if($t->locale == $lang && $t->key=="reason"){
                                                                                                                $translate[$lang]['reason'] = $t->value;
                                                                                                            }
                                                                                                        }
                                                                                                    }

                                                                                                    ?>
                                                                                                    <div class="form-group mb-3 d-none lang_form" id="{{$lang}}-form_{{$reason->id}}">
                                                                                                        <label class="form-label">{{translate('Order Cancellation Reason')}} ({{strtoupper($lang)}})</label>
                                                                                                        <input class="form-control" name='reason[]' placeholder="{{ translate('Ex:_Item_is_Broken') }}" value="{{ $translate[$lang]['reason'] ?? null }}" maxlength="191"  type="text">
                                                                                                        <input type="hidden" name="lang1[]" value="{{$lang}}">
                                                                                                    </div>
                                                                                                    @empty
                                                                                                    @endforelse
                                                                                                    @endif

                                                                            <select name="user_type" required class="form-control h--45px">
                                                                                <option value="">{{ translate('messages.select_user_type') }}</option>
                                                                                <option {{ $reason->user_type == 'admin' ? 'selected': '' }} value="admin">{{ translate('messages.admin') }}</option>
                                                                                <option {{ $reason->user_type == 'restaurant' ? 'selected': '' }} value="restaurant">{{ translate('messages.restaurant') }}</option>
                                                                                <option {{ $reason->user_type == 'customer' ? 'selected': '' }} value="customer">{{ translate('messages.customer') }}</option>
                                                                                <option {{ $reason->user_type == 'deliveryman' ? 'selected': '' }} value="deliveryman">{{ translate('messages.deliveryman') }}</option>
                                                                            </select>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ translate('Close') }}</button>
                                                                        <button type="submit" class="btn btn-primary">{{ translate('Save_changes') }}</button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    @endforeach
                                                </tbody>
                                            </table>
                                            @if(count($reasons) === 0)
                                            <div class="empty--data">
                                                <img src="{{asset('/public/assets/admin/img/empty.png')}}" alt="public">
                                                <h5>
                                                    {{translate('no_data_found')}}
                                                </h5>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="card-footer pt-0 border-0">
                                            <div class="page-area px-4 pb-3">
                                                <div class="d-flex align-items-center justify-content-end">
                                                    <div>
                                                        {!! $reasons->links() !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                    <!-- End Table -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>





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

@endsection

@push('script_2')
<script>
     function edit_reason(){
            $(".add_active").addClass('active');
            $(".lang_form").addClass('d-none');
            $(".add_active_2").removeClass('d-none');
        }

    $(".lang_link").click(function(e){
        e.preventDefault();
        $(".lang_link").removeClass('active');
        $(".lang_form").addClass('d-none');
        $(".add_active").removeClass('active');
        $(this).addClass('active');

        let form_id = this.id;
        let lang = form_id.substring(0, form_id.length - 5);

        console.log(lang);

        // $("#"+lang+"-form").removeClass('d-none');

        @foreach ( $reasons as $reason )
        $("#"+lang+"-form_{{ $reason->id }}").removeClass('d-none');
        @endforeach
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

    $(".lang_link1").click(function(e){
        e.preventDefault();
        $(".lang_link1").removeClass('active');
        $(".lang_form1").addClass('d-none');
        $(this).addClass('active');
        let form_id = this.id;
        let lang = form_id.substring(0, form_id.length - 6);
        $("#"+lang+"-form1").removeClass('d-none');
            if(lang == 'default')
        {
            $(".default-form1").removeClass('d-none');
        }
    })
</script>
@endpush
