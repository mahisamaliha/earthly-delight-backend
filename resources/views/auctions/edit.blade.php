<h1>Edit Auction</h1>
<form action="{{ route('auctions.update', $auction->id) }}" method="post">
<div class="form-group">
    <label for="current_price">Current Price:</label>
    <input type="text" name="current_price" id="current_price" value="{{ $auction->current_price }}" class="form-control">
</div>

<div class="form-group">
    <label for="highest_bid">Highest Bid:</label>
    <input type="text" name="highest_bid" id="highest_bid" value="{{ $auction->highest_bid }}" class="form-control">
</div>

<div class="form-group">
    <label for="bid_count">Bid Count:</label>
    <input type="text" name="bid_count" id="bid_count" value="{{ $auction->bid_count }}" class="form-control">
</div>

<div class="form-group">
    <label for="time_remaining">Time Remaining:</label>
    <input type="text" name="time_remaining" id="time_remaining" value="{{ $auction->time_remaining }}" class="form-control">
</div>

<button type="submit" class="btn btn-primary">Update Auction</button>
</form>


