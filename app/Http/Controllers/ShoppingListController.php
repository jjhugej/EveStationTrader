<?php

namespace App\Http\Controllers;

use App\ShoppingList;
use App\ShoppingListItem;
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

class ShoppingListController extends EveBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shoppingLists = ShoppingList::where('user_id', Auth::user()->id)->get();

        return view('shoppinglist.shoppinglist', compact('shoppingLists'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('shoppinglist.shoppinglist_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
        'name' => 'required|max:255',
        'notes' => 'nullable|max:1000',
        ]);

        $shoppingListInstance = new ShoppingList();
        
        $shoppingListInstance->user_id = Auth::user()->id;
        $shoppingListInstance->name = $validatedData['name'];
        $shoppingListInstance->notes = $validatedData['notes'];

        $shoppingListInstance->save();

        return redirect('/shoppinglist');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ShoppingList  $shoppingList
     * @return \Illuminate\Http\Response
     */
    public function show(ShoppingList $shoppingList)
    {
        $shoppingListItems = ShoppingListItem::where('user_id', Auth::user()->id)->where('shopping_list_id', $shoppingList->id)->get();

        return view('shoppinglist.shoppinglist_details', compact('shoppingList', 'shoppingListItems'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ShoppingList  $shoppingList
     * @return \Illuminate\Http\Response
     */
    public function edit(ShoppingList $shoppingList)
    {
        //dd($shoppingList);

        return view('shoppinglist.shoppinglist_edit', compact('shoppingList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ShoppingList  $shoppingList
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ShoppingList $shoppingList)
    {
        $validatedData = $request->validate([
        'name' => 'required|max:255',
        'notes' => 'nullable|max:1000',
        ]);

        $shoppingListInstance = ShoppingList::where('id', $shoppingList->id)->first();
        
        $shoppingListInstance->user_id = Auth::user()->id;
        $shoppingListInstance->name = $validatedData['name'];
        $shoppingListInstance->notes = $validatedData['notes'];

        $shoppingListInstance->save();

        return redirect('/shoppinglist');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ShoppingList  $shoppingList
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShoppingList $shoppingList)
    {
        $shoppingListInstance = ShoppingList::where('id', $shoppingList->id)->first();

        $shoppingListInstance->delete();

        return redirect('/shoppinglist');
    }
}
