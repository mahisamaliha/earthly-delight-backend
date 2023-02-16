<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMainProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('main_products', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->integer('menuId');
            $table->integer('groupId');
            $table->integer('categoryId');
            $table->integer('brandId');
            $table->integer('tagId');
            $table->string('productName'); 
            $table->string('model'); 
            $table->text('description'); 
            $table->string('unit'); 
            $table->text('brief_description')->nullable(); 
            $table->decimal('sellingPrice', 8, 2)->nullable();
            $table->string('averageBuyingPrice'); 
            $table->string('productImage')->nullable();
            $table->text('images')->nullable();
            $table->boolean('isNew')->default(0);
            $table->boolean('isFeatured')->default(0);
            $table->boolean('isHotDeal')->default(0);
            $table->boolean('isSale')->default(0);
            $table->integer('stock')->default(0);
            $table->integer('discount')->default(0);
            $table->integer('adminDiscount')->default(0);
            $table->integer('appDiscount')->default(0);
            $table->string('openingQuantity')->default(0);
            $table->string('openingUnitPrice')->default(0);
            $table->boolean('isAvailable')->default(1);
            $table->boolean('is_arcived')->default(0);
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
        Schema::dropIfExists('main__products');
    }
}
