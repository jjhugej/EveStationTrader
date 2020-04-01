<?php

namespace App\Http\Controllers;

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
class MarketBaseController extends EveBaseController
{

    public function getMarketOrders($character){    

        //TODO: Differentiate between orders for each character and ALL market orders...


        $selectedCharacter = Character::where('user_id', Auth::user()->id)->where('is_selected_character', 1)->first();
        
        
        //check if the selected character has made an ESI call within the last 10 minutes (or whatever $selectedCharacter->next_available_esi_market_fetch is set to when orders are retrieved from esi)
        if($selectedCharacter->next_available_esi_market_fetch !== null && 
            $selectedCharacter->next_available_esi_market_fetch < Carbon::now()->toDateTimeString()){

                //if true selected character has made an ESI call too recently, and its saved market orders will be returned instead of making another ESI call
                
                $data = MarketOrders::where('user_id', Auth::user()->id)->where('current_selected_character', 1)->get();   

                $data->character_name = $this->resolveMultipleCharacterNamesFromIDs($data);
                dd($data);
                return($data);
            }
            else{
                
                //else the character may make another ESI call since enough time has passed
                $client = new Client();
                try{
                    $character_orders_url = "https://esi.evetech.net/latest" . "/characters/" . $character->character_id . "/orders/";
                    $auth_headers = [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $character->access_token,
                            'User-Agent' => config('app.eveUserAgent'),
                            'Content-Type' => 'application/x-www-form-urlencoded',
                        ]
                    ];
                    $resp = $client->get($character_orders_url, $auth_headers);
                    $data = json_decode($resp->getBody());
                    
                    //update the last market fetch for the selected character
                    /*
                    $selectedCharacter = Character::where('user_id', Auth::user()->id)->where('is_selected_character', 1)->first();
                    $selectedCharacter->next_available_esi_market_fetch = Carbon::now()->addminutes(2)->toDateTimeString(); 
            
                    $selectedCharacter->save();
                    */

                    return($data);
                }   
                catch(Exception $e){
                    dd('error verifying character information' . $e);
                }
            }
    }

    public function saveMarketOrdersToDB($marketOrders){
        
        $marketOrderArray = [];

        foreach($marketOrders as $marketOrder){
            
            //check if the marketOrder already exists in the DB
            if(MarketOrders::where('order_id', $marketOrder->order_id)->first() !== null){
                //if marketOrder exists, update the record
                $currentSelectedCharacter = Character::where('user_id', Auth::user()->id)->where('is_selected_character', 1)->first();

                $marketOrderInstance = MarketOrders::where('order_id', $marketOrder->order_id)->first();
                
                $marketOrderInstance->order_id = $marketOrder->order_id;
                $marketOrderInstance->user_id = Auth::user()->id;
                $marketOrderInstance->character_id = $currentSelectedCharacter->character_id;
                $marketOrderInstance->duration = $marketOrder->duration;
                $marketOrderInstance->is_corporation = $marketOrder->is_corporation;
                $marketOrderInstance->issued = $this->convertEsiDateTime($marketOrder->issued);
                $marketOrderInstance->location_id = $marketOrder->location_id;
                $marketOrderInstance->price = $marketOrder->price;
                $marketOrderInstance->range = $marketOrder->range;
                $marketOrderInstance->region_id = $marketOrder->region_id;
                $marketOrderInstance->type_id = $marketOrder->type_id;
                $marketOrderInstance->volume_remain = $marketOrder->volume_remain;
                $marketOrderInstance->volume_total = $marketOrder->volume_total;
                
                $marketOrderInstance->save(); 

                $marketOrderInstance->character_name = $this->resolveSingleCharacterNameFromID($currentSelectedCharacter->character_id);
                
                array_push($marketOrderArray, $marketOrderInstance);
               
            }else{
                //if record doesn't exist create a new record in the DB

                $currentSelectedCharacter = Character::where('user_id', Auth::user()->id)->where('is_selected_character', 1)->first();

                $marketOrderInstance = new MarketOrders();

                $marketOrderInstance->order_id = $marketOrder->order_id;
                $marketOrderInstance->user_id = Auth::user()->id;
                $marketOrderInstance->character_id = $currentSelectedCharacter->character_id;
                $marketOrderInstance->duration = $marketOrder->duration;
                $marketOrderInstance->is_corporation = $marketOrder->is_corporation;
                $marketOrderInstance->issued = $this->convertEsiDateTime($marketOrder->issued);
                $marketOrderInstance->location_id = $marketOrder->location_id;
                $marketOrderInstance->price = $marketOrder->price;
                $marketOrderInstance->range = $marketOrder->range;
                $marketOrderInstance->region_id = $marketOrder->region_id;
                $marketOrderInstance->type_id = $marketOrder->type_id;
                $marketOrderInstance->volume_remain = $marketOrder->volume_remain;
                $marketOrderInstance->volume_total = $marketOrder->volume_total;

                $marketOrderInstance->save();

                $marketOrderInstance->character_name = $this->resolveSingleCharacterNameFromID($currentSelectedCharacter->character_id);
            
                array_push($marketOrderArray, $marketOrderInstance);
            }
        }  
        //dd($marketOrderArray,'saving marketorders to db', $marketOrders);
        return $marketOrderArray;
    }

}
   
