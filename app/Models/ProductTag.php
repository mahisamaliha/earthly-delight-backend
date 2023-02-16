<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductTag extends Model
{
    protected $fillable=[
        'productId','tagId'
    ];
    public function tag(){
        return $this->belongsTo('App\Models\Tag','tagId');
    }
    public function mainProducts(){
        return $this->belongsTo('App\Models\MainProduct','productId');
    }
}
