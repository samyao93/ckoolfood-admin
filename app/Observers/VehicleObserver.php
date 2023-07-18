<?php

namespace App\Observers;

use App\CentralLogics\Helpers;
use App\Models\Vehicle;


class VehicleObserver
{
    /**
     * Handle the Vehicle "created" event.
     *
     * @param  \App\Models\Vehicle  $vehicle
     * @return void
     */
    public function created(Vehicle $vehicle)
    {
        Helpers::create_all_logs($vehicle,'created','Vehicle');
    }

    /**
     * Handle the Vehicle "updated" event.
     *
     * @param  \App\Models\Vehicle  $vehicle
     * @return void
     */
    public function updated(Vehicle $vehicle)
    {
        Helpers::create_all_logs($vehicle,'updated','Vehicle');
    }

    /**
     * Handle the Vehicle "deleted" event.
     *
     * @param  \App\Models\Vehicle  $vehicle
     * @return void
     */
    public function deleted(Vehicle $vehicle)
    {
        Helpers::create_all_logs($vehicle,'deleted','Vehicle');
    }

    /**
     * Handle the Vehicle "restored" event.
     *
     * @param  \App\Models\Vehicle  $vehicle
     * @return void
     */
    public function restored(Vehicle $vehicle)
    {
        //
    }

    /**
     * Handle the Vehicle "force deleted" event.
     *
     * @param  \App\Models\Vehicle  $vehicle
     * @return void
     */
    public function forceDeleted(Vehicle $vehicle)
    {
        //
    }
}
