<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class MainProduct extends Model
{
    protected $fillable = [
        'slug',
        'menuId',
        'groupId',
        'categoryId',
        'brandId',
        'tagId',
        'productName',
        'model',
        'description',
        'unit',
        'brief_description',
        'sellingPrice',
        'averageBuyingPrice',
        'productImage',
        'images',
        'isNew',
        'totalSale',
        'isFeatured',
        'isHotDeal',
        'stock',
        'discount',
        'adminDiscount',
        'appDiscount',
        'openingQuantity',
        'openingunitPrice',
        'isAvailable',
        'is_archived',

    ];

    // public function productImages(){
    //     return $this->hasMany('App\Models\ProductImage','productId');
    // }
    public function review()
    {
      return $this->hasMany('App\Models\Review','productId' );
    }
    public function averageRating()
    {
      return $this->hasOne('App\Models\Review','productId' )->select('productId', DB::raw('round(AVG(rating)) AS rating'))->groupBy('productId');
    }
    public function productImages(){
      return $this->hasMany('App\Models\ProductImage','productId','id');
   }
    public function category()
    {
       return $this->belongsTo('App\Models\Category','categoryId','id');
    }
    public function brand()
    {
       return $this->belongsTo('App\Models\Brand','brandId','id');
    }
    public function group()
    {
       return $this->belongsTo('App\Models\Group','groupId','id');
    }
    public function tag(){
      return $this->hasMany('App\Models\Tag','tagId', 'id');
    }
    public function productTag(){
      return $this->hasMany('App\Models\ProductTag','productId', 'id');
    }
    public function productVariation(){
      return $this->hasMany('App\Models\ProductVariation','mproductId', 'id');
    }
    public function cart(){
      return $this->hasMany('App\Models\Tag','tagId', 'id');
    }
    public function variationproducts () {
      return $this->hasMany('App\Models\Product', 'mproductId', 'id')->where('isAvailable',1)->where('is_archived',0)->with('purchasestock')->with('sellstock')->with('productImages');
    }
    public function wishlist(){
      return $this->hasOne('App\Models\Wishlist','productId', 'id');
    }
    protected $casts = [
      'sellingPrice' => 'integer',
      'description' => 'array',
      // 'averageBuyingPrice' => 'integer',
      // 'openingUnitPrice' => 'integer',


   ];
    use HasFactory;
}