<?php

namespace App\Models;

use App\Models\Account;
use Illuminate\Database\Eloquent\Model;


class Membership extends Model
{

    protected $table = 'clients_memberships';

    /**
  * The attributes that are mass assignable.
  *
  * @var array
  */

    protected $fillable = ['id', 'first_name', 'last_name', 'parrain_id', 'phone_number', 'email', 'code', 'member_level'];

 /**
  * The attributes that should be hidden for arrays.
  *
  * @var array
  */

    protected $hidden = [];

    public function Account()
    {
        return $this->hasMany(Account::class);
    }


    public function ref () {
        return $this->where('id', $this->ref_id)->first();
    }

    public function parent () {
        return $this->where('id', $this->parent_id)->first();
    }

    public function children () {
        return $this->where('ref_id', $this->id)->get();
    }
}
