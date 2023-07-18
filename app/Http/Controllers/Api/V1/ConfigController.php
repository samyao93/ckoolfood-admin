<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Zone;
use App\Models\Vehicle;
use App\Models\Currency;
use App\Models\DataSetting;
use App\Models\SocialMedia;
use App\Models\ReactService;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\BusinessSetting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Models\ReactPromotionalBanner;
use Illuminate\Support\Facades\Validator;
use MatanYadaev\EloquentSpatial\Objects\Point;

class ConfigController extends Controller
{
    private $map_api_key;

    function __construct()
    {
        $map_api_key_server = BusinessSetting::where(['key' => 'map_api_key_server'])->first()?->value ?? null;
        $this->map_api_key = $map_api_key_server;
    }

    public function configuration()
    {
        $key = [
            'cash_on_delivery', 'digital_payment', 'default_location', 'free_delivery_over', 'business_name', 'logo', 'address', 'phone', 'email_address', 'country', 'currency_symbol_position', 'app_minimum_version_android',
            'app_url_android', 'app_minimum_version_ios', 'app_url_ios', 'customer_verification', 'order_delivery_verification', 'terms_and_conditions', 'privacy_policy', 'about_us', 'maintenance_mode', 'popular_food', 'popular_restaurant', 'new_restaurant', 'most_reviewed_foods', 'show_dm_earning', 'canceled_by_deliveryman', 'canceled_by_restaurant', 'timeformat', 'toggle_veg_non_veg', 'toggle_dm_registration', 'toggle_restaurant_registration', 'schedule_order_slot_duration',
            'loyalty_point_exchange_rate', 'loyalty_point_item_purchase_point', 'loyalty_point_status', 'loyalty_point_minimum_point', 'wallet_status', 'schedule_order', 'dm_tips_status', 'ref_earning_status', 'ref_earning_exchange_rate', 'theme','business_model','admin_commission','footer_text' ,'icon','refund_active_status',
            'refund_policy','shipping_policy','cancellation_policy','free_trial_period','app_minimum_version_android_restaurant',
            'app_url_android_restaurant','app_minimum_version_ios_restaurant','app_url_ios_restaurant','app_minimum_version_android_deliveryman','tax_included','order_subscription',
            'app_url_android_deliveryman','app_minimum_version_ios_deliveryman','app_url_ios_deliveryman', 'cookies_text','take_away','repeat_order_option','home_delivery',
        ];
        $social_login = [];
        $social_login_data=Helpers::get_business_settings('social_login') ?? [];
        foreach ($social_login_data as $social) {
            $config = [
                'login_medium' => $social['login_medium'],
                'status' => (boolean)$social['status']
            ];
            array_push($social_login, $config);
        }

        $settings =  array_column(BusinessSetting::whereIn('key', $key)->get()->toArray(), 'value', 'key');
        $currency_symbol = Currency::where(['currency_code' => Helpers::currency_code()])->first()?->currency_symbol;
        $cod = json_decode($settings['cash_on_delivery'], true);
        $business_plan = isset($settings['business_model']) ? json_decode($settings['business_model'], true) : [
            'commission'        =>  1,
            'subscription'     =>  0,
        ];

        $digital_payment = json_decode($settings['digital_payment'], true);

        $default_location = isset($settings['default_location']) ? json_decode($settings['default_location'], true) : 0;
        $free_delivery_over = $settings['free_delivery_over'];
        $free_delivery_over = $free_delivery_over ? (float)$free_delivery_over : $free_delivery_over;
        $languages = Helpers::get_business_settings('language');
        $lang_array = [];
        foreach ($languages as $language) {
            array_push($lang_array, [
                'key' => $language,
                'value' => Helpers::get_language_name($language)
            ]);
        }


        $apple_login = [];
        $apples = Helpers::get_business_settings('apple_login');
        if(isset($apples)){
            foreach (Helpers::get_business_settings('apple_login') as $apple) {
                $config = [
                    'login_medium' => $apple['login_medium'],
                    'status' => (boolean)$apple['status'],
                    'client_id' => $apple['client_id']
                ];
                array_push($apple_login, $config);
            }
        }

        return response()->json([
            'business_name' => $settings['business_name'],
            'logo' => $settings['logo'],
            'address' => $settings['address'],
            'phone' => $settings['phone'],
            'email' => $settings['email_address'],
            'base_urls' => [
                'product_image_url' => asset('storage/app/public/product'),
                'customer_image_url' => asset('storage/app/public/profile'),
                'banner_image_url' => asset('storage/app/public/banner'),
                'category_image_url' => asset('storage/app/public/category'),
                'cuisine_image_url' => asset('storage/app/public/cuisine'),
                'review_image_url' => asset('storage/app/public/review'),
                'notification_image_url' => asset('storage/app/public/notification'),
                'restaurant_image_url' => asset('storage/app/public/restaurant'),
                'vendor_image_url' => asset('storage/app/public/vendor'),
                'restaurant_cover_photo_url' => asset('storage/app/public/restaurant/cover'),
                'delivery_man_image_url' => asset('storage/app/public/delivery-man'),
                'chat_image_url' => asset('storage/app/public/conversation'),
                'campaign_image_url' => asset('storage/app/public/campaign'),
                'business_logo_url' => asset('storage/app/public/business'),
                'react_landing_page_images' => asset('storage/app/public/react_landing') ,
                'react_landing_page_feature_images' => asset('storage/app/public/react_landing/feature') ,
                'refund_image_url' => asset('storage/app/public/refund'),
            ],
            'country' => $settings['country'],
            'default_location' => ['lat' => $default_location ? $default_location['lat'] : '23.757989', 'lng' => $default_location ? $default_location['lng'] : '90.360587'],
            'currency_symbol' => $currency_symbol,
            'currency_symbol_direction' => $settings['currency_symbol_position'],
            'app_minimum_version_android' => (float)$settings['app_minimum_version_android'],
            'app_url_android' => $settings['app_url_android'],
            'app_minimum_version_ios' => (float)$settings['app_minimum_version_ios'],
            'app_url_ios' => $settings['app_url_ios'],
            'customer_verification' => (bool)$settings['customer_verification'],
            'schedule_order' => (bool)$settings['schedule_order'],
            'order_delivery_verification' => (bool)$settings['order_delivery_verification'],
            'cash_on_delivery' => (bool)($cod['status'] == 1 ? true : false),
            'digital_payment' => (bool)($digital_payment['status'] == 1 ? true : false),

            'free_delivery_over' => $free_delivery_over,
            'demo' => (bool)(env('APP_MODE') == 'demo' ? true : false),
            'maintenance_mode' => (bool)Helpers::get_business_settings('maintenance_mode') ?? 0,
            'order_confirmation_model' => config('order_confirmation_model'),
            'popular_food' => (float)$settings['popular_food'],
            'popular_restaurant' => (float)$settings['popular_restaurant'],
            'new_restaurant' => (float)$settings['new_restaurant'],
            'most_reviewed_foods' => (float)$settings['most_reviewed_foods'],
            'show_dm_earning' => (bool)$settings['show_dm_earning'],
            'canceled_by_deliveryman' => (bool)$settings['canceled_by_deliveryman'],
            'canceled_by_restaurant' => (bool)$settings['canceled_by_restaurant'],
            'timeformat' => (string)$settings['timeformat'],
            'language' => $lang_array,
            'toggle_veg_non_veg' => (bool)$settings['toggle_veg_non_veg'],
            'toggle_dm_registration' => (bool)$settings['toggle_dm_registration'],
            'toggle_restaurant_registration' => (bool)$settings['toggle_restaurant_registration'],
            'schedule_order_slot_duration' => (int)$settings['schedule_order_slot_duration'],
            'digit_after_decimal_point' => (int)config('round_up_to_digit'),
            'loyalty_point_exchange_rate' => (int)(isset($settings['loyalty_point_item_purchase_point']) ? $settings['loyalty_point_exchange_rate'] : 0),
            'loyalty_point_item_purchase_point' => (float)(isset($settings['loyalty_point_item_purchase_point']) ? $settings['loyalty_point_item_purchase_point'] : 0.0),
            'loyalty_point_status' => (int)(isset($settings['loyalty_point_status']) ? $settings['loyalty_point_status'] : 0),
            'minimum_point_to_transfer' => (int)(isset($settings['loyalty_point_minimum_point']) ? $settings['loyalty_point_minimum_point'] : 0),
            'customer_wallet_status' => (int)(isset($settings['wallet_status']) ? $settings['wallet_status'] : 0),
            'ref_earning_status' => (int)(isset($settings['ref_earning_status']) ? $settings['ref_earning_status'] : 0),
            'ref_earning_exchange_rate' => (double)(isset($settings['ref_earning_exchange_rate']) ? $settings['ref_earning_exchange_rate'] : 0),
            'dm_tips_status' => (int)(isset($settings['dm_tips_status']) ? $settings['dm_tips_status'] : 0),
            'theme' => (int)$settings['theme'],
            'social_media'=>SocialMedia::active()->get()->toArray(),
            'social_login' => $social_login,
            'business_plan' => $business_plan,
            'admin_commission' => (float)(isset($settings['admin_commission']) ? $settings['admin_commission'] : 0),
            'footer_text' => $settings['footer_text'],
            'fav_icon' => $settings['icon'],
            'refund_active_status' => (bool)(isset($settings['refund_active_status']) ? $settings['refund_active_status'] : 0),

            'free_trial_period_status' => (int)(isset($settings['free_trial_period']) ? json_decode($settings['free_trial_period'], true)['status'] : 0),
            'free_trial_period_data' =>  (int)(isset($settings['free_trial_period']) ? json_decode($settings['free_trial_period'], true)['data'] : 0),

            'app_minimum_version_android_restaurant' => (float)(isset($settings['app_minimum_version_android_restaurant']) ? $settings['app_minimum_version_android_restaurant'] : 0),
            'app_url_android_restaurant' => (isset($settings['app_url_android_restaurant']) ? $settings['app_url_android_restaurant'] : null),
            'app_minimum_version_ios_restaurant' => (float)(isset($settings['app_minimum_version_ios_restaurant']) ? $settings['app_minimum_version_ios_restaurant'] : 0),
            'app_url_ios_restaurant' => (isset($settings['app_url_ios_restaurant']) ? $settings['app_url_ios_restaurant'] : null),
            'app_minimum_version_android_deliveryman' => (float)(isset($settings['app_minimum_version_android_deliveryman']) ? $settings['app_minimum_version_android_deliveryman'] : 0),
            'app_url_android_deliveryman' => (isset($settings['app_url_android_deliveryman']) ? $settings['app_url_android_deliveryman'] : null),
            'app_minimum_version_ios_deliveryman' => (isset($settings['app_minimum_version_ios_deliveryman']) ? $settings['app_minimum_version_ios_deliveryman'] : null),
            'app_url_ios_deliveryman' => (isset($settings['app_url_ios_deliveryman']) ? $settings['app_url_ios_deliveryman'] : null),
            'tax_included' => (int)(isset($settings['tax_included']) ? $settings['tax_included'] : 0),
            'apple_login' => $apple_login,
            'order_subscription' => (int)(isset($settings['order_subscription']) ? $settings['order_subscription'] : 0),
            'cookies_text'=>isset($settings['cookies_text'])?$settings['cookies_text']:'',

            'refund_policy_status' => (int)(self::get_settings_data('refund_policy_status')),
            'cancellation_policy_status' => (int)(self::get_settings_data('cancellation_policy_status')),
            'shipping_policy_status' => (int)(self::get_settings_data('shipping_policy_status')),

            'refund_policy_data' => (string) (self::get_settings_data('refund_policy')),
            'cancellation_policy_data' => (string)(self::get_settings_data('cancellation_policy')),
            'shipping_policy_data' => (string)(self::get_settings_data('shipping_policy')),
            'terms_and_conditions' => (string) (self::get_settings_data('terms_and_conditions')),
            'privacy_policy' => (string) (self::get_settings_data('privacy_policy')),
            'about_us' => (string) (self::get_settings_data('about_us')),

            'take_away' => (bool)(isset($settings['take_away']) ? $settings['take_away'] : false),
            'repeat_order_option' => (bool)(isset($settings['repeat_order_option']) ? $settings['repeat_order_option'] : false),
            'home_delivery' => (bool)(isset($settings['home_delivery']) ? $settings['home_delivery'] : false),
        ]);
    }


