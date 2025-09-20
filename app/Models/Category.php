<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    
    protected $fillable=[
        'name',
        'seo_name',
        'describtion',
        'seo_description'
    ];
    
    public $timestamps=true;
    
    public function subcategories(){
        
       return  $this->hasMany(Subcategory::class);
    }
}