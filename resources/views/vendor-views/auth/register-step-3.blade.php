{{-- @extends('layouts.landing.app') --}}
@extends('layouts.landing.app')
@section('title', translate('messages.restaurant_registration'))
@push('css_or_js')
    <link rel="stylesheet" href="{{ asset('public/assets/landing') }}/css/style.css" />
@endpush
@section('content')
    <!-- Page Header Gap -->
    <div class="h-148px"></div>
    <!-- Page Header Gap -->


    <section class="m-0 landing-inline-1 section-gap">
        <div class="container">
            <!-- Page Header -->
            <div class="step__header">
                <h4 class="title">{{ translate('messages.Restaurant_registration_application') }}</h4>
                <div class="step__wrapper">
                    <div class="step__item active">
                        <span class="shapes"></span>
                        {{ translate('messages.general_information') }}
                    </div>
                    <div class="step__item active ">
                        <span class="shapes"></span>
                        {{ translate('messages.business_plan') }}
                    </div>
                    <div class="step__item current ">
                        <span class="shapes"></span>
                        {{ translate('messages.complete') }}
                    </div>
                </div>
            </div>
            <!-- End Page Header -->
            <div class="card __card">
                <form class="card-body" action="{{ route('restaurant.payment') }}" method="post" class="js-validate">
                    @csrf
                    @method('post')
                    <input type="hidden" name="restaurant_id" value="{{ $restaurant_id }}">
                    <input type="hidden" name="package_id" value="{{ $package_id }}">
                    <input type="hidden" name="type" value="{{ $type ?? null }}">
                    <h4 class="register--title text-center mb-40px">{{ translate('messages.payments') }}</h4>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="business-plan">
                                <input type="radio" name="payment" value="paying_now" checked hidden>
                                <div class="business-plan-card">
                                    <span class="checkicon"></span>
                                    <h4 class="title">{{ translate('messages.pay_now') }}</h4>
                                    {{-- <h4 class="title">{{ translate('messages.manual_payment') }}</h4> --}}
                                    <div>
                                        {{ translate('messages.manage_your_payment_manually') }}
                                    </div>
                                </div>
                            </label>
                        </div>
                        @php
                            $data = \App\Models\BusinessSetting::where(['key' => 'free_trial_period'])->first();
                            $free_trial_period_data= ( isset($data) ? json_decode($data->value,true) : 0);
                            $free_trial_period_status= (isset($free_trial_period_data['status']) ? $free_trial_period_data['status'] : 0);
                            $free_trial_period= (isset($free_trial_period_data['data']) ? $free_trial_period_data['data'] : 0);
                       @endphp
                        @if ( $free_trial_period_status ==  1)
                        <div class="col-md-6">
                            <label class="business-plan">
                                <input type="radio" name="payment" value="free_trial" hidden>
                                <div class="business-plan-card">
                                    <span class="checkicon"></span>
                                    <h4 class="title"> {{ $free_trial_period }} {{ translate('messages.days_free_trial') }}</h4>
                                    <div>
                                        {{ translate('messages.enjoy') .' '. $free_trial_period .' '.translate('messages.days_free_trial_and_pay_your_sibcription_fee_within_these_trial_period.') }}
                                    </div>
                                </div>
                            </label>
                        </div>
                        @endif

                        <div class="btn--container justify-content-end mt-3">
                            <a type="button" href="{{ route('restaurant.back', encrypt($restaurant_id)) }}"
                                class="btn btn--reset">{{ translate('messages.back') }}</a>
                            <button type="submit"
                                class="btn btn--primary submitBtn">{{ translate('messages.play_now') }}</button>
                        </div>
                </form>
                </div>
            </div>
        </div>
    </section>

@endsection
@push('script_2')
@endpush
