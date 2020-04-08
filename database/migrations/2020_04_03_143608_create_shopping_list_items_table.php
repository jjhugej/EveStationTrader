<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShoppingListItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopping_list_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->bigInteger('type_id')->nullable();
            $table->bigInteger('shopping_list_id')->nullable();
            $table->string('name')->nullable();
            $table->string('status');
            $table->bigInteger('logistics_group_id')->nullable();
            $table->bigInteger('market_order_id')->nullable();
            $table->bigInteger('inventory_item_id')->nullable();
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
        Schema::dropIfExists('shopping_list_items');
    }
}
