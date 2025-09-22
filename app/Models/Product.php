<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
   
    public $timestamps=true;
    
    public $fillable=[
        'name',
        'description',
        'image',
        'file',
        'hide',
        'seo_name',
        'seo_description',
        'subcategory_id'
        
    ];
    public function subcategory(){

        return $this->belongsTo(Subcategory::class,'subcategory_id','id');
    }
}