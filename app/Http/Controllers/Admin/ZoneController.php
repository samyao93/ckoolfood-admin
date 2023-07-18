<?php

namespace App\Http\Controllers\Admin;

use App\Models\Zone;
use App\Models\Incentive;
use App\Models\Translation;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Rap2hpoutre\FastExcel\FastExcel;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use Illuminate\Support\Facades\Validator;

class ZoneController extends Controller
{
    public function index(Request $request)
    {
        $key = explode(' ', $request['search'] ?? null );
        $zones = Zone::withCount(['restaurants','deliverymen'])
        ->when(isset($key), function($query)use($key){
            $query->where( function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%");
                }
            });
        })
        ->latest()->paginate(config('default_pagination'));
        return view('admin-views.zone.index', compact('zones'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:zones|max:191',
            'coordinates' => 'required',
        ]);

        if($request->name[array_search('default', $request->lang)] == '' ){
            $validator->getMessageBag()->add('title', translate('messages.default_Business_zone_name_is_required'));
                    return response()->json(['errors' => Helpers::error_processor($validator)]);
            }

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }
        $value = $request->coordinates;
        foreach(explode('),(',trim($value,'()')) as $index=>$single_array){
            if($index == 0)
            {
                $lastcord = explode(',',$single_array);
            }
            $coords = explode(',',$single_array);
            $polygon[] = new Point($coords[0], $coords[1]);
        }
        $zone_id=Zone::all()->count() + 1;
        $polygon[] = new Point($lastcord[0], $lastcord[1]);
        $zone = new Zone();
        $zone->name = $request->name[array_search('default', $request->lang)];
        $zone->coordinates = new Polygon([new LineString($polygon)]);
        $zone->restaurant_wise_topic =  'zone_'.$zone_id.'_restaurant';
        $zone->customer_wise_topic = 'zone_'.$zone_id.'_customer';
        $zone->deliveryman_wise_topic = 'zone_'.$zone_id.'_delivery_man';
        $zone->per_km_shipping_charge = $request->per_km_delivery_charge ?? 0;
        $zone->minimum_shipping_charge = $request->minimum_delivery_charge ?? 0;
        $zone->maximum_shipping_charge = $request->maximum_shipping_charge ??null ;
        $zone->max_cod_order_amount = $request->max_cod_order_amount ?? null;
        $zone->save();
        $default_lang = str_replace('_', '-', app()->getLocale());
        $data = [];
        foreach($request->lang as $index=>$key)
        {
            if($default_lang == $key && !($request->name[$index])){
                if($key != 'default')
                {
                    array_push($data, Array(
                        'translationable_type'  => 'App\Models\Zone',
                        'translationable_id'    => $zone->id,
                        'locale'                => $key,
                        'key'                   => 'name',
                        'value'                 => $zone->name,
                    ));
                }
            }else{
                if($request->name[$index] && $key != 'default')
                {
                    array_push($data, Array(
                        'translationable_type'  => 'App\Models\Zone',
                        'translationable_id'    => $zone->id,
                        'locale'                => $key,
                        'key'                   => 'name',
                        'value'                 => $request->name[$index],
                    ));
                }
            }
        }

        if(count($data))
        {
            Translation::insert($data);
        }
        $new_data=1;
        // Toastr::success(translate('messages.zone_added_successfully'));
        $zones = Zone::withCount(['restaurants','deliverymen'])->latest()->paginate(config('default_pagination'));
        // return redirect()->view('admin-views.zone.index', compact('zones','new_data'));
        return response()->json([
            'view'=>view('admin-views.zone.partials._table',compact('zones','new_data'))->render(),
            'total'=>$zones->count()
        ]);
    }

    public function edit($id)
    {
        if(env('APP_MODE')=='demo' && $id == 1)
        {
            Toastr::warning(translate('messages.you_can_not_edit_this_zone_please_add_a_new_zone_to_edit'));
            return back();
        }
        $zone=Zone::selectRaw("*,ST_AsText(ST_Centroid(`coordinates`)) as center")->findOrFail($id);
        $area = json_decode($zone->coordinates[0]->toJson(),true);
        return view('admin-views.zone.edit', compact(['zone','area']));
    }
    public function zone_settings($id)
    {
        if(env('APP_MODE')=='demo' && $id == 1)
        {
            Toastr::warning(translate('messages.you_can_not_edit_this_zone_please_add_a_new_zone_to_edit'));
            return back();
        }
        $zone=Zone::with('incentives')->selectRaw("*,ST_AsText(ST_Centroid(`coordinates`)) as center")->findOrFail($id);
        return view('admin-views.zone.settings', compact('zone'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:191|unique:zones,name,'.$id,
            'coordinates' => 'required',
        ]);

        if($request->name[array_search('default', $request->lang)] == '' ){
                    Toastr::error(translate('default_Business_zone_name_is_required'));
            return back();

                }

        $value = $request->coordinates;
        foreach(explode('),(',trim($value,'()')) as $index=>$single_array){
            if($index == 0)
            {
                $lastcord = explode(',',$single_array);
            }
            $coords = explode(',',$single_array);
            $polygon[] = new Point((float)$coords[0], (float)$coords[1]);
        }
        $polygon[] = new Point((float)$lastcord[0], (float)$lastcord[1]);
        $zone=Zone::findOrFail($id);
        $zone->name = $request->name[array_search('default', $request->lang)];
        $zone->coordinates = new Polygon([new LineString($polygon)]);
        $zone->restaurant_wise_topic =  'zone_'.$id.'_restaurant';
        $zone->customer_wise_topic = 'zone_'.$id.'_customer';
        $zone->deliveryman_wise_topic = 'zone_'.$id.'_delivery_man';
        $zone->save();

        $default_lang = str_replace('_', '-', app()->getLocale());
        foreach ($request->lang as $index => $key) {
            if ($default_lang == $key && !($request->name[$index])) {
                if ($key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\Zone',
                            'translationable_id' => $zone->id,
                            'locale' => $key,
                            'key' => 'name'
                        ],
                        ['value' => $zone->name]
                    );
                }
            } else {
                if ($request->name[$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\Zone',
                            'translationable_id' => $zone->id,
                            'locale' => $key,
                            'key' => 'name'
                        ],
                        ['value' => $request->name[$index]]
                    );
                }
            }

        }


        Toastr::success(translate('messages.zone_updated_successfully'));
        return redirect()->route('admin.zone.home');
    }

    public function zone_settings_update(Request $request, $id){
        $request->validate([
            'per_km_delivery_charge'=>'required|numeric|between:0.001,999999999999.99',
            'minimum_delivery_charge'=>'required|numeric|between:0.001,999999999999.99',
            'maximum_shipping_charge' => 'nullable|numeric|between:0,999999999999.99|gt:minimum_delivery_charge',
            'max_cod_order_amount' => 'nullable|numeric|between:0,999999999999.99',
            'increased_delivery_fee' => 'nullable|numeric|between:0,999999999.99|required_if:increased_delivery_fee_status,1',
            ], [
                'increased_delivery_fee.required_if' => translate('messages.increased_delivery_fee_is_required')
            ]);


        $zone=Zone::findOrFail($id);
        $zone->restaurant_wise_topic =  'zone_'.$id.'_restaurant';
        $zone->customer_wise_topic = 'zone_'.$id.'_customer';
        $zone->deliveryman_wise_topic = 'zone_'.$id.'_delivery_man';
        $zone->per_km_shipping_charge = $request->per_km_delivery_charge;
        $zone->minimum_shipping_charge = $request->minimum_delivery_charge;
        $zone->maximum_shipping_charge = $request->maximum_shipping_charge ?? null;
        $zone->max_cod_order_amount = $request->max_cod_order_amount ?? null;
        $zone->increased_delivery_fee = $request->increased_delivery_fee ?? 0;
        $zone->increased_delivery_fee_status = $request->increased_delivery_fee_status ?? 0;
        $zone->increase_delivery_charge_message = $request->increase_delivery_charge_message ?? null;
        $zone->save();
        Toastr::success(translate('messages.zone_settings_updated_successfully'));
        return back();
    }

    public function destroy(Zone $zone)
    {
        if(env('APP_MODE')=='demo' && $zone->id == 1)
        {
            Toastr::warning(translate('messages.you_can_not_delete_this_zone_please_add_a_new_zone_to_delete'));
            return back();
        }
        $zone->delete();
        Toastr::success(translate('messages.zone_deleted_successfully'));
        return back();
    }

    public function status(Request $request)
    {
        if(env('APP_MODE')=='demo' && $request->id == 1)
        {
            Toastr::warning('Sorry!You can not inactive this zone!');
            return back();
        }
        $zone = Zone::findOrFail($request->id);
        $zone->status = $request->status;
        $zone->save();
        Toastr::success(translate('messages.zone_status_updated'));
        return back();
    }

    // public function search(Request $request){
    //     $key = explode(' ', $request['search']);
    //     $zones=Zone::withCount(['restaurants','deliverymen'])
    //     ->where(function ($q) use ($key) {
    //         foreach ($key as $value) {
    //             $q->orWhere('name', 'like', "%{$value}%");
    //         }
    //     })->limit(50)->get();
    //     return response()->json([
    //         'view'=>view('admin-views.zone.partials._table',compact('zones'))->render(),
    //         'total'=>$zones->count()
    //     ]);
    // }

    public function get_coordinates($id){
        $zone=Zone::withoutGlobalScopes()->selectRaw("*,ST_AsText(ST_Centroid(`coordinates`)) as center")->findOrFail($id);
        $area = json_decode($zone->coordinates[0]->toJson(),true);
        $data = Helpers::format_coordiantes($area['coordinates']);
        $center = (object)['lat'=>(float)trim(explode(' ',$zone->center)[1], 'POINT()'), 'lng'=>(float)trim(explode(' ',$zone->center)[0], 'POINT()')];
        return response()->json(['coordinates'=>$data, 'center'=>$center]);
    }

    public function zone_filter($id)
    {
        if($id == 'all')
        {
            if(session()->has('zone_id')){
                session()->forget('zone_id');
            }
        }
        else{
            session()->put('zone_id', $id);
        }

        return back();
    }

    public function get_all_zone_cordinates($id = 0)
    {
        $zones = Zone::where('id', '<>', $id)->active()->get();
        $data = [];
        foreach($zones as $zone)
        {
            $area = json_decode($zone->coordinates[0]->toJson(),true);
            $data[] = Helpers::format_coordiantes($area['coordinates']);
        }
        return response()->json($data,200);
    }

    public function export_zones(Request $request, $type){


        $zones = Zone::with('restaurants', 'deliverymen')->get();
        if($type == 'csv'){
            return (new FastExcel(Helpers::export_zones($zones)))->download('Zones.csv');
        }
        return (new FastExcel(Helpers::export_zones($zones)))->download('Zones.xlsx');
    }

    public function store_incentive(Request $request, $zone_id)
    {
        $request->validate([
            'earning' => 'required|unique:incentives|numeric|between:1,999999999999.99',
            'incentive' => 'required|numeric|between:1,999999999999.99'
        ],[
            'earning.unique' => translate('This_earning_amount_already_exists')
        ]);

        $incentive = new Incentive();
        $incentive->earning = $request->earning;
        $incentive->incentive = $request->incentive;
        $incentive->zone_id = $zone_id;
        $incentive->save();
        Toastr::success(translate('messages.incentive_inserted_successfully'));
        return back();
    }

    public function destroy_incentive(Request $request, $id)
    {
        $incentive = Incentive::findOrFail($id);
        $incentive?->delete();
        Toastr::success(translate('messages.incentive_deleted_successfully'));
        return back();
    }
}
