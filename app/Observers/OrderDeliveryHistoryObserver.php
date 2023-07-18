<?php

namespace App\Observers;

use App\CentralLogics\Helpers;
use App\Models\OrderDeliveryHistory;


class OrderDeliveryHistoryObserver
{
    /**
     * Handle the OrderDeliveryHistory "created" event.
     *
     * @param  \App\Models\OrderDeliveryHistory  $orderDeliveryHistory
     * @return void
     */
    public function created(OrderDeliveryHistory $orderDeliveryHistory)
    {
        Helpers::create_all_logs($orderDeliveryHistory,'created','OrderDeliveryHistory');
    }

    /**
     * Handle the OrderDeliveryHistory "updated" event.
     *
     * @param  \App\Models\OrderDeliveryHistory  $orderDeliveryHistory
     * @return void
     */
    public function updated(OrderDeliveryHistory $orderDeliveryHistory)
    {
        Helpers::create_all_logs($orderDeliveryHistory,'updated','OrderDeliveryHistory');
    }

    /**
     * Handle the OrderDeliveryHistory "deleted" event.
     *
     * @param  \App\Models\OrderDeliveryHistory  $orderDeliveryHistory
     * @return void
     */
    public function deleted(OrderDeliveryHistory $orderDeliveryHistory)
    {
        Helpers::create_all_logs($orderDeliveryHistory,'deleted','OrderDeliveryHistory');
    }

    /**
     * Handle the OrderDeliveryHistory "restored" event.
     *
     * @param  \App\Models\OrderDeliveryHistory  $orderDeliveryHistory
     * @return void
     */
    public function restored(OrderDeliveryHistory $orderDeliveryHistory)
    {
        //
    }

    /**
     * Handle the OrderDeliveryHistory "force deleted" event.
     *
     * @param  \App\Models\OrderDeliveryHistory  $orderDeliveryHistory
     * @return void
     */
    public function forceDeleted(OrderDeliveryHistory $orderDeliveryHistory)
    {
        //
    }
}
