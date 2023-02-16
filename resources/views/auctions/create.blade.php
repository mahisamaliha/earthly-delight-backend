<form action="{{route('auctions.store')}}" method="POST">
  @csrf
  <div class="form-group">
    <label for="current_price">Current Price</label>
    <input type="text" class="form-control" id="current_price" name="current_price">
  </div>
  <div class="form-group">
    <label for="highest_bid">Highest Bid</label>
    <input type="text" class="form-control" id="highest_bid" name="highest_bid">
  </div>
  <div class="form-group">
    <label for="bid_count">Bid Count</label>
    <input type="text" class="form-control" id="bid_count" name="bid_count">
  </div>
  <div class="form-group">
    <label for="time_remaining">Time Remaining</label>
    <input type="text" class="form-control" id="time_remaining" name="time_remaining">
  </div>
  <button type="submit" class="btn btn-primary">Create Auction</button>
</form>