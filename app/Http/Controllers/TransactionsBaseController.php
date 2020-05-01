<?php

namespace App\Http\Controllers;

use App\Character;
use App\User;
use App\MarketOrders;
use App\EveItem;
use App\StructureName;
use App\Transactions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;

class TransactionsBaseController extends EveBaseController
{
    public function getTransactionHistory($character){
        $selectedCharacter = Character::where('user_id', Auth::user()->id)->where('is_selected_character', 1)->first();
        
        //TODO: add in next avalaible esi fetch to speed shit up and reduce esi calls

        
        //check if the selected character has made an ESI call within the last 10 minutes (or whatever $selectedCharacter->next_available_esi_market_fetch is set to when orders are retrieved from esi)
        if($selectedCharacter->next_available_esi_transactions_fetch !== null && 
            $selectedCharacter->next_available_esi_transactions_fetch > Carbon::now()->toDateTimeString()){

                //if true selected character has made an ESI call too recently, and its saved market orders will be returned instead of making another ESI call
    
                $data = Transactions::where('user_id', Auth::user()->id)->where('character_id', $selectedCharacter->character_id)->get();   
               
                //$data->character_name = $this->resolveMultipleCharacterNamesFromIDs($data);
               
                return($data);
            }
        else{
                //else the character may make another ESI call since enough time has passed
            //dd('esi called');
                $client = new Client();
                try{
                    $character_orders_url = "https://esi.evetech.net/latest" . "/characters/" . $character->character_id . "/wallet/transactions/";
                    $auth_headers = [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $character->access_token,
                            'User-Agent' => config('app.eveUserAgent'),
                            'Content-Type' => 'application/x-www-form-urlencoded',
                        ]
                    ];
                    $resp = $client->get($character_orders_url, $auth_headers);
                    $data = json_decode($resp->getBody());
                    
                    //update the last transactions fetch for the selected character                    
                    $selectedCharacter = Character::where('user_id', Auth::user()->id)->where('is_selected_character', 1)->first();
                    $selectedCharacter->next_available_esi_transactions_fetch = Carbon::now()->addminutes(20)->toDateTimeString(); 
        
                    $selectedCharacter->save();
                    
                    return($data);
                }   
                catch(Exception $e){
                    dd('error verifying character information' . $e);
                }
        }
    }

    public function saveTransactionsToDB($transactionHistorys){
        
        $transactionArray = [];

        foreach($transactionHistorys as $transactionHistory){

            $currentSelectedCharacter = Character::where('user_id', Auth::user()->id)->where('is_selected_character', 1)->first();
           
            //first check if transaction exists in database already
           
            if(Transactions::where('transaction_id', $transactionHistory->transaction_id)->first() !== null){
                $transactionHistoryInstance = Transactions::where('transaction_id', $transactionHistory->transaction_id)->first();
                
                $transactionHistoryInstance->user_id = Auth::user()->id;
                $transactionHistoryInstance->character_id = $currentSelectedCharacter->character_id;
                $transactionHistoryInstance->journal_ref_id = $transactionHistory->journal_ref_id;
                $transactionHistoryInstance->transaction_id = $transactionHistory->transaction_id;
                $transactionHistoryInstance->location_id = $transactionHistory->location_id;
                $transactionHistoryInstance->type_id = $transactionHistory->type_id;
                $transactionHistoryInstance->quantity = $transactionHistory->quantity;
                $transactionHistoryInstance->unit_price = $transactionHistory->unit_price;
                $transactionHistoryInstance->is_buy = $transactionHistory->is_buy;
                $transactionHistoryInstance->is_personal = $transactionHistory->is_personal;
                $transactionHistoryInstance->date = $this->convertEsiDateTime($transactionHistory->date);
                
                //dd('if', $transactionHistoryInstance);  
                $transactionHistoryInstance->save();
                
                array_push($transactionArray, $transactionHistoryInstance);

            }else{
                //else the transaction is not in the DB and needs to be newed up
                $transactionHistoryInstance = new Transactions();
                
                $transactionHistoryInstance->user_id = Auth::user()->id;
                $transactionHistoryInstance->character_id = $currentSelectedCharacter->character_id;
                $transactionHistoryInstance->journal_ref_id = $transactionHistory->journal_ref_id;
                $transactionHistoryInstance->transaction_id = $transactionHistory->transaction_id;
                $transactionHistoryInstance->location_id = $transactionHistory->location_id;
                $transactionHistoryInstance->type_id = $transactionHistory->type_id;
                $transactionHistoryInstance->quantity = $transactionHistory->quantity;
                $transactionHistoryInstance->unit_price = $transactionHistory->unit_price;
                $transactionHistoryInstance->is_buy = $transactionHistory->is_buy;
                $transactionHistoryInstance->is_personal = $transactionHistory->is_personal;
                $transactionHistoryInstance->date = $this->convertEsiDateTime($transactionHistory->date);
                
                //dd('else', $transactionHistoryInstance);
                $transactionHistoryInstance->save();
    
                array_push($transactionArray, $transactionHistoryInstance);
            }
        }
        $transactionArray = collect($transactionArray);
        //dd('transaction array collected', $transactionArray);
        return $transactionArray;

       // dd('saveTransactionsToDB->transactionhistorys', $transactionHistorys);
    }
}

