<?php

namespace App\Http\Controllers;

use App\Character;
use App\User;
use App\EveItem;
use App\Inventory;
use App\StructureName;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;


class ShoppingListBaseController extends EveBaseController
{
    public function createNewInventoryItemFromShoppingListItem($shoppingListItemInstance){
        $selectedCharacter = Character::where('user_id', Auth::user()->id)->where('is_selected_character', 1)->first();

        $inventoryInstance = new Inventory();

        $inventoryInstance->user_id = Auth::user()->id;
        $inventoryInstance->character_id = $selectedCharacter->character_id;
        $inventoryInstance->purchase_price = $shoppingListItemInstance->purchase_price;
        $inventoryInstance->name = $shoppingListItemInstance->name;
        $inventoryInstance->sell_price = $shoppingListItemInstance->sell_price;
        $inventoryInstance->par = $shoppingListItemInstance->par;
        $inventoryInstance->amount = $shoppingListItemInstance->amount;
        $inventoryInstance->taxes_paid = $shoppingListItemInstance->taxes_paid;
        $inventoryInstance->notes = $shoppingListItemInstance->notes;
        $inventoryInstance->current_location = $shoppingListItemInstance->current_location;

        $inventoryInstance->save();

        session()->flash('status', 'Item Succesfully Added To Inventory!');
    }
}
