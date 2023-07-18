<?php

namespace App\Observers;

use App\CentralLogics\Helpers;
use App\Models\SubscriptionTransaction;


class SubscriptionTransactionObserver
{
    /**
     * Handle the SubscriptionTransaction "created" event.
     *
     * @param  \App\Models\SubscriptionTransaction  $subscriptionTransaction
     * @return void
     */
    public function created(SubscriptionTransaction $subscriptionTransaction)
    {
        Helpers::create_all_logs($subscriptionTransaction,'created','SubscriptionTransaction');
    }

    /**
     * Handle the SubscriptionTransaction "updated" event.
     *
     * @param  \App\Models\SubscriptionTransaction  $subscriptionTransaction
     * @return void
     */
    public function updated(SubscriptionTransaction $subscriptionTransaction)
    {
        Helpers::create_all_logs($subscriptionTransaction,'updated','SubscriptionTransaction');
    }

    /**
     * Handle the SubscriptionTransaction "deleted" event.
     *
     * @param  \App\Models\SubscriptionTransaction  $subscriptionTransaction
     * @return void
     */
    public function deleted(SubscriptionTransaction $subscriptionTransaction)
    {
        Helpers::create_all_logs($subscriptionTransaction,'deleted','SubscriptionTransaction');
    }

    /**
     * Handle the SubscriptionTransaction "restored" event.
     *
     * @param  \App\Models\SubscriptionTransaction  $subscriptionTransaction
     * @return void
     */
    public function restored(SubscriptionTransaction $subscriptionTransaction)
    {
        //
    }

    /**
     * Handle the SubscriptionTransaction "force deleted" event.
     *
     * @param  \App\Models\SubscriptionTransaction  $subscriptionTransaction
     * @return void
     */
    public function forceDeleted(SubscriptionTransaction $subscriptionTransaction)
    {
        //
    }
}
