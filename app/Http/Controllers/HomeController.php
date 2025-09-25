<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\Category;
use App\Models\News;
use App\Models\Setting;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){

        $sliders=Setting::where([['hide',0],['group','slider']])->get();
        $categories=Category::where('hide',0)->with('subcategories')->get();
        return [
            'sliders'=>$sliders,
            'categories'=>$categories,
        ];
    }
}