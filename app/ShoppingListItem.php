<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShoppingListItem extends Model
{
    public function shoppingList(){
        $this->belongsTo('App\ShoppingList');
    }
}
