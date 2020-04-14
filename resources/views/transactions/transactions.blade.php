@extends('layouts.app')

@section('content')

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

    <div class="table-responsive border">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col">Item</th>
                    <th scope="col">Unit Price</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Order Type</th>
                    <th scope="col">Date</th>
                    

                </tr>
            </thead>
            <tbody>
                @foreach($transactionHistory as $transactionHistory)
                    <tr>
                        <td>{{$transactionHistory->typeName}}</td>
                        <td>@formatNumber($transactionHistory->unit_price)</td>
                        <td>@formatNumber($transactionHistory->quantity)</td>
                
                        @if($transactionHistory->is_buy == 0)
                            <td>Sell</td>
                        @else
                            <td>Buy</td>
                        @endif
                        
                        <td>{{date('d-M-y', strtotime($transactionHistory->date))}}</td>
                        <td> <a href="#">edit</a></td>     
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script type="text/javascript" src="{{ asset('js/transaction_search.js') }}"></script>

@endsection