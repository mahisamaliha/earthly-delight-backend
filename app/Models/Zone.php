<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    protected $fillable = [
        'zoneName',
        'delivery',
        'city_id',
        'postCode'
    ];

    public function city(){
        return $this->belongsTo('App\Models\UserCity', 'city_id');
        
    }

    public function areas(){
        return $this->hasMany('App\Models\UserArea', 'zone_id');
    } 


}
