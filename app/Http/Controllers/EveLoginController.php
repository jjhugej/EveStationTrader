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
        //$this->checkAccessToken(1);
        // first step of eve esi access token
        $eveAuthBaseUrl = 'https://login.eveonline.com/oauth/authorize';
        $eveRedirectUri = config('app.eveCallbackUri');
        $eveClientId = config('app.eveClientId');
        $eveSecretKey = config('app.eveSecretKey');
        $eveScopes = config('app.eveScopes');
        $redirectUrl = $eveAuthBaseUrl . '?response_type=code&redirect_uri=' . $eveRedirectUri
            . '&client_id=' . $eveClientId . '&scope=' . 'publicData esi-calendar.respond_calendar_events.v1 esi-calendar.read_calendar_events.v1 esi-location.read_location.v1 esi-location.read_ship_type.v1 esi-mail.organize_mail.v1 esi-mail.read_mail.v1 esi-mail.send_mail.v1 esi-skills.read_skills.v1 esi-skills.read_skillqueue.v1 esi-wallet.read_character_wallet.v1 esi-wallet.read_corporation_wallet.v1 esi-search.search_structures.v1 esi-clones.read_clones.v1 esi-characters.read_contacts.v1 esi-universe.read_structures.v1 esi-bookmarks.read_character_bookmarks.v1 esi-killmails.read_killmails.v1 esi-corporations.read_corporation_membership.v1 esi-assets.read_assets.v1 esi-planets.manage_planets.v1 esi-fleets.read_fleet.v1 esi-fleets.write_fleet.v1 esi-ui.open_window.v1 esi-ui.write_waypoint.v1 esi-characters.write_contacts.v1 esi-fittings.read_fittings.v1 esi-fittings.write_fittings.v1 esi-markets.structure_markets.v1 esi-corporations.read_structures.v1 esi-characters.read_loyalty.v1 esi-characters.read_opportunities.v1 esi-characters.read_chat_channels.v1 esi-characters.read_medals.v1 esi-characters.read_standings.v1 esi-characters.read_agents_research.v1 esi-industry.read_character_jobs.v1 esi-markets.read_character_orders.v1 esi-characters.read_blueprints.v1 esi-characters.read_corporation_roles.v1 esi-location.read_online.v1 esi-contracts.read_character_contracts.v1 esi-clones.read_implants.v1 esi-characters.read_fatigue.v1 esi-killmails.read_corporation_killmails.v1 esi-corporations.track_members.v1 esi-wallet.read_corporation_wallets.v1 esi-characters.read_notifications.v1 esi-corporations.read_divisions.v1 esi-corporations.read_contacts.v1 esi-assets.read_corporation_assets.v1 esi-corporations.read_titles.v1 esi-corporations.read_blueprints.v1 esi-bookmarks.read_corporation_bookmarks.v1 esi-contracts.read_corporation_contracts.v1 esi-corporations.read_standings.v1 esi-corporations.read_starbases.v1 esi-industry.read_corporation_jobs.v1 esi-markets.read_corporation_orders.v1 esi-corporations.read_container_logs.v1 esi-industry.read_character_mining.v1 esi-industry.read_corporation_mining.v1 esi-planets.read_customs_offices.v1 esi-corporations.read_facilities.v1 esi-corporations.read_medals.v1 esi-characters.read_titles.v1 esi-alliances.read_contacts.v1 esi-characters.read_fw_stats.v1 esi-corporations.read_fw_stats.v1 esi-characterstats.read.v1';

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
        /*
            plan:
            1) check db for access/refresh tokens on our end users account
            2) if db has no access/refresh tokens prompt login
            3) verify tokens
            4) make authenticated call

            thoughts:
                -clean up UI, its hard to have a plan like this.
                -upon login db must be checked for a linked character against the account
                    -- if tokens exist verify them with eve first
                    -- if tokens don't exist prompt an eve login to get tokens
                -routes and controllers need to be moved around/renamed. its hard to keep track of where things are.


        */
        //before calling getEsiTokens check db for a refresh token
        $tokens = $this->getEsiTokens($request);
        $characterCredentials = $this->getCharacterCredentials($tokens);
        $orders = $this->getMarketOrders($characterCredentials, $tokens);
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