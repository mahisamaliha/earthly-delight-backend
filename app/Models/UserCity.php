<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCity extends Model
{
    protected $fillable = [
        'name',
    ];

    public function zones(){
        return $this->hasMany('App\Models\Zone', 'city_id');
    } 
}
