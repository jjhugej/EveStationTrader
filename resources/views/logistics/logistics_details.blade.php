@extends('layouts.app')

@section('content')

    <a href="{{ url()->previous() }}"><button class="btn btn-danger">< Back</button></a>

    <h1 class="text-center">Delivery Group Detailed View</h1>

    <div class="container border">
        <p>Group Name: {{$deliveryGroup->name}}</p>
        <p>Start Station: {{$deliveryGroup->start_station}}</p>
        <p>End Station: {{$deliveryGroup->end_station}}</p>
        <p>Transport Cost: @formatNumber($deliveryGroup->price)</p>
        <p>Max Volume: @formatNumber($deliveryGroup->volume_limit)</p>
    <a href="/logistics/{{$deliveryGroup->id}}/edit"><button class="btn btn-primary">Edit</button></a>
    </div>

    <div class="table-responsive border">
        <h1>Items In Group:</h1>
        <table class="table table-striped table-hover">
            <a href="{{ config('baseUrl') }}/inventory/create"><button class="btn btn-primary">Add Item</button></a>
            <thead>
                <tr>
                <th scope="col">Item</th>
                <th scope="col">Purchase Price</th>
                <th scope="col">Sell Price</th>
                <th scope="col">Amount</th>
                <th scope="col">Remove</th>
                </tr>
            </thead>
            <tbody>
                @foreach($itemsInDeliveryGroup as $itemInDeliveryGroup)
                    <tr>
                        <th class="fit" scope="row">{{$itemInDeliveryGroup->name}}</th>
                        <td>{{$itemInDeliveryGroup->purchase_price}}</td>
                        <td>{{$itemInDeliveryGroup->sell_price}}</td>
                        <td>{{$itemInDeliveryGroup->amount}}</td>
                        <td><a href="#">Remove</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection