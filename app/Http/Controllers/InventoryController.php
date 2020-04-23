<?php

namespace App\Http\Controllers;

use App\Inventory;
use App\Logistics;
use App\Character;
use App\User;
use App\MarketOrders;
use App\EveItem;
use App\ShoppingListItem;
use App\Transactions;
use App\StructureName;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;


class InventoryController extends InventoryBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $inventoryItems = Inventory::where('user_id', Auth::user()->id)->orderBy('name', 'asc')->get();
        $items = $this->resolveMultipleLogisticsGroupIDToName($inventoryItems);
        $items = $this->resolveMultipleCharacterNamesFromIDs($items);
        
        return view('inventory.inventory', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //send delivery groups to view so users can link the item to a delivery group
        $deliveryGroups = Logistics::where('user_id', Auth::user()->id)->orderBy('created_at','desc')->get();

        //retrieve market orders of user's selected character and resolve the item name and station name
        $currentSelectedCharacter = $this->getSelectedCharacter();
            
        if($currentSelectedCharacter !== null && $currentSelectedCharacter->is_selected_character === 1){
            
            $marketOrders = MarketOrders::where('user_id', Auth::user()->id)->get();
            $marketOrders = $this->resolveTypeIDToItemName($marketOrders);
            $marketOrders = $this->resolveStationIDToName($currentSelectedCharacter, $marketOrders);
            $marketOrders = $this->resolveMultipleCharacterNamesFromIDs($marketOrders);
            $marketOrders = $marketOrders->sortBy('typeName');

        return view('inventory.inventory_create', compact('deliveryGroups', 'marketOrders'));
        }else{
            //redirect to characters because none are selected and flash an error message
            $request->session()->flash('error', 'You Must Select A Character Before Proceeding');

            return redirect('/characters');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //TODO CHECK IF THE REQUEST COMING IN IS FROM SHOPPING LIST ITEM AND MARK THE SHOPPING LIST ITEM AMOUNT AS 
        //THE AMOUNT PURCHASED FROM THE TRANSACTIONS

       // dd($request,$request->session(),  $request->session()->get('_previous')['url']);
        
                
        $inventoryItem = $this->saveInventoryItemToDB($request);

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function show(Inventory $inventoryItem)
    {
        
        //check if the item has a market order attached to it
        if($inventoryItem->market_order_id !== null){
            $attachedMarketOrder = MarketOrders::where('order_id', $inventoryItem->market_order_id)->get();
            $attachedMarketOrder=$this->resolveTypeIDToItemName($attachedMarketOrder)->first();
        }
        else{
            $attachedMarketOrder = null;
        }

        $item = $this->resolveSingleLogisticsGroupIDToName($inventoryItem);
        $item->character_name = $this->resolveSingleCharacterNameFromID($inventoryItem);

        //dd($item);
        
        return view('inventory.inventory_details', compact('item', 'attachedMarketOrder'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function edit(Inventory $inventoryItem)
    {
        //dd($inventoryItem);
        $deliveryGroups = Logistics::where('user_id', Auth::user()->id)->orderBy('created_at','desc')->get();
        
        $currentSelectedCharacter = $this->getSelectedCharacter();
            
        if($currentSelectedCharacter !== null && $currentSelectedCharacter->is_selected_character === 1){
            
            $marketOrders = MarketOrders::where('user_id', Auth::user()->id)->get();
            $marketOrders = $this->resolveTypeIDToItemName($marketOrders);
            $marketOrders = $this->resolveStationIDToName($currentSelectedCharacter, $marketOrders);
            $marketOrders = $this->resolveMultipleCharacterNamesFromIDs($marketOrders);
            $marketOrders = $marketOrders->sortBy('typeName');

        return view('inventory.inventory_edit', compact('inventoryItem','deliveryGroups', 'marketOrders')); 

        }else{
            //redirect to characters because none are selected and flash an error message
            $request->session()->flash('error', 'You Must Select A Character Before Proceeding');

            return redirect('/characters');
        }

        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Inventory $inventoryItem)
    {
        
        $validatedData = $request->validate([
        'name' => 'required|max:255',
        'purchase_price' => 'required|max:255',
        'sell_price' => 'nullable|max:255',
        'par' => 'integer|nullable',
        'amount' => 'integer|nullable',
        'taxes_paid' => 'integer|nullable',
        'delivery_group_select' => 'nullable',
        'current_location' => 'nullable',
        'market_order_id_select' => 'nullable',
        'notes' => 'nullable|max:1000',
        ]);

        $inventoryInstance = Inventory::where('id', $inventoryItem->id)->first();

        $inventoryInstance->user_id = Auth::user()->id;
        $inventoryInstance->purchase_price = $validatedData['purchase_price'];
        $inventoryInstance->name = $validatedData['name'];
        $inventoryInstance->sell_price = $validatedData['sell_price'];
        $inventoryInstance->par = $validatedData['par'];
        $inventoryInstance->amount = $validatedData['amount'];
        $inventoryInstance->taxes_paid = $validatedData['taxes_paid'];
        $inventoryInstance->current_location = $validatedData['current_location'];
        $inventoryInstance->notes = $validatedData['notes'];
        if(array_key_exists('delivery_group_select', $validatedData)){
            $inventoryInstance->logistics_group_id = $validatedData['delivery_group_select'];
        }else{
            $inventoryInstance->logistics_group_id = null;
        }
        if(array_key_exists('market_order_id_select', $validatedData)){
            /*
            because we store the value of every property within the value field of market_order_id_select,
            we need to split the string into an array (delimited by a comma) and take the 0th value (which is the actual market order id)
            */
            $inventoryInstance->market_order_id = explode(',',$validatedData['market_order_id_select'])[0];
        }

        $inventoryInstance->save();
        return redirect('/inventory');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function destroy(Inventory $inventoryItem)
    {
        //remove shoppingListItemID and inventory_id from deleted inventory items' transactions

         $inventoryItem = Inventory::where('id', $inventoryItem->id)->first();


            //delete the inventory id from its respective transaction
            $associatedTransaction = Transactions::where('inventory_id', $inventoryItem->id)->first();

            if($associatedTransaction !== null || $associatedTransaction !== 0){
                $associatedTransaction->inventory_id = 0;
                $associatedTransaction->save();
            }

            //update the purchase price and amount purchased for the related shopping list item
            $associatedShoppingListItem = ShoppingListItem:: where('id', $inventoryItem->shopping_list_item_id)->first();

            if($associatedShoppingListItem !== null || $associatedShoppingListItem !== 0){
                $associatedShoppingListItem->purchase_price -= $inventoryItem->purchase_price;
                $associatedShoppingListItem->amount_purchased -= $inventoryItem->amount;
                $associatedShoppingListItem->save();
            }

        $inventoryItem->delete();
        return redirect('/inventory');
    }
    public function remove($inventoryItem){
        //this method removes an item from an assigned logistics group
        $inventoryItemObject = Inventory::where('id', $inventoryItem)->first();
        $inventoryItemObject->logistics_group_id = null;
        $inventoryItemObject->save();

        return redirect()->back();

    }

    public function add($inventoryItemID, $logisticsGroupID){
        //this method adds an item to a logistics group
        $inventoryItemObject = Inventory::where('id',$inventoryItemID)->first();
        $inventoryItemObject->logistics_group_id = $logisticsGroupID;
        
        $inventoryItemObject->save();

        return redirect()->back();
    }

    public function itemSearch(Request $request){
        //This method is used to return data from the eveitems table via the search method on the item_create view (specifically the item name input field).
        //searchRequest is the variable that comes from the ajax get request
        $searchRequest = $request->searchRequest;
        if($searchRequest !== null){
            $searchMatches = EveItem::where('typeName', 'LIKE','%'.$searchRequest.'%')->whereNotNull('marketGroupID')->orderByRaw('CHAR_LENGTH(typeName)')->take(30)->get();
            return view('inventory._item_search', compact('searchMatches'));
        }
    }

    public function merge(Request $request){
        //dd('merge method', $request);
        $inventoryItemIDArray = $request->input('inventory_item_id_array');

        $typeIDCheck = null;
        $logisticsGroupID = null;
        $totalPurchasePrice = 0;
        $totalSellPrice = 0;
        $totalAmount = 0;
        $totalPar = 0;
        $totalTaxesPaid = 0;
       
        foreach($inventoryItemIDArray as $inventoryID){

            $inventoryItem = Inventory::where('id', $inventoryID)->first();

            if($typeIDCheck == null){
                $typeIDCheck = $inventoryItem->type_id;
            }
            if($inventoryItem->type_id !== $typeIDCheck){
                //if this check is true the item types are different and cannot be merged

                //return error

            }else{
                $logisticsGroupID = $inventoryItem->logistics_group_id;
                $totalPurchasePrice += $inventoryItem->purchase_price;
                $totalSellPrice += $inventoryItem->sell_price;
                $totalAmount += $inventoryItem->amount;
                $totalPar += $inventoryItem->par;
                $totalTaxesPaid += $inventoryItem->taxes_paid;

                $inventoryItem->delete();
            }
            

            //dd('merge method', $inventoryItem);
        }

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

    }
    public function updatetransaction($mergedInventoryItem){
        dd('updateTransaction on incentory controller',$mergedInventoryItem);
    }

    public function inventoryFormReRoute(Request $request){

        switch($request->input('action')){

            case 'merge':
                $mergedInventoryItem = $this->merge($request);
                $this->updatetransaction($mergedInventoryItem);
                return back();
            break;
            
            case 'delete':
                //dd('delete', $request->input('inventory_item_id_array'));
                
                foreach($request->input('inventory_item_id_array') as $inventoryItemID){
                    $inventoryItem = Inventory::where('id', $inventoryItemID)->first();


                    //delete the inventory id from its respective transaction
                    $associatedTransaction = Transactions::where('inventory_id', $inventoryItemID)->first();
                 
                    $associatedTransaction->inventory_id = 0;

                    $associatedTransaction->save();

                    //update the purchase price and amount purchased for the related shopping list item
                    $associatedShoppingListItem = ShoppingListItem:: where('id', $inventoryItem->shopping_list_item_id)->first();
                    $associatedShoppingListItem->purchase_price -= $inventoryItem->purchase_price;
                    $associatedShoppingListItem->amount_purchased -= $inventoryItem->amount;

                    $associatedShoppingListItem->save();
                    
                    $inventoryItem->delete();
                }
                return back();

                //TODO: UPDATE SHOPPINGLIST ITEM PURCHASE AMOUNT AND PURCHASE PRICE ON DELETION OF INVENTORY ITEMS
                //REMEMBER TO UPDATE FOR SINGLE DESTROY AS WELL
            break;
        }
    }

}
