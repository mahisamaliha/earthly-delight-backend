<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $fillable = [
        'admin_id',
        'store_id',
        'account_id',
        'sale_type',
        'purchase_type',
        'type',
        'totalQuantity',
        'subTotal',
        'invoiceTotal',
        'grandTotal',
        'giftVoucher',
        'promoCode',
        'refferalDiscount',
        'membershipDiscount',
        'promoDiscount',
        'specialDiscount',
        'roundingAmount',
        'refferalDiscountAmount',
        'membershipDiscountAmount',
        'promoDiscountAmount',
        'specialDiscountAmount',
        'giftVoucherAmount',
        'paidAmount',
        'cashPaid',
        'changeAmount',
        'bonusAmount',
        'homeDelivery',
        'homeDeliveryAmount',
        'customer_id',
        'supplier_id',
        'rinvoice_id',
        'date',
    ];
    public function customer () {
        return $this->belongsTo('App\Models\Customer');
    }
}
