<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashAccount extends Model
{
    use HasFactory;
    protected $fillable = [ 'is_main','account_name','account_type','payment_type','store_id','payment_type_id','account_number','bank_name','branch_name','routing_number','gateway_storeName','gateway_store_id','opening_balance'];

}
