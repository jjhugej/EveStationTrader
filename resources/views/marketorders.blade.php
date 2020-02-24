@extends('layouts.app')

@section('content')

    <h1> MARKET ORDERS: </h1>
    <table class="table">
        <thead>
            <tr>
            <th scope="col">Item ID</th>
            <th scope="col">Price</th>
            <th scope="col">Amount Left</th>
            <th scope="col">Duration</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr>
                    <th scope="row">{{$order->order_id}}</th>
                        <td>{{$order->price}}</td>
                        <td>{{$order->volume_remain}}</td>
                        <td>{{$order->duration}}</td>
                </tr>
            @endforeach
          
        </tbody>
    </table>
    
@endsection


