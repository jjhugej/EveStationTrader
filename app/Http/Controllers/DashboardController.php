<?php

namespace App\Http\Controllers;
use App\Character;
use App\User;
use App\MarketOrders;
use App\Transactions;
use App\Inventory;
use App\EveItemIDModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;



class DashboardController extends DashboardBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        
        return view('dashboard.dashboard');        
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getDashboardStats(Request $request){

        $currentSelectedCharacter = $this->getSelectedCharacter();

        if($currentSelectedCharacter !== null && $currentSelectedCharacter->is_selected_character === 1){

            $currentSelectedCharacter = $this->checkTokens($currentSelectedCharacter);

            //set the character portrait
            $character = Character::where('user_id', $currentSelectedCharacter->id)
                                            ->where('is_selected_character', true)
                                            ->first();

            if($character->portrait !== null && $character->portrait !==0){
                session(['characterPortrait' => $character->portrait]);
            }
            
            //market orders
            $marketOrders = $this->getMarketOrdersForDashboard($currentSelectedCharacter);
            $totalIskOnMarket = $this->getTotalIskOnMarket($marketOrders);
                
              
            //transactions
            $transactionHistory = $this->getTransactionHistoryForDashboard($currentSelectedCharacter);

            //shopping list
            $numberOfShoppingListItemsNotPurchased = $this->getNumberOfShoppingListItemsNotPurchased($currentSelectedCharacter);
            
               


            //delivery groups that need to be delivered
            $undeliveredLogisticsGroups = $this->getUndeliveredLogisticsGroups($currentSelectedCharacter);
            

            //inventory
            $inventoryStats = $this->getInventoryStats($currentSelectedCharacter);
            
            //pars
            $inventoryItemsUnderPar = Inventory::where('user_id', $currentSelectedCharacter->id)
                                                ->whereRaw('amount_remain < par')
                                                ->get();

            $inventoryItemsUnderParCount = count($inventoryItemsUnderPar);
 
        }else{
            //redirect to characters because none are selected and flash an error message

            $request->session()->flash('error', 'You Must Select A Character Before Proceeding');

            return redirect('/characters');
        }
        
        return ([
            'marketOrders' => $marketOrders,
            'totalIskOnMarket' => $totalIskOnMarket,
            'numberOfShoppingListItemsNotPurchased' => $numberOfShoppingListItemsNotPurchased,
            'transactionHistory' => $transactionHistory,
            'inventoryStats' => $inventoryStats,
            'inventoryItemsUnderParCount' => $inventoryItemsUnderParCount,
            'inventoryItemsUnderPar' => $inventoryItemsUnderPar,
            'currentSelectedCharacter' => $currentSelectedCharacter
        ]);
    }
}
