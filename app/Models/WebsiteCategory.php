<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteCategory extends Model
{
    protected $fillable = [
        'image','catName', 'group_id'
    ];
   
    public function group()
    {
       return $this->belongsTo('App\Models\Group');
    } 
    use HasFactory;
}
