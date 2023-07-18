<?php

namespace App\Http\Controllers\Admin;

use App\Models\Currency;
use App\Models\Restaurant;
use App\Models\Translation;
use Illuminate\Http\Request;
use App\Models\EmailTemplate;
use App\CentralLogics\Helpers;
use App\Models\BusinessSetting;
use Illuminate\Support\Facades\DB;
use App\Models\OrderCancelReason;
use App\Models\NotificationMessage;
use App\Http\Controllers\Controller;
use App\Models\DataSetting;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BusinessSettingsController extends Controller
{



    public function business_index($tab = 'business')
    {
        if (!Helpers::module_permission_check('settings')) {
            Toastr::error(translate('messages.access_denied'));
            return back();
        }
        if ($tab == 'business') {
            return view('admin-views.business-settings.business-index');
        } else if ($tab == 'customer') {
            $data = BusinessSetting::where('key', 'like', 'wallet_%')
                ->orWhere('key', 'like', 'loyalty_%')
                ->orWhere('key', 'like', 'ref_earning_%')
                ->orWhere('key', 'like', 'customer_%')
                ->orWhere('key', 'like', 'ref_earning_%')->get();
            $data = array_column($data->toArray(), 'value', 'key');
            return view('admin-views.business-settings.customer-index', compact('data'));
        } else if ($tab == 'deliveryman') {
            return view('admin-views.business-settings.deliveryman-index');
        } else if ($tab == 'order') {
            $reasons = OrderCancelReason::latest()->paginate(config('default_pagination'));
            return view('admin-views.business-settings.order-index', compact('reasons'));
        } else if ($tab == 'restaurant') {
            return view('admin-views.business-settings.restaurant-index');
        }
    }


    public function update_restaurant(Request $request)
    {
        BusinessSetting::updateOrInsert(['key' => 'canceled_by_restaurant'], [
            'value' => $request['canceled_by_restaurant']
        ]);
        BusinessSetting::updateOrInsert(['key' => 'toggle_restaurant_registration'], [
            'value' => $request['restaurant_self_registration']
        ]);
        Toastr::success(translate('messages.successfully_updated_to_changes_restart_app'));
        return back();
    }
    public function update_dm(Request $request)
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('messages.update_option_is_disable_for_demo'));
            return back();
        }
        BusinessSetting::updateOrInsert(['key' => 'dm_tips_status'], [
            'value' => $request['dm_tips_status']
        ]);

        BusinessSetting::updateOrInsert(['key' => 'dm_maximum_orders'], [
            'value' => $request['dm_maximum_orders']
        ]);

        BusinessSetting::updateOrInsert(['key' => 'canceled_by_deliveryman'], [
            'value' => $request['canceled_by_deliveryman']
        ]);

        BusinessSetting::updateOrInsert(['key' => 'show_dm_earning'], [
            'value' => $request['show_dm_earning']
        ]);

        BusinessSetting::updateOrInsert(['key' => 'toggle_dm_registration'], [
            'value' => $request['dm_self_registration']
        ]);
        BusinessSetting::updateOrInsert(['key' => 'dm_max_cash_in_hand'], [
            'value' => $request['dm_max_cash_in_hand']
        ]);

        Toastr::success(translate('messages.successfully_updated_to_changes_restart_app'));
        return back();
    }

    public function update_order(Request $request)
    {


        // $home_delivery = BusinessSetting::where('key', 'home_delivery')->first()?->value ?? null;
        // $take_away = BusinessSetting::where('key', 'take_away')->first()?->value ?? null;

        if ($request?->home_delivery == null && $request?->take_away == null)  {
            Toastr::warning(translate('messages.can_not_disable_both_take_away_and_delivery'));
            return back();
        }

        BusinessSetting::updateOrInsert(['key' => 'order_delivery_verification'], [
            'value' => $request['odc']
        ]);
        BusinessSetting::updateOrInsert(['key' => 'schedule_order'], [
            'value' => $request['schedule_order']
        ]);
        BusinessSetting::updateOrInsert(['key' => 'home_delivery'], [
            'value' => $request['home_delivery']
        ]);
        BusinessSetting::updateOrInsert(['key' => 'take_away'], [
            'value' => $request['take_away']
        ]);
        BusinessSetting::updateOrInsert(['key' => 'repeat_order_option'], [
            'value' => $request['repeat_order_option']
        ]);
        BusinessSetting::updateOrInsert(['key' => 'order_subscription'], [
            'value' => $request['order_subscription']
        ]);

        // if($request?->home_delivery  == null ){
        //     Restaurant::query()->update([
        //         'delivery' => 0,
        //         'take_away' => 1,
        //     ]);

        // }
        // if($request?->take_away  == null ){
        //     Restaurant::query()->update([
        //         'take_away' => 0,
        //         'delivery' => 1,
        //     ]);
        // }
        $time=  $request['schedule_order_slot_duration'];
        if($request['schedule_order_slot_duration_time_formate'] == 'hour'){
            $time=  $request['schedule_order_slot_duration']*60;
        }
        BusinessSetting::updateOrInsert(['key' => 'schedule_order_slot_duration'], [
            'value' => $time
        ]);
        BusinessSetting::updateOrInsert(['key' => 'schedule_order_slot_duration_time_formate'], [
            'value' => $request['schedule_order_slot_duration_time_formate']
        ]);
        BusinessSetting::updateOrInsert(['key' => 'canceled_by_restaurant'], [
            'value' => $request['canceled_by_restaurant']
        ]);
        BusinessSetting::updateOrInsert(['key' => 'canceled_by_deliveryman'], [
            'value' => $request['canceled_by_deliveryman']
        ]);
        BusinessSetting::query()->updateOrInsert(['key' => 'order_confirmation_model'], [
            'value' => $request['order_confirmation_model']
        ]);

        Toastr::success(translate('messages.successfully_updated_to_changes_restart_app'));
        return back();
    }



    public function business_setup(Request $request)

    {
        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('messages.update_option_is_disable_for_demo'));
            return back();
        }

        $validator = Validator::make($request->all(), [
            'logo' => 'nullable|max:48',
            'icon' => 'nullable|max:2048',
        ]);

        if ($validator->fails()) {
        Toastr::error( translate('Image size must be within 2mb'));
        return back();
        }

        $key =['logo','icon',];
        $settings =  array_column(BusinessSetting::whereIn('key', $key)->get()->toArray(), 'value', 'key');

        BusinessSetting::query()->updateOrInsert(['key' => 'tax_included'], [
            'value' => $request['tax_included']
        ]);
        // BusinessSetting::query()->updateOrInsert(['key' => 'order_subscription'], [
        //     'value' => $request['order_subscription']
        // ]);

        if($request['order_subscription']  == null ){
            Restaurant::query()->update([
                'order_subscription_active' => 0,
            ]);
        }
        BusinessSetting::query()->updateOrInsert(['key' => 'business_name'], [
            'value' => $request['restaurant_name']
        ]);

        BusinessSetting::query()->updateOrInsert(['key' => 'currency'], [
            'value' => $request['currency']
        ]);
        Session::forget('currency_symbol');
        Session::forget('currency_code');
        Session::forget('currency_symbol_position');

        BusinessSetting::query()->updateOrInsert(['key' => 'timezone'], [
            'value' => $request['timezone']
        ]);

        if ($request->has('logo')) {

            $image_name = Helpers::update( dir: 'business/', old_image:$settings['logo'],format: 'png',image: $request->file('logo'));
        } else {
            $image_name = $settings['logo'];
        }

        BusinessSetting::query()->updateOrInsert(['key' => 'logo'], [
            'value' => $image_name
        ]);

        if ($request->has('icon')) {

            $image_name = Helpers::update( dir: 'business/', old_image:$settings['icon'], format:'png', image: $request->file('icon'));
        } else {
            $image_name = $settings['icon'];
        }

        BusinessSetting::query()->updateOrInsert(['key' => 'icon'], [
            'value' => $image_name
        ]);

        BusinessSetting::query()->updateOrInsert(['key' => 'phone'], [
            'value' => $request['phone']
        ]);

        BusinessSetting::query()->updateOrInsert(['key' => 'email_address'], [
            'value' => $request['email']
        ]);

        BusinessSetting::query()->updateOrInsert(['key' => 'address'], [
            'value' => $request['address']
        ]);

        BusinessSetting::query()->updateOrInsert(['key' => 'footer_text'], [
            'value' => $request['footer_text']
        ]);

        // BusinessSetting::query()->updateOrInsert(['key' => 'customer_verification'], [
        //     'value' => $request['customer_verification']
        // ]);

        // BusinessSetting::query()->updateOrInsert(['key' => 'order_delivery_verification'], [
        //     'value' => $request['odc']
        // ]);


        BusinessSetting::query()->updateOrInsert(['key' => 'cookies_text'], [
            'value' => $request['cookies_text']
        ]);

        BusinessSetting::query()->updateOrInsert(['key' => 'currency_symbol_position'], [
            'value' => $request['currency_symbol_position']
        ]);

        // BusinessSetting::query()->updateOrInsert(['key' => 'schedule_order'], [
        //     'value' => $request['schedule_order']
        // ]);

        // BusinessSetting::query()->updateOrInsert(['key' => 'order_confirmation_model'], [
        //     'value' => $request['order_confirmation_model']
        // ]);
        // BusinessSetting::query()->updateOrInsert(['key' => 'dm_tips_status'], [
        //     'value' => $request['dm_tips_status']
        // ]);

        BusinessSetting::query()->updateOrInsert(['key' => 'tax'], [
            'value' => $request['tax']
        ]);

        BusinessSetting::query()->updateOrInsert(['key' => 'admin_commission'], [
            'value' => $request['admin_commission']
        ]);

        BusinessSetting::query()->updateOrInsert(['key' => 'country'], [
            'value' => $request['country']
        ]);

        BusinessSetting::query()->updateOrInsert(['key' => 'default_location'], [
            'value' => json_encode(['lat' => $request['latitude'], 'lng' => $request['longitude']])
        ]);

        BusinessSetting::query()->updateOrInsert(['key' => 'admin_order_notification'], [
            'value' => $request['admin_order_notification']
        ]);

        BusinessSetting::query()->updateOrInsert(['key' => 'free_delivery_over'], [
            'value' => $request['free_delivery_over_status'] ? $request['free_delivery_over'] : null
        ]);

        // BusinessSetting::query()->updateOrInsert(['key' => 'dm_maximum_orders'], [
        //     'value' => $request['dm_maximum_orders']
        // ]);

        BusinessSetting::query()->updateOrInsert(['key' => 'timeformat'], [
            'value' => $request['time_format']
        ]);

        // BusinessSetting::query()->updateOrInsert(['key' => 'canceled_by_restaurant'], [
        //     'value' => $request['canceled_by_restaurant']
        // ]);

        // BusinessSetting::query()->updateOrInsert(['key' => 'canceled_by_deliveryman'], [
        //     'value' => $request['canceled_by_deliveryman']
        // ]);

        // BusinessSetting::query()->updateOrInsert(['key' => 'show_dm_earning'], [
        //     'value' => $request['show_dm_earning']
        // ]);

        BusinessSetting::query()->updateOrInsert(['key' => 'toggle_veg_non_veg'], [
            'value' => $request['vnv']
        ]);

        // BusinessSetting::query()->updateOrInsert(['key' => 'toggle_dm_registration'], [
        //     'value' => $request['dm_self_registration']
        // ]);

        // BusinessSetting::query()->updateOrInsert(['key' => 'toggle_restaurant_registration'], [
        //     'value' => $request['restaurant_self_registration']
        // ]);

        // BusinessSetting::query()->updateOrInsert(['key' => 'schedule_order_slot_duration'], [
        //     'value' => $request['schedule_order_slot_duration']
        // ]);

        BusinessSetting::query()->updateOrInsert(['key' => 'digit_after_decimal_point'], [
            'value' => $request['digit_after_decimal_point']
        ]);

        BusinessSetting::query()->updateOrInsert(['key' => 'delivery_charge_comission'], [
            'value' => $request['admin_comission_in_delivery_charge']
        ]);

        // BusinessSetting::query()->updateOrInsert(['key' => 'dm_max_cash_in_hand'], [
        //     'value' => $request['dm_max_cash_in_hand']
        // ]);

        if(!isset($request->commission) && !isset($request->subscription)){
            Toastr::error( translate('You_must_select_at_least_one_business_model_between_commission_and_subscription'));
            return back();
        }

        // For commission Model
        if (isset($request->commission) && !isset($request->subscription)) {
            BusinessSetting::query()->updateOrInsert(['key' => 'business_model'], [
                    'value' => json_encode(['commission' => 1, 'subscription' => 0 ])
                ]);
                $business_model= BusinessSetting::where('key', 'business_model')->first()?->value;
                $business_model = json_decode($business_model, true) ?? [];

            if ($business_model && $business_model['subscription'] == 0 ){
                Restaurant::where('restaurant_model','unsubscribed')
                ->update(['restaurant_model' => 'commission',
            ]);
            }
        }
        // For subscription model
            elseif(isset($request->subscription) && !isset($request->commission)) {
            BusinessSetting::query()->updateOrInsert(['key' => 'business_model'], [
                'value' => json_encode(['commission' =>  0, 'subscription' => 1 ])
            ]);
            $business_model= BusinessSetting::where('key', 'business_model')->first()?->value;
            $business_model = json_decode($business_model, true) ?? [];

            if ( $business_model && $business_model['commission'] == 0 ){
                Restaurant::where('restaurant_model','commission')
                ->update(['restaurant_model' => 'unsubscribed',
                'status' => 0,]);
            }
        } else {
            BusinessSetting::query()->updateOrInsert(['key' => 'business_model'], [
                'value' => json_encode(['commission' =>  1, 'subscription' => 1 ])
            ]);
        }
        Toastr::success( translate('Successfully updated. To see the changes in app restart the app.'));
        return back();
    }

    public function mail_index()
    {
        return view('admin-views.business-settings.mail-index');
    }

    public function mail_config(Request $request)
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('messages.update_option_is_disable_for_demo'));
            return back();
        }
        BusinessSetting::updateOrInsert(
            ['key' => 'mail_config'],
            [
                'value' => json_encode([
                    "status" => $request['status'],
                    "name" => $request['name'],
                    "host" => $request['host'],
                    "driver" => $request['driver'],
                    "port" => $request['port'],
                    "username" => $request['username'],
                    "email_id" => $request['email'],
                    "encryption" => $request['encryption'],
                    "password" => $request['password']
                ]),
                'updated_at' => now()
            ]
        );
        Toastr::success(translate('messages.configuration_updated_successfully'));
        return back();
    }
    public function mail_config_status(Request $request)
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('messages.update_option_is_disable_for_demo'));
            return back();
        }
        $config = BusinessSetting::where(['key' => 'mail_config'])->first();

        $data = $config ? json_decode($config['value'], true) : null;

        BusinessSetting::updateOrInsert(
            ['key' => 'mail_config'],
            [
                'value' => json_encode([
                    "status" => $request['status'] ?? 0,
                    "name" => $data['name'] ?? '',
                    "host" => $data['host'] ?? '',
                    "driver" => $data['driver'] ?? '',
                    "port" => $data['port'] ?? '',
                    "username" => $data['username'] ?? '',
                    "email_id" => $data['email_id'] ?? '',
                    "encryption" => $data['encryption'] ?? '',
                    "password" => $data['password'] ?? ''
                ]),
                'updated_at' => now()
            ]
        );
        Toastr::success(translate('messages.configuration_updated_successfully'));
        return back();
    }

    public function payment_index()
    {
        return view('admin-views.business-settings.payment-index');
    }

    public function payment_update(Request $request, $name)
    {

        if ($name == 'cash_on_delivery') {
            $payment = BusinessSetting::where('key', 'cash_on_delivery')->first();
            if (isset($payment) == false) {
                BusinessSetting::query()->insert([
                    'key'        => 'cash_on_delivery',
                    'value'      => json_encode([
                        'status' => $request['status'],
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                BusinessSetting::where(['key' => 'cash_on_delivery'])->update([
                    'key'        => 'cash_on_delivery',
                    'value'      => json_encode([
                        'status' => $request['status'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'digital_payment') {
            $payment = BusinessSetting::where('key', 'digital_payment')->first();
            if (isset($payment) == false) {
                BusinessSetting::query()->insert([
                    'key'        => 'digital_payment',
                    'value'      => json_encode([
                        'status' => $request['status'],
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                BusinessSetting::where(['key' => 'digital_payment'])->update([
                    'key'        => 'digital_payment',
                    'value'      => json_encode([
                        'status' => $request['status'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'ssl_commerz_payment') {
            $payment = BusinessSetting::where('key', 'ssl_commerz_payment')->first();
            if (isset($payment) == false) {
                BusinessSetting::query()->insert([
                    'key'        => 'ssl_commerz_payment',
                    'value'      => json_encode([
                        'status'         => 1,
                        'store_id'       => '',
                        'store_password' => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                BusinessSetting::where(['key' => 'ssl_commerz_payment'])->update([
                    'key'        => 'ssl_commerz_payment',
                    'value'      => json_encode([
                        'status'         => $request['status'],
                        'store_id'       => $request['store_id'],
                        'store_password' => $request['store_password'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'razor_pay') {
            $payment = BusinessSetting::where('key', 'razor_pay')->first();
            if (isset($payment) == false) {
                BusinessSetting::query()->insert([
                    'key'        => 'razor_pay',
                    'value'      => json_encode([
                        'status'       => 1,
                        'razor_key'    => '',
                        'razor_secret' => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                BusinessSetting::where(['key' => 'razor_pay'])->update([
                    'key'        => 'razor_pay',
                    'value'      => json_encode([
                        'status'       => $request['status'],
                        'razor_key'    => $request['razor_key'],
                        'razor_secret' => $request['razor_secret'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'paypal') {
            $payment = BusinessSetting::where('key', 'paypal')->first();
            if (isset($payment) == false) {
                BusinessSetting::query()->insert([
                    'key'        => 'paypal',
                    'value'      => json_encode([
                        'status'           => 1,
                        'mode' => '',
                        'paypal_client_id' => '',
                        'paypal_secret'    => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                BusinessSetting::where(['key' => 'paypal'])->update([
                    'key'        => 'paypal',
                    'value'      => json_encode([
                        'status'           => $request['status'],
                        'mode' => $request['mode'],
                        'paypal_client_id' => $request['paypal_client_id'],
                        'paypal_secret'    => $request['paypal_secret'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'stripe') {
            $payment = BusinessSetting::where('key', 'stripe')->first();
            if (isset($payment) == false) {
                BusinessSetting::query()->insert([
                    'key'        => 'stripe',
                    'value'      => json_encode([
                        'status'        => 1,
                        'api_key'       => '',
                        'published_key' => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                BusinessSetting::where(['key' => 'stripe'])->update([
                    'key'        => 'stripe',
                    'value'      => json_encode([
                        'status'        => $request['status'],
                        'api_key'       => $request['api_key'],
                        'published_key' => $request['published_key'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'senang_pay') {
            $payment = BusinessSetting::where('key', 'senang_pay')->first();
            if (isset($payment) == false) {
                BusinessSetting::query()->insert([

                    'key'        => 'senang_pay',
                    'value'      => json_encode([
                        'status'        => 1,
                        'secret_key'    => '',
                        'published_key' => '',
                        'merchant_id' => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                BusinessSetting::where(['key' => 'senang_pay'])->update([
                    'key'        => 'senang_pay',
                    'value'      => json_encode([
                        'status'        => $request['status'],
                        'secret_key'    => $request['secret_key'],
                        'published_key' => $request['publish_key'],
                        'merchant_id' => $request['merchant_id'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'paystack') {
            $payment = BusinessSetting::where('key', 'paystack')->first();
            if (isset($payment) == false) {
                BusinessSetting::query()->insert([
                    'key'        => 'paystack',
                    'value'      => json_encode([
                        'status'        => 1,
                        'publicKey'     => '',
                        'secretKey'     => '',
                        'paymentUrl'    => '',
                        'merchantEmail' => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                BusinessSetting::where(['key' => 'paystack'])->update([
                    'key'        => 'paystack',
                    'value'      => json_encode([
                        'status'        => $request['status'],
                        'publicKey'     => $request['publicKey'],
                        'secretKey'     => $request['secretKey'],
                        'paymentUrl'    => $request['paymentUrl'],
                        'merchantEmail' => $request['merchantEmail'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'flutterwave') {
            $payment = BusinessSetting::where('key', 'flutterwave')->first();
            if (isset($payment) == false) {
                BusinessSetting::query()->insert([
                    'key'        => 'flutterwave',
                    'value'      => json_encode([
                        'status'        => 1,
                        'public_key'     => '',
                        'secret_key'     => '',
                        'hash'    => '',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                BusinessSetting::where(['key' => 'flutterwave'])->update([
                    'key'        => 'flutterwave',
                    'value'      => json_encode([
                        'status'        => $request['status'],
                        'public_key'     => $request['public_key'],
                        'secret_key'     => $request['secret_key'],
                        'hash'    => $request['hash'],
                    ]),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($name == 'mercadopago') {
            $payment = BusinessSetting::updateOrInsert(
                ['key' => 'mercadopago'],
                [
                    'value'      => json_encode([
                        'status'        => $request['status'],
                        'public_key'     => $request['public_key'],
                        'access_token'     => $request['access_token'],
                    ]),
                    'updated_at' => now()
                ]
            );
        } elseif ($name == 'paymob_accept') {
            BusinessSetting::query()->updateOrInsert(['key' => 'paymob_accept'], [
                'value' => json_encode([
                    'status' => $request['status'],
                    'api_key' => $request['api_key'],
                    'iframe_id' => $request['iframe_id'],
                    'integration_id' => $request['integration_id'],
                    'hmac' => $request['hmac'],
                ]),
                'updated_at' => now()
            ]);
        } elseif ($name == 'liqpay') {
            BusinessSetting::query()->updateOrInsert(['key' => 'liqpay'], [
                'value' => json_encode([
                    'status' => $request['status'],
                    'public_key' => $request['public_key'],
                    'private_key' => $request['private_key']
                ]),
                'updated_at' => now()
            ]);
        } elseif ($name == 'paytm') {
            BusinessSetting::query()->updateOrInsert(['key' => 'paytm'], [
                'value' => json_encode([
                    'status' => $request['status'],
                    'paytm_merchant_key' => $request['paytm_merchant_key'],
                    'paytm_merchant_mid' => $request['paytm_merchant_mid'],
                    'paytm_merchant_website' => $request['paytm_merchant_website'],
                    'paytm_refund_url' => $request['paytm_refund_url'],
                ]),
                'updated_at' => now()
            ]);
        } elseif ($name == 'bkash') {
            BusinessSetting::query()->updateOrInsert(['key' => 'bkash'], [
                'value' => json_encode([
                    'status' => $request['status'],
                    'api_key' => $request['api_key'],
                    'api_secret' => $request['api_secret'],
                    'username' => $request['username'],
                    'password' => $request['password'],
                ]),
                'updated_at' => now()
            ]);
        } elseif ($name == 'paytabs') {
            BusinessSetting::query()->updateOrInsert(['key' => 'paytabs'], [
                'value' => json_encode([
                    'status' => $request['status'],
                    'profile_id' => $request['profile_id'],
                    'server_key' => $request['server_key'],
                    'base_url' => $request['base_url']
                ]),
                'updated_at' => now()
            ]);
        }

        Toastr::success(translate('messages.payment_settings_updated'));
        return back();
    }
    public function theme_settings()
    {
        return view('admin-views.business-settings.theme-settings');
    }
    public function update_theme_settings(Request $request)
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('messages.update_option_is_disable_for_demo'));
            return back();
        }
        BusinessSetting::query()->updateOrInsert(['key' => 'theme'], [
            'value' => $request['theme']
        ]);
        Toastr::success(translate('theme_settings_updated'));
        return back();
    }

    public function app_settings()
    {
        return view('admin-views.business-settings.app-settings');
    }

    public function update_app_settings(Request $request)
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('messages.update_option_is_disable_for_demo'));
            return back();
        }
        if($request->type == 'user_app'){
            BusinessSetting::query()->updateOrInsert(['key' => 'app_minimum_version_android'], [
                'value' => $request['app_minimum_version_android']
            ]);
            BusinessSetting::query()->updateOrInsert(['key' => 'app_url_android'], [
                'value' => $request['app_url_android']
            ]);
            BusinessSetting::query()->updateOrInsert(['key' => 'app_minimum_version_ios'], [
                'value' => $request['app_minimum_version_ios']
            ]);

            BusinessSetting::query()->updateOrInsert(['key' => 'app_url_ios'], [
                'value' => $request['app_url_ios']
            ]);
            Toastr::success(translate('messages.User_app_settings_updated'));
            return back();
        }
        if($request->type == 'restaurant_app'){
            BusinessSetting::query()->updateOrInsert(['key' => 'app_minimum_version_android_restaurant'], [
                'value' => $request['app_minimum_version_android_restaurant']
            ]);
            BusinessSetting::query()->updateOrInsert(['key' => 'app_url_android_restaurant'], [
                'value' => $request['app_url_android_restaurant']
            ]);
            BusinessSetting::query()->updateOrInsert(['key' => 'app_minimum_version_ios_restaurant'], [
                'value' => $request['app_minimum_version_ios_restaurant']
            ]);
            BusinessSetting::query()->updateOrInsert(['key' => 'app_url_ios_restaurant'], [
                'value' => $request['app_url_ios_restaurant']
            ]);
            Toastr::success(translate('messages.Restaurant_app_settings_updated'));
            return back();
        }
        if($request->type == 'delivery_app'){
            BusinessSetting::query()->updateOrInsert(['key' => 'app_minimum_version_android_deliveryman'], [
                'value' => $request['app_minimum_version_android_deliveryman']
            ]);
            BusinessSetting::query()->updateOrInsert(['key' => 'app_url_android_deliveryman'], [
                'value' => $request['app_url_android_deliveryman']
            ]);
            BusinessSetting::query()->updateOrInsert(['key' => 'app_minimum_version_ios_deliveryman'], [
                'value' => $request['app_minimum_version_ios_deliveryman']
            ]);
            BusinessSetting::query()->updateOrInsert(['key' => 'app_url_ios_deliveryman'], [
                'value' => $request['app_url_ios_deliveryman']
            ]);

            Toastr::success(translate('messages.Delivery_app_settings_updated'));
            return back();
        }

        return back();
    }

    public function landing_page_settings($tab)
    {
        abort(404);
        if ($tab == 'index') {
            return view('admin-views.business-settings.landing-page-settings.index');
        } else if ($tab == 'links') {
            return view('admin-views.business-settings.landing-page-settings.links');
        } else if ($tab == 'speciality') {
            return view('admin-views.business-settings.landing-page-settings.speciality');
        } else if ($tab == 'testimonial') {
            return view('admin-views.business-settings.landing-page-settings.testimonial');
        } else if ($tab == 'feature') {
            return view('admin-views.business-settings.landing-page-settings.feature');
        } else if ($tab == 'image') {
            return view('admin-views.business-settings.landing-page-settings.image');
        } else if ($tab == 'backgroundChange') {
            return view('admin-views.business-settings.landing-page-settings.backgroundChange');
        }  else if ($tab == 'react') {
            return view('admin-views.business-settings.landing-page-settings.react');
        } else if ($tab == 'react-feature') {
            return view('admin-views.business-settings.landing-page-settings.react_feature');
        } else if ($tab == 'platform-order') {
            return view('admin-views.business-settings.landing-page-settings.our_platform');
        } else if ($tab == 'platform-restaurant') {
            return view('admin-views.business-settings.landing-page-settings.restaurant_platform');
        } else if ($tab == 'platform-delivery') {
            return view('admin-views.business-settings.landing-page-settings.delivery_platform');
        } else if ($tab == 'react-half-banner') {
            return view('admin-views.business-settings.landing-page-settings.react_half_banner');
        } else if ($tab == 'react-self-registration') {
            return view('admin-views.business-settings.landing-page-settings.react_self_reg');
        }
    }

    public function update_landing_page_settings(Request $request, $tab)
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('messages.update_option_is_disable_for_demo'));
            return back();
        }

        if ($tab == 'text') {
            BusinessSetting::query()->updateOrInsert(['key' => 'landing_page_text'], [
                'value' => json_encode([
                    'header_title_1' => $request['header_title_1'],
                    'header_title_2' => $request['header_title_2'],
                    'header_title_3' => $request['header_title_3'],
                    'about_title' => $request['about_title'],
                    'why_choose_us' => $request['why_choose_us'],
                    'why_choose_us_title' => $request['why_choose_us_title'],
                    'testimonial_title' => $request['testimonial_title'],
                    'mobile_app_section_heading' => $request['mobile_app_section_heading'],
                    'mobile_app_section_text' => $request['mobile_app_section_text'],
                    'feature_section_description' => $request['feature_section_description'],
                    'feature_section_title' => $request['feature_section_title'],
                    'footer_article' => $request['footer_article'],

                    'join_us_title' => $request['join_us_title'],
                    'join_us_sub_title' => $request['join_us_sub_title'],
                    'join_us_article' => $request['join_us_article'],
                    'our_platform_title' => $request['our_platform_title'],
                    'our_platform_article' => $request['our_platform_article'],
                    'newsletter_title' => $request['newsletter_title'],
                    'newsletter_article' => $request['newsletter_article'],
                ])
            ]);
            Toastr::success(translate('messages.landing_page_text_updated'));
        } else if ($tab == 'links') {
            BusinessSetting::query()->updateOrInsert(['key' => 'landing_page_links'], [
                'value' => json_encode([
                    'app_url_android_status' => $request['app_url_android_status'],
                    'app_url_android' => $request['app_url_android'],
                    'app_url_ios_status' => $request['app_url_ios_status'],
                    'app_url_ios' => $request['app_url_ios'],
                    'web_app_url_status' => $request['web_app_url_status'],
                    'web_app_url' => $request['web_app_url'],
                    'order_now_url_status' => $request['order_now_url_status'],
                    'order_now_url' => $request['order_now_url']
                ])
            ]);
            Toastr::success(translate('messages.landing_page_links_updated'));
        } else if ($tab == 'speciality') {
            $data = [];
            $imageName = null;
            $speciality = BusinessSetting::where('key', 'speciality')->first();
            if ($speciality) {
                $data = json_decode($speciality?->value, true);
            }
            if ($request->has('image')) {
                $validator = Validator::make($request->all(), [
                    'image' => 'required|max:2048',
                ]);
                if ($validator->fails()) {
                Toastr::error( translate('Image size must be within 2mb'));
                return back();
                }

                $imageName = \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . ".png";
                $request->image->move(public_path('assets/landing/image'), $imageName);
            }
            array_push($data, [
                'img' => $imageName,
                'title' => $request->speciality_title
            ]);

            BusinessSetting::query()->updateOrInsert(['key' => 'speciality'], [
                'value' => json_encode($data)
            ]);
            Toastr::success(translate('messages.landing_page_speciality_updated'));
        } else if ($tab == 'feature') {
            $data = [];
            $imageName = null;
            $feature = BusinessSetting::where('key', 'feature')->first();
            if ($feature) {
                $data = json_decode($feature?->value, true);
            }
            if ($request->has('image')) {
                $validator = Validator::make($request->all(), [
                    'image' => 'required|max:2048',
                ]);
                if ($validator->fails()) {
                Toastr::error( translate('Image size must be within 2mb'));
                return back();
                }
                $imageName = \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . ".png";
                $request->image->move(public_path('assets/landing/image'), $imageName);
            }
            array_push($data, [
                'img' => $imageName,
                'title' => $request->feature_title,
                'feature_description' => $request->feature_description
            ]);

            BusinessSetting::query()->updateOrInsert(['key' => 'feature'], [
                'value' => json_encode($data)
            ]);
            Toastr::success(translate('messages.landing_page_feature_updated'));
        }
        else if ($tab == 'testimonial') {
            $data = [];
            $imageName = null;
            $speciality = BusinessSetting::where('key', 'testimonial')->first();
            if ($speciality) {
                $data = json_decode($speciality?->value, true);
            }
            if ($request->has('image')) {
                $validator = Validator::make($request->all(), [
                    'image' => 'required|max:2048',
                ]);
                if ($validator->fails()) {
                Toastr::error( translate('Image size must be within 2mb'));
                return back();
                }
                $imageName = \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . ".png";
                $request->image->move(public_path('assets/landing/image'), $imageName);
            }
            array_push($data, [
                'img' => $imageName,
                'name' => $request->reviewer_name,
                'position' => $request->reviewer_designation,
                'detail' => $request->review,
            ]);

            BusinessSetting::query()->updateOrInsert(['key' => 'testimonial'], [
                'value' => json_encode($data)
            ]);
            Toastr::success(translate('messages.landing_page_testimonial_updated'));
        }
        else if ($tab == 'image') {
            $data = [];
            $images = BusinessSetting::where('key', 'landing_page_images')->first();
            if ($images) {
                $data = json_decode($images?->value, true);
            }
            if ($request->has('top_content_image')) {
                $validator = Validator::make($request->all(), [
                    'top_content_image' => 'required|max:2048',
                ]);
                if ($validator->fails()) {
                Toastr::error( translate('Image size must be within 2mb'));
                return back();
                }
                $imageName = \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . ".png";
                $request->top_content_image->move(public_path('assets/landing/image'), $imageName);
                $data['top_content_image'] = $imageName;
            }
            if ($request->has('about_us_image')) {
                $validator = Validator::make($request->all(), [
                    'about_us_image' => 'required|max:2048',
                ]);
                if ($validator->fails()) {
                Toastr::error( translate('Image size must be within 2mb'));
                return back();
                 }
                $imageName = \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . ".png";
                $request->about_us_image->move(public_path('assets/landing/image'), $imageName);
                $data['about_us_image'] = $imageName;
            }

            if ($request->has('feature_section_image')) {
                $validator = Validator::make($request->all(), [
                    'feature_section_image' => 'required|max:2048',
                ]);
                if ($validator->fails()) {
                Toastr::error( translate('Image size must be within 2mb'));
                return back();
                    }
                $imageName = \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . ".png";
                $request->feature_section_image->move(public_path('assets/landing/image'), $imageName);
                $data['feature_section_image'] = $imageName;
            }
            if ($request->has('mobile_app_section_image')) {
                $validator = Validator::make($request->all(), [
                    'mobile_app_section_image' => 'required|max:2048',
                ]);
                if ($validator->fails()) {
                Toastr::error( translate('Image size must be within 2mb'));
                return back();
                }
                $imageName = \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . ".png";
                $request->mobile_app_section_image->move(public_path('assets/landing/image'), $imageName);
                $data['mobile_app_section_image'] = $imageName;
            }
            BusinessSetting::query()->updateOrInsert(['key' => 'landing_page_images'], [
                'value' => json_encode($data)
            ]);
            Toastr::success(translate('messages.landing_page_image_updated'));
        } else if ($tab == 'background-change') {
            BusinessSetting::query()->updateOrInsert(['key' => 'backgroundChange'], [
                'value' => json_encode([
                    'primary_1_hex' => $request['header-bg'],
                    'primary_1_rgb' => Helpers::hex_to_rbg($request['header-bg']),
                    'primary_2_hex' => $request['footer-bg'],
                    'primary_2_rgb' => Helpers::hex_to_rbg($request['footer-bg']),
                ])
            ]);
            Toastr::success(translate('messages.background_updated'));
        } else if ($tab == 'react_header') {
            $data = null;
            $image = BusinessSetting::where('key', 'react_header_banner')->first();
            if ($image) {
                $data = $image?->value;
            }
            $image_name =$data ?? \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . ".png";
            if ($request->has('react_header_banner')) {
                // $image_name = ;
                $validator = Validator::make($request->all(), [
                    'react_header_banner' => 'required|max:2048',
                ]);
                if ($validator->fails()) {
                Toastr::error( translate('Image size must be within 2mb'));
                return back();
                }
                $data = Helpers::update( dir: 'react_landing/', old_image:$image_name, format:'png',image: $request->file('react_header_banner')) ?? null;
            }
            BusinessSetting::query()->updateOrInsert(['key' => 'react_header_banner'], [
                'value' => $data
            ]);
            Toastr::success(translate('Landing page header banner updated'));
        } else if ($tab == 'full-banner') {

            $request->validate([
                'banner_section_img_full' => 'nullable|max:2048',
                'full_banner_section_title' => 'required|max:30',
                'full_banner_section_sub_title' => 'required|max:55',
            ]);

            $data = [];
            $banner_section_full = BusinessSetting::where('key','banner_section_full')->first();
            $imageName = null;
            if($banner_section_full){
                $data = json_decode($banner_section_full?->value, true);
                $imageName =$data['banner_section_img_full'] ?? null;
            }
            if ($request->has('banner_section_img_full'))   {
                if (empty($imageName)) {
                    $imageName = Helpers::upload( dir:'react_landing/',format: 'png',image: $request->file('banner_section_img_full'));
                    }  else{
                    $imageName= Helpers::update( dir: 'react_landing/',old_image: $data['banner_section_img_full'],format: 'png', image:$request->file('banner_section_img_full')) ;
                    }
            }
            $data = [
                'banner_section_img_full' => $imageName,
                'full_banner_section_title' => $request->full_banner_section_title ?? $banner_section_full['full_banner_section_title'] ,
                'full_banner_section_sub_title' => $request->full_banner_section_sub_title ?? $banner_section_full['full_banner_section_sub_title'],
            ];
            BusinessSetting::query()->updateOrInsert(['key' => 'banner_section_full'], [
                'value' => json_encode($data)
            ]);
            Toastr::success(translate('messages.landing_page_banner_section_updated'));
        } else if ($tab == 'discount-banner') {

            $request->validate([
                'img' => 'nullable|max:2048',
                'title' => 'required|max:30',
                'sub_title' => 'required|max:55',
                ]);

            $data = [];
            $discount_banner = BusinessSetting::where('key','discount_banner')->first();
            $imageName = null;
            if($discount_banner){
                $data = json_decode($discount_banner?->value, true);
                $imageName =$data['img'] ?? null;
            }
            if ($request->has('img'))   {
                if (empty($imageName)) {
                    $imageName = Helpers::upload( dir:'react_landing/', format:'png',image: $request->file('img'));
                    }  else{
                    $imageName= Helpers::update( dir: 'react_landing/', old_image: $data['img'],format: 'png',image: $request->file('img')) ;
                    }
            }
            $data = [
                'img' => $imageName,
                'title' => $request->title ?? $discount_banner['title'] ,
                'sub_title' => $request->sub_title ?? $discount_banner['sub_title'],
            ];
            BusinessSetting::query()->updateOrInsert(['key' => 'discount_banner'], [
                'value' => json_encode($data)
            ]);
            Toastr::success(translate('messages.landing_page_discount_banner_section_updated'));
        } else if ($tab == 'banner-section-half') {

            $request->validate([
                'image' => 'nullable|max:2048',
                'title' => 'nullable|max:20',
                'sub_title' => 'nullable|max:30',
            ]);
            $data = [];
            $imageName = null;
            $banner_section_half = BusinessSetting::where('key', 'banner_section_half')->first();
            if ($banner_section_half) {
                $data = json_decode($banner_section_half?->value, true);
            }

            if ($request->has('image')) {
                $imageName=Helpers::upload( dir:'react_landing/',format:'png', image:$request->file('image')) ;
            }
            array_push($data, [
                'img' => $imageName,
                'title' => $request->title ?? null,
                'sub_title' => $request->sub_title ?? null
            ]);

            BusinessSetting::query()->updateOrInsert(['key' => 'banner_section_half'], [
                'value' => json_encode($data)
            ]);
            Toastr::success(translate('messages.landing_page_banner_section_updated'));
        }
        else if ($tab == 'app_section_image') {
            $data = [];
            $images = BusinessSetting::where('key', 'app_section_image')->first();
            if ($images) {
                $data = json_decode($images?->value, true);
            }
            if ($request->has('app_section_image')) {
                $validator = Validator::make($request->all(), [
                    'app_section_image' => 'required|max:2048',
                ]);
                if ($validator->fails()) {
                Toastr::error( translate('Image size must be within 2mb'));
                return back();
                }

                $imageName = \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . ".png";
                $imageName = Helpers::update( dir: 'react_landing/', old_image:$imageName, format:'png', image:$request->file('app_section_image'));
                $data['app_section_image'] = $imageName;
            }
            if ($request->has('app_section_image_2')) {
                $validator = Validator::make($request->all(), [
                    'app_section_image_2' => 'required|max:2048',
                ]);
                if ($validator->fails()) {
                Toastr::error( translate('Image size must be within 2mb'));
                return back();
                 }
                $imageName = \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . ".png";
                $imageName = Helpers::update( dir: 'react_landing/',  old_image:$imageName, format:'png',image: $request->file('app_section_image_2'));
                $data['app_section_image_2'] = $imageName;
            }

            BusinessSetting::query()->updateOrInsert(['key' => 'app_section_image'], [
                'value' => json_encode($data)
            ]);
            Toastr::success(translate('messages.App section image updated'));
        }

        else if ($tab == 'footer_logo') {
            $data = null;
            $image = BusinessSetting::where('key', 'footer_logo')->first();
            if ($image) {
                $data = $image?->value;
            }
            $image_name =$data ?? \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . ".png";
            if ($request->has('footer_logo')) {
                $validator = Validator::make($request->all(), [
                    'footer_logo' => 'required|max:2048',
                ]);
                if ($validator->fails()) {
                Toastr::error( translate('Image size must be within 2mb'));
                return back();
                }
                $data = Helpers::update( dir: 'react_landing/', old_image: $image_name, format:'png', image:$request->file('footer_logo')) ?? null;
            }
            BusinessSetting::query()->updateOrInsert(['key' => 'footer_logo'], [
                'value' => $data
            ]);
            Toastr::success(translate('Footer logo updated'));
        }  else if ($tab == 'react-feature') {

            $request->validate([
                'image' => 'nullable|max:2048',
                'feature_title' => 'required|max:20',
                'feature_description' => 'required',
            ]);

            $data = [];
            $imageName = null;
            $feature = BusinessSetting::where('key', 'react_feature')->first();
            if ($feature) {
                $data = json_decode($feature?->value, true);
            }
            if ($request->has('image')) {
                $imageName=Helpers::upload( dir:'react_landing/feature/',format:'png',image: $request->file('image')) ;
            }
            array_push($data, [
                'img' => $imageName,
                'title' => $request->feature_title,
                'feature_description' => $request->feature_description
            ]);

            BusinessSetting::query()->updateOrInsert(['key' => 'react_feature'], [
                'value' => json_encode($data)
            ]);
            Toastr::success(translate('messages.landing_page_feature_updated'));
        } else if ($tab == 'platform-main') {

            if($request->button == 'restaurant_platform'){
                $data = [];
                $imageName = null;
                $restaurant_platform = BusinessSetting::where('key', 'restaurant_platform')->first();
                if ($restaurant_platform) {
                    $data = json_decode($restaurant_platform?->value, true);
                    $imageName = $data['image'] ?? null;
                }

                $image_name =$data['image'] ?? \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . ".png";
                if ($request->has('image')) {
                    $validator = Validator::make($request->all(), [
                        'image' => 'required|max:2048',
                    ]);
                    if ($validator->fails()) {
                    Toastr::error( translate('Image size must be within 2mb'));
                    return back();
                    }

                    $imageName  = Helpers::update( dir: 'landing/', old_image:$image_name,format:'png', image:$request->file('image')) ?? null;
                }

                $data= [
                    'image' => $imageName,
                    'title' => $request->title,
                    'url' => $request->url,
                    'url_status' => $request->url_status ?? 0,
                ];

                BusinessSetting::query()->updateOrInsert(['key' => 'restaurant_platform'], [
                    'value' => json_encode($data)
                ]);
            }
            if($request->button == 'order_platform'){

                $data = [];
                $imageName = null;
                $order_platform = BusinessSetting::where('key', 'order_platform')->first();
                if ($order_platform) {
                    $data = json_decode($order_platform?->value, true);
                    $imageName = $data['image'] ?? null;
                }
                $image_name =$data['image'] ?? \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . ".png";
                if ($request->has('image')) {
                    $validator = Validator::make($request->all(), [
                        'image' => 'required|max:2048',
                    ]);
                    if ($validator->fails()) {
                    Toastr::error( translate('Image size must be within 2mb'));
                    return back();
                    }
                    $imageName  = Helpers::update( dir: 'landing/', old_image:$image_name, format:'png',image: $request->file('image')) ?? null;
                }
                $data= [
                    'image' => $imageName,
                    'title' => $request->title,
                    'url' => $request->url,
                    'url_status' => $request->url_status ?? 0,
                ];

                BusinessSetting::query()->updateOrInsert(['key' => 'order_platform'], [
                    'value' => json_encode($data)
                ]);
            }
            if($request->button == 'delivery_platform'){
                // dd($request->all());
                $data = [];
                $imageName = null;
                $delivery_platform = BusinessSetting::where('key', 'delivery_platform')->first();
                if ($delivery_platform) {
                    $data = json_decode($delivery_platform?->value, true);
                    $imageName = $data['image'] ?? null;
                }
                $image_name =$data['image'] ?? \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . ".png";
                if ($request->has('image')) {
                    $validator = Validator::make($request->all(), [
                        'image' => 'required|max:2048',
                    ]);
                    if ($validator->fails()) {
                    Toastr::error( translate('Image size must be within 2mb'));
                    return back();
                    }
                    $imageName  = Helpers::update( dir: 'landing/', old_image: $image_name, format:'png', image:$request->file('image')) ?? null;
                }
                $data= [
                    'image' => $imageName,
                    'title' => $request->title,
                    // 'sub_title' => $request->sub_title,
                    // 'detail' => $request->detail,
                    'url' => $request->url,
                    'url_status' => $request->url_status ?? 0,
                ];

                BusinessSetting::query()->updateOrInsert(['key' => 'delivery_platform'], [
                    'value' => json_encode($data)
                ]);
            }

            Toastr::success(translate('messages.landing_page_our_platform_updated'));
        }


        else if ($tab == 'platform-data') {
            if($request->button == 'platform_order_data'){
                $data = [];
                $imageName = null;
                $platform_order_data = BusinessSetting::where('key', 'platform_order_data')->first();
                if ($platform_order_data) {
                    $data = json_decode($platform_order_data?->value, true);
                }
                array_push($data, [
                    'title' => $request->title,
                    'detail' => $request->detail,
                ]);
                BusinessSetting::query()->updateOrInsert(['key' => 'platform_order_data'], [
                    'value' => json_encode($data)
                ]);
                Toastr::success(translate('messages.landing_page_order_platform_data_added'));
            }
            if($request->button == 'platform_restaurant_data'){
                $data = [];
                $imageName = null;
                $platform_restaurant_data = BusinessSetting::where('key', 'platform_restaurant_data')->first();
                if ($platform_restaurant_data) {
                    $data = json_decode($platform_restaurant_data?->value, true);
                }
                array_push($data, [
                    'title' => $request->title,
                    'detail' => $request->detail,
                ]);
                BusinessSetting::query()->updateOrInsert(['key' => 'platform_restaurant_data'], [
                    'value' => json_encode($data)
                ]);
                Toastr::success(translate('messages.landing_page_restaurant_platform_data_added'));
            }
            if($request->button == 'platform_delivery_data'){
                $data = [];
                $imageName = null;
                $platform_delivery_data = BusinessSetting::where('key', 'platform_delivery_data')->first();
                if ($platform_delivery_data) {
                    $data = json_decode($platform_delivery_data?->value, true);
                }
                array_push($data, [
                    'title' => $request->title,
                    'detail' => $request->detail,
                ]);
                BusinessSetting::query()->updateOrInsert(['key' => 'platform_delivery_data'], [
                    'value' => json_encode($data)
                ]);
                Toastr::success(translate('messages.landing_page_delivary_platform_data_updated'));
            }

        }
        else if ($tab == 'react-self-registration-delivery-man') {

            $request->validate([
                'image' => 'nullable|max:2048',
                'title' => 'required|max:24',
                'sub_title' => 'required|max:55',
                'button_name' => 'nullable|max:254',
                'button_status' => 'nullable|max:2',
                'button_link' => 'nullable|max:254',
            ]);
            $data = [];
            $react_self_registration_delivery_man = BusinessSetting::where('key','react_self_registration_delivery_man')->first();
            $imageName = null;
            if($react_self_registration_delivery_man){
                $data = json_decode($react_self_registration_delivery_man?->value, true);
                $imageName =$data['image'] ?? null;
            }
            if ($request->has('image'))   {

                if (empty($imageName)) {
                    $imageName = Helpers::upload( dir:'react_landing/', format:'png', image:$request->file('image'));
                    }  else{
                    $imageName= Helpers::update( dir: 'react_landing/',old_image: $data['image'],format: 'png',image: $request->file('image')) ;
                    }
            }
            $data = [
                'image' => $imageName,
                'title' => $request->title ?? $react_self_registration_delivery_man['title'] ,
                'sub_title' => $request->sub_title ?? $react_self_registration_delivery_man['sub_title'],
                'button_name' => $request->button_name ?? $react_self_registration_delivery_man['button_name'],
                'button_status' => $request->button_status ?? $react_self_registration_delivery_man['button_status'],
                'button_link' => $request->button_link ?? $react_self_registration_delivery_man['button_link'],
                    ];

                BusinessSetting::query()->updateOrInsert(['key' => 'react_self_registration_delivery_man'], [
                'value' => json_encode($data)
            ]);
            Toastr::success(translate('messages.Delivery_man_self_registration_section_updated'));
        }
        else if ($tab == 'react-self-registration-restaurant') {
            $request->validate([
                'image' => 'nullable|max:2048',
                'title' => 'required|max:24',
                'sub_title' => 'required|max:55',
                'button_name' => 'nullable|max:254',
                'button_status' => 'nullable|max:2',
                'button_link' => 'nullable|max:254'
            ]);
            $data = [];
            $react_self_registration_restaurant = BusinessSetting::where('key','react_self_registration_restaurant')->first();
            $imageName = null;
            if($react_self_registration_restaurant){
                $data = json_decode($react_self_registration_restaurant?->value, true);
                $imageName =$data['image'] ?? null;
            }
            if ($request->has('image'))   {
                if (empty($imageName)) {
                    $imageName = Helpers::upload( dir:'react_landing/', format:'png',image: $request->file('image'));
                    }  else{
                    $imageName= Helpers::update( dir: 'react_landing/',old_image: $data['image'],format: 'png',image: $request->file('image')) ;
                    }
            }
            $data = [
                'image' => $imageName,
                'title' => $request->title ?? $react_self_registration_restaurant['title'] ,
                'sub_title' => $request->sub_title ?? $react_self_registration_restaurant['sub_title'],
                'button_name' => $request->button_name ?? $react_self_registration_restaurant['button_name'],
                'button_status' => $request->button_status ?? $react_self_registration_restaurant['button_status'],
                'button_link' => $request->button_link ?? $react_self_registration_restaurant['button_link'],
            ];
            BusinessSetting::query()->updateOrInsert(['key' => 'react_self_registration_restaurant'], [
                'value' => json_encode($data)
            ]);
            Toastr::success(translate('messages.Restaurant_self_registration_section_updated'));
        }

        return back();
    }

    public function delete_landing_page_settings($tab, $key)
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('messages.update_option_is_disable_for_demo'));
            return back();
        }
        $item = BusinessSetting::where('key', $tab)->first();
        $data = $item ? json_decode($item?->value, true) : null;
        if ($data && array_key_exists($key, $data)) {
            if($tab == 'react_feature' && isset($data[$key]['img']) && Storage::disk('public')->exists('react_landing/feature/'. $data[$key]['img'])){
                Storage::disk('public')->delete('react_landing/feature/'. $data[$key]['img']);
            }
            if ( $tab != 'react_feature' && isset($data[$key]['img']) && file_exists(public_path('assets/landing/image') . $data[$key]['img'])) {
                unlink(public_path('assets/landing/image') . $data[$key]['img']);
            }

            array_splice($data, $key, 1);

            $item->value = json_encode($data);
            $item->save();
            Toastr::success(translate('messages.' . $tab) . ' ' . translate('messages.deleted'));
            return back();
        }
        Toastr::error(translate('messages.not_found'));
        return back();

    }

    public function currency_index()
    {
        return view('admin-views.business-settings.currency-index');
    }

    public function currency_store(Request $request)
    {
        $request->validate([
            'currency_code' => 'required|unique:currencies',
        ]);

        Currency::create([
            "country" => $request['country'],
            "currency_code" => $request['currency_code'],
            "currency_symbol" => $request['symbol'],
            "exchange_rate" => $request['exchange_rate'],
        ]);
        Toastr::success(translate('messages.currency_added_successfully'));
        return back();
    }

    public function currency_edit($id)
    {
        $currency = Currency::find($id);
        return view('admin-views.business-settings.currency-update', compact('currency'));
    }

    public function currency_update(Request $request, $id)
    {
        Currency::where(['id' => $id])->update([
            "country" => $request['country'],
            "currency_code" => $request['currency_code'],
            "currency_symbol" => $request['symbol'],
            "exchange_rate" => $request['exchange_rate'],
        ]);
        Toastr::success(translate('messages.currency_updated_successfully'));
        return redirect('restaurant-panel/business-settings/currency-add');
    }

    public function currency_delete($id)
    {
        Currency::where(['id' => $id])->delete();
        Toastr::success(translate('messages.currency_deleted_successfully'));
        return back();
    }




    private function update_data($request, $key_data){
        $data = DataSetting::firstOrNew(
            ['key' =>  $key_data,
            'type' =>  'admin_landing_page'],
        );

        $data->value = $request->{$key_data}[array_search('default', $request->lang)];
        $data->save();
        $default_lang = str_replace('_', '-', app()->getLocale());
        foreach ($request->lang as $index => $key) {
            if ($default_lang == $key && !($request->{$key_data}[$index])) {
                if ($key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\DataSetting',
                            'translationable_id' => $data->id,
                            'locale' => $key,
                            'key' => $key_data
                        ],
                        ['value' => $data->value]
                    );
                }
            } else {
                if ($request->{$key_data}[$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\DataSetting',
                            'translationable_id' => $data->id,
                            'locale' => $key,
                            'key' => $key_data
                        ],
                        ['value' => $request->{$key_data}[$index]]
                    );
                }
            }
        }

        return true;
    }


    private function policy_status_update($key_data , $status){
        $data = DataSetting::firstOrNew(
            ['key' =>  $key_data,
            'type' =>  'admin_landing_page'],
        );
        $data->value = $status;
        $data->save();

        return true;
    }


    public function terms_and_conditions()
    {
        $terms_and_conditions =DataSetting::withoutGlobalScope('translate')->where('type', 'admin_landing_page')->where('key', 'terms_and_conditions')->first();
        return view('admin-views.business-settings.terms-and-conditions', compact('terms_and_conditions'));
    }

    public function terms_and_conditions_update(Request $request)
    {
        $this->update_data($request , 'terms_and_conditions');
        Toastr::success(translate('messages.terms_and_condition_updated'));
        return back();
    }

    public function privacy_policy()
    {
        $privacy_policy =DataSetting::withoutGlobalScope('translate')->where('type', 'admin_landing_page')->where('key', 'privacy_policy')->first();
        return view('admin-views.business-settings.privacy-policy', compact('privacy_policy'));
    }

    public function privacy_policy_update(Request $request)
    {
        $this->update_data($request , 'privacy_policy');
        Toastr::success(translate('messages.privacy_policy_updated'));
        return back();
    }

    public function refund_policy()
    {
        $refund_policy =DataSetting::withoutGlobalScope('translate')->where('type', 'admin_landing_page')->where('key', 'refund_policy')->first();
        $refund_policy_status =DataSetting::where('type', 'admin_landing_page')->where('key','refund_policy_status')->first();
        return view('admin-views.business-settings.refund_policy', compact('refund_policy','refund_policy_status'));
    }

    public function refund_policy_update(Request $request)
    {
        $this->update_data($request , 'refund_policy');
        Toastr::success(translate('messages.refund_policy_updated'));
        return back();
    }
    public function refund_policy_status($status)
    {
        $this->policy_status_update('refund_policy_status' , $status);
        return response()->json(['status'=>"changed"]);
    }

    public function shipping_policy()
    {

        $shipping_policy =DataSetting::withoutGlobalScope('translate')->where('type', 'admin_landing_page')->where('key', 'shipping_policy')->first();
        $shipping_policy_status =DataSetting::where('type', 'admin_landing_page')->where('key','shipping_policy_status')->first();
        return view('admin-views.business-settings.shipping_policy', compact('shipping_policy','shipping_policy_status'));
    }

    public function shipping_policy_update(Request $request)
    {
        $this->update_data($request , 'shipping_policy');
        Toastr::success(translate('messages.shipping_policy_updated'));
        return back();
    }


    public function shipping_policy_status($status)
    {
        $this->policy_status_update('shipping_policy_status' , $status);
        return response()->json(['status'=>"changed"]);
    }

    public function cancellation_policy()
    {
        $cancellation_policy =DataSetting::withoutGlobalScope('translate')->where('type', 'admin_landing_page')->where('key', 'cancellation_policy')->first();
        $cancellation_policy_status =DataSetting::where('type', 'admin_landing_page')->where('key','cancellation_policy_status')->first();
        return view('admin-views.business-settings.cancellation_policy',compact('cancellation_policy','cancellation_policy_status'));
    }

    public function cancellation_policy_update(Request $request)
    {
        $this->update_data($request , 'cancellation_policy');
        Toastr::success(translate('messages.cancellation_policy_updated'));
        return back();
    }

    public function cancellation_policy_status($status)
    {
        $this->policy_status_update('cancellation_policy_status' , $status);
        return response()->json(['status'=>"changed"]);
    }

    public function about_us()
    {
        $about_us =DataSetting::withoutGlobalScope('translate')->where('type', 'admin_landing_page')->where('key', 'about_us')->first();
        return view('admin-views.business-settings.about-us', compact('about_us'));
    }

    public function about_us_update(Request $request)
    {
        $this->update_data($request , 'about_us');
        Toastr::success(translate('messages.about_us_updated'));
        return back();
    }

    public function fcm_index()
    {
        $fcm_credentials = Helpers::get_business_settings('fcm_credentials');
        return view('admin-views.business-settings.fcm-index', compact('fcm_credentials'));
    }

    public function update_fcm(Request $request)
    {
        BusinessSetting::query()->updateOrInsert(['key' => 'fcm_project_id'], [
            'value' => $request['projectId']
        ]);

        BusinessSetting::query()->updateOrInsert(['key' => 'push_notification_key'], [
            'value' => $request['push_notification_key']
        ]);

        BusinessSetting::query()->updateOrInsert(['key' => 'fcm_credentials'], [
            'value' => json_encode([
                'apiKey'=> $request->apiKey,
                'authDomain'=> $request->authDomain,
                'projectId'=> $request->projectId,
                'storageBucket'=> $request->storageBucket,
                'messagingSenderId'=> $request->messagingSenderId,
                'appId'=> $request->appId,
                'measurementId'=> $request->measurementId
            ])
        ]);
        Toastr::success(translate('messages.settings_updated'));
        session()->put('fcm_updated',1);
        return redirect()->back();
    }
    public function fcm_config()
    {
        $fcm_credentials = Helpers::get_business_settings('fcm_credentials');
        return view('admin-views.business-settings.fcm-config', compact('fcm_credentials'));
    }


    public function update_fcm_messages(Request $request)
    {
        $notification = NotificationMessage::where('key','order_pending_message')->first();
        if($notification == null){
            $notification = new NotificationMessage();
        }

        $notification->key = 'order_pending_message';

        $notification->message = $request->pending_message[array_search('default', $request->lang)];
        $notification->status = $request['pending_status'] == 1 ? 1 : 0;
        $notification->save();
        foreach($request->lang as $index=>$key)
        {
            if($request->pending_message[$index] && $key != 'default' )
            {
                Translation::updateOrInsert(
                    ['translationable_type'  => 'App\Models\NotificationMessage',
                        'translationable_id'    => $notification->id,
                        'locale'                => $key,
                        'key'                   => $notification->key],
                    ['value'                 => $request->pending_message[$index]]
                );
            }
        }

        $notification = NotificationMessage::where('key','order_confirmation_msg')->first();
        if($notification == null){
            $notification = new NotificationMessage();
        }

        $notification->key = 'order_confirmation_msg';

        $notification->message = $request->confirm_message[array_search('default', $request->lang)];
        $notification->status = $request['confirm_status'] == 1 ? 1 : 0;
        $notification->save();
        foreach($request->lang as $index=>$key)
        {
            if($request->confirm_message[$index] && $key != 'default' )
            {
                Translation::updateOrInsert(
                    ['translationable_type'  => 'App\Models\NotificationMessage',
                        'translationable_id'    => $notification->id,
                        'locale'                => $key,
                        'key'                   => $notification->key],
                    ['value'                 => $request->confirm_message[$index]]
                );
            }
        }

            $notification = NotificationMessage::where('key','order_processing_message')->first();
            if($notification == null){
                $notification = new NotificationMessage();
            }

            $notification->key = 'order_processing_message';

            $notification->message = $request->processing_message[array_search('default', $request->lang)];
            $notification->status = $request['processing_status'] == 1 ? 1 : 0;
            $notification->save();
            foreach($request->lang as $index=>$key)
            {
                if($request->processing_message[$index] && $key != 'default' )
                {
                    Translation::updateOrInsert(
                        ['translationable_type'  => 'App\Models\NotificationMessage',
                            'translationable_id'    => $notification->id,
                            'locale'                => $key,
                            'key'                   => $notification->key],
                        ['value'                 => $request->processing_message[$index]]
                    );
                }
            }

            $notification = NotificationMessage::where('key','order_handover_message')->first();
            if($notification == null){
                $notification = new NotificationMessage();
            }

            $notification->key = 'order_handover_message';

            $notification->message = $request->order_handover_message[array_search('default', $request->lang)];
            $notification->status = $request['order_handover_message_status'] == 1 ? 1 : 0;
            $notification->save();
            foreach($request->lang as $index=>$key)
            {
                if($request->order_handover_message[$index] && $key != 'default' )
                {
                    Translation::updateOrInsert(
                        ['translationable_type'  => 'App\Models\NotificationMessage',
                            'translationable_id'    => $notification->id,
                            'locale'                => $key,
                            'key'                   => $notification->key],
                        ['value'                 => $request->order_handover_message[$index]]
                    );
                }
            }

            $notification = NotificationMessage::where('key','order_refunded_message')->first();
            if($notification == null){
                $notification = new NotificationMessage();
            }

            $notification->key = 'order_refunded_message';

            $notification->message = $request->order_refunded_message[array_search('default', $request->lang)];
            $notification->status = $request['order_refunded_message_status'] == 1 ? 1 : 0;
            $notification->save();
            foreach($request->lang as $index=>$key)
            {
                if($request->order_refunded_message[$index] && $key != 'default' )
                {
                    Translation::updateOrInsert(
                        ['translationable_type'  => 'App\Models\NotificationMessage',
                            'translationable_id'    => $notification->id,
                            'locale'                => $key,
                            'key'                   => $notification->key],
                        ['value'                 => $request->order_refunded_message[$index]]
                    );
                }
            }

            $notification = NotificationMessage::where('key','refund_request_canceled')->first();

            if($notification == null){
                $notification = new NotificationMessage();
            }

            $notification->key = 'refund_request_canceled';

            $notification->message = $request->refund_request_canceled[array_search('default', $request->lang)];
            $notification->status = $request['refund_request_canceled_status'] == 1 ? 1 : 0;
            $notification->save();
            foreach($request->lang as $index=>$key)
            {
                if($request->refund_request_canceled[$index] && $key != 'default' )
                {
                    Translation::updateOrInsert(
                        ['translationable_type'  => 'App\Models\NotificationMessage',
                            'translationable_id'    => $notification->id,
                            'locale'                => $key,
                            'key'                   => $notification->key],
                        ['value'                 => $request->refund_request_canceled[$index]]
                    );
                }
            }



        $notification = NotificationMessage::where('key','out_for_delivery_message')->first();
        if($notification == null){
            $notification = new NotificationMessage();
        }

        $notification->key = 'out_for_delivery_message';

        $notification->message = $request->out_for_delivery_message[array_search('default', $request->lang)];
        $notification->status = $request['out_for_delivery_status'] == 1 ? 1 : 0;
        $notification->save();
        foreach($request->lang as $index=>$key)
        {
            if($request->out_for_delivery_message[$index] && $key != 'default' )
            {
                Translation::updateOrInsert(
                    ['translationable_type'  => 'App\Models\NotificationMessage',
                        'translationable_id'    => $notification->id,
                        'locale'                => $key,
                        'key'                   => $notification->key],
                    ['value'                 => $request->out_for_delivery_message[$index]]
                );
            }
        }

        $notification = NotificationMessage::where('key','order_delivered_message')->first();
        if($notification == null){
            $notification = new NotificationMessage();
        }

        $notification->key = 'order_delivered_message';

        $notification->message = $request->delivered_message[array_search('default', $request->lang)];
        $notification->status = $request['delivered_status'] == 1 ? 1 : 0;
        $notification->save();
        foreach($request->lang as $index=>$key)
        {
            if($request->delivered_message[$index] && $key != 'default' )
            {
                Translation::updateOrInsert(
                    ['translationable_type'  => 'App\Models\NotificationMessage',
                        'translationable_id'    => $notification->id,
                        'locale'                => $key,
                        'key'                   => $notification->key],
                    ['value'                 => $request->delivered_message[$index]]
                );
            }
        }

        $notification = NotificationMessage::where('key','delivery_boy_assign_message')->first();
        if($notification == null){
            $notification = new NotificationMessage();
        }

        $notification->key = 'delivery_boy_assign_message';

        $notification->message = $request->delivery_boy_assign_message[array_search('default', $request->lang)];
        $notification->status = $request['delivery_boy_assign_status'] == 1 ? 1 : 0;
        $notification->save();
        foreach($request->lang as $index=>$key)
        {
            if($request->delivery_boy_assign_message[$index] && $key != 'default' )
            {
                Translation::updateOrInsert(
                    ['translationable_type'  => 'App\Models\NotificationMessage',
                        'translationable_id'    => $notification->id,
                        'locale'                => $key,
                        'key'                   => $notification->key],
                    ['value'                 => $request->delivery_boy_assign_message[$index]]
                );
            }
        }

        $notification = NotificationMessage::where('key','delivery_boy_delivered_message')->first();
        if($notification == null){
            $notification = new NotificationMessage();
        }

        $notification->key = 'delivery_boy_delivered_message';

        $notification->message = $request->delivery_boy_delivered_message[array_search('default', $request->lang)];
        $notification->status = $request['delivery_boy_delivered_status'] == 1 ? 1 : 0;
        $notification->save();
        foreach($request->lang as $index=>$key)
        {
            if($request->delivery_boy_delivered_message[$index] && $key != 'default' )
            {
                Translation::updateOrInsert(
                    ['translationable_type'  => 'App\Models\NotificationMessage',
                        'translationable_id'    => $notification->id,
                        'locale'                => $key,
                        'key'                   => $notification->key],
                    ['value'                 => $request->delivery_boy_delivered_message[$index]]
                );
            }
        }

        $notification = NotificationMessage::where('key','order_cancled_message')->first();
        if($notification == null){
            $notification = new NotificationMessage();
        }

        $notification->key = 'order_cancled_message';

        $notification->message = $request->order_cancled_message[array_search('default', $request->lang)];
        $notification->status = $request['order_cancled_message_status'] == 1 ? 1 : 0;
        $notification->save();
        foreach($request->lang as $index=>$key)
        {
            if($request->order_cancled_message[$index] && $key != 'default' )
            {
                Translation::updateOrInsert(
                    ['translationable_type'  => 'App\Models\NotificationMessage',
                        'translationable_id'    => $notification->id,
                        'locale'                => $key,
                        'key'                   => $notification->key],
                    ['value'                 => $request->order_cancled_message[$index]]
                );
            }
        }


        Toastr::success(translate('messages.message_updated'));
        return back();
    }
    public function location_index()
    {
        return view('admin-views.business-settings.location-index');
    }

    public function location_setup(Request $request)
    {
        $restaurant = Helpers::get_restaurant_id();
        $restaurant->latitude = $request['latitude'];
        $restaurant->longitude = $request['longitude'];
        $restaurant->save();

        Toastr::success(translate('messages.settings_updated'));
        return back();
    }

    public function config_setup()
    {
        return view('admin-views.business-settings.config');
    }

    public function config_update(Request $request)
    {
        BusinessSetting::query()->updateOrInsert(['key' => 'map_api_key'], [
            'value' => $request['map_api_key']
        ]);

        BusinessSetting::query()->updateOrInsert(['key' => 'map_api_key_server'], [
            'value' => $request['map_api_key_server']
        ]);

        Toastr::success(translate('messages.config_data_updated'));
        return back();
    }

    public function toggle_settings($key, $value)
    {
        BusinessSetting::query()->updateOrInsert(['key' => $key], [
            'value' => $value
        ]);

        Toastr::success(translate('messages.app_settings_updated'));
        return back();
    }

    public function viewSocialLogin()
    {
        $data = BusinessSetting::where('key', 'social_login')->first();
        if(! $data){
            Helpers::insert_business_settings_key('social_login','[{"login_medium":"google","client_id":"","client_secret":"","status":"0"},{"login_medium":"facebook","client_id":"","client_secret":"","status":""}]');
            $data = BusinessSetting::where('key', 'social_login')->first();
        }
        $apple = BusinessSetting::where('key', 'apple_login')->first();
        if (!$apple) {
            Helpers::insert_business_settings_key('apple_login', '[{"login_medium":"apple","client_id":"","client_secret":"","team_id":"","key_id":"","service_file":"","redirect_url":"","status":""}]');
            $apple = BusinessSetting::where('key', 'apple_login')->first();
        }
        $appleLoginServices = json_decode($apple?->value, true);
        $socialLoginServices = json_decode($data?->value, true);
        return view('admin-views.business-settings.social-login.view', compact('socialLoginServices','appleLoginServices'));
    }

    public function updateSocialLogin($service, Request $request)
    {
        $socialLogin = BusinessSetting::where('key', 'social_login')->first();
        $credential_array = [];
        foreach (json_decode($socialLogin['value'], true) as $key => $data) {
            if ($data['login_medium'] == $service) {
                $cred = [
                    'login_medium' => $service,
                    'client_id' => $request['client_id'],
                    'client_secret' => $request['client_secret'],
                    'status' => $request['status'],
                ];
                array_push($credential_array, $cred);
            } else {
                array_push($credential_array, $data);
            }
        }
        BusinessSetting::where('key', 'social_login')->update([
            'value' => $credential_array
        ]);

        Toastr::success(translate('messages.credential_updated', ['service' => $service]));
        return redirect()->back();
    }
    public function updateAppleLogin($service, Request $request)
    {
        $appleLogin = BusinessSetting::where('key', 'apple_login')->first();
        $credential_array = [];
        if($request->hasfile('service_file')){
            $fileName = Helpers::upload( dir:'apple-login/', format:'p8',image: $request->file('service_file'));
        }
        foreach (json_decode($appleLogin['value'], true) as $key => $data) {
            if ($data['login_medium'] == $service) {
                $cred = [
                    'login_medium' => $service,
                    'client_id' => $request['client_id'],
                    'client_secret' => $request['client_secret'],
                    'status' => $request['status'],
                    'team_id' => $request['team_id'],
                    'key_id' => $request['key_id'],
                    'service_file' => isset($fileName)?$fileName:$data['service_file'],
                    'redirect_url' => $request['redirect_url'],
                ];
                array_push($credential_array, $cred);
            } else {
                array_push($credential_array, $data);
            }
        }
        BusinessSetting::where('key', 'apple_login')->update([
            'value' => $credential_array
        ]);

        Toastr::success(translate('messages.credential_updated', ['service' => $service]));
        return redirect()->back();
    }

    //recaptcha
    public function recaptcha_index(Request $request)
    {
        return view('admin-views.business-settings.recaptcha-index');
    }

    public function recaptcha_update(Request $request)
    {
        // dd( $request['status']);
        BusinessSetting::query()->updateOrInsert(['key' => 'recaptcha'], [
            'key' => 'recaptcha',
            'value' => json_encode([
                'status' => $request['status'],
                'site_key' => $request['site_key'],
                'secret_key' => $request['secret_key']
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Toastr::success(translate('messages.updated_successfully'));
        return back();
    }

    public function send_mail(Request $request)
    {
        $response_flag = 0;
        try {

            Mail::to($request->email)->send(new \App\Mail\TestEmailSender());
            $response_flag = 1;
        } catch (\Exception $exception) {
            info($exception);
            $response_flag = 2;
        }

        return response()->json(['success' => $response_flag]);
    }

    public function react_setup()
    {
        Helpers::react_domain_status_check();
        return view('admin-views.business-settings.react-setup');
    }

    public function react_update(Request $request)
    {
        $request->validate([
            'react_license_code'=>'required',
            'react_domain'=>'required'
        ],[
            'react_license_code.required'=>translate('messages.license_code_is_required'),
            'react_domain.required'=>translate('messages.doamain_is_required'),
        ]);
        if(Helpers::activation_submit($request['react_license_code'])){
            BusinessSetting::query()->updateOrInsert(['key' => 'react_setup'], [
                'value' => json_encode([
                    'status'=>1,
                    'react_license_code'=>$request['react_license_code'],
                    'react_domain'=>$request['react_domain'],
                    'react_platform' => 'codecanyon'
                ])
            ]);

            Toastr::success(translate('messages.react_data_updated'));
            return back();
        }
        elseif(Helpers::react_activation_check(react_domain:$request->react_domain,react_license_code: $request->react_license_code)){

            BusinessSetting::query()->updateOrInsert(['key' => 'react_setup'], [
                'value' => json_encode([
                    'status'=>1,
                    'react_license_code'=>$request['react_license_code'],
                    'react_domain'=>$request['react_domain'],
                    'react_platform' => 'iss'
                ])
            ]);

            Toastr::success(translate('messages.react_data_updated'));
            return back();
        }
        Toastr::error(translate('messages.Invalid_license_code_or_unregistered_domain'));
        return back()->withInput(['invalid-data'=>true]);
    }


    public function site_direction(Request $request){
        if (env('APP_MODE') == 'demo') {
            session()->put('site_direction', ($request->status == 1?'ltr':'rtl'));
            return response()->json();
        }
        if($request->status == 1){
            BusinessSetting::query()->updateOrInsert(['key' => 'site_direction'], [
                'value' => 'ltr'
            ]);
        } else
        {
            BusinessSetting::query()->updateOrInsert(['key' => 'site_direction'], [
                'value' => 'rtl'
            ]);
        }
        return ;
    }





    public function email_index(Request $request,$type,$tab)
    {
        $template = $request->query('template',null);
        if ($tab == 'new-order') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.place-order-format',compact('template'));
        } else if ($tab == 'forgot-password') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.forgot-pass-format',compact('template'));
        } else if ($tab == 'restaurant-registration') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.restaurant-registration-format',compact('template'));
        } else if ($tab == 'dm-registration') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.dm-registration-format',compact('template'));
        } else if ($tab == 'registration') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.registration-format',compact('template'));
        } else if ($tab == 'approve') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.approve-format',compact('template'));
        } else if ($tab == 'deny') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.deny-format',compact('template'));
        } else if ($tab == 'withdraw-request') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.withdraw-request-format',compact('template'));
        } else if ($tab == 'withdraw-approve') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.withdraw-approve-format',compact('template'));
        } else if ($tab == 'withdraw-deny') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.withdraw-deny-format',compact('template'));
        } else if ($tab == 'campaign-request') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.campaign-request-format',compact('template'));
        } else if ($tab == 'campaign-approve') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.campaign-approve-format',compact('template'));
        } else if ($tab == 'campaign-deny') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.campaign-deny-format',compact('template'));
        } else if ($tab == 'refund-request') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.refund-request-format',compact('template'));
        } else if ($tab == 'login') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.login-format',compact('template'));
        } else if ($tab == 'suspend') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.suspend-format',compact('template'));
        } else if ($tab == 'cash-collect') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.cash-collect-format',compact('template'));
        } else if ($tab == 'registration-otp') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.registration-otp-format',compact('template'));
        } else if ($tab == 'login-otp') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.login-otp-format',compact('template'));
        } else if ($tab == 'order-verification') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.order-verification-format',compact('template'));
        } else if ($tab == 'refund-request-deny') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.refund-request-deny-format',compact('template'));
        } else if ($tab == 'add-fund') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.add-fund-format',compact('template'));
        } else if ($tab == 'refund-order') {
            return view('admin-views.business-settings.email-format-setting.'.$type.'-email-formats.refund-order-format',compact('template'));
        }

    }

    public function update_email_index(Request $request,$type,$tab)
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('messages.update_option_is_disable_for_demo'));
            return back();
        }
        if ($tab == 'new-order') {
            $email_type = 'new_order';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'new_order')->first();
        }elseif($tab == 'forget-password'){
            $email_type = 'forget_password';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'forget_password')->first();
        }elseif($tab == 'restaurant-registration'){
            $email_type = 'restaurant_registration';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'restaurant_registration')->first();
        }elseif($tab == 'dm-registration'){
            $email_type = 'dm_registration';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'dm_registration')->first();
        }elseif($tab == 'registration'){
            $email_type = 'registration';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'registration')->first();
        }elseif($tab == 'approve'){
            $email_type = 'approve';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'approve')->first();
        }elseif($tab == 'deny'){
            $email_type = 'deny';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'deny')->first();
        }elseif($tab == 'withdraw-request'){
            $email_type = 'withdraw_request';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'withdraw_request')->first();
        }elseif($tab == 'withdraw-approve'){
            $email_type = 'withdraw_approve';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'withdraw_approve')->first();
        }elseif($tab == 'withdraw-deny'){
            $email_type = 'withdraw_deny';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'withdraw_deny')->first();
        }elseif($tab == 'campaign-request'){
            $email_type = 'campaign_request';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'campaign_request')->first();
        }elseif($tab == 'campaign-approve'){
            $email_type = 'campaign_approve';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'campaign_approve')->first();
        }elseif($tab == 'campaign-deny'){
            $email_type = 'campaign_deny';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'campaign_deny')->first();
        }elseif($tab == 'refund-request'){
            $email_type = 'refund_request';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'refund_request')->first();
        }elseif($tab == 'login'){
            $email_type = 'login';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'login')->first();
        }elseif($tab == 'suspend'){
            $email_type = 'suspend';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'suspend')->first();
        }elseif($tab == 'cash-collect'){
            $email_type = 'cash_collect';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'cash_collect')->first();
        }elseif($tab == 'registration-otp'){
            $email_type = 'registration_otp';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'registration_otp')->first();
        }elseif($tab == 'login-otp'){
            $email_type = 'login_otp';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'login_otp')->first();
        }elseif($tab == 'order-verification'){
            $email_type = 'order_verification';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'order_verification')->first();
        }elseif($tab == 'refund-request-deny'){
            $email_type = 'refund_request_deny';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'refund_request_deny')->first();
        }elseif($tab == 'add-fund'){
            $email_type = 'add_fund';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'add_fund')->first();
        }elseif($tab == 'refund-order'){
            $email_type = 'refund_order';
            $template = EmailTemplate::where('type',$type)->where('email_type', 'refund_order')->first();
        }

        if ($template == null) {
            $template = new EmailTemplate();
        }
        $template->title = $request->title[array_search('default', $request->lang)];
        $template->body = $request->body[array_search('default', $request->lang)];
        $template->button_name = $request->button_name?$request->button_name[array_search('default', $request->lang)]:'';
        $template->footer_text = $request->footer_text[array_search('default', $request->lang)];
        $template->copyright_text = $request->copyright_text[array_search('default', $request->lang)];
        $template->background_image = $request->has('background_image') ? Helpers::update('email_template/', $template->background_image, 'png', $request->file('background_image')) : $template->background_image;
        $template->image = $request->has('image') ? Helpers::update('email_template/', $template->image, 'png', $request->file('image')) : $template->image;
        $template->logo = $request->has('logo') ? Helpers::update('email_template/', $template->logo, 'png', $request->file('logo')) : $template->logo;
        $template->icon = $request->has('icon') ? Helpers::update('email_template/', $template->icon, 'png', $request->file('icon')) : $template->icon;
        $template->email_type = $email_type;
        $template->type = $type;
        $template->button_url = $request->button_url??'';
        $template->email_template = $request->email_template;
        $template->privacy = $request->privacy?'1':0;
        $template->refund = $request->refund?'1':0;
        $template->cancelation = $request->cancelation?'1':0;
        $template->contact = $request->contact?'1':0;
        $template->facebook = $request->facebook?'1':0;
        $template->instagram = $request->instagram?'1':0;
        $template->twitter = $request->twitter?'1':0;
        $template->linkedin = $request->linkedin?'1':0;
        $template->pinterest = $request->pinterest?'1':0;
        $template->save();
        $default_lang = str_replace('_', '-', app()->getLocale());
        foreach ($request->lang as $index => $key) {
            if ($default_lang == $key && !($request->title[$index])) {
                if ($key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type'  => 'App\Models\EmailTemplate',
                            'translationable_id'    => $template->id,
                            'locale'                => $key,
                            'key'                   => 'title'
                        ],
                        ['value'                 => $template->title]
                    );
                }
            } else {

                if ($request->title[$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type'  => 'App\Models\EmailTemplate',
                            'translationable_id'    => $template->id,
                            'locale'                => $key,
                            'key'                   => 'title'
                        ],
                        ['value'                 => $request->title[$index]]
                    );
                }
            }
            if ($default_lang == $key && !($request->body[$index])) {
                if ($key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type'  => 'App\Models\EmailTemplate',
                            'translationable_id'    => $template->id,
                            'locale'                => $key,
                            'key'                   => 'body'
                        ],
                        ['value'                 => $template->body]
                    );
                }
            } else {

                if ($request->body[$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type'  => 'App\Models\EmailTemplate',
                            'translationable_id'    => $template->id,
                            'locale'                => $key,
                            'key'                   => 'body'
                        ],
                        ['value'                 => $request->body[$index]]
                    );
                }
            }
            if ($default_lang == $key && !($request->button_name && $request->button_name[$index])) {
                if ($key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type'  => 'App\Models\EmailTemplate',
                            'translationable_id'    => $template->id,
                            'locale'                => $key,
                            'key'                   => 'button_name'
                        ],
                        ['value'                 => $template->button_name]
                    );
                }
            } else {

                if ($request->button_name && $request->button_name[$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type'  => 'App\Models\EmailTemplate',
                            'translationable_id'    => $template->id,
                            'locale'                => $key,
                            'key'                   => 'button_name'
                        ],
                        ['value'                 => $request->button_name[$index]]
                    );
                }
            }
            if ($default_lang == $key && !($request->footer_text[$index])) {
                if ($key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type'  => 'App\Models\EmailTemplate',
                            'translationable_id'    => $template->id,
                            'locale'                => $key,
                            'key'                   => 'footer_text'
                        ],
                        ['value'                 => $template->footer_text]
                    );
                }
            } else {

                if ($request->footer_text[$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type'  => 'App\Models\EmailTemplate',
                            'translationable_id'    => $template->id,
                            'locale'                => $key,
                            'key'                   => 'footer_text'
                        ],
                        ['value'                 => $request->footer_text[$index]]
                    );
                }
            }
            if ($default_lang == $key && !($request->copyright_text[$index])) {
                if ($key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type'  => 'App\Models\EmailTemplate',
                            'translationable_id'    => $template->id,
                            'locale'                => $key,
                            'key'                   => 'copyright_text'
                        ],
                        ['value'                 => $template->copyright_text]
                    );
                }
            } else {

                if ($request->copyright_text[$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type'  => 'App\Models\EmailTemplate',
                            'translationable_id'    => $template->id,
                            'locale'                => $key,
                            'key'                   => 'copyright_text'
                        ],
                        ['value'                 => $request->copyright_text[$index]]
                    );
                }
            }
        }

        Toastr::success(translate('messages.template_added_successfully'));
        return back();
    }

    public function update_email_status(Request $request,$type,$tab,$status)
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('messages.update_option_is_disable_for_demo'));
            return back();
        }

        if ($tab == 'place-order') {
            BusinessSetting::query()->updateOrInsert(['key' => 'place_order_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'forgot-password') {
            BusinessSetting::query()->updateOrInsert(['key' => 'forget_password_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'restaurant-registration') {
            BusinessSetting::query()->updateOrInsert(['key' => 'restaurant_registration_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'dm-registration') {
            BusinessSetting::query()->updateOrInsert(['key' => 'dm_registration_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'registration') {
            BusinessSetting::query()->updateOrInsert(['key' => 'registration_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'approve') {
            BusinessSetting::query()->updateOrInsert(['key' => 'approve_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'deny') {
            BusinessSetting::query()->updateOrInsert(['key' => 'deny_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'withdraw-request') {
            BusinessSetting::query()->updateOrInsert(['key' => 'withdraw_request_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'withdraw-approve') {
            BusinessSetting::query()->updateOrInsert(['key' => 'withdraw_approve_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'withdraw-deny') {
            BusinessSetting::query()->updateOrInsert(['key' => 'withdraw_deny_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'campaign-request') {
            BusinessSetting::query()->updateOrInsert(['key' => 'campaign_request_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'campaign-approve') {
            BusinessSetting::query()->updateOrInsert(['key' => 'campaign_approve_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'campaign-deny') {
            BusinessSetting::query()->updateOrInsert(['key' => 'campaign_deny_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'refund-request') {
            BusinessSetting::query()->updateOrInsert(['key' => 'refund_request_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'login') {
            BusinessSetting::query()->updateOrInsert(['key' => 'login_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'suspend') {
            BusinessSetting::query()->updateOrInsert(['key' => 'suspend_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'cash-collect') {
            BusinessSetting::query()->updateOrInsert(['key' => 'cash_collect_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'registration-otp') {
            BusinessSetting::query()->updateOrInsert(['key' => 'registration_otp_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'login-otp') {
            BusinessSetting::query()->updateOrInsert(['key' => 'login_otp_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'order-verification') {
            BusinessSetting::query()->updateOrInsert(['key' => 'order_verification_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'refund-request-deny') {
            BusinessSetting::query()->updateOrInsert(['key' => 'refund_request_deny_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'add-fund') {
            BusinessSetting::query()->updateOrInsert(['key' => 'add_fund_mail_status_'.$type], [
                'value' => $status
            ]);
        } else if ($tab == 'refund-order') {
            BusinessSetting::query()->updateOrInsert(['key' => 'refund_order_mail_status_'.$type], [
                'value' => $status
            ]);
        }

        Toastr::success(translate('messages.email_status_updated'));
        return back();

    }

    public function login_url_page(){
        $data=array_column(DataSetting::whereIn('key',['restaurant_employee_login_url','restaurant_login_url','admin_employee_login_url','admin_login_url'
                ])->get(['key','value'])->toArray(), 'value', 'key');

        return view('admin-views.login-setup.login_setup',compact('data'));
    }
    public function login_url_page_update(Request $request){

        $request->validate([
            'type' => 'required',
            'admin_login_url' => 'nullable|regex:/^[a-zA-Z0-9\-\_]+$/u|unique:data_settings,value',
            'admin_employee_login_url' => 'nullable|regex:/^[a-zA-Z0-9\-\_]+$/u|unique:data_settings,value',
            'restaurant_login_url' => 'nullable|regex:/^[a-zA-Z0-9\-\_]+$/u|unique:data_settings,value',
            'restaurant_employee_login_url' => 'nullable|regex:/^[a-zA-Z0-9\-\_]+$/u|unique:data_settings,value',
        ]);

        if($request->type == 'admin') {
            DataSetting::query()->updateOrInsert(['key' => 'admin_login_url','type' => 'login_admin'], [
                'value' => $request->admin_login_url
            ]);
            // Config::set('admin_login_url', $request->admin_login_url);
        }
        elseif($request->type == 'admin_employee') {
            DataSetting::query()->updateOrInsert(['key' => 'admin_employee_login_url','type' => 'login_admin_employee'], [
                'value' => $request->admin_employee_login_url
            ]);
        }
        elseif($request->type == 'restaurant') {
            DataSetting::query()->updateOrInsert(['key' => 'restaurant_login_url','type' => 'login_restaurant'], [
                'value' => $request->restaurant_login_url
            ]);
        }
        elseif($request->type == 'restaurant_employee') {
            DataSetting::query()->updateOrInsert(['key' => 'restaurant_employee_login_url','type' => 'login_restaurant_employee'], [
                'value' => $request->restaurant_employee_login_url
            ]);
        }
        Toastr::success(translate('messages.update_successfull'));
        return back();
    }


    public function remove_image(Request $request){

        $request->validate([
            'model_name' => 'required',
            'id' => 'required',
            'image_path' => 'required',
            'field_name' => 'required',
        ]);
    try {

        $model_name = $request->model_name;
        $model = app("\\App\\Models\\{$model_name}");
        $data=  $model->where('id', $request->id)->first();
        // dd($request->image_path);

        $data_value = $data?->{$request->field_name};

        // dd($data_value);

                if($request?->json == 1){
                    $data_value = json_decode($data?->value ,true);
                    if (Storage::disk('public')->exists($request->image_path.'/'.$data_value[$request->field_name])) {
                        Storage::disk('public')->delete($request->image_path.'/'.$data_value[$request->field_name]);
                    }
                    $data_value[$request->field_name] = null;
                    $data->value = json_encode($data_value);
                }
                else{
                    if (Storage::disk('public')->exists($request->image_path.'/'.$data_value)) {
                        Storage::disk('public')->delete($request->image_path.'/'.$data_value);
                    }
                    $data->{$request->field_name} = null;
                }

        $data?->save();

    } catch (\Throwable $th) {
        Toastr::error($th->getMessage(). 'Line....'.$th->getLine());
        return back();
    }
        Toastr::success(translate('messages.Image_removed_successfully'));
        return back();
    }
}
