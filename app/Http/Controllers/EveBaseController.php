<?php

namespace App\Http\Controllers;

use App\Character;
use App\User;
use App\EveItem;
use App\StructureName;
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
    public function checkTokenExpiration ($character_id){ 
        //if token has expired will return true, if not will return false.
        $characterModel = Character::where('character_id', $character_id)->firstOrFail();
        
        //possible bug when first logging in and not setting refresh token
        //dd(Carbon::now()->toDateTimeString(), $characterModel->expires, Carbon::now()->toDateTimeString() > $characterModel->expires );
        if(Carbon::now()->toDateTimeString() > $characterModel->expires){
            return true;
        }else{
            return false;
        }
    }
    public function getNewAccessTokenWithRefreshToken(){
        $character = auth()->user()->characters()->where('is_selected_character' , 1)->first();      
        $refresh_token = $character->refresh_token;        
        
        $client = new Client();

            try{
                $authSite = 'https://login.eveonline.com/oauth/token';
                $token_headers = [
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode(config('app.eveClientId') . ':' . config('app.eveSecretKey')),
                    'User-Agent' => config('app.eveUserAgent'),
                    //'Content-Type' => 'application/x-www-form-urlencoded',
                ],
                'form_params' => [
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $refresh_token,
                ]
                ];
                $resp = $client->post($authSite, $token_headers);
                $tokens = json_decode($resp->getBody()); 
                
                // set new tokens
                $character->access_token = $tokens->access_token;
                $character->refresh_token = $tokens->refresh_token;
                $character->last_esi_token_fetch = Carbon::now();
                $character->expires = Carbon::now()->addMinutes(20);
                $character->save();
                return $character;
            }
            catch(\Exception $e){
                dd('err: 111 - something went wrong with the token exchange', 'exception caught' . $e);
            }

    }

    public function checkTokens($character){
        //this method checks the tokens of a character and updates them if they are expired
        
        $tokenExpired = $this->checkTokenExpiration ($character->character_id);
        
        if($tokenExpired === true){
            $newTokens =  $this->getNewAccessTokenWithRefreshToken();

            return $newTokens;
        }
        return $character;
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

            //check to see if character info already exists in DB
            $isCharacterInDB = Character::where('character_id', $characterCredentials->CharacterID)->first();
            if($isCharacterInDB == null){
                //if the character_id is not already in the database new up an instance of Character and save it

                $characterModel = new Character;

                $characterModel->user_id = Auth::user()->id;
                $characterModel->character_id = $characterCredentials->CharacterID;
                $characterModel->character_name = $characterCredentials->CharacterName;
                $characterModel->last_esi_token_fetch = Carbon::now();
                $characterModel->expires = $characterCredentials->ExpiresOn;
                $characterModel->access_token = $tokens->access_token;
                $characterModel->refresh_token = $tokens->refresh_token;

                $characterModel->save();
            }
            else{
                //fire this code if the char is already in the database

                $characterModel = Character::where('character_id', $characterCredentials->CharacterID)->first();

                $characterModel->user_id = Auth::user()->id;
                $characterModel->last_esi_token_fetch = Carbon::now();
                $characterModel->expires = $characterCredentials->ExpiresOn;
                $characterModel->access_token = $tokens->access_token;
                $characterModel->refresh_token = $tokens->refresh_token;

                $characterModel->save();
            }             
        }

    

    public function updateTokenExpiration($character_id){
        $characterModel = Character::where('character_id', $character_id)->firstOrFail();
        $characterModel->last_esi_token_fetch = Carbon::now();
        $characterModel->expires = Carbon::now()->addMinutes(20);
        $characterModel->save();
    }

    public function saveNewTokensToDB($character_id, $newTokens){
                $characterModel = Character::where('character_id', $character_id)->get();
                dd($characterModel);
                $characterModel[0]->access_token = $newTokens->access_token;
                $characterModel[0]->refresh_token = $newTokens->refresh_token;
                $characterModel[0]->last_esi_token_fetch = Carbon::now();
                $characterModel[0]->expires = Carbon::now()->addMinutes(20);
                $characterModel[0]->save();
                return true;
    }

    function convertEsiDateTime($dateTime){
        /*
            this function converts the given timestamp from ESI to a properly formatted datetime for mysql
            for whatever reason CCP sends the timestamp with the letters T and Z surrounding the time portion of the datetime.
            This uses regex to replace the letters with a space, and then trims the final space at the end.
        */
            $pattern = '/[a-zA-Z]+/';
            $replacement = ' ';
            $convertedTime = trim(preg_replace($pattern, $replacement, $dateTime));
    
            return $convertedTime;
        }


     public function resolveTypeIDToItemName($eveObjectDatas){
        /*
            CCP sends back a type_id which corresponds to an item name from their static dump file.
            The table name for the item names from the dump is: invTypes. Renamed to eveItems in our DB
            NOTE: because this is a static table it must be imported every time a migration refresh is done.

            SECOND NOTE: THIS WILL RETURN THE OBJECT BACK WITH A PROPERTY OF "typeName" WHICH IS NOT PERSISTED ON THE "eveItem" TABLE
        */
            foreach($eveObjectDatas as $eveObjectData){
                
                $typeName = EveItem::where('typeID', $eveObjectData->type_id)->pluck('typeName')->first();
                $eveObjectData->typeName = $typeName;
            }
            return $eveObjectDatas;
    }

    public function resolveSingleTypeIDToItemName($typeID){
        $typeName = EveItem::where('typeID', $typeID)->pluck('typeName')->first();
        
        return $typeName;
    }


    public function resolveSingleItemNameToTypeID($eveObjectData){
        $typeID = EveItem::where('typeName', $eveObjectData->name)->pluck('typeID')->first();
        $eveObjectData->type_id = $typeID;
        return $eveObjectData;
    }
    

    public function resolveMultipleItemNamesToTypeID($eveObjectDatas){

        foreach($eveObjectDatas as $eveObjectData){
            $typeID = EveItem::where('typeName', $eveObjectData->name)->pluck('typeID')->first();
            $eveObjectData->type_id = $typeID;
        }
        return $eveObjectDatas;

    }
    

     public function resolveStationIDToName($character, $eveObjectDatas){
        //THIS WILL RETURN THE OBJECT BACK WITH A PROPERTY OF "locationName" WHICH IS NOT PERSISTED ON THE "eveItem" TABLE
        //BUT WILL BE PERSISTED ON THE STRUCTURENAME TABLE IF IT HASN'T BEEN UPDATED IN 30 DAYS
     
        $locationIDArray = [];
        $locationNameArray = [];

        //first, loop through objects plucking the location ID of each only once, and pushing to an array
        foreach($eveObjectDatas as $eveObjectData){
            if(!in_array($eveObjectData->location_id, $locationIDArray)){
                array_push($locationIDArray,$eveObjectData->location_id);
            }
        }
        
        //check if the location ID is already in the database and if it has been checked since its expiration date
        foreach($locationIDArray as $locationID){
            
            $structureInDB = StructureName::where('location_id', $locationID)->first();
            
            if($structureInDB !== null && $structureInDB->location_id !==null && Carbon::now() > Carbon::now()->addDays($structureInDB->expiration) === false){
                //if the location exists and has not expired, return the name to the locationNameArray so it can be passed
                //to the view without making a request to ESI
                $locationIDInstance = StructureName::where('location_id', $locationID)->first();
                array_push($locationNameArray, $locationIDInstance->location_name); 

            }else{
                //dd($locationIDArray, 'location id');
        
                //if locationIdArray[i] is <100,000,000 it is not a structure, it is a station
                if($locationID > 100000000){

                    //guzzle request for structures
                    $client = new Client();
                    try{
                        $station_url = "https://esi.evetech.net/latest" . "/universe/structures/" . $locationID;
                        $auth_headers = [
                            'headers' => [
                                'Authorization' => 'Bearer ' . $character->access_token,
                                'User-Agent' => config('app.eveUserAgent'),
                                'Content-Type' => 'application/x-www-form-urlencoded',
                            ]
                        ];
                        $resp = $client->get($station_url, $auth_headers);
                        $data = json_decode($resp->getBody());
                        
                        array_push($locationNameArray,$data->name);

                        //save the location to the DB
                        $locationIDInstance = new StructureName();
                        $locationIDInstance->location_id = $locationID;
                        $locationIDInstance->location_name = $data->name;
                        //$locationIDInstance->solar_system_id --> not working. not sure why. it is unecessary and was removed from migration
                        $locationIDInstance->type_id = $data->type_id;
                        $locationIDInstance->expiration = 30;
                        $locationIDInstance->save();
                    }   
                    catch(Exception $e){
                        dd('error verifying character information' . $e);
                    }
                }else{

                    //guzzle request for stations
                    $client = new Client();
                    try{
                        $station_url = "https://esi.evetech.net/latest" . "/universe/stations/" . $locationID;
                        
                        $auth_headers = [
                            'headers' => [
                                'Authorization' => 'Bearer ' . $character->access_token,
                                'User-Agent' => config('app.eveUserAgent'),
                                'Content-Type' => 'application/x-www-form-urlencoded',
                            ]
                        ];
                        
                        $resp = $client->get($station_url, $auth_headers);
                        $data = json_decode($resp->getBody());


                        array_push($locationNameArray,$data->name);

                        //save the location to the DB
                        $locationIDInstance = new StructureName();
                        $locationIDInstance->location_id = $locationID;
                        $locationIDInstance->location_name = $data->name;
                        $locationIDInstance->type_id = $data->type_id;
                        $locationIDInstance->expiration = 30;
                        $locationIDInstance->save();
    
                    }   
                    catch(Exception $e){
                        dd('error verifying character information' . $e);
                    }
                }
        
                
            }
        }
        //combine the idArray with the name Array to get an associative array. return the associative array
        //****if the key->value pair is not in the DB, save it for future checks to reduce the amount of requests to ESI*********************
        $idToNameArray = array_combine($locationIDArray, $locationNameArray);

        //finally set a non-persisted attribute, "locationName" to the marketOrder object. and then return all market orders
        foreach($eveObjectDatas as $eveObjectData){
            $eveObjectData->locationName = $idToNameArray[$eveObjectData->location_id]; 
        }
        return $eveObjectDatas;
    }

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

    public function resolveSingleCharacterNameFromID($object){
         $character_name = Character::where('character_id', $object->character_id)->first()->character_name;
         
        return $character_name;
    }
    public function resolveMultipleCharacterNamesFromIDs($objects){
        $objectsArr = [];
        foreach($objects as $object){
            
            if($object->character_id){
                $character = Character::where('character_id', $object->character_id)->first();
                if($character){
                    $object->character_name = $character->character_name;
                }else{
                    $object->character_name = 'character name not found';
                }
            }else{
                $object->character_name = 'character id not found';
            }
            
        }
        //dd($objectsArr ,$objects);
        return $objects;
    }

    
}
