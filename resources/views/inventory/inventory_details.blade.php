@extends('layouts.app')

@section('content')

    <a href="{{ url()->previous() }}"><button class="btn btn-danger">< Back</button></a>

    <h1 class="text-center">Inventory Item Detailed View</h1>

    <div class="container border">
        <p>Item: {{$item->name}}</p>
        <p>Delivery Group: {{$item->logistics_group_name}}</p>
        <p>On Market?</p>
        <p>Purchase Price: @formatNumber($item->purchase_price)</p>
        <p>Sell Price: @formatNumber($item->sell_price)</p>
        <p>Amount: @formatNumber($item->amount)</p>
        <p>Notes: {{$item->notes}}</p>
    <a href="/logistics/edit/{{$item->id}}"><button class="btn btn-primary">Edit</button></a>
    </div>

@endsection