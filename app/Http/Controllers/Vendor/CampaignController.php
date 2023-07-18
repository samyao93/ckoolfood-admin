<?php

namespace App\Http\Controllers\Vendor;

use App\Models\Admin;
use App\Models\Campaign;
use App\Models\ItemCampaign;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Mail;


class CampaignController extends Controller
{
    function list()
    {
        $campaigns=Campaign::with('restaurants')->running()->active()->latest()->paginate(config('default_pagination'));
        return view('vendor-views.campaign.list',compact('campaigns'));
    }
    function itemlist()
    {
        $campaigns=ItemCampaign::where('restaurant_id', Helpers::get_restaurant_id())->latest()->paginate(config('default_pagination'));
        return view('vendor-views.campaign.food_list',compact('campaigns'));
    }

    public function remove_restaurant(Campaign $campaign, $restaurant)
    {
        $campaign?->restaurants()?->detach($restaurant);
        $campaign->save();
        Toastr::success(translate('messages.restaurant_remove_from_campaign'));
        return back();
    }
    public function addrestaurant(Campaign $campaign, $restaurant)
    {
        $campaign?->restaurants()?->attach($restaurant, ['campaign_status' => 'pending']);
        $campaign->save();
        try
        {
            $mail_status = Helpers::get_mail_status('campaign_request_mail_status_admin');
            $admin= Admin::where('role_id', 1)->first();
            $restaurant= Restaurant::where('id', $restaurant )->with('vendor')->first();
            if(config('mail.status') && $mail_status == '1') {
                Mail::to($admin->email)->send(new \App\Mail\CampaignRequestMail($restaurant?->name));
            }
            $mail_status = Helpers::get_mail_status('campaign_request_mail_status_restaurant');
            if(config('mail.status') && $mail_status == '1') {
                Mail::to($restaurant?->vendor?->email)->send(new \App\Mail\VendorCampaignRequestMail($restaurant?->name,'pending'));
            }
        }
        catch(\Exception $e)
        {
            info($e->getMessage());
        }
        Toastr::success(translate('messages.restaurant_added_to_campaign'));
        return back();
    }

    public function search(Request $request){
        $key = explode(' ', $request['search']);
        $campaigns=Campaign::
        where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('title', 'like', "%{$value}%");
            }
        })->limit(50)->get();
        return response()->json([
            'view'=>view('vendor-views.campaign.partials._table',compact('campaigns'))->render()
        ]);
    }

    public function searchItem(Request $request){
        $key = explode(' ', $request['search']);
        $campaigns=ItemCampaign::where('restaurant_id', Helpers::get_restaurant_id())->where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('title', 'like', "%{$value}%");
            }
        })->limit(50)->get();
        return response()->json([
            'view'=>view('vendor-views.campaign.partials._item_table',compact('campaigns'))->render(),
            'count'=>$campaigns->count(),
        ]);
    }

}
