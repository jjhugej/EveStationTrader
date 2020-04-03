@extends('layouts.app')

@section('content')

    <a href="{{ url()->previous() }}"><button class="btn btn-danger">< Back</button></a>

    <h1 class="text-center mb-5">Market Order</h1>
    <a class="btn btn-primary mb-2" href="{{ config('baseUrl') }}/marketorders">View All Market Orders</a>
    <div class="container border mb-4 p-4">
        <p>Item Name: {{$marketOrder->typeName}}</p>
        <p>Price:  @formatNumber($marketOrder->price)</p>
        <p>Volume: @formatNumber($marketOrder->volume_remain) / @formatNumber($marketOrder->volume_total)</p>
        <p>Location: {{$marketOrder->locationName}}</p>
        <p>Associated Character: {{$marketOrder->character_name}}</p>
        <p>Range: {{$marketOrder->range}}</p>
        <p>Created At: {{$marketOrder->created_at}}</p>
        <p>Updated At: {{$marketOrder->updated_at}}</p>
    </div>

@endsection