<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Selling extends Model
{
    use HasFactory;
    protected $fillable = [
        'admin_id', 'invoice_id', 'product_id','quantity','unitPrice','profit','discount','date','store_id','sellingPrice'
    ];
}
