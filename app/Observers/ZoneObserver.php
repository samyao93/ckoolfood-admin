<?php

namespace App\Observers;

use App\CentralLogics\Helpers;
use App\Models\Zone;


class ZoneObserver
{
    /**
     * Handle the Zone "created" event.
     *
     * @param  \App\Models\Zone  $zone
     * @return void
     */
    public function created(Zone $zone)
    {
        Helpers::create_all_logs($zone,'created','Zone');
    }

    /**
     * Handle the Zone "updated" event.
     *
     * @param  \App\Models\Zone  $zone
     * @return void
     */
    public function updated(Zone $zone)
    {
        Helpers::create_all_logs($zone,'updated','Zone');
    }

    /**
     * Handle the Zone "deleted" event.
     *
     * @param  \App\Models\Zone  $zone
     * @return void
     */
    public function deleted(Zone $zone)
    {
        Helpers::create_all_logs($zone,'deleted','Zone');
    }

    /**
     * Handle the Zone "restored" event.
     *
     * @param  \App\Models\Zone  $zone
     * @return void
     */
    public function restored(Zone $zone)
    {
        //
    }

    /**
     * Handle the Zone "force deleted" event.
     *
     * @param  \App\Models\Zone  $zone
     * @return void
     */
    public function forceDeleted(Zone $zone)
    {
        //
    }
}
