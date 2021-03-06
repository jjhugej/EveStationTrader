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
    <form action="{{ config('baseUrl') }}/inventory/create" method="POST">

        @csrf

        <div class="table-responsive border mb-2">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th></th>
                        <th scope="col">Item</th>
                        <th scope="col">Price</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Order Type</th>
                        <th scope="col">Inventory</th>
                        <th scope="col">Date</th>
                        
    
                    </tr>
                </thead>
                <tbody>
                    @if($searchMatches !== null)
                        @foreach($searchMatches as $searchMatch)
                            <tr>
                            @if($searchMatch->is_buy_order == true || $searchMatch->inventory_id !== null)
                                <td></td>
                            @elseif($searchMatch->is_buy_order !== true && $searchMatch->is_buy_order == 0)
                                @if($searchMatch->inventory_id == null || $searchMatch->inventory_id == 0)
                                    <td><input type="checkbox" class="market_order_checkbox" name="market_order_id_array[]" value={{$searchMatch->order_id}}></td>
                                @endif
                            @endif
                                <td>{{$searchMatch->typeName}}</td>
                                <td>@formatNumber($searchMatch->price)</td>
                                <td>@formatNumber($searchMatch->volume_remain) / @formatNumber($searchMatch->volume_total)</td>
                        
                                @if($searchMatch->is_buy_order == 0)
                                    <td>Sell</td>
                                @else
                                    <td>Buy</td>
                                @endif
                                
                                <td>{{date('d-M-y', strtotime($searchMatch->issued))}}</td>
                                
                                @if($marketOrder->inventory_id !== null && $marketOrder->inventory_id !== 0)
                                    <td><a href="{{ config('baseUrl') }}/inventory/{{$marketOrder->inventory_id}}">View</a></td>
                                @else
                                    <td>No</td>
                                @endif
                                    <td> <a href="{{ config('baseUrl') }}/marketorders/{{$searchMatch->order_id}}">edit</a></td>     
                            </tr>
                        @endforeach                        
                    @endif
                </tbody>
            </table>
        </div>
        <input class="btn btn-success" id="submit_btn" type="submit" value="Add Items To Inventory">
    </form>

    <script type="text/javascript" src="{{ asset('js/marketOrder_search.js') }}"></script>

@endsection