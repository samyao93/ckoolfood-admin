<?php

namespace App\Observers;

use App\CentralLogics\Helpers;
use App\Models\SubscriptionPackage;


class SubscriptionPackageObserver
{
    /**
     * Handle the SubscriptionPackage "created" event.
     *
     * @param  \App\Models\SubscriptionPackage  $subscriptionPackage
     * @return void
     */
    public function created(SubscriptionPackage $subscriptionPackage)
    {
        Helpers::create_all_logs($subscriptionPackage,'created','SubscriptionPackage');
    }

    /**
     * Handle the SubscriptionPackage "updated" event.
     *
     * @param  \App\Models\SubscriptionPackage  $subscriptionPackage
     * @return void
     */
    public function updated(SubscriptionPackage $subscriptionPackage)
    {
        Helpers::create_all_logs($subscriptionPackage,'updated','SubscriptionPackage');
    }

    /**
     * Handle the SubscriptionPackage "deleted" event.
     *
     * @param  \App\Models\SubscriptionPackage  $subscriptionPackage
     * @return void
     */
    public function deleted(SubscriptionPackage $subscriptionPackage)
    {
        Helpers::create_all_logs($subscriptionPackage,'deleted','SubscriptionPackage');
    }

    /**
     * Handle the SubscriptionPackage "restored" event.
     *
     * @param  \App\Models\SubscriptionPackage  $subscriptionPackage
     * @return void
     */
    public function restored(SubscriptionPackage $subscriptionPackage)
    {
        //
    }

    /**
     * Handle the SubscriptionPackage "force deleted" event.
     *
     * @param  \App\Models\SubscriptionPackage  $subscriptionPackage
     * @return void
     */
    public function forceDeleted(SubscriptionPackage $subscriptionPackage)
    {
        //
    }
}
