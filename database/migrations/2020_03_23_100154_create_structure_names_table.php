<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStructureNamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('structure_names', function (Blueprint $table) {
            $table->bigIncrements('id')->unique();
            $table->bigInteger('location_id')->unsigned();
            $table->string('location_name');
            //$table->bigInteger('solar_system_id')->nullable(); --> had problems with this, removed for now
            $table->bigInteger('type_id');
            $table->integer('expiration')->default(30);
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
        Schema::dropIfExists('structure_names');
    }
}
