<?php

namespace App\Observers;

use App\CentralLogics\Helpers;
use App\Models\ItemCampaign;


class ItemCampaignObserver
{
    /**
     * Handle the ItemCampaign "created" event.
     *
     * @param  \App\Models\ItemCampaign  $itemCampaign
     * @return void
     */
    public function created(ItemCampaign $itemCampaign)
    {
        Helpers::create_all_logs($itemCampaign,'created','ItemCampaign');
    }

    /**
     * Handle the ItemCampaign "updated" event.
     *
     * @param  \App\Models\ItemCampaign  $itemCampaign
     * @return void
     */
    public function updated(ItemCampaign $itemCampaign)
    {
        Helpers::create_all_logs($itemCampaign,'updated','ItemCampaign');
    }

    /**
     * Handle the ItemCampaign "deleted" event.
     *
     * @param  \App\Models\ItemCampaign  $itemCampaign
     * @return void
     */
    public function deleted(ItemCampaign $itemCampaign)
    {
        Helpers::create_all_logs($itemCampaign,'deleted','ItemCampaign');
    }

    /**
     * Handle the ItemCampaign "restored" event.
     *
     * @param  \App\Models\ItemCampaign  $itemCampaign
     * @return void
     */
    public function restored(ItemCampaign $itemCampaign)
    {
        //
    }

    /**
     * Handle the ItemCampaign "force deleted" event.
     *
     * @param  \App\Models\ItemCampaign  $itemCampaign
     * @return void
     */
    public function forceDeleted(ItemCampaign $itemCampaign)
    {
        //
    }
}
