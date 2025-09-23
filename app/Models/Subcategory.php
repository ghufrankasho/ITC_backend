<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Subcategory extends Model
{
     use SoftDeletes;
    protected $fillable=[
        'name',
        'seo_name',
        'description',
        'hide',
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
    // app/Models/Subcategory.php
    protected static function booted()
    {
        static::deleting(function ($subcategory) {
            $subcategory->products()->each(function ($product) {
                $product->delete(); // soft delete product
            });
        });
    }
}