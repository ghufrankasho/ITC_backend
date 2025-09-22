<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Subcategory;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      try{
            return ProductResource::collection(Product::all());
        }
        catch (ValidationException $e) {
              return response()->json(['errors' => $e->errors()], 422);
          } catch (\Exception $e) {
              return response()->json(['message' => 'An error occurred while obtaining this categroy.'], 500);
          } 
    }
 

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
            $validated =Validator::make($request->all(), 
            [ 
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'seo_name' => 'nullable|string|max:255',
                'seo_description' => 'nullable|string',
                'image' => 'sometimes|file|required|mimetypes:image/jpeg,image/png,image/gif,image/svg+xml,image/webp,application/wbmp',
                'file' => 'file|required|mimes:pdf,doc,docx',
                'hide'=>'bool',
                'subcategory_id'=>'sometimes|required|integer|exists:subcategories,id'
            ]);
            $validated->sometimes('image', 'required|mimetypes:image/vnd.wap.wbmp', function ($input) {
                return $input->file('image') !== null && $input->file('image')->getClientOriginalExtension() === 'wbmp';
            });
            if ($validated->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Validation Error',
                    'errors'  => $validated->errors()
                ], 422);
            }

     
            $product = Product::create($validated->validated());  
            if($request->hasFile('image') and $request->file('image')->isValid()){
                $product->image = $this->storeImage($request->file('image'),'images'); 
            }
            if($request->hasFile('file') and $request->file('file')->isValid()){
                $product->file = $this->storeImage($request->file('file'),'files'); 
            }
          
            $product->save();
            return new ProductResource($product);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        try{
            return new ProductResource($product);
        }
        catch (ValidationException $e) {
              return response()->json(['errors' => $e->errors()], 422);
          } catch (\Exception $e) {
              return response()->json(['message' => 'An error occurred while obtaining this categroy.'], 500);
          } 
    }

    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated =Validator::make($request->all(), 
            [ 
                'name' => 'sometimes|string|max:255',
                'description' => 'nullable|string',
                'seo_name' => 'nullable|string|max:255',
                'seo_description' => 'nullable|string',
                'image' => 'sometimes|file|required|mimetypes:image/jpeg,image/png,image/gif,image/svg+xml,image/webp,application/wbmp',
                'file' => 'sometimes|file|required|mimes:pdf,doc,docx',
                'hide'=>'bool',
                'subcategory_id'=>'sometimes|required|integer|exists:subcategories,id'
            ]);
            $validated->sometimes('image', 'required|mimetypes:image/vnd.wap.wbmp', function ($input) {
                return $input->file('image') !== null && $input->file('image')->getClientOriginalExtension() === 'wbmp';
            });
            if ($validated->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Validation Error',
                    'errors'  => $validated->errors()
                ], 422);
            }

     
            $product->update($validated->validated());  
            if($request->hasFile('image') and $request->file('image')->isValid()){
                $product->image = $this->storeImage($request->file('image'),'images'); 
            }
            if($request->hasFile('file') and $request->file('file')->isValid()){
                $product->file = $this->storeImage($request->file('file'),'files'); 
            }
          
            $product->save();
            return new ProductResource($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
          $product->delete();

            return response()->json(null, 204);
    }
}