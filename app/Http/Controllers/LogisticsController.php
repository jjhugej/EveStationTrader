<?php

namespace App\Http\Controllers;

use App\Logistics;
use Illuminate\Http\Request;

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
        //
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
