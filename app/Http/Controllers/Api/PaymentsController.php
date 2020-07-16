<?php

namespace App\Http\Controllers\APi;

use App\Abstracts\Http\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PaymentMode;

session_start();

class PaymentsController extends ApiController
{
    public function index(Request $request)
    {
        $post_data = array();
         $post_data['amount'] = '500'; # You cant not pay less than 500
         $post_data['label'] = '';
        $post_data['id'] = ''; // id must be unique

        #Start to save these value  in session to pick in success page.
        $_SESSION['payment_values']['id']=$post_data['id'];
        #End to save these value  in session to pick in success page.


        dd($_SESSION);
    }

    public function success(Request $request)
    {
        echo "Transaction is Successful";

        $mlm = new TransactionsController();
        #Start to received these value from session. which was saved in index function.
        $id = $_SESSION['payment_values']['id'];
        #End to received these value from session. which was saved in index function.

        #Check transaction status in transaction tabel against the transaction id.
        $trans_detials = DB::table('Transactions')
                            ->where('id', $id)
                            ->select('id', 'trans_status','label')->first();

        if($trans_detials->trans_status=='Pending')
        {
            $validation = $mlm->orderValidate($id, $trans_detials->label, $request->all());
            if($validation == TRUE)
            {
                /*
                Here you need to update transaction status
                in transaction table as Processing or Complete.
                Here you can also sent sms or email for successfull transaction to customer
                */

                $update_product = DB::table('Transactions')
                            ->where('id', $id)
                            ->update(['trans_status' => 'Processing']);

                echo "<br >Transaction is successfully Complete";
            }
            else
            {
                /*
                That means IPN did not work or IPN URL was not set in your merchant panel and Transation validation failed.
                Here you need to update transaction status as Failed in transaction table.
                */
                $update_product = DB::table('transactions')
                            ->where('id', $id)
                            ->update(['trans_status' => 'Failed']);
                echo "validation Fail";
            }
        }
        else if($trans_detials->trans_status=='Processing' || $trans_detials->trans_status=='Complete')
        {
            /*
             That means through IPN Transactions status already updated. Now you can just show the customer that transaction is completed. No need to udate database.
             */
            echo "Transaction is successfully Complete";
        }
        else
        {
             #That means something wrong happened. You can redirect customer to your product page.
            echo "Invalid Transaction";
        }

    }

    public function fail(Request $request)
    {
         $id = $_SESSION['payment_values']['id'];
         $trans_detials = DB::table('transactions')
                            ->where('id', $id)
                            ->select('id', 'trans_status','label')->first();

        if($trans_detials->trans_status=='Pending')
        {
            $update_product = DB::table('transactions')
                            ->where('id', $id)
                            ->update(['trans_status' => 'Failed']);
            echo "Transaction is Falied";
        }
         else if($trans_detials->trans_status=='Processing' || $trans_detials->trans_status=='Complete')
        {
            echo "Transaction is already Successful";
        }
        else
        {
            echo "Transaction is Invalid";
        }

    }

     public function cancel(Request $request)
    {
        $id = $_SESSION['payment_values']['id'];

        $trans_detials = DB::table('transactions')
                            ->where('id', $id)
                            ->select('id', 'trans_status','label')->first();

        if($trans_detials->trans_status=='Pending')
        {
            $update_product = DB::table('transactions')
                            ->where('id', $id)
                            ->update(['trans_status' => 'Canceled']);
            echo "Transaction is Cancel";
        }
         else if($trans_detials->trans_status=='Processing' || $trans_detials->trans_status=='Complete')
        {
            echo "Transaction is already Successful";
        }
        else
        {
            echo "Transaction is Invalid";
        }
    }

        public function pin(Request $request)
        {
            #Received all the payement information from the gateway
          if($request->input('id')) #Check transation id is posted or not.
          {

              $id = $request->input('id');

            #Check transaction status in transaction tabel against the transaction id
             $trans_details = DB::table('Transactions')
                                ->where('id', $id)
                                ->select('id', 'trans_status','label')->first();

                    if($trans_details->trans_status =='Pending')
                    {
                        $mlm = new TransactionsController();
                        $validation = $mlm->transValidate($id, $trans_details->label,  $request->all());
                        if($validation == TRUE)
                        {
                            /*
                            That means IPN worked. Here you need to update transaction status
                            in transaction table as Processing or Complete.
                            Here you can also sent sms or email for successfull transaction to customer
                            */
                            $update_product = DB::table('Transactions')
                                        ->where('id', $id)
                                        ->update(['trans_status' => 'Processing']);

                            echo "Transaction is successfully Complete";
                        }
                        else
                        {
                            /*
                            That means IPN worked, but Transation validation failed.
                            Here you need to update transation status as Failed in transactions table.
                            */
                            $update_product = DB::table('transactions')
                                        ->where('id', $id)
                                        ->update(['trans_status' => 'Failed']);

                            echo "validation Fail";
                        }

                    }
                    else if($trans_details->trans_status == 'Processing' || $trans_details->trans_status =='Complete')
                    {

                      #That means Transactions status already updated. No need to udate database.

                        echo "Transaction is already successfully Complete";
                    }
                    else
                    {
                       #That means something wrong happened. You can redirect customer to your product page.

                        echo "Invalid Transaction";
                    }
            }
            else
            {
                echo "Inavalid Data";
            }
    }

}

