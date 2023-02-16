<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{ 
    protected $table = 'categories';
    
    protected $fillable = [
        'image','catName', 'group_id'
    ];
   
    public function group()
    {
       return $this->belongsTo('App\Models\Group');
    } 
    use HasFactory;
}
