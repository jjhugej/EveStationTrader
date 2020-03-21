@extends('layouts.app')

@section('content')
    <h1>Market Orders:</h1>
    @foreach($marketOrders as $marketOrder)

        <div class="card">
            <div class="card-body">
               <p>Item: {{ $marketOrder->typeName}}</p>
               <p>Price: @convertNumberToCurrency($marketOrder->price)</p>
               <p>Volume: {{ $marketOrder->volume_remain .'/' . $marketOrder->volume_total}}</p>
            </div>
        </div>

    @endforeach
@endsection