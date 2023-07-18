<?php

namespace App\Observers;

use App\Models\AdminRole;
use App\CentralLogics\Helpers;

class AdminRoleObserver
{
    /**
     * Handle the AdminRole "created" event.
     *
     * @param  \App\Models\AdminRole  $adminRole
     * @return void
     */
    public function created(AdminRole $adminRole)
    {
        Helpers::create_all_logs($adminRole,'created','AdminRole');

    }

    /**
     * Handle the AdminRole "updated" event.
     *
     * @param  \App\Models\AdminRole  $adminRole
     * @return void
     */
    public function updated(AdminRole $adminRole)
    {
        Helpers::create_all_logs($adminRole,'created','AdminRole');

    }

    /**
     * Handle the AdminRole "deleted" event.
     *
     * @param  \App\Models\AdminRole  $adminRole
     * @return void
     */
    public function deleted(AdminRole $adminRole)
    {
        Helpers::create_all_logs($adminRole,'created','AdminRole');

    }

    /**
     * Handle the AdminRole "restored" event.
     *
     * @param  \App\Models\AdminRole  $adminRole
     * @return void
     */
    public function restored(AdminRole $adminRole)
    {
        //
    }

    /**
     * Handle the AdminRole "force deleted" event.
     *
     * @param  \App\Models\AdminRole  $adminRole
     * @return void
     */
    public function forceDeleted(AdminRole $adminRole)
    {
        //
    }
}
