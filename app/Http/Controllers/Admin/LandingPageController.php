<?php

namespace App\Http\Controllers\Admin;

use App\Models\DataSetting;
use App\Models\Translation;
use App\Models\AdminFeature;
use App\Models\ReactService;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\BusinessSetting;
use App\Models\AdminTestimonial;
use App\Http\Controllers\Controller;
use App\Models\AdminSpecialCriteria;
use Brian2694\Toastr\Facades\Toastr;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Models\ReactPromotionalBanner;
use Illuminate\Support\Facades\Storage;



class LandingPageController extends Controller
{
    public function testimonial(){
        $testimonial_title=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','testimonial_title')->first() ?? null ;
        $language=BusinessSetting::where('key','language')->first()?->value ?? null;
        $testimonial=  AdminTestimonial::latest()->paginate(config('default_pagination'));
        return view('admin-views.landing_page.testimonial', compact('testimonial','language','testimonial_title'));
    }

    public function testimonial_store(Request $request){
        $request->validate([
            'name' => 'required|max:100',
            'designation' => 'required|max:100',
            'review' => 'required|max:1000',
            'reviewer_image' => 'required|max:2048',
            // 'company_image' => 'required|max:2048',
        ]);

        $testimonial = new AdminTestimonial();
        $testimonial->name = $request->name;
        $testimonial->designation = $request->designation;
        $testimonial->review = $request->review;
        $testimonial->reviewer_image = $request->has('reviewer_image') ? Helpers::upload(dir:'reviewer_image/', format: 'png',image: $request->file('reviewer_image')) : null ;
        $testimonial->company_image =  $request->has('company_image') ? Helpers::upload(dir:'reviewer_image/', format:'png', image: $request->file('company_image')) : null;
        $testimonial->save();

        Toastr::success(translate('messages.testimonial_added_successfully'));
        return back();
    }

    public function testimonial_status(Request $request)
    {

        if (env('APP_MODE') == 'demo' && $request->id == 1) {
            Toastr::warning('Sorry!You can not inactive this review!');
            return back();
        }
        $review = AdminTestimonial::findOrFail($request->id);
        $review->status = $request->status;
        $review->save();
        Toastr::success(translate('messages.testimonial_status_updated'));
        return back();
    }


