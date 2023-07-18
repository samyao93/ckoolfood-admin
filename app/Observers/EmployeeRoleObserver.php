<?php

namespace App\Observers;

use App\CentralLogics\Helpers;
use App\Models\EmployeeRole;


class EmployeeRoleObserver
{
    /**
     * Handle the EmployeeRole "created" event.
     *
     * @param  \App\Models\EmployeeRole  $employeeRole
     * @return void
     */
    public function created(EmployeeRole $employeeRole)
    {
        Helpers::create_all_logs($employeeRole,'created','EmployeeRole');
    }

    /**
     * Handle the EmployeeRole "updated" event.
     *
     * @param  \App\Models\EmployeeRole  $employeeRole
     * @return void
     */
    public function updated(EmployeeRole $employeeRole)
    {
        Helpers::create_all_logs($employeeRole,'updated','EmployeeRole');
    }

    /**
     * Handle the EmployeeRole "deleted" event.
     *
     * @param  \App\Models\EmployeeRole  $employeeRole
     * @return void
     */
    public function deleted(EmployeeRole $employeeRole)
    {
        Helpers::create_all_logs($employeeRole,'deleted','EmployeeRole');
    }

    /**
     * Handle the EmployeeRole "restored" event.
     *
     * @param  \App\Models\EmployeeRole  $employeeRole
     * @return void
     */
    public function restored(EmployeeRole $employeeRole)
    {
        //
    }

    /**
     * Handle the EmployeeRole "force deleted" event.
     *
     * @param  \App\Models\EmployeeRole  $employeeRole
     * @return void
     */
    public function forceDeleted(EmployeeRole $employeeRole)
    {
        //
    }
}
