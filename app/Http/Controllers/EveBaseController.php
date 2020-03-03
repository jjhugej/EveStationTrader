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


class EveBaseController extends Controller
            /*
                Most functions here are made for the purpose of abstracting long bits of code into functions that are
                callable in the EveLoginController.

                The EveLoginController extends this class.

            */
{
    public function eveLogin(){

        // first step of eve esi access token
        $eveAuthBaseUrl = 'https://login.eveonline.com/oauth/authorize';
        $eveRedirectUri = config('app.eveCallbackUri');
        $eveClientId = config('app.eveClientId');
        $eveSecretKey = config('app.eveSecretKey');
        $eveScopes = config('app.eveScopes');
        $redirectUrl = $eveAuthBaseUrl . '?response_type=code&redirect_uri=' . $eveRedirectUri
            . '&client_id=' . $eveClientId . '&scope=' . 'publicData esi-calendar.respond_calendar_events.v1 esi-calendar.read_calendar_events.v1 esi-location.read_location.v1 esi-location.read_ship_type.v1 esi-mail.organize_mail.v1 esi-mail.read_mail.v1 esi-mail.send_mail.v1 esi-skills.read_skills.v1 esi-skills.read_skillqueue.v1 esi-wallet.read_character_wallet.v1 esi-wallet.read_corporation_wallet.v1 esi-search.search_structures.v1 esi-clones.read_clones.v1 esi-characters.read_contacts.v1 esi-universe.read_structures.v1 esi-bookmarks.read_character_bookmarks.v1 esi-killmails.read_killmails.v1 esi-corporations.read_corporation_membership.v1 esi-assets.read_assets.v1 esi-planets.manage_planets.v1 esi-fleets.read_fleet.v1 esi-fleets.write_fleet.v1 esi-ui.open_window.v1 esi-ui.write_waypoint.v1 esi-characters.write_contacts.v1 esi-fittings.read_fittings.v1 esi-fittings.write_fittings.v1 esi-markets.structure_markets.v1 esi-corporations.read_structures.v1 esi-characters.read_loyalty.v1 esi-characters.read_opportunities.v1 esi-characters.read_chat_channels.v1 esi-characters.read_medals.v1 esi-characters.read_standings.v1 esi-characters.read_agents_research.v1 esi-industry.read_character_jobs.v1 esi-markets.read_character_orders.v1 esi-characters.read_blueprints.v1 esi-characters.read_corporation_roles.v1 esi-location.read_online.v1 esi-contracts.read_character_contracts.v1 esi-clones.read_implants.v1 esi-characters.read_fatigue.v1 esi-killmails.read_corporation_killmails.v1 esi-corporations.track_members.v1 esi-wallet.read_corporation_wallets.v1 esi-characters.read_notifications.v1 esi-corporations.read_divisions.v1 esi-corporations.read_contacts.v1 esi-assets.read_corporation_assets.v1 esi-corporations.read_titles.v1 esi-corporations.read_blueprints.v1 esi-bookmarks.read_corporation_bookmarks.v1 esi-contracts.read_corporation_contracts.v1 esi-corporations.read_standings.v1 esi-corporations.read_starbases.v1 esi-industry.read_corporation_jobs.v1 esi-markets.read_corporation_orders.v1 esi-corporations.read_container_logs.v1 esi-industry.read_character_mining.v1 esi-industry.read_corporation_mining.v1 esi-planets.read_customs_offices.v1 esi-corporations.read_facilities.v1 esi-corporations.read_medals.v1 esi-characters.read_titles.v1 esi-alliances.read_contacts.v1 esi-characters.read_fw_stats.v1 esi-corporations.read_fw_stats.v1 esi-characterstats.read.v1';

        //we redirect to eve and eve authenticates user and then sends back to website using callback url on eve dev website
        return $redirectUrl;
    }

    public function updateTokens(){
        //update refresh tokens here

    }

    public function getEsiTokens($request){

       if(isset($_GET['code'])){
            
            $client = new Client();

            try{
                $authSite = 'https://login.eveonline.com/oauth/token';
                $token_headers = [
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode(config('app.eveClientId') . ':' . config('app.eveSecretKey')),
                    'User-Agent' => config('app.eveUserAgent'),
                    //needs to be in query scope'Scopes' => 'publicData esi-calendar.respond_calendar_events.v1 esi-calendar.read_calendar_events.v1 esi-location.read_location.v1 esi-location.read_ship_type.v1 esi-mail.organize_mail.v1 esi-mail.read_mail.v1 esi-mail.send_mail.v1 esi-skills.read_skills.v1 esi-skills.read_skillqueue.v1 esi-wallet.read_character_wallet.v1 esi-wallet.read_corporation_wallet.v1 esi-search.search_structures.v1 esi-clones.read_clones.v1 esi-characters.read_contacts.v1 esi-universe.read_structures.v1 esi-bookmarks.read_character_bookmarks.v1 esi-killmails.read_killmails.v1 esi-corporations.read_corporation_membership.v1 esi-assets.read_assets.v1 esi-planets.manage_planets.v1 esi-fleets.read_fleet.v1 esi-fleets.write_fleet.v1 esi-ui.open_window.v1 esi-ui.write_waypoint.v1 esi-characters.write_contacts.v1 esi-fittings.read_fittings.v1 esi-fittings.write_fittings.v1 esi-markets.structure_markets.v1 esi-corporations.read_structures.v1 esi-characters.read_loyalty.v1 esi-characters.read_opportunities.v1 esi-characters.read_chat_channels.v1 esi-characters.read_medals.v1 esi-characters.read_standings.v1 esi-characters.read_agents_research.v1 esi-industry.read_character_jobs.v1 esi-markets.read_character_orders.v1 esi-characters.read_blueprints.v1 esi-characters.read_corporation_roles.v1 esi-location.read_online.v1 esi-contracts.read_character_contracts.v1 esi-clones.read_implants.v1 esi-characters.read_fatigue.v1 esi-killmails.read_corporation_killmails.v1 esi-corporations.track_members.v1 esi-wallet.read_corporation_wallets.v1 esi-characters.read_notifications.v1 esi-corporations.read_divisions.v1 esi-corporations.read_contacts.v1 esi-assets.read_corporation_assets.v1 esi-corporations.read_titles.v1 esi-corporations.read_blueprints.v1 esi-bookmarks.read_corporation_bookmarks.v1 esi-contracts.read_corporation_contracts.v1 esi-corporations.read_standings.v1 esi-corporations.read_starbases.v1 esi-industry.read_corporation_jobs.v1 esi-markets.read_corporation_orders.v1 esi-corporations.read_container_logs.v1 esi-industry.read_character_mining.v1 esi-industry.read_corporation_mining.v1 esi-planets.read_customs_offices.v1 esi-corporations.read_facilities.v1 esi-corporations.read_medals.v1 esi-characters.read_titles.v1 esi-alliances.read_contacts.v1 esi-characters.read_fw_stats.v1 esi-corporations.read_fw_stats.v1 esi-characterstats.read.v1',
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
                'form_params' => [
                    'grant_type' => 'authorization_code',
                    'code' => $request->code
                ]
                ];
                $resp = $client->post($authSite, $token_headers);
                $tokens = json_decode($resp->getBody());  
                return $tokens;
            }
            catch(\Exception $e){
                dd('exception caught' . $e);
            }

        }
        else{
            dd('failed to get access code');
        }

    }

    public function checkForTokens(){

    }

    public function getCharacterCredentials($tokens){

        $client = new Client();

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
            $characterCredentials = json_decode($resp->getBody());
            return $characterCredentials;
        }
        catch(\Exception $e){
            dd('failed to get character id' . $e);
        }
    }

     public function attachCharacterToUser($request){

            $tokens = $this->getEsiTokens($request);
            $characterCredentials = $this->getCharacterCredentials($tokens);
    
            //check if character is already tied to an account
            $userIdCheck = Character::where('character_id', $characterCredentials->CharacterID)->get();
           /////////// dd($characterCredentials->CharacterID);
            if(!$characterIdCheck->isEmpty()){
                dd('This character is already tied to another account');   
            }
            else{
                //if character is not bound to an account, bind it to the authenticated user
                $characterModel = new Character;
                $characterModel->user_id = Auth::user()->id;
                $characterModel->character_id = $characterCredentials->CharacterID;
                $characterModel->character_name = $characterCredentials->CharacterName;
                $characterModel->last_fetch = Carbon::now();
                $characterModel->expires = $characterCredentials->ExpiresOn;
                $characterModel->access_token = $tokens->access_token;
                $characterModel->refresh_token = $tokens->refresh_token;
                $characterModel->save();
            }   
        }

    public function getNewAccessTokenWithRefreshToken(){
        $user = auth()->user()->characters()->get();
        //currently just taking the hardcoded 0th spot of the character array. Eventually user should pick which character and that variable would take the place of the [0]
        $refresh_token = $user[0]->refresh_token;
        $user[0]->last_fetch = Carbon::now();
        $user[0]->expires = Carbon::now()->addMinutes(20);
        //dd($users[0]->refresh_token);
        

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
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $refresh_token,
                ]
                ];
                $resp = $client->post($authSite, $token_headers);
                $tokens = json_decode($resp->getBody());  
                return $tokens;
            }
            catch(\Exception $e){
                dd('exception caught' . $e);
            }

    }

    public function checkTokenExpiration ($character_id){ 
        //if token has expired will return true, if not will return false.
        $characterModel = Character::where('character_id', $character_id)->firstOrFail();
        if(Carbon::now() > $characterModel->expires){
            dd(Carbon::now(), $characterModel->expires, 'token expired');
            return true;
        }else{
            dd(Carbon::now(), $characterModel->expires, 'token is fine');
            return false;
        }
    }

    public function updateTokenExpiration($character_id){
        $characterModel = Character::where('character_id', $character_id)->firstOrFail();
        $characterModel->last_fetch = Carbon::now();
        $characterModel->expires = Carbon::now()->addMinutes(20);
        $characterModel->save();
    }

    public function saveNewTokensToDB($character_id, $newTokens){
                $characterModel = Character::where('character_id', $character_id)->get();
                $characterModel[0]->access_token = $newTokens->access_token;
                $characterModel[0]->refresh_token = $newTokens->refresh_token;
                $characterModel[0]->last_fetch = Carbon::now();
                $characterModel[0]->expires = Carbon::now()->addMinutes(20);
                $characterModel[0]->save();
                return true;
    }

    public function getMarketOrders($characterCredentials, $tokens){
        //***instead of making a function for each endppoint a function should be made to take a param and input the proper URL inputs***
        $client = new Client();
        try{
            $character_orders_url = "https://esi.evetech.net/latest" . "/characters/" . $characterCredentials->CharacterID . "/orders/";
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
}
