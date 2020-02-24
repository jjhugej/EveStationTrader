<?php

namespace App\Http\Controllers;

use App\EveLoginModel;
use App\EveItemIDModel;
use Illuminate\Http\Request;

class EveLoginController extends EveBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // first step of eve esi access token
        $eveAuthBaseUrl = 'https://login.eveonline.com/oauth/authorize';
        $eveRedirectUri = config('app.eveCallbackUri');
        $eveClientId = config('app.eveClientId');
        $eveSecretKey = config('app.eveSecretKey');
        $eveScopes = config('app.eveScopes');
        $redirectUrl = $eveAuthBaseUrl . '?response_type=code&redirect_uri=' . $eveRedirectUri
            . '&client_id=' . $eveClientId . '&scope=' . $eveScopes;

        //we redirect to eve and eve authenticates user and then sends back to website using callback url on eve dev website
        return redirect()->away($redirectUrl);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        $orders = $this->getEsiTokens($request);
       

        //because esi returns an item ID we have to look in our database for an ID and its associated name
        $itemNames = EveItemIDModel::all();
       
        $testarr =[];

        foreach($itemNames as $itemName){
            array_push($testarr, $itemName);
        }
        dd($orders);
        
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