<?php

namespace App\Http\Controllers;

use App\Character;
use App\User;
use App\EveItemIDModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EveLoginController extends EveBaseController

                /*
                    Most functions that start with '$this->xxx' are abstractions of code
                    that were refactored in to functions to make reading this main controller easier.

                    You can find the underlying code of these functions in the EveBaseController


                    ***current problem:we need to give the user a way to link multiple characters to an account
                    ***
                */

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
            //get characters assigned to the user
            $characters = $user->characters->where('user_id', $user->id);
            return redirect()->away($this->eveLogin());
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
        $this->attachCharacterToUser($request);        
        return view('dashboard');
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


