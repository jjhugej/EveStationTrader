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
        $inventoryItems = $this->resolveLogisticsGroupIDToName($inventoryItems);
        
        return view('inventory.inventory', compact('inventoryItems'));
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

        return view('inventory.inventory_create', compact('deliveryGroups'));
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

        $item = $this->resolveLogisticsGroupIDToName($inventoryItem);
        //dd($item);
        return view('inventory.inventory_details', compact('item'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function edit(Inventory $inventory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Inventory $inventory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function destroy(Inventory $inventory)
    {
        //
    }
}
