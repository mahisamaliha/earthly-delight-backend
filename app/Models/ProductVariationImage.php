<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariationImage extends Model
{
    protected $fillable = [ 'mproductId','productId', 'url' ];
}
