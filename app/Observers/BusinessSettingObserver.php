<?php

namespace App\Observers;

use App\Models\BusinessSetting;
use App\CentralLogics\Helpers;

class BusinessSettingObserver
{
    /**
     * Handle the BusinessSetting "created" event.
     *
     * @param  \App\Models\BusinessSetting  $businessSetting
     * @return void
     */
    public function created(BusinessSetting $businessSetting)
    {
        Helpers::create_all_logs($businessSetting,'created','BusinessSetting');

    }

    /**
     * Handle the BusinessSetting "updated" event.
     *
     * @param  \App\Models\BusinessSetting  $businessSetting
     * @return void
     */
    public function updated(BusinessSetting $businessSetting)
    {
        // info('------------------------dsfgsdgvsduhvnkdsgvfdsjhyf hjsdgfvkjsdbfsdbgfki ');
        Helpers::create_all_logs($businessSetting,'updated','BusinessSetting');
    }

    /**
     * Handle the BusinessSetting "deleted" event.
     *
     * @param  \App\Models\BusinessSetting  $businessSetting
     * @return void
     */
    public function deleted(BusinessSetting $businessSetting)
    {
        Helpers::create_all_logs($businessSetting,'deleted','BusinessSetting');
    }

    /**
     * Handle the BusinessSetting "restored" event.
     *
     * @param  \App\Models\BusinessSetting  $businessSetting
     * @return void
     */
    public function restored(BusinessSetting $businessSetting)
    {
        //
    }

    /**
     * Handle the BusinessSetting "force deleted" event.
     *
     * @param  \App\Models\BusinessSetting  $businessSetting
     * @return void
     */
    public function forceDeleted(BusinessSetting $businessSetting)
    {
        //
    }
}
