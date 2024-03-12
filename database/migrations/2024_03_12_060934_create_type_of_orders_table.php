<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTypeOfOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('type_of_orders', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedInteger('categoryId'); // Assuming categoryId is foreign key 
            $table->unsignedInteger('searchId');  // Assuming searchId is foreign key
            $table->string('name');
            $table->string('nameRu')->nullable();
            $table->json('techDocumentations')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('type_of_orders');
    }
}