    public static function get_settings_data($name)
    {
        $data = DataSetting::where(['key' => $name])->first()?->value;
        return $data ?? 0;
    }

    public function get_zone(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lat' => 'required',
            'lng' => 'required',
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $zones = Zone::whereContains('coordinates', new Point($request->lat, $request->lng, POINT_SRID))->latest()->get(['id', 'status', 'minimum_shipping_charge',
        'increased_delivery_fee','increased_delivery_fee_status','increase_delivery_charge_message','per_km_shipping_charge','max_cod_order_amount','maximum_shipping_charge']);
        if (count($zones) < 1) {
            return response()->json([
                'errors' => [
                    ['code' => 'coordinates', 'message' => translate('messages.service_not_available_in_this_area')]
                ]
            ], 404);
        }
        $data = array_filter($zones->toArray(), function ($zone) {
            if ($zone['status'] == 1) {
                return $zone;
            }
        });

        if (count($data) > 0) {
            return response()->json(['zone_id' => json_encode(array_column($data, 'id')), 'zone_data'=>array_values($data)], 200);
        }

        return response()->json([
            'errors' => [
                ['code' => 'coordinates', 'message' => translate('messages.we_are_temporarily_unavailable_in_this_area')]
            ]
        ], 403);
    }

    public function place_api_autocomplete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search_text' => 'required',
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $response = Http::get('https://maps.googleapis.com/maps/api/place/autocomplete/json?input=' . $request['search_text'] . '&key=' . $this->map_api_key);
        return $response->json();
    }


    public function distance_api(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'origin_lat' => 'required',
            'origin_lng' => 'required',
            'destination_lat' => 'required',
            'destination_lng' => 'required',
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $response = Http::get('https://maps.googleapis.com/maps/api/distancematrix/json?origins=' . $request['origin_lat'] . ',' . $request['origin_lng'] . '&destinations=' . $request['destination_lat'] . ',' . $request['destination_lng'] . '&key=' . $this->map_api_key . '&mode=walking');
        return $response->json();
    }


    public function place_api_details(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'placeid' => 'required',
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $response = Http::get('https://maps.googleapis.com/maps/api/place/details/json?placeid=' . $request['placeid'] . '&key=' . $this->map_api_key);
        return $response->json();
    }

    public function geocode_api(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lat' => 'required',
            'lng' => 'required',
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $request->lat . ',' . $request->lng . '&key=' . $this->map_api_key);
        return $response->json();
    }

    public function landing_page(){
        $key =['react_header_banner','banner_section_full','banner_section_half' ,'footer_logo','app_section_image',
        'react_feature' ,'discount_banner','landing_page_links','react_self_registration_restaurant','react_self_registration_delivery_man'];
        $settings =  array_column(BusinessSetting::whereIn('key', $key)->get()->toArray(), 'value', 'key');

        $app_section_image = isset($settings['app_section_image']) ? json_decode($settings['app_section_image'], true) : [];

        return  response()->json(
            [
                'react_header_banner'=>(isset($settings['react_header_banner']) )  ? $settings['react_header_banner'] : null ,
                'app_section_image'=> (isset($app_section_image['app_section_image'])) ?  $app_section_image['app_section_image'] : null,
                'app_section_image_2'=> (isset($app_section_image['app_section_image_2'])) ?  $app_section_image['app_section_image_2'] : null,
                'footer_logo'=> (isset($settings['footer_logo'])) ? $settings['footer_logo'] : null,
                'banner_section_full'=> (isset($settings['banner_section_full']) )  ? json_decode($settings['banner_section_full'], true) : null ,
                'banner_section_half'=>(isset($settings['banner_section_half']) )  ? json_decode($settings['banner_section_half'], true) : [],
                'react_feature'=> (isset($settings['react_feature'])) ? json_decode($settings['react_feature'], true) : [],
                'discount_banner'=> (isset($settings['discount_banner'])) ? json_decode($settings['discount_banner'], true) : null,
                'landing_page_links'=> (isset($settings['landing_page_links'])) ? json_decode($settings['landing_page_links'], true) : null,
                'react_self_registration_restaurant'=> (isset($settings['react_self_registration_restaurant'])) ? json_decode($settings['react_self_registration_restaurant'], true) : null,
                'react_self_registration_delivery_man'=> (isset($settings['react_self_registration_delivery_man'])) ? json_decode($settings['react_self_registration_delivery_man'], true) : null,
        ]);
    }


    public function extra_charge(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'distance' => 'required',
        ]);
        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $distance_data = $request->distance ?? 0;
        $data = Helpers::vehicle_extra_charge(distance_data:$distance_data);
        $extra_charges = (float) (isset($data) ? $data['extra_charge']  : 0);
        $vehicle_id= (isset($data) ? $data['vehicle_id']  : null);

        return response()->json($extra_charges,200);
    }

    public function get_vehicles(Request $request){
        $data = Vehicle::active()->get(['id','type']);
        return response()->json($data, 200);
    }




    public function react_landing_page()
    {
        // $settings =  DataSetting::where('type','react_landing_page')->pluck('value','key')->toArray();
        $datas =  DataSetting::with('translations')->where('type','react_landing_page')->get();
        $data = [];
        foreach ($datas as $key => $value) {
            if(count($value->translations)>0){
                $cred = [
                    $value->key => $value->translations[0]['value'],
                ];
                array_push($data,$cred);
            }else{
                $cred = [
                    $value->key => $value->value,
                ];
                array_push($data,$cred);
            }
        }
        $settings = [];
        foreach($data as $single_data){
            foreach($single_data as $key=>$single_value){
                $settings[$key] = $single_value;
            }
        }

        $services=  ReactService::orderBy('id' , 'asc')->where('status',1)->get();
        $ReactPromotionalBanner=  ReactPromotionalBanner::orderBy('id' , 'asc')->where('status',1)->get();

        // dd($settings);
        $restaurant_section= [
            'react_restaurant_section_title'=>(isset($settings['react_restaurant_section_title']) )  ? $settings['react_restaurant_section_title'] : null ,
            'react_restaurant_section_sub_title'=>(isset($settings['react_restaurant_section_sub_title']) )  ? $settings['react_restaurant_section_sub_title'] : null ,
            'react_restaurant_section_button_name'=>(isset($settings['react_restaurant_section_button_name']) )  ? $settings['react_restaurant_section_button_name'] : null ,
            // 'react_restaurant_section_link_data'=> (isset($settings['react_restaurant_section_link_data']) )  ? json_decode($settings['react_restaurant_section_link_data'] , true) : [] ,
            'react_restaurant_section_link_data'=> [
                'react_restaurant_section_button_status'=>(int) (isset($settings['react_restaurant_section_button_status']) )  ? $settings['react_restaurant_section_button_status'] : 0 ,
                'react_restaurant_section_link' => isset($settings['react_restaurant_section_link_data'])   ? $settings['react_restaurant_section_link_data'] : null,
            ],
            'react_restaurant_section_image'=> (isset($settings['react_restaurant_section_image']) )  ? $settings['react_restaurant_section_image']  : null ,
        ];
        $delivery_section= [
            'react_delivery_section_title'=>(isset($settings['react_delivery_section_title']) )  ? $settings['react_delivery_section_title'] : null ,
            'react_delivery_section_sub_title'=>(isset($settings['react_delivery_section_sub_title']) )  ? $settings['react_delivery_section_sub_title'] : null ,
            'react_delivery_section_button_name'=>(isset($settings['react_delivery_section_button_name']) )  ? $settings['react_delivery_section_button_name'] : null ,
            // 'react_delivery_section_link_data'=> (isset($settings['react_delivery_section_link_data']) )  ? json_decode($settings['react_delivery_section_link_data'] , true) : [] ,
            'react_delivery_section_link_data'=> [
                'react_delivery_section_button_status'=>(int) (isset($settings['react_delivery_section_button_status']) )  ? $settings['react_delivery_section_button_status'] : 0 ,
                'react_delivery_section_link' =>  (isset($settings['react_delivery_section_link_data']) )  ? $settings['react_delivery_section_link_data'] : null,
                ],
            'react_delivery_section_image'=> (isset($settings['react_delivery_section_image']) )  ? $settings['react_delivery_section_image'] : null ,
            ];
        $download_app_section= [
            'react_download_apps_banner_image'=>(isset($settings['react_download_apps_banner_image']) )  ? $settings['react_download_apps_banner_image'] : null ,
            'react_download_apps_image'=>(isset($settings['react_download_apps_image']) )  ? $settings['react_download_apps_image'] : null ,
            'react_download_apps_title'=>(isset($settings['react_download_apps_title']) )  ? $settings['react_download_apps_title'] : null ,
            'react_download_apps_tag'=>(isset($settings['react_download_apps_tag']) )  ? $settings['react_download_apps_tag'] : null ,
            'react_download_apps_sub_title'=>(isset($settings['react_download_apps_sub_title']) )  ? $settings['react_download_apps_sub_title'] : null ,
            'react_download_apps_app_store'=> (isset($settings['react_download_apps_link_data']) )  ? json_decode($settings['react_download_apps_link_data'] , true) : [] ,
                'react_download_apps_play_store' =>[
                    'react_download_apps_play_store_link'=>(isset($settings['react_download_apps_button_name']) )  ? $settings['react_download_apps_button_name'] : null ,
                    'react_download_apps_play_store_status'=>(int) (isset($settings['react_download_apps_button_status']) )  ? $settings['react_download_apps_button_status'] : 0 ,
                ],

        ];



        return  response()->json(
            [
                'base_urls' => [
                    'react_header_image_url' => asset('storage/app/public/react_header'),
                    'react_services_image_url' => asset('storage/app/public/react_service_image'),
                    'react_promotional_banner_image_url' => asset('storage/app/public/react_promotional_banner'),
                    'react_delivery_section_image_url' => asset('storage/app/public/react_delivery_section_image'),
                    'react_restaurant_section_image_url' => asset('storage/app/public/react_restaurant_section_image'),
                    'react_download_apps_banner_image_url' => asset('storage/app/public/react_download_apps_image'),
                    'react_download_apps_image_url' => asset('storage/app/public/react_download_apps_image'),
                ],

                'react_header_title'=>(isset($settings['react_header_title']) )  ? $settings['react_header_title'] : null ,
                'react_header_sub_title'=>(isset($settings['react_header_sub_title']) )  ? $settings['react_header_sub_title'] : null ,
                'react_header_image'=>(isset($settings['react_header_image']) )  ? $settings['react_header_image'] : null ,

                'react_services' => $services ?? [],
                'react_promotional_banner' => $ReactPromotionalBanner ?? [],

                'restaurant_section' => $restaurant_section,
                'delivery_section' => $delivery_section,
                'download_app_section' => $download_app_section,

                'news_letter_sub_title'=>(isset($settings['news_letter_sub_title']) )  ? $settings['news_letter_sub_title'] : null ,
                'news_letter_title'=>(isset($settings['news_letter_title']) )  ? $settings['news_letter_title'] : null ,
                'footer_data'=>(isset($settings['footer_data']) )  ? $settings['footer_data'] : null ,
        ]);
    }

}
