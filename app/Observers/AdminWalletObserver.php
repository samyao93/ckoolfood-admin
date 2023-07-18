<?php

namespace App\Observers;

use App\Models\AdminWallet;
use App\CentralLogics\Helpers;

class AdminWalletObserver
{
    /**
     * Handle the AdminWallet "created" event.
     *
     * @param  \App\Models\AdminWallet  $adminWallet
     * @return void
     */
    public function created(AdminWallet $adminWallet)
    {
        Helpers::create_all_logs($adminWallet,'created','AdminWallet');
    }

    /**
     * Handle the AdminWallet "updated" event.
     *
     * @param  \App\Models\AdminWallet  $adminWallet
     * @return void
     */
    public function updated(AdminWallet $adminWallet)
    {
        Helpers::create_all_logs($adminWallet,'updated','AdminWallet');
    }

    /**
     * Handle the AdminWallet "deleted" event.
     *
     * @param  \App\Models\AdminWallet  $adminWallet
     * @return void
     */
    public function deleted(AdminWallet $adminWallet)
    {
        Helpers::create_all_logs($adminWallet,'deleted','AdminWallet');
    }

    /**
     * Handle the AdminWallet "restored" event.
     *
     * @param  \App\Models\AdminWallet  $adminWallet
     * @return void
     */
    public function restored(AdminWallet $adminWallet)
    {
        //
    }

    /**
     * Handle the AdminWallet "force deleted" event.
     *
     * @param  \App\Models\AdminWallet  $adminWallet
     * @return void
     */
    public function forceDeleted(AdminWallet $adminWallet)
    {
        //
    }
}
