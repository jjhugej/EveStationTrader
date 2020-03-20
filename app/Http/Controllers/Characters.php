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


class Characters extends EveBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // this method returns all the characters attached to the authenticated user
        $characters = auth()->user()->characters()->get();
        
        return view('characters', compact('characters'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        //this endpoint is used to select and save a character

        /*
            Since both the character and user model can reflect a selected character we need
            to access both models and update them to set the currently selected character.

            First we set the user model, and then we set the character model.
        */

        //first we update the USER MODEL to reflect the currently selected character_id
        auth()->user()->current_selected_character_id = $request->character_id;
        auth()->user()->save();
        
        //second we reset all characters in the character model associated to the user to false (character is not selected)
        $allCharactersOfUser = Character::where('user_id', auth()->user()->id)->get();
            foreach($allCharactersOfUser as $characters){
                //0 is equal to false in mysql
                $characters->is_selected_character = 0;
                $characters->save();
            }
        
       //sets selected character on the CHARACTER MODEL to true (which is 1 in mysql)
       $character = auth()->user()->characters()->where('character_id', $request->character_id)->firstOrFail();
       $character->is_selected_character = 1;
       $character->save();

        return redirect('characters');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    
    public function destroy(Request $request)
    {
        //dd($request->character_id);
        //unlink a character from an account

        $character = auth()->user()->characters()->where('character_id', $request->character_id)->firstOrFail();
        $character->user_id = null;
        $character->is_selected_character = 0;
        $character->save();
        return redirect('characters');

    }
}
