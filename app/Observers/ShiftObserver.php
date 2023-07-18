<?php

namespace App\Observers;

use App\CentralLogics\Helpers;
use App\Models\Shift;


class ShiftObserver
{
    /**
     * Handle the Shift "created" event.
     *
     * @param  \App\Models\Shift  $shift
     * @return void
     */
    public function created(Shift $shift)
    {
        Helpers::create_all_logs($shift,'created','Shift');
    }

    /**
     * Handle the Shift "updated" event.
     *
     * @param  \App\Models\Shift  $shift
     * @return void
     */
    public function updated(Shift $shift)
    {
        Helpers::create_all_logs($shift,'updated','Shift');
    }

    /**
     * Handle the Shift "deleted" event.
     *
     * @param  \App\Models\Shift  $shift
     * @return void
     */
    public function deleted(Shift $shift)
    {
        Helpers::create_all_logs($shift,'deleted','Shift');
    }

    /**
     * Handle the Shift "restored" event.
     *
     * @param  \App\Models\Shift  $shift
     * @return void
     */
    public function restored(Shift $shift)
    {
        //
    }

    /**
     * Handle the Shift "force deleted" event.
     *
     * @param  \App\Models\Shift  $shift
     * @return void
     */
    public function forceDeleted(Shift $shift)
    {
        //
    }
}
