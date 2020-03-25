<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    public function logisticsGroup(){
        return $this->belongsTo('App\Logistics');
    }
    public function marketOrder(){
        return $this->hasOne('App\MarketOrders');
    }
}
