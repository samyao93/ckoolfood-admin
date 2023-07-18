<?php

namespace App\Observers;

use App\CentralLogics\Helpers;
use App\Models\IncentiveLog;


class IncentiveLogObserver
{
    /**
     * Handle the IncentiveLog "created" event.
     *
     * @param  \App\Models\IncentiveLog  $incentiveLog
     * @return void
     */
    public function created(IncentiveLog $incentiveLog)
    {
        Helpers::create_all_logs($incentiveLog,'created','IncentiveLog');
    }

    /**
     * Handle the IncentiveLog "updated" event.
     *
     * @param  \App\Models\IncentiveLog  $incentiveLog
     * @return void
     */
    public function updated(IncentiveLog $incentiveLog)
    {
        Helpers::create_all_logs($incentiveLog,'updated','IncentiveLog');
    }

    /**
     * Handle the IncentiveLog "deleted" event.
     *
     * @param  \App\Models\IncentiveLog  $incentiveLog
     * @return void
     */
    public function deleted(IncentiveLog $incentiveLog)
    {
        Helpers::create_all_logs($incentiveLog,'deleted','IncentiveLog');
    }

    /**
     * Handle the IncentiveLog "restored" event.
     *
     * @param  \App\Models\IncentiveLog  $incentiveLog
     * @return void
     */
    public function restored(IncentiveLog $incentiveLog)
    {
        //
    }

    /**
     * Handle the IncentiveLog "force deleted" event.
     *
     * @param  \App\Models\IncentiveLog  $incentiveLog
     * @return void
     */
    public function forceDeleted(IncentiveLog $incentiveLog)
    {
        //
    }
}
