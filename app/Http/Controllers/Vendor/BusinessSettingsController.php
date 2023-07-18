<?php

namespace App\Http\Controllers\Vendor;

use App\Models\Restaurant;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\BusinessSetting;
use App\Models\RestaurantSchedule;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Validator;

class BusinessSettingsController extends Controller
{

    private $restaurant;

    public function restaurant_index()
    {
        $restaurant = Helpers::get_restaurant_data();
        return view('vendor-views.business-settings.restaurant-index', compact('restaurant'));
    }

    public function restaurant_setup(Restaurant $restaurant, Request $request)
    {
        $request->validate([
            'gst' => 'required_if:gst_status,1',
            'per_km_delivery_charge'=>'required_with:minimum_delivery_charge|numeric|between:1,999999999999.99',
            'minimum_delivery_charge'=>'required_with:per_km_delivery_charge|numeric|between:1,999999999999.99',
            'maximum_shipping_charge'=>'nullable|gt:minimum_delivery_charge',
        ], [
            'gst.required_if' => translate('messages.gst_can_not_be_empty'),
        ]);

        $data =0;
        if (($restaurant->restaurant_model == 'subscription' && $restaurant?->restaurant_sub?->self_delivery == 1)  || ($restaurant->restaurant_model == 'commission' &&  $restaurant->self_delivery_system == 1) ){
            $data =1;
        }
        $cuisine_ids = [];
        $cuisine_ids=$request->cuisine_ids;

        $off_day = $request->off_day?implode('',$request->off_day):'';
        $restaurant->minimum_order = $request->minimum_order;
        $restaurant->opening_time = $request->opening_time;
        $restaurant->closeing_time = $request->closeing_time;
        $restaurant->off_day = $off_day;
        $restaurant->gst = json_encode(['status'=>$request->gst_status, 'code'=>$request->gst]);
        $restaurant->minimum_shipping_charge = $data?$request->minimum_delivery_charge??0: $restaurant->minimum_shipping_charge;
        $restaurant->per_km_shipping_charge = $data?$request->per_km_delivery_charge??0: $restaurant->per_km_shipping_charge;
        $restaurant->maximum_shipping_charge = $request->maximum_shipping_charge ?? null;
        // $restaurant->delivery_time = $request?->minimum_delivery_time .'-'. $request?->maximum_delivery_time.'-'.$request?->delivery_time_type;

        $restaurant->save();
        $restaurant->cuisine()->sync($cuisine_ids);

        Toastr::success(translate('messages.restaurant_settings_updated'));
        return back();
    }

    public function restaurant_status(Restaurant $restaurant, Request $request)
    {
        if($request->menu == "schedule_order" && !Helpers::schedule_order())
        {
            Toastr::warning(translate('messages.schedule_order_disabled_warning'));
            return back();
        }

        $home_delivery = BusinessSetting::where('key', 'home_delivery')->first()?->value ?? null;
        if ($request->menu == "delivery" && !$home_delivery) {
            Toastr::warning(translate('messages.Home_delivery_is_disabled_by_admin'));
            return back();
        }
        $take_away = BusinessSetting::where('key', 'take_away')->first()?->value ?? null;
        if ($request->menu == "take_away" && !$take_away) {
            Toastr::warning(translate('messages.Take_away_is_disabled_by_admin'));
            return back();

        }
        if((($request->menu == "delivery" && $restaurant->take_away==0) || ($request->menu == "take_away" && $restaurant->delivery==0)) &&  $request->status == 0 )
        {
            Toastr::warning(translate('messages.can_not_disable_both_take_away_and_delivery'));
            return back();
        }

        if((($request->menu == "veg" && $restaurant->non_veg==0) || ($request->menu == "non_veg" && $restaurant->veg==0)) &&  $request->status == 0 )
        {
            Toastr::warning(translate('messages.veg_non_veg_disable_warning'));
            return back();
        }

        if($request->menu == 'free_delivery' &&(($restaurant->restaurant_model == 'subscription' && $restaurant?->restaurant_sub?->self_delivery == 0) || ($restaurant->restaurant_model == 'unsubscribed'))){
            Toastr::error(translate('your_subscription_plane_does_not_have_this_feature'));
            return back();
        }

        $restaurant[$request->menu] = $request->status;
        $restaurant->save();
        Toastr::success(translate('messages.Restaurant settings updated!'));
        return back();
    }

    public function active_status(Request $request)
    {
        $restaurant = Helpers::get_restaurant_data();
        $restaurant->active = $restaurant->active?0:1;
        $restaurant->save();
        return response()->json(['message' => !$restaurant->active?translate('messages.restaurant_opened'):translate('messages.restaurant_temporarily_closed')], 200);
    }

    public function add_schedule(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'start_time'=>'required|date_format:H:i',
            'end_time'=>'required|date_format:H:i|after:start_time',
            'day'=>'required',
        ],[
            'end_time.after'=>translate('messages.End time must be after the start time')
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }
        $temp = RestaurantSchedule::where('day', $request->day)->where('restaurant_id',Helpers::get_restaurant_id())
        ->where(function($q)use($request){
            return $q->where(function($query)use($request){
                return $query->where('opening_time', '<=' , $request->start_time)->where('closing_time', '>=', $request->start_time);
            })->orWhere(function($query)use($request){
                return $query->where('opening_time', '<=' , $request->end_time)->where('closing_time', '>=', $request->end_time);
            });
        })
        ->first();

        if(isset($temp))
        {
            return response()->json(['errors' => [
                ['code'=>'time', 'message'=>translate('messages.schedule_overlapping_warning')]
            ]]);
        }

        $restaurant = Helpers::get_restaurant_data();
        $restaurant_schedule = RestaurantSchedule::insert(['restaurant_id'=>Helpers::get_restaurant_id(),'day'=>$request->day,'opening_time'=>$request->start_time,'closing_time'=>$request->end_time]);
        return response()->json([
            'view' => view('vendor-views.business-settings.partials._schedule', compact('restaurant'))->render(),
        ]);
    }

    public function remove_schedule($restaurant_schedule)
    {
        $restaurant = Helpers::get_restaurant_data();
        $schedule = RestaurantSchedule::where('restaurant_id', $restaurant->id)->find($restaurant_schedule);
        if(!$schedule)
        {
            return response()->json([],404);
        }
        $schedule->delete();
        return response()->json([
            'view' => view('vendor-views.business-settings.partials._schedule', compact('restaurant'))->render(),
        ]);
    }

    public function site_direction_vendor(Request $request){
        session()->put('site_direction_vendor', ($request->status == 1?'ltr':'rtl'));
        return response()->json();
    }

}
