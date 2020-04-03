@extends('layouts.app')

@section('content')

    <h1 class="text-center mb-5">Shopping Lists Overview</h1>

    <a href="/shoppinglist/create"><button type="button" class="btn btn-success mb-2">+ Add New Shopping List </button></a>
    <div class="table-responsive border">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Number Of Items</th>
                    <th scope="col">Created On</th>
                    
                </tr>
            </thead>
            <tbody>
                @foreach($shoppingLists as $shoppingList)
                    <tr>
                        <th class="fit" scope="row"><a href="/shoppinglist/{{$shoppingList->id}}"> {{$shoppingList->name}} </a></th>
                        <td>n/a</td>
                        <td>{{ date('d-m-y', strtotime($shoppingList->created_at)) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection