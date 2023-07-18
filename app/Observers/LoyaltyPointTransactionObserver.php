<?php

namespace App\Observers;

use App\CentralLogics\Helpers;
use App\Models\LoyaltyPointTransaction;


class LoyaltyPointTransactionObserver
{
    /**
     * Handle the LoyaltyPointTransaction "created" event.
     *
     * @param  \App\Models\LoyaltyPointTransaction  $loyaltyPointTransaction
     * @return void
     */
    public function created(LoyaltyPointTransaction $loyaltyPointTransaction)
    {
        Helpers::create_all_logs($loyaltyPointTransaction,'created','LoyaltyPointTransaction');
    }

    /**
     * Handle the LoyaltyPointTransaction "updated" event.
     *
     * @param  \App\Models\LoyaltyPointTransaction  $loyaltyPointTransaction
     * @return void
     */
    public function updated(LoyaltyPointTransaction $loyaltyPointTransaction)
    {
        Helpers::create_all_logs($loyaltyPointTransaction,'updated','LoyaltyPointTransaction');
    }

    /**
     * Handle the LoyaltyPointTransaction "deleted" event.
     *
     * @param  \App\Models\LoyaltyPointTransaction  $loyaltyPointTransaction
     * @return void
     */
    public function deleted(LoyaltyPointTransaction $loyaltyPointTransaction)
    {
        Helpers::create_all_logs($loyaltyPointTransaction,'deleted','LoyaltyPointTransaction');
    }

    /**
     * Handle the LoyaltyPointTransaction "restored" event.
     *
     * @param  \App\Models\LoyaltyPointTransaction  $loyaltyPointTransaction
     * @return void
     */
    public function restored(LoyaltyPointTransaction $loyaltyPointTransaction)
    {
        //
    }

    /**
     * Handle the LoyaltyPointTransaction "force deleted" event.
     *
     * @param  \App\Models\LoyaltyPointTransaction  $loyaltyPointTransaction
     * @return void
     */
    public function forceDeleted(LoyaltyPointTransaction $loyaltyPointTransaction)
    {
        //
    }
}
