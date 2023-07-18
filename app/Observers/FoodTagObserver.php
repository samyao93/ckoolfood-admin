<?php

namespace App\Observers;

use App\CentralLogics\Helpers;
use App\Models\FoodTag;


class FoodTagObserver
{
    /**
     * Handle the FoodTag "created" event.
     *
     * @param  \App\Models\FoodTag  $foodTag
     * @return void
     */
    public function created(FoodTag $foodTag)
    {
        Helpers::create_all_logs($foodTag,'created','FoodTag');
    }

    /**
     * Handle the FoodTag "updated" event.
     *
     * @param  \App\Models\FoodTag  $foodTag
     * @return void
     */
    public function updated(FoodTag $foodTag)
    {
        Helpers::create_all_logs($foodTag,'updated','FoodTag');
    }

    /**
     * Handle the FoodTag "deleted" event.
     *
     * @param  \App\Models\FoodTag  $foodTag
     * @return void
     */
    public function deleted(FoodTag $foodTag)
    {
        Helpers::create_all_logs($foodTag,'deleted','FoodTag');
    }

    /**
     * Handle the FoodTag "restored" event.
     *
     * @param  \App\Models\FoodTag  $foodTag
     * @return void
     */
    public function restored(FoodTag $foodTag)
    {
        //
    }

    /**
     * Handle the FoodTag "force deleted" event.
     *
     * @param  \App\Models\FoodTag  $foodTag
     * @return void
     */
    public function forceDeleted(FoodTag $foodTag)
    {
        //
    }
}
