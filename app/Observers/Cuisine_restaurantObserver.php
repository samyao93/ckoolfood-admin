<?php

namespace App\Observers;

use App\Models\Cuisine_restaurant;
use App\CentralLogics\Helpers;

class Cuisine_restaurantObserver
{
    /**
     * Handle the Cuisine_restaurant "created" event.
     *
     * @param  \App\Models\Cuisine_restaurant  $cuisine_restaurant
     * @return void
     */
    public function created(Cuisine_restaurant $cuisine_restaurant)
    {
        Helpers::create_all_logs($cuisine_restaurant,'created','Cuisine_restaurant');
    }

    /**
     * Handle the Cuisine_restaurant "updated" event.
     *
     * @param  \App\Models\Cuisine_restaurant  $cuisine_restaurant
     * @return void
     */
    public function updated(Cuisine_restaurant $cuisine_restaurant)
    {
        Helpers::create_all_logs($cuisine_restaurant,'updated','Cuisine_restaurant');
    }

    /**
     * Handle the Cuisine_restaurant "deleted" event.
     *
     * @param  \App\Models\Cuisine_restaurant  $cuisine_restaurant
     * @return void
     */
    public function deleted(Cuisine_restaurant $cuisine_restaurant)
    {
        Helpers::create_all_logs($cuisine_restaurant,'deleted','Cuisine_restaurant');
    }

    /**
     * Handle the Cuisine_restaurant "restored" event.
     *
     * @param  \App\Models\Cuisine_restaurant  $cuisine_restaurant
     * @return void
     */
    public function restored(Cuisine_restaurant $cuisine_restaurant)
    {
        //
    }

    /**
     * Handle the Cuisine_restaurant "force deleted" event.
     *
     * @param  \App\Models\Cuisine_restaurant  $cuisine_restaurant
     * @return void
     */
    public function forceDeleted(Cuisine_restaurant $cuisine_restaurant)
    {
        //
    }
}
