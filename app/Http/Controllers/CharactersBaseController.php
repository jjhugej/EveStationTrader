<?php

namespace App\Http\Controllers;

use App\Character;
use App\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Http\Request;


class CharactersBaseController extends EveBaseController
{
    public function getCharacterPortrait($character){
        $client = new Client();
            try{
                $character_orders_url = "https://esi.evetech.net/latest" . "/characters/" . $character->character_id . "/portrait/";
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

    public function setCharacterPortrait($character){
        //dd($character);
        //ccp returns a few different options for portraits, the 256 seems to be the appropriate size that we need
        //sample of portrait url: https://images.evetech.net/Character/90169631_256.jpg
        $characterPortrait = $character->portrait->px256x256;
        
        //return the portrait as a session variable to be accessed by the view
        session(['characterPortrait' => $characterPortrait]);
    }

    public function unsetCharacterPortrait($request){
        $request->session()->forget('characterPortrait');
    }
}
