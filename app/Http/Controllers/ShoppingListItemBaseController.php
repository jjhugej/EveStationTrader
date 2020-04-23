<?php

namespace App\Http\Controllers;

use App\Character;
use App\User;
use App\EveItem;
use App\Transactions;
use App\Inventory;
use App\StructureName;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;

class ShoppingListItemBaseController extends ShoppingListBaseController
{
    /*
        Unlike every other base controller this base controller extends ShoppingListBaseController.

        This is because the two are so closely related and we can allow transfer of necessary ShoppingListBaseController methods.

        EveBaseController methods are still callable since ShoppingListBaseController extends EveBaseController
    */

        public function getTransactionsWithSameTypeID($shoppingListItem){
            //This method retrieves buy order transactions that have items with the same type ID as the given shopping list item
            $transactions = Transactions::where('user_id', Auth::user()->id)
            ->where('type_id', $shoppingListItem->type_id)
            ->where('is_buy', 1)
            ->where('shopping_list_item_id', null)
            ->orWhere('shopping_list_item_id', 0)
            ->orderBy('date', 'desc')
            ->get();
            //dd($transactions);
            return $transactions;
        }

    
}
