<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEveItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
        
            This is the table structure for the typeIDs (renamed eveitems) from fuzzworks SDE dump https://www.fuzzwork.co.uk/dump/
            
        */
        Schema::create('eveitems', function (Blueprint $table) {    
            $table->integer('typeID', 11);
            $table->integer('groupID', 11);
            $table->string('typeName', 100);
            $table->text('description');
            $table->double('mass');
            $table->double('volume');
            $table->double('capacity');
            $table->integer('portionSize', 11);
            $table->integer('raceID', 11);
            $table->decimal('basePrice', 19, 4);
            $table->tinyInteger('published', 1);
            $table->integer('marketGroupID', 11);
            $table->integer('iconID', 11);
            $table->integer('soundID', 11);
            $table->integer('graphicID', 11);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('eveitems');
    }
}
