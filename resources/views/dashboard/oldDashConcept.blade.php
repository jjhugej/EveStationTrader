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
    <h1 class="dashboardHeader">Market Orders:</h1>
    <table class="table table-responsive table-striped">
        <thead>
            <tr>
            <th scope="col">Name</th>
            <th scope="col">Price</th>
            <th scope="col">Volume</th>
            <th scope="col">Station</th>
            </tr>
        </thead>
        <tbody>
            <tr>
            <td scope="row"><span>Tornadooooooooooooooooooooooooooooooooo</span></th>
            <td><span>15,000,000000000000000000000000000000000000000000</span></td>
            <td><span>40/100</span></td>
            <td><span>PF-QHK</span></td>
            </tr>
            <tr>
            <td scope="row">Gyrostabilizer</th>
            <td>1,500,000</td>
            <td>114/200</td>
            <td>PF-QHK</td>
            </tr>
            <tr>
            <td scope="row">Hail L</th>
            <td>1,200</td>
            <td>96,573/200,000</td>
            <td>PF-QHK</td>
            </tr>
        </tbody>
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
      <th scope="row">1</th>
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