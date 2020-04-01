@extends('layouts.app')

@section('content')
    <h1 class="text-center mb-5">Inventory Overview</h1>
    <a href="/inventory/create"><button type="button" class="btn btn-success mb-1">+ Add Item </button></a>
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
                    <th scope="col">Current Location</th>
                    <th scope="col">On Market?</th>
                    <th scope="col">Created At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                    <tr>
                    <th class="fit" scope="row"><a href="{{ config('baseUrl') }}/inventory/{{$item->id}}">{{$item->name}}</a></th>
                        <td>@formatNumber($item->purchase_price)</td>
                        <td>@formatNumber($item->sell_price)</td>
                        <td>@formatNumber($item->amount)</td>
                        <td>@formatNumber($item->par)</td>
                        <td>@formatNumber($item->taxes_paid)</td>
                        <td><a href="{{config('baseUrl')}}/logistics/{{$item->logistics_group_id}}">{{$item->logistics_group_name}}</a></td>
                        <td>{{$item->current_location}}</td>
                        <td><a href="#">no</a></td>
                        <td>{{$item->created_at}}</td>
                        <td> <a href="{{ config('baseUrl') }}/inventory/{{$item->id}}/edit">edit</a></td>
                        <td> <a href="{{ config('baseUrl') }}/inventory/{{$item->id}}/delete">delete</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection