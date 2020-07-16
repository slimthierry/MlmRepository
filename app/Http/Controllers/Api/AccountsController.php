<?php

namespace App\Http\Controllers\Api;

use App\Abstracts\Http\ApiController;

use Exception;
use Throwable;
use App\Models\Account;
use App\Jobs\UpdateAccount;
use App\Models\Transaction;
use App\Models\Membership;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Money\Money;
use Money\Currency;
use Carbon\Carbon;

/**
 * @property    Money $balance
 *
 * @property    Carbon $updated_at
 * @property    Carbon $created_at
 * @property    Carbon $created_at
 */


class AccountsController extends ApiController
{

    // public function __construct()
    // {
    //     $this->id = Account::findOrFail(1);
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        //
        // $accounts = Account::all();
        $accounts = Account::all();
        $accounts =[
            'msg' => 'List of all Accounts',
            'accounts' =>$accounts
        ];

        return response()->json($accounts);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function jsonDispatch($job)
    {
        try {
            $data = $this->dispatch($job);

            $response = [
                'success' => true,
                'error' => false,
                'data' => $data,
                'message' => '',
            ];
        }
         catch (Exception | Throwable $e) {

            $response = [
                'success' => false,
                'error' => true,
                'data' => null,
                'message' => $e->getMessage(),
            ];
        }

        return $response;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     *
     * @return Response
     */


    public function store(Request $request, Membership $member)
    {
        $validator = $this->validate($request,[
            // 'id' => 'required',
            'enabled' => 'required|boolean',
            'balance'=>'required',
            ]);

        //   Create account
            $id =$request->input('id');
            $enabled =$request->boolean('0', '1');
            $client_membership_id =$request->input('client_membership_id');
            $payment_mode_id =$request->input('payment_mode_id');
            $balance=$request->input('balance');
            $enabled =$request->input('enabled');

            $account = new Account([
                'id' => $id,
                'enabled'=> $enabled,
                'client_membership_id'=> $client_membership_id,
                'payment_mode_id'=> $payment_mode_id,
                'balance' => $balance,
                'enabled'=> $enabled,

                ]);

                if ($account->save()) {[
                    # code...
                    'method' => 'POST',
                    'params' => 'enabled, balance'

                ];

                $response = [
                    'msg' => 'Member registered for account',
                    'account' => $account,
                    'member' => $member,

                    'unregister' => [
                        'href' => 'api/member/registration/' . $account->id,
                        'method' => 'DELETE',
                    ]
                ];

                $response=[
                    'msg' => 'Account created',
                    'account' => $account
                ];
             return response()->json($response, 201);
            }
            $response =
            [
                'msg' => 'An error occured'
            ];
        return response()->json($response , 404);

        if ($enabled == 0) {
            # code...
            return 'Your account not enabled';
        }else {

            return 'Your acount is enabled';
        }

        }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // return redirect()->route('accounts.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Account $account, Request $request)
    {
        $response = $this->jsonDispatch(new UpdateAccount($account, $request));

        if ($response['success']) {
            $response['redirect'] = route('accounts.index');

            $message = trans('messages.success.updated', ['type' => $account->id]);

            $request->session()->flash('success', $message);

        } else {
            $response['redirect'] = route('accounts.edit', $account->id);

            $message = $response['message'];

            $request->session()->flash('error', $message);

        }

        return response()->json($response);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $account = Account::findOrFail($id);
        $account->member()->detach();

        $response = [
            'msg' => 'Member unregistered for account',
            'account' => $account,
            'member' => 'dd',
            'register' => [
                'href' => 'api/member/account/registration',
                'method' => 'POST',
                'params' => 'id' , 'id'
            ]
        ];
        return response()->json($response, 200);
    }

    public function enable(Account $account)
    {
        $response = $this->jsonDispatch(new UpdateAccount($account, request()->merge(['enabled' => 1])));

        if ($response['success']) {
            $response['message'] = trans('messages.success.enabled', ['type' => $account->id]);
        }

        return response()->json($response);
    }

    public function disable(Account $account)
    {
        $response = $this->jsonDispatch(new UpdateAccount($account, request()->merge(['enabled' => 0])));

        if ($response['success']) {
            $response['message'] = trans('messages.success.disabled', ['type' => $account->id]);
        }

        return response()->json($response);
    }


    // public function resetCurrentBalances()
    // {
    //     $this->balance = $this->getBalance();
    //     $this->save();
    //     return $this->balance;
    // }

    /**
     * @param Money|float $value
     */
    protected function getBalanceAttribute($value)
    {
        // return new Money($value, new Currency($this->currency));
    }

      /**
     * @param Money|float $value
     */
    // protected function setBalanceAttribute($value): void
    // {
    //     $value = is_a($value, Money::class)
    //         ? $value
    //         : new $this->Money($value);
    //     $this->attributes['balance'] = $value ? (int)$value->getAmount() : null;
    // }


    /**
     * Get the debit only balance of the account based on a given date.
     */
    public function getDebitBalanceOn(Carbon $date): Money
    {
        $balance = $this->Transac()->where('created_at', '<=', $date)->sum('debit') ?: 0;
        return new $this->Money($balance);

    }

    /**
     * Get the credit only balance of the account based on a given date.
     */
    public function getCreditBalanceOn(Carbon $date): Money
    {
        $balance = $this->Transac()->where('created_at', '<=', $date)->sum('credit') ?: 0;
        return new $this->Money($balance);
    }

    /**
     * Get the balance of the account based on a given date.
     */
    public function getBalanceOn(Carbon $date): Money
    {
        return $this->getCreditBalanceOn($date)->subtract($this->getDebitBalanceOn($date));
    }

    /**
     * Get the balance of the account as of right now, excluding future transactions.
     */
    public function getCurrentBalance(): Money
    {
        return $this->getBalanceOn(Carbon::now());
    }

    /**
     * Get the balance of the account.  This "could" include future dates.
     */
    public function getBalance()
    {
        if ($this->Transac()->count() > 0) {
            $balance = $this->Transac()->sum('credit') - $this->Transac()->sum('debit');
        } else {
            $balance = 0;
        }

        return new Money($balance, new Currency($this->currency));
    }

    public function credit(
        $value,
        Carbon $created_at = null
    ): Transaction {
        $value = is_a($value, Money::class)
            ? $value
            : new Money($value, new Currency($this->currency));
        return $this->postTrans($value, null, $created_at);
    }

    public function debit(
        $value,
        Carbon $created_at = null
    ): Transaction {
        $value = is_a($value, Money::class)
            ? $value
            : new Money($value, new Currency($this->currency));
        return $this->postTrans(null, $value, $created_at);
    }

    // private function postTrans(
    //     Money $credit = null,
    //     Money $debit = null,
    //     Carbon $created_at = null
    // ): Transaction {
    //     $transaction = new Transaction;
    //     $transaction->credit = $credit ? $credit->getAmount() : null;
    //     $transaction->debit = $debit ? $debit->getAmount() : null;
    //     $currency_code = $credit
    //         ? $credit->getCurrency()->getCode()
    //         : $debit->getCurrency()->getCode();
    //     $transaction->currency = $currency_code;
    //     $transaction->created_at = $created_at ?: Carbon::now();
    //     $this->transactions()->save($transaction);
    //     return $transaction;
    // }

    public function transac( Money $credit = null, Money $debit = null, Carbon $created_at = null)
    {
        $transaction = new Transaction;
        $transaction->credit = $credit ? $credit->getAmount() : null;
        $transaction->debit = $debit ? $debit->getAmount() : null;
        // $currency_code = $credit
            // ? $credit->getCurrency()->getCode()
            // : $debit->getCurrency()->getCode();
        // $transaction->currency = $currency_code;
        $transaction->created_at = $created_at ?: Carbon::now();
        $this->Transac()->save();
        return $transaction;
    }

    public function enabled(Account $account)
    {
        $response = $this->jsonDispatch(new UpdateAccount($account, request()->merge(['enabled' => 1])));

        if ($response['success']) {
            $response['message'] = trans('messages.success.enabled', ['type' => $account->id]);
        }

        return response()->json($response);
    }

    /**
     * Disable the specified resource.
     *
     * @param  Account $account
     *
     * @return Response
     */
    public function disabled(Account $account)
    {
        $response = $this->jsonDispatch(new UpdateAccount($account, request()->merge(['enabled' => 0])));

        if ($response['success']) {
            $response['message'] = trans('messages.success.disabled', ['type' => $account->id]);
        }
        return response()->json($response);
    }


}
