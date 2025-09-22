<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
        protected $fillable=[
        'name',
        'seo_name',
        'message',
        'description',
        'email'
    ];
    
    public $timestamps=true;
}