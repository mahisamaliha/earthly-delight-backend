<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $fillable = [
        'userId',
        'productId',
    ];

    public function user(){
        return $this->belongsTo('App\Models\User', 'userId');

    }
    public function product(){
        return $this->belongsTo('App\Models\MainProduct', 'productId');

    }

    use HasFactory;
}
