@extends('layouts.app')

@section('content')
    <h1>Logistics</h1>
    <a href="logistics/create"><button type="button" class="btn btn-primary">Create A Delivery</button></a>
    <div class="table-responsive border">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                <th scope="col">Item</th>
                <th scope="col">Price</th>
                <th scope="col">Volume</th>
                <th scope="col">Par</th>
                <th scope="col">Location</th>
                </tr>
            </thead>
            <tbody>
                    <tr>
                        <th class="fit" scope="row">N/A</th>
                        <td>N/A</td>
                        <td>N/A</td>
                        <td>N/A</td>
                        <td>N/A</td>
                    </tr>
            </tbody>
        </table>
    </div>
@endsection