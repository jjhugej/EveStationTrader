<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    public function user(){
        $this->belongsTo('App\User');
    }
}
