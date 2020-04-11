<?php

namespace App\Http\Controllers;

use App\Transactions;
use App\EveItem;
use Illuminate\Http\Request;

class TransactionsController extends TransactionsBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $currentSelectedCharacter = $this->getSelectedCharacter();

         if($currentSelectedCharacter !== null && $currentSelectedCharacter->is_selected_character === 1){

            $currentSelectedCharacter = $this->checkTokens($currentSelectedCharacter);
     
            $transactionHistory = $this->getTransactionHistory($currentSelectedCharacter);

            $transactionHistory = $this->saveTransactionsToDB($transactionHistory);
            
            $transactionHistory = $this->resolveTypeIDToItemName($transactionHistory);

            $transactionHistory = collect($transactionHistory)->sortByDesc('date');

            //dd('index method->resolveTypeIDToItemName()', $transactionHistory);

            return view('transactions.transactions', compact('transactionHistory'));
        }
        else{
            //redirect to characters because none are selected and flash an error message
            session()->flash('error', 'You Must Select A Character Before Proceeding');

            return redirect('/characters');
        }
        

        

        
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Transactions  $transactions
     * @return \Illuminate\Http\Response
     */
    public function show(Transactions $transactions)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Transactions  $transactions
     * @return \Illuminate\Http\Response
     */
    public function edit(Transactions $transactions)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Transactions  $transactions
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transactions $transactions)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Transactions  $transactions
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transactions $transactions)
    {
        //
    }

    public function search(Request $request){
        //This method is used to return data from the eveitems table via the search method on the item_create view (specifically the item name input field).
        //searchRequest is the variable that comes from the ajax get request
        
        //TODO: hook up search feature with transactions
        $searchRequest = $request->searchRequest;
        if($searchRequest !== null){
            $searchMatches = EveItem::where('typeName', 'LIKE','%'.$searchRequest.'%')->take(30)->get();
            return view('inventory._item_search', compact('searchMatches'));
        }
    }
    
}
