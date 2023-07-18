<?php

namespace App\Observers;

use App\CentralLogics\Helpers;
use App\Models\SubscriptionPause;


class SubscriptionPauseObserver
{
    /**
     * Handle the SubscriptionPause "created" event.
     *
     * @param  \App\Models\SubscriptionPause  $subscriptionPause
     * @return void
     */
    public function created(SubscriptionPause $subscriptionPause)
    {
        Helpers::create_all_logs($subscriptionPause,'created','SubscriptionPause');
    }

    /**
     * Handle the SubscriptionPause "updated" event.
     *
     * @param  \App\Models\SubscriptionPause  $subscriptionPause
     * @return void
     */
    public function updated(SubscriptionPause $subscriptionPause)
    {
        Helpers::create_all_logs($subscriptionPause,'updated','SubscriptionPause');
    }

    /**
     * Handle the SubscriptionPause "deleted" event.
     *
     * @param  \App\Models\SubscriptionPause  $subscriptionPause
     * @return void
     */
    public function deleted(SubscriptionPause $subscriptionPause)
    {
        Helpers::create_all_logs($subscriptionPause,'deleted','SubscriptionPause');
    }

    /**
     * Handle the SubscriptionPause "restored" event.
     *
     * @param  \App\Models\SubscriptionPause  $subscriptionPause
     * @return void
     */
    public function restored(SubscriptionPause $subscriptionPause)
    {
        //
    }

    /**
     * Handle the SubscriptionPause "force deleted" event.
     *
     * @param  \App\Models\SubscriptionPause  $subscriptionPause
     * @return void
     */
    public function forceDeleted(SubscriptionPause $subscriptionPause)
    {
        //
    }
}
