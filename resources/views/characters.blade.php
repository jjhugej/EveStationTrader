@extends('layouts.app')

@section('content')

    <h1>Characters:</h1>
    <a href="/evelogin" class = "btn btn-primary">Add A Character</a>
    @foreach($characters as $character)
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{$character->character_name}}</h5>
            @if($character->is_selected_character == 0)
                <a href="/selectcharacter/{{$character->character_id}}" class="btn btn-primary">Select Character</a>
            @endif
                <a href="/dropcharacter/{{$character->character_id}}" class="btn btn-danger">Drop Character</a>
        </div>
    </div>
    @endforeach

@endsection