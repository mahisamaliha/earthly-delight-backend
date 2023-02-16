<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'admin_id','uid','invoice_id' ,'type','paidAmount','date','store_id','account_id','remarks'
    ];
}
