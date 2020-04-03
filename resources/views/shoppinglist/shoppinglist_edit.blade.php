@extends('layouts.app')

@section('content')

<h1 class="text-center mb-5"> Edit Shopping List </h1>

@if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ config('baseUrl') }}/shoppinglist/{{$shoppingList->id}}/edit">
        
        @method('PUT')

        @csrf

        <div class="form-group mb-4">
            <label for="name">Shopping List Name:</label>
            <input type="text" name="name" class="form-control {{$errors->has('name') ? 'border border-danger' : ''}}" value="{{ $shoppingList->name }}" required>
        </div>
        <div class="form-group mb-4">
            <label for="notes">Notes:</label>
            <textarea name="notes" class="form-control {{$errors->has('notes') ? 'border border-danger' : ''}}" rows="3">{{ $shoppingList->notes }}</textarea>
        </div>
        <input class="btn btn-success btn-lg btn-block" type="submit" value="Edit Shopping List"> 
    </form>

@endsection