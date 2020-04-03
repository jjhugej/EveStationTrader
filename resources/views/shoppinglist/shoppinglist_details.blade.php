@extends('layouts.app')

@section('content')

<a href="{{ url()->previous() }}"><button class="btn btn-danger">< Back</button></a>

    <h1 class="text-center mb-5">Shopping List Detailed View</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="container border mb-4 p-4">
        <p>Name: {{$shoppingList->name}}</p>
        <p>Notes: {{$shoppingList->notes}}</p>
        <p>Created On: {{ date('d-m-y', strtotime($shoppingList->created_at)) }}</p>
        <p>Last Update: {{ date('d-m-y', strtotime($shoppingList->updated_at)) }}</p>
        <a class ="col" href="/shoppinglist/{{$shoppingList->id}}/edit"><button class="btn btn-primary">Edit Shopping List</button></a>
        <a class ="col" href="/shoppinglist/{{$shoppingList->id}}/delete"><button class="btn btn-danger">Delete Shopping List</button></a>
    </div>

    <h1 class="text-center mb-3">Items In This Shopping List:</h1>

    <a href="{{ config('baseUrl') }}/inventory/create"><button class="btn btn-primary mb-1">+ Add Item</button></a>
    <div class="table-responsive border mb-3">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                <th scope="col">Item</th>
                <th scope="col">Purchase Price</th>
                <th scope="col">Amount</th>
                <th scope="col">Remove</th>
                </tr>
            </thead>
            <tbody>
                @foreach($shoppingListItems as $shoppingListItem)
                    <tr>
                        <th class="fit" scope="row"> <a href="{{ config('baseUrl') }}/shoppinglistitem/{{$shoppingListItem->id}}">{{$shoppingListItem->name}}</a></th>
                        <td>{{$shoppingListItem->purchase_price}}</td>
                        <td>{{$shoppingListItem->amount}}</td>
                    <td><a href="{{ config('baseUrl') }}/shoppinglistitem/{{$shoppingListItem->id}}/remove">Remove</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <h2 class="text-center">Add Item To Shopping List</h2>
    <form method="POST" action="{{ config('baseUrl') }}/shoppinglistitem/create/{{$shoppingList->id}}">
        
        @csrf

        <div class="form-group mb-0">
            <label for="name">Item Name:</label>
            <input autocomplete="off" type="text" name="name" id="name" class="form-control {{$errors->has('name') ? 'border border-danger' : ''}}" value="{{ old('name') }}" placeholder="" required>
        </div>
        <div class="container">
            <div id="js_item_search_results_target" class="card mb-3">
                <!-- Item Search Results Field -->
            </div>
        </div>
        <div class="form-row">
            <div class="col">
                <label for="purchase_price">Purchase Price:</label>
                <input type="number" name="purchase_price" id ="purchase_price" class="form-control {{$errors->has('purchase_price') ? 'border border-danger' : ''}}" value="{{ old('purchase_price') }}" placeholder="">
            </div>
            <div class="col">
                <label for="sell_price">Sell Price:</label>
                <input type="number" name="sell_price" id = "sell_price" class="form-control {{$errors->has('sell_price') ? 'border border-danger' : ''}}" value="{{ old('sell_price') }}" placeholder="">
            </div>
            <div class="form-group col">
                <label for="taxes_paid">Taxes Paid:</label>
                <input type="number" name="taxes_paid" id="taxes_paid" class="form-control {{$errors->has('taxes_paid') ? 'border border-danger' : ''}}" value="{{ old('taxes_paid') }}" placeholder="">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col">
                <label for="amount">Amount:</label>
                <input type="number" name="amount" id="amount" class="form-control {{$errors->has('amount') ? 'border border-danger' : ''}}" value="{{ old('amount') }}" placeholder="">
            </div>
            <div class="form-group col">
                <label for="par">Par:</label>
                <input type="number" name="par" id="par" class="form-control {{$errors->has('par') ? 'border border-danger' : ''}}" value="{{ old('par') }}" placeholder="">
            </div>
        </div>
        <div class="form-group">
                <label for="current_location">Current Location:</label>
                <input type="text" name="current_location" id="current_location" class="form-control {{$errors->has('current_location') ? 'border border-danger' : ''}}" value="{{ old('current_location') }}" placeholder="">
            </div>
        <div class="form-group">
            <label for="notes">Notes:</label>
            <textarea  id="notes" name="notes" class="form-control {{$errors->has('notes') ? 'border border-danger' : ''}}" rows="3">{{ old('notes') }}</textarea>
        </div>

        <input class="btn btn-success btn-lg btn-block" type="submit" value="Add Item"> 
    </form>

    <script type="text/javascript" src="{{ asset('js/item_search.js') }}"></script>
@endsection