    public function testimonial_edit($id)
    {
        $review = AdminTestimonial::withoutGlobalScope('translate')->findOrFail($id);
        return view('admin-views.landing_page.testimonial_edit', compact('review'));
    }
    public function testimonial_update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:100',
            'designation' => 'required|max:100',
            'review' => 'required|max:1000',
            'reviewer_image' => 'nullable|max:2048',
            'company_image' => 'nullable|max:2048',
        ]);

        $review = AdminTestimonial::findOrFail($id);
        $review->name = $request->name;
        $review->designation = $request->designation;
        $review->review = $request->review;
        $review->reviewer_image = $request->has('reviewer_image') ? Helpers::update( dir: 'reviewer_image/', old_image:  $review->reviewer_image, format:  'png', image:  $request->file('reviewer_image')) : $review->reviewer_image;
        $review->company_image = $request->has('company_image') ? Helpers::update( dir: 'reviewer_image/', old_image:  $review->company_image,  format: 'png',  image: $request->file('company_image')) : $review->company_image;
        $review->save();

        Toastr::success(translate('messages.testimonial_updated_successfully'));
        return back();
    }

    public function testimonial_destroy(AdminTestimonial $testimonial)
    {
        if (env('APP_MODE') == 'demo' && $testimonial->id == 1) {
            Toastr::warning(translate('messages.you_can_not_delete_this_review_please_add_a_new_review_to_delete'));
            return back();
        }

        if (Storage::disk('public')->exists('reviewer_image/' . $testimonial->reviewer_image)) {
            Storage::disk('public')->delete('reviewer_image/' . $testimonial->reviewer_image);
        }

        if (Storage::disk('public')->exists('reviewer_image/' . $testimonial->company_image)) {
            Storage::disk('public')->delete('reviewer_image/' . $testimonial->company_image);
        }

        $testimonial->delete();
        Toastr::success(translate('messages.testimonial_deleted_successfully'));
        return back();
    }

    public function features(){
        $features=  AdminFeature::latest()->paginate(config('default_pagination'));
        $feature_title=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','feature_title')->first() ?? null ;
        $feature_sub_title=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','feature_sub_title')->first() ?? null ;
       $language=BusinessSetting::where('key','language')->first()?->value ?? null ;
        return view('admin-views.landing_page.features', compact('features','feature_title','feature_sub_title','language'));
    }

    public function feature_store(Request $request){
        $request->validate([
            'name' => 'required|max:100',
            'description' => 'required|max:1000',
            'feature_image' => 'required|max:2048',
        ]);

        if($request->name[array_search('default', $request->lang)] == '' || $request->description[array_search('default', $request->lang)] == '' ){
            Toastr::error(translate('default_title_and_description_is_required'));
            return back();
            }

        $feature = new AdminFeature();
        $feature->title =  $request->name[array_search('default', $request->lang)];
        $feature->description =  $request->description[array_search('default', $request->lang)];
        $feature->image = Helpers::upload(dir:'feature_image/', format: 'png',image: $request->file('feature_image'));
        $feature->save();

        $default_lang = str_replace('_', '-', app()->getLocale());
        foreach ($request->lang as $index => $key) {
        if ($default_lang == $key && !($request->name[$index])) {
            if ($key != 'default') {
                Translation::updateOrInsert(
                    [
                        'translationable_type'  => 'App\Models\AdminFeature',
                        'translationable_id'    => $feature->id,
                        'locale'                => $key,
                        'key'                   => 'feature_name'
                    ],
                    ['value'                 => $feature->title]
                );
            }
        } else {
            if ($request->name[$index] && $key != 'default') {
                Translation::updateOrInsert(
                    [
                        'translationable_type'  => 'App\Models\AdminFeature',
                        'translationable_id'    => $feature->id,
                        'locale'                => $key,
                        'key'                   => 'feature_name'
                    ],
                    ['value'                 => $request->name[$index]]
                );
            }
        }
        if ($default_lang == $key && !($request->description[$index])) {
            if ($key != 'default') {
                Translation::updateOrInsert(
                    [
                        'translationable_type'  => 'App\Models\AdminFeature',
                        'translationable_id'    => $feature->id,
                        'locale'                => $key,
                        'key'                   => 'feature_description'
                    ],
                    ['value'                 => $feature->description]
                );
            }
        } else {
            if ($request->description[$index] && $key != 'default') {
                Translation::updateOrInsert(
                    [
                        'translationable_type'  => 'App\Models\AdminFeature',
                        'translationable_id'    => $feature->id,
                        'locale'                => $key,
                        'key'                   => 'feature_description'
                    ],
                    ['value'                 => $request->description[$index]]
                );
            }
        }
        }

        Toastr::success(translate('messages.Feature_added_successfully'));
        return back();
    }

    public function feature_status(Request $request)
    {

        if (env('APP_MODE') == 'demo' && $request->id == 1) {
            Toastr::warning('Sorry!You can not inactive this review!');
            return back();
        }
        $features = AdminFeature::findOrFail($request->id);
        $features->status = $request->status;
        $features->save();
        Toastr::success(translate('messages.feature_status_updated'));
        return back();
    }


    public function feature_edit($id)
    {
        $feature = AdminFeature::withoutGlobalScope('translate')->with('translations')->findOrFail($id);
        return view('admin-views.landing_page.feature_edit', compact('feature'));
    }
    public function feature_update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:100',
            'description' => 'required|max:1000',
            'feature_image' => 'nullable|max:2048',
        ]);

        if($request->name[array_search('default', $request->lang)] == '' || $request->description[array_search('default', $request->lang)] == '' ){
            Toastr::error(translate('default_title_and_description_is_required'));
            return back();
            }
        $feature = AdminFeature::findOrFail($id);

        $feature->title = $request->name[array_search('default', $request->lang)];
        $feature->description = $request->description[array_search('default', $request->lang)];
        $feature->image = $request->has('feature_image') ? Helpers::update( dir: 'feature_image/', old_image:  $feature->image, format:  'png', image:  $request->file('feature_image')) : $feature->image;
        $feature->save();

        $default_lang = str_replace('_', '-', app()->getLocale());
        foreach ($request->lang as $index => $key) {
        if ($default_lang == $key && !($request->name[$index])) {
            if ($key != 'default') {
                Translation::updateOrInsert(
                    [
                        'translationable_type'  => 'App\Models\AdminFeature',
                        'translationable_id'    => $feature->id,
                        'locale'                => $key,
                        'key'                   => 'feature_name'
                    ],
                    ['value'                 => $feature->title]
                );
            }
        } else {
            if ($request->name[$index] && $key != 'default') {
                Translation::updateOrInsert(
                    [
                        'translationable_type'  => 'App\Models\AdminFeature',
                        'translationable_id'    => $feature->id,
                        'locale'                => $key,
                        'key'                   => 'feature_name'
                    ],
                    ['value'                 => $request->name[$index]]
                );
            }
        }
        if ($default_lang == $key && !($request->description[$index])) {
            if ($key != 'default') {
                Translation::updateOrInsert(
                    [
                        'translationable_type'  => 'App\Models\AdminFeature',
                        'translationable_id'    => $feature->id,
                        'locale'                => $key,
                        'key'                   => 'feature_description'
                    ],
                    ['value'                 => $feature->description]
                );
            }
        } else {
            if ($request->description[$index] && $key != 'default') {
                Translation::updateOrInsert(
                    [
                        'translationable_type'  => 'App\Models\AdminFeature',
                        'translationable_id'    => $feature->id,
                        'locale'                => $key,
                        'key'                   => 'feature_description'
                    ],
                    ['value'                 => $request->description[$index]]
                );
            }
        }
        }
        Toastr::success(translate('messages.feature_updated_successfully'));
        return back();
    }


    public function feature_destroy(AdminFeature $feature)
    {
        if (env('APP_MODE') == 'demo' && $feature->id == 1) {
            Toastr::warning(translate('messages.you_can_not_delete_this_review_please_add_a_new_review_to_delete'));
            return back();
        }

        if (Storage::disk('public')->exists('feature_image/' . $feature->image)) {
            Storage::disk('public')->delete('feature_image/' . $feature->image);
        }

        $feature?->translations()?->delete();
        $feature?->delete();
        Toastr::success(translate('messages.feature_deleted_successfully'));
        return back();
    }





    public function header(){

        $header_title=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','header_title')->first() ?? null ;
        $header_sub_title=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','header_sub_title')->first() ?? null;
        $header_tag_line=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','header_tag_line')->first()  ?? null ;

        $header_image_content=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','header_image_content')->first()  ?? null;
        $header_floating_content_data=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','header_floating_content')->first()  ?? null;

        $header_app_button_name=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','header_app_button_name')->first()  ?? null;
        $header_app_button_status=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','header_app_button_status')->first()?->value  ?? null;
// dd($header_app_button_status);
        $header_floating_content_data=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','header_floating_content')->first()  ?? null;

        $header_button_content=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','header_button_content')->first()?->value  ?? null;

        // $button_content=json_decode($header_button_content?? null ,true );
        $image_content=json_decode($header_image_content?->value?? null ,true );
        $header_floating_content=json_decode($header_floating_content_data?->value?? null ,true );

        $language=BusinessSetting::where('key','language')->first()?->value ?? null;

        return view('admin-views.landing_page.header',compact('header_title','header_sub_title','header_tag_line','header_image_content','header_floating_content_data','header_app_button_name','header_app_button_status','header_floating_content_data','header_button_content','image_content','header_floating_content','language'));
    }

    public function about_us(){

        $header_title=\App\Models\DataSetting::with('translations')->withoutGlobalScope('translate')->where('type','admin_landing_page')->where('key','about_us_title')->first() ?? null ;
        $header_sub_title=\App\Models\DataSetting::with('translations')->withoutGlobalScope('translate')->where('type','admin_landing_page')->where('key','about_us_sub_title')->first() ?? null ;
        $header_tag_line=\App\Models\DataSetting::with('translations')->withoutGlobalScope('translate')->where('type','admin_landing_page')->where('key','about_us_text')->first() ?? null ;
        $about_us_button_content=\App\Models\DataSetting::with('translations')->withoutGlobalScope('translate')->where('type','admin_landing_page')->where('key','about_us_button_content')->first()?->value ?? null ;
        $header_image_content=\App\Models\DataSetting::with('translations')->withoutGlobalScope('translate')->where('type','admin_landing_page')->where('key','about_us_image_content')->first() ?? null ;

        $about_us_app_button_name=\App\Models\DataSetting::with('translations')->withoutGlobalScope('translate')->where('type','admin_landing_page')->where('key','about_us_app_button_name')->first() ?? null ;
        $about_us_app_button_status=\App\Models\DataSetting::with('translations')->withoutGlobalScope('translate')->where('type','admin_landing_page')->where('key','about_us_app_button_status')->first()?->value ?? null ;
        $about_us_content_image=$header_image_content?->value?? null ;
        $language=BusinessSetting::where('key','language')->first()?->value ?? null;


        return view('admin-views.landing_page.about_us',compact('header_title','header_sub_title','header_tag_line','header_image_content','about_us_button_content','about_us_app_button_name','about_us_app_button_status' ,'language','about_us_content_image'));
    }

    public function why_choose_us(){
        $data_1 = DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','why_choose_us_title_1')->first();
        $data_1_image = DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','why_choose_us_image_1')->first();
        $data_2 = DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','why_choose_us_title_2')->first();
        $data_2_image = DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','why_choose_us_image_2')->first();
        $data_3 = DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','why_choose_us_title_3')->first();
        $data_3_image = DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','why_choose_us_image_3')->first();
        $data_4 = DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','why_choose_us_title_4')->first();
        $data_4_image = DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','why_choose_us_image_4')->first();

        $why_choose_us_title=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','why_choose_us_title')->first();

        $why_choose_us_sub_title=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','why_choose_us_sub_title')->first();

        $language=BusinessSetting::where('key','language')->first()?->value ?? null;

        return view('admin-views.landing_page.why_choose_us', compact('data_1','data_1_image','data_2','data_2_image','data_3','data_3_image','data_4','data_4_image','why_choose_us_title','why_choose_us_sub_title','language'));
    }

    public function earn_money(){

        $earn_money_title=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','earn_money_title')->first() ?? null;
        $earn_money_sub_title=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','earn_money_sub_title')->first() ?? null;
        $earn_money_reg_title=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','earn_money_reg_title')->first() ?? null;
        $earn_money_reg_image=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','earn_money_reg_image')->first() ?? null;

        $earn_money_restaurant_req_button_name=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','earn_money_restaurant_req_button_name')->first() ?? null;
        $earn_money_restaurant_req_button_status=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','earn_money_restaurant_req_button_status')->first() ?? null;

        $earn_money_restaurant_req_button_link=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','earn_money_restaurant_req_button_link')->first()?->value  ?? null;

        $earn_money_delivety_man_req_button_name=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','earn_money_delivety_man_req_button_name')->first() ?? null;
        $earn_money_delivery_man_req_button_status=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','earn_money_delivery_man_req_button_status')->first() ?? null;

        $earn_money_delivery_man_req_button_link=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','earn_money_delivery_man_req_button_link')->first()?->value ?? null;
        $language=BusinessSetting::where('key','language')->first()?->value ?? null;

        return view('admin-views.landing_page.earn_money', compact('earn_money_title','earn_money_sub_title','earn_money_reg_title','earn_money_reg_image','earn_money_restaurant_req_button_name','earn_money_restaurant_req_button_status','earn_money_restaurant_req_button_link','earn_money_delivety_man_req_button_name','earn_money_delivery_man_req_button_status','earn_money_delivery_man_req_button_link','language'));
    }

    public function services(){
        $services_title=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','services_title')->first() ?? null;
        $services_sub_title=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','services_sub_title')->first() ?? null;

        $services_order_title_1=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','services_order_title_1')->first() ?? null;
        $services_order_title_2=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','services_order_title_2')->first() ?? null;
        $services_order_description_1=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','services_order_description_1')->first() ?? null;
        $services_order_description_2=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','services_order_description_2')->first() ?? null;

        $services_order_button_name=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','services_order_button_name')->first() ?? null;
        $services_order_button_status=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','services_order_button_status')->first() ?? null;

        $services_order_button_link=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','services_order_button_link')->first()?->value ?? null;


        $services_manage_restaurant_title_1=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','services_manage_restaurant_title_1')->first() ?? null;
        $services_manage_restaurant_title_2=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','services_manage_restaurant_title_2')->first() ?? null;
        $services_manage_restaurant_description_1=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','services_manage_restaurant_description_1')->first() ?? null;
        $services_manage_restaurant_description_2=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','services_manage_restaurant_description_2')->first() ?? null;

        $services_manage_restaurant_button_name=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','services_manage_restaurant_button_name')->first() ?? null;
        $services_manage_restaurant_button_status=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','services_manage_restaurant_button_status')->first() ?? null;

        $services_manage_restaurant_button_link=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','services_manage_restaurant_button_link')->first()?->value ?? null;

        $services_manage_delivery_title_1=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','services_manage_delivery_title_1')->first() ?? null;
        $services_manage_delivery_title_2=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','services_manage_delivery_title_2')->first() ?? null;
        $services_manage_delivery_description_1=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','services_manage_delivery_description_1')->first() ?? null;
        $services_manage_delivery_description_2=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','services_manage_delivery_description_2')->first() ?? null;

        $services_manage_delivery_button_name=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','services_manage_delivery_button_name')->first() ?? null;
        $services_manage_delivery_button_status=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','services_manage_delivery_button_status')->first() ?? null;

        $services_manage_delivery_button_link=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','services_manage_delivery_button_link')->first()?->value ?? null;

        $language=BusinessSetting::where('key','language')->first()?->value ?? null;

        return view('admin-views.landing_page.services',compact('services_title','services_sub_title','services_order_title_1','services_order_title_2','services_order_description_1',
        'services_order_description_2','services_order_button_name','services_order_button_status','services_order_button_link',
        'services_manage_restaurant_title_1','services_manage_restaurant_title_2','services_manage_restaurant_description_1','services_manage_restaurant_description_2','services_manage_restaurant_button_name',
        'services_manage_restaurant_button_status','services_manage_restaurant_button_link',
        'services_manage_delivery_title_1','services_manage_delivery_title_2','services_manage_delivery_description_1','services_manage_delivery_description_2','services_manage_delivery_button_name',
        'services_manage_delivery_button_status','services_manage_delivery_button_link'
        ,'language'));
    }

    public function fixed_data(){

        $news_letter_title=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','news_letter_title')->first() ?? null;
        $news_letter_sub_title=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','news_letter_sub_title')->first() ?? null;
        $footer_data=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','footer_data')->first() ?? null;
        $language=BusinessSetting::where('key','language')->first()?->value ?? null ;
        // $copyright_text=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','admin_landing_page')->where('key','copyright_text')->first() ?? null;

        return view('admin-views.landing_page.fixed_data' , compact('news_letter_title','news_letter_sub_title','footer_data' ,'language'));
    }

    public function links(){

        $landing_page_links = BusinessSetting::where('key','landing_page_links')->first();
        $landing_page_links = isset($landing_page_links->value)?json_decode($landing_page_links->value, true):null;

        return view('admin-views.landing_page.links',compact('landing_page_links'));
    }
    public function backgroung_color(){
        $backgroundChange = BusinessSetting::where(['key' => 'backgroundChange'])->first();
        $backgroundChange = isset($backgroundChange->value) ? json_decode($backgroundChange->value, true) : null;

        return view('admin-views.landing_page.backgroundChange',compact('backgroundChange'));
    }
    public function react_header(){

        $react_header_title=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','react_landing_page')->where('key','react_header_title')->first() ?? null;
        $react_header_sub_title=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','react_landing_page')->where('key','react_header_sub_title')->first() ?? null;
        $react_header_image=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','react_landing_page')->where('key','react_header_image')->first() ?? null;

        $language=BusinessSetting::where('key','language')->first()?->value ?? null;

        return view('admin-views.landing_page.react.header',compact('react_header_title','react_header_sub_title','react_header_image','language'));
    }

    public function react_fixed_data(){
        $news_letter_title=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','react_landing_page')->where('key','news_letter_title')->first() ?? null;
        $news_letter_sub_title=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','react_landing_page')->where('key','news_letter_sub_title')->first() ?? null;
        $footer_data=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','react_landing_page')->where('key','footer_data')->first() ?? null;
        // $copyright_text=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','react_landing_page')->where('key','copyright_text')->first() ?? null;
        $language=BusinessSetting::where('key','language')->first()?->value ?? null ;

        return view('admin-views.landing_page.react.fixed_data' , compact('news_letter_title','news_letter_sub_title','footer_data', 'language'));
    }
    public function react_services(Request $request){
        $key = explode(' ', $request['search']);

        $services=  ReactService::latest()
        ->when(isset($request['search']), function ($query) use($key){
            $query->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('title', 'like', "%{$value}%");
                }
            });
        })
        ->paginate(config('default_pagination'));
        return view('admin-views.landing_page.react.services',compact('services'));
    }
    public function service_export(Request $request){
        $key = explode(' ', $request['search']);

        $services=  ReactService::latest()->with('translations')
        ->when(isset($request['search']), function ($query) use($key){
            $query->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('title', 'like', "%{$value}%");
                }
            });
        })
        ->get();
        if($request->type == 'csv'){
            return (new FastExcel(Helpers::react_services_formater($services)))->download('React_services.csv');
        }
        return (new FastExcel(Helpers::react_services_formater($services)))->download('React_services.xlsx');
    }
    public function react_service_store(Request $request){
        $request->validate([
            'title' => 'required|max:100',
            'sub_title' => 'required|max:254',
            'image' => 'required|max:2048',
        ]);


        if($request->title[array_search('default', $request->lang)] == '' || $request->sub_title[array_search('default', $request->lang)] == '' ){
            Toastr::error(translate('default_title_and_subtitle_is_required'));
            return back();
            }


        $ReactService = new ReactService();
        $ReactService->title =  $request->title[array_search('default', $request->lang)];
        $ReactService->sub_title =  $request->sub_title[array_search('default', $request->lang)];
        $ReactService->image = Helpers::upload(dir:'react_service_image/', format: 'png',image: $request->file('image'));
        $ReactService->save();

        $default_lang = str_replace('_', '-', app()->getLocale());
        foreach ($request->lang as $index => $key) {
        if ($default_lang == $key && !($request->title[$index])) {
            if ($key != 'default') {
                Translation::updateOrInsert(
                    [
                        'translationable_type'  => 'App\Models\ReactService',
                        'translationable_id'    => $ReactService->id,
                        'locale'                => $key,
                        'key'                   => 'react_service_title'
                    ],
                    ['value'                 => $ReactService->title]
                );
            }
        } else {
            if ($request->title[$index] && $key != 'default') {
                Translation::updateOrInsert(
                    [
                        'translationable_type'  => 'App\Models\ReactService',
                        'translationable_id'    => $ReactService->id,
                        'locale'                => $key,
                        'key'                   => 'react_service_title'
                    ],
                    ['value'                 => $request->title[$index]]
                );
            }
        }
        if ($default_lang == $key && !($request->sub_title[$index])) {
            if ($key != 'default') {
                Translation::updateOrInsert(
                    [
                        'translationable_type'  => 'App\Models\ReactService',
                        'translationable_id'    => $ReactService->id,
                        'locale'                => $key,
                        'key'                   => 'react_service_sub_title'
                    ],
                    ['value'                 => $ReactService->sub_title]
                );
            }
        } else {
            if ($request->sub_title[$index] && $key != 'default') {
                Translation::updateOrInsert(
                    [
                        'translationable_type'  => 'App\Models\ReactService',
                        'translationable_id'    => $ReactService->id,
                        'locale'                => $key,
                        'key'                   => 'react_service_sub_title'
                    ],
                    ['value'                 => $request->sub_title[$index]]
                );
            }
        }

        }

        Toastr::success(translate('messages.React_service_added_successfully'));
        return back();
    }

    public function react_service_status(Request $request)
    {
        if (env('APP_MODE') == 'demo' && $request->id == 1) {
            Toastr::warning('Sorry!You can not inactive this review!');
            return back();
        }
        $ReactService = ReactService::findOrFail($request->id);
        $ReactService->status = $request->status;
        $ReactService->save();
        Toastr::success(translate('messages.React_service_status_updated'));
        return back();
    }

    public function react_service_edit($id)
    {
        $service = ReactService::withoutGlobalScope('translate')->with('translations')->findOrFail($id);
        return view('admin-views.landing_page.react.service_edit', compact('service'));
    }
    public function react_service_update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|max:100',
            'sub_title' => 'required|max:254',
            'image' => 'nullable|max:2048',
        ]);


        if($request->title[array_search('default', $request->lang)] == '' || $request->sub_title[array_search('default', $request->lang)] == '' ){
            Toastr::error(translate('default_title_and_subtitle_is_required'));
            return back();
            }


        $ReactService = ReactService::findOrFail($id);

        $ReactService->title = $request->title[array_search('default', $request->lang)];
        $ReactService->sub_title = $request->sub_title[array_search('default', $request->lang)];
        $ReactService->image = $request->has('image') ? Helpers::update( dir: 'react_service_image/', old_image:  $ReactService->image, format:  'png', image:  $request->file('image')) : $ReactService->image;
        $ReactService->save();

        $default_lang = str_replace('_', '-', app()->getLocale());
        foreach ($request->lang as $index => $key) {
            if ($default_lang == $key && !($request->title[$index])) {
                if ($key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type'  => 'App\Models\ReactService',
                            'translationable_id'    => $ReactService->id,
                            'locale'                => $key,
                            'key'                   => 'react_service_title'
                        ],
                        ['value'                 => $ReactService->title]
                    );
                }
            } else {
                if ($request->title[$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type'  => 'App\Models\ReactService',
                            'translationable_id'    => $ReactService->id,
                            'locale'                => $key,
                            'key'                   => 'react_service_title'
                        ],
                        ['value'                 => $request->title[$index]]
                    );
                }
            }
            if ($default_lang == $key && !($request->sub_title[$index])) {
                if ($key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type'  => 'App\Models\ReactService',
                            'translationable_id'    => $ReactService->id,
                            'locale'                => $key,
                            'key'                   => 'react_service_sub_title'
                        ],
                        ['value'                 => $ReactService->sub_title]
                    );
                }
            } else {
                if ($request->sub_title[$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type'  => 'App\Models\ReactService',
                            'translationable_id'    => $ReactService->id,
                            'locale'                => $key,
                            'key'                   => 'react_service_sub_title'
                        ],
                        ['value'                 => $request->sub_title[$index]]
                    );
                }
            }
        }
        Toastr::success(translate('messages.React_service_updated_successfully'));
        return back();
    }

    public function react_service_destroy(ReactService $service)
    {
        if (env('APP_MODE') == 'demo' && $service->id == 1) {
            Toastr::warning(translate('messages.you_can_not_delete_this_review_please_add_a_new_review_to_delete'));
            return back();
        }

        if (Storage::disk('public')->exists('react_service_image/' . $service->image)) {
            Storage::disk('public')->delete('react_service_image/' . $service->image);
        }

        $service?->translations()?->delete();
        $service?->delete();
        Toastr::success(translate('messages.React_service_deleted_successfully'));
        return back();
    }


    public function react_promotional_banner(Request $request){
        $key = explode(' ', $request['search']);
        $react_promotional_banners=  ReactPromotionalBanner::latest()->with('translations')
        ->when(isset($request['search']), function ($query) use($key){
            $query->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('title', 'like', "%{$value}%");
                }
            });
        })
        ->paginate(config('default_pagination'));
        return view('admin-views.landing_page.react.promotional_banner',compact('react_promotional_banners'));
    }
    public function react_promotional_banner_store(Request $request){
        $request->validate([
            'title' => 'nullable|max:100',
            'description' => 'nullable|max:1000',
            'image' => 'required|max:2048',
        ]);

        $react_promotional_banner = new ReactPromotionalBanner();
        $react_promotional_banner->title =  $request->title[array_search('default', $request->lang)];
        $react_promotional_banner->description =  $request->description[array_search('default', $request->lang)];
        $react_promotional_banner->image = Helpers::upload(dir:'react_promotional_banner/', format: 'png',image: $request->file('image'));
        $react_promotional_banner->save();

        $default_lang = str_replace('_', '-', app()->getLocale());
        foreach ($request->lang as $index => $key) {
        if ($default_lang == $key && !($request->title[$index])) {
            if ($key != 'default') {
                Translation::updateOrInsert(
                    [
                        'translationable_type'  => 'App\Models\ReactPromotionalBanner',
                        'translationable_id'    => $react_promotional_banner->id,
                        'locale'                => $key,
                        'key'                   => 'react_promotional_banner_title'
                    ],
                    ['value'                 => $react_promotional_banner->title]
                );
            }
        } else {
            if ($request->title[$index] && $key != 'default') {
                Translation::updateOrInsert(
                    [
                        'translationable_type'  => 'App\Models\ReactPromotionalBanner',
                        'translationable_id'    => $react_promotional_banner->id,
                        'locale'                => $key,
                        'key'                   => 'react_promotional_banner_title'
                    ],
                    ['value'                 => $request->title[$index]]
                );
            }
        }
        if ($default_lang == $key && !($request->description[$index])) {
            if ($key != 'default') {
                Translation::updateOrInsert(
                    [
                        'translationable_type'  => 'App\Models\ReactPromotionalBanner',
                        'translationable_id'    => $react_promotional_banner->id,
                        'locale'                => $key,
                        'key'                   => 'react_promotional_banner_description'
                    ],
                    ['value'                 => $react_promotional_banner->description]
                );
            }
        } else {
            if ($request->description[$index] && $key != 'default') {
                Translation::updateOrInsert(
                    [
                        'translationable_type'  => 'App\Models\ReactPromotionalBanner',
                        'translationable_id'    => $react_promotional_banner->id,
                        'locale'                => $key,
                        'key'                   => 'react_promotional_banner_description'
                    ],
                    ['value'                 => $request->description[$index]]
                );
            }
        }

        }

        Toastr::success(translate('messages.React_promotional_banner_added_successfully'));
        return back();
    }

    public function react_promotional_banner_status(Request $request)
    {
        if (env('APP_MODE') == 'demo' && $request->id == 1) {
            Toastr::warning('Sorry!You can not inactive this review!');
            return back();
        }
        $ReactPromotionalBanner = ReactPromotionalBanner::findOrFail($request->id);
        $ReactPromotionalBanner->status = $request->status;
        $ReactPromotionalBanner->save();
        Toastr::success(translate('messages.React_promotional_banner_status_updated'));
        return back();
    }

    public function react_promotional_banner_edit($id)
    {
        $react_promotional_banner = ReactPromotionalBanner::withoutGlobalScope('translate')->with('translations')->findOrFail($id);
        return view('admin-views.landing_page.react.promotional_banner_edit', compact('react_promotional_banner'));
    }
    public function react_promotional_banner_update(Request $request, $id)
    {
        $request->validate([
            'title' => 'nullable|max:100',
            'description' => 'nullable|max:1000',
            'image' => 'required|max:2048',
        ]);

        $ReactPromotionalBanner = ReactPromotionalBanner::findOrFail($id);

        $ReactPromotionalBanner->title = $request->title[array_search('default', $request->lang)];
        $ReactPromotionalBanner->description = $request->description[array_search('default', $request->lang)];
        $ReactPromotionalBanner->image = $request->has('image') ? Helpers::update( dir: 'react_promotional_banner/', old_image:  $ReactPromotionalBanner->image, format:  'png', image:  $request->file('image')) : $ReactPromotionalBanner->image;
        $ReactPromotionalBanner->save();

        $default_lang = str_replace('_', '-', app()->getLocale());
        foreach ($request->lang as $index => $key) {
            if ($default_lang == $key && !($request->title[$index])) {
                if ($key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type'  => 'App\Models\ReactPromotionalBanner',
                            'translationable_id'    => $ReactPromotionalBanner->id,
                            'locale'                => $key,
                            'key'                   => 'react_promotional_banner_title'
                        ],
                        ['value'                 => $ReactPromotionalBanner->title]
                    );
                }
            } else {
                if ($request->title[$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type'  => 'App\Models\ReactPromotionalBanner',
                            'translationable_id'    => $ReactPromotionalBanner->id,
                            'locale'                => $key,
                            'key'                   => 'react_promotional_banner_title'
                        ],
                        ['value'                 => $request->title[$index]]
                    );
                }
            }
            if ($default_lang == $key && !($request->description[$index])) {
                if ($key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type'  => 'App\Models\ReactPromotionalBanner',
                            'translationable_id'    => $ReactPromotionalBanner->id,
                            'locale'                => $key,
                            'key'                   => 'react_promotional_banner_description'
                        ],
                        ['value'                 => $ReactPromotionalBanner->description]
                    );
                }
            } else {
                if ($request->description[$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type'  => 'App\Models\ReactPromotionalBanner',
                            'translationable_id'    => $ReactPromotionalBanner->id,
                            'locale'                => $key,
                            'key'                   => 'react_promotional_banner_description'
                        ],
                        ['value'                 => $request->description[$index]]
                    );
                }
            }
        }
        Toastr::success(translate('messages.React_promotional_banner_updated_successfully'));
        return back();
    }

    public function react_promotional_banner_destroy(ReactPromotionalBanner $react_promotional_banner)
    {
        if (env('APP_MODE') == 'demo' && $react_promotional_banner->id == 1) {
            Toastr::warning(translate('messages.you_can_not_delete_this_review_please_add_a_new_review_to_delete'));
            return back();
        }

        if (Storage::disk('public')->exists('react_promotional_banner/' . $react_promotional_banner->image)) {
            Storage::disk('public')->delete('react_promotional_banner/' . $react_promotional_banner->image);
        }

        $react_promotional_banner?->translations()?->delete();
        $react_promotional_banner?->delete();
        Toastr::success(translate('messages.React_promotional_banner_deleted_successfully'));
        return back();
    }
    public function react_promotional_banners_export(Request $request){
        $key = explode(' ', $request['search']);

        $react_promotional_banner=  ReactPromotionalBanner::latest()->with('translations')
        ->when(isset($request['search']), function ($query) use($key){
            $query->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('title', 'like', "%{$value}%");
                }
            });
        })
        ->get();
        if($request->type == 'csv'){
            return (new FastExcel(Helpers::react_react_promotional_banner_formater($react_promotional_banner)))->download('React_react_promotional_banner.csv');
        }
        return (new FastExcel(Helpers::react_react_promotional_banner_formater($react_promotional_banner)))->download('React_react_promotional_banner.xlsx');
    }


    public function registration_scetion(){

        $react_restaurant_section_title=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','react_landing_page')->where('key','react_restaurant_section_title')->first() ?? null;
        $react_restaurant_section_sub_title=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','react_landing_page')->where('key','react_restaurant_section_sub_title')->first() ?? null;
        $react_restaurant_section_image=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','react_landing_page')->where('key','react_restaurant_section_image')->first() ?? null;
        $react_restaurant_section_button_status=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','react_landing_page')->where('key','react_restaurant_section_button_status')->first() ?? null;
        $react_restaurant_section_button_name=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','react_landing_page')->where('key','react_restaurant_section_button_name')->first() ?? null;
        $react_restaurant_section_link_data=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','react_landing_page')->where('key','react_restaurant_section_link_data')->first()?->value ?? null;


        $react_delivery_section_title=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','react_landing_page')->where('key','react_delivery_section_title')->first() ?? null;
        $react_delivery_section_sub_title=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','react_landing_page')->where('key','react_delivery_section_sub_title')->first() ?? null;
        $react_delivery_section_image=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','react_landing_page')->where('key','react_delivery_section_image')->first() ?? null;
        $react_delivery_section_button_status=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','react_landing_page')->where('key','react_delivery_section_button_status')->first() ?? null;
        $react_delivery_section_button_name=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','react_landing_page')->where('key','react_delivery_section_button_name')->first() ?? null;
        $react_delivery_section_link_data=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','react_landing_page')->where('key','react_delivery_section_link_data')->first()?->value ?? null;


        $language=BusinessSetting::where('key','language')->first()?->value ?? null;

        return view('admin-views.landing_page.react.registration_scetion' ,compact('react_restaurant_section_title','react_restaurant_section_sub_title','react_restaurant_section_button_name','react_restaurant_section_image','react_restaurant_section_button_status','react_restaurant_section_link_data','react_delivery_section_title','react_delivery_section_sub_title','language','react_delivery_section_image','react_delivery_section_button_status','react_delivery_section_button_name','react_delivery_section_link_data'));
    }


    public function download_apps(){
        $react_download_apps_banner_image=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','react_landing_page')->where('key','react_download_apps_banner_image')->first() ?? null;
        $react_download_apps_title=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','react_landing_page')->where('key','react_download_apps_title')->first() ?? null;
        $react_download_apps_sub_title=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','react_landing_page')->where('key','react_download_apps_sub_title')->first() ?? null;
        $react_download_apps_tag=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','react_landing_page')->where('key','react_download_apps_tag')->first() ?? null;
        $react_download_apps_image=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','react_landing_page')->where('key','react_download_apps_image')->first() ?? null;
        $react_download_apps_button_status=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','react_landing_page')->where('key','react_download_apps_button_status')->first() ?? null;
        $react_download_apps_button_name=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','react_landing_page')->where('key','react_download_apps_button_name')->first() ?? null;
        $react_download_apps_link_data=DataSetting::withoutGlobalScope('translate')->with('translations')->where('type','react_landing_page')->where('key','react_download_apps_link_data')->first() ?? null;
        $react_download_apps_link_data=json_decode($react_download_apps_link_data?->value ?? null ,true );
        $language=BusinessSetting::where('key','language')->first()?->value ?? null;


        return view('admin-views.landing_page.react.download_apps',compact('react_download_apps_banner_image','react_download_apps_title','react_download_apps_sub_title','react_download_apps_image','react_download_apps_button_status','react_download_apps_button_name','react_download_apps_link_data','react_download_apps_tag','language'));
    }

    public function update_react_landing_page_settings(Request $request, $tab){
        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('messages.update_option_is_disable_for_demo'));
            return back();
        }
        elseif($tab == 'fixed-data-newsletter'){
            $request->validate([
                'title' => 'required|max:254',
                'sub_title' => 'required|max:254',
            ]);

            if($request->title[array_search('default', $request->lang)] == '' || $request->sub_title[array_search('default', $request->lang)] == '' ){
            Toastr::error(translate('default_title_and_subtitle_is_required'));
            return back();
            }

            $this->update_data($request , $request->key , 'title' , 'react_landing_page');
            $this->update_data($request , $request->key_2 , 'sub_title' , 'react_landing_page');

            Toastr::success(translate('messages.React_landing_page_newsletter_updated'));
            return back();
        } elseif($tab == 'fixed-data-footer'){
            $request->validate([
                'footer_data' => 'required|max:1000',
                // 'copyright_text' => 'nullable|max:100',
            ]);



            if($request->footer_data[array_search('default', $request->lang)] == '' ){
            Toastr::error(translate('default_footer_descrtption_is_required'));
            return back();
            }


            $this->update_data($request , $request->footer_key , 'footer_data', 'react_landing_page');
            // $this->update_data($request , $request->key_copyright , 'copyright_text', 'react_landing_page');

            Toastr::success(translate('messages.React_landing_page_footer_description_updated'));
            return back();
        }
        elseif ($tab == 'react-header') {
            $request->validate([
                'react_header_title' => 'required|max:254',
                'react_header_sub_title' => 'required|max:254',
                'react_header_image' => 'nullable|max:2048',
            ]);

            if($request->react_header_title[array_search('default', $request->lang)] == '' || $request->react_header_sub_title[array_search('default', $request->lang)] == '' ){
            Toastr::error(translate('default_title_and_subtitle_is_required'));
            return back();
            }


            $react_header_title = DataSetting::where('type', 'react_landing_page')->where('key', 'react_header_title')->first();
            if ($react_header_title == null) {
                $react_header_title = new DataSetting();
            }
            $react_header_title->key = 'react_header_title';
            $react_header_title->type = 'react_landing_page';
            $react_header_title->value = $request->react_header_title[array_search('default', $request->lang)];
            $react_header_title->save();

            $react_header_sub_title = DataSetting::where('type', 'react_landing_page')->where('key', 'react_header_sub_title')->first();
            if ($react_header_sub_title == null) {
                $react_header_sub_title = new DataSetting();
            }
            $react_header_sub_title->key = 'react_header_sub_title';
            $react_header_sub_title->type = 'react_landing_page';
            $react_header_sub_title->value = $request->react_header_sub_title[array_search('default', $request->lang)];
            $react_header_sub_title->save();


            if ($request->has('react_header_image'))   {

                $react_header_image = DataSetting::where('type', 'react_landing_page')->where('key', 'react_header_image')->first();
                $imageName1 = null;
                if($react_header_image){
                    $imageName1 = $react_header_image?->value;
                }
                if ($react_header_image == null) {
                    $react_header_image = new DataSetting();
                }
                $react_header_image->key = 'react_header_image';
                $react_header_image->type = 'react_landing_page';
                    if (empty($imageName1)) {
                        $imageName1 = Helpers::upload( dir:'react_header/',format: 'png',image: $request->file('react_header_image'));
                        }  else{
                        $imageName1= Helpers::update( dir: 'react_header/',old_image: $imageName1,format: 'png', image:$request->file('react_header_image')) ;
                        }
                        $react_header_image->value =  $imageName1;
                        $react_header_image->save();
                }



            $default_lang = str_replace('_', '-', app()->getLocale());
            foreach ($request->lang as $index => $key) {
                if ($default_lang == $key && !($request->react_header_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $react_header_title->id,
                                'locale' => $key,
                                'key' => 'react_header_title'
                            ],
                            ['value' => $react_header_title->value]
                        );
                    }
                } else {
                    if ($request->react_header_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $react_header_title->id,
                                'locale' => $key,
                                'key' => 'react_header_title'
                            ],
                            ['value' => $request->react_header_title[$index]]
                        );
                    }
                }
                if ($default_lang == $key && !($request->react_header_sub_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $react_header_sub_title->id,
                                'locale' => $key,
                                'key' => 'react_header_sub_title'
                            ],
                            ['value' => $react_header_sub_title->value]
                        );
                    }
                } else {
                    if ($request->react_header_sub_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $react_header_sub_title->id,
                                'locale' => $key,
                                'key' => 'react_header_sub_title'
                            ],
                            ['value' => $request->react_header_sub_title[$index]]
                        );
                    }
                }

            }
            Toastr::success(translate('messages.React_landing_page_header_updated'));
            return back();
        }
        elseif ($tab == 'react-regisrtation-section-content') {

            $request->validate([
                'react_restaurant_section_title' => 'required|max:254',
                'react_restaurant_section_sub_title' => 'required|max:254',
                'react_restaurant_section_image' => 'nullable|max:2048',
                'react_delivery_section_title' => 'required|max:254',
                'react_delivery_section_sub_title' => 'required|max:254',
                'react_delivery_section_image' => 'nullable|max:2048',
            ]);

            if($request->react_restaurant_section_title[array_search('default', $request->lang)] == '' || $request->react_restaurant_section_sub_title[array_search('default', $request->lang)] == '' || $request->react_delivery_section_title[array_search('default', $request->lang)] == '' || $request->react_delivery_section_sub_title[array_search('default', $request->lang)] == '' ){
            Toastr::error(translate('default_title_and_subtitle_is_required'));
            return back();
            }


            $react_restaurant_section_title = DataSetting::where('type', 'react_landing_page')->where('key', 'react_restaurant_section_title')->first();
            if ($react_restaurant_section_title == null) {
                $react_restaurant_section_title = new DataSetting();
            }
            $react_restaurant_section_title->key = 'react_restaurant_section_title';
            $react_restaurant_section_title->type = 'react_landing_page';
            $react_restaurant_section_title->value = $request->react_restaurant_section_title[array_search('default', $request->lang)];
            $react_restaurant_section_title->save();

            $react_restaurant_section_sub_title = DataSetting::where('type', 'react_landing_page')->where('key', 'react_restaurant_section_sub_title')->first();
            if ($react_restaurant_section_sub_title == null) {
                $react_restaurant_section_sub_title = new DataSetting();
            }
            $react_restaurant_section_sub_title->key = 'react_restaurant_section_sub_title';
            $react_restaurant_section_sub_title->type = 'react_landing_page';
            $react_restaurant_section_sub_title->value = $request->react_restaurant_section_sub_title[array_search('default', $request->lang)];
            $react_restaurant_section_sub_title->save();

            $react_restaurant_section_button_name = DataSetting::where('type', 'react_landing_page')->where('key', 'react_restaurant_section_button_name')->first();
            if ($react_restaurant_section_button_name == null) {
                $react_restaurant_section_button_name = new DataSetting();
            }
            $react_restaurant_section_button_name->key = 'react_restaurant_section_button_name';
            $react_restaurant_section_button_name->type = 'react_landing_page';
            $react_restaurant_section_button_name->value = $request->react_restaurant_section_button_name[array_search('default', $request->lang)];
            $react_restaurant_section_button_name->save();

            $react_restaurant_section_button_status = DataSetting::where('type', 'react_landing_page')->where('key', 'react_restaurant_section_button_status')->first();
            if ($react_restaurant_section_button_status == null) {
                $react_restaurant_section_button_status = new DataSetting();
            }
            $react_restaurant_section_button_status->key = 'react_restaurant_section_button_status';
            $react_restaurant_section_button_status->type = 'react_landing_page';
            $react_restaurant_section_button_status->value = $request->react_restaurant_section_button_status ?? 0;
            $react_restaurant_section_button_status->save();


            if ($request->has('react_restaurant_section_image'))   {

                $react_restaurant_section_image = DataSetting::where('type', 'react_landing_page')->where('key', 'react_restaurant_section_image')->first();
                $imageName1 = null;
                if($react_restaurant_section_image){
                    $imageName1 = $react_restaurant_section_image?->value;
                }
                if ($react_restaurant_section_image == null) {
                    $react_restaurant_section_image = new DataSetting();
                }
                $react_restaurant_section_image->key = 'react_restaurant_section_image';
                $react_restaurant_section_image->type = 'react_landing_page';
                    if (empty($imageName1)) {
                        $imageName1 = Helpers::upload( dir:'react_restaurant_section_image/',format: 'png',image: $request->file('react_restaurant_section_image'));
                        }  else{
                        $imageName1= Helpers::update( dir: 'react_restaurant_section_image/',old_image: $imageName1,format: 'png', image:$request->file('react_restaurant_section_image')) ;
                        }
                        $react_restaurant_section_image->value =  $imageName1;
                        $react_restaurant_section_image->save();
                }

                $react_restaurant_section_link_data = DataSetting::where('type', 'react_landing_page')->where('key', 'react_restaurant_section_link_data')->first();
                if ($react_restaurant_section_link_data == null) {
                    $react_restaurant_section_link_data = new DataSetting();
                }

                $react_restaurant_section_link_data->key = 'react_restaurant_section_link_data';
                $react_restaurant_section_link_data->type = 'react_landing_page';
                $react_restaurant_section_link_data->value = $request->react_restaurant_section_link;
                $react_restaurant_section_link_data->save();



            $react_delivery_section_title = DataSetting::where('type', 'react_landing_page')->where('key', 'react_delivery_section_title')->first();
            if ($react_delivery_section_title == null) {
                $react_delivery_section_title = new DataSetting();
            }
            $react_delivery_section_title->key = 'react_delivery_section_title';
            $react_delivery_section_title->type = 'react_landing_page';
            $react_delivery_section_title->value = $request->react_delivery_section_title[array_search('default', $request->lang)];
            $react_delivery_section_title->save();

            $react_delivery_section_sub_title = DataSetting::where('type', 'react_landing_page')->where('key', 'react_delivery_section_sub_title')->first();
            if ($react_delivery_section_sub_title == null) {
                $react_delivery_section_sub_title = new DataSetting();
            }
            $react_delivery_section_sub_title->key = 'react_delivery_section_sub_title';
            $react_delivery_section_sub_title->type = 'react_landing_page';
            $react_delivery_section_sub_title->value = $request->react_delivery_section_sub_title[array_search('default', $request->lang)];
            $react_delivery_section_sub_title->save();

            $react_delivery_section_button_name = DataSetting::where('type', 'react_landing_page')->where('key', 'react_delivery_section_button_name')->first();
            if ($react_delivery_section_button_name == null) {
                $react_delivery_section_button_name = new DataSetting();
            }
            $react_delivery_section_button_name->key = 'react_delivery_section_button_name';
            $react_delivery_section_button_name->type = 'react_landing_page';
            $react_delivery_section_button_name->value = $request->react_delivery_section_button_name[array_search('default', $request->lang)];
            $react_delivery_section_button_name->save();

            $react_delivery_section_button_status = DataSetting::where('type', 'react_landing_page')->where('key', 'react_delivery_section_button_status')->first();
            if ($react_delivery_section_button_status == null) {
                $react_delivery_section_button_status = new DataSetting();
            }
            $react_delivery_section_button_status->key = 'react_delivery_section_button_status';
            $react_delivery_section_button_status->type = 'react_landing_page';
            $react_delivery_section_button_status->value = $request->react_delivery_section_button_status ?? 0;
            $react_delivery_section_button_status->save();


            if ($request->has('react_delivery_section_image'))   {

                $react_delivery_section_image = DataSetting::where('type', 'react_landing_page')->where('key', 'react_delivery_section_image')->first();
                $imageName1 = null;
                if($react_delivery_section_image){
                    $imageName1 = $react_delivery_section_image?->value;
                }
                if ($react_delivery_section_image == null) {
                    $react_delivery_section_image = new DataSetting();
                }
                $react_delivery_section_image->key = 'react_delivery_section_image';
                $react_delivery_section_image->type = 'react_landing_page';
                    if (empty($imageName1)) {
                        $imageName1 = Helpers::upload( dir:'react_delivery_section_image/',format: 'png',image: $request->file('react_delivery_section_image'));
                        }  else{
                        $imageName1= Helpers::update( dir: 'react_delivery_section_image/',old_image: $imageName1,format: 'png', image:$request->file('react_delivery_section_image')) ;
                        }
                        $react_delivery_section_image->value =  $imageName1;
                        $react_delivery_section_image->save();
                }

                $react_delivery_section_link_data = DataSetting::where('type', 'react_landing_page')->where('key', 'react_delivery_section_link_data')->first();
                if ($react_delivery_section_link_data == null) {
                    $react_delivery_section_link_data = new DataSetting();
                }

                $react_delivery_section_link_data->key = 'react_delivery_section_link_data';
                $react_delivery_section_link_data->type = 'react_landing_page';

                $react_delivery_section_link_data->value =  $request->react_delivery_section_link;
                $react_delivery_section_link_data->save();

            $default_lang = str_replace('_', '-', app()->getLocale());
            foreach ($request->lang as $index => $key) {

                if ($default_lang == $key && !($request->react_restaurant_section_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $react_restaurant_section_title->id,
                                'locale' => $key,
                                'key' => 'react_restaurant_section_title'
                            ],
                            ['value' => $react_restaurant_section_title->value]
                        );
                    }
                } else {
                    if ($request->react_restaurant_section_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $react_restaurant_section_title->id,
                                'locale' => $key,
                                'key' => 'react_restaurant_section_title'
                            ],
                            ['value' => $request->react_restaurant_section_title[$index]]
                        );
                    }
                }
                if ($default_lang == $key && !($request->react_restaurant_section_sub_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $react_restaurant_section_sub_title->id,
                                'locale' => $key,
                                'key' => 'react_restaurant_section_sub_title'
                            ],
                            ['value' => $react_restaurant_section_sub_title->value]
                        );
                    }
                } else {
                    if ($request->react_restaurant_section_sub_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $react_restaurant_section_sub_title->id,
                                'locale' => $key,
                                'key' => 'react_restaurant_section_sub_title'
                            ],
                            ['value' => $request->react_restaurant_section_sub_title[$index]]
                        );
                    }
                }
                if ($default_lang == $key && !($request->react_restaurant_section_button_name[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $react_restaurant_section_button_name->id,
                                'locale' => $key,
                                'key' => 'react_restaurant_section_button_name'
                            ],
                            ['value' => $react_restaurant_section_button_name->value]
                        );
                    }
                } else {
                    if ($request->react_restaurant_section_button_name[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $react_restaurant_section_button_name->id,
                                'locale' => $key,
                                'key' => 'react_restaurant_section_button_name'
                            ],
                            ['value' => $request->react_restaurant_section_button_name[$index]]
                        );
                    }
                }
                if ($default_lang == $key && !($request->react_delivery_section_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $react_delivery_section_title->id,
                                'locale' => $key,
                                'key' => 'react_delivery_section_title'
                            ],
                            ['value' => $react_delivery_section_title->value]
                        );
                    }
                } else {
                    if ($request->react_delivery_section_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $react_delivery_section_title->id,
                                'locale' => $key,
                                'key' => 'react_delivery_section_title'
                            ],
                            ['value' => $request->react_delivery_section_title[$index]]
                        );
                    }
                }
                if ($default_lang == $key && !($request->react_delivery_section_sub_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $react_delivery_section_sub_title->id,
                                'locale' => $key,
                                'key' => 'react_delivery_section_sub_title'
                            ],
                            ['value' => $react_delivery_section_sub_title->value]
                        );
                    }
                } else {
                    if ($request->react_delivery_section_sub_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $react_delivery_section_sub_title->id,
                                'locale' => $key,
                                'key' => 'react_delivery_section_sub_title'
                            ],
                            ['value' => $request->react_delivery_section_sub_title[$index]]
                        );
                    }
                }
                if ($default_lang == $key && !($request->react_delivery_section_button_name[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $react_delivery_section_button_name->id,
                                'locale' => $key,
                                'key' => 'react_delivery_section_button_name'
                            ],
                            ['value' => $react_delivery_section_button_name->value]
                        );
                    }
                } else {
                    if ($request->react_delivery_section_button_name[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $react_delivery_section_button_name->id,
                                'locale' => $key,
                                'key' => 'react_delivery_section_button_name'
                            ],
                            ['value' => $request->react_delivery_section_button_name[$index]]
                        );
                    }
                }

            }
            Toastr::success(translate('messages.React_registration_section_updated'));
            return back();
        }
        elseif ($tab == 'react-download-apps') {
            $request->validate([
                'react_download_apps_title' => 'required|max:254',
                'react_download_apps_sub_title' => 'required|max:254',
                'react_download_apps_tag' => 'required|max:254',
                'react_download_apps_image' => 'nullable|max:2048',
            ]);


            if($request->react_download_apps_title[array_search('default', $request->lang)] == '' || $request->react_download_apps_sub_title[array_search('default', $request->lang)] == ''  || $request->react_download_apps_tag[array_search('default', $request->lang)] == '' ){
            Toastr::error(translate('default_title_subtitle_and_tagline_is_required'));
            return back();
            }

            $react_download_apps_title = DataSetting::where('type', 'react_landing_page')->where('key', 'react_download_apps_title')->first();
            if ($react_download_apps_title == null) {
                $react_download_apps_title = new DataSetting();
            }
            $react_download_apps_title->key = 'react_download_apps_title';
            $react_download_apps_title->type = 'react_landing_page';
            $react_download_apps_title->value = $request->react_download_apps_title[array_search('default', $request->lang)];
            $react_download_apps_title->save();

            $react_download_apps_sub_title = DataSetting::where('type', 'react_landing_page')->where('key', 'react_download_apps_sub_title')->first();
            if ($react_download_apps_sub_title == null) {
                $react_download_apps_sub_title = new DataSetting();
            }
            $react_download_apps_sub_title->key = 'react_download_apps_sub_title';
            $react_download_apps_sub_title->type = 'react_landing_page';
            $react_download_apps_sub_title->value = $request->react_download_apps_sub_title[array_search('default', $request->lang)];
            $react_download_apps_sub_title->save();

            $react_download_apps_tag = DataSetting::where('type', 'react_landing_page')->where('key', 'react_download_apps_tag')->first();
            if ($react_download_apps_tag == null) {
                $react_download_apps_tag = new DataSetting();
            }
            $react_download_apps_tag->key = 'react_download_apps_tag';
            $react_download_apps_tag->type = 'react_landing_page';
            $react_download_apps_tag->value = $request->react_download_apps_tag[array_search('default', $request->lang)];
            $react_download_apps_tag->save();

            $react_download_apps_button_name = DataSetting::where('type', 'react_landing_page')->where('key', 'react_download_apps_button_name')->first();
            if ($react_download_apps_button_name == null) {
                $react_download_apps_button_name = new DataSetting();
            }
            $react_download_apps_button_name->key = 'react_download_apps_button_name';
            $react_download_apps_button_name->type = 'react_landing_page';
            $react_download_apps_button_name->value = $request->react_download_apps_button_name;
            $react_download_apps_button_name->save();

            $react_download_apps_button_status = DataSetting::where('type', 'react_landing_page')->where('key', 'react_download_apps_button_status')->first();
            if ($react_download_apps_button_status == null) {
                $react_download_apps_button_status = new DataSetting();
            }
            $react_download_apps_button_status->key = 'react_download_apps_button_status';
            $react_download_apps_button_status->type = 'react_landing_page';
            $react_download_apps_button_status->value = $request->react_download_apps_button_status ?? 0;
            $react_download_apps_button_status->save();


            if ($request->has('react_download_apps_image'))   {

                $react_download_apps_image = DataSetting::where('type', 'react_landing_page')->where('key', 'react_download_apps_image')->first();
                $imageName1 = null;
                if($react_download_apps_image){
                    $imageName1 = $react_download_apps_image?->value;
                }
                if ($react_download_apps_image == null) {
                    $react_download_apps_image = new DataSetting();
                }
                $react_download_apps_image->key = 'react_download_apps_image';
                $react_download_apps_image->type = 'react_landing_page';
                    if (empty($imageName1)) {
                        $imageName1 = Helpers::upload( dir:'react_download_apps_image/',format: 'png',image: $request->file('react_download_apps_image'));
                        }  else{
                        $imageName1= Helpers::update( dir: 'react_download_apps_image/',old_image: $imageName1,format: 'png', image:$request->file('react_download_apps_image')) ;
                        }
                        $react_download_apps_image->value =  $imageName1;
                        $react_download_apps_image->save();
                }

                $react_download_apps_link_data = DataSetting::where('type', 'react_landing_page')->where('key', 'react_download_apps_link_data')->first();
                if ($react_download_apps_link_data == null) {
                    $react_download_apps_link_data = new DataSetting();
                }

                $react_download_apps_link_data->key = 'react_download_apps_link_data';
                $react_download_apps_link_data->type = 'react_landing_page';

                $button_data = [
                    'react_download_apps_link_status' => $request->react_download_apps_link_status ?? 0,
                    'react_download_apps_link' => $request->react_download_apps_link,
                ];

                $react_download_apps_link_data->value =  json_encode($button_data);
                $react_download_apps_link_data->save();


            $default_lang = str_replace('_', '-', app()->getLocale());
            foreach ($request->lang as $index => $key) {
                if ($default_lang == $key && !($request->react_download_apps_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $react_download_apps_title->id,
                                'locale' => $key,
                                'key' => 'react_download_apps_title'
                            ],
                            ['value' => $react_download_apps_title->value]
                        );
                    }
                } else {
                    if ($request->react_download_apps_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $react_download_apps_title->id,
                                'locale' => $key,
                                'key' => 'react_download_apps_title'
                            ],
                            ['value' => $request->react_download_apps_title[$index]]
                        );
                    }
                }
                if ($default_lang == $key && !($request->react_download_apps_sub_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $react_download_apps_sub_title->id,
                                'locale' => $key,
                                'key' => 'react_download_apps_sub_title'
                            ],
                            ['value' => $react_download_apps_sub_title->value]
                        );
                    }
                } else {
                    if ($request->react_download_apps_sub_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $react_download_apps_sub_title->id,
                                'locale' => $key,
                                'key' => 'react_download_apps_sub_title'
                            ],
                            ['value' => $request->react_download_apps_sub_title[$index]]
                        );
                    }
                }
                if ($default_lang == $key && !($request->react_download_apps_tag[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $react_download_apps_tag->id,
                                'locale' => $key,
                                'key' => 'react_download_apps_tag'
                            ],
                            ['value' => $react_download_apps_tag->value]
                        );
                    }
                } else {
                    if ($request->react_download_apps_tag[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $react_download_apps_tag->id,
                                'locale' => $key,
                                'key' => 'react_download_apps_tag'
                            ],
                            ['value' => $request->react_download_apps_tag[$index]]
                        );
                    }
                }


            }
            Toastr::success(translate('messages.React_download_apps_section_updated'));
            return back();
        }
        elseif ($tab == 'react-download-apps-banner-image') {
            $request->validate([
                'react_download_apps_banner_image' => 'nullable|max:2048',
            ]);

            if ($request->has('react_download_apps_banner_image'))   {

                $react_download_apps_banner_image = DataSetting::where('type', 'react_landing_page')->where('key', 'react_download_apps_banner_image')->first();
                $imageName1 = null;
                if($react_download_apps_banner_image){
                    $imageName1 = $react_download_apps_banner_image?->value;
                }
                if ($react_download_apps_banner_image == null) {
                    $react_download_apps_banner_image = new DataSetting();
                }
                $react_download_apps_banner_image->key = 'react_download_apps_banner_image';
                $react_download_apps_banner_image->type = 'react_landing_page';
                    if (empty($imageName1)) {
                        $imageName1 = Helpers::upload( dir:'react_download_apps_image/',format: 'png',image: $request->file('react_download_apps_banner_image'));
                        }  else{
                        $imageName1= Helpers::update( dir: 'react_download_apps_image/',old_image: $imageName1,format: 'png', image:$request->file('react_download_apps_banner_image')) ;
                        }
                        $react_download_apps_banner_image->value =  $imageName1;
                        $react_download_apps_banner_image->save();
                }

            Toastr::success(translate('messages.React_download_apps_section_banner_image_updated'));
            return back();
        }

        return back();
    }

    public function update_admin_landing_page_settings(Request $request, $tab)
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('messages.update_option_is_disable_for_demo'));
            return back();
        }

        if ($tab == 'header-data') {
            $request->validate([
                'header_title' => 'required|max:254',
                'header_sub_title' => 'required|max:254',
                'header_tag_line' => 'required|max:254',
            ]);

            if($request->header_title[array_search('default', $request->lang)] == '' || $request->header_sub_title[array_search('default', $request->lang)] == ''  || $request->header_tag_line[array_search('default', $request->lang)] == '' ){
            Toastr::error(translate('default_title_subtitle_and_tagline_is_required'));
            return back();
            }

            $header_title = DataSetting::where('type', 'admin_landing_page')->where('key', 'header_title')->first();
            if ($header_title == null) {
                $header_title = new DataSetting();
            }
            $header_title->key = 'header_title';
            $header_title->type = 'admin_landing_page';
            $header_title->value = $request->header_title[array_search('default', $request->lang)];
            $header_title->save();

            $header_sub_title = DataSetting::where('type', 'admin_landing_page')->where('key', 'header_sub_title')->first();
            if ($header_sub_title == null) {
                $header_sub_title = new DataSetting();
            }

            $header_sub_title->key = 'header_sub_title';
            $header_sub_title->type = 'admin_landing_page';
            $header_sub_title->value = $request->header_sub_title[array_search('default', $request->lang)];
            $header_sub_title->save();

            $header_tag_line = DataSetting::where('type', 'admin_landing_page')->where('key', 'header_tag_line')->first();
            if ($header_tag_line == null) {
                $header_tag_line = new DataSetting();
            }

            $header_tag_line->key = 'header_tag_line';
            $header_tag_line->type = 'admin_landing_page';
            $header_tag_line->value = $request->header_tag_line[array_search('default', $request->lang)];
            $header_tag_line->save();


            $header_app_button_name = DataSetting::where('type', 'admin_landing_page')->where('key', 'header_app_button_name')->first();
            if ($header_app_button_name == null) {
                $header_app_button_name = new DataSetting();
            }

            $header_app_button_name->key = 'header_app_button_name';
            $header_app_button_name->type = 'admin_landing_page';
            $header_app_button_name->value = $request->header_app_button_name[array_search('default', $request->lang)];
            $header_app_button_name->save();

            $header_app_button_status = DataSetting::where('type', 'admin_landing_page')->where('key', 'header_app_button_status')->first();
            if ($header_app_button_status == null) {
                $header_app_button_status = new DataSetting();
            }

            $header_app_button_status->key = 'header_app_button_status';
            $header_app_button_status->type = 'admin_landing_page';
            $header_app_button_status->value = $request->header_app_button_status;
            $header_app_button_status->save();




            $header_button_content = DataSetting::where('type', 'admin_landing_page')->where('key', 'header_button_content')->first();
            if ($header_button_content == null) {
                $header_button_content = new DataSetting();
            }

            $header_button_content->key = 'header_button_content';
            $header_button_content->type = 'admin_landing_page';
            $header_button_content->value =  $request->redirect_link;
            $header_button_content->save();


            $default_lang = str_replace('_', '-', app()->getLocale());
            foreach ($request->lang as $index => $key) {
                if ($default_lang == $key && !($request->header_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $header_title->id,
                                'locale' => $key,
                                'key' => 'header_title'
                            ],
                            ['value' => $header_title->value]
                        );
                    }
                } else {
                    if ($request->header_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $header_title->id,
                                'locale' => $key,
                                'key' => 'header_title'
                            ],
                            ['value' => $request->header_title[$index]]
                        );
                    }
                }
                if ($default_lang == $key && !($request->header_sub_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $header_sub_title->id,
                                'locale' => $key,
                                'key' => 'header_sub_title'
                            ],
                            ['value' => $header_sub_title->value]
                        );
                    }
                } else {
                    if ($request->header_sub_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $header_sub_title->id,
                                'locale' => $key,
                                'key' => 'header_sub_title'
                            ],
                            ['value' => $request->header_sub_title[$index]]
                        );
                    }
                }
                if ($default_lang == $key && !($request->header_tag_line[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $header_tag_line->id,
                                'locale' => $key,
                                'key' => 'header_tag_line'
                            ],
                            ['value' => $header_tag_line->value]
                        );
                    }
                } else {
                    if ($request->header_tag_line[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $header_tag_line->id,
                                'locale' => $key,
                                'key' => 'header_tag_line'
                            ],
                            ['value' => $request->header_tag_line[$index]]
                        );
                    }
                }
                if ($default_lang == $key && !($request->header_app_button_name[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $header_app_button_name->id,
                                'locale' => $key,
                                'key' => 'header_app_button_name'
                            ],
                            ['value' => $header_app_button_name->value]
                        );
                    }
                } else {
                    if ($request->header_app_button_name[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $header_app_button_name->id,
                                'locale' => $key,
                                'key' => 'header_app_button_name'
                            ],
                            ['value' => $request->header_app_button_name[$index]]
                        );
                    }
                }

            }
            Toastr::success(translate('messages.landing_page_text_updated'));
            return back();
        }
        elseif($tab == 'header-data-images'){
            $request->validate([
                'header_content_image' => 'nullable|max:2048',
                'header_bg_image' => 'nullable|max:2048',
            ]);

            $header_image_content = DataSetting::where('type', 'admin_landing_page')->where('key', 'header_image_content')->first();
            $data = [];
            $imageName1 = null;
            $imageName2 = null;
            if($header_image_content){
                $data = json_decode($header_image_content?->value, true);
                $imageName1 =$data['header_content_image'] ?? null;
                $imageName2 =$data['header_bg_image'] ?? null;
            }

            if ($header_image_content == null) {
                $header_image_content = new DataSetting();
            }
            $header_image_content->key = 'header_image_content';
            $header_image_content->type = 'admin_landing_page';
            if ($request->has('header_content_image'))   {
                if (empty($imageName1)) {
                    $imageName1 = Helpers::upload( dir:'header_image/',format: 'png',image: $request->file('header_content_image'));
                    }  else{
                    $imageName1= Helpers::update( dir: 'header_image/',old_image: $data['header_content_image'],format: 'png', image:$request->file('header_content_image')) ;
                    }
            }
            if ($request->has('header_bg_image'))   {
                if (empty($imageName2)) {
                    $imageName2 = Helpers::upload( dir:'header_image/',format: 'png',image: $request->file('header_bg_image'));
                    }  else{
                    $imageName2= Helpers::update( dir: 'header_image/',old_image: $data['header_bg_image'],format: 'png', image:$request->file('header_bg_image')) ;
                    }
            }
            $img_data = [
                'header_content_image' => $imageName1,
                'header_bg_image' => $imageName2 ,
            ];
            $header_image_content->value =  json_encode($img_data);
            $header_image_content->save();

            Toastr::success(translate('messages.landing_page_image_content_updated'));
            return back();
        }
        elseif($tab == 'header-data-floating-icon'){
            $header_floating_content = DataSetting::where('type', 'admin_landing_page')->where('key', 'header_floating_content')->first();
            if ($header_floating_content == null) {
                $header_floating_content = new DataSetting();
            }
            $header_floating_content->key = 'header_floating_content';
            $header_floating_content->type = 'admin_landing_page';
            $button_data = [
                'header_floating_total_order' => $request->header_floating_total_order ?? null,
                'header_floating_total_user' => $request->header_floating_total_user ?? null,
                'header_floating_total_reviews' => $request->header_floating_total_reviews ?? null,
            ];
            $header_floating_content->value =  json_encode($button_data);
            $header_floating_content->save();
            Toastr::success(translate('messages.landing_page_header_floating_content_updated'));
            return back();
        }
        elseif ($tab == 'about-us-data') {
            $request->validate([
                'about_us_title' => 'required|max:254',
                'about_us_sub_title' => 'required|max:254',
                'about_us_text' => 'required|max:1000',
                'about_us_content_image' => 'nullable|max:2048',

            ]);

            if($request->about_us_title[array_search('default', $request->lang)] == '' || $request->about_us_sub_title[array_search('default', $request->lang)] == '' ){
            Toastr::error(translate('default_title_and_subtitle_is_required'));
            return back();
            }

            $about_us_image_content = DataSetting::where('type', 'admin_landing_page')->where('key', 'about_us_image_content')->first();
            $imageName1 = null;
            if($about_us_image_content){
                $imageName1 = $about_us_image_content?->value ?? null ;
            }

            $about_us_image_content = DataSetting::firstOrNew(
                ['key' =>  'about_us_image_content',
                'type' =>  'admin_landing_page'],
            );

            if ($request->has('about_us_content_image'))   {
                if (empty($imageName1)) {
                    $imageName1 = Helpers::upload( dir:'about_us_image/',format: 'png',image: $request->file('about_us_content_image'));
                    }  else{
                    $imageName1= Helpers::update( dir: 'about_us_image/',old_image: $imageName1 ,format: 'png', image:$request->file('about_us_content_image')) ;
                    }
            }
            $about_us_image_content->value =  $imageName1;
            $about_us_image_content->save();

            $about_us_title = DataSetting::where('type', 'admin_landing_page')->where('key', 'about_us_title')->first();
            if ($about_us_title == null) {
                $about_us_title = new DataSetting();
            }
            $about_us_title->key = 'about_us_title';
            $about_us_title->type = 'admin_landing_page';
            $about_us_title->value = $request->about_us_title[array_search('default', $request->lang)];
            $about_us_title->save();

            $about_us_sub_title = DataSetting::where('type', 'admin_landing_page')->where('key', 'about_us_sub_title')->first();
            if ($about_us_sub_title == null) {
                $about_us_sub_title = new DataSetting();
            }

            $about_us_sub_title->key = 'about_us_sub_title';
            $about_us_sub_title->type = 'admin_landing_page';
            $about_us_sub_title->value = $request->about_us_sub_title[array_search('default', $request->lang)];
            $about_us_sub_title->save();

            $about_us_text = DataSetting::where('type', 'admin_landing_page')->where('key', 'about_us_text')->first();
            if ($about_us_text == null) {
                $about_us_text = new DataSetting();
            }

            $about_us_text->key = 'about_us_text';
            $about_us_text->type = 'admin_landing_page';
            $about_us_text->value = $request->about_us_text[array_search('default', $request->lang)];
            $about_us_text->save();

            $about_us_app_button_name = DataSetting::where('type', 'admin_landing_page')->where('key', 'about_us_app_button_name')->first();
            if ($about_us_app_button_name == null) {
                $about_us_app_button_name = new DataSetting();
            }

            $about_us_app_button_name->key = 'about_us_app_button_name';
            $about_us_app_button_name->type = 'admin_landing_page';
            $about_us_app_button_name->value = $request->about_us_app_button_name[array_search('default', $request->lang)];
            // dd($about_us_app_button_name->value , $request->about_us_app_button_name[array_search('default', $request->lang)]);
            $about_us_app_button_name->save();

            $about_us_app_button_status = DataSetting::where('type', 'admin_landing_page')->where('key', 'about_us_app_button_status')->first();
            if ($about_us_app_button_status == null) {
                $about_us_app_button_status = new DataSetting();
            }

            $about_us_app_button_status->key = 'about_us_app_button_status';
            $about_us_app_button_status->type = 'admin_landing_page';
            $about_us_app_button_status->value = $request->about_us_app_button_status;
            $about_us_app_button_status->save();




            $about_us_button_content = DataSetting::where('type', 'admin_landing_page')->where('key', 'about_us_button_content')->first();
            if ($about_us_button_content == null) {
                $about_us_button_content = new DataSetting();
            }

            $about_us_button_content->key = 'about_us_button_content';
            $about_us_button_content->type = 'admin_landing_page';


            $about_us_button_content->value = $request->redirect_link;
            $about_us_button_content->save();


            $default_lang = str_replace('_', '-', app()->getLocale());
            foreach ($request->lang as $index => $key) {
                if ($default_lang == $key && !($request->about_us_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $about_us_title->id,
                                'locale' => $key,
                                'key' => 'about_us_title'
                            ],
                            ['value' => $about_us_title->value]
                        );
                    }
                } else {
                    if ($request->about_us_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $about_us_title->id,
                                'locale' => $key,
                                'key' => 'about_us_title'
                            ],
                            ['value' => $request->about_us_title[$index]]
                        );
                    }
                }
                if ($default_lang == $key && !($request->about_us_sub_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $about_us_sub_title->id,
                                'locale' => $key,
                                'key' => 'about_us_sub_title'
                            ],
                            ['value' => $about_us_sub_title->value]
                        );
                    }
                } else {
                    if ($request->about_us_sub_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $about_us_sub_title->id,
                                'locale' => $key,
                                'key' => 'about_us_sub_title'
                            ],
                            ['value' => $request->about_us_sub_title[$index]]
                        );
                    }
                }
                if ($default_lang == $key && !($request->about_us_text[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $about_us_text->id,
                                'locale' => $key,
                                'key' => 'about_us_text'
                            ],
                            ['value' => $about_us_text->value]
                        );
                    }
                } else {
                    if ($request->about_us_text[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $about_us_text->id,
                                'locale' => $key,
                                'key' => 'about_us_text'
                            ],
                            ['value' => $request->about_us_text[$index]]
                        );
                    }
                }
                if ($default_lang == $key && !($request->about_us_app_button_name[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $about_us_app_button_name->id,
                                'locale' => $key,
                                'key' => 'about_us_app_button_name'
                            ],
                            ['value' => $about_us_app_button_name->value]
                        );
                    }
                } else {
                    if ($request->about_us_app_button_name[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $about_us_app_button_name->id,
                                'locale' => $key,
                                'key' => 'about_us_app_button_name'
                            ],
                            ['value' => $request->about_us_app_button_name[$index]]
                        );
                    }
                }

            }
            Toastr::success(translate('messages.landing_page_about_us_section_updated'));
            return back();
        }
        elseif ($tab == 'why-choose-us-data') {

            $request->validate([
                'why_choose_us_title' => 'required|max:254',
                'why_choose_us_sub_title' => 'required|max:254',
            ]);


            if($request->why_choose_us_title[array_search('default', $request->lang)] == '' || $request->why_choose_us_sub_title[array_search('default', $request->lang)] == '' ){
            Toastr::error(translate('default_title_and_subtitle_is_required'));
            return back();
            }


            $why_choose_us_title = DataSetting::where('type', 'admin_landing_page')->where('key', 'why_choose_us_title')->first();
            if ($why_choose_us_title == null) {
                $why_choose_us_title = new DataSetting();
            }
            $why_choose_us_title->key = 'why_choose_us_title';
            $why_choose_us_title->type = 'admin_landing_page';
            $why_choose_us_title->value = $request->why_choose_us_title[array_search('default', $request->lang)];
            $why_choose_us_title->save();

            $why_choose_us_sub_title = DataSetting::where('type', 'admin_landing_page')->where('key', 'why_choose_us_sub_title')->first();
            if ($why_choose_us_sub_title == null) {
                $why_choose_us_sub_title = new DataSetting();
            }

            $why_choose_us_sub_title->key = 'why_choose_us_sub_title';
            $why_choose_us_sub_title->type = 'admin_landing_page';
            $why_choose_us_sub_title->value = $request->why_choose_us_sub_title[array_search('default', $request->lang)];
            $why_choose_us_sub_title->save();

            $default_lang = str_replace('_', '-', app()->getLocale());
            foreach ($request->lang as $index => $key) {
                if ($default_lang == $key && !($request->why_choose_us_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $why_choose_us_title->id,
                                'locale' => $key,
                                'key' => 'why_choose_us_title'
                            ],
                            ['value' => $why_choose_us_title->value]
                        );
                    }
                } else {
                    if ($request->why_choose_us_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $why_choose_us_title->id,
                                'locale' => $key,
                                'key' => 'why_choose_us_title'
                            ],
                            ['value' => $request->why_choose_us_title[$index]]
                        );
                    }
                }
                if ($default_lang == $key && !($request->why_choose_us_sub_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $why_choose_us_sub_title->id,
                                'locale' => $key,
                                'key' => 'why_choose_us_sub_title'
                            ],
                            ['value' => $why_choose_us_sub_title->value]
                        );
                    }
                } else {
                    if ($request->why_choose_us_sub_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $why_choose_us_sub_title->id,
                                'locale' => $key,
                                'key' => 'why_choose_us_sub_title'
                            ],
                            ['value' => $request->why_choose_us_sub_title[$index]]
                        );
                    }
                }
            }
            Toastr::success(translate('messages.landing_page_why_choose_us_section_updated'));
            return back();
        }
        elseif ($tab == 'why-choose-us-data-1') {
            $request->validate([
                'title' => 'required|max:254',
                'key' => 'required|max:100',
                'key_image' => 'required|max:100',
                'image' => 'nullable|max:2048',
            ]);

            if($request->title[array_search('default', $request->lang)] == '' ){
            Toastr::error(translate('default_title_is_required'));
            return back();
            }

            $this->update_data($request , $request->key , 'title');

            if ($request->has('image'))   {
            $why_choose_us_image = DataSetting::where('type', 'admin_landing_page')->where('key', $request->key_image)->first();
            $imageName1 = null;
            if($why_choose_us_image){
                $imageName1 = $why_choose_us_image?->value;
            }
            if ($why_choose_us_image == null) {
                $why_choose_us_image = new DataSetting();
            }
            $why_choose_us_image->key = $request->key_image;
            $why_choose_us_image->type = 'admin_landing_page';
                if (empty($imageName1)) {
                    $imageName1 = Helpers::upload( dir:'why_choose_us_image/',format: 'png',image: $request->file('image'));
                    }  else{
                    $imageName1= Helpers::update( dir: 'why_choose_us_image/',old_image: $imageName1,format: 'png', image:$request->file('image')) ;
                    }
                    $why_choose_us_image->value =  $imageName1;
                    $why_choose_us_image->save();
            }

            Toastr::success(translate('messages.landing_page_why_choose_us_section_data_updated'));
            return back();
        }
        elseif ($tab == 'earn-money-data') {

            $request->validate([
                'earn_money_title' => 'required|max:254',
                'earn_money_sub_title' => 'required|max:254',
            ]);
            $earn_money_title = DataSetting::where('type', 'admin_landing_page')->where('key', 'earn_money_title')->first();
            if ($earn_money_title == null) {
                $earn_money_title = new DataSetting();
            }
            $earn_money_title->key = 'earn_money_title';
            $earn_money_title->type = 'admin_landing_page';
            $earn_money_title->value = $request->earn_money_title[array_search('default', $request->lang)];
            $earn_money_title->save();

            $earn_money_sub_title = DataSetting::where('type', 'admin_landing_page')->where('key', 'earn_money_sub_title')->first();
            if ($earn_money_sub_title == null) {
                $earn_money_sub_title = new DataSetting();
            }

            $earn_money_sub_title->key = 'earn_money_sub_title';
            $earn_money_sub_title->type = 'admin_landing_page';
            $earn_money_sub_title->value = $request->earn_money_sub_title[array_search('default', $request->lang)];
            $earn_money_sub_title->save();

            $default_lang = str_replace('_', '-', app()->getLocale());
            foreach ($request->lang as $index => $key) {
                if ($default_lang == $key && !($request->earn_money_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $earn_money_title->id,
                                'locale' => $key,
                                'key' => 'earn_money_title'
                            ],
                            ['value' => $earn_money_title->value]
                        );
                    }
                } else {
                    if ($request->earn_money_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $earn_money_title->id,
                                'locale' => $key,
                                'key' => 'earn_money_title'
                            ],
                            ['value' => $request->earn_money_title[$index]]
                        );
                    }
                }
                if ($default_lang == $key && !($request->earn_money_sub_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $earn_money_sub_title->id,
                                'locale' => $key,
                                'key' => 'earn_money_sub_title'
                            ],
                            ['value' => $earn_money_sub_title->value]
                        );
                    }
                } else {
                    if ($request->earn_money_sub_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $earn_money_sub_title->id,
                                'locale' => $key,
                                'key' => 'earn_money_sub_title'
                            ],
                            ['value' => $request->earn_money_sub_title[$index]]
                        );
                    }
                }
            }
            Toastr::success(translate('messages.landing_page_why_choose_us_section_updated'));
            return back();
        }
        elseif ($tab == 'earn-money-data-reg-section') {
            $request->validate([
                'earn_money_reg_title' => 'required',
                'earn_money_reg_image' => 'nullable|max:2048',
            ]);

            $earn_money_reg_title = DataSetting::where('type', 'admin_landing_page')->where('key', 'earn_money_reg_title')->first();
            if ($earn_money_reg_title == null) {
                $earn_money_reg_title = new DataSetting();
            }
            $earn_money_reg_title->key = 'earn_money_reg_title';
            $earn_money_reg_title->type = 'admin_landing_page';
            $earn_money_reg_title->value = $request->earn_money_reg_title[array_search('default', $request->lang)];
            $earn_money_reg_title->save();

            // restaurant
            $earn_money_restaurant_req_button_name = DataSetting::where('type', 'admin_landing_page')->where('key', 'earn_money_restaurant_req_button_name')->first();
            if ($earn_money_restaurant_req_button_name == null) {
                $earn_money_restaurant_req_button_name = new DataSetting();
            }
            $earn_money_restaurant_req_button_name->key = 'earn_money_restaurant_req_button_name';
            $earn_money_restaurant_req_button_name->type = 'admin_landing_page';
            $earn_money_restaurant_req_button_name->value = $request->earn_money_restaurant_req_button_name[array_search('default', $request->lang)];
            $earn_money_restaurant_req_button_name->save();

            $earn_money_restaurant_req_button_status = DataSetting::where('type', 'admin_landing_page')->where('key', 'earn_money_restaurant_req_button_status')->first();
            if ($earn_money_restaurant_req_button_status == null) {
                $earn_money_restaurant_req_button_status = new DataSetting();
            }
            $earn_money_restaurant_req_button_status->key = 'earn_money_restaurant_req_button_status';
            $earn_money_restaurant_req_button_status->type = 'admin_landing_page';
            $earn_money_restaurant_req_button_status->value = $request->earn_money_restaurant_req_button_status ?? 0;
            $earn_money_restaurant_req_button_status->save();

            $earn_money_restaurant_req_button_link = DataSetting::where('type', 'admin_landing_page')->where('key', 'earn_money_restaurant_req_button_link')->first();
            if ($earn_money_restaurant_req_button_link == null) {
                $earn_money_restaurant_req_button_link = new DataSetting();
            }
            $earn_money_restaurant_req_button_link->key = 'earn_money_restaurant_req_button_link';
            $earn_money_restaurant_req_button_link->type = 'admin_landing_page';
            $earn_money_restaurant_req_button_link->value =  $request->earn_money_restaurant_req_button_link ;
            $earn_money_restaurant_req_button_link->save();
            // restaurant end
            // delivery
            $earn_money_delivety_man_req_button_name = DataSetting::where('type', 'admin_landing_page')->where('key', 'earn_money_delivety_man_req_button_name')->first();
            if ($earn_money_delivety_man_req_button_name == null) {
                $earn_money_delivety_man_req_button_name = new DataSetting();
            }
            $earn_money_delivety_man_req_button_name->key = 'earn_money_delivety_man_req_button_name';
            $earn_money_delivety_man_req_button_name->type = 'admin_landing_page';
            $earn_money_delivety_man_req_button_name->value = $request->earn_money_delivety_man_req_button_name[array_search('default', $request->lang)];
            $earn_money_delivety_man_req_button_name->save();

            $earn_money_delivery_man_req_button_status = DataSetting::where('type', 'admin_landing_page')->where('key', 'earn_money_delivery_man_req_button_status')->first();
            if ($earn_money_delivery_man_req_button_status == null) {
                $earn_money_delivery_man_req_button_status = new DataSetting();
            }
            $earn_money_delivery_man_req_button_status->key = 'earn_money_delivery_man_req_button_status';
            $earn_money_delivery_man_req_button_status->type = 'admin_landing_page';
            $earn_money_delivery_man_req_button_status->value = $request->earn_money_delivery_man_req_button_status ?? 0;
            $earn_money_delivery_man_req_button_status->save();

            $earn_money_delivery_man_req_button_link = DataSetting::where('type', 'admin_landing_page')->where('key', 'earn_money_delivery_man_req_button_link')->first();
            if ($earn_money_delivery_man_req_button_link == null) {
                $earn_money_delivery_man_req_button_link = new DataSetting();
            }
            $earn_money_delivery_man_req_button_link->key = 'earn_money_delivery_man_req_button_link';
            $earn_money_delivery_man_req_button_link->type = 'admin_landing_page';

            $earn_money_delivery_man_req_button_link->value =$request->earn_money_delivery_req_button_link ;
            $earn_money_delivery_man_req_button_link->save();
            // delivery end
            if ($request->has('earn_money_reg_image'))   {

                $earn_money_reg_image = DataSetting::where('type', 'admin_landing_page')->where('key', 'earn_money_reg_image')->first();
                $imageName1 = null;
                if($earn_money_reg_image){
                    $imageName1 = $earn_money_reg_image?->value;
                }
                if ($earn_money_reg_image == null) {
                    $earn_money_reg_image = new DataSetting();
                }
                $earn_money_reg_image->key = 'earn_money_reg_image';
                $earn_money_reg_image->type = 'admin_landing_page';
                    if (empty($imageName1)) {
                        $imageName1 = Helpers::upload( dir:'earn_money/',format: 'png',image: $request->file('earn_money_reg_image'));
                        }  else{
                        $imageName1= Helpers::update( dir: 'earn_money/',old_image: $imageName1,format: 'png', image:$request->file('earn_money_reg_image')) ;
                        }
                        $earn_money_reg_image->value =  $imageName1;
                        $earn_money_reg_image->save();
                }

                $default_lang = str_replace('_', '-', app()->getLocale());
                foreach ($request->lang as $index => $key) {
                    if ($default_lang == $key && !($request->earn_money_reg_title[$index])) {
                        if ($key != 'default') {
                            Translation::updateOrInsert(
                                [
                                    'translationable_type' => 'App\Models\DataSetting',
                                    'translationable_id' => $earn_money_reg_title->id,
                                    'locale' => $key,
                                    'key' => 'earn_money_reg_title'
                                ],
                                ['value' => $earn_money_reg_title->value]
                            );
                        }
                    } else {
                        if ($request->earn_money_reg_title[$index] && $key != 'default') {
                            Translation::updateOrInsert(
                                [
                                    'translationable_type' => 'App\Models\DataSetting',
                                    'translationable_id' => $earn_money_reg_title->id,
                                    'locale' => $key,
                                    'key' => 'earn_money_reg_title'
                                ],
                                ['value' => $request->earn_money_reg_title[$index]]
                            );
                        }
                    }


                    if ($default_lang == $key && !($request->earn_money_restaurant_req_button_name[$index])) {
                        if ($key != 'default') {
                            Translation::updateOrInsert(
                                [
                                    'translationable_type' => 'App\Models\DataSetting',
                                    'translationable_id' => $earn_money_restaurant_req_button_name->id,
                                    'locale' => $key,
                                    'key' => 'earn_money_restaurant_req_button_name'
                                ],
                                ['value' => $earn_money_restaurant_req_button_name->value]
                            );
                        }
                    } else {
                        if ($request->earn_money_restaurant_req_button_name[$index] && $key != 'default') {
                            Translation::updateOrInsert(
                                [
                                    'translationable_type' => 'App\Models\DataSetting',
                                    'translationable_id' => $earn_money_restaurant_req_button_name->id,
                                    'locale' => $key,
                                    'key' => 'earn_money_restaurant_req_button_name'
                                ],
                                ['value' => $request->earn_money_restaurant_req_button_name[$index]]
                            );
                        }
                    }

                    if ($default_lang == $key && !($request->earn_money_delivety_man_req_button_name[$index])) {
                        if ($key != 'default') {
                            Translation::updateOrInsert(
                                [
                                    'translationable_type' => 'App\Models\DataSetting',
                                    'translationable_id' => $earn_money_delivety_man_req_button_name->id,
                                    'locale' => $key,
                                    'key' => 'earn_money_delivety_man_req_button_name'
                                ],
                                ['value' => $earn_money_delivety_man_req_button_name->value]
                            );
                        }
                    } else {
                        if ($request->earn_money_delivety_man_req_button_name[$index] && $key != 'default') {
                            Translation::updateOrInsert(
                                [
                                    'translationable_type' => 'App\Models\DataSetting',
                                    'translationable_id' => $earn_money_delivety_man_req_button_name->id,
                                    'locale' => $key,
                                    'key' => 'earn_money_delivety_man_req_button_name'
                                ],
                                ['value' => $request->earn_money_delivety_man_req_button_name[$index]]
                            );
                        }
                    }
                }

                Toastr::success(translate('messages.Registration_section_updated_successfully'));
            return back();
        }
        elseif ($tab == 'feature-list') {
            $request->validate([
                'title' => 'required',
                'sub_title' => 'required',
                'image' => 'required',
            ]);

            $feature = new AdminFeature();
            $feature->title = $request->title[array_search('default', $request->lang)];
            $feature->sub_title = $request->sub_title[array_search('default', $request->lang)];
            $feature->image = Helpers::upload('admin_feature/', 'png', $request->file('image'));
            $feature->save();
            $default_lang = str_replace('_', '-', app()->getLocale());
            $data = [];
            foreach ($request->lang as $index => $key) {
                if ($default_lang == $key && !($request->title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type'  => 'App\Models\AdminFeature',
                                'translationable_id'    => $feature->id,
                                'locale'                => $key,
                                'key'                   => 'title'
                            ],
                            ['value'                 => $feature->title]
                        );
                    }
                } else {

                    if ($request->title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type'  => 'App\Models\AdminFeature',
                                'translationable_id'    => $feature->id,
                                'locale'                => $key,
                                'key'                   => 'title'
                            ],
                            ['value'                 => $request->title[$index]]
                        );
                    }
                }
                if ($default_lang == $key && !($request->sub_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type'  => 'App\Models\AdminFeature',
                                'translationable_id'    => $feature->id,
                                'locale'                => $key,
                                'key'                   => 'sub_title'
                            ],
                            ['value'                 => $feature->sub_title]
                        );
                    }
                } else {

                    if ($request->sub_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type'  => 'App\Models\AdminFeature',
                                'translationable_id'    => $feature->id,
                                'locale'                => $key,
                                'key'                   => 'sub_title'
                            ],
                            ['value'                 => $request->sub_title[$index]]
                        );
                    }
                }
            }

            Toastr::success(translate('messages.feature_added_successfully'));
        }
        elseif ($tab == 'testimonial-title') {
            $testimonial_title = DataSetting::where('type', 'admin_landing_page')->where('key', 'testimonial_title')->first();
            if ($testimonial_title == null) {
                $testimonial_title = new DataSetting();
            }

            $testimonial_title->key = 'testimonial_title';
            $testimonial_title->type = 'admin_landing_page';
            $testimonial_title->value = $request->testimonial_title[array_search('default', $request->lang)];
            $testimonial_title->save();

            $data = [];
            $default_lang = str_replace('_', '-', app()->getLocale());
            foreach ($request->lang as $index => $key) {
                if ($default_lang == $key && !($request->testimonial_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $testimonial_title->id,
                                'locale' => $key,
                                'key' => 'testimonial_title'
                            ],
                            ['value' => $testimonial_title->value]
                        );
                    }
                } else {
                    if ($request->testimonial_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $testimonial_title->id,
                                'locale' => $key,
                                'key' => 'testimonial_title'
                            ],
                            ['value' => $request->testimonial_title[$index]]
                        );
                    }
                }
            }

            Toastr::success(translate('messages.testimonial_section_updated'));
        }
        elseif ($tab == 'features-title-section') {


            if($request->feature_title[array_search('default', $request->lang)] == '' || $request->feature_sub_title[array_search('default', $request->lang)] == '' ){
            Toastr::error(translate('default_title_and_subtitle_is_required'));
            return back();
            }

            $feature_title = DataSetting::where('type', 'admin_landing_page')->where('key', 'feature_title')->first();
            if ($feature_title == null) {
                $feature_title = new DataSetting();
            }

            $feature_title->key = 'feature_title';
            $feature_title->type = 'admin_landing_page';
            $feature_title->value = $request->feature_title[array_search('default', $request->lang)];
            $feature_title->save();

            $feature_sub_title = DataSetting::where('type', 'admin_landing_page')->where('key', 'feature_sub_title')->first();
            if ($feature_sub_title == null) {
                $feature_sub_title = new DataSetting();
            }

            $feature_sub_title->key = 'feature_sub_title';
            $feature_sub_title->type = 'admin_landing_page';
            $feature_sub_title->value = $request->feature_sub_title[array_search('default', $request->lang)];
            $feature_sub_title->save();

            $data = [];
            $default_lang = str_replace('_', '-', app()->getLocale());
            foreach ($request->lang as $index => $key) {
                if ($default_lang == $key && !($request->feature_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $feature_title->id,
                                'locale' => $key,
                                'key' => 'feature_title'
                            ],
                            ['value' => $feature_title->value]
                        );
                    }
                } else {
                    if ($request->feature_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $feature_title->id,
                                'locale' => $key,
                                'key' => 'feature_title'
                            ],
                            ['value' => $request->feature_title[$index]]
                        );
                    }
                }
                if ($default_lang == $key && !($request->feature_sub_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $feature_sub_title->id,
                                'locale' => $key,
                                'key' => 'feature_sub_title'
                            ],
                            ['value' => $feature_sub_title->value]
                        );
                    }
                } else {
                    if ($request->feature_sub_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $feature_sub_title->id,
                                'locale' => $key,
                                'key' => 'feature_sub_title'
                            ],
                            ['value' => $request->feature_sub_title[$index]]
                        );
                    }
                }
            }

            Toastr::success(translate('messages.Feature_section_updated'));
        }
        elseif ($tab == 'services-data') {

            $request->validate([
                'services_title' => 'required|max:254',
                'services_sub_title' => 'required|max:254',
            ]);
            $services_title = DataSetting::where('type', 'admin_landing_page')->where('key', 'services_title')->first();
            if ($services_title == null) {
                $services_title = new DataSetting();
            }
            $services_title->key = 'services_title';
            $services_title->type = 'admin_landing_page';
            $services_title->value = $request->services_title[array_search('default', $request->lang)];
            $services_title->save();

            $services_sub_title = DataSetting::where('type', 'admin_landing_page')->where('key', 'services_sub_title')->first();
            if ($services_sub_title == null) {
                $services_sub_title = new DataSetting();
            }

            $services_sub_title->key = 'services_sub_title';
            $services_sub_title->type = 'admin_landing_page';
            $services_sub_title->value = $request->services_sub_title[array_search('default', $request->lang)];
            $services_sub_title->save();

            $default_lang = str_replace('_', '-', app()->getLocale());
            foreach ($request->lang as $index => $key) {
                if ($default_lang == $key && !($request->services_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $services_title->id,
                                'locale' => $key,
                                'key' => 'services_title'
                            ],
                            ['value' => $services_title->value]
                        );
                    }
                } else {
                    if ($request->services_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $services_title->id,
                                'locale' => $key,
                                'key' => 'services_title'
                            ],
                            ['value' => $request->services_title[$index]]
                        );
                    }
                }
                if ($default_lang == $key && !($request->services_sub_title[$index])) {
                    if ($key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $services_sub_title->id,
                                'locale' => $key,
                                'key' => 'services_sub_title'
                            ],
                            ['value' => $services_sub_title->value]
                        );
                    }
                } else {
                    if ($request->services_sub_title[$index] && $key != 'default') {
                        Translation::updateOrInsert(
                            [
                                'translationable_type' => 'App\Models\DataSetting',
                                'translationable_id' => $services_sub_title->id,
                                'locale' => $key,
                                'key' => 'services_sub_title'
                            ],
                            ['value' => $request->services_sub_title[$index]]
                        );
                    }
                }
            }
            Toastr::success(translate('messages.Services_section_updated'));
            return back();
        }
        elseif ($tab == 'services-order-data') {
            $request->validate([
                'services_order_title_1' => 'required|max:100',
                'services_order_title_2' => 'required|max:100',
                'services_order_description_1' => 'required|max:100',
                'services_order_description_2' => 'required|max:100',
            ]);

            $services_order_title_1 = DataSetting::where('type', 'admin_landing_page')->where('key', 'services_order_title_1')->first();
            if ($services_order_title_1 == null) {
                $services_order_title_1 = new DataSetting();
            }
            $services_order_title_1->key = 'services_order_title_1';
            $services_order_title_1->type = 'admin_landing_page';
            $services_order_title_1->value = $request->services_order_title_1[array_search('default', $request->lang)];
            $services_order_title_1->save();

            $services_order_title_2 = DataSetting::where('type', 'admin_landing_page')->where('key', 'services_order_title_2')->first();
            if ($services_order_title_2 == null) {
                $services_order_title_2 = new DataSetting();
            }
            $services_order_title_2->key = 'services_order_title_2';
            $services_order_title_2->type = 'admin_landing_page';
            $services_order_title_2->value = $request->services_order_title_2[array_search('default', $request->lang)];
            $services_order_title_2->save();

            $services_order_description_1 = DataSetting::where('type', 'admin_landing_page')->where('key', 'services_order_description_1')->first();
            if ($services_order_description_1 == null) {
                $services_order_description_1 = new DataSetting();
            }
            $services_order_description_1->key = 'services_order_description_1';
            $services_order_description_1->type = 'admin_landing_page';
            $services_order_description_1->value = $request->services_order_description_1[array_search('default', $request->lang)];
            $services_order_description_1->save();

            $services_order_description_2 = DataSetting::where('type', 'admin_landing_page')->where('key', 'services_order_description_2')->first();
            if ($services_order_description_2 == null) {
                $services_order_description_2 = new DataSetting();
            }
            $services_order_description_2->key = 'services_order_description_2';
            $services_order_description_2->type = 'admin_landing_page';
            $services_order_description_2->value = $request->services_order_description_2[array_search('default', $request->lang)];
            $services_order_description_2->save();

            $services_order_button_name = DataSetting::where('type', 'admin_landing_page')->where('key', 'services_order_button_name')->first();
            if ($services_order_button_name == null) {
                $services_order_button_name = new DataSetting();
            }
            $services_order_button_name->key = 'services_order_button_name';
            $services_order_button_name->type = 'admin_landing_page';
            $services_order_button_name->value = $request->services_order_button_name[array_search('default', $request->lang)];
            $services_order_button_name->save();

            $services_order_button_status = DataSetting::where('type', 'admin_landing_page')->where('key', 'services_order_button_status')->first();
            if ($services_order_button_status == null) {
                $services_order_button_status = new DataSetting();
            }
            $services_order_button_status->key = 'services_order_button_status';
            $services_order_button_status->type = 'admin_landing_page';
            $services_order_button_status->value = $request->services_order_button_status ?? 0;
            $services_order_button_status->save();




            $services_order_button_link = DataSetting::where('type', 'admin_landing_page')->where('key', 'services_order_button_link')->first();
            if ($services_order_button_link == null) {
                $services_order_button_link = new DataSetting();
            }
            $services_order_button_link->key = 'services_order_button_link';
            $services_order_button_link->type = 'admin_landing_page';

            $services_order_button_link->value =$request->services_order_button_link;
            $services_order_button_link->save();


                $default_lang = str_replace('_', '-', app()->getLocale());
                foreach ($request->lang as $index => $key) {
                    if ($default_lang == $key && !($request->services_order_title_1[$index])) {
                        if ($key != 'default') {
                            Translation::updateOrInsert(
                                [
                                    'translationable_type' => 'App\Models\DataSetting',
                                    'translationable_id' => $services_order_title_1->id,
                                    'locale' => $key,
                                    'key' => 'services_order_title_1'
                                ],
                                ['value' => $services_order_title_1->value]
                            );
                        }
                    } else {
                        if ($request->services_order_title_1[$index] && $key != 'default') {
                            Translation::updateOrInsert(
                                [
                                    'translationable_type' => 'App\Models\DataSetting',
                                    'translationable_id' => $services_order_title_1->id,
                                    'locale' => $key,
                                    'key' => 'services_order_title_1'
                                ],
                                ['value' => $request->services_order_title_1[$index]]
                            );
                        }
                    }


                    if ($default_lang == $key && !($request->services_order_title_2[$index])) {
                        if ($key != 'default') {
                            Translation::updateOrInsert(
                                [
                                    'translationable_type' => 'App\Models\DataSetting',
                                    'translationable_id' => $services_order_title_2->id,
                                    'locale' => $key,
                                    'key' => 'services_order_title_2'
                                ],
                                ['value' => $services_order_title_2->value]
                            );
                        }
                    } else {
                        if ($request->services_order_title_2[$index] && $key != 'default') {
                            Translation::updateOrInsert(
                                [
                                    'translationable_type' => 'App\Models\DataSetting',
                                    'translationable_id' => $services_order_title_2->id,
                                    'locale' => $key,
                                    'key' => 'services_order_title_2'
                                ],
                                ['value' => $request->services_order_title_2[$index]]
                            );
                        }
                    }

                    if ($default_lang == $key && !($request->services_order_description_1[$index])) {
                        if ($key != 'default') {
                            Translation::updateOrInsert(
                                [
                                    'translationable_type' => 'App\Models\DataSetting',
                                    'translationable_id' => $services_order_description_1->id,
                                    'locale' => $key,
                                    'key' => 'services_order_description_1'
                                ],
                                ['value' => $services_order_description_1->value]
                            );
                        }
                    } else {
                        if ($request->services_order_description_1[$index] && $key != 'default') {
                            Translation::updateOrInsert(
                                [
                                    'translationable_type' => 'App\Models\DataSetting',
                                    'translationable_id' => $services_order_description_1->id,
                                    'locale' => $key,
                                    'key' => 'services_order_description_1'
                                ],
                                ['value' => $request->services_order_description_1[$index]]
                            );
                        }
                    }
                    if ($default_lang == $key && !($request->services_order_description_2[$index])) {
                        if ($key != 'default') {
                            Translation::updateOrInsert(
                                [
                                    'translationable_type' => 'App\Models\DataSetting',
                                    'translationable_id' => $services_order_description_2->id,
                                    'locale' => $key,
                                    'key' => 'services_order_description_2'
                                ],
                                ['value' => $services_order_description_2->value]
                            );
                        }
                    } else {
                        if ($request->services_order_description_2[$index] && $key != 'default') {
                            Translation::updateOrInsert(
                                [
                                    'translationable_type' => 'App\Models\DataSetting',
                                    'translationable_id' => $services_order_description_2->id,
                                    'locale' => $key,
                                    'key' => 'services_order_description_2'
                                ],
                                ['value' => $request->services_order_description_2[$index]]
                            );
                        }
                    }
                    if ($default_lang == $key && !($request->services_order_button_name[$index])) {
                        if ($key != 'default') {
                            Translation::updateOrInsert(
                                [
                                    'translationable_type' => 'App\Models\DataSetting',
                                    'translationable_id' => $services_order_button_name->id,
                                    'locale' => $key,
                                    'key' => 'services_order_button_name'
                                ],
                                ['value' => $services_order_button_name->value]
                            );
                        }
                    } else {
                        if ($request->services_order_button_name[$index] && $key != 'default') {
                            Translation::updateOrInsert(
                                [
                                    'translationable_type' => 'App\Models\DataSetting',
                                    'translationable_id' => $services_order_button_name->id,
                                    'locale' => $key,
                                    'key' => 'services_order_button_name'
                                ],
                                ['value' => $request->services_order_button_name[$index]]
                            );
                        }
                    }
                }

                Toastr::success(translate('messages.Order_section_updated_successfully'));
            return back();
        }
        elseif ($tab == 'services-manage-restaurant-data') {
            $request->validate([
                'services_manage_restaurant_title_1' => 'required|max:100',
                'services_manage_restaurant_title_2' => 'required|max:100',
                'services_manage_restaurant_description_1' => 'required|max:100',
                'services_manage_restaurant_description_2' => 'required|max:100',
            ]);

            $services_manage_restaurant_title_1 = DataSetting::where('type', 'admin_landing_page')->where('key', 'services_manage_restaurant_title_1')->first();
            if ($services_manage_restaurant_title_1 == null) {
                $services_manage_restaurant_title_1 = new DataSetting();
            }
            $services_manage_restaurant_title_1->key = 'services_manage_restaurant_title_1';
            $services_manage_restaurant_title_1->type = 'admin_landing_page';
            $services_manage_restaurant_title_1->value = $request->services_manage_restaurant_title_1[array_search('default', $request->lang)];
            $services_manage_restaurant_title_1->save();

            $services_manage_restaurant_title_2 = DataSetting::where('type', 'admin_landing_page')->where('key', 'services_manage_restaurant_title_2')->first();
            if ($services_manage_restaurant_title_2 == null) {
                $services_manage_restaurant_title_2 = new DataSetting();
            }
            $services_manage_restaurant_title_2->key = 'services_manage_restaurant_title_2';
            $services_manage_restaurant_title_2->type = 'admin_landing_page';
            $services_manage_restaurant_title_2->value = $request->services_manage_restaurant_title_2[array_search('default', $request->lang)];
            $services_manage_restaurant_title_2->save();

            $services_manage_restaurant_description_1 = DataSetting::where('type', 'admin_landing_page')->where('key', 'services_manage_restaurant_description_1')->first();
            if ($services_manage_restaurant_description_1 == null) {
                $services_manage_restaurant_description_1 = new DataSetting();
            }
            $services_manage_restaurant_description_1->key = 'services_manage_restaurant_description_1';
            $services_manage_restaurant_description_1->type = 'admin_landing_page';
            $services_manage_restaurant_description_1->value = $request->services_manage_restaurant_description_1[array_search('default', $request->lang)];
            $services_manage_restaurant_description_1->save();

            $services_manage_restaurant_description_2 = DataSetting::where('type', 'admin_landing_page')->where('key', 'services_manage_restaurant_description_2')->first();
            if ($services_manage_restaurant_description_2 == null) {
                $services_manage_restaurant_description_2 = new DataSetting();
            }
            $services_manage_restaurant_description_2->key = 'services_manage_restaurant_description_2';
            $services_manage_restaurant_description_2->type = 'admin_landing_page';
            $services_manage_restaurant_description_2->value = $request->services_manage_restaurant_description_2[array_search('default', $request->lang)];
            $services_manage_restaurant_description_2->save();

            $services_manage_restaurant_button_name = DataSetting::where('type', 'admin_landing_page')->where('key', 'services_manage_restaurant_button_name')->first();
            if ($services_manage_restaurant_button_name == null) {
                $services_manage_restaurant_button_name = new DataSetting();
            }
            $services_manage_restaurant_button_name->key = 'services_manage_restaurant_button_name';
            $services_manage_restaurant_button_name->type = 'admin_landing_page';
            $services_manage_restaurant_button_name->value = $request->services_manage_restaurant_button_name[array_search('default', $request->lang)];
            $services_manage_restaurant_button_name->save();

            $services_manage_restaurant_button_status = DataSetting::where('type', 'admin_landing_page')->where('key', 'services_manage_restaurant_button_status')->first();
            if ($services_manage_restaurant_button_status == null) {
                $services_manage_restaurant_button_status = new DataSetting();
            }
            $services_manage_restaurant_button_status->key = 'services_manage_restaurant_button_status';
            $services_manage_restaurant_button_status->type = 'admin_landing_page';
            $services_manage_restaurant_button_status->value = $request->services_manage_restaurant_button_status ?? 0;
            $services_manage_restaurant_button_status->save();




            $services_manage_restaurant_button_link = DataSetting::where('type', 'admin_landing_page')->where('key', 'services_manage_restaurant_button_link')->first();
            if ($services_manage_restaurant_button_link == null) {
                $services_manage_restaurant_button_link = new DataSetting();
            }
            $services_manage_restaurant_button_link->key = 'services_manage_restaurant_button_link';
            $services_manage_restaurant_button_link->type = 'admin_landing_page';
            $services_manage_restaurant_button_link->value = $request->services_manage_restaurant_button_link;
            $services_manage_restaurant_button_link->save();


                $default_lang = str_replace('_', '-', app()->getLocale());
                foreach ($request->lang as $index => $key) {
                    if ($default_lang == $key && !($request->services_manage_restaurant_title_1[$index])) {
                        if ($key != 'default') {
                            Translation::updateOrInsert(
                                [
                                    'translationable_type' => 'App\Models\DataSetting',
                                    'translationable_id' => $services_manage_restaurant_title_1->id,
                                    'locale' => $key,
                                    'key' => 'services_manage_restaurant_title_1'
                                ],
                                ['value' => $services_manage_restaurant_title_1->value]
                            );
                        }
                    } else {
                        if ($request->services_manage_restaurant_title_1[$index] && $key != 'default') {
                            Translation::updateOrInsert(
                                [
                                    'translationable_type' => 'App\Models\DataSetting',
                                    'translationable_id' => $services_manage_restaurant_title_1->id,
                                    'locale' => $key,
                                    'key' => 'services_manage_restaurant_title_1'
                                ],
                                ['value' => $request->services_manage_restaurant_title_1[$index]]
                            );
                        }
                    }


                    if ($default_lang == $key && !($request->services_manage_restaurant_title_2[$index])) {
                        if ($key != 'default') {
                            Translation::updateOrInsert(
                                [
                                    'translationable_type' => 'App\Models\DataSetting',
                                    'translationable_id' => $services_manage_restaurant_title_2->id,
                                    'locale' => $key,
                                    'key' => 'services_manage_restaurant_title_2'
                                ],
                                ['value' => $services_manage_restaurant_title_2->value]
                            );
                        }
                    } else {
                        if ($request->services_manage_restaurant_title_2[$index] && $key != 'default') {
                            Translation::updateOrInsert(
                                [
                                    'translationable_type' => 'App\Models\DataSetting',
                                    'translationable_id' => $services_manage_restaurant_title_2->id,
                                    'locale' => $key,
                                    'key' => 'services_manage_restaurant_title_2'
                                ],
                                ['value' => $request->services_manage_restaurant_title_2[$index]]
                            );
                        }
                    }

                    if ($default_lang == $key && !($request->services_manage_restaurant_description_1[$index])) {
                        if ($key != 'default') {
                            Translation::updateOrInsert(
                                [
                                    'translationable_type' => 'App\Models\DataSetting',
                                    'translationable_id' => $services_manage_restaurant_description_1->id,
                                    'locale' => $key,
                                    'key' => 'services_manage_restaurant_description_1'
                                ],
                                ['value' => $services_manage_restaurant_description_1->value]
                            );
                        }
                    } else {
                        if ($request->services_manage_restaurant_description_1[$index] && $key != 'default') {
                            Translation::updateOrInsert(
                                [
                                    'translationable_type' => 'App\Models\DataSetting',
                                    'translationable_id' => $services_manage_restaurant_description_1->id,
                                    'locale' => $key,
                                    'key' => 'services_manage_restaurant_description_1'
                                ],
                                ['value' => $request->services_manage_restaurant_description_1[$index]]
                            );
                        }
                    }
                    if ($default_lang == $key && !($request->services_manage_restaurant_description_2[$index])) {
                        if ($key != 'default') {
                            Translation::updateOrInsert(
                                [
                                    'translationable_type' => 'App\Models\DataSetting',
                                    'translationable_id' => $services_manage_restaurant_description_2->id,
                                    'locale' => $key,
                                    'key' => 'services_manage_restaurant_description_2'
                                ],
                                ['value' => $services_manage_restaurant_description_2->value]
                            );
                        }
                    } else {
                        if ($request->services_manage_restaurant_description_2[$index] && $key != 'default') {
                            Translation::updateOrInsert(
                                [
                                    'translationable_type' => 'App\Models\DataSetting',
                                    'translationable_id' => $services_manage_restaurant_description_2->id,
                                    'locale' => $key,
                                    'key' => 'services_manage_restaurant_description_2'
                                ],
                                ['value' => $request->services_manage_restaurant_description_2[$index]]
                            );
                        }
                    }
                    if ($default_lang == $key && !($request->services_manage_restaurant_button_name[$index])) {
                        if ($key != 'default') {
                            Translation::updateOrInsert(
                                [
                                    'translationable_type' => 'App\Models\DataSetting',
                                    'translationable_id' => $services_manage_restaurant_button_name->id,
                                    'locale' => $key,
                                    'key' => 'services_manage_restaurant_button_name'
                                ],
                                ['value' => $services_manage_restaurant_button_name->value]
                            );
                        }
                    } else {
                        if ($request->services_manage_restaurant_button_name[$index] && $key != 'default') {
                            Translation::updateOrInsert(
                                [
                                    'translationable_type' => 'App\Models\DataSetting',
                                    'translationable_id' => $services_manage_restaurant_button_name->id,
                                    'locale' => $key,
                                    'key' => 'services_manage_restaurant_button_name'
                                ],
                                ['value' => $request->services_manage_restaurant_button_name[$index]]
                            );
                        }
                    }
                }

                Toastr::success(translate('messages.Manage_restaurant_section_updated_successfully'));
            return back();
        }
        elseif ($tab == 'services-manage-delivery-data') {
            $request->validate([
                'services_manage_delivery_title_1' => 'required|max:100',
                'services_manage_delivery_title_2' => 'required|max:100',
                'services_manage_delivery_description_1' => 'required|max:100',
                'services_manage_delivery_description_2' => 'required|max:100',
            ]);

            $services_manage_delivery_title_1 = DataSetting::where('type', 'admin_landing_page')->where('key', 'services_manage_delivery_title_1')->first();
            if ($services_manage_delivery_title_1 == null) {
                $services_manage_delivery_title_1 = new DataSetting();
            }
            $services_manage_delivery_title_1->key = 'services_manage_delivery_title_1';
            $services_manage_delivery_title_1->type = 'admin_landing_page';
            $services_manage_delivery_title_1->value = $request->services_manage_delivery_title_1[array_search('default', $request->lang)];
            $services_manage_delivery_title_1->save();

            $services_manage_delivery_title_2 = DataSetting::where('type', 'admin_landing_page')->where('key', 'services_manage_delivery_title_2')->first();
            if ($services_manage_delivery_title_2 == null) {
                $services_manage_delivery_title_2 = new DataSetting();
            }
            $services_manage_delivery_title_2->key = 'services_manage_delivery_title_2';
            $services_manage_delivery_title_2->type = 'admin_landing_page';
            $services_manage_delivery_title_2->value = $request->services_manage_delivery_title_2[array_search('default', $request->lang)];
            $services_manage_delivery_title_2->save();

            $services_manage_delivery_description_1 = DataSetting::where('type', 'admin_landing_page')->where('key', 'services_manage_delivery_description_1')->first();
            if ($services_manage_delivery_description_1 == null) {
                $services_manage_delivery_description_1 = new DataSetting();
            }
            $services_manage_delivery_description_1->key = 'services_manage_delivery_description_1';
            $services_manage_delivery_description_1->type = 'admin_landing_page';
            $services_manage_delivery_description_1->value = $request->services_manage_delivery_description_1[array_search('default', $request->lang)];
            $services_manage_delivery_description_1->save();

            $services_manage_delivery_description_2 = DataSetting::where('type', 'admin_landing_page')->where('key', 'services_manage_delivery_description_2')->first();
            if ($services_manage_delivery_description_2 == null) {
                $services_manage_delivery_description_2 = new DataSetting();
            }
            $services_manage_delivery_description_2->key = 'services_manage_delivery_description_2';
            $services_manage_delivery_description_2->type = 'admin_landing_page';
            $services_manage_delivery_description_2->value = $request->services_manage_delivery_description_2[array_search('default', $request->lang)];
            $services_manage_delivery_description_2->save();

            $services_manage_delivery_button_name = DataSetting::where('type', 'admin_landing_page')->where('key', 'services_manage_delivery_button_name')->first();
            if ($services_manage_delivery_button_name == null) {
                $services_manage_delivery_button_name = new DataSetting();
            }
            $services_manage_delivery_button_name->key = 'services_manage_delivery_button_name';
            $services_manage_delivery_button_name->type = 'admin_landing_page';
            $services_manage_delivery_button_name->value = $request->services_manage_delivery_button_name[array_search('default', $request->lang)];
            $services_manage_delivery_button_name->save();

            $services_manage_delivery_button_status = DataSetting::where('type', 'admin_landing_page')->where('key', 'services_manage_delivery_button_status')->first();
            if ($services_manage_delivery_button_status == null) {
                $services_manage_delivery_button_status = new DataSetting();
            }
            $services_manage_delivery_button_status->key = 'services_manage_delivery_button_status';
            $services_manage_delivery_button_status->type = 'admin_landing_page';
            $services_manage_delivery_button_status->value = $request->services_manage_delivery_button_status ?? 0;
            $services_manage_delivery_button_status->save();




            $services_manage_delivery_button_link = DataSetting::where('type', 'admin_landing_page')->where('key', 'services_manage_delivery_button_link')->first();
            if ($services_manage_delivery_button_link == null) {
                $services_manage_delivery_button_link = new DataSetting();
            }
            $services_manage_delivery_button_link->key = 'services_manage_delivery_button_link';
            $services_manage_delivery_button_link->type = 'admin_landing_page';
            $services_manage_delivery_button_link->value =$request->services_manage_delivery_button_link;
            $services_manage_delivery_button_link->save();


                $default_lang = str_replace('_', '-', app()->getLocale());
                foreach ($request->lang as $index => $key) {
                    if ($default_lang == $key && !($request->services_manage_delivery_title_1[$index])) {
                        if ($key != 'default') {
                            Translation::updateOrInsert(
                                [
                                    'translationable_type' => 'App\Models\DataSetting',
                                    'translationable_id' => $services_manage_delivery_title_1->id,
                                    'locale' => $key,
                                    'key' => 'services_manage_delivery_title_1'
                                ],
                                ['value' => $services_manage_delivery_title_1->value]
                            );
                        }
                    } else {
                        if ($request->services_manage_delivery_title_1[$index] && $key != 'default') {
                            Translation::updateOrInsert(
                                [
                                    'translationable_type' => 'App\Models\DataSetting',
                                    'translationable_id' => $services_manage_delivery_title_1->id,
                                    'locale' => $key,
                                    'key' => 'services_manage_delivery_title_1'
                                ],
                                ['value' => $request->services_manage_delivery_title_1[$index]]
                            );
                        }
                    }


                    if ($default_lang == $key && !($request->services_manage_delivery_title_2[$index])) {
                        if ($key != 'default') {
                            Translation::updateOrInsert(
                                [
                                    'translationable_type' => 'App\Models\DataSetting',
                                    'translationable_id' => $services_manage_delivery_title_2->id,
                                    'locale' => $key,
                                    'key' => 'services_manage_delivery_title_2'
                                ],
                                ['value' => $services_manage_delivery_title_2->value]
                            );
                        }
                    } else {
                        if ($request->services_manage_delivery_title_2[$index] && $key != 'default') {
                            Translation::updateOrInsert(
                                [
                                    'translationable_type' => 'App\Models\DataSetting',
                                    'translationable_id' => $services_manage_delivery_title_2->id,
                                    'locale' => $key,
                                    'key' => 'services_manage_delivery_title_2'
                                ],
                                ['value' => $request->services_manage_delivery_title_2[$index]]
                            );
                        }
                    }

                    if ($default_lang == $key && !($request->services_manage_delivery_description_1[$index])) {
                        if ($key != 'default') {
                            Translation::updateOrInsert(
                                [
                                    'translationable_type' => 'App\Models\DataSetting',
                                    'translationable_id' => $services_manage_delivery_description_1->id,
                                    'locale' => $key,
                                    'key' => 'services_manage_delivery_description_1'
                                ],
                                ['value' => $services_manage_delivery_description_1->value]
                            );
                        }
                    } else {
                        if ($request->services_manage_delivery_description_1[$index] && $key != 'default') {
                            Translation::updateOrInsert(
                                [
                                    'translationable_type' => 'App\Models\DataSetting',
                                    'translationable_id' => $services_manage_delivery_description_1->id,
                                    'locale' => $key,
                                    'key' => 'services_manage_delivery_description_1'
                                ],
                                ['value' => $request->services_manage_delivery_description_1[$index]]
                            );
                        }
                    }
                    if ($default_lang == $key && !($request->services_manage_delivery_description_2[$index])) {
                        if ($key != 'default') {
                            Translation::updateOrInsert(
                                [
                                    'translationable_type' => 'App\Models\DataSetting',
                                    'translationable_id' => $services_manage_delivery_description_2->id,
                                    'locale' => $key,
                                    'key' => 'services_manage_delivery_description_2'
                                ],
                                ['value' => $services_manage_delivery_description_2->value]
                            );
                        }
                    } else {
                        if ($request->services_manage_delivery_description_2[$index] && $key != 'default') {
                            Translation::updateOrInsert(
                                [
                                    'translationable_type' => 'App\Models\DataSetting',
                                    'translationable_id' => $services_manage_delivery_description_2->id,
                                    'locale' => $key,
                                    'key' => 'services_manage_delivery_description_2'
                                ],
                                ['value' => $request->services_manage_delivery_description_2[$index]]
                            );
                        }
                    }
                    if ($default_lang == $key && !($request->services_manage_delivery_button_name[$index])) {
                        if ($key != 'default') {
                            Translation::updateOrInsert(
                                [
                                    'translationable_type' => 'App\Models\DataSetting',
                                    'translationable_id' => $services_manage_delivery_button_name->id,
                                    'locale' => $key,
                                    'key' => 'services_manage_delivery_button_name'
                                ],
                                ['value' => $services_manage_delivery_button_name->value]
                            );
                        }
                    } else {
                        if ($request->services_manage_delivery_button_name[$index] && $key != 'default') {
                            Translation::updateOrInsert(
                                [
                                    'translationable_type' => 'App\Models\DataSetting',
                                    'translationable_id' => $services_manage_delivery_button_name->id,
                                    'locale' => $key,
                                    'key' => 'services_manage_delivery_button_name'
                                ],
                                ['value' => $request->services_manage_delivery_button_name[$index]]
                            );
                        }
                    }
                }

                Toastr::success(translate('messages.Delivery_section_updated_successfully'));
            return back();

        } elseif($tab == 'fixed-data-newsletter'){
            $request->validate([
                'title' => 'required|max:254',
                'sub_title' => 'required|max:254',
            ]);


            if($request->title[array_search('default', $request->lang)] == '' || $request->sub_title[array_search('default', $request->lang)] == '' ){
            Toastr::error(translate('default_title_and_subtitle_is_required'));
            return back();
            }

            $this->update_data($request , $request->key , 'title' );
            $this->update_data($request , $request->key_2 , 'sub_title');

            Toastr::success(translate('messages.Admin_landing_page_newsletter_updated'));
            return back();

        } elseif($tab == 'fixed-data-footer'){
            $request->validate([
                'footer_data' => 'required|max:1000',
                // 'copyright_text' => 'nullable|max:100',

            ]);

            if($request->footer_data[array_search('default', $request->lang)] == '' ){
            Toastr::error(translate('default_footer_descrtption_is_required'));
            return back();
            }
            $this->update_data($request , $request->footer_key , 'footer_data' );
            // $this->update_data($request , $request->key_copyright , 'copyright_text');

            Toastr::success(translate('messages.Admin_landing_page_footer_description_updated'));
            return back();
        }

        return back();
    }



    private function update_data($request, $key_data,$name_field , $type = 'admin_landing_page' ){
        $data = DataSetting::firstOrNew(
            ['key' =>  $key_data,
            'type' =>  $type],
        );

        $data->value = $request->{$name_field}[array_search('default', $request->lang)];
        $data->save();
        $default_lang = str_replace('_', '-', app()->getLocale());
        foreach ($request->lang as $index => $key) {
            if ($default_lang == $key && !($request->{$name_field}[$index])) {
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
                if ($request->{$name_field}[$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\DataSetting',
                            'translationable_id' => $data->id,
                            'locale' => $key,
                            'key' => $key_data
                        ],
                        ['value' => $request->{$name_field}[$index]]
                    );
                }
            }
        }

        return true;
    }

}

