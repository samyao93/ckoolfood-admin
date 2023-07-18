<?php

namespace App\Observers;

use App\CentralLogics\Helpers;
use App\Models\OrderTransaction;


class OrderTransactionObserver
{
    /**
     * Handle the OrderTransaction "created" event.
     *
     * @param  \App\Models\OrderTransaction  $orderTransaction
     * @return void
     */
    public function created(OrderTransaction $orderTransaction)
    {
        Helpers::create_all_logs($orderTransaction,'created','OrderTransaction');
    }

    /**
     * Handle the OrderTransaction "updated" event.
     *
     * @param  \App\Models\OrderTransaction  $orderTransaction
     * @return void
     */
    public function updated(OrderTransaction $orderTransaction)
    {
        Helpers::create_all_logs($orderTransaction,'updated','OrderTransaction');
    }

    /**
     * Handle the OrderTransaction "deleted" event.
     *
     * @param  \App\Models\OrderTransaction  $orderTransaction
     * @return void
     */
    public function deleted(OrderTransaction $orderTransaction)
    {
        Helpers::create_all_logs($orderTransaction,'deleted','OrderTransaction');
    }

    /**
     * Handle the OrderTransaction "restored" event.
     *
     * @param  \App\Models\OrderTransaction  $orderTransaction
     * @return void
     */
    public function restored(OrderTransaction $orderTransaction)
    {
        //
    }

    /**
     * Handle the OrderTransaction "force deleted" event.
     *
     * @param  \App\Models\OrderTransaction  $orderTransaction
     * @return void
     */
    public function forceDeleted(OrderTransaction $orderTransaction)
    {
        //
    }
}
