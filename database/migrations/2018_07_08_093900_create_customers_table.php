<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('userId');
            $table->string('customerName');
            $table->string('address')->nullable();
            $table->string('contact')->unique();
            $table->string('email')->nullable();
            $table->string('zone')->nullable();
            $table->string('facebook')->default("https://www.facebook.com");
            $table->string('instagram')->default("https://www.instagram.com");
            $table->string('barcode')->nullable();
            $table->integer('zoneId')->default(1);
            $table->string('opening')->nullable()->default(0);
            $table->string('balance')->default(0);
            $table->timestamps();
            // $table->foreign('userId')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });

        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
