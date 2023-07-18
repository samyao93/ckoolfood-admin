<?php

namespace App\Observers;

use App\CentralLogics\Helpers;
use App\Models\Refund;


class RefundObserver
{
    /**
     * Handle the Refund "created" event.
     *
     * @param  \App\Models\Refund  $refund
     * @return void
     */
    public function created(Refund $refund)
    {
        Helpers::create_all_logs($refund,'created','Refund');
    }

    /**
     * Handle the Refund "updated" event.
     *
     * @param  \App\Models\Refund  $refund
     * @return void
     */
    public function updated(Refund $refund)
    {
        Helpers::create_all_logs($refund,'updated','Refund');
    }

    /**
     * Handle the Refund "deleted" event.
     *
     * @param  \App\Models\Refund  $refund
     * @return void
     */
    public function deleted(Refund $refund)
    {
        Helpers::create_all_logs($refund,'deleted','Refund');
    }

    /**
     * Handle the Refund "restored" event.
     *
     * @param  \App\Models\Refund  $refund
     * @return void
     */
    public function restored(Refund $refund)
    {
        //
    }

    /**
     * Handle the Refund "force deleted" event.
     *
     * @param  \App\Models\Refund  $refund
     * @return void
     */
    public function forceDeleted(Refund $refund)
    {
        //
    }
}
