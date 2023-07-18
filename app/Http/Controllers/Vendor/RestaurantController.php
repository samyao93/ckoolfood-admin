<?php

namespace App\Http\Controllers\Vendor;

use App\Models\Restaurant;
use App\Models\Translation;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;

class RestaurantController extends Controller
{
    public function view()
    {
        $shop = Helpers::get_restaurant_data();
        return view('vendor-views.shop.shopInfo', compact('shop'));
    }

    public function edit()
    {
        $shop = Restaurant::withoutGlobalScope('translate')->with('translations')->find(Helpers::get_restaurant_id());
        return view('vendor-views.shop.edit', compact('shop'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|max:191',
            'address' => 'nullable|max:1000',
            'contact' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:20|unique:restaurants,phone,'.Helpers::get_restaurant_id(),
            'image' => 'nullable|max:2048',
            'photo' => 'nullable|max:2048',

        ], [
            'f_name.required' => translate('messages.first_name_is_required'),
        ]);

        if($request->name[array_search('default', $request->lang)] == '' ){
            Toastr::error(translate('default_restaurant_name_is_required'));
            return back();
            }
        if($request->address[array_search('default', $request->lang)] == '' ){
            Toastr::error(translate('default_restaurant_address_is_required'));
            return back();
        }

        $shop = Restaurant::findOrFail(Helpers::get_restaurant_id());
        $shop->name = $request->name[array_search('default', $request->lang)];
        $shop->address = $request->address[array_search('default', $request->lang)];
        $shop->phone = $request->contact;
        $shop->logo = $request->has('image') ? Helpers::update(dir: 'restaurant/',old_image:  $shop->logo ,format: 'png', image: $request->file('image')) : $shop->logo;
        $shop->cover_photo = $request->has('photo') ? Helpers::update(dir: 'restaurant/cover/',old_image:  $shop->cover_photo,  format:'png',image:  $request->file('photo')) : $shop->cover_photo;
        $shop?->save();
        $default_lang = str_replace('_', '-', app()->getLocale());
        foreach($request->lang as $index=>$key)
        {
            if($default_lang == $key && !($request->name[$index])){
                if ($key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\Restaurant',
                            'translationable_id' => $shop->id,
                            'locale' => $key,
                            'key' => 'name'
                        ],
                        ['value' => $shop->name]
                    );
                }
            }else{

                if ($request->name[$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        ['translationable_type'  => 'App\Models\Restaurant',
                            'translationable_id'    => $shop->id,
                            'locale'                => $key,
                            'key'                   => 'name'],
                        ['value'                 => $request->name[$index]]
                    );
                }
            }
            if($default_lang == $key && !($request->address[$index])){
                if ($key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\Restaurant',
                            'translationable_id' => $shop->id,
                            'locale' => $key,
                            'key' => 'address'
                        ],
                        ['value' => $shop->address]
                    );
                }
            }else{

                if ($request->address[$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        ['translationable_type'  => 'App\Models\Restaurant',
                            'translationable_id'    => $shop->id,
                            'locale'                => $key,
                            'key'                   => 'address'],
                        ['value'                 => $request->address[$index]]
                    );
                }
            }
        }
        if($shop?->vendor?->userinfo) {
            $userinfo = $shop->vendor->userinfo;
            $userinfo->f_name = $shop->name;
            $userinfo->image = $shop->logo;
            $userinfo?->save();
        }

        Toastr::success(translate('messages.restaurant_data_updated'));
        return redirect()->route('vendor.shop.view');
    }

}
