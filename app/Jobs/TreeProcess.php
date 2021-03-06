<?php

namespace App\Jobs;

// use App\Http\Controllers\MemberShipController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Membership;

class TreeProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $member;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($member)
    {
        //
        $this->membership= $member;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $repo = new MemberShip();
        $repo->save($this->member);
    }
}
