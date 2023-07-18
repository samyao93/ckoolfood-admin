<?php

namespace App\Observers;

use App\CentralLogics\Helpers;
use App\Models\Newsletter;


class NewsletterObserver
{
    /**
     * Handle the Newsletter "created" event.
     *
     * @param  \App\Models\Newsletter  $newsletter
     * @return void
     */
    public function created(Newsletter $newsletter)
    {
        Helpers::create_all_logs($newsletter,'created','Newsletter');
    }

    /**
     * Handle the Newsletter "updated" event.
     *
     * @param  \App\Models\Newsletter  $newsletter
     * @return void
     */
    public function updated(Newsletter $newsletter)
    {
        Helpers::create_all_logs($newsletter,'updated','Newsletter');
    }

    /**
     * Handle the Newsletter "deleted" event.
     *
     * @param  \App\Models\Newsletter  $newsletter
     * @return void
     */
    public function deleted(Newsletter $newsletter)
    {
        Helpers::create_all_logs($newsletter,'deleted','Newsletter');
    }

    /**
     * Handle the Newsletter "restored" event.
     *
     * @param  \App\Models\Newsletter  $newsletter
     * @return void
     */
    public function restored(Newsletter $newsletter)
    {
        //
    }

    /**
     * Handle the Newsletter "force deleted" event.
     *
     * @param  \App\Models\Newsletter  $newsletter
     * @return void
     */
    public function forceDeleted(Newsletter $newsletter)
    {
        //
    }
}
