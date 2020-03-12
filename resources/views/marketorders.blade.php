@extends('layouts.app')

@section('content')
    <h1>Market Orders:</h1>
    @foreach($marketOrders as $marketOrder)

        <div class="card">
            <div class="card-body">
               <p>Type Id: {{ $marketOrder->type_id}}</p>
               <p>Price: {{ $marketOrder->price}}</p>
               <p>Price: {{ $marketOrder->volume_remain .'/' . $marketOrder->volume_total}}</p>
            </div>
        </div>

    @endforeach
@endsection