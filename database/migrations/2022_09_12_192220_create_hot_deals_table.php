<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHotDealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hot_deals', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('percentageTitle');
            $table->dateTime('startTime');
            $table->dateTime('endTime');
            $table->integer('duration');
            $table->boolean('isHotSale')->default(0);
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
        Schema::dropIfExists('hot_deals');
    }
}
