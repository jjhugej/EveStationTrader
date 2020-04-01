@extends('layouts.app')

@section('content')
    <h1>Market Orders:</h1>
    <div class="table-responsive border">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                <th scope="col">Item</th>
                <th scope="col">Price</th>
                <th scope="col">Volume</th>
                <th scope="col">Location</th>
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
                        <td>{{$marketOrder->character_name}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection