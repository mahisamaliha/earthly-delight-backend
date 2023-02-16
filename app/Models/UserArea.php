<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserArea extends Model
{
    protected $fillable = [
        'name',
        'city_id',
        'zone_id',
        'postCode'
    ];

    public function city(){
        return $this->belongsTo('App\Models\UserCity', 'city_id');
        
    }

    public function zone(){
        return $this->belongsTo('App\Models\Zone', 'zone_id');
        
    }
}
