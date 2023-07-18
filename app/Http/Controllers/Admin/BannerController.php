<?php

namespace App\Http\Controllers\Admin;

use App\Models\Banner;
use App\Models\Translation;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BannerController extends Controller
{
    function index()
    {
        $banners = Banner::latest()->paginate(config('default_pagination'));
        return view('admin-views.banner.index', compact('banners'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:191',
            'image' => 'required|max:2048',
            'banner_type' => 'required',
            'zone_id' => 'required',
            'restaurant_id' => 'required_if:banner_type,restaurant_wise',
            'item_id' => 'required_if:banner_type,item_wise',
        ], [
            'zone_id.required' => translate('messages.select_a_zone'),
            'restaurant_id.required_if'=> translate('messages.Restaurant is required when banner type is restaurant wise'),
            'item_id.required_if'=> translate('messages.Food is required when banner type is food wise'),
        ]);

        if($request->title[array_search('default', $request->lang)] == '' ){
            $validator->getMessageBag()->add('title', translate('messages.default_title_is_required'));
                    return response()->json(['errors' => Helpers::error_processor($validator)]);
            }
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $banner = new Banner;
        $banner->title = $request->title[array_search('default', $request->lang)];
        $banner->type = $request->banner_type;
        $banner->zone_id = $request->zone_id;
        $banner->image = Helpers::upload(dir:'banner/',  format:'png', image: $request->file('image'));
        $banner->data = ($request->banner_type == 'restaurant_wise')?$request->restaurant_id:$request->item_id;
        $banner->save();
        $data=[];
        $default_lang = str_replace('_', '-', app()->getLocale());
        foreach ($request->lang as $index => $key) {
            if($default_lang == $key && !($request->title[$index])){
                if ($key != 'default') {
                    array_push($data, array(
                        'translationable_type' => 'App\Models\Banner',
                        'translationable_id' => $banner->id,
                        'locale' => $key,
                        'key' => 'title',
                        'value' => $banner->title,
                    ));
                }
            }else{
                if ($request->title[$index] && $key != 'default') {
                    array_push($data, array(
                        'translationable_type' => 'App\Models\Banner',
                        'translationable_id' => $banner->id,
                        'locale' => $key,
                        'key' => 'title',
                        'value' => $request->title[$index],
                    ));
                }
            }
        }
        Translation::insert($data);
        return response()->json([], 200);
    }

    public function edit(Banner $banner)
    {
        return view('admin-views.banner.edit', compact('banner'));
    }


    public function status(Request $request)
    {
        $banner = Banner::findOrFail($request->id);
        $banner->status = $request->status;
        $banner->save();
        Toastr::success(translate('messages.banner_status_updated'));
        return back();
    }

    public function update(Request $request, Banner $banner)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:191',
            'banner_type' => 'required',
            'zone_id' => 'required',
            'image' => 'nullable|max:2048',
            'restaurant_id' => 'required_if:banner_type,restaurant_wise',
            'item_id' => 'required_if:banner_type,item_wise',
        ], [
            'zone_id.required' => translate('messages.select_a_zone'),
            'restaurant_id.required_if'=> translate('messages.Restaurant is required when banner type is restaurant wise'),
            'item_id.required_if'=> translate('messages.Food is required when banner type is food wise'),
        ]);


        if($request->title[array_search('default', $request->lang)] == '' ){
            $validator->getMessageBag()->add('title', translate('messages.default_title_is_required'));
                    return response()->json(['errors' => Helpers::error_processor($validator)]);
            }
            
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $banner->title = $request->title[array_search('default', $request->lang)];;
        $banner->type = $request->banner_type;
        $banner->zone_id = $request->zone_id;
        $banner->image = $request->has('image') ? Helpers::update(dir:'banner/',old_image: $banner->image, format:'png', image: $request->file('image')) : $banner->image;
        $banner->data = $request->banner_type=='restaurant_wise'?$request->restaurant_id:$request->item_id;
        $banner->save();
        $default_lang = str_replace('_', '-', app()->getLocale());
        foreach ($request->lang as $index => $key) {
            if($default_lang == $key && !($request->title[$index])){
                if ($key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\Banner',
                            'translationable_id' => $banner->id,
                            'locale' => $key,
                            'key' => 'title'
                        ],
                        ['value' => $banner->title]
                    );
                }
            }else{

                if ($request->title[$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\Banner',
                            'translationable_id' => $banner->id,
                            'locale' => $key,
                            'key' => 'title'
                        ],
                        ['value' => $request->title[$index]]
                    );
                }
            }
        }
        return response()->json([], 200);
    }

    public function delete(Banner $banner)
    {
        if (Storage::disk('public')->exists('banner/' . $banner['image'])) {
            Storage::disk('public')->delete('banner/' . $banner['image']);
        }
        $banner?->translations()?->delete();
        $banner->delete();
        Toastr::success(translate('messages.banner_deleted_successfully'));
        return back();
    }

    public function search(Request $request){
        $key = explode(' ', $request['search']);
        $banners=Banner::where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('title', 'like', "%{$value}%");
            }
        })->limit(50)->get();
        return response()->json([
            'view'=>view('admin-views.banner.partials._table',compact('banners'))->render(),
            'count'=>$banners->count()
        ]);
    }
}
