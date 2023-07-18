<?php

namespace App\Observers;

use App\CentralLogics\Helpers;
use App\Models\UserInfo;


class UserInfoObserver
{
    /**
     * Handle the UserInfo "created" event.
     *
     * @param  \App\Models\UserInfo  $userInfo
     * @return void
     */
    public function created(UserInfo $userInfo)
    {
        Helpers::create_all_logs($userInfo,'created','UserInfo');
    }

    /**
     * Handle the UserInfo "updated" event.
     *
     * @param  \App\Models\UserInfo  $userInfo
     * @return void
     */
    public function updated(UserInfo $userInfo)
    {
        Helpers::create_all_logs($userInfo,'updated','UserInfo');
    }

    /**
     * Handle the UserInfo "deleted" event.
     *
     * @param  \App\Models\UserInfo  $userInfo
     * @return void
     */
    public function deleted(UserInfo $userInfo)
    {
        Helpers::create_all_logs($userInfo,'deleted','UserInfo');
    }

    /**
     * Handle the UserInfo "restored" event.
     *
     * @param  \App\Models\UserInfo  $userInfo
     * @return void
     */
    public function restored(UserInfo $userInfo)
    {
        //
    }

    /**
     * Handle the UserInfo "force deleted" event.
     *
     * @param  \App\Models\UserInfo  $userInfo
     * @return void
     */
    public function forceDeleted(UserInfo $userInfo)
    {
        //
    }
}
