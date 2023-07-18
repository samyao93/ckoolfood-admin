<?php

namespace App\Observers;

use App\CentralLogics\Helpers;
use App\Models\DMReview;


class DMReviewObserver
{
    /**
     * Handle the DMReview "created" event.
     *
     * @param  \App\Models\DMReview  $dMReview
     * @return void
     */
    public function created(DMReview $dMReview)
    {
        Helpers::create_all_logs($dMReview,'created','DMReview');
    }

    /**
     * Handle the DMReview "updated" event.
     *
     * @param  \App\Models\DMReview  $dMReview
     * @return void
     */
    public function updated(DMReview $dMReview)
    {
        Helpers::create_all_logs($dMReview,'updated','DMReview');
    }

    /**
     * Handle the DMReview "deleted" event.
     *
     * @param  \App\Models\DMReview  $dMReview
     * @return void
     */
    public function deleted(DMReview $dMReview)
    {
        Helpers::create_all_logs($dMReview,'deleted','DMReview');
    }

    /**
     * Handle the DMReview "restored" event.
     *
     * @param  \App\Models\DMReview  $dMReview
     * @return void
     */
    public function restored(DMReview $dMReview)
    {
        //
    }

    /**
     * Handle the DMReview "force deleted" event.
     *
     * @param  \App\Models\DMReview  $dMReview
     * @return void
     */
    public function forceDeleted(DMReview $dMReview)
    {
        //
    }
}
