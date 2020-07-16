<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsMemberships extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::create('clients_memberships', function (Blueprint $table) {
                $table->id();
                $table->integer('parrain_id')->nullable()->default(0);
                $table->string('username')->nullable();
                $table->integer('phone_number');
                $table->string('email');
                $table->string('code')->unique();
                $table->integer('member_level')->default(0);

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
        Schema::dropIfExists('clients_memberships');

    }
}
