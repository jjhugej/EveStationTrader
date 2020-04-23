@extends('layouts.app')

@section('content')

    <a href="{{ url()->previous() }}"><button class="btn btn-danger">< Back</button></a>

    <h1 class="text-center mb-5">Delivery Group Detailed View</h1>

    <div class="container border mb-4 p-4">
        <p>Group Name: {{$deliveryGroup->name}}</p>
        <p>Start Station: {{$deliveryGroup->start_station}}</p>
        <p>End Station: {{$deliveryGroup->end_station}}</p>
        <p>Transport Cost: @formatNumber($deliveryGroup->price)</p>
        <p>Max Volume: @formatNumber($deliveryGroup->volume_limit)</p>
        <p>Created At: {{$deliveryGroup->created_at}}</p>
        <p>Updated At: {{$deliveryGroup->updated_at}}</p>
        <a class ="col" href="/logistics/{{$deliveryGroup->id}}/edit"><button class="btn btn-primary">Edit Delivery Group</button></a>
        <a class ="col" href="/logistics/{{$deliveryGroup->id}}/delete"><button class="btn btn-danger">Delete Delivery Group</button></a>
    </div>

    <h1 class="mb-3">Items In This Group:</h1>

    <a href="{{ config('baseUrl') }}/inventory/create"><button class="btn btn-primary mb-1">Create New Item</button></a>
    <div class="table-responsive border mb-3">
        <table class="table table-striped table-hover">
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
                        <th class="fit" scope="row"> <a href="{{ config('baseUrl') }}/inventory/{{$itemInDeliveryGroup->id}}">{{$itemInDeliveryGroup->name}}</a></th>
                        <td>{{$itemInDeliveryGroup->purchase_price}}</td>
                        <td>{{$itemInDeliveryGroup->sell_price}}</td>
                        <td>{{$itemInDeliveryGroup->amount}}</td>
                    <td><a href="{{ config('baseUrl') }}/inventory/{{$itemInDeliveryGroup->id}}/remove">Remove</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <h1>Add Existing Inventory Item To This Group</h1>
    <div class="table-responsive border">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                <th scope="col">Item</th>
                <th scope="col">Purchase Price</th>
                <th scope="col">Sell Price</th>
                <th scope="col">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($itemsNotInDeliveryGroup as $itemNotInDeliveryGroup)
                        <tr>
                            <th class="fit" scope="row"> <a href="{{ config('baseUrl') }}/inventory/{{$itemNotInDeliveryGroup->id}}">{{$itemNotInDeliveryGroup->name}}</a></th>
                            <td>{{$itemNotInDeliveryGroup->purchase_price}}</td>
                            <td>{{$itemNotInDeliveryGroup->sell_price}}</td>
                            <td>{{$itemNotInDeliveryGroup->amount}}</td>
                            <td><a class="btn btn-primary" href="{{ config('baseUrl') }}/inventory/{{$itemNotInDeliveryGroup->id}}/add/{{$deliveryGroup->id}}">add</a></td>
                        </tr>
                    </form>
                    @endforeach
            </tbody>
    </div>
    </table>

@endsection