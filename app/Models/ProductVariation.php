<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    protected $fillable=[
        'mproductId','name'
    ];
    public function values(){
        return $this->hasMany('App\Models\ProductVariationValue','pvariationId','id');
    }
    // public function mainProducts(){
    //     return $this->belongsTo('App\Models\MainProduct','productId');
    // }
}