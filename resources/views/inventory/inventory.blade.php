@extends('layouts.app')

@section('content')
    <h1 class="text-center mb-5">Inventory Overview</h1>
    <a href="/inventory/create"><button type="button" class="btn btn-success mb-2">+ Add Item </button></a>
    <div class="table-responsive border">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col">Item</th>
                    <th scope="col">Sell Price</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Par</th>
                    <th scope="col">Delivery Group</th>
                    <th scope="col">Current Location</th>
                    <th scope="col">Assigned Market Order</th>
                    <th scope="col">Created At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                    <tr>
                    <th class="fit" scope="row"><a href="{{ config('baseUrl') }}/inventory/{{$item->id}}">{{$item->name}}</a></th>
                        <td>@formatNumber($item->sell_price)</td>
                        <td>@formatNumber($item->amount)</td>
                        <td>@formatNumber($item->par)</td>
                        @if($item->logistics_group_id == 0 || $item->logistics_group_id == null)
                            <td><a href="{{ config('baseUrl') }}/inventory/{{$item->id}}/edit">Assign</a></td>
                        @else
                            <td><a href="{{config('baseUrl')}}/logistics/{{$item->logistics_group_id}}">{{$item->logistics_group_name}}</a></td>
                        @endif
                        <td>{{$item->current_location}}</td>
                        @if($item->market_order_id == 0 || $item->market_order_id == null)
                            <td><a href="{{ config('baseUrl') }}/inventory/{{$item->id}}/edit">Assign</a></td>
                        @else
                            <td><a href="{{config('baseUrl')}}/marketorders/{{$item->market_order_id}}">View</a></td>
                        @endif
                        <td>{{date('d-M-y', strtotime($item->created_at))}}</td>    
                        <td> <a href="{{ config('baseUrl') }}/inventory/{{$item->id}}/edit">edit</a></td>     
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection