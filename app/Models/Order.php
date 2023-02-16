<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'userId','name','contact','subTotal','grandTotal','shippingPrice', 'coupon', 'discount' , 'discountType', 'billingCity','billingZone','postCode', 'billingAddress','status' ,'paymentType'
    ];
    public function orderDetails(){
        return $this->hasMany('App\Models\OrderDetails','orderId');
    }
    public function countStatus(){
        return $this->hasOne('App\Models\Orders')->select(DB::raw ('count(status) as totalstatus'))
        ->where('status','=','Delivered');
    }




}
