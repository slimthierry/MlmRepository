<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Exceptions\AccountAlreadyExists;
use App\Models\Account;

trait AccountTrait
{
    public function account(): MorphOne
    {
        return $this->morphOne(Account::class, 'morphed');
    }

    /**
     * Initialize a account for a given model object
     *
     * @param null|string $currency_code
     * @return mixed
     * @throws AccountAlreadyExists
     */
    public function initAccount()
        // ?string $currency_code = 'CFA'
    {
        if (!$this->account) {
            $account = new Account();
            // $account->currency = $currency_code;
            $account->balance = 0;
            return $this->Account()->save($account);
        }
        throw new AccountAlreadyExists;
    }
}
