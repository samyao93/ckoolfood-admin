<?php

namespace App\Observers;

use App\CentralLogics\Helpers;
use App\Models\Incentive;


class IncentiveObserver
{
    /**
     * Handle the Incentive "created" event.
     *
     * @param  \App\Models\Incentive  $incentive
     * @return void
     */
    public function created(Incentive $incentive)
    {
        Helpers::create_all_logs($incentive,'created','Incentive');
    }

    /**
     * Handle the Incentive "updated" event.
     *
     * @param  \App\Models\Incentive  $incentive
     * @return void
     */
    public function updated(Incentive $incentive)
    {
        Helpers::create_all_logs($incentive,'updated','Incentive');
    }

    /**
     * Handle the Incentive "deleted" event.
     *
     * @param  \App\Models\Incentive  $incentive
     * @return void
     */
    public function deleted(Incentive $incentive)
    {
        Helpers::create_all_logs($incentive,'deleted','Incentive');
    }

    /**
     * Handle the Incentive "restored" event.
     *
     * @param  \App\Models\Incentive  $incentive
     * @return void
     */
    public function restored(Incentive $incentive)
    {
        //
    }

    /**
     * Handle the Incentive "force deleted" event.
     *
     * @param  \App\Models\Incentive  $incentive
     * @return void
     */
    public function forceDeleted(Incentive $incentive)
    {
        //
    }
}
