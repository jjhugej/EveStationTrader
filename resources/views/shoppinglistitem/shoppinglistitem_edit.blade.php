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
            <div class="col">
                <label for="purchase_price">Purchase Price:</label>
                <input type="number" name="purchase_price" id ="purchase_price" class="form-control {{$errors->has('purchase_price') ? 'border border-danger' : ''}}" value="{{ $shoppingListItem->purchase_price}}" placeholder="">
            </div>
            <div class="col">
                <label for="sell_price">Sell Price:</label>
                <input type="number" name="sell_price" id = "sell_price" class="form-control {{$errors->has('sell_price') ? 'border border-danger' : ''}}" value="{{ $shoppingListItem->sell_price }}" placeholder="">
            </div>
            <div class="form-group col">
                <label for="taxes_paid">Taxes Paid:</label>
                <input type="number" name="taxes_paid" id="taxes_paid" class="form-control {{$errors->has('taxes_paid') ? 'border border-danger' : ''}}" value="{{ $shoppingListItem->taxes_paid }}" placeholder="">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col">
                <label for="amount">Amount:</label>
                <input type="number" name="amount" id="amount" class="form-control {{$errors->has('amount') ? 'border border-danger' : ''}}" value="{{ $shoppingListItem->amount }}" placeholder="">
            </div>
            <div class="form-group col">
                <label for="par">Par:</label>
                <input type="number" name="par" id="par" class="form-control {{$errors->has('par') ? 'border border-danger' : ''}}" value="{{ $shoppingListItem->par }}" placeholder="">
            </div>
        </div>
        <div class="form-group">
                <label for="current_location">Current Location:</label>
                <input type="text" name="current_location" id="current_location" class="form-control {{$errors->has('current_location') ? 'border border-danger' : ''}}" value="{{ $shoppingListItem->current_location }}" placeholder="">
            </div>
        <div class="form-group">
            <label for="notes">Notes:</label>
            <textarea  id="notes" name="notes" class="form-control {{$errors->has('notes') ? 'border border-danger' : ''}}" rows="3">{{ $shoppingListItem->notes }}</textarea>
        </div>

        <input class="btn btn-success btn-lg btn-block" type="submit" value="Edit Item"> 
    </form>

    <script type="text/javascript" src="{{ asset('js/item_search.js') }}"></script>

@endsection