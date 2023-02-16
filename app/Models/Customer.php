<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Customer extends Model{

    protected $fillable = [
       'userId', 'customerName', 'address', 'contact','email', 'zone', 'balance','opening','barcode','cityId','areaId','status','zoneId','facebook','instagram','postCode','is_archived','points','discount'
    ];
    public function outStanding(){
       return $this->hasOne('App\Models\Paymentsheet','uid')->where('paymentFor','customer')->whereIn('type',['due','opening','dueincoming'])->select('uid',DB::raw('ABS(sum(amount)) as outStanding'))->groupBy('uid');
    }
    public function bonus(){

       return $this->hasMany('App\Models\Bonus');
    }
    public function bonusAmount(){

       return $this->hasOne('App\Models\Bonus')->select('customer_id', DB::raw('sum(amount) as totalAmount'))->groupBy('customer_id');
    }
    public function zoneInfo()
    {
       return $this->belongsTo('App\Models\Zone','zoneId');
    }
    public function zone()
    {
      return $this->belongsTo('App\Models\Zone','zoneId');
    }
    public function area()
    {
      return $this->belongsTo('App\Models\UserArea','areaId');
    }
    public function city()
    {
      return $this->belongsTo('App\Models\UserCity','cityId');
    }


}
