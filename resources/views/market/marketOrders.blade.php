@extends('layouts.app')

@section('content')
    <h1 class="mb-4 text-center">Market Orders</h1>

    <form action="{{ config('baseUrl') }}/marketorders/search/show" method="GET">
        
        @csrf

        <div class="form-group mb-0">
            <label for="name">Search By Item Name</label> 
            <div class="row">
                <input class="col-sm-10" autocomplete="off" type="text" name="name" id="name" class="form-control {{$errors->has('name') ? 'border border-danger' : ''}}" value="{{ old('name') }}" placeholder="" required>
                <input class="col-sm-2 btn btn-primary" type="submit" value="search">
            </div>
        </div>
        
        <div class="row container">
            <div id="js_item_search_results_target" class="card mb-3 col-sm-10">
                <!-- Item Search Results Field -->
            </div>
        </div>
    </form>

    <div class="row mb-2">
        <a class="col-md-4 btn btn-success mb-2" href="{{ config('baseUrl') }}/marketorders/search/sell">View Sell Orders</a>
        <span class="col-md-4"></span>
        <a class="col-md-4 btn btn-info mb-2" href="{{ config('baseUrl') }}/marketorders/search/buy">View Buy Orders</a>
    </div>

    <div class="table-responsive border">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                <th scope="col">Item</th>
                <th scope="col">Price</th>
                <th scope="col">Volume</th>
                <th scope="col">Location</th>
                <th scope="col">Order Type</th>
                <th scope="col">Character</th>
                </tr>
            </thead>
            <tbody>
                @foreach($marketOrders as $marketOrder)
                    <tr>
                        <th class="fit" scope="row"> <a href="/marketorders/{{$marketOrder->order_id}}">{{ $marketOrder->typeName}}</a></th>
                        <td>@convertNumberToCurrency($marketOrder->price)</td>
                        <td>@formatNumber($marketOrder->volume_remain) {{'/'}} @formatNumber($marketOrder->volume_total)</td>
                        <td>{{$marketOrder->locationName}}</td>
                        @if($marketOrder->is_buy_order == true)
                            <td>Buy</td>
                        @else
                            <td>Sell</td>
                        @endif
                        <td>{{$marketOrder->character_name}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script type="text/javascript" src="{{asset('js/marketOrder_search.js')}}"></script>
@endsection