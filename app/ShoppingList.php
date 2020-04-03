<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShoppingList extends Model
{
    public function user(){
        $this->belongsTo('App\User');
    }
    public function shoppingListItems(){
        $this->hasMany('App\ShoppingListItem');
    }
}
