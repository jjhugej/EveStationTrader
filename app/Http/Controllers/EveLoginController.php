<?php

namespace App\Http\Controllers;

use App\Character;
use App\User;
use App\EveItemIDModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EveLoginController extends EveBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //first check DB for character info (auth_tokens/refresh_tokens)
        if(Auth::check()){
            $user = Auth::user();
            $characters = $user->characters->where('user_id', $user->id);
           
            //if $characters is empty, we need to route the user through the eve login
            if($characters->count() < 1){
                return redirect()->away($this->eveLogin());
                //***once the user logs in we need to save the information to the database associated with the authd user***
            }
            else{
                //verify the auth/refresh token
            }
        }
        else{
            return redirect(route('login'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //before calling getEsiTokens check db for a refresh token
        $tokens = $this->getEsiTokens($request);
        $characterCredentials = $this->getCharacterCredentials($tokens);
        
        //check if char id alrdy exists)))))
        $characterModel = new Character;

        //set character variables into DB
        $characterModel->user_id = Auth::user()->id;
        $characterModel->character_id = $characterCredentials->CharacterID;
        $characterModel->character_name = $characterCredentials->CharacterName;
        //$characterModel->last_fetch = Carbon::now();
        //$characterModel->expires = $characterCredentials->ExpiresOn;

        //set tokens
        $characterModel->access_token = $tokens->access_token;
        $characterModel->refresh_token = $tokens->refresh_token;
        $characterModel->save();
        
        dd(Auth::user()->characters());
        
        return view('marketorders', compact('orders'));
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\EveLoginModel  $eveLoginModel
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //$orders = $this->getMarketOrders($characterCredentials, $tokens);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\EveLoginModel  $eveLoginModel
     * @return \Illuminate\Http\Response
     */
    public function edit(EveLoginModel $eveLoginModel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\EveLoginModel  $eveLoginModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EveLoginModel $eveLoginModel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EveLoginModel  $eveLoginModel
     * @return \Illuminate\Http\Response
     */
    public function destroy(EveLoginModel $eveLoginModel)
    {
        //
    }
}


/*

{#294 ▼
  +"access_token": "1|CfDJ8O+5Z0aH+aBNj61BXVSPWfgJw+9j+FLB9deu0sb+MhSE+bxGB+lS2xlDg+4y4PuBSOlaryL0fU0TB5nD8n1UygJHW4dJRPy2USgmNSirbGRjK0uw6ztS2SqbvGMks/b0kYmh3N6GU3Krd93MvG2NLTznUB ▶"
  +"token_type": "Bearer"
  +"expires_in": 1199
  +"refresh_token": "b2LaTLcUgoSiIPebx7CHT7yVnjwVu2k0a5BkBX2J6Ukun5l_yDjcwtPauvFGKuNHjapEQaeC8J1ehLi7QBjS3YYeC0NvvP9s7v7iZ9rODX8"
}

*/

/*


{#290 ▼
  +"CharacterID": 90167643
  +"CharacterName": "Iggys"
  +"ExpiresOn": "2020-02-19T21:39:55"
  +"Scopes": "publicData esi-skills.read_skills.v1 esi-wallet.read_character_wallet.v1 esi-search.search_structures.v1 esi-universe.read_structures.v1 esi-assets.read_assets. ▶"
  +"TokenType": "Character"
  +"CharacterOwnerHash": "A72B64E2jqb+lueog818t1EGYYE="
  +"IntellectualProperty": "EVE"
}

*/