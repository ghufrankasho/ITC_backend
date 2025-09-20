<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    protected $fillable=[
        'name',
        'seo_name',
        'describtion',
        'seo_description',
        'category_id'
    ];
    
    
    public $timestamps=true;

    
    public function category(){
        
       return  $this->belongsTo(Category::class);
    }
    public function products(){

        return $this->hasMany(Product::class,'subcategory_id','id');
    }
}