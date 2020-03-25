<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Logistics extends Model
{
    protected $fillable = ['name', 'start_station', 'end_station', 'price', 'volume', 'status', 'notes'];

    public function user (){
        return $this->belongsTo('App\User');
    }
    public function inventory(){
        return $this->hasMany('App\Inventory');
    }
}
