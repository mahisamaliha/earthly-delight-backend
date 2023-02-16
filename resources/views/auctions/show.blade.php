<!-- show.blade.php -->

<h1>Auction Details</h1>
<p>Current Price: {{ $auction->current_price }}</p>
<p>Highest Bid: {{ $auction->highest_bid }}</p>
<p>Bid Count: {{ $auction->bid_count }}</p>
<p>Time Remaining: {{ $auction->time_remaining }}</p>
