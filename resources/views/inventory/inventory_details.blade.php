@extends('layouts.app')

@section('content')

    <a href="{{ url()->previous() }}"><button class="btn btn-danger">< Back</button></a>

    <h1 class="text-center mb-5">Inventory Item Detailed View</h1>

    <div class="container border mb-4 p-4">
        <p>Item: {{$item->name}}</p>
        @if($item->logistics_group_id !== null)
            <p>Delivery Group: <a href="{{ config('baseUrl') }}/logistics/{{$item->logistics_group_id}}">{{$item->logistics_group_name}}</a></p>
        @else
        <p>Delivery Group: <span class="text-danger"> Not Assigned </span><a href="{{ config('baseUrl')}}/inventory/{{$item->id}}/edit"><button class="btn btn-sm btn-info">Add To Delivery Group</button></a></p> </p>
        @endif
        @if($attachedMarketOrder !== null)
            <p>Attached Market Order: <a href="{{ config('baseUrl')}}/marketorders/{{$attachedMarketOrder->order_id}}"> {{$attachedMarketOrder->typeName}}</a></p>
        @else
        <p>Assigned Market Order: <span class="text-danger"> Not Assigned </span><a href="{{ config('baseUrl')}}/inventory/{{$item->id}}/edit"><button class="btn btn-sm btn-info">Add To Market Order</button></a></p>
        @endif
        <p>Purchase Price: @formatNumber($item->purchase_price)</p>
        <p>Sell Price: @formatNumber($item->sell_price)</p>
        <p>Amount: @formatNumber($item->amount)</p>
        <p>Par: @formatNumber($item->par)</p>
        <p>Current Location: {{$item->current_location}}</p>
        <p>Associated Character: {{$item->character_name}}</p>
        <p>Notes: {{$item->notes}}</p>
        <p>Created At: {{$item->created_at}}</p>
        <p>Updated At: {{$item->updated_at}}</p>
        <a class ="col" href="/inventory/{{$item->id}}/edit"><button class="btn btn-primary">Edit Item</button></a>
        <a class ="col" href="/inventory/{{$item->id}}/delete"><button class="btn btn-danger">Delete Item</button></a>
    </div>

@endsection