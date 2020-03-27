<?php

namespace App\Http\Controllers;

use App\Logistics;
use App\Character;
use App\User;
use App\Inventory;
use App\MarketOrders;
use App\EveItem;
use App\StructureName;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;

class LogisticsController extends EveBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $deliveryGroups = Logistics::where('user_id', Auth::user()->id)->orderBy('created_at','desc')->get();
        //dd($logisticsData);
        return view('logistics.logistics', compact('deliveryGroups'));
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
        'name' => 'required|max:255',
        'start_station' => 'required|max:255',
        'end_station' => 'required|max:255',
        'price' => 'integer|nullable',
        'volume_limit' => 'integer|nullable',
        'status' => 'required',
        'notes' => 'nullable|max:1000',
    ]);
            
        $logisticsInstance = new Logistics();

        $logisticsInstance->user_id = Auth::user()->id;
        $logisticsInstance->name = $validatedData['name'];
        $logisticsInstance->start_station = $validatedData['start_station'];
        $logisticsInstance->end_station = $validatedData['end_station'];
        $logisticsInstance->price = $validatedData['price'];
        $logisticsInstance->volume_limit = $validatedData['volume_limit'];
        $logisticsInstance->status = $validatedData['status'];
        $logisticsInstance->notes = $validatedData['notes'];

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
    public function show(Logistics $deliveryGroup)
    {
        $itemsInDeliveryGroup = Inventory::where('logistics_group_id', $deliveryGroup->id)->get();

        //dd($itemsInDeliveryGroup);

        return view('logistics.logistics_details', compact('deliveryGroup', 'itemsInDeliveryGroup'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Logistics  $logistics
     * @return \Illuminate\Http\Response
     */
    public function edit(Logistics $deliveryGroup)
    {
        //dd($deliveryGroup);
        return view('logistics.logistics_edit', compact('deliveryGroup'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Logistics  $logistics
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Logistics $deliveryGroup)
    {
        $validatedData = $request->validate([
        'name' => 'required|max:255',
        'start_station' => 'required|max:255',
        'end_station' => 'required|max:255',
        'price' => 'integer|nullable',
        'volume_limit' => 'integer|nullable',
        'status' => 'required',
        'notes' => 'nullable|max:1000',
    ]);     
           
        $logisticsInstance = Logistics::where('id', $deliveryGroup->id)->first();
        $logisticsInstance->user_id = Auth::user()->id;
        $logisticsInstance->name = $validatedData['name'];
        $logisticsInstance->start_station = $validatedData['start_station'];
        $logisticsInstance->end_station = $validatedData['end_station'];
        $logisticsInstance->price = $validatedData['price'];
        $logisticsInstance->volume_limit = $validatedData['volume_limit'];
        $logisticsInstance->status = $validatedData['status'];
        $logisticsInstance->notes = $validatedData['notes'];
        $logisticsInstance->save();

        return redirect('/logistics');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Logistics  $logistics
     * @return \Illuminate\Http\Response
     */
    public function destroy(Logistics $deliveryGroup)
    {
        $deliveryGroup->delete();
        return redirect('/logistics');
    }
}
