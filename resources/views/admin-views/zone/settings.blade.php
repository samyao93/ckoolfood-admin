@extends('layouts.admin.app')
@section('title', translate('messages.zone_settings'))
@push('css_or_js')
    <style>
    .div_size{
        margin-inline-end: 60px;
    }

    </style>
@endpush
@section('content')



{{--  --}}
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header pb-0">
            <div class="d-flex flex-wrap justify-content-between align-items-start">
                <div class="d-flex align-items-start __gap-12px">
                    <img src="{{asset('/public/assets/admin/img/zone.png')}}" alt="">
                    <div>
                        <h1 class="page-header-title text-capitalize">
                            {{translate('messages.Business_Zone')}}  {{ translate('messages.settings') }} : {{ $zone->name }}
                        </h1>
                        <p>
                            {{translate('messages.Set_zone-wise_delivery_fees_and_incentives')}}
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
        <form action="{{ route('admin.zone.zone_settings_update', $zone->id) }}" method="post" class="card p-0 border-0 shadow--card">
            @csrf
            <div class="card-header">
                <h5 class="card-title align-items-center">
                    <span class="card-header-icon mr-2">
                        <i class="tio-settings-outlined"></i>
                    </span>
                    <span>{{translate('Delivery Charges Settings')}}</span> &nbsp;
                    <img src="{{asset('/public/assets/admin/img/info-circle.svg')}}" data-toggle="tooltip" title="{{ translate('messages.Set_zone_wise_delivery_charges_for_this_business_zone')}}" alt="">
                </h5>
            </div>
            <div class="card-body zone-setup">
                <div class="row">
                    <div class="col-sm-6 col-lg-4">
                        <div class="form-group">
                            <label class="input-label text-capitalize d-inline-flex alig-items-center">
                                {{ translate('messages.minimum_delivery_charge') }}
                                ({{ \App\CentralLogics\Helpers::currency_symbol() }})&nbsp;
                                <span data-toggle="tooltip" data-placement="right"
                                    data-original-title="{{ translate('messages.Set_the_minimum_delivery_for_each_order_in_this_business_zone.')}}"
                                    class="input-label-secondary text-danger"><img
                                        src="{{ asset('/public/assets/admin/img/info-circle.svg') }}"
                                        alt="{{ translate('messages.maximum_shipping_charge') }}"></span>
                            </label>
                            <input id="min_delivery_charge" name="minimum_delivery_charge" type="number"
                                min=".001" step=".001" class="form-control h--45px" required
                                placeholder="{{ translate('messages.Ex :') }} 10"
                                value="{{ $zone->minimum_shipping_charge }}">
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4">
                        <div class="form-group">
                            <label class="input-label text-capitalize d-inline-flex alig-items-center">
                                {{ translate('messages.maximum_delivery_charge') }}
                                ({{ \App\CentralLogics\Helpers::currency_symbol() }})&nbsp;
                                <span data-toggle="tooltip" data-placement="right"
                                    data-original-title="{{ translate('messages.Set_the_maximum_limit_for_the_total_delivery_charge._If_the_delivery_charge_crosses_the_limit,_it_will_not_add_any_extra_charge._Leave_it_empty_if_you_don’t_want_to_limit_the_delivery_charge.')}}"
                                    class="input-label-secondary text-danger"><img
                                        src="{{ asset('/public/assets/admin/img/info-circle.svg') }}"
                                        alt="{{ translate('messages.maximum_shipping_charge') }}"></span>
                            </label>
                            <input id="maximum_shipping_charge" name="maximum_shipping_charge" type="number"
                                class="form-control h--45px"
                                placeholder="{{ translate('messages.Ex :') }} 10000" min="0"
                                step=".001" value="{{ $zone->maximum_shipping_charge ?? '' }}">
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4">
                        <div class="form-group">
                            <label class="input-label text-capitalize d-inline-flex alig-items-center">
                                {{ translate('messages.delivery_charge_per_km') }}
                                ({{ \App\CentralLogics\Helpers::currency_symbol() }})&nbsp;
                                <span data-toggle="tooltip" data-placement="right"
                                data-original-title="{{ translate('messages.Set_a_delivery_charge_for_each_kilometer_for_this_business_zone.')}}"
                                class="input-label-secondary "><img
                                    src="{{ asset('/public/assets/admin/img/info-circle.svg') }}"
                                    alt="{{ translate('messages.maximum_shipping_charge') }}"></span>
                            </label>
                            <input id="delivery_charge_per_km" name="per_km_delivery_charge" type="number"
                                min=".001" step=".001" class="form-control h--45px" required
                                placeholder="{{ translate('messages.Ex :') }} 10"
                                value="{{ $zone->per_km_shipping_charge }}">
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4">
                        <div class="form-group">
                            <label class="input-label text-capitalize d-inline-flex alig-items-center">
                                {{ translate('messages.maximum_COD_order_amount') }}
                                ({{ \App\CentralLogics\Helpers::currency_symbol() }})&nbsp;
                                <span data-toggle="tooltip" data-placement="right"
                                    data-original-title="{{ translate('messages.Add_the_maximum_Cash_On_Delivery_order_limit_for_this_business_zone._Leave_it_empty_if_you_don’t_want_to_limit_the_COD_order_amount') }}"
                                    class="input-label-secondary"><img
                                        src="{{ asset('/public/assets/admin/img/info-circle.svg') }}"
                                        alt="{{ translate('messages.max_cod_order_amount_status') }}"></span>
                            </label>
                            <input id="max_cod_order_amount" name="max_cod_order_amount" min="0"
                                step=".001" type="number" class="form-control h--45px"
                                placeholder="{{ translate('messages.Ex :') }} 100000"
                                value="{{ $zone->max_cod_order_amount ?? '' }}">
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4">
                        <div class="form-group">
                            <div class="d-flex justify-content-between align-items-center">
                                <label class="input-label text-capitalize d-inline-flex alig-items-center"
                                    for="increased_delivery_fee">
                                    <span class="line--limit-1">{{ translate('messages.increase_delivery_charge') }} (%)
                                    <span data-toggle="tooltip" data-placement="right" data-original-title="{{translate('messages.Set_an_additional_delivery_charge_in_percentage_for_any_emergency_situations._This_amount_will_be_added_to_the_delivery_charge.')}}" class="input-label-secondary"><img src="{{ asset('/public/assets/admin/img/info-circle.svg') }}" alt="{{ translate('messages.dm_maximum_order_hint') }}"></span>
                                </label>
                                <label class="toggle-switch toggle-switch-sm">
                                    <input type="checkbox" class="toggle-switch-input" name="increased_delivery_fee_status"
                                        id="increased_delivery_fee_status" value="1"
                                        {{ $zone->increased_delivery_fee_status == 1 ? 'checked' : '' }}>
                                        <span class="toggle-switch-label">
                                            <div class="toggle-switch-indicator"></div>
                                        </span>
                                </label>
                            </div>
                            <input type="number" name="increased_delivery_fee" class="form-control"
                                id="increased_delivery_fee"
                                value="{{ $zone->increased_delivery_fee ? $zone->increased_delivery_fee : '' }}" min="0"
                                step=".001" placeholder="{{ translate('messages.Ex :') }} 10" {{ ($zone->increased_delivery_fee_status == 1) ? ' ' : 'readonly' }}>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4">
                        <div class="form-group">
                            <div class="d-flex justify-content-between align-items-center">
                                <label class="input-label text-capitalize d-inline-flex alig-items-center"
                                    for="increased_delivery_fee">
                                    <span class="line--limit-1">{{ translate('messages.increase_delivery_charge_message') }}
                                        <span data-toggle="tooltip" data-placement="right" data-original-title="{{translate('messages.Customers_will_see_the_delivery_charge_increased_reason_on_the_website_and_customer_app.')}}" class="input-label-secondary"><img src="{{ asset('/public/assets/admin/img/info-circle.svg') }}" alt="{{ translate('messages.dm_maximum_order_hint') }}"></span>

                                </label>
                            </div>
                            <input type="text" name="increase_delivery_charge_message" class="form-control"
                                id="increase_delivery_charge_message"
                                value="{{ $zone->increase_delivery_charge_message ? $zone->increase_delivery_charge_message : '' }}"
                                    placeholder="{{ translate('messages.Ex : Rainy season') }} " {{ ($zone->increased_delivery_fee_status == 1) ? ' ' : 'readonly' }}>
                        </div>
                    </div>

                </div>
                <div class="btn--container mt-3 justify-content-end">
                    <button id="reset_btn" type="reset"
                        class="btn btn--reset">{{ translate('messages.reset') }}</button>
                    <button type="submit" class="btn btn--primary">{{ translate('messages.save') }}</button>
                </div>
            </div>
        </form>
        <div class="mt-4 pb-2 text-center">
            <h3>{{ translate('messages.Incentive_Settings_for_Deliveryman') }}</h3>
            <p>
                {{ translate('messages.Motivate_deliverymen_to_achieve_daily_earning_targets_and_provide_additional_incentives_to_encourage_increased_deliveries.') }}
            </p>
        </div>
        <div class="card shadow--card border-0 mt-3 p-0">
            <div class="card-header flex-wrap __gap-5px">
                <h5 class="card-title align-items-center">
                    <span class="card-header-icon mr-2">
                        <i class="tio-settings-outlined"></i>
                    </span>
                    <span>{{translate('Incentive_Settings')}}</span> &nbsp;
                    <img src="{{asset('/public/assets/admin/img/info-circle.svg')}}" data-toggle="tooltip" title="{{ translate('messages.Set_the_daily_earning_target_and_the_incentive_upon_completing_the_target.') }}" alt="">
                </h5>
            </div>

            <div class="card-body">
            <!-- Incentive Item -->
                <div class="__bg-F8F9FC-card">
                    @forelse ($zone->incentives as $key => $incentive)
                    <div class="d-flex align-items-end __gap-15px mb-2">
                        <div class="row g-3 w-0 flex-grow-1">
                            <div class="col-sm-6">
                                @if ($key == 0)
                                <label class="form-label">{{translate('Daily Earning Target')}} {{ \App\CentralLogics\Helpers::currency_symbol() }}


                                    <span data-toggle="tooltip" data-placement="right"
                                    data-original-title="{{ translate('messages.Set_the_daily_earning_target_for_deliverymen_for_this_business_zone.')}}"
                                    class="input-label-secondary text-danger"><img
                                        src="{{ asset('/public/assets/admin/img/info-circle.svg') }}"
                                        alt="{{ translate('messages.maximum_shipping_charge') }}"></span>

                                </label>
                                @endif
                                <input type="number" readonly value="{{ \App\CentralLogics\Helpers::format_currency($incentive->earning) }}"  placeholder="{{ \App\CentralLogics\Helpers::format_currency($incentive->earning) }}" placeholder="Ex: 10" class="form-control">
                            </div>
                            <div class="col-sm-6">
                                @if ($key == 0)
                                <label class="form-label">{{translate('Incentive for Completing Target')}} {{ \App\CentralLogics\Helpers::currency_symbol() }}

                                    <span data-toggle="tooltip" data-placement="right"
                                    data-original-title="{{ translate('messages.Set_the_incentive_amount_for_deliverymen_on_completing_the_daily_earning_target_for_this_business_zone.')}}"
                                    class="input-label-secondary text-danger"><img
                                        src="{{ asset('/public/assets/admin/img/info-circle.svg') }}"
                                        alt="{{ translate('messages.maximum_shipping_charge') }}"></span>
                                </label>
                                @endif
                                <input  readonly  type="number" value="{{ \App\CentralLogics\Helpers::format_currency($incentive->incentive) }}" placeholder="{{ \App\CentralLogics\Helpers::format_currency($incentive->incentive) }}" class="form-control">
                            </div>
                        </div>
                        <div class="mb-1">
                            <a class="btn action-btn btn--danger btn-outline-danger" href="javascript:"
                            onclick="form_alert('attribute-{{ $incentive->id }}','{{ translate('messages.want_to_delece_this_incentive') }}')"
                            title="{{ translate('messages.delete') }}"><i class="tio-delete-outlined"></i></a>
                        </div>
                            <form
                            action="{{ route('admin.zone.incentive.destory', ['id' => $incentive->id]) }}"
                            method="post" id="attribute-{{ $incentive->id }}">
                            @csrf @method('delete')
                            </form>
                    </div>
                    @empty


                    @endforelse
                    <div class="text-right mt-3">
                        <button  type="button"  id="show_incentive_button" onclick="show_incentive()" class="btn text--primary py-1 ml-auto">{{translate('Add_New_Incentive_+')}}</button>
                    </div>
                    <div class="d-none" id="show_incentive">
                        <!-- Incentive Item -->
                        <form action="{{ route('admin.zone.incentive.store', ['zone_id' => $zone->id]) }}"
                            method="POST">
                            @csrf
                            <div class="d-flex div_size align-items-end __gap-16px mb-2">
                                <div class="row g-3 w-0 flex-grow-1">
                                    <div class="col-sm-6">
                                        @if (count($zone->incentives) == 0)
                                        <label class="form-label">{{translate('Daily Earning Target')}} {{ \App\CentralLogics\Helpers::currency_symbol() }}</label>
                                        @endif
                                        <input type="number" name="earning" step=".01"  min="1" max="99999999999.999" class="form-control" required>
                                    </div>
                                    <div class="col-sm-6">
                                        @if (count($zone->incentives) == 0)
                                            <label class="form-label">{{translate('Incentive for Completing Target')}} {{ \App\CentralLogics\Helpers::currency_symbol() }} </label>
                                        @endif
                                        <input type="number" name="incentive" id="" min="1" max="99999999999.999"
                                            class="form-control" step=".01"
                                            placeholder="{{ translate('messages.enter_incentive') }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="btn--container mt-3 justify-content-end">
                                <button id="reset_btn" onclick="hide_incentive()" type="reset"
                                    class="btn btn--reset">{{ translate('messages.reset') }}</button>
                                <button type="submit" class="btn btn--primary">{{ translate('messages.save') }}</button>
                            </div>
                        </form>
                    </div>


                </div>

            </div>
        </div>
    </div>

    <!-- How it Works -->
    <div class="modal fade" id="how-it-works">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true" class="tio-clear"></span>
                    </button>
                </div>
                <div class="modal-body pb-5 pt-0">
                    <div class="single-item-slider owl-carousel">
                        <div class="item">
                            <div class="max-544 mx-auto mb-20 text-center">
                                <img src="{{asset('/public/assets/admin/img/modal/zone1.png')}}" alt="" class="mb-20">
                                <h5 class="modal-title">{{translate('messages.Zone_wise_delivery_charge_settings')}}</h5>
                                <p>
                                    {{translate("messages.You_can_set_a_different_delivery_charge,_order_limit_for_COD,_increase_delivery_charge_percentage,_etc.,_for_this_business_zone.")}}
                                </p>
                                <p>
                                    {{translate("messages.Note:_Leave_this_section_empty_if_you_want_to_keep_the_default_charges_for_this_zone.")}}
                                </p>
                            </div>
                        </div>
                        <div class="item">
                            <div class="max-544 mx-auto mb-20 text-center">
                                <img src="{{asset('/public/assets/admin/img/modal/zone1.png')}}" alt="" class="mb-20">
                                <h5 class="modal-title">{{translate('messages.Zone_wise_Incentives_for_Deliverymen')}}</h5>
                                <p>
                                    {{translate("messages.You_can_provide_a_certain_amount_of_incentives_to_deliverymen_of_this_zone_only.")}}
                                </p>
                                <p>
                                    {{translate("messages.Note:_You_will_receive_an_instant_request_to_pay_the_incentive_amount_whenever_a_deliveryman_completes_his_target._To_see_the_incentive_requests_click_on_the_View_Incentive_Requests_button_below.")}}
                                </p>
                                <a  href="{{ route('admin.delivery-man.incentive')  }}" type="button"  class="btn btn--primary">{{translate('View Incentive Requests')}}</a>
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
@endsection

