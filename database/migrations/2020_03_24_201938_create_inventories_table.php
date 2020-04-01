<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->bigIncrements('id')->unique();
            $table->bigInteger('user_id');
            $table->bigInteger('character_id');
            $table->bigInteger('type_id')->nullable();
            $table->string('name')->nullable();
            $table->bigInteger('logistics_group_id')->nullable();
            $table->bigInteger('market_order_id')->nullable();
            $table->bigInteger('purchase_price')->nullable();
            $table->bigInteger('sell_price')->nullable();
            $table->bigInteger('amount')->nullable();
            $table->bigInteger('par')->nullable();
            $table->bigInteger('volume_per_item')->nullable();
            $table->bigInteger('taxes_paid')->nullable();
            $table->string('current_location')->nullable();
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('inventories');
    }
}
