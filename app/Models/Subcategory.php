<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
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
     protected static function booted()
    {
        static::deleting(function ($subcategory) {
            // delete related products (this will trigger Product::deleting)
            foreach ($subcategory->products as $product) {
                $product->delete();
            }
        });
    }
}