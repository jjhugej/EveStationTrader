@extends('layouts.app')

@section('content')

    <div class="container">
        <h1>Characters:</h1>
        <div class="container">
            @foreach($characterInfo as $characterInfo)
                <div class="card mb-2">
                    <div class="card-body">
                            <p>Character Name: {{$characterInfo->character_name}}</p>
                            <p>Character ID: {{$characterInfo->character_id}}</p>

                            <p><a href="/dropcharacter/{{$characterInfo->character_id}}">Drop Character</a></p>
                    </div>
                </div>
            @endforeach 
        </div>
    </div>

@endsection