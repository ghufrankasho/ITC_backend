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

        $sliders=Setting::where([['hide',0],['group','slider']])->latest()->get();
        $categories=Category::where('hide',0)->with('subcategories')->latest()->get();
        $products=Product::where('hide',0) ->latest()
    ->take(6)
    ->get();;
        $news=News::where('hide',0)->latest()->get();
        $settings=Setting::where([['hide',0],['group','footer']])->latest()->get();
        return [
            'products'=> $products,
            'sliders'=>$sliders,
            'categories'=>$categories,
            'news'=>$news,
            'settings'=>$settings,
        ];
    }
}