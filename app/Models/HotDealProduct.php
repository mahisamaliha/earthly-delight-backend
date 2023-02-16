<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotDealProduct extends Model
{
    protected $fillable = [
        'product_id',
        'discount',
        'percentage',
        'stock',
    ];
    use HasFactory;
}
