<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            return CategoryResource::collection(Category::all());
        }
        catch (ValidationException $e) {
              return response()->json(['errors' => $e->errors()], 422);
          } catch (\Exception $e) {
              return response()->json(['message' => 'An error occurred while obtaining this categroy.'], 500);
          } 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(Request $request)
        {
        
            $validated =Validator::make($request->all(), 
            [ 
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'hide'=>'bool',
                'seo_name' => 'nullable|string|max:255',
                'seo_description' => 'nullable|string',
            ]);
            if ($validated->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Validation Error',
                    'errors'  => $validated->errors()
                ], 422);
            }


            $category = Category::create($validated->validated());

            return new CategoryResource($category);
        
        }
    public function show(Category $category)
        {
            return new CategoryResource($category);
        }

    public function destroy(Category $category)
        {
            $category->delete();

            return response()->json(null, 204);
        }
    public function update(Request $request, Category $category)
        {
        
            $validated =Validator::make($request->all(), 
                [  
                    'name' => 'sometimes|string|max:255',
                    'hide'=>'bool',
                    'description' => 'nullable|string',
                    'seo_name' => 'nullable|string|max:255',
                    'seo_description' => 'nullable|string',
                ]);
                if ($validated->fails()) {
                    return response()->json([
                        'status'  => false,
                        'message' => 'Validation Error',
                        'errors'  => $validated->errors()
                    ], 422);
                }
            $category->update($validated->validated());

            return new CategoryResource($category);
        }
}