<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCharactersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('characters', function (Blueprint $table) {
            $table->bigIncrements('id')->unique();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->bigInteger('character_id')->nullable();
            $table->bigInteger('corporation_id')->nullable();
            $table->string('portrait')->nullable();
            $table->string('corporation_name')->nullable();
            $table->string('character_name')->nullable();
            $table->longText('access_token')->nullable();
            $table->longText('refresh_token')->nullable();
            $table->boolean('is_selected_character')->default(false);
            $table->dateTime('expires')->nullable();
            $table->dateTime('last_esi_token_fetch')->nullable();
            $table->dateTime('next_available_esi_market_fetch')->nullable();
            $table->dateTime('next_available_esi_transactions_fetch')->nullable();
            $table->dateTime('next_available_esi_inventory_fetch')->nullable();
            $table->dateTime('next_available_esi_portrait_fetch')->nullable();
            $table->timestamps();
        });
    }

    /**c
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('characters');
        
    }
}