@push('script_2')
    <script>
        function show_incentive(){
            $('#show_incentive').removeClass('d-none');
            $('#show_incentive_button').addClass('d-none');
        }
        function hide_incentive(){
            $('#show_incentive').addClass('d-none');
            $('#show_incentive_button').removeClass('d-none');
        }

        $('#reset_btn').click(function() {
            location.reload(true);
        })
        $(document).on('ready', function() {
            $("#maximum_shipping_charge_status").on('change', function() {
                if ($("#maximum_shipping_charge_status").is(':checked')) {
                    $('#maximum_shipping_charge').removeAttr('readonly');
                } else {
                    $('#maximum_shipping_charge').attr('readonly', true);
                    $('#maximum_shipping_charge').val('Ex : 0');
                }
            });
            $("#max_cod_order_amount_status").on('change', function() {
                if ($("#max_cod_order_amount_status").is(':checked')) {
                    $('#max_cod_order_amount').removeAttr('readonly');
                } else {
                    $('#max_cod_order_amount').attr('readonly', true);
                    $('#max_cod_order_amount').val('Ex : 0');
                }
            });

            $("#increased_delivery_fee_status").on('change', function() {
                if ($("#increased_delivery_fee_status").is(':checked')) {
                    $('#increased_delivery_fee').removeAttr('readonly');
                    $('#increase_delivery_charge_message').removeAttr('readonly');
                } else {
                    $('#increased_delivery_fee').attr('readonly', true);
                    $('#increase_delivery_charge_message').attr('readonly', true);
                    $('#increased_delivery_fee').val('Ex : 0');
                    $('#increase_delivery_charge_message').val('');
                }
            });
        });
    </script>
@endpush
