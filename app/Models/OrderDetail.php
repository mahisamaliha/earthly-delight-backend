<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $fillable = [
       'orderId', 'productId', 'quantity', 'price'
    ];
    public function product(){
        return $this->belongsTo('App\Models\Product', 'productId');
    }
}
