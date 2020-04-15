@extends('layouts.app')

@section('content')
    <h1 class="mb-4 text-center">Market Orders - Sell Orders</h1>

    <div class="row mb-2">
        <a class="col-md-4 btn btn-primary" href="{{ config('baseUrl') }}/marketorders/">View All Orders</a>
        <span class="col-md-4"></span>
        <a class="col-md-4 btn btn-info" href="{{ config('baseUrl') }}/marketorders/search/buy">View Buy Orders</a>
    </div>

    <div class="table-responsive border">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                <th scope="col">Item</th>
                <th scope="col">Price</th>
                <th scope="col">Volume</th>
                <th scope="col">Location</th>
                <th scope="col">Order Type</th>
                <th scope="col">Character</th>
                </tr>
            </thead>
            <tbody>
                @foreach($marketOrders as $marketOrder)
                    <tr>
                        <th class="fit" scope="row"> <a href="/marketorders/{{$marketOrder->order_id}}">{{ $marketOrder->typeName}}</a></th>
                        <td>@convertNumberToCurrency($marketOrder->price)</td>
                        <td>@formatNumber($marketOrder->volume_remain) {{'/'}} @formatNumber($marketOrder->volume_total)</td>
                        <td>{{$marketOrder->locationName}}</td>
                        @if($marketOrder->is_buy_order == true)
                            <td>Buy</td>
                        @else
                            <td>Sell</td>
                        @endif
                        <td>{{$marketOrder->character_name}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection