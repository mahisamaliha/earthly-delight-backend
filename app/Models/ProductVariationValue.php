<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariationValue extends Model
{
    use HasFactory;
    protected $fillable=[
        'mproductId','pvariationId','value','name'
    ];
}
