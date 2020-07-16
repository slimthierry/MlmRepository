<?php

namespace App\Jobs;

use App\Models\Account;
use Illuminate\Support\Facades\DB;
use App\Abstracts\Job;


// use App\Traits\Jobs;
// use App\Traits\Relationships;
// use App\Traits\Uploads;


class CreateAccount extends Job
{

     protected $account;

    protected $request;

    /**
     * Create a new job instance.
     *
     * @return void
     */
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
        DB::transaction(function () {
            $this->account = Account::create($this->request->all());

            // Set default account
            if ($this->request['default_account']) {
                setting()->set('default.account', $this->account->id);
                setting()->save();
            }
        });

        return $this->account;
    }

}
