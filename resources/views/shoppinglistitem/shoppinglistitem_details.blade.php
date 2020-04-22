@extends('layouts.app')

@section('content')

    <h1 class="text-center">Shopping List Item Detailed View</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    @if(Session::has('status'))
        <div class="alert alert-success">
        {{ Session::get('status')}}
        </div>
    @endif

    <div class="container border mb-4 p-4">
        <p>Item Name: {{$shoppingListItem->name}}</p>
        <p>Status: {{$shoppingListItem->status}}</p>
        <p>Assigned Shopping List: <a href="{{ config('baseUrl') }}/shoppinglist/{{$shoppingListItem->shopping_list_id}}"> {{$assignedShoppingList->name}} </a></p>
        <p>Purchase Price: @formatNumber($shoppingListItem->purchase_price) </p>
        <p>Total Amount Needed: @formatNumber($shoppingListItem->amount)</p>
        <p>Amount Purchased: @formatNumber($shoppingListItem->amount_purchased) </p>
        <p>Notes: {{$shoppingListItem->notes}}</p>
        <p>Created On: {{ date('d-m-y', strtotime($shoppingListItem->created_at)) }}</p>
        <a class ="col" href="/shoppinglistitem/{{$shoppingListItem->id}}/edit"><button class="btn btn-primary">Edit Item</button></a>
        <a class ="col" href="/shoppinglistitem/{{$shoppingListItem->id}}/delete"><button class="btn btn-danger">Delete Item</button></a>
    </div>


    <h2 class="text-center">Recent Transactions With This Item</h2>
    <p>Note: This will mark the item purchased</p>
    <form action="{{ config('baseUrl') }}/inventory/create" method="POST">
            @csrf
     <div class="table-responsive border">

            <table id="transaction_table" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th scope="col"></th>
                        <th scope="col">Item</th>
                        <th scope="col">Unit Price</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Date</th>
                    </tr>
                </thead>

                
                    <tbody>
                        @foreach($transactions as $transaction)
                        <tr>
                            <td><input type="checkbox" class="transaction_checkbox" name="transaction_id_array[]" value={{$transaction->transaction_id}}></td>
                            <td class="d-none"><input type="text" name="shoppingListItemID" value={{$shoppingListItem->id}}></td>
                            <td>{{$transaction->typeName}}</td>
                            <td>@formatNumber($transaction->unit_price)</td>
                            <td>@formatNumber($transaction->quantity)</td>
                            <td>{{date('d-M-y', strtotime($transaction->date))}}</td>
                            <td> <a href="#">quick add</a></td>     
                        </tr>
                        @endforeach
                    </tbody>
                    
                </table>
            </div>
                <input class="btn btn-success" id="submit_btn" type="submit" value="Add Items To Inventory">
        </form>
      

@endsection