@extends('layouts.app')

@section('content')

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

    <h1 class="text-center mb-5">Transactions</h1>
    <form action="{{ config('baseUrl') }}/transactions/search/show" method="GET">
        
        @csrf

        <div class="form-group mb-0">
            <label for="name">Search By Item Name</label> 
            <div class="row">
                <input class="col-sm-11" autocomplete="off" type="text" name="name" id="name" class="form-control {{$errors->has('name') ? 'border border-danger' : ''}}" value="{{ old('name') }}" placeholder="" required>
                <input class="col btn btn-primary" type="submit" value="search">
            </div>
        </div>
        
        <div class="row container">
            <div id="js_item_search_results_target" class="card mb-3 col-sm-11">
                <!-- Item Search Results Field -->
            </div>
        </div>
    </form>

    <div class="row mb-2">
        <a class="col-md-4 btn btn-success" href="{{ config('baseUrl') }}/transactions/search/sell">View Sell Orders</a>
        <span class="col-md-4"></span>
        <a class="col-md-4 btn btn-info" href="{{ config('baseUrl') }}/transactions/search/buy">View Buy Orders</a>
    </div>

    <form action="{{ config('baseUrl') }}/inventory/create" method="POST">

            @csrf

            <div id="transactionsTableWrapper" class="table-responsive border">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th></th>
                            <th scope="col">Item</th>
                            <th scope="col">Unit Price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Inventory</th>
                            <th scope="col">Order Type</th>
                            <th scope="col">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactionHistory as $transactionHistory)
                            <tr>
                                @if($transactionHistory->is_buy == true)
                                    @if($transactionHistory->inventory_id == null || $transactionHistory->inventory_id == 0)
                                    <td><input type="checkbox" class="transaction_checkbox" name="transaction_id_array[]" value={{$transactionHistory->transaction_id}}></td>
                                    @else
                                    <td></td>
                                    @endif
                                    @else
                                    <td></td>
                                @endif
                                <td>{{$transactionHistory->typeName}}</td>
                                <td>@formatNumber($transactionHistory->unit_price)</td>
                                <td>@formatNumber($transactionHistory->quantity)</td>
                                @if($transactionHistory->inventory_id !== null && $transactionHistory->inventory_id !== 0)
                                <td><a href="{{ config('baseUrl') }}/inventory/{{$transactionHistory->inventory_id}}">View</a></td>
                                @else
                                    <td>None</td>
                                @endif
                        
                                @if($transactionHistory->is_buy == 0)
                                    <td>Sell</td>
                                @else
                                    <td>Buy</td>
                                @endif
                                
                                <td>{{date('d-M-y', strtotime($transactionHistory->date))}}</td>  
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <p>Note:only completed "buy" transactions may be added to inventory</p>
            <input class="btn btn-success" id="submit_btn" type="submit" value="Add Items To Inventory">
    </form>

    <script type="text/javascript" src="{{ asset('js/transaction_search.js') }}"></script>

@endsection