<?php

namespace App\Http\Controllers;

use App\Inventory;
use App\Logistics;
use App\Character;
use App\User;
use App\MarketOrders;
use App\EveItem;
use App\Transactions;
use App\StructureName;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;

class InventoryBaseController extends EveBaseController
{
    public function resolveMultipleLogisticsGroupIDToName($inventoryItems){
        
        foreach($inventoryItems as $inventoryItem){
            if($inventoryItem->logistics_group_id){
                if(isset(Logistics::where('id', $inventoryItem->logistics_group_id)->first()->name)){
                    $inventoryItem->logistics_group_name = Logistics::where('id', $inventoryItem->logistics_group_id)->first()->name;
                }else{
                    $inventoryItem->logistics_group_name = null;
                }
            }
        }
        return $inventoryItems;
    }

    public function resolveSingleLogisticsGroupIDToName($inventoryItem){
        if($inventoryItem->logistics_group_id){
            if(isset(Logistics::where('id', $inventoryItem->logistics_group_id)->first()->name)){
                $inventoryItem->logistics_group_name = Logistics::where('id', $inventoryItem->logistics_group_id)->first()->name;
            }else{
                $inventoryItem->logistics_group_name = null;
            }
        }
        return $inventoryItem;
    }

    public function saveInventoryItemToDB($request){
       // dd($request,$request->shoppingListItemID);
        $selectedCharacter = $this->getSelectedCharacter();
      
        if(isset($request->transaction_id_array)){

            //if the request has the property transaction_id_array we need to run logic to get the proper data from the transaction_ID
          
           foreach($request->transaction_id_array as $transaction_id){
               
            $transaction = Transactions::where('transaction_id', $transaction_id)->get();
            
                //next we have to update the transaction with the shopping list item id to attach the two

               $transaction->first()->shopping_list_item_id = $request->shoppingListItemID;
               
               $transaction->first()->save();
               $transaction = $this->resolveTypeIDToItemName($transaction)->first();
                            
               $inventoryInstance = new Inventory();
               $inventoryInstance->user_id = Auth::user()->id;
               $inventoryInstance->character_id = $selectedCharacter->character_id;
               $inventoryInstance->name = $transaction->typeName;
               $inventoryInstance->type_id = $transaction->type_id;
               $inventoryInstance->amount = $transaction->quantity;
               $inventoryInstance->purchase_price = $transaction->unit_price;

               if(isset($request->shoppingListItemID)){
                  
                   //if the request comes with shoppinglistitemID save it to the inventory instance
                   $inventoryInstance->shopping_list_item_id = $request->shoppingListItemID;
                }
               
               $inventoryInstance->save();

                    
     
           }

        }
        else{
            dd('saveInventoryItemToDB','else');

            //else we validate and save the data normally
 
            $validatedData = $request->validate([
            'name' => 'required|max:255',
            'purchase_price' => 'nullable|max:255',
            'sell_price' => 'nullable|max:255',
            'par' => 'integer|nullable',
            'amount' => 'integer|nullable',
            'taxes_paid' => 'integer|nullable',
            'delivery_group_select' => 'nullable',
            'current_location' => 'nullable',
            'market_order_id_select' => 'nullable',
            'notes' => 'nullable|max:1000',
            ]);
    
            
            
            $inventoryInstance = new Inventory();
                //TODO: RESOLVE TYPE_ID FROM NAME
            $inventoryInstance->user_id = Auth::user()->id;
            $inventoryInstance->character_id = $selectedCharacter->character_id;
            $inventoryInstance->purchase_price = $validatedData['purchase_price'];
            $inventoryInstance->name = $validatedData['name'];
            $inventoryInstance->sell_price = $validatedData['sell_price'];
            $inventoryInstance->par = $validatedData['par'];
            $inventoryInstance->amount = $validatedData['amount'];
            $inventoryInstance->taxes_paid = $validatedData['taxes_paid'];
            $inventoryInstance->notes = $validatedData['notes'];
            $inventoryInstance->current_location = $validatedData['current_location'];
            if(array_key_exists('delivery_group_select', $validatedData)){
                $inventoryInstance->logistics_group_id = $validatedData['delivery_group_select'];
            }
            if(array_key_exists('market_order_id_select', $validatedData)){
                /*
                because we store the value of every property within the value field of market_order_id_select,
                we need to split the string into an array (delimited by a comma) and take the 0th value (which is the actual market order id)
                */
                $inventoryInstance->market_order_id = explode(',',$validatedData['market_order_id_select'])[0];
            }
    
            $inventoryInstance->save();
        }

        return $inventoryInstance;

    }
}   