<?php

namespace App\Observers;

use App\CentralLogics\Helpers;
use App\Models\WalletTransaction;


class WalletTransactionObserver
{
    /**
     * Handle the WalletTransaction "created" event.
     *
     * @param  \App\Models\WalletTransaction  $walletTransaction
     * @return void
     */
    public function created(WalletTransaction $walletTransaction)
    {
        Helpers::create_all_logs($walletTransaction,'created','WalletTransaction');
    }

    /**
     * Handle the WalletTransaction "updated" event.
     *
     * @param  \App\Models\WalletTransaction  $walletTransaction
     * @return void
     */
    public function updated(WalletTransaction $walletTransaction)
    {
        Helpers::create_all_logs($walletTransaction,'updated','WalletTransaction');
    }

    /**
     * Handle the WalletTransaction "deleted" event.
     *
     * @param  \App\Models\WalletTransaction  $walletTransaction
     * @return void
     */
    public function deleted(WalletTransaction $walletTransaction)
    {
        Helpers::create_all_logs($walletTransaction,'deleted','WalletTransaction');
    }

    /**
     * Handle the WalletTransaction "restored" event.
     *
     * @param  \App\Models\WalletTransaction  $walletTransaction
     * @return void
     */
    public function restored(WalletTransaction $walletTransaction)
    {
        //
    }

    /**
     * Handle the WalletTransaction "force deleted" event.
     *
     * @param  \App\Models\WalletTransaction  $walletTransaction
     * @return void
     */
    public function forceDeleted(WalletTransaction $walletTransaction)
    {
        //
    }
}
