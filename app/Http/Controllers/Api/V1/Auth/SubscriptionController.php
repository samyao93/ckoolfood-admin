<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Models\Restaurant;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\RestaurantWallet;
use App\Models\SubscriptionPackage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SubscriptionController extends Controller
{
    public function package_renew_change_update_api(Request $request){
        $validator = Validator::make($request->all(), [
            'restaurant_id' => 'required',
            'package_id' => 'required',
            'payment_type' => 'required',

        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $package = SubscriptionPackage::findOrFail($request->package_id);
        $discount = $request->discount ?? 0;
        $restaurant=Restaurant::findOrFail($request->restaurant_id);
        $restaurant_id=$restaurant->id;
        $total_price =$package->price - (($package->price*$discount)/100);
        $reference= $request->reference ?? null;
        $type = $request->type;

        if ($request->payment_type == 'wallet') {
            $wallet = RestaurantWallet::where('vendor_id',$restaurant->vendor_id)->first();
            if ( $wallet?->balance >= $total_price) {
                $payment_method= 'wallet';
                $status=  Helpers::subscription_plan_chosen(restaurant_id:$restaurant_id ,package_id:$package->id,payment_method: $payment_method ,discount:$discount, reference:$reference ,type:$type);

                if($status === 'downgrade_error'){
                    return response()->json([
                        'errors' => ['message' => translate('messages.You_can_not_downgraded_to_this_package_please_choose_a_package_with_higher_upload_limits')]
                    ], 403);
                }
                $wallet->total_withdrawn= $wallet->total_withdrawn +$total_price;
                    $wallet?->save();
            }
            else{
                return response()->json([
                    'errors' => ['message' => translate('messages.Insufficient Balance')]
                ], 403);
            }
        }
        elseif ($request->payment_type == 'pay_now') {
            // dd('pay_now');
        $payment_method= 'manual_payment_admin';
        $status=  Helpers::subscription_plan_chosen(restaurant_id:$restaurant_id ,package_id:$package->id,payment_method: $payment_method ,discount:$discount, reference:$reference ,type:$type);
        if($status === 'downgrade_error'){
            return response()->json([
                'errors' => ['message' => translate('messages.You_can_not_downgraded_to_this_package_please_choose_a_package_with_higher_upload_limits')]
            ], 403);
            }
        }
        return response()->json(['message' => translate('messages.subscription_successful')], 200);

    }
}
