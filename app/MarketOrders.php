<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MarketOrders extends Model
{
    protected $primaryKey = 'order_id';
    public $incrementing = false;
    protected $keyType = 'bigInt';
    protected $table = 'market_orders';
    
    public function eveItem (){
        return $this->hasMany('App\EveItem', 'typeID');
    }
}
    