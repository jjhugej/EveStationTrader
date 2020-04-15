@extends('layouts.app')

@section('content')
<a href="{{ url()->previous() }}"><button class="btn btn-danger">< Back</button></a>
    <h1 class="text-center mb-5">Market Orders Search View</h1>
    <form action="{{ config('baseUrl') }}/marketorders/search/show" method="GET">
        
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
           <span class="font-weight-bold text-danger">Item Not Found In This Character's Market Orders</span></p>
        @endif

    <div class="table-responsive border">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col">Item</th>
                    <th scope="col">Price</th>
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
                            <td>@formatNumber($searchMatch->price)</td>
                            <td>@formatNumber($searchMatch->volume_remain) / @formatNumber($searchMatch->volume_total)</td>
                    
                            @if($searchMatch->is_buy_order == 0)
                                <td>Sell</td>
                            @else
                                <td>Buy</td>
                            @endif
                            
                            <td>{{date('d-M-y', strtotime($searchMatch->issued))}}</td>
                            <td> <a href="{{ config('baseUrl') }}/marketorders/{{$searchMatch->order_id}}">edit</a></td>     
                        </tr>
                    @endforeach                        
                @endif
            </tbody>
        </table>
    </div>

    <script type="text/javascript" src="{{ asset('js/marketOrder_search.js') }}"></script>

@endsection