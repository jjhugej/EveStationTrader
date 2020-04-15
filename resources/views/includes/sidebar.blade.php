  <div id="sideBarNav">
    @if(Session::has('characterPortrait'))
      <img class="mb-4" id = "profilePicture" src="{{Session::get('characterPortrait')}}" alt="">
    @else
      <img class="mb-4" id = "profilePicture" src="https://texasgeneralinsurance.com/wp-content/uploads/Person-placeholder.png" alt="">
    @endif
    <div id="sideBarLinkWrapper">
      <a href="/dashboard">Dashboard</a>
      <a href="/characters">Characters</a>
      <a href="/inventory">Inventory</a>
      <a href="/logistics">Logistics</a>
      <a href="/marketorders">Market Orders</a>
      <a href="/shoppinglist">Shopping List</a>
      <a href="/transactions">Transactions</a>
      <a href="/logout">Sign Out</a>
    </div>
  </div>

