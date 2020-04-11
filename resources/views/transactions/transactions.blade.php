@extends('layouts.app')

@section('content')

    <h1 class="text-center mb-5">Transactions</h1>

    <div class="form-group mb-0">
        <label for="name">Search By Item Name</label>
        <input autocomplete="off" type="text" name="name" id="name" class="form-control {{$errors->has('name') ? 'border border-danger' : ''}}" value="{{ old('name') }}" placeholder="" required>
    </div>
    <div class="container">
        <div id="js_item_search_results_target" class="card mb-3">
            <!-- Item Search Results Field -->
        </div>
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