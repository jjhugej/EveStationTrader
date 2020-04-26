@extends('layouts.app')

@section('content')
    <h1 class="mb-4 text-center">Market Orders</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if(Session::has('error'))
        <div class="alert alert-danger">
        {{ Session::get('error')}}
        </div>
    @endif
    
    @if(Session::has('status'))
        <div class="alert alert-success">
        {{ Session::get('status')}}
        </div>
    @endif

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

    <form action="{{ config('baseUrl') }}/inventory/create" method="POST">

        @csrf

        <div id="marketOrdersTableWrapper" class="table-responsive border mb-1">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                    <th></th>
                    <th scope="col">Item</th>
                    <th scope="col">Price</th>
                    <th scope="col">Volume</th>
                    <th scope="col">Location</th>
                    <th scope="col">Order Type</th>
                    <th scope="col">Inventory</th>
                    <th scope="col">Character</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($marketOrders as $marketOrder)
                        <tr>

                            @if($marketOrder->is_buy_order == true || $marketOrder->inventory_id !== null)
                                <td></td>
                            @elseif($marketOrder->is_buy_order !== true && $marketOrder->is_buy_order == 0)
                                @if($marketOrder->inventory_id == null || $marketOrder->inventory_id == 0)
                                    <td><input type="checkbox" class="market_order_checkbox" name="market_order_id_array[]" value={{$marketOrder->order_id}}></td>
                                @endif
                            @endif

                            <th class="fit" scope="row"> <a href="/marketorders/{{$marketOrder->order_id}}">{{ $marketOrder->typeName}}</a></th>
                            <td>@convertNumberToCurrency($marketOrder->price)</td>
                            <td>@formatNumber($marketOrder->volume_remain) {{'/'}} @formatNumber($marketOrder->volume_total)</td>
                            <td>{{$marketOrder->locationName}}</td>

                            @if($marketOrder->is_buy_order == true)
                                <td>Buy</td>
                            @else
                                <td>Sell</td>
                            @endif

                            @if($marketOrder->inventory_id !== null && $marketOrder->inventory_id !== 0)
                                <td><a href="{{ config('baseUrl') }}/inventory/{{$marketOrder->inventory_id}}">View</a></td>
                            @else
                                <td>No</td>
                            @endif

                            <td>{{$marketOrder->character_name}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <p>Note: You may only add existing sell orders or orders not associated with an inventory item</p>
        <input class="btn btn-success" id="submit_btn" type="submit" value="Add Items To Inventory">
    </form>

    <script type="text/javascript" src="{{asset('js/marketOrder_search.js')}}"></script>
@endsection