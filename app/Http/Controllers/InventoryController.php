<?php

namespace App\Http\Controllers;

use App\Inventory;
use App\Logistics;
use App\Character;
use App\User;
use App\MarketOrders;
use App\EveItem;
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
        $inventoryItems = Inventory::where('user_id', Auth::user()->id)->get();
        $items = $this->resolveMultipleLogisticsGroupIDToName($inventoryItems);
        
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
            $marketOrders = MarketOrders::where('user_id', Auth::user()->id)->orderBy('created_at','desc')->get();
            $marketOrders = $this->resolveTypeIDToItemName($marketOrders);
            $marketOrders = $this->resolveStationIDToName($currentSelectedCharacter, $marketOrders);

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
        //dd($request->all());
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
        $selectedCharacter = Character::where('user_id', Auth::user()->id)->where('is_selected_character', 1)->first();

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
        return redirect('/inventory');
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
        //dd($inventoryItem->id);
        $deliveryGroups = Logistics::where('user_id', Auth::user()->id)->orderBy('created_at','desc')->get();

        return view('inventory.inventory_edit', compact('inventoryItem','deliveryGroups')); 
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
        //dd($inventoryItem);
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
            $searchMatches = EveItem::where('typeName', 'LIKE','%'.$searchRequest.'%')->take(30)->get();
            return view('inventory._item_search', compact('searchMatches'));
        }
    }

}
