<?php

namespace App\Http\Controllers\Api;

use App\Abstracts\Http\ApiController;
use Illuminate\Http\Request;
use App\Exceptions\InvalidAccountException;
use App\Exceptions\InsufficientBalanceException;
use App\Jobs\DeleteTransaction;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use App\Models\Account;
use App\Models\Membership;

class TransactionsController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {

        // $transactions =  Transaction::all();

        // return response()->json($response, 200);

        $accounts = Account::enable()->orderBy('id')->pluck('id');

        $types = collect(['expense' => 'Expense', 'income' => 'Income'])
            ->prepend(trans('general.all_type', ['type' => trans_choice('general.types', 2)]), '');

        $request_type = !request()->has('type') ? ['income', 'expense'] : request('type');

        $transactions = Transaction::with('account')->collect(['paid_at'=> 'desc']);

        $transactions = Transaction::with('account')->all(['created_at'=> 'desc']);

        // $transactions =[
        //     'msg' => 'List of all Accounts',
        //     'membership' =>$transactions
        // ];
        // return response()->json($transactions);

        // dd($transactions);

        // return view('ransactions.index', compact('transactions', 'accounts', 'types'));


    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // private $transaction = 0;

    public function store(Request $request)
    {

        // $transaction = Transaction::all();

        $this->validate($request,[
                'account_id'=>'required',
                'client_membership_id'=>'required',
                'currency'=>'required',
                'type'=>'required',
                'payment_method'=>'required'
                ]);


            // $id =$request->input('id');
            $account_id =$request->input('account_id');
            $client_membership_id = $request->input('client_membership_id');
            $debit =$request->input('debit');
            $credit =$request->input('credit');
            $currency =$request->input('currency');
            $type =$request->input('type');
            $balance_before =$request->input('balance_before');
            $balance_after =$request->input('balance_after');
            $label =$request->input('label');
            $trans_status = $request->input('trans_status');
            $payment_method =$request->input('payment_method');


             // Create Transaction
        $transaction = new Transaction([
            // 'id' => $id,
            'account_id' => $account_id,
            'client_membership_id' => $client_membership_id,
            'debit' => $debit,
            'credit' => $credit,
            'currency' => $currency,
            'balance_before' => $balance_before,
            'balance_after' => $balance_after,
            'label' => $label,
            'trans_status'=> $trans_status,
            'type'=> $type,
            'payment_method' => $payment_method,
            ]);



            //  Update account balance
            $transaction->balance_after = $transaction->balance_before ;
            $transaction->save();

        // );

        if ($transaction->save()) {[
            # code...
            'method' => 'POST',
            'params' => 'payment_method, type'
        ];
        $response=[
            'msg' => 'Transaction created',
            'transaction' => $transaction
        ];
        return response()->json($response , 201);
            }
        $response =
            [
                'msg' => 'An error occured'
            ];
        return response()->json($response , 404);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        $response = $this->jsonDispatch(new DeleteTransaction($transaction));

        $response['redirect'] = url()->previous();

        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => trans_choice('general.transactions', 1)]);

            // flash($message)->success();
        } else {
            $message = $response['message'];

            // flash($message)->error();
        }

        return response()->json($response);
    }


    public  static function getTransactionReport( $id)
        {
            # code..
            $p = Transaction::with('')
                ->with('type')
                ->with('currency')
                ->orderBy('created_at' , 'desc');
            if (!is_null($id)){
                $p->where('id', $id);
            }
            $transactions = $p->get();
            $results = [];

            foreach($transactions as $row){

            }
            return $results;
        }

        /**
         * Cette function nous permet de faire les differents transactions
         Les outs s'occuppent des operations de sortie du compte
         Les in s'occupent des operations s'ajout sur le compte
         */


    public function getOutAccount(){

    }

    // public function transfer($id,  $amount = 0, $balance_before = 0, $balance_after = 0 )
    //     {


    public function transfer(Request $request)
    {
        $this->validate($request, [
            'account' => 'required',
            'amount' => 'required',
        ]);

        $account = $request->input('account');
        $account = $this->account()->id;
        $amount = $request->input('amount');

        $balance = $request->account()->balance;


        if($balance-$amount > 0 ){
            if(DB::table('accounts')->where('id', $account)->exists()){

                $id = Account::where('id', $account)->value('id');
                $balance= Account::find($id)->balance;

                DB::table('accounts')
                    ->where('id', $account)
                    ->update(['balance' => $balance+$amount]);

                DB::table('accounts')
                    ->where('id', $account)
                    ->update(['balance' => $balance-$amount]);

                DB::table('transfers')->insert(
                ['receiver_account' => $account, 'sender_account' => $account, 'amount' => $amount]
                );

            }else echo "Account doesn't exist!";
        }else echo "Not enough money to transfer!";

        

    }
}
            // F
    //         $outQuery = Transaction::whereId($id);
    //         if (!$outQuery->exists()) {
    //             throw new InvalidAccountException();
    //         }

    //         //   T
    //         $inQuery = Transaction::whereId($id);
    //         if (! $inQuery->exists()) {
    //             throw new InvalidAccountException();
    //         }

    //         do
    //         {
    //                 $outAccount = $outQuery->first();
    //                 if ($outAccount->label > $amount)

    //                 {
    //                     throw new InsufficientBalanceException();
    //                 }
    //                 // F
    //                 $updated = Transaction::whereId($id)
    //                 ->where('updated_at', '=', $outAccount->updated_at)
    //                 ->update(
    //                                 ['balance_before' => $outAccount->balance],
    //                                 ['amount' => $outAccount->label - $amount],
    //                                 ['balance_after' => $outAccount->balance]
    //                             );


    //         } while (! $updated);

    //         do
    //         {
    //             //   T
    //                 $inAccount = $inQuery->first();
    //                 $updated = Transaction::whereId($id)
    //                 ->where('updated_at', '=', $inAccount->updated_at)
    //                 ->update(
    //                                 ['balance_before' => $inAccount->balance],
    //                                 ['amount' => $inAccount->label + $amount],
    //                                 ['balance_after' => $inAccount->balance]
    //                             );

    //         } while (! $updated);


    //         $transaction = new Transaction();
    //         $transaction->id                             = $id;
    //         // $transaction->amount                   = $amount;
    //         $transaction->balance_before       = $balance_before;
    //         $transaction->balance_after          = $balance_after;
    //         $transaction->save();
            // }
    // }

    // STRICT_TRANS_TABLES

