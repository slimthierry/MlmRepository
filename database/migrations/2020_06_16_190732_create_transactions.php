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
            $table->Increments('id');
            $table->integer('amount');
            $table->string('balance_before')->nullable();
            $table->string('balance_after')->nullable();
            $table->string('label')->nullable();
            $table->string('type');
            $table->integer('account_id')->unsigned();
            $table->integer('payement_mode_id')->unsigned();
            $table->foreign('account_id','accounts')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('payement_mode_id','payements_modes')->references('id')->on('payements_modes')->onDelete('cascade');
            $table->timestamp('created_at')->useCurrent();
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
