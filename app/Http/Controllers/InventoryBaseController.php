<?php

namespace App\Http\Controllers;

use App\Inventory;
use App\Logistics;
use App\Character;
use App\User;
use App\MarketOrders;
use App\ShoppingList;
use App\ShoppingListItem;
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
        $selectedCharacter = $this->getSelectedCharacter();
        
        if(isset($request->transaction_id_array)){
            /*
                if the request has the property transaction_id_array we need to run logic to get 
                the proper data from the transaction_ID.
                transaction_id_array also means that the request is coming from the shopping list item page
            */

           $this->processTransactionsAndSaveToDB($request, $selectedCharacter);


        }
        elseif(isset($request->market_order_id_array)){
            /*
                if the request has the property market_order_id_array we need to run logic to get 
                the proper data from the market orders.
                market_order_id_array also means that the request is coming from the market orders page
            */

            $this->processMarketOrdersAndSaveToDB($request, $selectedCharacter);
        }
        elseif(!isset($request->name) && !isset($request->transaction_id_array)){
            /*
                if neither of these are set it means the user clicked the submit form 
                on the shoppinglistitem page without actually selecting a transaction to link
            
            */
           
            $request->session()->flash('error', 'You must select an item');
            //return redirect()->back();
        }
        else{
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
            return $inventoryInstance;
        }
        

    }
    public function merge(Request $request){
        //dd('merge method', $request);
        // NOTE TO SELF: CHECK THAT ATLEAST TWO ITEMS ARE IN THE ID ARRAY 
        
        if($request->has('inventory_item_id_array') == true){
            
            $inventoryItemIDArray = $request->input('inventory_item_id_array');
    
            $typeIDCheck = null;
            $logisticsGroupID = null;
            $totalPurchasePrice = 0;
            $totalSellPrice = 0;
            $totalAmount = 0;
            $totalPar = 0;
            $totalTaxesPaid = 0;
            
            //this checks the items selected and makes sure they are the same item
            foreach($inventoryItemIDArray as $inventoryID){
                $inventoryItem = Inventory::where('id', $inventoryID)->first();

                if($typeIDCheck == null){
                    $typeIDCheck = $inventoryItem->type_id;
                }
                if($inventoryItem->type_id !== $typeIDCheck){
                    $request->session()->flash('error', 'You can only merge items of the same type');
                    return back();
                }
            }

            //this updates the variables above with the combined information from all selected items
            foreach($inventoryItemIDArray as $inventoryID){
    
                $inventoryItem = Inventory::where('id', $inventoryID)->first();

                    $logisticsGroupID = $inventoryItem->logistics_group_id;
                    $totalPurchasePrice += $inventoryItem->purchase_price;
                    $totalSellPrice += $inventoryItem->sell_price;
                    $totalAmount += $inventoryItem->amount;
                    $totalPar += $inventoryItem->par;
                    $totalTaxesPaid += $inventoryItem->taxes_paid;
    
                    $inventoryItem->delete();
                
                
    
            }

            //new up an instance of inventory and use the variables which have been updated from the above block of code
    
            $mergedInventoryItem = new Inventory();
            $mergedInventoryItem->user_id = Auth::user()->id;
            $mergedInventoryItem->character_id = $this->getSelectedCharacter()->character_id;
            $mergedInventoryItem->type_id = $typeIDCheck;
            $mergedInventoryItem->name = $this->resolveSingleTypeIDToItemName($typeIDCheck);
            $mergedInventoryItem->logistics_group_id = $logisticsGroupID;
            $mergedInventoryItem->purchase_price = $totalPurchasePrice;
            $mergedInventoryItem->sell_price = $totalSellPrice;
            $mergedInventoryItem->amount = $totalAmount;
            $mergedInventoryItem->par = $totalPar;
            $mergedInventoryItem->taxes_paid = $totalTaxesPaid;
            $mergedInventoryItem->notes = 'Merged from multiple inventory items';
    
            $mergedInventoryItem->save();
    
            return $mergedInventoryItem;
        }else{
            //else the request did not have any items selected and should be returned back with an error message
            $request->session()->flash('error', 'You must select at least two of the same items to merge');

            return back();
        }

    }

    public function processTransactionsAndSaveToDB($request, $selectedCharacter){
        /*
                if the request has the property transaction_id_array we need to run logic to get 
                the proper data from the transaction_ID.
                transaction_id_array also means that the request is coming from the shopping list item page
            */
           //processTransactionsAndSaveToDB($request);

            $totalTransactionQuantity = 0;
            $totalTransactionPurchasePrice = 0;


           foreach($request->transaction_id_array as $transaction_id){
               
                $transaction = Transactions::where('transaction_id', $transaction_id)->get();               

                $transaction = $this->resolveTypeIDToItemName($transaction)->first();
               

                $inventoryInstance = new Inventory();
                $inventoryInstance->user_id = Auth::user()->id;
                $inventoryInstance->character_id = $selectedCharacter->character_id;
                $inventoryInstance->name = $transaction->typeName;
                $inventoryInstance->type_id = $transaction->type_id;
                $inventoryInstance->amount = $transaction->quantity;
                $inventoryInstance->purchase_price = $transaction->unit_price;

                if(isset($request->shoppingListItemID)){
                    $inventoryInstance->shopping_list_item_id = $request->shoppingListItemID;
                    $shoppingListItem = ShoppingListItem::where('id', $request->shoppingListItemID)->first();
                    $shoppingList = ShoppingList::where('id', $shoppingListItem->shopping_list_id)->first();
                    $inventoryInstance->notes = 'Added from shopping list item "' . $shoppingListItem->name .
                    '" from the shopping list "' . $shoppingList->name . '"';
                }
    
                 $inventoryInstance->save();
               
                //next we have to update the transaction with the shopping list item id to attach the two 
                $transaction->shopping_list_item_id = $request->shoppingListItemID;
                $transaction->inventory_id = $inventoryInstance->id;
                unset($transaction->typeName);

                $transaction->save();

                 //update initialized variables with purchase price and quantity
                 //These variables are used to update the shopping list items purchase and quanity amounts
                $totalTransactionQuantity += $transaction->quantity;
                $totalTransactionPurchasePrice += $transaction->unit_price;
                

           }
           $shoppingListItem = ShoppingListitem::where('id', $request->shoppingListItemID)->first();
           if($shoppingListItem !== null && $shoppingListItem !== 0){
               $shoppingListItem->amount_purchased += $totalTransactionQuantity;
               $shoppingListItem->purchase_price += $totalTransactionPurchasePrice;
               if($shoppingListItem->amount <= $shoppingListItem->amount_purchased ){
                    $shoppingListItem->status = 'Purchased';
               }else{
    
                   $shoppingListItem->status = 'Partially Purchased';
               }
    
               $shoppingListItem->save();
           }

           $request->session()->flash('status', 'Inventory Item Created!');
           
           return back();
    }

    public function processMarketOrdersAndSaveToDB($request, $selectedCharacter){
        //dd('process market orders ->inventorybaseController', $request);

        // TODO: The request is properly coming here. Next we have to figure out how to save the market order as an inventory item
        //and we should display a notification on market orders that are already assigned to an inventory item

        foreach($request->market_order_id_array as $marketOrder_id){
            $marketOrder = MarketOrders::where('order_id', $marketOrder_id)->get();
            $marketOrder = $this->resolveStationIDToName($selectedCharacter, $marketOrder)->first();
            $marketOrder->typeName = $this->resolveSingleTypeIDToItemName($marketOrder->type_id);
            //update inventory id and add notes to migration for market  orders
         
            
            $inventoryInstance = new Inventory();

            $inventoryInstance->user_id = Auth::user()->id;
            $inventoryInstance->character_id = $selectedCharacter->character_id;
            $inventoryInstance->name = $marketOrder->typeName;
            $inventoryInstance->type_id = $marketOrder->type_id;
            $inventoryInstance->market_order_id = $marketOrder->order_id;
            $inventoryInstance->amount = $marketOrder->volume_remain;
            $inventoryInstance->sell_price = $marketOrder->unit_price;
            $inventoryInstance->current_location = $marketOrder->locationName;
            $inventoryInstance->notes = 'Added from market orders page';
           
            $inventoryInstance->save();
           
            //Update the market order to show that it was added to the inventory, and assign its respective inventory ID
            //TODO: on market orders pages update to show that an item is already part of the inventory,
            //AND remove its select box to prevent future inventory items from being made.
            unset($marketOrder->typeName);
            unset($marketOrder->locationName);

            $marketOrder->inventory_id = $inventoryInstance->id;
            $marketOrder->notes = $marketOrder->notes . ' .Quick Added To Inventory From The Market Orders Page.';
            
            $marketOrder->save();

            
        }

        $request->session()->flash('status', 'Inventory Item(s) Created');

        return back();




        dd('end of processmarketorders');
    }
}   