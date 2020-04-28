<?php

namespace App\Http\Controllers;

use App\ShoppingListItem;
use App\ShoppingList;
use App\Character;
use App\User;
use App\EveItem;
use App\StructureName;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;

class ShoppingListItemController extends ShoppingListItemBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $shoppingListID)
    {
        $validatedData = $request->validate([
        'name' => 'required|max:255',
        'purchase_price' => 'nullable|integer',
        'amount' => 'nullable|integer',
        'status' => 'required',
        'notes' => 'nullable|max:1000',
        ]);
        
        $shoppingListItemInstance = new ShoppingListItem();
            //dd($validatedData); 
        $shoppingListItemInstance->user_id = Auth::user()->id;
        $shoppingListItemInstance->shopping_list_id = $shoppingListID;
        $shoppingListItemInstance->name = $validatedData['name'];
        $shoppingListItemInstance->purchase_price = $validatedData['purchase_price'];
        $shoppingListItemInstance->amount = $validatedData['amount'];
        $shoppingListItemInstance->status = $validatedData['status'];
        $shoppingListItemInstance->notes = $validatedData['notes'];
        
        $shoppingListItemInstance = $this->resolveSingleItemNameToTypeID($shoppingListItemInstance);

            //if item is already purchased - and the user wants to- make a new inventory item for it
        if($request->inventoryCheckBox && $validatedData['status'] === 'Purchased'){
            $this->createNewInventoryItemFromShoppingListItem($shoppingListItemInstance);
        }

        $shoppingListItemInstance->save();

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ShoppingListItem  $shoppingListItem
     * @return \Illuminate\Http\Response
     */
    public function show(ShoppingListItem $shoppingListItem)
    {
        $assignedShoppingList = ShoppingList::where('user_id', Auth::user()->id)
                                ->where('id', $shoppingListItem->shopping_list_id)->first();
    
        $transactions = $this->getTransactionsWithSameTypeID($shoppingListItem);

        $transactions = $this->resolveTypeIDToItemName($transactions);

        return view('shoppinglistitem.shoppinglistitem_details', compact('shoppingListItem', 'assignedShoppingList','transactions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ShoppingListItem  $shoppingListItem
     * @return \Illuminate\Http\Response
     */
    public function edit(ShoppingListItem $shoppingListItem)
    {
       // dd($shoppingListItem);
        

        return view('shoppinglistitem.shoppinglistitem_edit', compact('shoppingListItem'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ShoppingListItem  $shoppingListItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ShoppingListItem $shoppingListItem)
    {
        $validatedData = $request->validate([
        'name' => 'required|max:255',
        'purchase_price' => 'nullable|integer',
        'sell_price' => 'nullable|integer',
        'taxes_paid' => 'nullable|integer',
        'amount' => 'nullable|integer',
        'par' => 'nullable|integer',
        'current_location' => 'nullable',
        'status' => 'required',
        'notes' => 'nullable|max:1000',
        ]);
        
        $shoppingListItemInstance = ShoppingListItem::where('user_id', Auth::user()->id)->where('id', $shoppingListItem->id)->first();

        $shoppingListItemInstance->user_id = Auth::user()->id;
        $shoppingListItemInstance->shopping_list_id = $shoppingListItem->shopping_list_id;
        $shoppingListItemInstance->name = $validatedData['name'];
        $shoppingListItemInstance->purchase_price = $validatedData['purchase_price'];
        $shoppingListItemInstance->sell_price = $validatedData['sell_price'];
        $shoppingListItemInstance->taxes_paid = $validatedData['taxes_paid'];
        $shoppingListItemInstance->amount = $validatedData['amount'];
        $shoppingListItemInstance->par = $validatedData['par'];
        $shoppingListItemInstance->current_location = $validatedData['current_location'];
        $shoppingListItemInstance->status = $validatedData['status'];
        $shoppingListItemInstance->notes = $validatedData['notes'];
        
        $shoppingListItemInstance = $this->resolveSingleItemNameToTypeID($shoppingListItemInstance);

        //if item is already purchased make a new inventory item for it
        if($request->inventoryCheckBox && $validatedData['status'] === 'Purchased'){
            $this->createNewInventoryItemFromShoppingListItem($shoppingListItemInstance);
        }

        $shoppingListItemInstance->save();

        return redirect('/shoppinglistitem/' . $shoppingListItem->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ShoppingListItem  $shoppingListItem
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShoppingListItem $shoppingListItem)
    {
        $shoppingListItemInstance = ShoppingListItem::where('id', $shoppingListItem->id)->first();

        $shoppingListItemInstance->delete();

        return back();
    }
}
