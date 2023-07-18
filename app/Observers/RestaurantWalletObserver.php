<?php

namespace App\Observers;

use App\CentralLogics\Helpers;
use App\Models\RestaurantWallet;


class RestaurantWalletObserver
{
    /**
     * Handle the RestaurantWallet "created" event.
     *
     * @param  \App\Models\RestaurantWallet  $restaurantWallet
     * @return void
     */
    public function created(RestaurantWallet $restaurantWallet)
    {
        Helpers::create_all_logs($restaurantWallet,'created','RestaurantWallet');
    }

    /**
     * Handle the RestaurantWallet "updated" event.
     *
     * @param  \App\Models\RestaurantWallet  $restaurantWallet
     * @return void
     */
    public function updated(RestaurantWallet $restaurantWallet)
    {
        Helpers::create_all_logs($restaurantWallet,'updated','RestaurantWallet');
    }

    /**
     * Handle the RestaurantWallet "deleted" event.
     *
     * @param  \App\Models\RestaurantWallet  $restaurantWallet
     * @return void
     */
    public function deleted(RestaurantWallet $restaurantWallet)
    {
        Helpers::create_all_logs($restaurantWallet,'deleted','RestaurantWallet');
    }

    /**
     * Handle the RestaurantWallet "restored" event.
     *
     * @param  \App\Models\RestaurantWallet  $restaurantWallet
     * @return void
     */
    public function restored(RestaurantWallet $restaurantWallet)
    {
        //
    }

    /**
     * Handle the RestaurantWallet "force deleted" event.
     *
     * @param  \App\Models\RestaurantWallet  $restaurantWallet
     * @return void
     */
    public function forceDeleted(RestaurantWallet $restaurantWallet)
    {
        //
    }
}
