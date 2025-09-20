<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
        protected $fillable=[
        'image',
        'seo_title',
        'describtion',
        'title'
    ];
    
    public $timestamps=true;
}