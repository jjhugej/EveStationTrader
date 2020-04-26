@extends('layouts.app')

@section('content')
    <h1 class="mb-4 text-center">Market Orders - Sell Orders</h1>

    <div class="row mb-2">
        <a class="col-md-4 btn btn-primary" href="{{ config('baseUrl') }}/marketorders/">View All Orders</a>
        <span class="col-md-4"></span>
        <a class="col-md-4 btn btn-info" href="{{ config('baseUrl') }}/marketorders/search/buy">View Buy Orders</a>
    </div>

    <form action="{{ config('baseUrl') }}/inventory/create" method="POST">

        @csrf

        <div id="marketOrdersTableWrapper" class="table-responsive border mb-2">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th></th>
                        <th scope="col">Item</th>
                        <th scope="col">Price</th>
                        <th scope="col">Volume</th>
                        <th scope="col">Location</th>
                        <th scope="col">Order Type</th>
                        <th scope="col">Inventory</th>
                        <th scope="col">Character</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($marketOrders as $marketOrder)
                        <tr>
                            @if($marketOrder->inventory_id !== null && $marketOrder->inventory_id !== 0)
                                <td></td>
                           
                            @else
                                <td><input type="checkbox" class="market_order_checkbox" name="market_order_id_array[]" value={{$marketOrder->order_id}}></td>
                            @endif
                    
                            <th class="fit" scope="row"> <a href="/marketorders/{{$marketOrder->order_id}}">{{ $marketOrder->typeName}}</a></th>
                            <td>@convertNumberToCurrency($marketOrder->price)</td>
                            <td>@formatNumber($marketOrder->volume_remain) {{'/'}} @formatNumber($marketOrder->volume_total)</td>
                            <td>{{$marketOrder->locationName}}</td>
                            @if($marketOrder->is_buy_order == true)
                                <td>Buy</td>
                            @else
                                <td>Sell</td>
                            @endif
                            @if($marketOrder->inventory_id !== null && $marketOrder->inventory_id !== 0)
                                <td><a href="{{ config('baseUrl') }}/inventory/{{$marketOrder->inventory_id}}">View</a></td>
                            @else
                                <td>No</td>
                            @endif
                            <td>{{$marketOrder->character_name}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <input class="btn btn-success" id="submit_btn" type="submit" value="Add Items To Inventory">
    </form>

@endsection