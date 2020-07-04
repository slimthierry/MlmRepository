<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('status');
            $table->string('balance');
            $table->timestamp('created_at')->useCurrent();
            $table->integer('client_membership_id')->unsigned();
            $table->foreign('client_membership_id','clients_memberships')->references('id')->on('clients_memberships')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts');
    }
}
