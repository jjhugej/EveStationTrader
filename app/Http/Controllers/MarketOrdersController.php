<?php

namespace App\Http\Controllers;


use App\Character;
use App\User;
use App\MarketOrders;
use App\EveItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;

class MarketOrdersController extends MarketBaseController

{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {        
        $currentSelectedCharacter = $this->getSelectedCharacter();
        
        //check to make sure the user has a character selected, if not send them back to the character page to select a character
        if($currentSelectedCharacter !== null && $currentSelectedCharacter->is_selected_character === 1){

            $currentSelectedCharacter = $this->checkTokens($currentSelectedCharacter);
     
            $marketOrders = $this->getMarketOrders($currentSelectedCharacter);

            $this->saveMarketOrdersToDB($marketOrders);

            //convert type_id to item name should look like: $this->convertTypeIDToItemName($type_id)
            $marketOrders = $this->resolveTypeIDToItemName($marketOrders);

            $marketOrders = $this->resolveStationIDToName($currentSelectedCharacter, $marketOrders);

            return view('marketorders', compact('marketOrders'));
        }
        else{
            //redirect to characters because none are selected and flash an error message
            $request->session()->flash('error', 'You Must Select A Character Before Proceeding');

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
     * @param  \App\MarketOrders  $marketOrders
     * @return \Illuminate\Http\Response
     */
    public function show(MarketOrders $marketOrders)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MarketOrders  $marketOrders
     * @return \Illuminate\Http\Response
     */
    public function edit(MarketOrders $marketOrders)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MarketOrders  $marketOrders
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MarketOrders $marketOrders)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MarketOrders  $marketOrders
     * @return \Illuminate\Http\Response
     */
    public function destroy(MarketOrders $marketOrders)
    {
        //
    }
}
