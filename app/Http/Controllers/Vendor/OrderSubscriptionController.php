<?php

namespace App\Http\Controllers\Vendor;

use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CentralLogics\Helpers;
class OrderSubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $key = explode(' ', $request['search']);

        $subscriptions = Subscription::with(['customer', 'order.details'])->where('restaurant_id',Helpers::get_restaurant_id())
        ->when(isset($request['search']), function ($query) use($key){
            $query->whereHas('order',function ($qu) use ($key){
                    foreach ($key as $value) {
                        $qu->Where('id', 'like', "%{$value}%");
                    }
            });
        })->orderBy('created_at', 'desc')
        ->paginate(config('default_pagination'));
        return view('vendor-views.order-subscription.index', compact('subscriptions'));
    }
    public function show(Request $request, Subscription $subscription)
    {
        $tab = $request->query('tab', 'info');
        return view('vendor-views.order-subscription.view', compact('subscription', 'tab'));
    }
}
