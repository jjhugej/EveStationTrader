@extends('layouts.app')

@section('content')

<a href="{{ url()->previous() }}"><button class="btn btn-danger">< Back</button></a>

    <h1 class="text-center mb-5">Shopping List Detailed View</h1>

    <div class="container border mb-4 p-4">
        <p>Name: {{$shoppingList->name}}</p>
        <p>Notes: {{$shoppingList->notes}}</p>
        <p>Created On: {{ date('d-m-y', strtotime($shoppingList->created_at)) }}</p>
        <p>Last Update: {{ date('d-m-y', strtotime($shoppingList->updated_at)) }}</p>
    </div>

    <h2 class="text-center">Add Item To Shopping List</h2>
    <form method="POST" action="{{ config('baseUrl') }}/shoppinglistitem/create">
        
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