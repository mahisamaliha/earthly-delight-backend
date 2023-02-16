<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paymentsheet extends Model
{
    use HasFactory;
    protected $fillable = [
        'admin_id','uid', 'amount','type','paymentFor','remarks','paymentMethod','invoice_id','date','payment_id','voucher_id','store_id','account_id','sale_type'
    ];
}
