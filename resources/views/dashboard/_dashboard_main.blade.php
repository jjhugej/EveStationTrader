
<!-- top section -->

<div class="container mb-3">
  <div class="row">
    <div class="col-md border text-center p-4 m-2">
        <span class="font-weight-bold">@formatNumber($inventoryStats['numberOfInventoryItemsNotOnMarket'])</span>
        <hr class="w-50">
        <p class="font-italic">items not on market</p>
      </div>
    <div class="col-md border text-center p-4 m-2">
      <span class="font-weight-bold">@formatNumber($totalIskOnMarket)</span>
      <hr class="w-50">
      <p class="font-italic">value of sell orders</p>
    </div>
    <div class="col-md border text-center p-4 m-2">
      <span class="font-weight-bold">@formatNumber($numberOfShoppingListItemsNotPurchased)</span>
      <hr class="w-50">
      <p class="font-italic">shopping orders unfilled</p>
    </div>
    <div class="col-md border text-center p-4 m-2">
      <span class="font-weight-bold">@formatNumber($inventoryItemsUnderParCount)</span>
      <hr class="w-50">
      <p class="font-italic">items under par</p>
    </div>
  </div>
  
</div>

<!-- Middle Section -->
<div class="container mb-3">
  <div class="row">
    <div class="col-lg">
      <h5 class="text-center">Market Orders</h5>
      <hr class="w-50">
    
      <div class="table-responsive table-height-override mb-3">
        <table class="table table-sm">
          <thead class="thead-light">
            <tr>
              <th scope="col">Item</th>
              <th scope="col">Price</th>
              <th scope="col">Volume</th>
              <th scope="col">Type</th>
            </tr>
          </thead>
          <tbody>
            @foreach($marketOrders as $marketOrder)
            <tr>
              <td scope="row">{{$marketOrder->typeName}}</th>
              <td>@formatNumber($marketOrder->price)</td>
              <td>@formatNumber($marketOrder->volume_remain)</td>
              @if($marketOrder->is_buy_order == 1)
                <td>buy</td>
              @else
                <td>sell</td>
              @endif
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

  
  
  <div class="row">
    <div class="col-lg">

      <h5 class="text-center">Recent Transactions</h5>
      <hr class="w-50">

      <div class="table-responsive table-height-override">
        <table class="table table-sm">
          <thead class="thead-light">
            <tr>
              <th scope="col">Item</th>
              <th scope="col">Price</th>
              <th scope="col">Volume</th>
              <th scope="col">Type</th>
            </tr>
          </thead>
          <tbody>
            @foreach($transactionHistory as $transaction)
            <tr>
              <td scope="row">{{$transaction->typeName}}</th>
              <td>@formatNumber($transaction->unit_price)</td>
              <td>@formatNumber($transaction->quantity)</td>
              @if($transaction->is_buy == 1)
                <td>buy</td>
              @else
                <td>sell</td>
              @endif
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
    
</div>
    

<!-- second card section -->
  <div class="container mb-3">
    <div class="row">
      <div class="col-md border text-center p-4 m-2">
        <span class="font-weight-bold">@formatNumber($inventoryStats['numberOfItemsInInventory'])</span>
        <hr class="w-50">
        <p class="font-italic">items in inventory</p>
      </div>
      <div class="col-md border text-center p-4 m-2">
        <span class="font-weight-bold">@formatNumber($inventoryStats['totalIskAmountInInventory'])</span>
        <hr class="w-50">
        <p class="font-italic">value of inventory</p>
      </div>
      <div class="col-md border text-center p-4 m-2">
      <span class="font-weight-bold"> @formatNumber($numberOfItemsOnMarket) </span>
      <hr class="w-50">
      <p class="font-italic">items on market</p>
      </div>
    </div>
  </div>
