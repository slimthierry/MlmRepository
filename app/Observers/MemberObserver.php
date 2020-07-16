<?php

namespace App\Observers;

use App\Models\Membership;

class MemberObserver
{
    /**
     * Handle the membership "created" event.
     *
     * @param  \App\Membership  $membership
     * @return void
     */
    public function created(Membership $membership)
    {
        //
    }

    /**
     * Handle the membership "updated" event.
     *
     * @param  \App\Membership  $membership
     * @return void
     */
    public function updated(Membership $membership)
    {
        //
    }

    /**
     * Handle the membership "deleted" event.
     *
     * @param  \App\Membership  $membership
     * @return void
     */
    public function deleted(Membership $membership)
    {
        //
    }

    /**
     * Handle the membership "restored" event.
     *
     * @param  \App\Membership  $membership
     * @return void
     */
    public function restored(Membership $membership)
    {
        //
    }

    /**
     * Handle the membership "force deleted" event.
     *
     * @param  \App\Membership  $membership
     * @return void
     */
    public function forceDeleted(Membership $membership)
    {
        //
    }
}
