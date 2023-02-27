<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
	protected $fillable = [
        'admin_id', 'invoice_id', 'product_id','quantity','unitPrice','hasReturned','date','profit','store_id','type'
    ];
    public function admin()
    {
       return $this->belongsTo('App\Models\User', 'admin_id');
    }
    public function invoice()
    {
       return $this->belongsTo('App\Models\Invoice');
    }
    public function product()
    {
       return $this->belongsTo('App\Models\Product');
    }
    protected $casts = [
      'quantity' => 'integer',
      'profit' => 'integer',
      'unitPrice' => 'integer',

  ];

}