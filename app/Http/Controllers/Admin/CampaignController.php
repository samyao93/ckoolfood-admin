<?php

namespace App\Http\Controllers\Admin;

use App\Models\Campaign;
use App\Models\Translation;
use Illuminate\Support\Str;
use App\Models\ItemCampaign;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CampaignController extends Controller
{
    function index($type)
    {
        return view('admin-views.campaign.'.$type.'.index');
    }

    function list($type)
    {
        if($type=='basic')
        {
            $campaigns=Campaign::latest()->paginate(config('default_pagination'));
        }
        else{
            $campaigns=ItemCampaign::latest()->paginate(config('default_pagination'));
        }

        return view('admin-views.campaign.'.$type.'.list', compact('campaigns'));
    }

    public function storeBasic(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:campaigns|max:191',
            'description'=>'max:1000',
            'image' => 'required|max:2048',

        ]);

        if($request->title[array_search('default', $request->lang)] == '' ){
            $validator->getMessageBag()->add('title', translate('messages.default_title_is_required'));
                    return response()->json(['errors' => Helpers::error_processor($validator)]);
            }



        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $campaign = new Campaign;
        $campaign->title = $request->title[array_search('default', $request->lang)];
        $campaign->description = $request->description[array_search('default', $request->lang)];
        $campaign->image = Helpers::upload(dir: 'campaign/',format: 'png', image: $request->file('image'));
        $campaign->start_date = $request->start_date;
        $campaign->end_date = $request->end_date;
        $campaign->start_time = $request->start_time;
        $campaign->end_time = $request->end_time;
        $campaign->save();
        $data = [];
        $default_lang = str_replace('_', '-', app()->getLocale());
        foreach ($request->lang as $index => $key) {
            if($default_lang == $key && !($request->title[$index])){
                if ($key != 'default') {
                    array_push($data, array(
                        'translationable_type' => 'App\Models\Campaign',
                        'translationable_id' => $campaign->id,
                        'locale' => $key,
                        'key' => 'title',
                        'value' => $campaign->title,
                    ));
                }
            }else{
                if ($request->title[$index] && $key != 'default') {
                    array_push($data, array(
                        'translationable_type' => 'App\Models\Campaign',
                        'translationable_id' => $campaign->id,
                        'locale' => $key,
                        'key' => 'title',
                        'value' => $request->title[$index],
                    ));
                }
            }

            if($default_lang == $key && !($request->description[$index])){
                if (isset($campaign->description) && $key != 'default') {
                    array_push($data, array(
                        'translationable_type' => 'App\Models\Campaign',
                        'translationable_id' => $campaign->id,
                        'locale' => $key,
                        'key' => 'description',
                        'value' => $campaign->description,
                    ));
                }
            }else{
                if ($request->description[$index] && $key != 'default') {
                    array_push($data, array(
                        'translationable_type' => 'App\Models\Campaign',
                        'translationable_id' => $campaign->id,
                        'locale' => $key,
                        'key' => 'description',
                        'value' => $request->description[$index],
                    ));
                }
            }
        }

        Translation::insert($data);
        return response()->json([], 200);
    }

    public function update(Request $request, Campaign $campaign)
    {
        $validator = Validator::make($request->all(),[
            'title' => 'required|max:191',
            'description' => 'max:1000',
            'image' => 'nullable|max:2048',
        ]);

        if($request->title[array_search('default', $request->lang)] == '' ){
            $validator->getMessageBag()->add('title', translate('messages.default_title_is_required'));
                    return response()->json(['errors' => Helpers::error_processor($validator)]);
            }

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $campaign->title = $request->title[array_search('default', $request->lang)];
        $campaign->description = $request->description[array_search('default', $request->lang)];
        $campaign->image = $request->has('image') ? Helpers::update(dir:'campaign/',old_image: $campaign->image, format:'png', image:$request->file('image')) : $campaign->image;;
        $campaign->start_date = $request->start_date;
        $campaign->end_date = $request->end_date;
        $campaign->start_time = $request->start_time;
        $campaign->end_time = $request->end_time;
        $campaign->save();

        $default_lang = str_replace('_', '-', app()->getLocale());
        foreach ($request->lang as $index => $key) {
            if($default_lang == $key && !($request->title[$index])){
                if ($key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\Campaign',
                            'translationable_id' => $campaign->id,
                            'locale' => $key,
                            'key' => 'title'
                        ],
                        ['value' => $campaign->title]
                    );
                }
            }else{

                if ($request->title[$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\Campaign',
                            'translationable_id' => $campaign->id,
                            'locale' => $key,
                            'key' => 'title'
                        ],
                        ['value' => $request->title[$index]]
                    );
                }
            }
            if($default_lang == $key && !($request->description[$index])){
                if (isset($campaign->description) && $key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\Campaign',
                            'translationable_id' => $campaign->id,
                            'locale' => $key,
                            'key' => 'description'
                        ],
                        ['value' => $campaign->description]
                    );
                }

            }else{

                if ($request->description[$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\Campaign',
                            'translationable_id' => $campaign->id,
                            'locale' => $key,
                            'key' => 'description'
                        ],
                        ['value' => $request->description[$index]]
                    );
                }
            }
        }

        return response()->json([], 200);
    }

    public function storeItem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:191|unique:item_campaigns',
            'image' => 'required|max:2048',
            'category_id' => 'required',
            'price' => 'required|numeric|between:0.01,999999999999.99',
            'restaurant_id' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'start_date' => 'required',
            'start_date' => 'required',
            'veg' => 'required',
            'description'=>'max:1000'
        ], [
            'category_id.required' => translate('messages.select_category'),
        ]);

        if($request->title[array_search('default', $request->lang)] == '' ){
            $validator->getMessageBag()->add('title', translate('messages.default_title_is_required'));
                    return response()->json(['errors' => Helpers::error_processor($validator)]);
            }

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        if ($request['discount_type'] == 'percent') {
            $dis = ($request['price'] / 100) * $request['discount'];
        } else {
            $dis = $request['discount'];
        }

        if ($request['price'] <= $dis) {
            $validator->getMessageBag()->add('unit_price', translate('messages.discount_can_not_be_more_than_or_equal'));
        }

        if ($request['price'] <= $dis || $validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $campaign = new ItemCampaign;

        $category = [];
        if ($request->category_id != null) {
            array_push($category, [
                'id' => $request->category_id,
                'position' => 1,
            ]);
        }
        if ($request->sub_category_id != null) {
            array_push($category, [
                'id' => $request->sub_category_id,
                'position' => 2,
            ]);
        }
        if ($request->sub_sub_category_id != null) {
            array_push($category, [
                'id' => $request->sub_sub_category_id,
                'position' => 3,
            ]);
        }

        $campaign->category_ids = json_encode($category);
        // $choice_options = [];
        // if ($request->has('choice')) {
        //     foreach ($request->choice_no as $key => $no) {
        //         $str = 'choice_options_' . $no;
        //         if ($request[$str][0] == null) {
        //             $validator->getMessageBag()->add('name', translate('messages.attribute_choice_option_value_can_not_be_null'));
        //             return response()->json(['errors' => Helpers::error_processor($validator)]);
        //         }
        //         $item['name'] = 'choice_' . $no;
        //         $item['title'] = $request->choice[$key];
        //         $item['options'] = explode(',', implode('|', preg_replace('/\s+/', ' ', $request[$str])));
        //         array_push($choice_options, $item);
        //     }
        // }
        $campaign->choice_options = json_encode([]);
        $variations = [];
        if($request?->options)
        {
            foreach(array_values($request->options) as $key=>$option)
            {

                $temp_variation['name']= $option['name'];
                $temp_variation['type']= $option['type'];
                $temp_variation['min']= $option['min'] ?? 0;
                $temp_variation['max']= $option['max'] ?? 0;
                if($option['min'] > 0 &&  $option['min'] > $option['max']  ){
                    $validator->getMessageBag()->add('name', translate('messages.minimum_value_can_not_be_greater_then_maximum_value'));
                    return response()->json(['errors' => Helpers::error_processor($validator)]);
                }
                if(!isset($option['values'])){
                    $validator->getMessageBag()->add('name', translate('messages.please_add_options_for').$option['name']);
                    return response()->json(['errors' => Helpers::error_processor($validator)]);
                }
                if($option['max'] > count($option['values'])  ){
                    $validator->getMessageBag()->add('name', translate('messages.please_add_more_options_or_change_the_max_value_for').$option['name']);
                    return response()->json(['errors' => Helpers::error_processor($validator)]);
                }
                $temp_variation['required']= $option['required']??'off';

                $temp_value = [];
                foreach(array_values($option['values']) as $value)
                {
                    if(isset($value['label'])){
                        $temp_option['label'] = $value['label'];
                    }
                    $temp_option['optionPrice'] = $value['optionPrice'];
                    array_push($temp_value,$temp_option);
                }
                $temp_variation['values']= $temp_value;
                array_push($variations,$temp_variation);
            }
        }

        $slug = Str::slug($request->title[array_search('default', $request->lang)]);
        $campaign->slug = $campaign->slug? $campaign->slug :"{$slug}{$campaign->id}";

        $campaign->admin_id = auth('admin')->id();
        $campaign->title = $request->title[array_search('default', $request->lang)];
        $campaign->description = $request->description[array_search('default', $request->lang)];
        $campaign->image = Helpers::upload(dir: 'campaign/', format: 'png', image: $request->file('image'));
        $campaign->start_date = $request->start_date;
        $campaign->end_date = $request->end_date;
        $campaign->start_time = $request->start_time;
        $campaign->end_time = $request->end_time;
        $campaign->variations = json_encode($variations);
        $campaign->price = $request->price;
        $campaign->discount =  $request->discount ?? 0;
        $campaign->discount_type = $request->discount_type;
        $campaign->attributes =  json_encode([]);
        $campaign->add_ons = $request->has('addon_ids') ? json_encode($request->addon_ids) : json_encode([]);
        $campaign->restaurant_id = $request->restaurant_id;
        $campaign->veg = $request->veg;
        $campaign->save();

        $data = [];
        $default_lang = str_replace('_', '-', app()->getLocale());
        foreach ($request->lang as $index => $key) {
            if($default_lang == $key && !($request->title[$index])){
                if ($key != 'default') {
                    array_push($data, array(
                        'translationable_type' => 'App\Models\ItemCampaign',
                        'translationable_id' => $campaign->id,
                        'locale' => $key,
                        'key' => 'title',
                        'value' => $campaign->title,
                    ));
                }
            }else{
                if ($request->title[$index] && $key != 'default') {
                    array_push($data, array(
                        'translationable_type' => 'App\Models\ItemCampaign',
                        'translationable_id' => $campaign->id,
                        'locale' => $key,
                        'key' => 'title',
                        'value' => $request->title[$index],
                    ));
                }
            }

            if($default_lang == $key && !($request->description[$index])){
                if (isset($campaign->description) && $key != 'default') {
                    array_push($data, array(
                        'translationable_type' => 'App\Models\ItemCampaign',
                        'translationable_id' => $campaign->id,
                        'locale' => $key,
                        'key' => 'description',
                        'value' => $campaign->description,
                    ));
                }
            }else{
                if ($request->description[$index] && $key != 'default') {
                    array_push($data, array(
                        'translationable_type' => 'App\Models\ItemCampaign',
                        'translationable_id' => $campaign->id,
                        'locale' => $key,
                        'key' => 'description',
                        'value' => $request->description[$index],
                    ));
                }
            }
        }

        Translation::insert($data);
        return response()->json([], 200);
    }

    public function updateItem(ItemCampaign $campaign, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:item_campaigns,title,' . $campaign->id,
            'category_id' => 'required',
            'price' => 'required|numeric|between:0.01,999999999999.99',
            'restaurant_id' => 'required',
            'veg' => 'required',
            'image' => 'nullable|max:2048',
            'description.*'=>'max:1000',
        ]);

        if($request->title[array_search('default', $request->lang)] == '' ){
            $validator->getMessageBag()->add('title', translate('messages.default_title_is_required'));
                    return response()->json(['errors' => Helpers::error_processor($validator)]);
            }
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        if ($request['discount_type'] == 'percent') {
            $dis = ($request['price'] / 100) * $request['discount'];
        } else {
            $dis = $request['discount'];
        }

        if ($request['price'] <= $dis) {
            $validator->getMessageBag()->add('unit_price', translate('messages.discount_can_not_be_more_than_or_equal'));
        }

        if ($request['price'] <= $dis || $validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $category = [];
        if ($request->category_id != null) {
            array_push($category, [
                'id' => $request->category_id,
                'position' => 1,
            ]);
        }
        if ($request->sub_category_id != null) {
            array_push($category, [
                'id' => $request->sub_category_id,
                'position' => 2,
            ]);
        }
        if ($request->sub_sub_category_id != null) {
            array_push($category, [
                'id' => $request->sub_sub_category_id,
                'position' => 3,
            ]);
        }

        $campaign->category_ids = json_encode($category);


        $campaign->choice_options = json_encode([]);
        $variations = [];
        if($request?->options)
        {
            foreach(array_values($request->options) as $key=>$option)
            {
                $temp_variation['name']= $option['name'];
                $temp_variation['type']= $option['type'];
                $temp_variation['min']= $option['min'] ?? 0;
                $temp_variation['max']= $option['max'] ?? 0;
                if($option['min'] > 0 &&  $option['min'] > $option['max']  ){
                    $validator->getMessageBag()->add('name', translate('messages.minimum_value_can_not_be_greater_then_maximum_value'));
                    return response()->json(['errors' => Helpers::error_processor($validator)]);
                }
                if(!isset($option['values'])){
                    $validator->getMessageBag()->add('name', translate('messages.please_add_options_for').$option['name']);
                    return response()->json(['errors' => Helpers::error_processor($validator)]);
                }
                if($option['max'] > count($option['values'])  ){
                    $validator->getMessageBag()->add('name', translate('messages.please_add_more_options_or_change_the_max_value_for').$option['name']);
                    return response()->json(['errors' => Helpers::error_processor($validator)]);
                }
                $temp_variation['required']= $option['required']??'off';

                $temp_value = [];
                foreach(array_values($option['values']) as $value)
                {
                    if(isset($value['label'])){
                        $temp_option['label'] = $value['label'];
                    }
                    $temp_option['optionPrice'] = $value['optionPrice'];
                    array_push($temp_value,$temp_option);
                }
                $temp_variation['values']= $temp_value;
                array_push($variations,$temp_variation);
            }
        }

        $campaign->title = $request->title[array_search('default', $request->lang)];
        $campaign->description = $request->description[array_search('default', $request->lang)];
        $campaign->image = $request->has('image') ? Helpers::update(dir:'campaign/',old_image: $campaign->image,format: 'png', image:$request->file('image')) : $campaign->image;
        $campaign->start_date = $request->start_date;
        $campaign->end_date = $request->end_date;
        $campaign->start_time = $request->start_time;
        $campaign->end_time = $request->end_time;
        $campaign->restaurant_id = $request->restaurant_id;
        $campaign->variations = json_encode($variations);
        $campaign->price = $request->price;
        $campaign->discount =  $request->discount ?? 0;
        $campaign->discount_type = $request->discount_type;
        $campaign->attributes = json_encode([]);
        $campaign->add_ons = $request->has('addon_ids') ? json_encode($request->addon_ids) : json_encode([]);
        $campaign->veg = $request->veg;
        $campaign->save();
        $default_lang = str_replace('_', '-', app()->getLocale());

        foreach ($request->lang as $index => $key) {
            if($default_lang == $key && !($request->title[$index])){
                if ($key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\ItemCampaign',
                            'translationable_id' => $campaign->id,
                            'locale' => $key,
                            'key' => 'title'
                        ],
                        ['value' => $campaign->title]
                    );
                }
            }else{

                if ($request->title[$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\ItemCampaign',
                            'translationable_id' => $campaign->id,
                            'locale' => $key,
                            'key' => 'title'
                        ],
                        ['value' => $request->title[$index]]
                    );
                }
            }
            if($default_lang == $key && !($request->description[$index])){
                if (isset($campaign->description) && $key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\ItemCampaign',
                            'translationable_id' => $campaign->id,
                            'locale' => $key,
                            'key' => 'description'
                        ],
                        ['value' => $campaign->description]
                    );
                }

            }else{

                if ($request->description[$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\ItemCampaign',
                            'translationable_id' => $campaign->id,
                            'locale' => $key,
                            'key' => 'description'
                        ],
                        ['value' => $request->description[$index]]
                    );
                }
            }
        }


        return response()->json([], 200);
    }

    public function edit($type, $campaign)
    {
        if($type=='basic')
        {
            $campaign = Campaign::withoutGlobalScope('translate')->findOrFail($campaign);
        }
        else
        {
            $campaign = ItemCampaign::withoutGlobalScope('translate')->findOrFail($campaign);
        }
        return view('admin-views.campaign.'.$type.'.edit', compact('campaign'));
    }

    public function view(Request $request ,$type, $campaign)
    {
        if($type=='basic')
        {
            $campaign = Campaign::findOrFail($campaign);
            $key = explode(' ', $request['search']);

            $restaurants = $campaign->restaurants()
            ->when(isset($key) ,function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->where('name', 'like', "%{$value}%");
                    // ->orWhere('email', 'like', "%{$value}%");
                }
            })->paginate(config('default_pagination'));

            $restaurant_ids = [];
            foreach($campaign?->restaurants as $restaurant)
            {
                $restaurant_ids[] = $restaurant->id;
            }
            return view('admin-views.campaign.basic.view', compact('campaign', 'restaurants', 'restaurant_ids'));
        }
        else
        {
            $campaign = ItemCampaign::findOrFail($campaign);
        }
        return view('admin-views.campaign.item.view', compact('campaign'));

    }

    public function status($type, $id, $status)
    {
        if($type=='item')
        {
            $campaign = ItemCampaign::findOrFail($id);
        }
        else{
            $campaign = Campaign::findOrFail($id);
        }
        $campaign->status = $status;
        $campaign->save();
        Toastr::success(translate('messages.campaign_status_updated'));
        return back();
    }

    public function delete(Campaign $campaign)
    {
        if (Storage::disk('public')->exists('campaign/' . $campaign->image)) {
            Storage::disk('public')->delete('campaign/' . $campaign->image);
        }
        $campaign?->translations()?->delete();
        $campaign->delete();
        Toastr::success(translate('messages.campaign_deleted_successfully'));
        return back();
    }
    public function delete_item(ItemCampaign $campaign)
    {
        if (Storage::disk('public')->exists('campaign/' . $campaign->image)) {
            Storage::disk('public')->delete('campaign/' . $campaign->image);
        }
        $campaign?->translations()?->delete();
        $campaign->delete();
        Toastr::success(translate('messages.campaign_deleted_successfully'));
        return back();
    }

    public function remove_restaurant(Campaign $campaign, $restaurant)
    {
        $campaign?->restaurants()?->detach($restaurant);
        $campaign->save();
        Toastr::success(translate('messages.restaurant_remove_from_campaign'));
        return back();
    }
    public function addrestaurant(Request $request, Campaign $campaign)
    {
        $campaign?->restaurants()?->attach($request->restaurant_id,['campaign_status' => 'confirmed']);
        $campaign->save();
        Toastr::success(translate('messages.restaurant_added_to_campaign'));
        return back();
    }

    public function restaurant_confirmation($campaign,$restaurant_id,$status)
    {
        $campaign = Campaign::findOrFail($campaign);
        $campaign?->restaurants()?->updateExistingPivot($restaurant_id,['campaign_status' => $status]);
        $campaign->save();
        try
        {
            $restaurant=Restaurant::find($restaurant_id);
            $mail_status = Helpers::get_mail_status('campaign_deny_mail_status_restaurant');
            if(config('mail.status') && $mail_status == '1' && $status == 'rejected') {
                Mail::to($restaurant->vendor->email)->send(new \App\Mail\VendorCampaignRequestMail($restaurant->name,'denied'));
            }

            $mail_status = Helpers::get_mail_status('campaign_approve_mail_status_restaurant');
            if(config('mail.status') && $mail_status == '1' && $status == 'confirmed') {
                Mail::to($restaurant->vendor->email)->send(new \App\Mail\VendorCampaignRequestMail($restaurant->name,'approved'));
            }
        }
        catch(\Exception $e)
        {
            info($e->getMessage());
        }
        Toastr::success(translate('messages.restaurant_added_to_campaign'));
        return back();
    }

    public function searchBasic(Request $request){
        $key = explode(' ', $request['search']);
        $campaigns=Campaign::where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('title', 'like', "%{$value}%");
            }
        })->limit(50)->get();
        return response()->json([
            'view'=>view('admin-views.campaign.basic.partials._table',compact('campaigns'))->render(),
            'count'=>$campaigns->count()
        ]);
    }
    public function searchItem(Request $request){
        $key = explode(' ', $request['search']);
        $campaigns=ItemCampaign::where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('title', 'like', "%{$value}%");
            }
        })->limit(50)->get();
        return response()->json([
            'view'=>view('admin-views.campaign.item.partials._table',compact('campaigns'))->render()
        ]);
    }
}
