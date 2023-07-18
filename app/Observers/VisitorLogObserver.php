<?php

namespace App\Observers;

use App\CentralLogics\Helpers;
use App\Models\VisitorLog;


class VisitorLogObserver
{
    /**
     * Handle the VisitorLog "created" event.
     *
     * @param  \App\Models\VisitorLog  $visitorLog
     * @return void
     */
    public function created(VisitorLog $visitorLog)
    {
        Helpers::create_all_logs($visitorLog,'created','VisitorLog');
    }

    /**
     * Handle the VisitorLog "updated" event.
     *
     * @param  \App\Models\VisitorLog  $visitorLog
     * @return void
     */
    public function updated(VisitorLog $visitorLog)
    {
        Helpers::create_all_logs($visitorLog,'updated','VisitorLog');
    }

    /**
     * Handle the VisitorLog "deleted" event.
     *
     * @param  \App\Models\VisitorLog  $visitorLog
     * @return void
     */
    public function deleted(VisitorLog $visitorLog)
    {
        Helpers::create_all_logs($visitorLog,'deleted','VisitorLog');
    }

    /**
     * Handle the VisitorLog "restored" event.
     *
     * @param  \App\Models\VisitorLog  $visitorLog
     * @return void
     */
    public function restored(VisitorLog $visitorLog)
    {
        //
    }

    /**
     * Handle the VisitorLog "force deleted" event.
     *
     * @param  \App\Models\VisitorLog  $visitorLog
     * @return void
     */
    public function forceDeleted(VisitorLog $visitorLog)
    {
        //
    }
}
