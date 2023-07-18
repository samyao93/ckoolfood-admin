<?php

namespace App\Observers;

use App\CentralLogics\Helpers;
use App\Models\RefundReason;


class RefundReasonObserver
{
    /**
     * Handle the RefundReason "created" event.
     *
     * @param  \App\Models\RefundReason  $refundReason
     * @return void
     */
    public function created(RefundReason $refundReason)
    {
        Helpers::create_all_logs($refundReason,'created','RefundReason');
    }

    /**
     * Handle the RefundReason "updated" event.
     *
     * @param  \App\Models\RefundReason  $refundReason
     * @return void
     */
    public function updated(RefundReason $refundReason)
    {
        Helpers::create_all_logs($refundReason,'updated','RefundReason');
    }

    /**
     * Handle the RefundReason "deleted" event.
     *
     * @param  \App\Models\RefundReason  $refundReason
     * @return void
     */
    public function deleted(RefundReason $refundReason)
    {
        Helpers::create_all_logs($refundReason,'deleted','RefundReason');
    }

    /**
     * Handle the RefundReason "restored" event.
     *
     * @param  \App\Models\RefundReason  $refundReason
     * @return void
     */
    public function restored(RefundReason $refundReason)
    {
        //
    }

    /**
     * Handle the RefundReason "force deleted" event.
     *
     * @param  \App\Models\RefundReason  $refundReason
     * @return void
     */
    public function forceDeleted(RefundReason $refundReason)
    {
        //
    }
}
