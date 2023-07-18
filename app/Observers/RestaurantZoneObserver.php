<?php

namespace App\Observers;

use App\CentralLogics\Helpers;
use App\Models\RestaurantZone;


class RestaurantZoneObserver
{
    /**
     * Handle the RestaurantZone "created" event.
     *
     * @param  \App\Models\RestaurantZone  $restaurantZone
     * @return void
     */
    public function created(RestaurantZone $restaurantZone)
    {
        Helpers::create_all_logs($restaurantZone,'created','RestaurantZone');
    }

    /**
     * Handle the RestaurantZone "updated" event.
     *
     * @param  \App\Models\RestaurantZone  $restaurantZone
     * @return void
     */
    public function updated(RestaurantZone $restaurantZone)
    {
        Helpers::create_all_logs($restaurantZone,'updated','RestaurantZone');
    }

    /**
     * Handle the RestaurantZone "deleted" event.
     *
     * @param  \App\Models\RestaurantZone  $restaurantZone
     * @return void
     */
    public function deleted(RestaurantZone $restaurantZone)
    {
        Helpers::create_all_logs($restaurantZone,'deleted','RestaurantZone');
    }

    /**
     * Handle the RestaurantZone "restored" event.
     *
     * @param  \App\Models\RestaurantZone  $restaurantZone
     * @return void
     */
    public function restored(RestaurantZone $restaurantZone)
    {
        //
    }

    /**
     * Handle the RestaurantZone "force deleted" event.
     *
     * @param  \App\Models\RestaurantZone  $restaurantZone
     * @return void
     */
    public function forceDeleted(RestaurantZone $restaurantZone)
    {
        //
    }
}
