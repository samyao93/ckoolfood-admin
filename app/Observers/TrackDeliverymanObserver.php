<?php

namespace App\Observers;

use App\CentralLogics\Helpers;
use App\Models\TrackDeliveryman;


class TrackDeliverymanObserver
{
    /**
     * Handle the TrackDeliveryman "created" event.
     *
     * @param  \App\Models\TrackDeliveryman  $trackDeliveryman
     * @return void
     */
    public function created(TrackDeliveryman $trackDeliveryman)
    {
        Helpers::create_all_logs($trackDeliveryman,'created','TrackDeliveryman');
    }

    /**
     * Handle the TrackDeliveryman "updated" event.
     *
     * @param  \App\Models\TrackDeliveryman  $trackDeliveryman
     * @return void
     */
    public function updated(TrackDeliveryman $trackDeliveryman)
    {
        Helpers::create_all_logs($trackDeliveryman,'updated','TrackDeliveryman');
    }

    /**
     * Handle the TrackDeliveryman "deleted" event.
     *
     * @param  \App\Models\TrackDeliveryman  $trackDeliveryman
     * @return void
     */
    public function deleted(TrackDeliveryman $trackDeliveryman)
    {
        Helpers::create_all_logs($trackDeliveryman,'deleted','TrackDeliveryman');
    }

    /**
     * Handle the TrackDeliveryman "restored" event.
     *
     * @param  \App\Models\TrackDeliveryman  $trackDeliveryman
     * @return void
     */
    public function restored(TrackDeliveryman $trackDeliveryman)
    {
        //
    }

    /**
     * Handle the TrackDeliveryman "force deleted" event.
     *
     * @param  \App\Models\TrackDeliveryman  $trackDeliveryman
     * @return void
     */
    public function forceDeleted(TrackDeliveryman $trackDeliveryman)
    {
        //
    }
}
