@extends('layouts.app')

@section('content')
    <h1 class="text-center">Edit Item</h1>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ config('baseUrl') }}/inventory/{{$inventoryItem->id}}/edit">
        
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Item Name:</label>
            <input type="text" name="name" id="name" class="form-control {{$errors->has('name') ? 'border border-danger' : ''}}" value="{{ $inventoryItem->name }}" placeholder="" required>
        </div>
        <div class="container">
            <div id="js_item_search_results_target" class="card mb-3">
                <!-- Item Search Results Field -->
            </div>
        </div>
        <div class="form-row">
            <div class="col">
                <label for="purchase_price">Purchase Price:</label>
                <input type="number" name="purchase_price" id ="purchase_price" class="form-control {{$errors->has('purchase_price') ? 'border border-danger' : ''}}" value="{{ $inventoryItem->purchase_price }}" placeholder="">
            </div>
            <div class="col">
                <label for="sell_price">Sell Price:</label>
                <input type="number" name="sell_price" id = "sell_price" class="form-control {{$errors->has('sell_price') ? 'border border-danger' : ''}}" value="{{ $inventoryItem->sell_price }}" placeholder="">
            </div>
            <div class="form-group col">
                <label for="taxes_paid">Taxes Paid:</label>
                <input type="number" name="taxes_paid" id="taxes_paid" class="form-control {{$errors->has('taxes_paid') ? 'border border-danger' : ''}}" value="{{ $inventoryItem->taxes_paid }}" placeholder="">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col">
                <label for="amount">Amount:</label>
                <input type="number" name="amount" id="amount" class="form-control {{$errors->has('amount') ? 'border border-danger' : ''}}" value="{{ $inventoryItem->amount }}" placeholder="">
            </div>
            <div class="form-group col">
                <label for="par">Par:</label>
                <input type="number" name="par" id="par" class="form-control {{$errors->has('par') ? 'border border-danger' : ''}}" value="{{ $inventoryItem->par }}" placeholder="">
            </div>
        </div>
        <div class="form-group">
                <label for="current_location">Current Location:</label>
                <input type="text" name="current_location" id="current_location" class="form-control {{$errors->has('current_location') ? 'border border-danger' : ''}}" value="{{ $inventoryItem->current_location }}" placeholder="">
            </div>
        <div class="form-group">
            <label for="notes">Notes:</label>
            <textarea  id="notes" name="notes" class="form-control {{$errors->has('notes') ? 'border border-danger' : ''}}" rows="3">{{ $inventoryItem->notes }}</textarea>
        </div>
        <div class="form-group">
            <h2 class="text-center">Assign Item To Delivery Group</h2>
            <a href="{{ config('baseUrl') }}/logistics/create"><button type="button" class="btn btn-primary">+ Create A Delivery Group</button></a>
        <div class="table-responsive border mb-5">
        <table id="deliveryGroupTable" class="table table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col">Select</th>
                    <th scope="col">Group Name</th>
                    <th scope="col">Price</th>
                    <th scope="col">Volume</th>
                    <th scope="col">Status</th>
                    <th scope="col">Start Location</th>
                    <th scope="col">End Location</th>
                    <th scope="col">Created At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($deliveryGroups as $deliveryGroup)
                    <tr>
                    <th scope="row">                        
                        <input type="checkbox" name="delivery_group_select" value="{{$deliveryGroup->id}}">
                    </th>
                    <td class="fit"><a href="/logistics/{{$deliveryGroup->id}}">{{$deliveryGroup->name}}</a></td>
                        <td>@formatNumber($deliveryGroup->price)</td>
                        <td>@formatNumber($deliveryGroup->volume_limit)</td>
                        <td>{{$deliveryGroup->status}}</td>
                        <td>{{$deliveryGroup->start_station}}</td>
                        <td>{{$deliveryGroup->end_station}}</td>
                        <td class="fit">{{$deliveryGroup->created_at}}</td>
                    </tr>
                @endforeach
            </tbody>
            </table>
        </div>
    </div>
    <h2 class="text-center">Assign Item To Market Order</h2>
    <div class="table-responsive border mb-4">
            <table id="marketOrderGroupTable" class="table table-striped table-hover">
                <thead>
                    <tr>
                    <th scope="col">Select</th>
                    <th scope="col">Item</th>
                    <th scope="col">Price</th>
                    <th scope="col">Volume</th>
                    <th scope="col">Par</th>
                    <th scope="col">Location</th>
                    <th scope="col">Character Name</th>
                    </tr>
                </thead>
                <tbody id="js-market-order-target">
                    @foreach($marketOrders as $marketOrder)
                        <tr>
                            <th  scope="row">
                                <input class="market-order-id-select" type="radio" name="market_order_id_select" 
                                value="{{$marketOrder->order_id}},{{ $marketOrder->typeName}},{{$marketOrder->price}},{{$marketOrder->volume_remain}},{{$marketOrder->volume_total}},{{$marketOrder->locationName}}">
                            </th>
                            <td class="fit">{{ $marketOrder->typeName}}</td>
                            <td>@convertNumberToCurrency($marketOrder->price)</td>
                            <td>@formatNumber($marketOrder->volume_remain) / @formatNumber($marketOrder->volume_total)</td>
                            <td>N/A</td>
                            <td>{{$marketOrder->locationName}}</td>
                            <td>{{$marketOrder->character_name}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <input class="btn btn-success btn-lg btn-block" type="submit" value="Edit Item"> 
    </form>
     <script type="text/javascript" src="{{ asset('js/item_search.js') }}"></script>
@endsection