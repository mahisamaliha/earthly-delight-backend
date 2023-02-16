<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = [
        'groupName','menuId','discount'
    ];
    protected $casts = [
        'discount' => 'integer',
    ];
    public function category(){
        return $this->hasMany('App\Models\Category','group_id');
     }
    public function menuId()
    {
       return $this->belongsTo('App\Models\Menu','menuId');
    }
}
