@extends('layouts.app')

@section('content')
    <h1>Create A Delivery Group</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="/logistics/create">
        
        @csrf

        <div class="form-group">
            <label for="name">Name of delivery group:</label>
        <input type="text" name="name" id="name" class="form-control {{$errors->has('name') ? 'border border-danger' : ''}}" value="{{ old('name') }}" placeholder="Delivery Group 1" required>
        </div>
        <div class="form-row">
            <div class="col">
                <label for="start_station">Start Station:</label>
                <input type="text" name="start_station" id ="start_station" class="form-control {{$errors->has('start_station') ? 'border border-danger' : ''}}" value="{{ old('start_station') }}" placeholder="Jita IV - Moon 4 - Caldari Navy Assembly Plant" required>
            </div>
            <div class="col">
                <label for="end_station">End Station:</label>
                <input type="text" name="end_station" id = "end_station" class="form-control {{$errors->has('end_station') ? 'border border-danger' : ''}}" value="{{ old('end_station') }}" placeholder="1DQ1-A - 1-st Imperial Palace" required>
            </div>
        </div>
        <div class="form-group">
            <label for="price">Price:</label>
            <input type="number" name="price" id="price" class="form-control {{$errors->has('price') ? 'border border-danger' : ''}}" value="{{ old('price') }}" placeholder="35,000,000">
        </div>
        <div class="form-group">
            <label for="volume_limit">Total Volume Limit:</label>
            <input type="number" name="volume_limit" id="volume_limit" class="form-control {{$errors->has('volume_limit') ? 'border border-danger' : ''}}" value="{{ old('volume_limit') }}" placeholder="50,000 m3">
        </div>
        <div class="form-group">
            <label for="status">Status:</label>
            <select  id="status" name ="status" class="form-control {{$errors->has('status') ? 'border border-danger' : ''}}">
            <option>To Be Delivered</option>
            <option>Pending</option>
            <option>Delivered</option>
            </select>
        </div>
        <div class="form-group">
            <label for="notes">Notes:</label>
            <textarea  id="notes" name="notes" class="form-control {{$errors->has('notes') ? 'border border-danger' : ''}}" rows="3">{{ old('notes') }}</textarea>
        </div>
        <input class="btn btn-primary btn-lg btn-block" type="submit" value="Create"> 
    </form>

@endsection