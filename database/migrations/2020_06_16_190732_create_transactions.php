<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
        $table->id();
        // $table->unsignedInteger('account_id');
        $table->unsignedInteger('client_membership_id');
        // $table->string('currency', 3);
        $table->string('balance_before')->nullable()->default(0);
        $table->string('balance_after')->nullable()->default(0);
        $table->decimal('debit', 16, 4)->nullable();
        $table->decimal('credit', 16, 4)->nullable();
        // $table->enum('type', ['mobile', 'web']);
        // $table->decimal('label', 16,4)->nullable(0);
        // $table->decimal('amount', 16, 4);
        $table->string('payment_method')->nullable(); //paypal, stripe, paystack etc;
         $table->string('trans_status')->default('initiated'); //initiated, completed and payment failed, completed and successful;

        // $table->foreign('account_id','accounts')->references('id')->on('accounts')->onDelete('cascade');
        $table->foreign('client_membership_id','clients_memberships')->references('id')->on('clients_memberships')->onDelete('cascade');


        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
