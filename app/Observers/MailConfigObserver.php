<?php

namespace App\Observers;

use App\CentralLogics\Helpers;
use App\Models\MailConfig;


class MailConfigObserver
{
    /**
     * Handle the MailConfig "created" event.
     *
     * @param  \App\Models\MailConfig  $mailConfig
     * @return void
     */
    public function created(MailConfig $mailConfig)
    {
        Helpers::create_all_logs($mailConfig,'created','MailConfig');
    }

    /**
     * Handle the MailConfig "updated" event.
     *
     * @param  \App\Models\MailConfig  $mailConfig
     * @return void
     */
    public function updated(MailConfig $mailConfig)
    {
        Helpers::create_all_logs($mailConfig,'updated','MailConfig');
    }

    /**
     * Handle the MailConfig "deleted" event.
     *
     * @param  \App\Models\MailConfig  $mailConfig
     * @return void
     */
    public function deleted(MailConfig $mailConfig)
    {
        Helpers::create_all_logs($mailConfig,'deleted','MailConfig');
    }

    /**
     * Handle the MailConfig "restored" event.
     *
     * @param  \App\Models\MailConfig  $mailConfig
     * @return void
     */
    public function restored(MailConfig $mailConfig)
    {
        //
    }

    /**
     * Handle the MailConfig "force deleted" event.
     *
     * @param  \App\Models\MailConfig  $mailConfig
     * @return void
     */
    public function forceDeleted(MailConfig $mailConfig)
    {
        //
    }
}
