<?php

namespace App\Observers;

use App\CentralLogics\Helpers;
use App\Models\EmailVerifications;


class EmailVerificationsObserver
{
    /**
     * Handle the EmailVerifications "created" event.
     *
     * @param  \App\Models\EmailVerifications  $emailVerifications
     * @return void
     */
    public function created(EmailVerifications $emailVerifications)
    {
        Helpers::create_all_logs($emailVerifications,'created','EmailVerifications');
    }

    /**
     * Handle the EmailVerifications "updated" event.
     *
     * @param  \App\Models\EmailVerifications  $emailVerifications
     * @return void
     */
    public function updated(EmailVerifications $emailVerifications)
    {
        Helpers::create_all_logs($emailVerifications,'updated','EmailVerifications');
    }

    /**
     * Handle the EmailVerifications "deleted" event.
     *
     * @param  \App\Models\EmailVerifications  $emailVerifications
     * @return void
     */
    public function deleted(EmailVerifications $emailVerifications)
    {
        Helpers::create_all_logs($emailVerifications,'deleted','EmailVerifications');
    }

    /**
     * Handle the EmailVerifications "restored" event.
     *
     * @param  \App\Models\EmailVerifications  $emailVerifications
     * @return void
     */
    public function restored(EmailVerifications $emailVerifications)
    {
        //
    }

    /**
     * Handle the EmailVerifications "force deleted" event.
     *
     * @param  \App\Models\EmailVerifications  $emailVerifications
     * @return void
     */
    public function forceDeleted(EmailVerifications $emailVerifications)
    {
        //
    }
}
