@extends('layouts.app')

@section('content')

  @csrf
  
  <div id="loading">
    <p>Loading</p>
  </div>

  <script type="text/javascript" src="{{asset('js/dashboard.js')}}"></script>
@endsection