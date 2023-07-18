<?php

namespace App\Observers;

use App\CentralLogics\Helpers;
use App\Models\OrderCancelReason;


class OrderCancelReasonObserver
{
    /**
     * Handle the OrderCancelReason "created" event.
     *
     * @param  \App\Models\OrderCancelReason  $orderCancelReason
     * @return void
     */
    public function created(OrderCancelReason $orderCancelReason)
    {
        Helpers::create_all_logs($orderCancelReason,'created','OrderCancelReason');
    }

    /**
     * Handle the OrderCancelReason "updated" event.
     *
     * @param  \App\Models\OrderCancelReason  $orderCancelReason
     * @return void
     */
    public function updated(OrderCancelReason $orderCancelReason)
    {
        Helpers::create_all_logs($orderCancelReason,'updated','OrderCancelReason');
    }

    /**
     * Handle the OrderCancelReason "deleted" event.
     *
     * @param  \App\Models\OrderCancelReason  $orderCancelReason
     * @return void
     */
    public function deleted(OrderCancelReason $orderCancelReason)
    {
        Helpers::create_all_logs($orderCancelReason,'deleted','OrderCancelReason');
    }

    /**
     * Handle the OrderCancelReason "restored" event.
     *
     * @param  \App\Models\OrderCancelReason  $orderCancelReason
     * @return void
     */
    public function restored(OrderCancelReason $orderCancelReason)
    {
        //
    }

    /**
     * Handle the OrderCancelReason "force deleted" event.
     *
     * @param  \App\Models\OrderCancelReason  $orderCancelReason
     * @return void
     */
    public function forceDeleted(OrderCancelReason $orderCancelReason)
    {
        //
    }
}
