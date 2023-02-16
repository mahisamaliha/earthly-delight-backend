<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $fillable = [
        'companyName','ownerName','','companyLogo','invoiceNote','currencyType', 'refererBonus' , 'referenceBonus','contact', 'address',
        'ownerContact','facebookLink','favIcon','supportEmail','websiteLink','isShippingFree','shippingFreeAfter','supportContact','invoiceTag','opening_date','min_points','membership_discount'
    ];
}
