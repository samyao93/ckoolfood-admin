<?php

namespace App\Observers;

use App\CentralLogics\Helpers;
use App\Models\SubscriptionSchedule;


class SubscriptionScheduleObserver
{
    /**
     * Handle the SubscriptionSchedule "created" event.
     *
     * @param  \App\Models\SubscriptionSchedule  $subscriptionSchedule
     * @return void
     */
    public function created(SubscriptionSchedule $subscriptionSchedule)
    {
        Helpers::create_all_logs($subscriptionSchedule,'created','SubscriptionSchedule');
    }

    /**
     * Handle the SubscriptionSchedule "updated" event.
     *
     * @param  \App\Models\SubscriptionSchedule  $subscriptionSchedule
     * @return void
     */
    public function updated(SubscriptionSchedule $subscriptionSchedule)
    {
        Helpers::create_all_logs($subscriptionSchedule,'updated','SubscriptionSchedule');
    }

    /**
     * Handle the SubscriptionSchedule "deleted" event.
     *
     * @param  \App\Models\SubscriptionSchedule  $subscriptionSchedule
     * @return void
     */
    public function deleted(SubscriptionSchedule $subscriptionSchedule)
    {
        Helpers::create_all_logs($subscriptionSchedule,'deleted','SubscriptionSchedule');
    }

    /**
     * Handle the SubscriptionSchedule "restored" event.
     *
     * @param  \App\Models\SubscriptionSchedule  $subscriptionSchedule
     * @return void
     */
    public function restored(SubscriptionSchedule $subscriptionSchedule)
    {
        //
    }

    /**
     * Handle the SubscriptionSchedule "force deleted" event.
     *
     * @param  \App\Models\SubscriptionSchedule  $subscriptionSchedule
     * @return void
     */
    public function forceDeleted(SubscriptionSchedule $subscriptionSchedule)
    {
        //
    }
}
