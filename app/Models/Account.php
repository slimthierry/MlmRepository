<?php

namespace App\Models;

use App\Models\Membership;

use App\Models\Transaction;
// use Laravel\Paddle\Billable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Account extends Model
{
    //
    protected $table= 'Accounts';

    protected $fillable =['id', 'status', 'balance', 'created_at', 'client_membership_id'];

    protected $hibben =[];

    public function Membership(){
        return $this->belongsTo(Membership::class);
      }

      public function Transaction(){
        return $this->hasMany(Transaction::class);
      }

      public function isEnable()
      {
          return Cache::has('account-is-enable' .$this->id);
      }
}
