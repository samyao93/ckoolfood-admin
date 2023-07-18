<?php

namespace App\Observers;

use App\CentralLogics\Helpers;
use App\Models\Wishlist;


class WishlistObserver
{
    /**
     * Handle the Wishlist "created" event.
     *
     * @param  \App\Models\Wishlist  $wishlist
     * @return void
     */
    public function created(Wishlist $wishlist)
    {
        Helpers::create_all_logs($wishlist,'created','Wishlist');
    }

    /**
     * Handle the Wishlist "updated" event.
     *
     * @param  \App\Models\Wishlist  $wishlist
     * @return void
     */
    public function updated(Wishlist $wishlist)
    {
        Helpers::create_all_logs($wishlist,'updated','Wishlist');
    }

    /**
     * Handle the Wishlist "deleted" event.
     *
     * @param  \App\Models\Wishlist  $wishlist
     * @return void
     */
    public function deleted(Wishlist $wishlist)
    {
        Helpers::create_all_logs($wishlist,'deleted','Wishlist');
    }

    /**
     * Handle the Wishlist "restored" event.
     *
     * @param  \App\Models\Wishlist  $wishlist
     * @return void
     */
    public function restored(Wishlist $wishlist)
    {
        //
    }

    /**
     * Handle the Wishlist "force deleted" event.
     *
     * @param  \App\Models\Wishlist  $wishlist
     * @return void
     */
    public function forceDeleted(Wishlist $wishlist)
    {
        //
    }
}
