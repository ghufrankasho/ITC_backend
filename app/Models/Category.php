<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Category extends Model
{
    use SoftDeletes;
    protected $fillable=[
        'name',
        'seo_name',
        'description',
        'hide',
        'seo_description'
    ];
    
    public $timestamps=true;
    
    public function subcategories(){
        
       return  $this->hasMany(Subcategory::class);
    }
   
    protected static function booted()
    {
        static::deleting(function ($category) {
        if ($category->isForceDeleting()) {
            
            $subcategories=$category->subcategories()->withTrashed()->get();
            foreach($subcategories as $subcategory){
                $products=$subcategory->products()->withTrashed()->get();
                foreach( $products as $product){
                    $product->forceDelete();
                }
            }
            // hard delete subcategories & products
            $category->subcategories()->withTrashed()->forceDelete();
        } else {
            // soft delete
            $subcategories=$category->subcategories()->get();
            foreach($subcategories as $subcategory){
                $products=$subcategory->products()->get();
                foreach( $products as $product){
                    $product->delete();
                }
            }
            $category->subcategories()->delete();
        }
    });
            static::restoring(function ($category) {
            // restore subcategories
             $subcategories=$category->subcategories()->withTrashed()->get();
            foreach($subcategories as $subcategory){
                $products=$subcategory->products()->withTrashed()->get();
                foreach( $products as $product){
                    $product->restore();
                }
            }
            $category->subcategories()->withTrashed()->restore();
    
        });
}
}