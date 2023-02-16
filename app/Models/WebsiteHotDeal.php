<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteHotDeal extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'percentageTitle',
        'startTime',
        'endTime',
        'duration',
        'isHotSale',
    ];
    public function products(){
        return $this->hasMany('App\Models\WebsiteHotDealProduct','hotdeal_id')->with('product');
    }
}


