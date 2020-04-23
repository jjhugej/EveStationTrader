@extends('layouts.app')

@section('content')

    <h1 class="text-center mb-5">Edit Shopping List Item</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ config('baseUrl') }}/shoppinglistitem/{{$shoppingListItem->id}}/edit">
        
        @method('PUT')
        
        @csrf
        

        <div class="form-group mb-0">
            <label for="name">Item Name:</label>
            <input autocomplete="off" type="text" name="name" id="name" class="form-control {{$errors->has('name') ? 'border border-danger' : ''}}" value="{{ $shoppingListItem->name }}" placeholder="" required>
        </div>
        <div class="container">
            <div id="js_item_search_results_target" class="card mb-3">
                <!-- Item Search Results Field -->
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col">
                <label for="purchase_price">Purchase Price:</label>
                <input type="number" name="purchase_price" id ="purchase_price" class="form-control {{$errors->has('purchase_price') ? 'border border-danger' : ''}}" value="{{ $shoppingListItem->purchase_price}}" placeholder="">
            </div>
        
            <div class="form-group col">
                <label for="amount">Amount:</label>
                <input type="number" name="amount" id="amount" class="form-control {{$errors->has('amount') ? 'border border-danger' : ''}}" value="{{ $shoppingListItem->amount }}" placeholder="">
            </div>
        </div>
            <div class="form-group">
            <label for="status" class="my-1 mr-2">Status:</label>
            <select name="status" id="status" class="custom-select my-1 mr-sm-2">
                <option value="Not Purchased">Not Purchased</option>
                <option value="Partially Purchased">Partially Purchased</option>
                <option value="Purchased">Purchased</option>
            </select>
            <div id="inventoryCheckBoxWrapper" class="form-check mb-2 d-none">
                <input type="checkbox" class="form-check-input" name="inventoryCheckBox" id="inventoryCheckBox">
                <label class="form-check-label" for="inventoryCheckBox">Add item to inventory?</label>
        </div>
        </div>
        <div class="form-group">
            <label for="notes">Notes:</label>
            <textarea  id="notes" name="notes" class="form-control {{$errors->has('notes') ? 'border border-danger' : ''}}" rows="3">{{ $shoppingListItem->notes }}</textarea>
        </div>

        <input class="btn btn-success btn-lg btn-block" type="submit" value="Edit Item"> 
    </form>

    <script type="text/javascript" src="{{ asset('js/item_search.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/shopping_list_item_add.js') }}"></script>

@endsection