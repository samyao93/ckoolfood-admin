<?php

namespace App\Http\Controllers\Admin;

use App\Models\Zone;
use App\Models\AddOn;
use App\Models\Vendor;
use App\Models\Message;
use App\Models\UserInfo;
use App\Models\Restaurant;
use App\Models\Translation;
use Illuminate\Support\Str;
use App\Models\Conversation;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use Illuminate\Support\Carbon;
use App\Models\BusinessSetting;
use App\Models\WithdrawRequest;
use App\Scopes\RestaurantScope;
use App\Models\OrderTransaction;
use App\Models\RestaurantWallet;
use App\Models\AccountTransaction;
use App\Models\RestaurantSchedule;
use Illuminate\Support\Facades\DB;
use App\Models\SubscriptionPackage;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Mail;
use Rap2hpoutre\FastExcel\FastExcel;
use App\CentralLogics\RestaurantLogic;
use App\Models\RestaurantSubscription;
use App\Models\SubscriptionTransaction;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use MatanYadaev\EloquentSpatial\Objects\Point;


class VendorController extends Controller
{
    public function index()
    {
        return view('admin-views.vendor.index');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'f_name' => 'required|max:100',
            'l_name' => 'nullable|max:100',
            'name' => 'required|max:191',
            'address' => 'required|max:1000',
            'latitude' => 'required|numeric|min:-90|max:90',
            'longitude' => 'required|numeric|min:-180|max:180',
            'email' => 'required|unique:vendors',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:20|unique:vendors',
            'minimum_delivery_time' => 'required',
            'maximum_delivery_time' => 'required|gt:minimum_delivery_time',
            'password' => ['required', Password::min(8)->mixedCase()->letters()->numbers()->symbols()->uncompromised()],

            'zone_id' => 'required',
            'logo' => 'required|max:2048',
            'cover_photo' => 'required|max:2048',
            'tax' => 'required',
            'delivery_time_type'=>'required',
        ], [
            'f_name.required' => translate('messages.first_name_is_required')
        ]);
        $cuisine_ids = [];
        $cuisine_ids=$request->cuisine_ids;
        if ($request->zone_id) {
            $zone = Zone::query()
            ->whereContains('coordinates', new Point($request->latitude, $request->longitude, POINT_SRID))->where('id', $request->zone_id)->first();
            if (!$zone) {
                $validator->getMessageBag()->add('latitude', translate('messages.coordinates_out_of_zone'));
                return back()->withErrors($validator)
                    ->withInput();
            }
        }

        if($request->name[array_search('default', $request->lang)] == '' ){
                    $validator->getMessageBag()->add('address', translate('messages.default_restaurant_name_is_required'));
                return back()->withErrors($validator)->withInput();
            }
        if($request->address[array_search('default', $request->lang)] == '' ){
                    $validator->getMessageBag()->add('address', translate('messages.default_restaurant_address_is_required'));
                return back()->withErrors($validator)->withInput();
                }

        if ($request->delivery_time_type == 'min') {
            $minimum_delivery_time = (int) $request->input('minimum_delivery_time');
            if ($minimum_delivery_time < 10) {
                $validator->getMessageBag()->add('minimum_delivery_time', translate('messages.minimum_delivery_time_should_be_more_than_10_min'));
                return back()->withErrors($validator)->withInput();
            }
        }
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        $vendor = new Vendor();
        $vendor->f_name = $request->f_name;
        $vendor->l_name = $request->l_name;
        $vendor->email = $request->email;
        $vendor->phone = $request->phone;
        $vendor->password = bcrypt($request->password);
        $vendor->save();

        $restaurant = new Restaurant;
        $restaurant->name = $request->name[array_search('default', $request->lang)];
        $restaurant->phone = $request->phone;
        $restaurant->email = $request->email;
        $restaurant->logo = Helpers::upload( dir: 'restaurant/', format: 'png',  image: $request->file('logo'));
        $restaurant->cover_photo = Helpers::upload( dir: 'restaurant/cover/',  format:'png', image:  $request->file('cover_photo'));
        $restaurant->address = $request->address[array_search('default', $request->lang)];
        $restaurant->latitude = $request->latitude;
        $restaurant->longitude = $request->longitude;
        $restaurant->vendor_id = $vendor->id;
        $restaurant->zone_id = $request->zone_id;
        $restaurant->tax = $request->tax;
        $restaurant->restaurant_model = 'none';
        $restaurant->delivery_time =$request->minimum_delivery_time .'-'. $request->maximum_delivery_time.'-'.$request->delivery_time_type;
        $restaurant->save();
        $restaurant->cuisine()->sync($cuisine_ids);

        $default_lang = str_replace('_', '-', app()->getLocale());
        $data = [];
        foreach ($request->lang as $index => $key) {
            if($default_lang == $key && !($request->name[$index])){
                if ($key != 'default') {
                    array_push($data, array(
                        'translationable_type' => 'App\Models\Restaurant',
                        'translationable_id' => $restaurant->id,
                        'locale' => $key,
                        'key' => 'name',
                        'value' => $restaurant->name,
                    ));
                }
            }else{
                if ($request->name[$index] && $key != 'default') {
                    array_push($data, array(
                        'translationable_type' => 'App\Models\Restaurant',
                        'translationable_id' => $restaurant->id,
                        'locale' => $key,
                        'key' => 'name',
                        'value' => $request->name[$index],
                    ));
                }
            }
            if($default_lang == $key && !($request->address[$index])){
                if ($key != 'default') {
                    array_push($data, array(
                        'translationable_type' => 'App\Models\Restaurant',
                        'translationable_id' => $restaurant->id,
                        'locale' => $key,
                        'key' => 'address',
                        'value' => $restaurant->address,
                    ));
                }
            }else{
                if ($request->address[$index] && $key != 'default') {
                    array_push($data, array(
                        'translationable_type' => 'App\Models\Restaurant',
                        'translationable_id' => $restaurant->id,
                        'locale' => $key,
                        'key' => 'address',
                        'value' => $request->address[$index],
                    ));
                }
            }
        }
        Translation::insert($data);
        Toastr::success(translate('messages.vendor') . translate('messages.added_successfully'));
        return redirect('admin/restaurant/list');
    }

    public function edit($id)
    {
        if (env('APP_MODE') == 'demo' && $id == 2) {
            Toastr::warning(translate('messages.you_can_not_edit_this_restaurant_please_add_a_new_restaurant_to_edit'));
            return back();
        }
        $restaurant = Restaurant::withoutGlobalScope('translate')->with('translations')->find($id);
        return view('admin-views.vendor.edit', compact('restaurant'));
    }


    public function update(Request $request, Restaurant $restaurant)
    {
        $validator = Validator::make($request->all(), [
            'f_name' => 'required|max:100',
            'l_name' => 'nullable|max:100',
            'name' => 'required|max:191',
            'email' => 'required|unique:vendors,email,' . $restaurant?->vendor?->id,
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:20|unique:vendors,phone,' . $restaurant?->vendor?->id,
            'zone_id' => 'required',
            'latitude' => 'required|min:-90|max:90',
            'longitude' => 'required|min:-180|max:180',
            'tax' => 'required',
            'password' => ['nullable', Password::min(8)->mixedCase()->letters()->numbers()->symbols()->uncompromised()],
            'minimum_delivery_time' => 'required',
            'maximum_delivery_time' => 'required|gt:minimum_delivery_time',
            'logo' => 'nullable|max:2048',
            'cover_photo' => 'nullable|max:2048',
            'delivery_time_type'=>'required',
        ], [
            'f_name.required' => translate('messages.first_name_is_required')
        ]);


        if($request->name[array_search('default', $request->lang)] == '' ){
                    $validator->getMessageBag()->add('address', translate('messages.default_restaurant_name_is_required'));
                return back()->withErrors($validator)->withInput();
            }
        if($request->address[array_search('default', $request->lang)] == '' ){
                    $validator->getMessageBag()->add('address', translate('messages.default_restaurant_address_is_required'));
                return back()->withErrors($validator)->withInput();
                }
        if ($request?->zone_id) {
            $zone = Zone::query()
            ->whereContains('coordinates', new Point($request->latitude, $request->longitude, POINT_SRID))
            ->where('id',$request->zone_id)
            ->first();

            if (!$zone) {
                $validator->getMessageBag()->add('latitude', translate('messages.coordinates_out_of_zone'));
                return back()->withErrors($validator)
                    ->withInput();
            }
        }


        if ($request->delivery_time_type == 'min') {
            $minimum_delivery_time = (int) $request->input('minimum_delivery_time');
            if ($minimum_delivery_time < 10) {
                $validator->getMessageBag()->add('minimum_delivery_time', translate('messages.minimum_delivery_time_should_be_more_than_10_min'));
                return back()->withErrors($validator)->withInput();
            }
        }
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        $vendor = Vendor::findOrFail($restaurant?->vendor?->id);
        $vendor->f_name = $request->f_name;
        $vendor->l_name = $request->l_name;
        $vendor->email = $request->email;
        $vendor->phone = $request->phone;
        $vendor->password = strlen($request->password) > 1 ? bcrypt($request->password) : $restaurant->vendor->password;
        $vendor->save();

        $cuisine_ids = [];
        $cuisine_ids=$request->cuisine_ids;

        $slug = Str::slug($request->name[array_search('default', $request->lang)]);
        $restaurant->slug = $restaurant->slug? $restaurant->slug :"{$slug}{$restaurant->id}";

        $restaurant->email = $request->email;
        $restaurant->phone = $request->phone;
        $restaurant->logo = $request->has('logo') ? Helpers::update( dir:'restaurant/',old_image: $restaurant->logo, format:'png',image: $request->file('logo')) : $restaurant->logo;
        $restaurant->cover_photo = $request->has('cover_photo') ? Helpers::update( dir:'restaurant/cover/', old_image: $restaurant->cover_photo, format:'png', image:$request->file('cover_photo')) : $restaurant->cover_photo;
        $restaurant->name = $request->name[array_search('default', $request->lang)];
        $restaurant->address = $request->address[array_search('default', $request->lang)];
        $restaurant->latitude = $request->latitude;
        $restaurant->longitude = $request->longitude;
        $restaurant->zone_id = $request->zone_id;
        $restaurant->tax = $request->tax;
        $restaurant->delivery_time =$request->minimum_delivery_time .'-'. $request->maximum_delivery_time.'-'.$request->delivery_time_type;
        $restaurant->save();

        $default_lang = str_replace('_', '-', app()->getLocale());
        foreach($request->lang as $index=>$key)
        {
            if($default_lang == $key && !($request->name[$index])){
                if ($key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\Restaurant',
                            'translationable_id' => $restaurant->id,
                            'locale' => $key,
                            'key' => 'name'
                        ],
                        ['value' => $restaurant->name]
                    );
                }
            }else{

                if ($request->name[$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        ['translationable_type'  => 'App\Models\Restaurant',
                            'translationable_id'    => $restaurant->id,
                            'locale'                => $key,
                            'key'                   => 'name'],
                        ['value'                 => $request->name[$index]]
                    );
                }
            }
            if($default_lang == $key && !($request->address[$index])){
                if ($key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\Restaurant',
                            'translationable_id' => $restaurant->id,
                            'locale' => $key,
                            'key' => 'address'
                        ],
                        ['value' => $restaurant->address]
                    );
                }
            }else{

                if ($request->address[$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        ['translationable_type'  => 'App\Models\Restaurant',
                            'translationable_id'    => $restaurant->id,
                            'locale'                => $key,
                            'key'                   => 'address'],
                        ['value'                 => $request->address[$index]]
                    );
                }
            }
        }
        $restaurant?->cuisine()?->sync($cuisine_ids);
        if ($vendor?->userinfo) {
            $userinfo = $vendor->userinfo;
            $userinfo->f_name = $request->name;
            $userinfo->l_name = '';
            $userinfo->email = $request->email;
            $userinfo->image = $restaurant->logo;
            $userinfo->save();
        }
        Toastr::success(translate('messages.restaurant') . translate('messages.updated_successfully'));
        return redirect('admin/restaurant/list');
    }

    public function destroy(Request $request, Restaurant $restaurant)
    {
        if (env('APP_MODE') == 'demo' && $restaurant->id == 2) {
            Toastr::warning(translate('messages.you_can_not_delete_this_restaurant_please_add_a_new_restaurant_to_delete'));
            return back();
        }
        if (Storage::disk('public')->exists('restaurant/' . $restaurant['logo'])) {
            Storage::disk('public')->delete('restaurant/' . $restaurant['logo']);
        }
        $vendor = Vendor::findOrFail($restaurant?->vendor?->id);
        $restaurant?->delete();
        $vendor?->userinfo?->delete();
        $vendor?->delete();
        Toastr::success(translate('messages.restaurant') . ' ' . translate('messages.removed'));
        return back();
    }

    public function view($restaurant,Request $request, $tab = null, $sub_tab = 'cash')
    {
        $restaurant= Restaurant::find($restaurant);
        $wallet = $restaurant?->vendor?->wallet;
        if (!$wallet) {
            $wallet = new RestaurantWallet();
            $wallet->vendor_id = $restaurant?->vendor?->id;
            $wallet->total_earning = 0.0;
            $wallet->total_withdrawn = 0.0;
            $wallet->pending_withdraw = 0.0;
            $wallet->created_at = now();
            $wallet->updated_at = now();
            $wallet->save();
        }
        if ($tab == 'settings') {
            return view('admin-views.vendor.view.settings', compact('restaurant'));
        } else if ($tab == 'order') {
            return view('admin-views.vendor.view.order', compact('restaurant'));
        } else if ($tab == 'product') {
            return view('admin-views.vendor.view.product', compact('restaurant'));
        } else if ($tab == 'discount') {
            return view('admin-views.vendor.view.discount', compact('restaurant'));
        } else if ($tab == 'transaction') {
            return view('admin-views.vendor.view.transaction', compact('restaurant', 'sub_tab'));
        } else if ($tab == 'reviews') {
            return view('admin-views.vendor.view.review', compact('restaurant', 'sub_tab'));
        } else if ($tab == 'conversations') {
            $user = UserInfo::where(['vendor_id' => $restaurant?->vendor?->id])->first();
            if ($user) {
                $conversations = Conversation::with(['sender', 'receiver', 'last_message'])->WhereUser($user->id)
                    ->paginate(8);
            } else {
                $conversations = [];
            }
            return view('admin-views.vendor.view.conversations', compact('restaurant', 'sub_tab', 'conversations'));
        } elseif ($tab == 'subscriptions'){

            $id=$restaurant->id;
            if ($restaurant->restaurant_model == 'subscription' || $restaurant->restaurant_model == 'unsubscribed') {
                $rest_subscription= RestaurantSubscription::where('restaurant_id', $id)->with(['package'])->latest()->first();
                $package_id=  $rest_subscription?->package_id ?? 0 ;
                $total_bill=SubscriptionTransaction::where('restaurant_id', $id)->where('package_id', $package_id)->sum('paid_amount');
                $packages= SubscriptionPackage::where('status', 1)->get();
                return view('admin-views.vendor.view.subscriptions', compact('restaurant', 'rest_subscription','package_id','total_bill','packages'));
            } else{
                abort(404);
            }

        } elseif ($tab == 'subscriptions-transactions'){
            $filter = $request->query('filter', 'all');
            $transcations = SubscriptionTransaction::where('restaurant_id', $restaurant->id)
            ->when($filter == 'month', function ($query) {
                return $query->whereMonth('created_at', Carbon::now()->month);
            })
            ->when($filter == 'year', function ($query) {
                return $query->whereYear('created_at', Carbon::now()->year);
            })
            ->latest()->paginate(config('default_pagination'));
            $total = $transcations?->total();
            return view('admin-views.vendor.view.subs_transaction',[
            'transcations' => $transcations,
            'filter' => $filter,
            'total' => $total,
            'restaurant' => $restaurant,
            ]);
        }
        return view('admin-views.vendor.view.index', compact('restaurant','wallet'));
    }



    public function rest_transcation_search(Request $request)
    {
        $key = explode(' ', $request['search']);
        $transcations = SubscriptionTransaction::where('restaurant_id',$request->id)->where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('id', 'like', "%{$value}%")
                    ->orWhere('paid_amount', 'like', "%{$value}%")
                    ->orWhere('reference', 'like', "%{$value}%")
                    ->orWheredate('created_at', 'like', "%{$value}%");
            }
        })
            ->get();
        $total = $transcations?->count();
        return response()->json([
            'view' => view('admin-views.vendor.view.partials._rest_subs_transcation', compact('transcations','total'))->render(), 'total'=> $total
        ]);
    }
    public function trans_search_by_date(Request $request){
        $from=$request->start_date;
        $to= $request->end_date;
        $id= $request->id;
        $filter = 'all';
        $restaurant=Restaurant::findOrFail($id);
        $transcations=SubscriptionTransaction::where('restaurant_id', $restaurant->id)
        ->whereBetween('created_at', ["{$from}", "{$to} 23:59:59"])
        ->latest()->paginate(config('default_pagination'));
        $total = $transcations->total();
        return view('admin-views.vendor.view.subs_transaction',[
            'transcations' => $transcations,
            'filter' => $filter,
            'total' => $total,
            'restaurant' => $restaurant,
            'from' =>  $from,
            'to' =>  $to,
            ]);
    }

    public function view_tab(Restaurant $restaurant)
    {
        Toastr::error(translate('messages.unknown_tab'));
        return back();
    }

    public function list(Request $request)
    {
        $zone_id = $request->query('zone_id', 'all');
        $cuisine_id = $request->query('cuisine_id', 'all');
        $type = $request->query('type', 'all');
        $typ = $request->query('restaurant_model', '');
        $restaurants = Restaurant::when(is_numeric($zone_id), function ($query) use ($zone_id) {
                return $query->where('zone_id', $zone_id);
            })
            ->with('vendor')
            ->withSum('reviews' , 'rating')
            ->withCount('reviews')
            ->whereHas('vendor', function($q){
                $q->where('status',1);
            })
            ->cuisine($cuisine_id)
            ->type($type)->RestaurantModel($typ)->latest()->paginate(config('default_pagination'));
                $zone = is_numeric($zone_id) ? Zone::findOrFail($zone_id) : null;
        return view('admin-views.vendor.list', compact('restaurants', 'zone', 'type','typ','cuisine_id'));
    }

    public function pending(Request $request)
    {
        $key = explode(' ', $request['search']);
        $zone_id = $request->query('zone_id', 'all');
        $type = $request->query('type', 'all');
        $typ = $request->query('restaurant_model', '');
        $restaurants = Restaurant::when(is_numeric($zone_id), function ($query) use ($zone_id) {
                return $query->where('zone_id', $zone_id);
            })
            ->when(isset($key),function($query)use($key){
                $query->where(function($q)use($key){
                    foreach ($key as $value) {
                        $q->orWhere('name', 'like', "%{$value}%")
                        ->orWhere('email', 'like', "%{$value}%")
                        ->orWhere('phone', 'like', "%{$value}%");
                    }
                });
            })
            ->with('vendor')
            ->whereHas('vendor', function ($q) {
                $q->where('status', null);
            })
            ->type($type)->RestaurantModel($typ)->latest()->paginate(config('default_pagination'));
                $zone = is_numeric($zone_id) ? Zone::findOrFail($zone_id) : null;
        return view('admin-views.vendor.pending_list', compact('restaurants', 'zone', 'type','typ'));
    }
    public function denied(Request $request)
    {
        $key = explode(' ', $request['search']);
        $zone_id = $request->query('zone_id', 'all');
        $type = $request->query('type', 'all');
        $typ = $request->query('restaurant_model', '');
        $restaurants = Restaurant::when(is_numeric($zone_id), function ($query) use ($zone_id) {
                    return $query->where('zone_id', $zone_id);
                })
                ->when(isset($key),function($query)use($key){
                    $query->where(function($q)use($key){
                        foreach ($key as $value) {
                            $q->orWhere('name', 'like', "%{$value}%")
                            ->orWhere('email', 'like', "%{$value}%")
                            ->orWhere('phone', 'like', "%{$value}%");
                        }
                    });
                })
                ->with('vendor')
                ->whereHas('vendor', function ($q) {
                    $q->Where('status', 0);
                })
                ->type($type)->RestaurantModel($typ)->latest()->paginate(config('default_pagination'));
                $zone = is_numeric($zone_id) ? Zone::findOrFail($zone_id) : null;
        return view('admin-views.vendor.denied', compact('restaurants', 'zone', 'type','typ'));
    }

    public function search(Request $request)
    {
        $key = explode(' ', $request['search']);
        $restaurants = Restaurant::whereHas('vendor', function($q){
                $q->where('status',1);
            })
            ->where(function($query)use ($key){
                $query->orWhereHas('vendor', function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('f_name', 'like', "%{$value}%")
                            ->orWhere('l_name', 'like', "%{$value}%")
                            ->orWhere('email', 'like', "%{$value}%")
                            ->orWhere('phone', 'like', "%{$value}%");
                    }
                })
                ->where(function ($q) use ($key) {
                        foreach ($key as $value) {
                            $q->orWhere('name', 'like', "%{$value}%")
                                ->orWhere('email', 'like', "%{$value}%")
                                ->orWhere('phone', 'like', "%{$value}%");
                        }
                    });
            })
                ->withSum('reviews' , 'rating')
                ->withCount('reviews')
                ->get();
        $total = $restaurants?->count();
        return response()->json([
            'view' => view('admin-views.vendor.partials._table', compact('restaurants'))->render(), 'total' => $total
        ]);
    }

    public function get_restaurants(Request $request)
    {
        $zone_ids = isset($request->zone_ids) ? (count($request->zone_ids) > 0 ? $request->zone_ids : []) : 0;
        $data = Restaurant::

        when($zone_ids, function($query) use($zone_ids){
            $query->whereIn('restaurants.zone_id', $zone_ids);
        })

        ->where('restaurants.name', 'like', '%'.$request->q.'%')
        ->limit(8)->get()
        ->map(function ($restaurant) {
            return [
                'id' => $restaurant->id,
                'text' => $restaurant->name . ' (' . $restaurant->zone?->name . ')',
            ];
        });

        $data[]=(object)['id'=>'all', 'text'=>'All'];
        return response()->json($data);
    }

    public function status(Restaurant $restaurant, Request $request)
    {
        $restaurant->status = $request->status;
        $restaurant?->save();
        $vendor = $restaurant?->vendor;

        try {
            if ($request->status == 0) {
                $vendor->auth_token = null;
                if (isset($vendor->fcm_token)) {
                    $data = [
                        'title' => translate('messages.suspended'),
                        'description' => translate('messages.your_account_has_been_suspended'),
                        'order_id' => '',
                        'image' => '',
                        'type' => 'block'
                    ];
                    Helpers::send_push_notif_to_device($vendor->fcm_token, $data);
                    DB::table('user_notifications')->insert([
                        'data' => json_encode($data),
                        'vendor_id' => $vendor->id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }
        } catch (\Exception $e) {
            info($e->getMessage());
            Toastr::warning(translate('messages.push_notification_faild'));
        }

        Toastr::success(translate('messages.restaurant') . translate('messages.status_updated'));
        return back();
    }

    public function restaurant_status(Restaurant $restaurant, Request $request)
    {
        if ($request->menu == "schedule_order" && !Helpers::schedule_order()) {
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

        if ((($request->menu == "delivery" && $restaurant->take_away == 0) || ($request->menu == "take_away" && $restaurant->delivery == 0)) &&  $request->status == 0) {
            Toastr::warning(translate('messages.can_not_disable_both_take_away_and_delivery'));
            return back();
        }

        if ((($request->menu == "veg" && $restaurant->non_veg == 0) || ($request->menu == "non_veg" && $restaurant->veg == 0)) &&  $request->status == 0) {
            Toastr::warning(translate('messages.veg_non_veg_disable_warning'));
            return back();
        }
        if ($request->menu == "self_delivery_system" && $request->status == '0') {
            $restaurant['free_delivery'] = 0;
            $restaurant?->coupon()?->where('created_by','vendor')->where('coupon_type','free_delivery')?->delete();
        }
        $restaurant[$request->menu] = $request->status;
        $restaurant?->save();
        Toastr::success(translate('messages.restaurant') . translate('messages.settings_updated'));
        return back();
    }

    public function discountSetup(Restaurant $restaurant, Request $request)
    {
        $message = translate('messages.discount');
        $message .= $restaurant->discount ? translate('messages.updated_successfully') : translate('messages.added_successfully');
        $restaurant?->discount()?->updateOrinsert(
            [
                'restaurant_id' => $restaurant->id
            ],
            [
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'min_purchase' => $request->min_purchase != null ? $request->min_purchase : 0,
                'max_discount' => $request->max_discount != null ? $request->max_discount : 0,
                'discount' => $request->discount_type == 'amount' ? $request->discount : $request['discount'],
                'discount_type' => 'percent'
            ]
        );
        return response()->json(['message' => $message], 200);
    }

    public function updateRestaurantSettings(Restaurant $restaurant, Request $request)
    {

        if(isset($request->restaurant_model)){
            if($request->restaurant_model == 'subscription'){
                $restaurant->restaurant_model= 'unsubscribed';
                $restaurant->status=0;

            } elseif($request->restaurant_model == 'commission'){
                $restaurant->restaurant_model= 'commission';
            }
            if(isset($restaurant->restaurant_sub)){
                $restaurant->restaurant_sub->update([
                    'status'=>0,
                ]);
            }
            $restaurant->save();
            Toastr::success(translate('messages.restaurant') .' '.translate('messages.Business_Model_Updated'));
            return back();
            }

        $request->validate([
            'minimum_order' => 'required',
            // 'comission' => 'required',
            'tax' => 'required',
            'minimum_delivery_time' => 'required',
            'maximum_delivery_time' => 'required|gt:minimum_delivery_time',
            'delivery_time_type'=>'required',

        ]);

        if ($request->comission_status) {
            $restaurant->comission = $request->comission;
        } else {
            $restaurant->comission = null;
        }

        if ($request->delivery_time_type == 'min') {
            $minimum_delivery_time = (int) $request->input('minimum_delivery_time');
            if ($minimum_delivery_time < 10) {
                Toastr::error(translate('messages.restaurant') . translate('messages.minimum_delivery_time_should_be_more_than_10_min'));
                return back();
            }
        }


        $restaurant->minimum_order = $request->minimum_order;
        $restaurant->opening_time = $request->opening_time;
        $restaurant->closeing_time = $request->closeing_time;
        $restaurant->tax = $request->tax;
        $restaurant->delivery_time =$request->minimum_delivery_time .'-'. $request->maximum_delivery_time.'-'.$request->delivery_time_type;
        if ($request->menu == "veg") {
            $restaurant->veg = 1;
            $restaurant->non_veg = 0;
        } elseif ($request->menu == "non-veg") {
            $restaurant->veg = 0;
            $restaurant->non_veg = 1;
        } elseif ($request->menu == "both") {
            $restaurant->veg = 1;
            $restaurant->non_veg = 1;
        }
        $restaurant->save();
        Toastr::success(translate('messages.restaurant') . translate('messages.settings_updated'));
        return back();
    }

    public function update_application($id,$status)
    {
        $restaurant = Restaurant::findOrFail($id);
        $restaurant->vendor->status = $status;
        $restaurant?->vendor?->save();
        if ($status) $restaurant->status = 1;
        if($restaurant?->restaurant_sub_update_application && $restaurant?->restaurant_sub_trans?->payment_method == 'free_trial'){
            $free_trial_period_data = json_decode(BusinessSetting::where(['key' => 'free_trial_period'])->first()?->value,true);
            $free_trial_period=  $free_trial_period_data['data'] ??  0;
            $restaurant->restaurant_sub_update_application->update([
                'expiry_date'=> Carbon::now()->addDays($free_trial_period)->format('Y-m-d'),
                'status'=>1
            ]);
            $restaurant->restaurant_model= 'subscription';
        } elseif ($restaurant?->restaurant_sub_trans && $restaurant?->restaurant_sub_update_application && $restaurant?->restaurant_sub_trans?->payment_method != 'free_trial') {
            $add_days=$restaurant->restaurant_sub_trans->validity;
            $restaurant->restaurant_sub_update_application->update([
                'expiry_date'=> Carbon::now()->addDays($add_days)->format('Y-m-d'),
                'status'=>1
            ]);
            $restaurant->restaurant_model= 'subscription';
        }
        $restaurant?->save();
        try {
            if($status==1){
                $mail_status = Helpers::get_mail_status('approve_mail_status_restaurant');
                if ( config('mail.status') && $mail_status == '1') {
                    Mail::to( $restaurant?->vendor?->email)->send(new \App\Mail\VendorSelfRegistration('approved', $restaurant?->vendor?->f_name.' '.$restaurant?->vendor?->l_name));
                }
            }else{
                $mail_status = Helpers::get_mail_status('deny_mail_status_restaurant');
                if ( config('mail.status') && $mail_status == '1') {
                    Mail::to( $restaurant?->vendor?->email)->send(new \App\Mail\VendorSelfRegistration('denied', $restaurant?->vendor?->f_name.' '.$restaurant?->vendor?->l_name));
                }
            }
        } catch (\Exception $ex) {
            info($ex->getMessage());
        }
        Toastr::success(translate('messages.application_status_updated_successfully'));
        return back();
    }

    public function cleardiscount(Restaurant $restaurant)
    {
        $restaurant?->discount?->delete();
        Toastr::success(translate('messages.restaurant') . translate('messages.discount_cleared'));
        return back();
    }

    public function withdraw()
    {
        $all = session()->has('withdraw_status_filter') && session('withdraw_status_filter') == 'all' ? 1 : 0;
        $active = session()->has('withdraw_status_filter') && session('withdraw_status_filter') == 'approved' ? 1 : 0;
        $denied = session()->has('withdraw_status_filter') && session('withdraw_status_filter') == 'denied' ? 1 : 0;
        $pending = session()->has('withdraw_status_filter') && session('withdraw_status_filter') == 'pending' ? 1 : 0;

        $withdraw_req = WithdrawRequest::with(['vendor'])
            ->when($all, function ($query) {
                return $query;
            })
            ->when($active, function ($query) {
                return $query->where('approved', 1);
            })
            ->when($denied, function ($query) {
                return $query->where('approved', 2);
            })
            ->when($pending, function ($query) {
                return $query->where('approved', 0);
            })
            ->latest()
            ->paginate(config('default_pagination'));

        return view('admin-views.wallet.withdraw', compact('withdraw_req'));
    }

    public function withdraw_view($withdraw_id, $seller_id)
    {
        $wr = WithdrawRequest::with(['vendor','method:id,method_name'])->where(['id' => $withdraw_id])->first();
        return view('admin-views.wallet.withdraw-view', compact('wr'));
    }

    public function status_filter(Request $request)
    {
        session()->put('withdraw_status_filter', $request['withdraw_status_filter']);
        return response()->json(session('withdraw_status_filter'));
    }

    public function withdrawStatus(Request $request, $id)
    {
        $withdraw = WithdrawRequest::findOrFail($id);
        $withdraw->approved = $request->approved;
        $withdraw->transaction_note = $request['note'];
        if ($request->approved == 1) {
            RestaurantWallet::where('vendor_id', $withdraw->vendor_id)->increment('total_withdrawn', $withdraw->amount);
            RestaurantWallet::where('vendor_id', $withdraw->vendor_id)->decrement('pending_withdraw', $withdraw->amount);
            $withdraw->save();
            try
            {
                $mail_status = Helpers::get_mail_status('withdraw_approve_mail_status_restaurant');
                if(config('mail.status') && $mail_status == '1') {
                    Mail::to($withdraw->vendor->email)->send(new \App\Mail\WithdrawRequestMail('approved',$withdraw));
                }
            }
            catch(\Exception $e)
            {
                info($e->getMessage());
            }
            Toastr::success(translate('messages.seller_payment_approved'));
            return redirect()->route('admin.restaurant.withdraw_list');
        } else if ($request->approved == 2) {
            try
            {
                $mail_status = Helpers::get_mail_status('withdraw_deny_mail_status_restaurant');
                if(config('mail.status') && $mail_status == '1') {
                    Mail::to($withdraw->vendor->email)->send(new \App\Mail\WithdrawRequestMail('denied',$withdraw));
                }
            }
            catch(\Exception $e)
            {
                info($e->getMessage());
            }
            RestaurantWallet::where('vendor_id', $withdraw->vendor_id)->decrement('pending_withdraw', $withdraw->amount);
            $withdraw->save();
            Toastr::info(translate('messages.seller_payment_denied'));
            return redirect()->route('admin.restaurant.withdraw_list');
        } else {
            Toastr::error(translate('messages.not_found'));
            return back();
        }
    }

    public function get_addons(Request $request)
    {
        $cat = AddOn::withoutGlobalScope(RestaurantScope::class)->where(['restaurant_id' => $request->restaurant_id])->active()->get();
        $res = '';
        foreach ($cat as $row) {
            $res .= '<option value="' . $row->id . '"';
            if (count($request->data)) {
                $res .= in_array($row->id, $request->data) ? 'selected' : '';
            }
            $res .=  '>' . $row->name . '</option>';
        }
        return response()->json([
            'options' => $res,
        ]);
    }

    public function get_restaurant_data(Restaurant $restaurant)
    {
        return response()->json($restaurant);
    }

    public function restaurant_filter($id)
    {
        if ($id == 'all') {
            if (session()->has('restaurant_filter')) {
                session()->forget('restaurant_filter');
            }
        } else {
            session()->put('restaurant_filter', Restaurant::where('id', $id)->first(['id', 'name']));
        }
        return back();
    }

    public function get_account_data(Restaurant $restaurant)
    {
        $wallet = $restaurant?->vendor?->wallet;
        $cash_in_hand = 0;
        $balance = 0;

        if ($wallet) {
            $cash_in_hand = $wallet->collected_cash;
            $balance = $wallet->total_earning - $wallet->total_withdrawn - $wallet->pending_withdraw - $wallet->collected_cash;
        }
        return response()->json(['cash_in_hand' => $cash_in_hand, 'earning_balance' => $balance], 200);
    }

    public function bulk_import_index()
    {
        return view('admin-views.vendor.bulk-import');
    }

    public function bulk_import_data(Request $request)
    {
        $validator=  Validator::make($request->all(), [
            'products_file' => 'required|max:2048',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }
        try {
            $collections = (new FastExcel)->import($request->file('products_file'));
        } catch (\Exception $exception) {
            info(["line___{$exception->getLine()}",$exception->getMessage()]);
            Toastr::error(translate('messages.you_have_uploaded_a_wrong_format_file'));
            return back();
        }
        $duplicate_phones = $collections->duplicates('phone');
        $duplicate_emails = $collections->duplicates('email');

        if ($duplicate_emails->isNotEmpty()) {
            Toastr::error(translate('messages.duplicate_data_on_column', ['field' => translate('messages.email')]));
            return back();
        }

        if ($duplicate_phones->isNotEmpty()) {
            Toastr::error(translate('messages.duplicate_data_on_column', ['field' => translate('messages.phone')]));
            return back();
        }
        $vendors = [];
        $restaurants = [];
        if($request->button === 'import'){

            $email= $collections->pluck('email')->toArray();
            $phone= $collections->pluck('phone')->toArray();

            if(Restaurant::whereIn('email', $email)->orWhereIn('phone', $phone)->exists()
            ){
                Toastr::error(translate('messages.duplicate_email_or_phone_exists_at_the_database'));
                return back();
            }

            $vendor = Vendor::orderBy('id', 'desc')->first('id');
            $vendor_id = $vendor ? $vendor->id : 0;
            foreach ($collections as $key => $collection) {
                if ($collection['ownerFirstName'] === "" || $collection['restaurantName'] === "" || $collection['phone'] === ""
                || $collection['email'] === "" || $collection['latitude'] === "" || $collection['longitude'] === ""
                || $collection['zone_id'] === "" ||  $collection['DeliveryTime'] === ""  || $collection['RestaurantModel'] === ""  ) {
                    Toastr::error(translate('messages.please_fill_all_required_fields'));
                    return back();
                }
                if(isset($collection['DeliveryTime']) && explode("-", (string)$collection['DeliveryTime'])[0] >  explode("-", (string)$collection['DeliveryTime'])[1]){
                    Toastr::error(translate('messages.max_delivery_time_must_be_greater_than_min_delivery_time'));
                    return back();
                }
                if(isset($collection['Comission']) && ($collection['Comission'] < 0 ||  $collection['Comission'] > 100) ) {
                    Toastr::error(translate('messages.Comission_must_be_in_0_to_100'));
                    return back();
                }
                if(isset($collection['Tax']) && ($collection['Tax'] < 0 ||  $collection['Tax'] > 100 )) {
                    Toastr::error(translate('messages.Tax_must_be_in_0_to_100'));
                    return back();
                }
                if(isset($collection['latitude']) && ($collection['latitude'] < -90 ||  $collection['latitude'] > 90 )) {
                    Toastr::error(translate('messages.latitude_must_be_in_-90_to_90'));
                    return back();
                }
                if(isset($collection['longitude']) && ($collection['longitude'] < -180 ||  $collection['longitude'] > 180 )) {
                    Toastr::error(translate('messages.longitude_must_be_in_-180_to_180'));
                    return back();
                }
                if(isset($collection['MinimumDeliveryFee']) && ($collection['MinimumDeliveryFee'] < 0  )) {
                    Toastr::error(translate('messages.Enter_valid_Minimum_Delivery_Fee'));
                    return back();
                }
                if(isset($collection['MinimumOrderAmount']) && ($collection['MinimumOrderAmount'] < 0  )) {
                    Toastr::error(translate('messages.Enter_valid_Minimum_Order_Amount'));
                    return back();
                }
                if(isset($collection['PerKmDeliveryFee']) && ($collection['PerKmDeliveryFee'] < 0  )) {
                    Toastr::error(translate('messages.Enter_valid_Per_Km_Delivery_Fee'));
                    return back();
                }
                if(isset($collection['MaximumDeliveryFee']) && ($collection['MaximumDeliveryFee'] < 0  )  ) {
                    Toastr::error(translate('messages.Enter_valid_Maximum_Delivery_Fee'));
                    return back();
                }

                array_push($vendors, [
                    'id' => $vendor_id + $key + 1,
                    'f_name' => $collection['ownerFirstName'],
                    'l_name' => $collection['ownerLastName'],
                    'password' => bcrypt(12345678),
                    'phone' => $collection['phone'],
                    'email' => $collection['email'],
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                array_push($restaurants, [
                    'name' => $collection['restaurantName'],
                    'logo' => $collection['logo'] ?? null,
                    'cover_photo' => $collection['CoverPhoto'] ?? null,
                    'phone' => $collection['phone'],
                    'email' => $collection['email'],
                    'latitude' => $collection['latitude'],
                    'longitude' => $collection['longitude'],
                    'vendor_id' => $vendor_id + $key + 1,
                    'zone_id' => $collection['zone_id'],
                    'address' => $collection['Address'] ?? null,
                    'tax' => $collection['Tax'] ?? 0,
                    'minimum_order' => $collection['MinimumOrderAmount'] ?? 0,
                    'delivery_time' => $collection['DeliveryTime'] ?? '15-30',
                    'comission' => $collection['Comission'] ?? 'comission',
                    'minimum_shipping_charge' => $collection['MinimumDeliveryFee'] ?? 0,
                    'per_km_shipping_charge' => $collection['PerKmDeliveryFee'] ?? 0,
                    'maximum_shipping_charge' => $collection['MaximumDeliveryFee'] ?? 0,
                    'restaurant_model' =>  $collection['RestaurantModel'] == 'subscription' ? 'unsubscribed' : 'commission'  ,
                    'schedule_order' => $collection['ScheduleOrder'] == 'yes' ? 1 : 0,
                    'take_away' => $collection['TakeAway'] == 'yes' ? 1 : 0,
                    'free_delivery' => $collection['FreeDelivery']  == 'yes' ? 1 : 0,
                    'veg' => $collection['Veg']  == 'yes' ? 1 : 0,
                    'non_veg' => $collection['NonVeg']  == 'yes' ? 1 : 0,
                    'order_subscription_active' => $collection['OrderSubscription'] == 'yes' ? 1 : 0,

                    'delivery' => $collection['Delivery']  == 'yes' ? 1 : 0,
                    'status' => $collection['Status']  == 'active' ? 1 : 0,
                    'food_section' => $collection['FoodSection']  == 'active' ?1 : 0,
                    'reviews_section' => $collection['ReviewsSection']   == 'active' ?1 : 0,
                    'pos_system' => $collection['PosSystem']  == 'active' ?1 : 0,
                    'self_delivery_system' => $collection['SelfDeliverySystem']  == 'active' ?1 : 0,
                    'active' => $collection['RestaurantOpen']  == 'yes' ?1 : 0,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            $restaurant_ids[] = $vendor_id+$key+1;
            $data = array_map(function($id){
                return array_map(function($item)use($id){
                    return     ['restaurant_id'=>$id,'day'=>$item,'opening_time'=>'00:00:00','closing_time'=>'23:59:59'];
                },[0,1,2,3,4,5,6]);
            },$restaurant_ids);
            try {
                $chunkSize = 100;
                $chunk_restaurants= array_chunk($restaurants,$chunkSize);
                $chunk_vendors= array_chunk($vendors,$chunkSize);

                DB::beginTransaction();
                    foreach($chunk_restaurants as $key=> $chunk_restaurant){
                    DB::table('vendors')->insert($chunk_vendors[$key]);
                    DB::table('restaurants')->insert($chunk_restaurant);
                }

                DB::table('restaurant_schedule')->insert(array_merge(...$data));
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                info(["line___{$e->getLine()}",$e->getMessage()]);
                Toastr::error(translate('messages.failed_to_import_data'));
                return back();
            }

            Toastr::success(translate('messages.restaurant_imported_successfully', ['count' => count($restaurants)]));
            return back();
        }


        if($request->button === 'update'){

            $email= $collections->pluck('email')->toArray();
            $phone= $collections->pluck('phone')->toArray();
            if(Restaurant::whereIn('email', $email)->orWhereIn('phone', $phone)->doesntExist()
            ){
                Toastr::error(translate('messages.email_or_phone_doesnt_exist_at_the_database'));
                return back();
            }

            foreach ($collections as $key => $collection) {
                if (!isset($collection['id'])  || !isset($collection['ownerID'])  || $collection['ownerFirstName'] === "" || $collection['restaurantName'] === "" || $collection['phone'] === ""
                || $collection['email'] === "" || $collection['latitude'] === "" || $collection['longitude'] === ""
                || $collection['zone_id'] === "" ||  $collection['DeliveryTime'] === ""  || $collection['RestaurantModel'] === ""  ) {
                    Toastr::error(translate('messages.please_fill_all_required_fields'));
                    return back();
                }
                if(isset($collection['DeliveryTime']) && explode("-", (string)$collection['DeliveryTime'])[0] >  explode("-", (string)$collection['DeliveryTime'])[1]){
                    Toastr::error('messages.max_delivery_time_must_be_greater_than_min_delivery_time');
                    return back();
                }
                if(isset($collection['Comission']) && ($collection['Comission'] < 0 ||  $collection['Comission'] > 100) ) {
                    Toastr::error('messages.Comission_must_be_in_0_to_100');
                    return back();
                }
                if(isset($collection['Tax']) && ($collection['Tax'] < 0 ||  $collection['Tax'] > 100 )) {
                    Toastr::error('messages.Tax_must_be_in_0_to_100');
                    return back();
                }
                if(isset($collection['latitude']) && ($collection['latitude'] < -90 ||  $collection['latitude'] > 90 )) {
                    Toastr::error('messages.latitude_must_be_in_-90_to_90');
                    return back();
                }
                if(isset($collection['longitude']) && ($collection['longitude'] < -180 ||  $collection['longitude'] > 180 )) {
                    Toastr::error('messages.longitude_must_be_in_-180_to_180');
                    return back();
                }
                if(isset($collection['MinimumDeliveryFee']) && ($collection['MinimumDeliveryFee'] < 0  )) {
                    Toastr::error('messages.Enter_valid_Minimum_Delivery_Fee');
                    return back();
                }
                if(isset($collection['MinimumOrderAmount']) && ($collection['MinimumOrderAmount'] < 0  )) {
                    Toastr::error('messages.Enter_valid_Minimum_Order_Amount');
                    return back();
                }
                if(isset($collection['PerKmDeliveryFee']) && ($collection['PerKmDeliveryFee'] < 0  )) {
                    Toastr::error('messages.Enter_valid_Per_Km_Delivery_Fee');
                    return back();
                }
                if(isset($collection['MaximumDeliveryFee']) && ($collection['MaximumDeliveryFee'] < 0  )  ) {
                    Toastr::error('messages.Enter_valid_Maximum_Delivery_Fee');
                    return back();
                }

                array_push($vendors, [
                    'id'=>$collection['ownerID'],
                    'f_name' => $collection['ownerFirstName'],
                    'l_name' => $collection['ownerLastName'],
                    'phone' => $collection['phone'],
                    'email' => $collection['email'],
                    'status' => 1,
                    'password' => bcrypt(12345678),
                    'updated_at' => now()
                ]);
                array_push($restaurants, [
                    'id' => $collection['id'],
                    'name' => $collection['restaurantName'],
                    'logo' => $collection['logo'] ?? null,
                    'cover_photo' => $collection['CoverPhoto'] ?? null,
                    'phone' => $collection['phone'],
                    'email' => $collection['email'],
                    'latitude' => $collection['latitude'],
                    'longitude' => $collection['longitude'],
                    'vendor_id' => $collection['ownerID'],
                    'zone_id' => $collection['zone_id'],
                    'address' => $collection['Address'] ?? null,
                    'tax' => $collection['Tax'] ?? 0,
                    'minimum_order' => $collection['MinimumOrderAmount'] ?? 0,
                    'delivery_time' => $collection['DeliveryTime'] ?? '15-30',
                    'comission' => $collection['Comission'] ?? 'comission',
                    'minimum_shipping_charge' => $collection['MinimumDeliveryFee'] ?? 0,
                    'per_km_shipping_charge' => $collection['PerKmDeliveryFee'] ?? 0,
                    'maximum_shipping_charge' => $collection['MaximumDeliveryFee'] ?? 0,
                    'restaurant_model' =>  $collection['RestaurantModel']  == 'subscription' ? 'unsubscribed' : 'commission'  ,
                    'order_subscription_active' => $collection['OrderSubscription']  == 'yes' ? 1 : 0,
                    'schedule_order' => $collection['ScheduleOrder']  == 'yes' ? 1 : 0,
                    'take_away' => $collection['TakeAway']  == 'yes' ? 1 : 0,
                    'free_delivery' => $collection['FreeDelivery']   == 'yes' ? 1 : 0,
                    'veg' => $collection['Veg']   == 'yes' ? 1 : 0,
                    'non_veg' => $collection['NonVeg']   == 'yes' ? 1 : 0,
                    'delivery' => $collection['Delivery']   == 'yes' ? 1 : 0,
                    'status' => $collection['Status']   == 'active' ? 1 : 0,
                    'food_section' => $collection['FoodSection']   == 'active' ?1 : 0,
                    'reviews_section' => $collection['ReviewsSection']    == 'active' ?1 : 0,
                    'pos_system' => $collection['PosSystem']   == 'active' ?1 : 0,
                    'self_delivery_system' => $collection['SelfDeliverySystem']   == 'active' ?1 : 0,
                    'active' => $collection['RestaurantOpen']   == 'yes' ?1 : 0,
                    'updated_at' => now()
                ]);
            }
            try {

                $chunkSize = 100;
                $chunk_restaurants= array_chunk($restaurants,$chunkSize);
                $chunk_vendors= array_chunk($vendors,$chunkSize);
                DB::beginTransaction();
                foreach($chunk_restaurants as $key=> $chunk_restaurant){
                    DB::table('vendors')->upsert($chunk_vendors[$key],['id','email','phone','password'],['f_name','l_name']);
                    DB::table('restaurants')->upsert($chunk_restaurant,['id','email','phone','vendor_id',],['name','logo','cover_photo','latitude','longitude','address','zone_id','minimum_order','comission','tax','delivery_time','minimum_shipping_charge','per_km_shipping_charge','maximum_shipping_charge','schedule_order','status','self_delivery_system','veg','non_veg','free_delivery','take_away','delivery','reviews_section','pos_system','active','restaurant_model','food_section','order_subscription_active']);
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                info(["line___{$e->getLine()}",$e->getMessage()]);
                Toastr::error(translate('messages.failed_to_update_data'));
                return back();
            }
            Toastr::success(translate('messages.restaurant_update_successfully', ['count' => count($restaurants)]));
            return back();
        }

    }

    public function bulk_export_index()
    {
        return view('admin-views.vendor.bulk-export');
    }

    public function bulk_export_data(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'start_id' => 'required_if:type,id_wise',
            'end_id' => 'required_if:type,id_wise',
            'from_date' => 'required_if:type,date_wise',
            'to_date' => 'required_if:type,date_wise'
        ]);
        $vendors = Vendor::with('restaurants')->has('restaurants')
        ->when($request['type'] == 'date_wise', function ($query) use ($request) {
            $query->whereBetween('created_at', [$request['from_date'] . ' 00:00:00', $request['to_date'] . ' 23:59:59']);
        })
        ->when($request['type'] == 'id_wise', function ($query) use ($request) {
            $query->whereBetween('id', [$request['start_id'], $request['end_id']]);
        })
        ->when($request->type == 'all' ,function($q){
            $q->where('status',1);
        })
        ->get();

            // Export consumes only a few MB, even with 10M+ rows.
            return  (new FastExcel(RestaurantLogic::format_export_restaurants(Helpers::Export_generator($vendors))))->download('Restaurants.xlsx');
    }

    public function add_schedule(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'restaurant_id' => 'required',
        ], [
            'end_time.after' => translate('messages.End time must be after the start time')
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $temp = RestaurantSchedule::where('day', $request->day)->where('restaurant_id', $request->restaurant_id)
            ->where(function ($q) use ($request) {
                return $q->where(function ($query) use ($request) {
                    return $query->where('opening_time', '<=', $request->start_time)->where('closing_time', '>=', $request->start_time);
                })->orWhere(function ($query) use ($request) {
                    return $query->where('opening_time', '<=', $request->end_time)->where('closing_time', '>=', $request->end_time);
                });
            })
            ->first();

        if (isset($temp)) {
            return response()->json(['errors' => [
                ['code' => 'time', 'message' => translate('messages.schedule_overlapping_warning')]
            ]]);
        }

        $restaurant = Restaurant::find($request->restaurant_id);
        $restaurant_schedule = RestaurantSchedule::insert(['restaurant_id' => $request->restaurant_id, 'day' => $request->day, 'opening_time' => $request->start_time, 'closing_time' => $request->end_time]);

        return response()->json([
            'view' => view('admin-views.vendor.view.partials._schedule', compact('restaurant'))->render(),
        ]);
    }

    public function remove_schedule($restaurant_schedule)
    {
        $schedule = RestaurantSchedule::find($restaurant_schedule);
        if (!$schedule) {
            return response()->json([], 404);
        }
        $restaurant = $schedule?->restaurant;
        $schedule?->delete();
        return response()->json([
            'view' => view('admin-views.vendor.view.partials._schedule', compact('restaurant'))->render(),
        ]);
    }

    public function restaurants_export( Request $request,  $type)
    {
        $zone_id = $request->query('zone_id', 'all');
        $restaurant_model = $request->query('restaurant_model', '');
        $ty = $request->query('ty', 'all');
        $restaurants = Restaurant::when(is_numeric($zone_id), function ($query) use ($zone_id) {
            return $query->where('zone_id', $zone_id);
        })
        ->type($ty)->RestaurantModel($restaurant_model)->latest()->with('vendor', 'zone')->get();

        if ($type == 'csv') {
            return  (new FastExcel(Helpers::export_restaurants(Helpers::Export_generator($restaurants))))->download('Restaurants.csv');
        }
        return  (new FastExcel(Helpers::export_restaurants(Helpers::Export_generator($restaurants))))->download('Restaurants.xlsx');
    }

    public function withdraw_list_export(Request $request)
    {
        $withdraw_request = WithdrawRequest::latest()->get();
        if ($request->type == 'csv') {
            return (new FastExcel(Helpers::restaurant_withdraw_list_export($withdraw_request)))->download('WithdrawRequests.csv');
        }
        return (new FastExcel(Helpers::restaurant_withdraw_list_export($withdraw_request)))->download('WithdrawRequests.xlsx');
    }

    public function conversation_list(Request $request)
    {

        $user = UserInfo::where('vendor_id', $request->user_id)->first();
        $conversations = Conversation::WhereUser($user->id);
        if ($request->query('key') != null) {
            $key = explode(' ', $request->get('key'));
            $conversations = $conversations->where(function ($qu) use ($key) {
                $qu->whereHas('sender', function ($query) use ($key) {
                    foreach ($key as $value) {
                        $query->where('f_name', 'like', "%{$value}%")->orWhere('l_name', 'like', "%{$value}%")->orWhere('phone', 'like', "%{$value}%");
                    }
                })->orWhereHas('receiver', function ($query1) use ($key) {
                        foreach ($key as $value) {
                            $query1->where('f_name', 'like', "%{$value}%")->orWhere('l_name', 'like', "%{$value}%")->orWhere('phone', 'like', "%{$value}%");
                        }
                    });
            });
        }
        $conversations = $conversations->paginate(8);
        $view = view('admin-views.vendor.view.partials._conversation_list', compact('conversations'))->render();
        return response()->json(['html' => $view]);
    }

    public function conversation_view($conversation_id, $user_id)
    {
        $convs = Message::where(['conversation_id' => $conversation_id])->get();
        $conversation = Conversation::find($conversation_id);
        $receiver = UserInfo::find($conversation->receiver_id);
        $sender = UserInfo::find($conversation->sender_id);
        $user = UserInfo::find($user_id);
        return response()->json([
            'view' => view('admin-views.vendor.view.partials._conversations', compact('convs', 'user', 'receiver'))->render()
        ]);
    }

    public function cash_transaction_export(Request $request)
    {
        $transaction = AccountTransaction::where('from_type', 'restaurant')->where('from_id', $request->restaurant)->get();
        if ($request->type == 'csv') {
            return (new FastExcel($transaction))->download('CashTransaction.csv');
        }
        return (new FastExcel($transaction))->download('CashTransaction.xlsx');
    }

    public function digital_transaction_export(Request $request)
    {
        $transaction = OrderTransaction::where('vendor_id', $request->restaurant)->latest()->get();
        if ($request->type == 'csv') {
            return (new FastExcel($transaction))->download('AdminOrderTransaction.csv');
        }
        return (new FastExcel($transaction))->download('AdminOrderTransaction.xlsx');
    }

    public function withdraw_transaction_export(Request $request)
    {
        $transaction = WithdrawRequest::where('vendor_id', $request->restaurant)->get();
        if ($request->type == 'csv') {
            return (new FastExcel($transaction))->download('WithdrawTransaction.csv');
        }
        return (new FastExcel($transaction))->download('WithdrawTransaction.xlsx');
    }

    public function withdraw_search(Request $request)
    {
        $key = explode(' ', $request['search']);
        $withdraw_req = WithdrawRequest::whereHas('vendor', function ($query) use ($key) {
            $query->whereHas('restaurants', function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->where('name', 'like', "%{$value}%");
                }
            });
        })->get();

        return response()->json([
            'view' => view('admin-views.wallet.partials._table', compact('withdraw_req'))->render(),
            'total' => $withdraw_req?->count()
        ]);
    }
}
