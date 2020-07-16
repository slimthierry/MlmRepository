<?php

namespace App\Jobs;

use App\Abstracts\Job;
use App\Events\TransactionCreated;
use App\Events\TransactionCreating;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;


class CreateTransaction extends Job
{

    /**
     * Create a new job instance.
     *
     * @return void
     */

     protected $transaction;

    protected $request;

    public function __construct($request)
    {
        //
        $this->request = $this->getRequestInstance($request);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        event(new TransactionCreating($this->request));

        DB::transaction(function () {
            $this->transaction = Transaction::create($this->request->all());

            // Upload attachment
            if ($this->request->file('attachment')) {
                $media = $this->getMedia($this->request->file('attachment'), 'transactions');

                $this->transaction->attachMedia($media, 'attachment');
            }

            // Recurring
            $this->transaction->createRecurring();
        });

        event(new TransactionCreated($this->transaction));

        return $this->transaction;

    }
}
