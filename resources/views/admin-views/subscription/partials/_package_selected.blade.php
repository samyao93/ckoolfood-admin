        <?php
        $restaurant= \App\Models\Restaurant::find($restaurant_id);
        $wallet = \App\Models\RestaurantWallet::where('vendor_id',$restaurant->vendor_id)->first();
        if(isset($wallet)==false){
            \Illuminate\Support\Facades\DB::table('restaurant_wallets')->insert([
                'vendor_id'=>$restaurant->vendor_id,
                'created_at'=>now(),
                'updated_at'=>now()
            ]);
            $wallet = \App\Models\RestaurantWallet::where('vendor_id',$restaurant->vendor_id)->first();
        }
        ?>
        @if (isset($rest_subscription) && $rest_subscription->package_id == $package->id)
        <h3 class="modal-title text-center">{{translate('Renew Subscription Plan')}}</h3>
        @else
        <h3 class="modal-title text-center">{{translate('messages.migrate_to_new_plan')}}</h3>
        @endif
  <!-- Modal body -->
  <div class="modal-body">
    @if (isset($rest_subscription))
        <div class="change-plan-wrapper align-items-center">
            <div class="plan-item">
                <div class="plan-header">
                    <img class="plan-header-shape" src="{{ asset('/public/assets/admin/img/plan-1.svg') }}" alt="">
                    <h3 class="title">{{ $rest_subscription->package->package_name }}</h3>
                </div>
                <h2 class="price">
                    {{ \App\CentralLogics\Helpers::format_currency($rest_subscription->package->price) }}<sub>/
                        {{ $rest_subscription->package->validity }} days</sub></h2>
            </div>

            <!-- Plan Seperator Arrow -->
            <div class="plan-seperator-arrow mx-auto">
                <img src="{{ asset('/public/assets/admin/img/arrow.svg') }}" alt="" class="w-100">
            </div>
            <!-- Plan Seperator Arrow -->

            <div class="plan-item">
                <div class="plan-header">
                    <div class="checkicon active"></div>
                    <img class="plan-header-shape" src="{{ asset('/public/assets/admin/img/plan-2.svg') }}" alt="">
                    <h3 class="title">{{ $package->package_name }}</h3>
                </div>
                <h2 class="price">{{ \App\CentralLogics\Helpers::format_currency($package->price )}} <sub>/ {{ $package->validity }} {{ translate('messages.days') }}</sub></h2>
            </div>
        </div>
        @else

    @endif

    <div class="mb-4 mb-lg-5 subscription__plan-info-wrapper bg-ECEEF1 rounded-20">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="subscription__plan-info">
                    <div class="info">
                        {{ translate('messages.validity') }}
                    </div>
                    <h4 class="subtitle">{{ $package->validity }} {{ translate('messages.days') }}</h4>
                </div>
            </div>
            <div class="col-md-4">
                <div class="subscription__plan-info">
                    <div class="info">
                        {{ translate('messages.price') }}
                    </div>
                    <h4 class="subtitle">{{ \App\CentralLogics\Helpers::format_currency($package->price )}}</h4>
                </div>
            </div>
            <div class="col-md-4">
                <div class="subscription__plan-info">
                    <div class="info">
                        {{ translate('messages.bill_status') }}
                    </div>
                    @if (isset($rest_subscription) && $rest_subscription->package_id == $package->id)
                        <h4 class="subtitle">{{ translate('messages.renew') }}</h4>
                    @else
                    <h4 class="subtitle">{{ translate('messages.migrate_to_new_plan') }}</h4>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.subscription.package_renew_change_update') }}" method="post">
    @csrf
    @method('POST')
        <input type="hidden" value="{{ $package->id }}" name="package_id">
        <input type="hidden" value="{{ $restaurant_id }}" name="restaurant_id">
    <div class="row g-3">
        <div class="col-md-6">
            <label class="payment__method">
                <input type="radio" name="payment_type" value="pay_now" checked="" hidden="">
                <div class="payment__method-card">
                    <span class="checkicon"></span>
                    <h4 class="title">{{ translate('messages.manual_payment') }} </h4>
                    <div>
                        {{ translate('messages.collcet_payment_manually_from_the_restaurant') }}
                        {{-- {{ translate('messages.pay_with_online_payment_gateway') }} --}}
                    </div>
                </div>
            </label>
        </div>
        <div class="col-md-6">
            <label class="payment__method">
                <input type="radio" name="payment_type" value="wallet" hidden="">
                <div class="payment__method-card">
                    <span class="checkicon"></span>
                    <h4 class="title">{{ translate('messages.pay_from_restaurant_wallet') }}
                        </h4>
                    <div>
                        <strong>{{ \App\CentralLogics\Helpers::format_currency($wallet->balance )}} </strong> {{ translate('messages.payable_amount_in_the_wallet') }}
                    </div>
                </div>
            </label>
        </div>
    </div>


    <div class="__btn-container btn--container justify-content-end mt-5">
        <button type="button" data-dismiss="modal" class="btn btn--reset px-lg-5">{{ translate('Cancel') }}</button>
        @if (isset($rest_subscription) && $rest_subscription->package_id == $package->id)
            <button type="submit" name="button"  value="renew" class="btn btn--primary">
                <span class="ml-1">{{ translate('Renew Subscription Plan') }}</span> </button>
        @else
            <button type="submit" name="button" value="totally_new" class="btn btn--primary">
                <span class="ml-1">{{ translate('Change Subscription Plan') }}</span> </button>
        @endif

    </div>
</form>

</div>
