@extends('layouts.app')

@section('content')
<a href="{{ url()->previous() }}"><button class="btn btn-danger">< Back</button></a>
    <h1 class="text-center mb-5">Transactions Search View</h1>
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

    <p><span class="font-italic">Currently showing results for: </span> 
        @if($searchMatches !== null)
         <span class="font-weight-bold">{{$searchMatches[0]->typeName}} </span></p>
        @else
           <span class="font-weight-bold text-danger">Item Not Found In This Character's History</span></p>
        @endif

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
                @if($searchMatches !== null)
                    @foreach($searchMatches as $searchMatch)
                        <tr>
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

    <script type="text/javascript" src="{{ asset('js/transaction_search.js') }}"></script>

@endsection