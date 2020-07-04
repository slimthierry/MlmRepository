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
                $table->Increments('id');
                $table->integer('ref_id')->nullable();
                $table->integer('parrain_id')->nullable();
                $table->string('first_name')->nullable();
                $table->string('last_name')->nullable();
                $table->integer('phone_number');
                $table->string('email');
                $table->string('code')->unique();
                // $table->boolean('is_active')->default(1);
                $table->integer('member_level')->default(1);
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
        Schema::dropIfExists('clients_memberships');

    }
}
