<?php

namespace App\Observers;

use App\CentralLogics\Helpers;
use App\Models\RestaurantSubscription;


class RestaurantSubscriptionObserver
{
    /**
     * Handle the RestaurantSubscription "created" event.
     *
     * @param  \App\Models\RestaurantSubscription  $restaurantSubscription
     * @return void
     */
    public function created(RestaurantSubscription $restaurantSubscription)
    {
        Helpers::create_all_logs($restaurantSubscription,'created','RestaurantSubscription');
    }

    /**
     * Handle the RestaurantSubscription "updated" event.
     *
     * @param  \App\Models\RestaurantSubscription  $restaurantSubscription
     * @return void
     */
    public function updated(RestaurantSubscription $restaurantSubscription)
    {
        Helpers::create_all_logs($restaurantSubscription,'updated','RestaurantSubscription');
    }

    /**
     * Handle the RestaurantSubscription "deleted" event.
     *
     * @param  \App\Models\RestaurantSubscription  $restaurantSubscription
     * @return void
     */
    public function deleted(RestaurantSubscription $restaurantSubscription)
    {
        Helpers::create_all_logs($restaurantSubscription,'deleted','RestaurantSubscription');
    }

    /**
     * Handle the RestaurantSubscription "restored" event.
     *
     * @param  \App\Models\RestaurantSubscription  $restaurantSubscription
     * @return void
     */
    public function restored(RestaurantSubscription $restaurantSubscription)
    {
        //
    }

    /**
     * Handle the RestaurantSubscription "force deleted" event.
     *
     * @param  \App\Models\RestaurantSubscription  $restaurantSubscription
     * @return void
     */
    public function forceDeleted(RestaurantSubscription $restaurantSubscription)
    {
        //
    }
}
