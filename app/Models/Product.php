<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
	protected $fillable = [
        'menuId','productName','groupName','catName','brand','unit' , 'sellingPrice', 'barCode' ,'opening',
        'groupId','categoryId','brandId','mproductId','openingQuantity','openingUnitPrice','stock','model','variation','is_archived'
    ];
    public function mainproduct(){
       return $this->belongsTo('App\Models\MainProduct','mproductId','id');
    }
   public function productImages(){
      return $this->hasMany('App\Models\ProductVariationImage','productId');
   }

    public function selling()
    {
       return $this->belongsTo('App\Models\Selling');
    }
    public function sellStock()
    {
       return $this->hasOne('App\Models\Selling');
    }
    public function purchase()
    {
       return $this->belongsTo('App\Models\Purchase');
    }
    public function purchaseStock()
    {
       return $this->hasOne('App\Models\Purchase');
    }
    public function groupDiscount()
    {
       return $this->hasOne('App\Models\Group');
    }
    public function group()
    {
       return $this->belongsTo('App\Models\Group','groupId','id');
    }

    public function category()
    {
       return $this->belongsTo('App\Models\Category','categoryId','id');
    }
    public function brand()
    {
       return $this->belongsTo('App\Models\Brand','brandId','id');
    }


    protected $casts = [
      'sellingPrice' => 'integer',
      'averageBuyingPrice' => 'integer',
      'openingQuantity' => 'integer',
      'openingUnitPrice' => 'integer',
      'variation' => 'array',
      'images' => 'array',


   ];


    //public function


    // public function group()
    // {
    //    return $this->belongsTo('App\Models\Group');
    // }
    // public function category()
    // {
    //    return $this->belongsTo('App\Models\Group');
    // }
    //     public function unit_type()
    // {
    //    return $this->belongsTo('App\Models\Unit_type');
    // }
}
