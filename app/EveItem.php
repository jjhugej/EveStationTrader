<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EveItem extends Model
{
    public function user(){
        $this->belongsTo('App\MarketOrders');
    }
    protected $primaryKey = 'typeID';
    protected $table = 'eveItems';
}
