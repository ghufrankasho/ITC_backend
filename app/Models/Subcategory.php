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
            if ($subcategory->isForceDeleting()) {
                // hard delete products
               
                 $products = $subcategory->products()->withTrashed()->get();
                // Force-delete each product so Product::deleting runs
                foreach ($products as $product) {
                    $product->forceDelete();
                }
            $subcategory->products()->withTrashed()->forceDelete();
            } else {
                // soft delete
                $subcategory->products()->delete();
            }
        });

        static::restoring(function ($subcategory) {
            $subcategory->products()->withTrashed()->restore();
        });
    }
}