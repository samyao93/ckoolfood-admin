<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Cuisine;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CuisineController extends Controller
{
    public function get_all_cuisines()
    {
        $Cuisines = Cuisine::where('status',1)->get();
        return response()->json( ['Cuisines' => $Cuisines], 200);
    }
    public function get_restaurants(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cuisine_id' => 'required',
            'limit' => 'required',
            'offset' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 203);
        }
        $longitude= $request->header('longitude');
        $latitude= $request->header('latitude');

        if (!$request->hasHeader('zoneId')) {
            $errors = [];
            array_push($errors, ['code' => 'zoneId', 'message' => translate('messages.zone_id_required')]);
            return response()->json([
                'errors' => $errors
            ], 403);
        }
        $zone_id= json_decode($request->header('zoneId'), true);
        $limit = $request->query('limit', 1);
        $offset = $request->query('offset', 1);

        $restaurants=Restaurant::whereIn('zone_id',$zone_id)->cuisine($request->cuisine_id)->WithOpen($longitude,$latitude)
        ->paginate($limit, ['*'], 'page', $offset);

        $restaurants_data = Helpers::restaurant_data_formatting($restaurants->items(), true);

        $data = [
            'total_size' => $restaurants->total(),
            'limit' => $limit,
            'offset' => $offset,
            'restaurants' => $restaurants_data,
        ];
        return response()->json($data, 200);

    }
}
