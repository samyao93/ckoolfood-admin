<?php

namespace App\Observers;

use App\CentralLogics\Helpers;
use App\Models\SubscriptionLog;


class SubscriptionLogObserver
{
    /**
     * Handle the SubscriptionLog "created" event.
     *
     * @param  \App\Models\SubscriptionLog  $subscriptionLog
     * @return void
     */
    public function created(SubscriptionLog $subscriptionLog)
    {
        Helpers::create_all_logs($subscriptionLog,'created','SubscriptionLog');
    }

    /**
     * Handle the SubscriptionLog "updated" event.
     *
     * @param  \App\Models\SubscriptionLog  $subscriptionLog
     * @return void
     */
    public function updated(SubscriptionLog $subscriptionLog)
    {
        Helpers::create_all_logs($subscriptionLog,'updated','SubscriptionLog');
    }

    /**
     * Handle the SubscriptionLog "deleted" event.
     *
     * @param  \App\Models\SubscriptionLog  $subscriptionLog
     * @return void
     */
    public function deleted(SubscriptionLog $subscriptionLog)
    {
        Helpers::create_all_logs($subscriptionLog,'deleted','SubscriptionLog');
    }

    /**
     * Handle the SubscriptionLog "restored" event.
     *
     * @param  \App\Models\SubscriptionLog  $subscriptionLog
     * @return void
     */
    public function restored(SubscriptionLog $subscriptionLog)
    {
        //
    }

    /**
     * Handle the SubscriptionLog "force deleted" event.
     *
     * @param  \App\Models\SubscriptionLog  $subscriptionLog
     * @return void
     */
    public function forceDeleted(SubscriptionLog $subscriptionLog)
    {
        //
    }
}
