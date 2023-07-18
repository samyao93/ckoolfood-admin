<?php

namespace App\Observers;

use App\CentralLogics\Helpers;
use App\Models\WithdrawalMethod;


class WithdrawalMethodObserver
{
    /**
     * Handle the WithdrawalMethod "created" event.
     *
     * @param  \App\Models\WithdrawalMethod  $withdrawalMethod
     * @return void
     */
    public function created(WithdrawalMethod $withdrawalMethod)
    {
        Helpers::create_all_logs($withdrawalMethod,'created','WithdrawalMethod');
    }

    /**
     * Handle the WithdrawalMethod "updated" event.
     *
     * @param  \App\Models\WithdrawalMethod  $withdrawalMethod
     * @return void
     */
    public function updated(WithdrawalMethod $withdrawalMethod)
    {
        Helpers::create_all_logs($withdrawalMethod,'updated','WithdrawalMethod');
    }

    /**
     * Handle the WithdrawalMethod "deleted" event.
     *
     * @param  \App\Models\WithdrawalMethod  $withdrawalMethod
     * @return void
     */
    public function deleted(WithdrawalMethod $withdrawalMethod)
    {
        Helpers::create_all_logs($withdrawalMethod,'deleted','WithdrawalMethod');
    }

    /**
     * Handle the WithdrawalMethod "restored" event.
     *
     * @param  \App\Models\WithdrawalMethod  $withdrawalMethod
     * @return void
     */
    public function restored(WithdrawalMethod $withdrawalMethod)
    {
        //
    }

    /**
     * Handle the WithdrawalMethod "force deleted" event.
     *
     * @param  \App\Models\WithdrawalMethod  $withdrawalMethod
     * @return void
     */
    public function forceDeleted(WithdrawalMethod $withdrawalMethod)
    {
        //
    }
}
