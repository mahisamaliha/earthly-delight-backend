<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'userId',
        'mproductId',
        'productId',
        'menuId',
        'categoryId',
        'subcategoryId',
        'product',
        'quantity',
    ];
    public function vproduct(){
        return $this->belongsTo('App\Models\Product','productId');
    }
    public function mproduct(){
        return $this->belongsTo('App\Models\MainProduct','mproductId');
    }
    use HasFactory;
}
