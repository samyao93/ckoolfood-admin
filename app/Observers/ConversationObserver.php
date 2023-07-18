<?php

namespace App\Observers;

use App\Models\Conversation;
use App\CentralLogics\Helpers;

class ConversationObserver
{
    /**
     * Handle the Conversation "created" event.
     *
     * @param  \App\Models\Conversation  $conversation
     * @return void
     */
    public function created(Conversation $conversation)
    {
        Helpers::create_all_logs($conversation,'created','Conversation');
    }

    /**
     * Handle the Conversation "updated" event.
     *
     * @param  \App\Models\Conversation  $conversation
     * @return void
     */
    public function updated(Conversation $conversation)
    {
        Helpers::create_all_logs($conversation,'updated','Conversation');
    }

    /**
     * Handle the Conversation "deleted" event.
     *
     * @param  \App\Models\Conversation  $conversation
     * @return void
     */
    public function deleted(Conversation $conversation)
    {
        Helpers::create_all_logs($conversation,'deleted','Conversation');
    }

    /**
     * Handle the Conversation "restored" event.
     *
     * @param  \App\Models\Conversation  $conversation
     * @return void
     */
    public function restored(Conversation $conversation)
    {
        //
    }

    /**
     * Handle the Conversation "force deleted" event.
     *
     * @param  \App\Models\Conversation  $conversation
     * @return void
     */
    public function forceDeleted(Conversation $conversation)
    {
        //
    }
}
