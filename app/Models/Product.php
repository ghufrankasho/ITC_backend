<?php

namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
class Product extends Model
{
     use SoftDeletes;
   
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
    
     public function deleteImage($url) {
        // Parse the URL and get the path part
        $parsedUrl = parse_url($url, PHP_URL_PATH);
        // return [$parsedUrl];
        // Remove leading slashes from the path if any
        $parsedUrl = ltrim($parsedUrl, '/');
        // return [$parsedUrl];
        // Construct the full path of the image using public_path
        $fullPath = public_path($parsedUrl);
        gc_collect_cycles();
        // return [$fullPath];
        // Check if the image file exists and delete it
        if (file_exists($fullPath)) {
            if (unlink($fullPath)) {
                return true;
            } else {
                return false; // Failed to delete the file
            }
        } else {
            return false; // File does not exist
        }
    }
   
     protected static function booted()
    {
        static::deleting(function ($product) {
            if ($product->isForceDeleting()) {
                // hard delete products
                 if($product->image !== null){
                        
                        $product->deleteImage($product->image);
                    }
                    if($product->file !== null){
                        
                        $product->deleteImage($product->file);
                    }
                 
            } 
        });

        static::restoring(function ($product) {
            $product->withTrashed()->restore();
        });
    }
    
    
  
}