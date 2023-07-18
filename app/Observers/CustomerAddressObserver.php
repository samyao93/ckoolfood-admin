<?php

namespace App\Observers;

use App\Models\CustomerAddress;
use App\CentralLogics\Helpers;

class CustomerAddressObserver
{
    /**
     * Handle the CustomerAddress "created" event.
     *
     * @param  \App\Models\CustomerAddress  $customerAddress
     * @return void
     */
    public function created(CustomerAddress $customerAddress)
    {
        Helpers::create_all_logs($customerAddress,'created','CustomerAddress');
    }

    /**
     * Handle the CustomerAddress "updated" event.
     *
     * @param  \App\Models\CustomerAddress  $customerAddress
     * @return void
     */
    public function updated(CustomerAddress $customerAddress)
    {
        Helpers::create_all_logs($customerAddress,'updated','CustomerAddress');
    }

    /**
     * Handle the CustomerAddress "deleted" event.
     *
     * @param  \App\Models\CustomerAddress  $customerAddress
     * @return void
     */
    public function deleted(CustomerAddress $customerAddress)
    {
        Helpers::create_all_logs($customerAddress,'deleted','CustomerAddress');
    }

    /**
     * Handle the CustomerAddress "restored" event.
     *
     * @param  \App\Models\CustomerAddress  $customerAddress
     * @return void
     */
    public function restored(CustomerAddress $customerAddress)
    {
        //
    }

    /**
     * Handle the CustomerAddress "force deleted" event.
     *
     * @param  \App\Models\CustomerAddress  $customerAddress
     * @return void
     */
    public function forceDeleted(CustomerAddress $customerAddress)
    {
        //
    }
}
