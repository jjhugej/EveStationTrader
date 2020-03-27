@extends('layouts.app')

@section('content')

    <a href="{{ url()->previous() }}"><button class="btn btn-danger">< Back</button></a>

    <h1 class="text-center mb-5">Inventory Item Detailed View</h1>

    <div class="container border mb-4 p-4">
        <p>Item: {{$item->name}}</p>
        <p>Delivery Group: <a href="{{ config('baseUrl') }}/logistics/{{$item->delivery_group_id}}">{{$item->logistics_group_name}}</a></p>
        <p>On Market?</p>
        <p>Purchase Price: @formatNumber($item->purchase_price)</p>
        <p>Sell Price: @formatNumber($item->sell_price)</p>
        <p>Amount: @formatNumber($item->amount)</p>
        <p>Notes: {{$item->notes}}</p>
        <p>Created At: {{$item->created_at}}</p>
        <p>Updated At: {{$item->updated_at}}</p>
        <a class ="col" href="/inventory/{{$item->id}}/edit"><button class="btn btn-primary">Edit Item</button></a>
        <a class ="col" href="/inventory/{{$item->id}}/delete"><button class="btn btn-danger">Delete Item</button></a>
    </div>

@endsection