<?php

namespace App\Observers;

use App\Models\AddOn;
use App\CentralLogics\Helpers;

class AddOnObserver
{
    /**
     * Handle the AddOn "created" event.
     *
     * @param  \App\Models\AddOn  $addOn
     * @return void
     */
    public function created(AddOn $addOn)
    {
        Helpers::create_all_logs($addOn,'created','AddOn');

    }

    /**
     * Handle the AddOn "updated" event.
     *
     * @param  \App\Models\AddOn  $addOn
     * @return void
     */
    public function updated(AddOn $addOn)
    {
        Helpers::create_all_logs($addOn,'updated','AddOn');

    }

    /**
     * Handle the AddOn "deleted" event.
     *
     * @param  \App\Models\AddOn  $addOn
     * @return void
     */
    public function deleted(AddOn $addOn)
    {
        Helpers::create_all_logs($addOn,'deleted','AddOn');

    }

    /**
     * Handle the AddOn "restored" event.
     *
     * @param  \App\Models\AddOn  $addOn
     * @return void
     */
    public function restored(AddOn $addOn)
    {
        //
    }

    /**
     * Handle the AddOn "force deleted" event.
     *
     * @param  \App\Models\AddOn  $addOn
     * @return void
     */
    public function forceDeleted(AddOn $addOn)
    {
        //
    }
}
