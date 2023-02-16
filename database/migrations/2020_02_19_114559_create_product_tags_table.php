<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */ 
    public function up()
    {
        Schema::create('product_tags', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('productId');
            $table->unsignedInteger('tagId');
            // $table->foreign('productId')->references('id')->on('main_products')->onDelete('cascade')->onUpdate('cascade');
            // $table->foreign('tagId')->references('id')->on('tags')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('product_tags');
    }
}
