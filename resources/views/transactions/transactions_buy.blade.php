@extends('layouts.app')

@section('content')

@extends('layouts.app')

@section('content')
<a href="{{ url()->previous() }}"><button class="btn btn-danger">< Back</button></a>
    <h1 class="text-center mb-5">Transactions Buy Orders View</h1>

   

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


@endsection