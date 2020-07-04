<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Exceptions\InvalidAccountException;
use App\Exceptions\InsufficientBalanceException;
// use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use App\Models\Account;

class TransactionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $count;

    public function index()
    {

        $response =  Transaction::all();

        return response()->json($response, 200);


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

        $transaction = Transaction::all();

        $this->validate($request,[
                'amount'=>'required',
                'type'=>'required'
            ]);
            $id =$request->input('id');
            $amount =$request->input('amount');
            $balance_before =$request->input('balance_before');
            $balance_after =$request->input('balance_after');
            $label =$request->input('label');
            $type =$request->input('type');
            $id =$request->input('id');
            // $payement_mode_id = $request->input('id');


             // Create Transaction
        $transaction = new Transaction([
            'id' => $id,
            'amount' => $amount,
            'balance_before' => $balance_before,
            'balance_after' => $balance_after,
            'label' => $label,
            'type'=> $type,
            'account_id' => $id,
            'payement_mode_id' => $id,
            ]);

            //  Update account balance
            // $transaction->balance_after = $transaction->balance_before ;
            // $transaction->save();

        // );

        if ($transaction->save()) {[
            # code...
            'method' => 'POST',
            'params' => 'amount, type'
        ];
        $response=[
            'msg' => 'sss created',
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
    public function destroy($id)
    {
        //
    }

   public function getTransactionPassword($length)

    {
            $count= 0;
            for ($i = 0; $i < $length; $i++)
                $key = [
                    'cost' => 12,
                ];
                $randum_id = password_hash(PASSWORD_BCRYPT, $key);
                    //$randum_id = $key;
                $results = Transaction::select('select *  from transactions where transaction_password =".$key" ');

                $this->get_row($results);
                $count = $this->num_rows;
            if (!$count){
                return $key;
            }else{
                $this->getRandTransPasscode($length);
            }
        }


    public  static function getTransactionReport( $id, int $transId = NULL)
        {
            # code..
            $p = Transaction::with('')
                ->with('transaction_type')
                ->with('income_source')
                ->with('currency')
                ->orderBy('created_at' , '       desc');
            if (!is_null($transId)){
                $p->where('id', $transId);
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

    public function transfer($id,  $amount = 0, $balance_before = 0, $balance_after = 0 )
        {
            // F
            $outQuery = Transaction::whereId($id);
            if (!$outQuery->exists()) {
                throw new InvalidAccountException();
            }

            //   T
            $inQuery = Transaction::whereId($id);
            if (! $inQuery->exists()) {
                throw new InvalidAccountException();
            }

            do
            {
                    $outAccount = $outQuery->first();
                    if ($outAccount->balance < $amount)
                    {
                        throw new InsufficientBalanceException();
                    }
                    // F
                    $updated = Transaction::whereId($id)
                    ->where('updated_at', '=', $outAccount->updated_at)
                    ->update(
                                    ['balance_before' => $outAccount->balance],
                                    ['amount' => $outAccount->balance - $amount],
                                    ['balance_after' => $outAccount->balance]
                                );

            } while (! $updated);

            do
            {
                //   T
                    $inAccount = $inQuery->first();
                    $updated = Transaction::whereId($id)
                    ->where('updated_at', '=', $inAccount->updated_at)
                    ->update(
                                    ['balance_before' => $inAccount->balance],
                                    ['amount' => $inAccount->balance + $amount],
                                    ['balance_after' => $inAccount->balance]
                                );

            } while (! $updated);

            $transaction = new Transaction();
            $transaction->id                             = $id;
            $transaction->amount                   = $amount;
            $transaction->balance_before       = $balance_before;
            $transaction->balance_after          = $balance_after;
            $transaction->save();
            }
    }

