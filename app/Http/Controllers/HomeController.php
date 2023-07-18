<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use App\Models\DataSetting;
use App\Models\AdminFeature;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\ContactMessage;
use App\Models\BusinessSetting;
use App\Models\AdminTestimonial;
use App\Models\AdminSpecialCriteria;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $datas =  DataSetting::with('translations')->where('type','admin_landing_page')->get();
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


        $key=['business_name'];
        $business_settings =  BusinessSetting::whereIn('key', $key)->pluck('value','key')->toArray();

        $features = AdminFeature::latest()->where('status',1)->get()->toArray();
        $testimonials = AdminTestimonial::latest()->where('status',1)->get()->toArray();

        $header_floating_content= json_decode($settings['header_floating_content'] ?? null, true);
        $header_image_content= json_decode($settings['header_image_content'] ?? null, true);

        $landing_data = [
            'header_title'=>  $settings['header_title'] ??  'Why Stay Hungry !' ,
            'header_sub_title'=> $settings['header_sub_title'] ?? 'When you can order from' ,
            'header_tag_line'=> $settings['header_tag_line'] ?? 'Get Offers' ,
            'header_app_button_name'=> $settings['header_app_button_name'] ?? 'Order now' ,
            'header_app_button_status'=> $settings['header_app_button_status'] ?? 0 ,
            'header_button_redirect_link'=>   $settings['header_button_content'] ?? null ,

            'header_floating_total_order'=>   $header_floating_content['header_floating_total_order'] ?? null ,
            'header_floating_total_user'=>   $header_floating_content['header_floating_total_user'] ?? null ,
            'header_floating_total_reviews'=>   $header_floating_content['header_floating_total_reviews'] ?? null ,

            'header_content_image'=>   $header_image_content['header_content_image'] ?? 'double_screen_image.png' ,
            'header_bg_image'=>   $header_image_content['header_bg_image'] ?? null ,

            'about_us_title'=>   $settings['about_us_title'] ?? null ,
            'about_us_sub_title'=>   $settings['about_us_sub_title'] ?? null ,
            'about_us_text'=>   $settings['about_us_text'] ?? null ,
            'about_us_app_button_name'=>   $settings['about_us_app_button_name'] ?? 'More' ,
            'about_us_app_button_status'=>   $settings['about_us_app_button_status'] ?? 0 ,

            'about_us_redirect_link'=>   $settings['about_us_button_content'] ?? null ,
            'about_us_image_content'=>   $settings['about_us_image_content'] ??  null ,

            'why_choose_us_title'=>   $settings['why_choose_us_title']?? null ,
            'why_choose_us_sub_title'=>   $settings['why_choose_us_sub_title'] ??  null ,
            'why_choose_us_image_1'=>   $settings['why_choose_us_image_1'] ??  null ,
            'why_choose_us_title_1'=>   $settings['why_choose_us_title_1'] ??  null ,
            'why_choose_us_title_2'=>   $settings['why_choose_us_title_2'] ??  null ,
            'why_choose_us_image_2'=>   $settings['why_choose_us_image_2'] ??  null ,
            'why_choose_us_title_3'=>   $settings['why_choose_us_title_3'] ??  null ,
            'why_choose_us_image_3'=>   $settings['why_choose_us_image_3'] ??  null ,
            'why_choose_us_title_4'=>   $settings['why_choose_us_title_4'] ??  null ,
            'why_choose_us_image_4'=>   $settings['why_choose_us_image_4'] ??  null ,


            'feature_title'=>   $settings['feature_title'] ??  null ,
            'feature_sub_title'=>   $settings['feature_sub_title'] ??  null ,
            'features'=> $features ?? [] ,

            'services_title'=>   $settings['services_title'] ??  null ,
            'services_sub_title'=>   $settings['services_sub_title'] ??  null ,
            'services_order_title_1'=>   $settings['services_order_title_1'] ??  null ,
            'services_order_title_2'=>   $settings['services_order_title_2'] ??  null ,
            'services_order_description_1'=>   $settings['services_order_description_1'] ??  null ,
            'services_order_description_2'=>   $settings['services_order_description_2'] ??  null ,
            'services_order_button_name'=>   $settings['services_order_button_name'] ??  null ,
            'services_order_button_status'=>   $settings['services_order_button_status'] ??  null ,
            'services_order_button_link'=>   $settings['services_order_button_link'] ??  null ,


            'services_manage_restaurant_title_1'=>   $settings['services_manage_restaurant_title_1'] ??  null ,
            'services_manage_restaurant_title_2'=>   $settings['services_manage_restaurant_title_2'] ??  null ,
            'services_manage_restaurant_description_1'=>   $settings['services_manage_restaurant_description_1'] ??  null ,
            'services_manage_restaurant_description_2'=>   $settings['services_manage_restaurant_description_2'] ??  null ,
            'services_manage_restaurant_button_name'=>   $settings['services_manage_restaurant_button_name'] ??  null ,
            'services_manage_restaurant_button_status'=>   $settings['services_manage_restaurant_button_status'] ??  null ,
            'services_manage_restaurant_button_link'=>   $settings['services_manage_restaurant_button_link'] ??  null ,


            'services_manage_delivery_title_1'=>   $settings['services_manage_delivery_title_1'] ??  null ,
            'services_manage_delivery_title_2'=>   $settings['services_manage_delivery_title_2'] ??  null ,
            'services_manage_delivery_description_1'=>   $settings['services_manage_delivery_description_1'] ??  null ,
            'services_manage_delivery_description_2'=>   $settings['services_manage_delivery_description_2'] ??  null ,
            'services_manage_delivery_button_name'=>   $settings['services_manage_delivery_button_name'] ??  null ,
            'services_manage_delivery_button_status'=>   $settings['services_manage_delivery_button_status'] ??  null ,
            'services_manage_delivery_button_link'=>   $settings['services_manage_delivery_button_link'] ??  null ,

            'testimonial_title'=> $settings['testimonial_title'] ??  null ,
            'testimonials'=> $testimonials ?? [] ,

            'earn_money_title'=>   $settings['earn_money_title'] ??  null ,
            'earn_money_sub_title'=>   $settings['earn_money_sub_title'] ??  null ,
            'earn_money_reg_title'=>   $settings['earn_money_reg_title'] ??  null ,
            'earn_money_restaurant_req_button_name'=>   $settings['earn_money_restaurant_req_button_name'] ??  null ,
            'earn_money_restaurant_req_button_status'=>   $settings['earn_money_restaurant_req_button_status'] ??  null ,
            'earn_money_delivety_man_req_button_name'=>   $settings['earn_money_delivety_man_req_button_name'] ??  null ,
            'earn_money_delivery_man_req_button_status'=>   $settings['earn_money_delivery_man_req_button_status'] ??  0 ,
            'earn_money_reg_image'=>   $settings['earn_money_reg_image'] ??  null ,

            'earn_money_delivery_req_button_link'=>   $settings['earn_money_delivery_man_req_button_link']??  null ,
            'earn_money_restaurant_req_button_link'=>   $settings['earn_money_restaurant_req_button_link'] ??  null ,

            'business_name' =>  $business_settings['business_name'] ?? 'Stackfood',

        ];


        return view('home',compact('landing_data'));
    }

    public function terms_and_conditions(Request $request)
    {
        $data = self::get_settings('terms_and_conditions');
        if ($request->expectsJson()) {
            if($request->hasHeader('X-localization')){
                $current_language = $request->header('X-localization');
                $data = self::get_settings_localization('terms_and_conditions',$current_language);
                return response()->json($data);
            }
            return response()->json($data);
        }
        return view('terms-and-conditions',compact('data'));
    }

    public function about_us(Request $request)
    {
        $data = self::get_settings('about_us');

        if ($request->expectsJson()) {
            if($request->hasHeader('X-localization')){
                $current_language = $request->header('X-localization');
                $data = self::get_settings_localization('about_us',$current_language);
                return response()->json($data);
            }
            return response()->json($data);
        }

        return view('about-us',compact('data'));
    }

    public function contact_us(Request $request)
    {
        if ($request->isMethod('POST')) {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email:filter',
                'message' => 'required',
            ],[
                'name.required' => translate('messages.Name is required!'),
                'email.required' => translate('messages.Email is required!'),
                'email.filter' => translate('messages.Must ba a valid email!'),
                'message.required' => translate('messages.Message is required!'),
            ]);

            $email = Helpers::get_settings('email_address');
            $messageData = [
                'name' => $request->name,
                'email' => $request->email,
                'message' => $request->message,
            ];
            ContactMessage::create($messageData);

            $business_name=Helpers::get_settings('business_name') ?? 'Stackfood';
            $subject='Enquiry from '.$business_name;
            try{
                if(config('mail.status')) {
                    Mail::to($email)->send(new ContactMail($messageData,$subject));
                    Toastr::success(translate('messages.Thanks_for_your_enquiry._We_will_get_back_to_you_soon.'));
                }
            }catch(\Exception $ex)
            {
                info($ex->getMessage());
            }
            return back();
        }
        return view('contact-us');
    }

    public function privacy_policy(Request $request)
    {
        $data = self::get_settings('privacy_policy');
        if ($request->expectsJson()) {
            if($request->hasHeader('X-localization')){
                $current_language = $request->header('X-localization');
                $data = self::get_settings_localization('privacy_policy',$current_language);
                return response()->json($data);
            }
            return response()->json($data);
        }
        return view('privacy-policy',compact('data'));
    }

    public function refund_policy(Request $request)
    {
        $data = self::get_settings('refund_policy');
        $status = self::get_settings('refund_policy_status');
        if ($request->expectsJson()) {
            if($request->hasHeader('X-localization')){
                $current_language = $request->header('X-localization');
                $data = self::get_settings_localization('refund_policy',$current_language);
                return response()->json($data);
            }
            return response()->json($data);
        }
        abort_if($status == 0 ,404);
        return view('refund_policy',compact('data'));
    }

    public function shipping_policy(Request $request)
    {
        $data = self::get_settings('shipping_policy');
        $status = self::get_settings('shipping_policy_status');
        if ($request->expectsJson()) {
            if($request->hasHeader('X-localization')){
                $current_language = $request->header('X-localization');
                $data = self::get_settings_localization('shipping_policy',$current_language);
                return response()->json($data);
            }
            return response()->json($data);
        }
        abort_if($status == 0 ,404);
        return view('shipping_policy',compact('data'));
    }

    public function cancellation_policy(Request $request)
    {
        $data = self::get_settings('cancellation_policy');
        $status = self::get_settings('cancellation_policy_status');
        if ($request->expectsJson()) {
            if($request->hasHeader('X-localization')){
                $current_language = $request->header('X-localization');
                $data = self::get_settings_localization('cancellation_policy',$current_language);
                return response()->json($data);
            }
            return response()->json($data);
        }
        abort_if($status == 0 ,404);
        return view('cancellation_policy',compact('data'));
    }

    public static function get_settings($name)
    {
        $data = DataSetting::where(['key' => $name])->first()?->value;
        return $data;
    }


    public function lang($local)
    {
        $direction = BusinessSetting::where('key', 'site_direction')->first();
        $direction = $direction->value ?? 'ltr';
        $language = BusinessSetting::where('key', 'system_language')->first();
        foreach (json_decode($language['value'], true) as $key => $data) {
            if ($data['code'] == $local) {
                $direction = isset($data['direction']) ? $data['direction'] : 'ltr';
            }
        }
        session()->forget('landing_language_settings');
        Helpers::landing_language_load();
        session()->put('landing_site_direction', $direction);
        session()->put('landing_local', $local);
        return redirect()->back();
    }
    public static function get_settings_localization($name,$lang)
    {
        $config = null;
        $data = DataSetting::withoutGlobalScope('translate')->with(['translations' => function ($query) use ($lang) {
            return $query->where('locale', $lang);
        }])->where(['key' => $name])->first();
        if($data && count($data->translations)>0){
            $data = $data->translations[0]['value'];
        }else{
            $data = $data ? $data->value: '';
        }
        return $data;
    }
}
