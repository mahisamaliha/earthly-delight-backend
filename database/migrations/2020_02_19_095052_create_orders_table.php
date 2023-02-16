<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('userId');
            $table->string('name');
            $table->string('contact'); 
            $table->decimal('subTotal', 10, 2);
            $table->decimal('grandTotal', 10, 2);
            $table->integer('shippingPrice');
            $table->string('coupon')->nullable();
            $table->string('referralCode')->nullable();
            $table->integer('discount')->default(0);
            $table->string('discountType')->nullable();
            $table->string('billingCity');
            $table->text('billingAddress');
            $table->string('postCode');
            $table->string('email')->nullable();
            $table->string('status')->default('Order Placed');
            // $table->foreign('userId')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->string('paymentType');





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
        Schema::dropIfExists('orders');
    }
}
