<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
        protected $fillable=[
        'key',
        'group',
        'value',
        'hide'
        
    ];
    
    public $timestamps=true;
}