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
            $table->bigInteger('user_id');
            //$table->foreign('user_id')->references('id')->on('users');
            $table->bigInteger('character_id'); 
            $table->bigInteger('inventory_id')->nullable();  
            $table->bigInteger('logistics_group_id')->nullable();
            $table->integer('duration');
            $table->boolean('is_corporation');
            $table->dateTime('issued')->nullable();
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
