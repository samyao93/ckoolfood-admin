<?php

namespace App\Observers;

use App\CentralLogics\Helpers;
use App\Models\Log;


class LogObserver
{
    /**
     * Handle the Log "created" event.
     *
     * @param  \App\Models\Log  $log
     * @return void
     */
    public function created(Log $log)
    {
        // Helpers::create_all_logs($log,'created','Log');
    }

    /**
     * Handle the Log "updated" event.
     *
     * @param  \App\Models\Log  $log
     * @return void
     */
    public function updated(Log $log)
    {
        // Helpers::create_all_logs($log,'updated','Log');
    }

    /**
     * Handle the Log "deleted" event.
     *
     * @param  \App\Models\Log  $log
     * @return void
     */
    public function deleted(Log $log)
    {
        Helpers::create_all_logs($log,'deleted','Log');
    }

    /**
     * Handle the Log "restored" event.
     *
     * @param  \App\Models\Log  $log
     * @return void
     */
    public function restored(Log $log)
    {
        //
    }

    /**
     * Handle the Log "force deleted" event.
     *
     * @param  \App\Models\Log  $log
     * @return void
     */
    public function forceDeleted(Log $log)
    {
        //
    }
}
