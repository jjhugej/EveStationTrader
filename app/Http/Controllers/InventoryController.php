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
    public function create()
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
        'delivery_group_select' => 'nullable|max:1',
        'notes' => 'nullable|max:1000',
        ]);

        $inventoryInstance = new Inventory();

        $inventoryInstance->user_id = Auth::user()->id;
        $inventoryInstance->purchase_price = $validatedData['purchase_price'];
        $inventoryInstance->name = $validatedData['name'];
        $inventoryInstance->sell_price = $validatedData['sell_price'];
        $inventoryInstance->amount = $validatedData['amount'];
        $inventoryInstance->taxes_paid = $validatedData['taxes_paid'];
        $inventoryInstance->notes = $validatedData['notes'];
        if(array_key_exists('delivery_group_select', $validatedData)){
            $inventoryInstance->logistics_group_id = $validatedData['delivery_group_select'];
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
        $item = $this->resolveSingleLogisticsGroupIDToName($inventoryItem);
        //dd($item);
        return view('inventory.inventory_details', compact('item'));
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
        'delivery_group_select' => 'nullable|max:1',
        'notes' => 'nullable|max:1000',
        ]);

        $inventoryInstance = Inventory::where('id', $inventoryItem->id)->first();

        $inventoryInstance->user_id = Auth::user()->id;
        $inventoryInstance->purchase_price = $validatedData['purchase_price'];
        $inventoryInstance->name = $validatedData['name'];
        $inventoryInstance->sell_price = $validatedData['sell_price'];
        $inventoryInstance->amount = $validatedData['amount'];
        $inventoryInstance->taxes_paid = $validatedData['taxes_paid'];
        $inventoryInstance->notes = $validatedData['notes'];
        if(array_key_exists('delivery_group_select', $validatedData)){
            $inventoryInstance->logistics_group_id = $validatedData['delivery_group_select'];
        }else{
            $inventoryInstance->logistics_group_id = null;
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
}
