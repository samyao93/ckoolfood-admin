<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Coupon;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\CentralLogics\CouponLogic;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{
    public function list(Request $request)
    {
        if (!$request->hasHeader('zoneId')) {
            $errors = [];
            array_push($errors, ['code' => 'zoneId', 'message' => translate('messages.zone_id_required')]);
            return response()->json([
                'errors' => $errors
            ], 403);
        }
        $customer_id=Auth::user()?->id;
        $zone_id= json_decode($request->header('zoneId'), true);
        $data = [];
            $coupons = Coupon::with('restaurant:id,name')->active()->whereDate('expire_date', '>=', date('Y-m-d'))->whereDate('start_date', '<=', date('Y-m-d'))
            ->when(isset($request->restaurant_id), function($query) use($request){
                $query->where('restaurant_id',$request->restaurant_id)
                        ->orWhere(function($query)use($request){
                            $query->where('coupon_type','restaurant_wise')->whereJsonContains('data',$request->restaurant_id);
                        });
            })
            ->get();
            foreach($coupons as $key=>$coupon)
            {
                if($coupon->coupon_type == 'restaurant_wise')
                {
                    $temp = Restaurant::active()->whereIn('zone_id', $zone_id)->whereIn('id', json_decode($coupon->data, true))->first();
                    if($temp && (in_array("all", json_decode($coupon->customer_id, true)) || in_array($customer_id,json_decode($coupon->customer_id, true))))
                    {
                        $coupon->data = $temp->name;
                        $data[] = $coupon;
                    }
                }
                else if($coupon->coupon_type == 'zone_wise')
                {
                    foreach($zone_id as $z_id) {
                        if(in_array($z_id, json_decode($coupon->data,true)))
                        {
                            $data[] = $coupon;
                            break;
                        }
                    }
                }
                else{
                    if((in_array("all", json_decode($coupon->customer_id, true)) || in_array($customer_id,json_decode($coupon->customer_id, true))) ){
                        $data[] = $coupon;
                    }
                }
            }
            return response()->json($data, 200);
    }



    public function apply(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'restaurant_id' => 'required',
        ]);

        if ($validator->errors()->count()>0) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        try {
            $coupon = Coupon::active()->where(['code' => $request['code']])->first();
            if (isset($coupon)) {
                $staus = CouponLogic::is_valide($coupon, $request->user()->id ,$request['restaurant_id']);

                switch ($staus) {
                case 200:
                    return response()->json($coupon, 200);
                case 406:
                    return response()->json([
                        'errors' => [
                            ['code' => 'coupon', 'message' => translate('messages.coupon_usage_limit_over')]
                        ]
                    ], 406);
                case 407:
                    return response()->json([
                        'errors' => [
                            ['code' => 'coupon', 'message' => translate('messages.coupon_expire')]
                        ]
                    ], 407);
                case 408:
                    return response()->json([
                        'errors' => [
                            ['code' => 'coupon', 'message' => translate('messages.You_are_not_eligible_for_this_coupon')]
                        ]
                    ], 403);
                default:
                    return response()->json([
                        'errors' => [
                            ['code' => 'coupon', 'message' => translate('messages.not_found')]
                        ]
                    ], 404);
                }
            } else {
                return response()->json([
                    'errors' => [
                        ['code' => 'coupon', 'message' => translate('messages.not_found')]
                    ]
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 403);
        }
    }
}
