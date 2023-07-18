<?php

namespace App\Observers;

use App\CentralLogics\Helpers;
use App\Models\WithdrawRequest;


class WithdrawRequestObserver
{
    /**
     * Handle the WithdrawRequest "created" event.
     *
     * @param  \App\Models\WithdrawRequest  $withdrawRequest
     * @return void
     */
    public function created(WithdrawRequest $withdrawRequest)
    {
        Helpers::create_all_logs($withdrawRequest,'created','WithdrawRequest');
    }

    /**
     * Handle the WithdrawRequest "updated" event.
     *
     * @param  \App\Models\WithdrawRequest  $withdrawRequest
     * @return void
     */
    public function updated(WithdrawRequest $withdrawRequest)
    {
        Helpers::create_all_logs($withdrawRequest,'updated','WithdrawRequest');
    }

    /**
     * Handle the WithdrawRequest "deleted" event.
     *
     * @param  \App\Models\WithdrawRequest  $withdrawRequest
     * @return void
     */
    public function deleted(WithdrawRequest $withdrawRequest)
    {
        Helpers::create_all_logs($withdrawRequest,'deleted','WithdrawRequest');
    }

    /**
     * Handle the WithdrawRequest "restored" event.
     *
     * @param  \App\Models\WithdrawRequest  $withdrawRequest
     * @return void
     */
    public function restored(WithdrawRequest $withdrawRequest)
    {
        //
    }

    /**
     * Handle the WithdrawRequest "force deleted" event.
     *
     * @param  \App\Models\WithdrawRequest  $withdrawRequest
     * @return void
     */
    public function forceDeleted(WithdrawRequest $withdrawRequest)
    {
        //
    }
}
