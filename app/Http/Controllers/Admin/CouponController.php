<?php

namespace App\Http\Controllers\Admin;

use App\Models\Coupon;
use App\Models\Translation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;

class CouponController extends Controller
{
    public function add_new(Request $request)
    {
        $key = explode(' ', $request['search']);
        $coupons = Coupon::where('created_by','admin')
        ->when(isset($key), function($query)use($key){
            $query->where( function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('title', 'like', "%{$value}%")
                    ->orWhere('code', 'like', "%{$value}%");
                }
            });
        })
        ->latest()->paginate(config('default_pagination'));
        return view('admin-views.coupon.index', compact('coupons'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:coupons|max:100',
            'title' => 'required|max:191',
            'start_date' => 'required',
            'expire_date' => 'required',
            'discount' => 'required',
            'coupon_type' => 'required|in:zone_wise,restaurant_wise,free_delivery,first_order,default',
            'zone_ids' => 'required_if:coupon_type,zone_wise',
            'restaurant_ids' => 'required_if:coupon_type,restaurant_wise'
        ]);

        if($request->title[array_search('default', $request->lang)] == '' ){
            Toastr::error(translate('default_title_is_required'));
            return back();
            }

        $data  = '';
        $customer_id  = $request->customer_ids ?? ['all'];
        if($request->coupon_type == 'zone_wise')
        {
            $data = $request->zone_ids;
        }
        else if($request->coupon_type == 'restaurant_wise')
        {
            $data = $request->restaurant_ids;
        }


        $coupon = new Coupon();
        $coupon->title = $request->title[array_search('default', $request->lang)];
        $coupon->code = $request->code;
        $coupon->limit = $request->coupon_type=='first_order'?1:$request->limit;
        $coupon->coupon_type = $request->coupon_type;
        $coupon->start_date = $request->start_date;
        $coupon->expire_date = $request->expire_date;
        $coupon->min_purchase = $request?->min_purchase ??  0;
        $coupon->max_discount = $request?->max_discount??  0;
        $coupon->discount = $request->discount ?? 0;
        $coupon->discount_type = $request->discount_type??'';
        $coupon->status =  1;
        $coupon->created_by =  'admin';
        $coupon->data =  json_encode($data);
        $coupon->customer_id =  json_encode($customer_id);
        $coupon->save();

        $data = [];
        $default_lang = str_replace('_', '-', app()->getLocale());
        foreach ($request->lang as $index => $key) {
            if($default_lang == $key && !($request->title[$index])){
                if ($key != 'default') {
                    array_push($data, array(
                        'translationable_type' => 'App\Models\Coupon',
                        'translationable_id' => $coupon->id,
                        'locale' => $key,
                        'key' => 'title',
                        'value' => $coupon->title,
                    ));
                }
            }else{
                if ($request->title[$index] && $key != 'default') {
                    array_push($data, array(
                        'translationable_type' => 'App\Models\Coupon',
                        'translationable_id' => $coupon->id,
                        'locale' => $key,
                        'key' => 'title',
                        'value' => $request->title[$index],
                    ));
                }
            }
        }

        Translation::insert($data);

        Toastr::success(translate('messages.coupon_added_successfully'));
        return back();
    }

    public function edit($id)
    {
        $coupon = Coupon::withoutGlobalScope('translate')->where(['id' => $id])->first();
        return view('admin-views.coupon.edit', compact('coupon'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|max:100|unique:coupons,code,'.$id,
            'title' => 'required|max:191',
            'start_date' => 'required',
            'expire_date' => 'required',
            'discount' => 'required',
            'zone_ids' => 'required_if:coupon_type,zone_wise',
            'restaurant_ids' => 'required_if:coupon_type,restaurant_wise'
        ]);

        if($request->title[array_search('default', $request->lang)] == '' ){
            Toastr::error(translate('default_title_is_required'));
            return back();
            }
            
        $data  = '';
        if($request->coupon_type == 'zone_wise')
        {
            $data = $request->zone_ids;
        }
        else if($request->coupon_type == 'restaurant_wise')
        {
            $data = $request->restaurant_ids;
        }
        $customer_id  = $request->customer_ids ?? ['all'];

        $coupon = Coupon::find($id);
        $coupon->title = $request->title[array_search('default', $request->lang)];
        $coupon->code = $request->code;
        $coupon->limit = $request->coupon_type=='first_order'?1:$request->limit;
        $coupon->coupon_type = $request->coupon_type;
        $coupon->start_date = $request->start_date;
        $coupon->expire_date = $request->expire_date;
        $coupon->min_purchase = $request?->min_purchase ?? 0;
        $coupon->max_discount = $request?->max_discount ?? 0;
        $coupon->discount =$request->discount ?? 0;
        $coupon->discount_type = $request->discount_type??'';
        $coupon->data = json_encode($data);
        $coupon->customer_id = json_encode($customer_id);
        $coupon->save();
        $default_lang = str_replace('_', '-', app()->getLocale());
        foreach ($request->lang as $index => $key) {
            if($default_lang == $key && !($request->title[$index])){
                if ($key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\Coupon',
                            'translationable_id' => $coupon->id,
                            'locale' => $key,
                            'key' => 'title'
                        ],
                        ['value' => $coupon->title]
                    );
                }
            }else{

                if ($request->title[$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\Coupon',
                            'translationable_id' => $coupon->id,
                            'locale' => $key,
                            'key' => 'title'
                        ],
                        ['value' => $request->title[$index]]
                    );
                }
            }
        }



        Toastr::success(translate('messages.coupon_updated_successfully'));
        return redirect()->route('admin.coupon.add-new');
    }

    public function status(Request $request)
    {
        $coupon = Coupon::find($request->id);
        $coupon->status = $request->status;
        $coupon->save();
        Toastr::success(translate('messages.coupon_status_updated'));
        return back();
    }

    public function delete(Request $request)
    {
        $coupon = Coupon::find($request->id);
        $coupon?->translations()?->delete();
        $coupon->delete();
        Toastr::success(translate('messages.coupon_deleted_successfully'));
        return back();
    }

}
