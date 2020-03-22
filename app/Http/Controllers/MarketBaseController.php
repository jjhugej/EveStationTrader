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
class MarketBaseController extends EveBaseController
{

    public function getSelectedCharacter(){

        if(Auth::check()){
            $user = Auth::user();

            //check to make sure the user has a character selected before setting variables
            if($user->current_selected_character_id !== null){
                $currentSelectedCharacter = Character::where('character_id', $user->current_selected_character_id)->first();
                return $currentSelectedCharacter;
            }
        }else{
            return redirect('/login');
        }
    }

    public function getMarketOrders($character){    

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
            return($data);
        }   
        catch(Exception $e){
            dd('error verifying character information' . $e);
        }
    }

    public function saveMarketOrdersToDB($marketOrders){
        //dd($marketOrders);
        foreach($marketOrders as $marketOrder){
            
            //check if the marketOrder already exists in the DB
            if(MarketOrders::where('order_id', $marketOrder->order_id)->first() !== null){
                //if marketOrder exists, update the record
                $marketOrderInstance = MarketOrders::where('order_id', $marketOrder->order_id)->first();
            
                $marketOrderInstance->order_id = $marketOrder->order_id;
                $marketOrderInstance->user_id = Auth::user()->id;
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
            }else{
                //if record doesn't exist create a new record in the DB

                $marketOrderInstance = new MarketOrders();

                $marketOrderInstance->order_id = $marketOrder->order_id;
                $marketOrderInstance->user_id = Auth::user()->id;
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
            }
        }  
    }

    public function resolveTypeIDToItemName($marketOrders){
        /*
            CCP sends back a type_id which corresponds to an item name from their static dump file.
            The table name for the item names from the dump is: invTypes. Renamed to eveItems in our DB
            NOTE: because this is a static table it must be imported every time a migration refresh is done.

            SECOND NOTE: THIS WILL RETURN THE OBJECT BACK WITH A PROPERTY OF "typeName" WHICH IS NOT PERSISTED ON THE "eveItem" TABLE
        */
        //dd($marketOrders);

        foreach($marketOrders as $marketOrder){
            $typeName = EveItem::where('typeID', $marketOrder->type_id)->pluck('typeName')->first();
            $marketOrder->typeName = $typeName;
        }
        return $marketOrders;
    }

    public function resolveStationIDToName($character, $marketOrders){
        //THIS WILL RETURN THE OBJECT BACK WITH A PROPERTY OF "stationName" WHICH IS NOT PERSISTED ON THE "eveItem" TABLE

        //initialize the locationID and locationName arrays
        $locationIDArray = [];
        $locationNameArray = [];
        
        //first, loop through market orders plucking the location ID of each only once, and pushing to an array
        foreach($marketOrders as $marketOrder){
            if(!in_array($marketOrder->location_id, $locationIDArray)){
                array_push($locationIDArray,$marketOrder->location_id);
            }
        }
        //dd(implode('|',$locationIDArray));

        
        
        foreach($locationIDArray as $locationIDArrayItem){
            //if locationIdArray[i] is <100,000,000 it is not a structure, it is a station

            if($locationIDArrayItem > 100000000){
                //guzzle request for structures
                $client = new Client();
                try{
                    $station_url = "https://esi.evetech.net/latest" . "/universe/structures/" . $locationIDArrayItem;
                    $auth_headers = [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $character->access_token,
                            'User-Agent' => config('app.eveUserAgent'),
                            'Content-Type' => 'application/x-www-form-urlencoded',
                        ]
                    ];
                    $resp = $client->get($station_url, $auth_headers);
                    $data = json_decode($resp->getBody());
                    //dd($station_url,$resp, $data);
                    array_push($locationNameArray,$data->name);
                    //return($data);
                }   
                catch(Exception $e){
                    dd('error verifying character information' . $e);
                }
            }else{
                //guzzle request for stations
                $client = new Client();
                try{
                    $station_url = "https://esi.evetech.net/latest" . "/universe/stations/" . $locationIDArrayItem;
                    $auth_headers = [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $character->access_token,
                            'User-Agent' => config('app.eveUserAgent'),
                            'Content-Type' => 'application/x-www-form-urlencoded',
                        ]
                    ];
                    $resp = $client->get($station_url, $auth_headers);
                    $data = json_decode($resp->getBody());
                    //dd($station_url,$resp, $data);
                    array_push($locationNameArray,$data->name);
                    //return($data);
                }   
                catch(Exception $e){
                    dd('error verifying character information' . $e);
                }
            }

        }
        //dd($locationIDArray, $locationNameArray, array_combine($locationIDArray, $locationNameArray));

        //combine the idArray with the name Array to get an associative array. return the associative array
        //****if the key->value pair is not in the DB, save it for future checks to reduce the amount of requests to ESI*********************
        $idToNameArray = array_combine($locationIDArray, $locationNameArray);

        //finally set a non-persisted attribute, "locationName" to the marketOrder object. and then return all market orders
        foreach($marketOrders as $marketOrder){
            $marketOrder->locationName = $idToNameArray[$marketOrder->location_id]; 
        }
        return $marketOrders;
    }

    
//*** figure out how to cache the location ids/names so esi doesnt spank you */


    
}
