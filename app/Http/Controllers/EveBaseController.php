<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use App\Character;

class EveBaseController extends Controller
{
    //base controller that contains helper functions for common interactions between ESI
    public function getEsiTokens($request){

       if(isset($_GET['code'])){
            
            $client = new Client();

            try{
                $authSite = 'https://login.eveonline.com/oauth/token';
                $token_headers = [
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode(config('app.eveClientId') . ':' . config('app.eveSecretKey')),
                    'User-Agent' => config('app.eveUserAgent'),
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
                'form_params' => [
                    'grant_type' => 'authorization_code',
                    'code' => $request->code
                ]
                ];
                $resp = $client->post($authSite, $token_headers);
                $tokens = json_decode($resp->getBody());  
            }
            catch(\Exception $e){
                dd('exception caught' . $e);
            }

        }
        else{
            dd('failed to get access code');
        }

        try{
            $verifySite = 'https://login.eveonline.com/oauth/verify';
            $verify_headers = [
                'headers' =>[
                    'Authorization' => 'Bearer ' . $tokens->access_token,
                    'User-Agent' => config('app.eveUserAgent'),
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
            ];
            $resp = $client->get($verifySite, $verify_headers);
            $verify = json_decode($resp->getBody());
        }
        catch(\Exception $e){
            dd('failed to get character id' . $e);
        }

        try{
            
            $character_orders_url = "https://esi.evetech.net/latest" . "/characters/" . $verify->CharacterID . "/orders/";
            $auth_headers = [
                'headers' => [
                    'Authorization' => 'Bearer ' . $tokens->access_token,
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

    public function getItemNameFromId($itemId){
        
    }

    public function checkAccessToken($id){
        $character = \App\Character::find('id', $id)->get();
        dd($character);
    }
}
