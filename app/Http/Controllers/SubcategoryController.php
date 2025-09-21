<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subcategory;
use App\Http\Resources\SubcategoryResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SubcategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
                'category_id'=>'required|integer|exists:categories,id'
            ]);
            if ($validated->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Validation Error',
                    'errors'  => $validated->errors()
                ], 422);
            }


            $subcategory = Subcategory::create($validated->validated());

            return new SubcategoryResource($subcategory);
        
        }
   

     /**
     * Display the specified resource.
     */
    public function show(Subcategory $subcategory)
        {
            return new SubcategoryResource($subcategory);
        }

     /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
        {
            //
        }

     /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subcategory $subcategory)
        {
        
            $validated =Validator::make($request->all(), 
                [ 
                    'name' => 'sometimes|string|max:255',
                    'description' => 'nullable|string',
                    'seo_name' => 'nullable|string|max:255',
                    'seo_description' => 'nullable|string',
                    'category_id'=>'sometimes|integer|exists:categories,id'
                ]);
                if ($validated->fails()) {
                    return response()->json([
                        'status'  => false,
                        'message' => 'Validation Error',
                        'errors'  => $validated->errors()
                    ], 422);
                }


                $subcategory->update($validated->validated());

                return new SubcategoryResource($subcategory);
            
        }

     /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subcategory $subcategory)
        {
             
            $subcategory->delete();

            return response()->json(null, 204);
        }
}