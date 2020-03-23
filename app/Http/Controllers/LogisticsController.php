<?php

namespace App\Http\Controllers;

use App\Logistics;
use App\Character;
use App\User;
use App\MarketOrders;
use App\EveItem;
use App\StructureName;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;

class LogisticsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('logistics.logistics');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('logistics.logistics_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
        'title' => 'required|unique:posts|max:255',
        'body' => 'required',
    ]);
        $logisticsInstance = new Logistics();
        $logisticsInstance->user_id = Auth::user()->id;
        $logisticsInstance->name = $request->name;
        $logisticsInstance->start_station = $request->start_station;
        $logisticsInstance->end_station = $request->end_station;
        $logisticsInstance->price = $request->price;
        $logisticsInstance->volume_limit = $request->volume_limit;
        $logisticsInstance->status = $request->status;
        $logisticsInstance->notes = $request->notes;
        $logisticsInstance->save();
      

        //redirect to index
        return redirect('/logistics');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Logistics  $logistics
     * @return \Illuminate\Http\Response
     */
    public function show(Logistics $logistics)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Logistics  $logistics
     * @return \Illuminate\Http\Response
     */
    public function edit(Logistics $logistics)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Logistics  $logistics
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Logistics $logistics)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Logistics  $logistics
     * @return \Illuminate\Http\Response
     */
    public function destroy(Logistics $logistics)
    {
        //
    }
}
