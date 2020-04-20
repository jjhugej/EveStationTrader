<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->bigInteger('character_id');
            $table->bigInteger('shopping_list_item_id')->nullable();
            $table->bigInteger('inventory_id')->nullable();
            $table->bigInteger('journal_ref_id');
            $table->bigInteger('transaction_id');
            $table->bigInteger('location_id');
            $table->bigInteger('type_id');
            $table->bigInteger('quantity');
            $table->bigInteger('unit_price');
            $table->boolean('is_buy');
            $table->boolean('is_personal');
            $table->dateTime('date');
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
        Schema::dropIfExists('transactions');
    }
}
