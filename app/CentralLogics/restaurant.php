<?php

namespace App\CentralLogics;

use App\Models\Restaurant;
use App\Models\OrderTransaction;
use Illuminate\Support\Facades\DB;

class RestaurantLogic
{
    public static function get_restaurants($zone_id, $filter, $limit = 10, $offset = 1, $type='all',$name= null,$longitude=0,$latitude=0,$cuisine='all', $veg = null ,$non_veg = null ,$discount = null,$top_rated = null)
    {
        $paginator = Restaurant::
        withOpen($longitude,$latitude)
        ->with(['discount'=>function($q){
            return $q->validate();
        }])->whereIn('zone_id', $zone_id)
        ->when($filter=='delivery', function($q){
            return $q->delivery();
        })
        ->when($filter=='take_away', function($q){
            return $q->takeaway();
        })
        ->when($name, function($q)use($name){
            $key = explode(' ', $name);
            $q->where(function($q)use($key){
                foreach ($key as $value){
                    $q->orWhere('name', 'like', '%'.$value.'%');
                }
                return $q;
            });
        })

        ->when(isset($veg)   && $veg == 1  , function($q) {
            $q->where('veg',1);
        })
        ->when(isset($non_veg) && $non_veg == 1   , function($q) {
            $q->where('non_veg',1);
        })
        ->when(isset($discount)  && $discount == 1  , function($q) {
            $q->whereHas('discount',function($query){
                return $query->validate();
            });
        })
        ->when(isset($top_rated) && $top_rated == 1 , function($query){
            $query->whereHas('reviews',function($q){
                $q->selectRaw('avg(reviews.rating) as avg_r')
                    ->having('avg_r', '>', 3)->groupby('reviews.id');
            });
        })
        ->Active()
        ->type($type)
        ->cuisine($cuisine)

        ->when($filter=='latest', function($q){
            return $q->latest();
        })
        ->when($filter=='popular', function($q){
            return $q->withCount('orders')
            ->orderBy('open', 'desc')
            ->orderBy('orders_count', 'desc');
        })
        ->when($filter !='popular' || $filter !='latest', function($q){
            return $q->withCount('orders')
            ->orderBy('open', 'desc')
            ->orderBy('distance');
        })

        ->paginate($limit, ['*'], 'page', $offset);
        return [
            'filter_data'=> $filter ?? null,
            'total_size' => $paginator->total(),
            'limit' => $limit,
            'offset' => $offset,
            'restaurants' => $paginator->items()
        ];
    }

    public static function get_latest_restaurants($zone_id, $limit = 10, $offset = 1, $type='all',$longitude=0,$latitude=0,
    $veg = null ,$non_veg = null ,$discount = null,$top_rated = null)
    {
        $paginator = Restaurant::withOpen($longitude,$latitude)
        ->with(['discount'=>function($q){
            return $q->validate();
        }])->whereIn('zone_id', $zone_id)

        ->when(isset($veg)   && $veg == 1  , function($q) {
            $q->where('veg',1);
        })
        ->when(isset($non_veg) && $non_veg == 1   , function($q) {
            $q->where('non_veg',1);
        })
        ->when(isset($discount)  && $discount == 1  , function($q) {
            $q->whereHas('discount',function($query){
                return $query->validate();
            });
        })
        ->when(isset($top_rated) && $top_rated == 1 , function($query){
            $query->whereHas('reviews',function($q){
                $q->selectRaw('avg(reviews.rating) as avg_r')
                    ->having('avg_r', '>', 3);
            });
        })
        ->Active()
        ->type($type)
        ->latest()
        ->limit(50)
        ->get();
        return [
            'total_size' => $paginator->count(),
            'limit' => $limit,
            'offset' => $offset,
            'restaurants' => $paginator
        ];
    }

    public static function get_popular_restaurants($zone_id, $limit = 10, $offset = 1, $type='all',$longitude=0,$latitude=0 ,$veg = null ,$non_veg = null ,$discount = null,$top_rated = null)
    {
        $paginator = Restaurant::withOpen($longitude,$latitude)
        ->with(['reviews','discount'=>function($q){
            return $q->validate();
        }])->whereIn('zone_id', $zone_id)

        ->when(isset($veg) && $veg == 1  , function($q) {
            $q->where('veg',1);
        })
        ->when(isset($non_veg) && $non_veg == 1   , function($q) {
            $q->where('non_veg',1);
        })
        ->when(isset($discount)  && $discount == 1  , function($q) {
            $q->whereHas('discount',function($query){
                return $query->validate();
            });
        })
        ->when(isset($top_rated) && $top_rated == 1 , function($query){
            $query->whereHas('reviews',function($q){
                $q->selectRaw('avg(reviews.rating) as avg_r')
                    ->having('avg_r', '>', 3);
            });
        })

        ->Active()
        ->type($type)
        ->withCount('orders')
        ->orderBy('open', 'desc')
        ->orderBy('orders_count', 'desc')
        ->limit(50)
        ->get();
        return [
            'total_size' => $paginator->count(),
            'limit' => $limit,
            'offset' => $offset,
            'restaurants' => $paginator
        ];
    }

