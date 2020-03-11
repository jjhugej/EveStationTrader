<?php

namespace App\Http\Controllers;

use App\Character;
use App\User;
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
            if($user->current_selected_character_id !== null){
                //check to make sure the user has a character selected before setting variables
                //$currentSelectedCharacterID = $user->current_selected_character_id;
                $currentSelectedCharacter = Character::where('character_id', $user->current_selected_character_id)->first();
                return $currentSelectedCharacter;
            }
        }else{
            return redirect('/login');
        }
    }

    public function getMarketOrders($character){
        //dd($character->character_id, $character->access_token);
        //***instead of making a function for each endppoint a function should be made to take a param and input the proper URL inputs***
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
        catch(\Exception $e){
            dd('error verifying character information' . $e);
        }
    }
}
