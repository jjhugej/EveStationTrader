<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('market_orders', function (Blueprint $table) {
            $table->bigInteger('order_id')->unique();
            $table->bigInteger('user_id')->unsigned();
            $table->integer('duration');
            $table->boolean('is_corporation');
            $table->dateTime('issued');
            $table->bigInteger('location_id');
            $table->bigInteger('price');
            $table->string('range');
            $table->bigInteger('region_id');
            $table->bigInteger('type_id');
            $table->bigInteger('volume_remain');
            $table->bigInteger('volume_total');
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
        Schema::dropIfExists('market_orders');
    }
}
