@extends('layouts.app')

@section('content')

    <h1 class="text-center mb-5">Shopping List Detailed View</h1>

    @if(Session::has('status'))
        <div class="alert alert-success">
        {{ Session::get('status')}}
        </div>
    @endif

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

    <a href="#shoppingListItemFormHeader"><button class="btn btn-primary mb-1">+ Add Item</button></a>
    
    <div class="table-responsive border mb-3">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col">Item</th>
                    <th scope="col">Purchase Price</th>
                    <th scope="col">Amount Purchased</th>
                    <th scope="col">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($shoppingListItems as $shoppingListItem)
                    <tr>
                        <th class="fit" scope="row"> <a href="{{ config('baseUrl') }}/shoppinglistitem/{{$shoppingListItem->id}}">{{$shoppingListItem->name}}</a></th>
                        <td>@formatNumber($shoppingListItem->purchase_price)</td>
                        <td>@formatNumber($shoppingListItem->amount_purchased) / @formatNumber($shoppingListItem->amount)</td>
                        <td>{{$shoppingListItem->status}}</td>
                    <td><a href="{{ config('baseUrl') }}/shoppinglistitem/{{$shoppingListItem->id}}/delete">Delete</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <h2 name="shoppingListItemFormHeader" id="shoppingListItemFormHeader" class="text-center">Add Item To Shopping List</h2>

    <form name="shoppingListItemForm" id="shoppingListItemForm" method="POST" action="{{ config('baseUrl') }}/shoppinglistitem/create/{{$shoppingList->id}}">
        
        @csrf

        <div class="form-group mb-0">
            <label for="name">Item Name*:</label>
            <input autocomplete="off" type="text" name="name" id="name" class="form-control {{$errors->has('name') ? 'border border-danger' : ''}}" value="{{ old('name') }}" placeholder="" required>
        </div>
        <div class="container">
            <div id="js_item_search_results_target" class="card mb-3">
                <!-- Item Search Results Field -->
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col">
                <label for="purchase_price">Estimated Purchase Price:</label>
                <input type="number" name="purchase_price" id ="purchase_price" class="form-control {{$errors->has('purchase_price') ? 'border border-danger' : ''}}" value="{{ old('purchase_price') }}" placeholder="">
            </div>
            <div class="form-group col">
                <label for="amount">Amount:</label>
                <input type="number" name="amount" id="amount" class="form-control {{$errors->has('amount') ? 'border border-danger' : ''}}" value="{{ old('amount') }}" placeholder="">
            </div>
        </div>
        <div class="form-group">
            <label for="status" class="my-1 mr-2">Status:</label>
            <select name="status" id="status" class="custom-select my-1 mr-sm-2">
                <option value="Not Purchased">Not Purchased</option>
                <option value="Purchased">Purchased</option>
            </select>
        </div>
        <div id="inventoryCheckBoxWrapper" class="form-check mb-2 d-none">
                <input type="checkbox" class="form-check-input" name="inventoryCheckBox" id="inventoryCheckBox">
                <label class="form-check-label" for="inventoryCheckBox">Add item to inventory?</label>
        </div>
        <div class="form-group">
            <label for="notes">Notes:</label>
            <textarea  id="notes" name="notes" class="form-control {{$errors->has('notes') ? 'border border-danger' : ''}}" rows="3">{{ old('notes') }}</textarea>
        </div>

        <input class="btn btn-success btn-lg btn-block" type="submit" value="Add Item"> 
    </form>

    <script type="text/javascript" src="{{ asset('js/item_search.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/shopping_list_item_add.js') }}"></script>
@endsection