    public static function get_restaurant_details($restaurant_id)
    {
        return Restaurant::with(['discount'=>function($q){
            return $q->validate();
        }, 'campaigns', 'schedules','restaurant_sub'])->active()
        ->when(is_numeric($restaurant_id),function ($qurey) use($restaurant_id){
            $qurey-> where('id', $restaurant_id);
        })
        ->when(!is_numeric($restaurant_id),function ($qurey) use($restaurant_id){
            $qurey-> where('slug', $restaurant_id);
        })
        ->first();
    }

    public static function calculate_restaurant_rating($ratings)
    {
        $total_submit = $ratings[0]+$ratings[1]+$ratings[2]+$ratings[3]+$ratings[4];
        $rating = ($ratings[0]*5+$ratings[1]*4+$ratings[2]*3+$ratings[3]*2+$ratings[4])/($total_submit?$total_submit:1);
        return ['rating'=>$rating, 'total'=>$total_submit];
    }
    public static function calculate_positive_rating($ratings)
    {
        $total_submit = $ratings[0]+$ratings[1]+$ratings[2]+$ratings[3]+$ratings[4];
        $rating = (($ratings[0]+$ratings[1]) / ($total_submit?$total_submit:1)) *100;
        return ['rating'=>$rating, 'total'=>$total_submit];
    }

    public static function update_restaurant_rating($ratings, $product_rating)
    {
        $restaurant_ratings = [1=>0 , 2=>0, 3=>0, 4=>0, 5=>0];
        if($ratings)
        {
            $restaurant_ratings[1] = $ratings[4];
            $restaurant_ratings[2] = $ratings[3];
            $restaurant_ratings[3] = $ratings[2];
            $restaurant_ratings[4] = $ratings[1];
            $restaurant_ratings[5] = $ratings[0];
            $restaurant_ratings[$product_rating] = $ratings[5-$product_rating] + 1;
        }
        else
        {
            $restaurant_ratings[$product_rating] = 1;
        }
        return json_encode($restaurant_ratings);
    }

