<?php

namespace App\Observers;

use App\CentralLogics\Helpers;
use App\Models\DeliveryManWallet;


class DeliveryManWalletObserver
{
    /**
     * Handle the DeliveryManWallet "created" event.
     *
     * @param  \App\Models\DeliveryManWallet  $deliveryManWallet
     * @return void
     */
    public function created(DeliveryManWallet $deliveryManWallet)
    {
        Helpers::create_all_logs($deliveryManWallet,'created','DeliveryManWallet');
    }

    /**
     * Handle the DeliveryManWallet "updated" event.
     *
     * @param  \App\Models\DeliveryManWallet  $deliveryManWallet
     * @return void
     */
    public function updated(DeliveryManWallet $deliveryManWallet)
    {
        Helpers::create_all_logs($deliveryManWallet,'updated','DeliveryManWallet');
    }

    /**
     * Handle the DeliveryManWallet "deleted" event.
     *
     * @param  \App\Models\DeliveryManWallet  $deliveryManWallet
     * @return void
     */
    public function deleted(DeliveryManWallet $deliveryManWallet)
    {
        Helpers::create_all_logs($deliveryManWallet,'deleted','DeliveryManWallet');
    }

    /**
     * Handle the DeliveryManWallet "restored" event.
     *
     * @param  \App\Models\DeliveryManWallet  $deliveryManWallet
     * @return void
     */
    public function restored(DeliveryManWallet $deliveryManWallet)
    {
        //
    }

    /**
     * Handle the DeliveryManWallet "force deleted" event.
     *
     * @param  \App\Models\DeliveryManWallet  $deliveryManWallet
     * @return void
     */
    public function forceDeleted(DeliveryManWallet $deliveryManWallet)
    {
        //
    }
}
