<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Campaign;
use App\CentralLogics\BannerLogic;
use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function get_banners(Request $request)
    {
        if (!$request->hasHeader('zoneId')) {
            $errors = [];
            array_push($errors, ['code' => 'zoneId', 'message' => translate('messages.zone_id_required')]);
            return response()->json([
                'errors' => $errors
            ], 403);
        }
        $longitude= $request->header('longitude');
        $latitude= $request->header('latitude');
        $zone_id= json_decode($request->header('zoneId'), true);
        $banners = BannerLogic::get_banners($zone_id);
        $campaigns = Campaign::whereHas('restaurants', function($query)use($zone_id){
            $query->whereIn('zone_id', $zone_id);
        })->with('restaurants',function($query)use($zone_id,$longitude,$latitude){
            return $query->WithOpen($longitude,$latitude)->whereIn('zone_id', $zone_id);
        })->running()->active()->get();
        try {
            return response()->json(['campaigns'=>Helpers::basic_campaign_data_formatting($campaigns, true),'banners'=>$banners], 200);
        } catch (\Exception $e) {
            info($e->getMessage());
            return response()->json([], 200);
        }
    }
}
