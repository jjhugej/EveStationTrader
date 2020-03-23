@extends('layouts.app')

@section('content')
    <h1>Create A Delivery Group</h1>

    <form>
        <div class="form-group">
            <label for="name">Name of delivery group:</label>
            <input type="text" class="form-control" id="name" placeholder="Delivery Group 1">
        </div>
        <div class="form-row">
            <div class="col">
                <label for="startStation">Start Station:</label>
                <input type="text" id = "startStation" class="form-control" placeholder="Jita IV - Moon 4 - Caldari Navy Assembly Plant">
            </div>
            <div class="col">
                <label for="endStation">End Station:</label>
                <input type="text" id = "endStation" class="form-control" placeholder="1DQ1-A - 1-st Imperial Palace">
            </div>
        </div>
        <div class="form-group">
            <label for="price">Price:</label>
            <input type="number" class="form-control" id="price" placeholder="35,000,000">
        </div>
        <div class="form-group">
            <label for="volume">Total Volume Limit:</label>
            <input type="number" class="form-control" id="volume" placeholder="50,000 m3">
        </div>
        <div class="form-group">
            <label for="status">Status:</label>
            <select class="form-control" id="status">
            <option>To Be Delivered</option>
            <option>Pending</option>
            <option>Delivered</option>
            </select>
        </div>
        <div class="form-group">
            <label for="notes">Notes:</label>
            <textarea class="form-control" id="notes" rows="3"></textarea>
        </div>
        <input class="btn btn-primary btn-lg btn-block" type="submit" value="Create"> 
    </form>

@endsection