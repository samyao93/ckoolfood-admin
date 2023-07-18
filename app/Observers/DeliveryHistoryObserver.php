<?php

namespace App\Observers;

use App\CentralLogics\Helpers;
use App\Models\DeliveryHistory;


class DeliveryHistoryObserver
{
    /**
     * Handle the DeliveryHistory "created" event.
     *
     * @param  \App\Models\DeliveryHistory  $deliveryHistory
     * @return void
     */
    public function created(DeliveryHistory $deliveryHistory)
    {
            Helpers::create_all_logs($deliveryHistory,'created','DeliveryHistory');
    }

    /**
     * Handle the DeliveryHistory "updated" event.
     *
     * @param  \App\Models\DeliveryHistory  $deliveryHistory
     * @return void
     */
    public function updated(DeliveryHistory $deliveryHistory)
    {
            Helpers::create_all_logs($deliveryHistory,'updated','DeliveryHistory');
    }

    /**
     * Handle the DeliveryHistory "deleted" event.
     *
     * @param  \App\Models\DeliveryHistory  $deliveryHistory
     * @return void
     */
    public function deleted(DeliveryHistory $deliveryHistory)
    {
            Helpers::create_all_logs($deliveryHistory,'deleted','DeliveryHistory');
    }

    /**
     * Handle the DeliveryHistory "restored" event.
     *
     * @param  \App\Models\DeliveryHistory  $deliveryHistory
     * @return void
     */
    public function restored(DeliveryHistory $deliveryHistory)
    {
        //
    }

    /**
     * Handle the DeliveryHistory "force deleted" event.
     *
     * @param  \App\Models\DeliveryHistory  $deliveryHistory
     * @return void
     */
    public function forceDeleted(DeliveryHistory $deliveryHistory)
    {
        //
    }
}
