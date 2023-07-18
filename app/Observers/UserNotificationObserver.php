<?php

namespace App\Observers;

use App\CentralLogics\Helpers;
use App\Models\UserNotification;


class UserNotificationObserver
{
    /**
     * Handle the UserNotification "created" event.
     *
     * @param  \App\Models\UserNotification  $userNotification
     * @return void
     */
    public function created(UserNotification $userNotification)
    {
        Helpers::create_all_logs($userNotification,'created','UserNotification');
    }

    /**
     * Handle the UserNotification "updated" event.
     *
     * @param  \App\Models\UserNotification  $userNotification
     * @return void
     */
    public function updated(UserNotification $userNotification)
    {
        Helpers::create_all_logs($userNotification,'updated','UserNotification');
    }

    /**
     * Handle the UserNotification "deleted" event.
     *
     * @param  \App\Models\UserNotification  $userNotification
     * @return void
     */
    public function deleted(UserNotification $userNotification)
    {
        Helpers::create_all_logs($userNotification,'deleted','UserNotification');
    }

    /**
     * Handle the UserNotification "restored" event.
     *
     * @param  \App\Models\UserNotification  $userNotification
     * @return void
     */
    public function restored(UserNotification $userNotification)
    {
        //
    }

    /**
     * Handle the UserNotification "force deleted" event.
     *
     * @param  \App\Models\UserNotification  $userNotification
     * @return void
     */
    public function forceDeleted(UserNotification $userNotification)
    {
        //
    }
}
