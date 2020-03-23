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

}
   
