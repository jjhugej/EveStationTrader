<?php

namespace App\Http\Controllers;

use App\MarketOrders;

use App\Character;
use App\User;
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
    public function index()
    {        
        $currentSelectedCharacter = $this->getSelectedCharacter();

        //check tokens(if true token is expired)
        $this->checkTokens($currentSelectedCharacter);

        $marketOrders = $this->getMarketOrders($currentSelectedCharacter);

        dd($marketOrders);
        
        // ***left off here. market orders are properly coming in, now just need to send to view***
        return view('marketorders', compact('marketOrders'));
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
