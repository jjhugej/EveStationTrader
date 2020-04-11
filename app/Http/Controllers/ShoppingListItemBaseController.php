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

class ShoppingListItemBaseController extends ShoppingListBaseController
{
    /*
        Unlike every other base controller this base controller extends ShoppingListBaseController.

        This is because the two are so closely related and we can allow transfer of necessary ShoppingListBaseController methods.

        EveBaseController methods are still callable since ShoppingListBaseController extends EveBaseController
    */

    
}
