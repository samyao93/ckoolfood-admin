<?php

namespace App\Http\Controllers\Vendor;

use App\Models\Restaurant;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use Illuminate\Support\Carbon;
use App\Models\RestaurantWallet;
use App\Models\SubscriptionPackage;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\RestaurantSubscription;
use App\Models\SubscriptionTransaction;

class SubscriptionController extends Controller
{
    public function subscription()
    {
            $id=Helpers::get_restaurant_id();
            $restaurant=  Restaurant::where('id',$id)->first();
            if ($restaurant->restaurant_model == 'subscription' || $restaurant->restaurant_model == 'unsubscribed') {
                $rest_subscription= RestaurantSubscription::where('restaurant_id', $id)->with(['package'])->latest()->first();
                $transcations = SubscriptionTransaction::where('restaurant_id', $id)->latest()->paginate(config('default_pagination'));
                $package_id=(isset($rest_subscription->package_id))  ? $rest_subscription->package_id : 0 ;
                $total_bill=SubscriptionTransaction::where('restaurant_id', $id)->where('package_id', $package_id)->sum('paid_amount');
                $packages= SubscriptionPackage::where('status', 1)->get();
                return view('vendor-views.subscription.subscription',compact(['rest_subscription','restaurant','transcations','packages','total_bill']));
            }
            else{
            abort(404);
        }
    }

        public function transcation(Request $request){
            $filter = $request->query('filter', 'all');
            $transcations = SubscriptionTransaction::where('restaurant_id', Helpers::get_restaurant_id())
            ->when($filter == 'month', function ($query) {
                return $query->whereMonth('created_at', Carbon::now()->month);
            })
            ->when($filter == 'year', function ($query) {
                return $query->whereYear('created_at', Carbon::now()->year);
            })
            ->latest()->paginate(config('default_pagination'));
            $total = $transcations->total();
            return view('vendor-views.subscription.subscription-transaction',[
            'transcations' => $transcations,
            'filter' => $filter,
            'total' => $total,
            ]);
        }
        public function trans_search_by_date(Request $request){
            $from=$request->start_date;
            $to= $request->end_date;
            $filter = 'all';
            $transcations=SubscriptionTransaction::where('restaurant_id', Helpers::get_restaurant_id())
            ->whereBetween('created_at', ["{$from}", "{$to} 23:59:59"])
            ->latest()->paginate(config('default_pagination'));
            $total = $transcations->total();
            return view('vendor-views.subscription.subscription-transaction',[
                'transcations' => $transcations,
                'filter' => $filter,
                'total' => $total,
                'from' =>  $from,
                'to' =>  $to,
                ]);
        }

    public function package_renew_change_update(Request $request){
        $package = SubscriptionPackage::findOrFail($request->package_id);
        $restaurant_id=Helpers::get_restaurant_id();
        $discount = $request->discount ?? 0;

        $total_parice =$package->price - (($package->price*$discount)/100);
        $reference= $request->reference ?? null;
        if($request->button == 'renew'){
            $type = 'renew';
        }else{
            $type = null;
        }
        if ($request->payment_type == 'wallet') {
            $wallet = RestaurantWallet::where('vendor_id',Helpers::get_vendor_id())->first();
            if ( $wallet?->balance >= $total_parice) {
                $payment_method= 'wallet';
                $status =Helpers::subscription_plan_chosen(restaurant_id:$restaurant_id ,package_id:$package->id,payment_method: $payment_method ,discount:$discount, reference:$reference ,type:$type);
                if($status === 'downgrade_error'){
                    Toastr::error(translate('messages.You_can_not_downgraded_to_this_package_please_choose_a_package_with_higher_upload_limits') );
                    return back();
                    }
                $wallet->total_withdrawn= $wallet->total_withdrawn +$total_parice;
                    $wallet?->save();
            }
            else{
                Toastr::error('Insufficient Balance');
                return back();
            }
        }
        elseif ($request->payment_type == 'pay_now') {
            // dd('pay_now');
            $payment_method= 'manual_payment_by_restaurant';
            $status = Helpers::subscription_plan_chosen(restaurant_id:$restaurant_id ,package_id:$package->id,payment_method: $payment_method ,discount:$discount, reference:$reference ,type:$type);
            if($status === 'downgrade_error'){
                Toastr::error(translate('messages.You_can_not_downgraded_to_this_package_please_choose_a_package_with_higher_upload_limits') );
                return back();
                }
        }
        Toastr::success(translate('messages.subscription_successful') );
        return redirect()->route('vendor.subscription.subscription');
    }

    public function rest_transcation_search(Request $request)
    {
        $key = explode(' ', $request['search']);
        $transcations = SubscriptionTransaction::where('restaurant_id',Helpers::get_restaurant_id())->where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('id', 'like', "%{$value}%")
                    ->orWhere('paid_amount', 'like', "%{$value}%")
                    ->orWhere('reference', 'like', "%{$value}%")
                    ->orWheredate('created_at', 'like', "%{$value}%");
            }
        })
            ->get();
        $total = $transcations->count();
        return response()->json([
            'view' => view('vendor-views.subscription._rest_subs_transcation', compact('transcations'))->render(), 'total'=> $total
        ]);
    }

    public function invoice($id){
        $subscription_transaction= SubscriptionTransaction::findOrFail($id);
        $restaurant= Restaurant::findOrFail($subscription_transaction->restaurant_id);

        return view('vendor-views.subscription.subs_transcation_invoice', compact(
            'restaurant',
            'subscription_transaction',
        ));
    }

    public function package_selected(Request $request,$id){

        $rest_subscription= RestaurantSubscription::where('restaurant_id',Helpers::get_restaurant_id())->with(['package'])->latest()->first();
        $package = SubscriptionPackage::where('status',1)->where('id',$id)->first();
        return response()->json([
            'view' => view('vendor-views.subscription._package_selected', compact('rest_subscription','package'))->render()
        ]);
    }


}

