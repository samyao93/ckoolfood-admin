<?php

namespace App\Observers;

use App\CentralLogics\Helpers;
use App\Models\DeliveryMan;


class DeliveryManObserver
{
    /**
     * Handle the DeliveryMan "created" event.
     *
     * @param  \App\Models\DeliveryMan  $deliveryMan
     * @return void
     */
    public function created(DeliveryMan $deliveryMan)
    {
        Helpers::create_all_logs($deliveryMan,'created','DeliveryMan');
    }

    /**
     * Handle the DeliveryMan "updated" event.
     *
     * @param  \App\Models\DeliveryMan  $deliveryMan
     * @return void
     */
    public function updated(DeliveryMan $deliveryMan)
    {
        Helpers::create_all_logs($deliveryMan,'updated','DeliveryMan');
    }

    /**
     * Handle the DeliveryMan "deleted" event.
     *
     * @param  \App\Models\DeliveryMan  $deliveryMan
     * @return void
     */
    public function deleted(DeliveryMan $deliveryMan)
    {
        Helpers::create_all_logs($deliveryMan,'deleted','DeliveryMan');
    }

    /**
     * Handle the DeliveryMan "restored" event.
     *
     * @param  \App\Models\DeliveryMan  $deliveryMan
     * @return void
     */
    public function restored(DeliveryMan $deliveryMan)
    {
        //
    }

    /**
     * Handle the DeliveryMan "force deleted" event.
     *
     * @param  \App\Models\DeliveryMan  $deliveryMan
     * @return void
     */
    public function forceDeleted(DeliveryMan $deliveryMan)
    {
        //
    }
}
