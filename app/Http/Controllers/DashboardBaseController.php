<?php

namespace App\Http\Controllers;
use App\Character;
use App\User;
use App\MarketOrders;
use App\Transactions;
use App\ShoppingList;
use App\ShoppingListItem;
use App\Inventory;
use App\Logistics;
use App\EveItemIDModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;

class DashboardBaseController extends EveBaseController
{
    public function getMarketOrdersForDashboard($currentSelectedCharacter){
            $marketOrders = app('App\Http\Controllers\MarketOrdersController')->getMarketOrdersForSelectedCharacter($currentSelectedCharacter);
            $marketOrders = app('App\Http\Controllers\MarketOrdersController')->saveMarketOrdersToDB($marketOrders);
            $marketOrders = app('App\Http\Controllers\MarketOrdersController')->resolveTypeIDToItemName($marketOrders);
            $marketOrders = app('App\Http\Controllers\MarketOrdersController')->resolveStationIDToName($currentSelectedCharacter, $marketOrders);
            app('App\Http\Controllers\MarketBaseController')->updateAttachedInventoryItems($marketOrders);
            return $marketOrders;
    }

    public function getTransactionHistoryForDashboard($currentSelectedCharacter){
            $transactionHistory = app('App\Http\Controllers\TransactionsController')->getTransactionHistory($currentSelectedCharacter);
            $transactionHistory = app('App\Http\Controllers\TransactionsController')->saveTransactionsToDB($transactionHistory);
            $transactionHistory = app('App\Http\Controllers\TransactionsController')->resolveTypeIDToItemName($transactionHistory);
            $transactionHistory = collect($transactionHistory)->sortByDesc('date');

            return $transactionHistory;
    }

    public function getTotalIskOnMarket($marketOrders){
        $totalIskOnMarket = 0;
        if($marketOrders !== null){
            foreach($marketOrders as $marketOrder){
                if($marketOrder->price !== null && $marketOrder->volume_remain !== null){
                    $pricePerUnit = $marketOrder->price;
                    $unitsRemaining = $marketOrder->volume_remain;
                    $totalIskOnMarket += ($pricePerUnit * $unitsRemaining);
                }
            }
        }
        return $totalIskOnMarket;
    }

    public function getNumberOfShoppingListItemsNotPurchased($currentSelectedCharacter){
        $shoppingListItems = ShoppingListItem::where('user_id', Auth::user()->id)
                                            ->where('status','!=' ,'Purchased')  
                                            ->where(function($query){
                                                $query->where('amount_purchased', '<', 'amount')
                                                    ->orWhereNull('amount_purchased');
                                            })
                                            ->get();
        
        return count($shoppingListItems);
    }

    public function getUndeliveredLogisticsGroups($currentSelectedCharacter){
        $undeliveredLogisticsGroups = Logistics::where('user_id', Auth::user()->id)
                                                ->where('status', '!=', 'Delivered')
                                                ->get();
       
        return count($undeliveredLogisticsGroups);
    }

    public function getInventoryStats($currentSelectedCharacter){

        $inventoryItems = Inventory::where('user_id', Auth::user()->id)
                                    ->get();

        $numberOfItemsInInventory = count($inventoryItems);
        $totalIskAmountInInventory = 0;      
        $numberOfInventoryItemsNotOnMarket = 0;  

        foreach($inventoryItems as $inventoryItem){
            $totalIskAmountInInventory += ($inventoryItem->sell_price * $inventoryItem->amount_remain);

            if($inventoryItem->market_order_id == null || $inventoryItem->market_order_id == 0){
                $numberOfInventoryItemsNotOnMarket ++;
            }
        }

        $inventoryStats =[
            'numberOfItemsInInventory' => $numberOfItemsInInventory,
            'totalIskAmountInInventory' => $totalIskAmountInInventory,
            'numberOfInventoryItemsNotOnMarket' => $numberOfInventoryItemsNotOnMarket,
        ];

        return $inventoryStats;
    }

    public function getDashBoardStats(Request $request){
        /*
            This method will update market orders, transactions, and associated items such as inventory amounts and pars.
            
            TODO: Read up on traits for the purposes of refactoring this method. We are pulling methods from other controllers,
            and this is considered bad form especially for code organization(hence, the need for a refactor).

            Traits:https://www.php.net/manual/en/language.oop5.traits.php

            Due to time-constraints we will need to pull from other controllers like so:
                app('App\Http\Controllers\MarketOrdersController')->getMarketOrders($variable);
        */
        /*
                Second note: to tell if the request is an ajax call or not:
                        https://stackoverflow.com/questions/29868903/laravel-5-return-json-or-view-depends-if-ajax-or-not
        */
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
            /*
        return view('dashboard',
                     compact('marketOrders',
                            'transactionHistory',
                            'inventoryItemsUnderPar',
                            'currentSelectedCharacter',
                            ));
            */
    }
            
}
