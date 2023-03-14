<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuctionTracking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'auction_id',
        'bidding_price'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function auction()
    {
        return $this->belongsTo(Auction::class, 'auction_id');
    }
}
