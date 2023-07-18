<?php

namespace App\Observers;

use App\CentralLogics\Helpers;
use App\Models\ProvideDMEarning;


class ProvideDMEarningObserver
{
    /**
     * Handle the ProvideDMEarning "created" event.
     *
     * @param  \App\Models\ProvideDMEarning  $provideDMEarning
     * @return void
     */
    public function created(ProvideDMEarning $provideDMEarning)
    {
        Helpers::create_all_logs($provideDMEarning,'created','ProvideDMEarning');
    }

    /**
     * Handle the ProvideDMEarning "updated" event.
     *
     * @param  \App\Models\ProvideDMEarning  $provideDMEarning
     * @return void
     */
    public function updated(ProvideDMEarning $provideDMEarning)
    {
        Helpers::create_all_logs($provideDMEarning,'updated','ProvideDMEarning');
    }

    /**
     * Handle the ProvideDMEarning "deleted" event.
     *
     * @param  \App\Models\ProvideDMEarning  $provideDMEarning
     * @return void
     */
    public function deleted(ProvideDMEarning $provideDMEarning)
    {
        Helpers::create_all_logs($provideDMEarning,'deleted','ProvideDMEarning');
    }

    /**
     * Handle the ProvideDMEarning "restored" event.
     *
     * @param  \App\Models\ProvideDMEarning  $provideDMEarning
     * @return void
     */
    public function restored(ProvideDMEarning $provideDMEarning)
    {
        //
    }

    /**
     * Handle the ProvideDMEarning "force deleted" event.
     *
     * @param  \App\Models\ProvideDMEarning  $provideDMEarning
     * @return void
     */
    public function forceDeleted(ProvideDMEarning $provideDMEarning)
    {
        //
    }
}
