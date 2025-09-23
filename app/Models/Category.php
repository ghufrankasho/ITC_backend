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
            $category->subcategories()->each(function ($subcategory) {
                $subcategory->delete(); // soft delete subcategory
            });
        });
}
}