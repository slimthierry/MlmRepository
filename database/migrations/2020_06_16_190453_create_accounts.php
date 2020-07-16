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

            $table->id();
            $table->unsignedInteger('client_membership_id');
            // $table->unsignedInteger('payment_mode_id');
            $table->decimal('balance', 16, 4)->default(0);
            $table->boolean('enabled')->default(0);
            // $table->integer('paid')->default(0);

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
        Schema::dropIfExists('accounts');

        // Schema::table('accounts', function (Blueprint $table) {
        //     Schema::enableForeignKeyConstraints();
        //     $table->dropForeign(['client_membership_id']);
        // });

    }
}
