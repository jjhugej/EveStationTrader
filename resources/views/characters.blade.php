@extends('layouts.app')

@section('content')

    <h1 class="mb-4 text-center">Characters</h1>
    <a href="/evelogin" class = "btn btn-primary mb-4">+ Add A Character</a>

        @if(Session::has('error'))
            <div class="alert alert-danger">
                {{ Session::get('error')}}
            </div>
        @endif

    @foreach($characters as $character)

        <div class="card mb-2">
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