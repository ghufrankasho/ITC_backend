<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\Category;
use App\Models\News;
use App\Models\Setting;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){

        $sliders=Setting::where([['hide',0],['group','slider']])->latest()->get();
        $categories=Category::where('hide',0)->with('subcategories')->latest()->get();
        $products=Product::where('hide',0) ->latest()->take(6)->get();
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

    public function get(Request $request){
        try{

            
            $page=1;
            $limit=4;
            $type=0;
           
            if($request->filled('page')){
                 
                  $page=$request->page;
            }
            if($request->filled('limit')){
                $limit=$request->limit;
                  
            }
            if($request->filled('type')){
                $type=$request->type;
                  
            }
            
            if($page <=1){
                $value=0;  
                  
            }
            else{
                $value=($page-1)*$limit;
            }
               
           
            if($type==0) 
            { 
                $data=Product::where('hide',0)->offset($value)
                ->limit($limit)->orderBy('updated_at', 'desc')
                ->get();
               $number= count(Product::where('hide',0)->get());
               
            }
            else{
                $data=News::where('hide',0)->offset($value)
                ->limit($limit)->orderBy('updated_at', 'desc')
                ->get();
               $number= count(News::where('hide',0)->get());
            }
            
            return response()->json(
                           ['result'=>$data,
                           'total'=>$number]
                            , 200);
          
           }
            
            
        
        catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
          } catch (\Exception $e) {
            return response()->json(['message'=>'An error occurred while requesting data.'], 500);
          }
        
    }
   
}