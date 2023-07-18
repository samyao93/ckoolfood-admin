<?php

namespace App\Observers;

use App\Models\AccountTransaction;
use App\CentralLogics\Helpers;

class AccountTransactionObserver
{
    /**
     * Handle the AccountTransaction "created" event.
     *
     * @param  \App\Models\AccountTransaction  $accountTransaction
     * @return void
     */
    public function created(AccountTransaction $accountTransaction)
    {
        Helpers::create_all_logs($accountTransaction,'created','AccountTransaction');
    }

    /**
     * Handle the AccountTransaction "updated" event.
     *
     * @param  \App\Models\AccountTransaction  $accountTransaction
     * @return void
     */
    public function updated(AccountTransaction $accountTransaction)
    {
        Helpers::create_all_logs($accountTransaction,'updated','AccountTransaction');

    }

    /**
     * Handle the AccountTransaction "deleted" event.
     *
     * @param  \App\Models\AccountTransaction  $accountTransaction
     * @return void
     */
    public function deleted(AccountTransaction $accountTransaction)
    {
        Helpers::create_all_logs($accountTransaction,'deleted','AccountTransaction');

    }

    /**
     * Handle the AccountTransaction "restored" event.
     *
     * @param  \App\Models\AccountTransaction  $accountTransaction
     * @return void
     */
    public function restored(AccountTransaction $accountTransaction)
    {
        //
    }

    /**
     * Handle the AccountTransaction "force deleted" event.
     *
     * @param  \App\Models\AccountTransaction  $accountTransaction
     * @return void
     */
    public function forceDeleted(AccountTransaction $accountTransaction)
    {
        //
    }
}
