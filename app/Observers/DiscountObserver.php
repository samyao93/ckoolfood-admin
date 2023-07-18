<?php

namespace App\Observers;

use App\CentralLogics\Helpers;
use App\Models\Discount;


class DiscountObserver
{
    /**
     * Handle the Discount "created" event.
     *
     * @param  \App\Models\Discount  $discount
     * @return void
     */
    public function created(Discount $discount)
    {
        Helpers::create_all_logs($discount,'created','Discount');
    }

    /**
     * Handle the Discount "updated" event.
     *
     * @param  \App\Models\Discount  $discount
     * @return void
     */
    public function updated(Discount $discount)
    {
        Helpers::create_all_logs($discount,'updated','Discount');
    }

    /**
     * Handle the Discount "deleted" event.
     *
     * @param  \App\Models\Discount  $discount
     * @return void
     */
    public function deleted(Discount $discount)
    {
        Helpers::create_all_logs($discount,'deleted','Discount');
    }

    /**
     * Handle the Discount "restored" event.
     *
     * @param  \App\Models\Discount  $discount
     * @return void
     */
    public function restored(Discount $discount)
    {
        //
    }

    /**
     * Handle the Discount "force deleted" event.
     *
     * @param  \App\Models\Discount  $discount
     * @return void
     */
    public function forceDeleted(Discount $discount)
    {
        //
    }
}
