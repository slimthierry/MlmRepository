<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class DeleteTransaction implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $transaction;

    public function __construct($transaction)
    {
            $this->transaction = $transaction;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // $this->authorize();

        DB::transaction(function () {
            $this->transaction->recurring()->delete();
            $this->transaction->delete();
        });

        return true;
    }

    /**
     * Determine if this action is applicable.
     *
     * @return void
     */


}
