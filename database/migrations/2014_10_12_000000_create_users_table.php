<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');


            $table->string('crm_api__endpoint_import', 1024);
            $table->string('crm_api__endpoint_export', 1024);
            $table->string('crm_api__login', 128);
            $table->string('crm_api__password', 128);
            $table->string('bigQuery_api__keyfile', 6144);
            $table->string('bigQuery_api__datasetId', 512);

//            $table->integer('t3', 9)->default(86400);
//            $table->integer('t9', 9)->default(0);
//
//
//            $table->integer('d1', 3)->default(0);
//            $table->integer('d2', 3)->default(0);
//            $table->integer('d4', 3)->default(0);
//            $table->string('time_starting_tasks', 5)->default('20:00');
//
//
//            $table->integer('role', 1);


// without AUTO_INCREMENT
            $table->integer('t3')->default(86400);
            $table->integer('t9')->default(0);


            $table->integer('d1')->default(0);
            $table->integer('d2')->default(0);
            $table->integer('d4')->default(0);
            $table->string('time_starting_tasks', 5)->default('20:00');


            $table->integer('role');

            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
