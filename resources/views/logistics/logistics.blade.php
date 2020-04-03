@extends('layouts.app')

@section('content')
    <h1 class="text-center mb-5">Logistics Overview</h1>
    <a href="logistics/create"><button type="button" class="btn btn-primary mb-2">+ Create A New Delivery Group</button></a>
    <div class="table-responsive border">
        <table id="logisticsGroupTable" class="table table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col">Group Name</th>
                    <th scope="col">Delivery Cost</th>
                    <th scope="col">Volume</th>
                    <th scope="col">Status</th>
                    <th scope="col">Start - Location</th>
                    <th scope="col">End - Location</th>
                    <th scope="col">Created At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($deliveryGroups as $deliveryGroup)
                    <tr>
                    <th class="fit" scope="row"><a href="/logistics/{{$deliveryGroup->id}}">{{$deliveryGroup->name}}</a></th>
                        <td>@formatNumber($deliveryGroup->price)</td>
                        <td>@formatNumber($deliveryGroup->volume_limit)</td>
                        <td>{{$deliveryGroup->status}}</td>
                        <td>{{$deliveryGroup->start_station}}</td>
                        <td>{{$deliveryGroup->end_station}}</td>
                        <td class="fit">{{$deliveryGroup->created_at}}</td>
                        <td><a href="/logistics/{{$deliveryGroup->id}}/edit">edit</a></td>
                        <td><a href="/logistics/{{$deliveryGroup->id}}/delete">delete</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection