@extends('layouts.app')

@section('content')


 <!-- Top Box -->
    <div id= "dashboardTopBoxWrapper" class="row">
        <div class="col-md dashboardTopBox">
            <h1>42</h1>
            <p>Items Under Par</p>
        </div>

        <div class="col-md dashboardTopBox">
            <h1>182</h1>
            <p>Items On Market</p>
        </div>

        <div class="col-md dashboardTopBox">
            <h1>12</h1>
            <p>Items In Cart</p>
        </div>
    </div>


<!-- Middle Box -->
    <div id="dashboardMidBoxWrapper">
        <table>
            <th scope="col">Name</th>
            <th scope="col">Price</th>
            <th scope="col">Volume</th>
        </table>
    </div>
<!-- Bottom Box -->
    <div id="dashboardBotBoxWrapper">
        <h1 class="dashboardHeader">Shopping List:</h1>
        <div id="dashboardBotBox">      
            <table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">First</th>
      <th scope="col">Last</th>
      <th scope="col">Handle</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td scope="row">1</th>
      <td>Mark</td>
      <td>Otto</td>
      <td>@mdo</td>
    </tr>
    <tr>
      <th scope="row">2</th>
      <td>Jacob</td>
      <td>Thornton</td>
      <td>@fat</td>
    </tr>
  </tbody>
</table>
        </div>
    </div>

@endsection