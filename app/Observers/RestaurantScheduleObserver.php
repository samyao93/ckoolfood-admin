<?php

namespace App\Observers;

use App\CentralLogics\Helpers;
use App\Models\RestaurantSchedule;


class RestaurantScheduleObserver
{
    /**
     * Handle the RestaurantSchedule "created" event.
     *
     * @param  \App\Models\RestaurantSchedule  $restaurantSchedule
     * @return void
     */
    public function created(RestaurantSchedule $restaurantSchedule)
    {
        Helpers::create_all_logs($restaurantSchedule,'created','RestaurantSchedule');
    }

    /**
     * Handle the RestaurantSchedule "updated" event.
     *
     * @param  \App\Models\RestaurantSchedule  $restaurantSchedule
     * @return void
     */
    public function updated(RestaurantSchedule $restaurantSchedule)
    {
        Helpers::create_all_logs($restaurantSchedule,'updated','RestaurantSchedule');
    }

    /**
     * Handle the RestaurantSchedule "deleted" event.
     *
     * @param  \App\Models\RestaurantSchedule  $restaurantSchedule
     * @return void
     */
    public function deleted(RestaurantSchedule $restaurantSchedule)
    {
        Helpers::create_all_logs($restaurantSchedule,'deleted','RestaurantSchedule');
    }

    /**
     * Handle the RestaurantSchedule "restored" event.
     *
     * @param  \App\Models\RestaurantSchedule  $restaurantSchedule
     * @return void
     */
    public function restored(RestaurantSchedule $restaurantSchedule)
    {
        //
    }

    /**
     * Handle the RestaurantSchedule "force deleted" event.
     *
     * @param  \App\Models\RestaurantSchedule  $restaurantSchedule
     * @return void
     */
    public function forceDeleted(RestaurantSchedule $restaurantSchedule)
    {
        //
    }
}
