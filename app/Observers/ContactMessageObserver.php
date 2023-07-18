<?php

namespace App\Observers;

use App\Models\ContactMessage;
use App\CentralLogics\Helpers;

class ContactMessageObserver
{
    /**
     * Handle the ContactMessage "created" event.
     *
     * @param  \App\Models\ContactMessage  $contactMessage
     * @return void
     */
    public function created(ContactMessage $contactMessage)
    {
        Helpers::create_all_logs($contactMessage,'created','ContactMessage');
    }

    /**
     * Handle the ContactMessage "updated" event.
     *
     * @param  \App\Models\ContactMessage  $contactMessage
     * @return void
     */
    public function updated(ContactMessage $contactMessage)
    {
        Helpers::create_all_logs($contactMessage,'updated','ContactMessage');
    }

    /**
     * Handle the ContactMessage "deleted" event.
     *
     * @param  \App\Models\ContactMessage  $contactMessage
     * @return void
     */
    public function deleted(ContactMessage $contactMessage)
    {
        Helpers::create_all_logs($contactMessage,'deleted','ContactMessage');
    }

    /**
     * Handle the ContactMessage "restored" event.
     *
     * @param  \App\Models\ContactMessage  $contactMessage
     * @return void
     */
    public function restored(ContactMessage $contactMessage)
    {
        //
    }

    /**
     * Handle the ContactMessage "force deleted" event.
     *
     * @param  \App\Models\ContactMessage  $contactMessage
     * @return void
     */
    public function forceDeleted(ContactMessage $contactMessage)
    {
        //
    }
}
