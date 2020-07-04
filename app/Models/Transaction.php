<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Account;

class Transaction extends Model
{

    protected $table= 'Transactions';

    protected $fillable = ['id','balance_before', 'balance_after', 'label', 'type', 'amount' , 'account_id', 'payement_mode_id', 'created_at'];

    public function Account()
    {
        return $this->belongsTo(Account::class);
    }
}
