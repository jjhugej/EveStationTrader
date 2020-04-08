@extends('layouts.app')

@section('content')

    <h1 class="text-center">Shopping List Item Detailed View</h1>

    <div class="container border mb-4 p-4">
        <p>Item Name: {{$shoppingListItem->name}}</p>
        <p>Status: {{$shoppingListItem->status}}</p>
        <p>Assigned Shopping List: <a href="{{ config('baseUrl') }}/shoppinglist/{{$shoppingListItem->shopping_list_id}}"> {{$assignedShoppingList->name}} </a></p>
        <p>Delivery Group: {{$shoppingListItem->logistics_group_id}}</p>
        <p>Assigned Market Order: {{$shoppingListItem->market_order_id}}</p>
        <p>Purchase Price: {{$shoppingListItem->purchase_price}} </p>
        <p>Sell Price: {{$shoppingListItem->sell_price}} </p>
        <p>Amount: {{$shoppingListItem->amount}} </p>
        <p>Taxes: {{$shoppingListItem->taxes_paid}} </p>
        <p>Current Location: {{$shoppingListItem->current_location}}</p>
        <p>Notes: {{$shoppingListItem->notes}}</p>
        <p>Created On: {{ date('d-m-y', strtotime($shoppingListItem->created_at)) }}</p>
        <p>Last Update: {{ date('d-m-y', strtotime($shoppingListItem->updated_at)) }}</p>
        <a class ="col" href="/shoppinglistitem/{{$shoppingListItem->id}}/edit"><button class="btn btn-primary">Edit Item</button></a>
        <a class ="col" href="/shoppinglistitem/{{$shoppingListItem->id}}/delete"><button class="btn btn-danger">Delete Item</button></a>
    </div>

@endsection