    public static function search_restaurants($name, $zone_id, $category_id= null,$limit = 10, $offset = 1, $type='all',$longitude=0,$latitude=0)
    {
        $key = explode(' ', $name);
        $paginator = Restaurant::withOpen($longitude,$latitude)->with(['discount'=>function($q){
            return $q->validate();
        }])->whereIn('zone_id', $zone_id)->weekday()
        ->where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('name', 'like', "%{$value}%")
                ->orWhereHas('cuisine',function($query) use($key){
                    $query->where(function($q2)use($key){
                        foreach ($key as $value) {
                            $q2->where('name', 'like', "%{$value}%");
                        };
                    });
                });
            }
        })
        ->when($category_id, function($query)use($category_id){
            $query->whereHas('foods.category', function($q)use($category_id){
                return $q->whereId($category_id)->orWhere('parent_id', $category_id);
            });
        })
        ->active()->type($type)
        ->orderBy('open', 'desc')
        ->orderBy('distance')
        ->paginate($limit, ['*'], 'page', $offset);

        return [
            'total_size' => $paginator->total(),
            'limit' => $limit,
            'offset' => $offset,
            'restaurants' => $paginator->items()
        ];
    }

    public static function get_overall_rating($reviews)
    {
        $totalRating = count($reviews);
        $rating = 0;
        foreach ($reviews as $key => $review) {
            $rating += $review->rating;
        }
        if ($totalRating == 0) {
            $overallRating = 0;
        } else {
            $overallRating = number_format($rating / $totalRating, 2);
        }

        return [$overallRating, $totalRating];
    }

    public static function get_earning_data($vendor_id)
    {
        $monthly_earning = OrderTransaction::whereMonth('created_at', date('m'))->NotRefunded()->where('vendor_id', $vendor_id)->sum('restaurant_amount');
        $weekly_earning = OrderTransaction::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->NotRefunded()->where('vendor_id', $vendor_id)->sum('restaurant_amount');
        $daily_earning = OrderTransaction::whereDate('created_at', now())->NotRefunded()->where('vendor_id', $vendor_id)->sum('restaurant_amount');

        return['monthely_earning'=>(float)$monthly_earning, 'weekly_earning'=>(float)$weekly_earning, 'daily_earning'=>(float)$daily_earning];
    }

    public static function format_export_restaurants($restaurants)
    {
        $storage = [];
        foreach($restaurants as $item)
        {
            $storage[] = [
                'id'=>$item->restaurants[0]->id,
                'ownerID'=>$item->id,
                'ownerFirstName'=>$item->f_name,
                'ownerLastName'=>$item->l_name,
                'restaurantName'=>$item->restaurants[0]->name,
                'CoverPhoto'=>$item->restaurants[0]->cover_photo,
                'logo'=>$item->restaurants[0]->logo,
                'phone'=>$item->phone,
                'email'=>$item->email,
                'latitude'=>$item->restaurants[0]->latitude,
                'longitude'=>$item->restaurants[0]->longitude,
                'zone_id'=>$item->restaurants[0]->zone_id,
                'Address'=>$item->restaurants[0]->address ?? null,
                'Slug'=> $item->restaurants[0]->slug  ?? null,
                'MinimumOrderAmount'=>$item->restaurants[0]->minimum_order,
                'Comission'=>$item->restaurants[0]->comission ?? 0,
                'Tax'=>$item->restaurants[0]->tax ?? 0,

                'DeliveryTime'=>$item->restaurants[0]->delivery_time ?? '20-30',
                'MinimumDeliveryFee'=>$item->restaurants[0]->minimum_shipping_charge ?? 0,
                'PerKmDeliveryFee'=>$item->restaurants[0]->per_km_shipping_charge ?? 0,
                'MaximumDeliveryFee'=>$item->restaurants[0]->maximum_shipping_charge ?? 0,
                // 'order_count'=>$item->restaurants[0]->order_count,
                // 'total_order'=>$item->restaurants[0]->total_order,
                'RestaurantModel'=>$item->restaurants[0]->restaurant_model,
                'ScheduleOrder'=> $item->restaurants[0]->schedule_order == 1 ? 'yes' : 'no',
                'FreeDelivery'=> $item->restaurants[0]->free_delivery == 1 ? 'yes' : 'no',
                'TakeAway'=> $item->restaurants[0]->take_away == 1 ? 'yes' : 'no',
                'Delivery'=> $item->restaurants[0]->delivery == 1 ? 'yes' : 'no',
                'Veg'=> $item->restaurants[0]->veg == 1 ? 'yes' : 'no',
                'NonVeg'=> $item->restaurants[0]->non_veg == 1 ? 'yes' : 'no',
                'OrderSubscription'=> $item->restaurants[0]->order_subscription_active == 1 ? 'yes' : 'no',
                'Status'=> $item->restaurants[0]->status == 1 ? 'active' : 'inactive',
                'FoodSection'=> $item->restaurants[0]->food_section == 1 ? 'active' : 'inactive',
                'ReviewsSection'=> $item->restaurants[0]->reviews_section == 1 ? 'active' : 'inactive',
                'SelfDeliverySystem'=> $item->restaurants[0]->self_delivery_system == 1 ? 'active' : 'inactive',
                'PosSystem'=> $item->restaurants[0]->pos_system == 1 ? 'active' : 'inactive',
                'RestaurantOpen'=> $item->restaurants[0]->active == 1 ? 'yes' : 'no',
                // 'gst'=>$item->restaurants[0]->gst ?? null,
            ];
        }

        return $storage;
    }
    public static function format_restaurant_report_export_data($restaurants)
    {
        $storage = [];
        foreach($restaurants as $key => $restaurant)
        {
            if($restaurant->count()<1)
            {
                break;
            }
            if ($restaurant->reviews_count){
                $reviews_count = $restaurant->reviews_count;
            }
            else{
                $reviews_count = 1;
            }

            $restaurant_rating = round($restaurant->reviews_sum_rating /$reviews_count,1);
            $storage[] = [
                '#'=>$key+1,
                translate('messages.restaurant') =>$restaurant->name,
                translate('messages.total_food') =>$restaurant->foods_count ?? 0,
                translate('messages.total_order') =>$restaurant->without_refund_total_orders_count ?? 0,
                translate('messages.total_order').translate('messages.amount') =>$restaurant->transaction_sum_order_amount ?? 0,
                translate('messages.total_discount_given') =>$restaurant->transaction_sum_restaurant_expense ?? 0,
                translate('messages.total_admin_commission') =>$restaurant->transaction_sum_admin_commission ?? 0,
                translate('messages.total_vat_tax') =>$restaurant->transaction_sum_tax ?? 0,
                translate('messages.average_ratings') =>$restaurant_rating,
            ];
        }
        return $storage;
    }

    public static function recently_viewed_restaurants_data($zone_id, $limit = 10, $offset = 1, $type='all',$longitude=0,$latitude=0)
    {
        $user_id = null;
        if(auth('api')->user() !== null){
            $user_id =auth('api')->user()->id;
        }

        $paginator = Restaurant::whereHas('users',function ($query) use($user_id){
            $query->where('user_id',$user_id);
        })
        ->withOpen($longitude,$latitude)
        ->with(['discount'=>function($q){
            return $q->validate();
        }])->whereIn('zone_id', $zone_id)
        ->Active()
        ->type($type)
        ->withCount('orders')
        ->orderBy('orders_count', 'desc')
        ->orderBy('open', 'desc')
        ->limit(50)
        ->get();
        return [
            'total_size' => $paginator->count(),
            'limit' => $limit,
            'offset' => $offset,
            'restaurants' => $paginator
        ];
    }
}
