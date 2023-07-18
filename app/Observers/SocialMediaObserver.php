<?php

namespace App\Observers;

use App\CentralLogics\Helpers;
use App\Models\SocialMedia;


class SocialMediaObserver
{
    /**
     * Handle the SocialMedia "created" event.
     *
     * @param  \App\Models\SocialMedia  $socialMedia
     * @return void
     */
    public function created(SocialMedia $socialMedia)
    {
        Helpers::create_all_logs($socialMedia,'created','SocialMedia');
    }

    /**
     * Handle the SocialMedia "updated" event.
     *
     * @param  \App\Models\SocialMedia  $socialMedia
     * @return void
     */
    public function updated(SocialMedia $socialMedia)
    {
        Helpers::create_all_logs($socialMedia,'updated','SocialMedia');
    }

    /**
     * Handle the SocialMedia "deleted" event.
     *
     * @param  \App\Models\SocialMedia  $socialMedia
     * @return void
     */
    public function deleted(SocialMedia $socialMedia)
    {
        Helpers::create_all_logs($socialMedia,'deleted','SocialMedia');
    }

    /**
     * Handle the SocialMedia "restored" event.
     *
     * @param  \App\Models\SocialMedia  $socialMedia
     * @return void
     */
    public function restored(SocialMedia $socialMedia)
    {
        //
    }

    /**
     * Handle the SocialMedia "force deleted" event.
     *
     * @param  \App\Models\SocialMedia  $socialMedia
     * @return void
     */
    public function forceDeleted(SocialMedia $socialMedia)
    {
        //
    }
}
