<?php

namespace App\Observers;

use App\CentralLogics\Helpers;
use App\Models\VendorEmployee;


class VendorEmployeeObserver
{
    /**
     * Handle the VendorEmployee "created" event.
     *
     * @param  \App\Models\VendorEmployee  $vendorEmployee
     * @return void
     */
    public function created(VendorEmployee $vendorEmployee)
    {
        Helpers::create_all_logs($vendorEmployee,'created','VendorEmployee');
    }

    /**
     * Handle the VendorEmployee "updated" event.
     *
     * @param  \App\Models\VendorEmployee  $vendorEmployee
     * @return void
     */
    public function updated(VendorEmployee $vendorEmployee)
    {
        Helpers::create_all_logs($vendorEmployee,'updated','VendorEmployee');
    }

    /**
     * Handle the VendorEmployee "deleted" event.
     *
     * @param  \App\Models\VendorEmployee  $vendorEmployee
     * @return void
     */
    public function deleted(VendorEmployee $vendorEmployee)
    {
        Helpers::create_all_logs($vendorEmployee,'deleted','VendorEmployee');
    }

    /**
     * Handle the VendorEmployee "restored" event.
     *
     * @param  \App\Models\VendorEmployee  $vendorEmployee
     * @return void
     */
    public function restored(VendorEmployee $vendorEmployee)
    {
        //
    }

    /**
     * Handle the VendorEmployee "force deleted" event.
     *
     * @param  \App\Models\VendorEmployee  $vendorEmployee
     * @return void
     */
    public function forceDeleted(VendorEmployee $vendorEmployee)
    {
        //
    }
}
