@extends('layouts.app')

@section('content')

<a href="{{ config('baseUrl') }}/transactions"><button class="btn btn-danger">< Transactions Home</button></a>
    <h1 class="text-center mb-5">Transactions Buy Orders View</h1>

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
   
    <form action="{{ config('baseUrl') }}/inventory/create" method="POST">

            @csrf
    
            <div id = "transactionsTableWrapper" class="table-responsive border">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th></th>
                            <th scope="col">Item</th>
                            <th scope="col">Unit Price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Order Type</th>
                            <th scope="col">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($searchMatches !== null)
                            @foreach($searchMatches as $searchMatch)
                                <tr>
                                    @if($searchMatch->is_buy == true)
                                        @if($searchMatch->inventory_id == null || $searchMatch->inventory_id == 0)
                                            <td><input type="checkbox" class="transaction_checkbox" name="transaction_id_array[]" value={{$searchMatch->transaction_id}}></td>
                                        @else
                                            <td></td>
                                        @endif
                                    @else
                                        <td></td>
                                    @endif
                                    <td>{{$searchMatch->typeName}}</td>
                                    <td>@formatNumber($searchMatch->unit_price)</td>
                                    <td>@formatNumber($searchMatch->quantity)</td>
                            
                                    @if($searchMatch->is_buy == 0)
                                        <td>Sell</td>
                                    @else
                                        <td>Buy</td>
                                    @endif
                                    
                                    <td>{{date('d-M-y', strtotime($searchMatch->date))}}</td>
                                    <td> <a href="#">edit</a></td>     
                                </tr>
                            @endforeach                        
                        @endif
                    </tbody>
                </table>
            </div>
            <input class="btn btn-success" id="submit_btn" type="submit" value="Add Items To Inventory">
    </form>

    <script type="text/javascript" src="{{ asset('js/transaction_search.js') }}"></script>

@endsection
