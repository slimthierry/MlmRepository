<?php

namespace App\Http\Controllers;

use App\Http\Resources\AccountResource;
use App\Models\Account;
use App\Models\Membership;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AccountController extends Controller
{
    private $id;
    public function __construct()
    {
        // $this->id = Account::findOrFail(1);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        //
        $accounts = Account::all();
        // foreach ($accounts as $account => $value) {
        //      $account->view_account =[
        //         'href'=>'api/account' . $account->id,
        //         'method' => 'GET',
        //     ];
        // }
        $response =[
            'msg' => 'List of all Accounts',
            'accounts' =>$accounts
        ];

        // return AccountResource::collection(Account::paginate(10));
        return response()->json($response, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

         // prepare price array
        //   for ($i=$min; $i<=$max; $i+=0.001) {
        //     array_push($data[0], 0);
        //     array_push($data[1], number_format($i, 3));
        // }

        // if (count($sales) > 0) {
        //     foreach ($sales as $sale) {
        //         $check = number_format($sale->price, 3);
        //         $index = array_search($check, $data[1]);
        //         if (isset($data[0][$index])) {
        //             $total += $sale->amount_left;
        //             $data[0][$index] = $sale->amount;
        //         }
        //     }
        // }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'id' => 'required',
            'status' => 'required|boolean',
            'balance'=>'required',
            // 'client_membership_id' =>'required',
            ]);

        $member = Membership::find($request->input('id'));
        if(!$member){
            return back()->withErrors(['member'=> 'Memmber  not found']);
        }

          // Create account
            $id =$request->input('id');
            $status =$request->boolean('0', '1');
            $balance =$request->input('balance');
            $created_at =$request->input('created_at');
            // $client_membership_id =$request->input('client_membership_id');

            $account = new Account([
                'id' => $id,
                'status'=> $status,
                'balance'=> $balance,
                'created_at'=> $created_at,
                'client_membership_id'=> $id
                ]);

                if ($account->save()) {[
                    # code...
                    'method' => 'POST',
                    'params' => 'statut, balance'
                    ];
                // if ($account->membership()->where('id', $member->id)->first()){
                //     return response()->json($message, 404);
                // };
                // $member->account()->attach($account);

                $response = [
                    'msg' => 'Member registered for account',
                    'account' => $account,
                    'member' => $member,

                    'unregister' => [
                        'href' => 'api/member/registration/' . $account->id,
                        'method' => 'DELETE',
                    ]
                ];
                return response()->json($response, 201);
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
}
