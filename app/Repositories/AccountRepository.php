<?php

namespace App\Repositories;

use App\Models\Account;
use Money\Money;


/**
 * Class AccountRepository
 * @package App\Repositories
 *
 * @method Account findWithoutFail($id, $columns = ['*'])
 * @method Account find($id, $columns = ['*'])
 * @method Account first($columns = ['*'])
*/
class AccountRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'client_membership_id',
        'balance',
        'debit',
        'credit',
        'enabled',
        'paid',
    ];


    protected $model, $adminFee;
    protected $allowedFields = [];
    protected $booleanFields = [];


    /**
     * Configure the Model
     **/
    public function model()
    {
        return Account::class;
    }

    public function __construct() {
        $this->model = new Account();
    }

    protected function saveModel($model, $data) {
        foreach ($data as $k=>$d) {
            $model->{$k} = $d;
        }
        $model->save();
        return $model;
    }

    public function store($data) {
        $model = $this->saveModel(new $this->model, $data);
        return $model;
    }

    public function update($model, $data) {
        $model = $this->saveModel($model, $data);
        return $model;
    }

    public function getAllowedFields () {
        return $this->allowedFields;
    }

    public function getBooleanFields () {
        return $this->booleanFields;
    }

    public function findById ($id) {
        return $this->model->where('id', $id)->first();
    }

    /**
     * All Withdraws - DataTable
     * @param  boolean $table
     * @return object
     */
    public function findAll ($table=false) {
        if (!$table) return $this->model->all();
        else {
            return Account::eloquent($this->model->query())
                ->addColumn('action', function ($model) {
                })
                ->editColumn('status', function ($model) {
                    if ($model->status == 'done') return '<label class="label label-success">DONE</label>';
                    elseif ($model->status == 'reject') return '<label class="label label-danger">REJECT</label>';
                    else return '<label class="label label-default">PROCESS</label>';
                })
                ->editColumn('admin', function ($model) {
                    return number_format($model->admin, 2);
                })
                ->editColumn('amount', function ($model) {
                    return number_format($model->amount, 2);
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
    }

    /**
     * Make Withdraw - Member
     * @param  App\Models\Member $member [description]
     * @param  decimal $amount [description]
     * @return [type]         [description]
     */
    // public function makeWithdraw ($member, $amount) {



        // if ($amount < 300 || ($amount % 100) != 0) {
        //     throw new \Exception(\Lang::get('error.withdrawAmountError'), 1);
        //     return false;
        // }
        // $wallet = $member->wallet;
        // if ($wallet->lock_cash) {
        //     throw new \Exception(\Lang::get('error.cashWalletLock'), 1);
        //     return false;
        // }
        // $adminFee = ($this->adminFee / 100) * $amount;
        // $wdAmount = $amount + $adminFee;

        // if ($wdAmount > $wallet->cash_point) {
        //     throw new \Exception(\Lang::get('error.cashNotEnough'), 1);
        //     return false;
        // }

        // $this->saveModel($this->model, [
        //     'member_id' =>  $member->id,
        //     'username'  =>  $member->username,
        //     'amount'    =>  $wdAmount,
        //     'admin'     =>  $adminFee,
        //     'status'    =>  'process'
        // ]);

        // $wallet->cash_point -= $wdAmount;
        // $wallet->save();
        // return true;

    // }

    /**
     * Account List - DataTable
     * @param  App\Models\Member $member [description]
     * @return [type]         [description]
     */
    public function getList ($account) {
        return Account::eloquent($this->model->where('account_id', $account->id))
            ->editColumn('status', function ($model) {
                if ($model->status == 'done') return '';
                elseif ($model->status == 'reject') return '';
                else return '' ;
            })
            ->editColumn('amount', function ($model) {
                return number_format($model->amount, 2);
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

}

