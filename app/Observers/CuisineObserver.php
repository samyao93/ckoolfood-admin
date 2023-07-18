<?php

namespace App\Observers;

use App\Models\Cuisine;
use App\CentralLogics\Helpers;

class CuisineObserver
{
    /**
     * Handle the Cuisine "created" event.
     *
     * @param  \App\Models\Cuisine  $cuisine
     * @return void
     */
    public function created(Cuisine $cuisine)
    {
        Helpers::create_all_logs($cuisine,'created','Cuisine');
    }

    /**
     * Handle the Cuisine "updated" event.
     *
     * @param  \App\Models\Cuisine  $cuisine
     * @return void
     */
    public function updated(Cuisine $cuisine)
    {
        Helpers::create_all_logs($cuisine,'updated','Cuisine');
    }

    /**
     * Handle the Cuisine "deleted" event.
     *
     * @param  \App\Models\Cuisine  $cuisine
     * @return void
     */
    public function deleted(Cuisine $cuisine)
    {
        Helpers::create_all_logs($cuisine,'deleted','Cuisine');
    }

    /**
     * Handle the Cuisine "restored" event.
     *
     * @param  \App\Models\Cuisine  $cuisine
     * @return void
     */
    public function restored(Cuisine $cuisine)
    {
        //
    }

    /**
     * Handle the Cuisine "force deleted" event.
     *
     * @param  \App\Models\Cuisine  $cuisine
     * @return void
     */
    public function forceDeleted(Cuisine $cuisine)
    {
        //
    }
}
