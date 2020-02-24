<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EveItemIDModel extends Model
{
    protected $primaryKey = 'flight_id';
    public $incrementing = false;
    public $timestamps = false;

    public function getItemNameByID(){
        dd('regearg');
    }
}
