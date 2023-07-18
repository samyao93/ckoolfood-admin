<?php

namespace App\Observers;

use App\CentralLogics\Helpers;
use App\Models\TimeLog;


class TimeLogObserver
{
    /**
     * Handle the TimeLog "created" event.
     *
     * @param  \App\Models\TimeLog  $timeLog
     * @return void
     */
    public function created(TimeLog $timeLog)
    {
        Helpers::create_all_logs($timeLog,'created','TimeLog');
    }

    /**
     * Handle the TimeLog "updated" event.
     *
     * @param  \App\Models\TimeLog  $timeLog
     * @return void
     */
    public function updated(TimeLog $timeLog)
    {
        Helpers::create_all_logs($timeLog,'updated','TimeLog');
    }

    /**
     * Handle the TimeLog "deleted" event.
     *
     * @param  \App\Models\TimeLog  $timeLog
     * @return void
     */
    public function deleted(TimeLog $timeLog)
    {
        Helpers::create_all_logs($timeLog,'deleted','TimeLog');
    }

    /**
     * Handle the TimeLog "restored" event.
     *
     * @param  \App\Models\TimeLog  $timeLog
     * @return void
     */
    public function restored(TimeLog $timeLog)
    {
        //
    }

    /**
     * Handle the TimeLog "force deleted" event.
     *
     * @param  \App\Models\TimeLog  $timeLog
     * @return void
     */
    public function forceDeleted(TimeLog $timeLog)
    {
        //
    }
}
