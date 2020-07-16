<?php

namespace App\Providers;

use App\Models\Membership;
use Illuminate\Support\ServiceProvider;
use App\Observers\MemberObserver;

class UploadedEventServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Membership::observe(MemberObserver::class);


    }
}
