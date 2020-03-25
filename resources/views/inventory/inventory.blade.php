@extends('layouts.app')

@section('content')
    <h1 class="text-center">Inventory Overview</h1>
    <a href="/inventory/create"><button type="button" class="btn btn-success">+ Add Item </button></a>
    <div class="table-responsive border">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col">Item</th>
                    <th scope="col">Purchase Price</th>
                    <th scope="col">Sell Price</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Par</th>
                    <th scope="col">Taxes Paid</th>
                    <th scope="col">Delivery Group</th>
                    <th scope="col">On Market?</th>
                    <th scope="col">Purchase Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($inventoryItems as $inventoryItem)
                    <tr>
                    <th class="fit" scope="row"><a href="{{ config('baseUrl') }}/inventory/show/{{$inventoryItem->id}}">{{$inventoryItem->name}}</a></th>
                        <td>{{$inventoryItem->purchase_price}}</td>
                        <td>{{$inventoryItem->sell_price}}</td>
                        <td>{{$inventoryItem->amount}}</td>
                        <td>{{$inventoryItem->par}}</td>
                        <td>{{$inventoryItem->taxes_paid}}</td>
                    <td><a href="{{config('baseUrl')}}/logistics/{{$inventoryItem->logistics_group_id}}">{{$inventoryItem->logistics_group_name}}</a></td>
                        <td><a href="#">no</a></td>
                        <td>{{$inventoryItem->created_at}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection