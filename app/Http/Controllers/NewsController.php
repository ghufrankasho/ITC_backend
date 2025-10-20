<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
 
use App\Http\Resources\NewsResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
class NewsController extends Controller
{
    public function index()
    {
      try{
            return NewsResource::collection(News::all());
        }
        catch (ValidationException $e) {
              return response()->json(['errors' => $e->errors()], 422);
          } catch (\Exception $e) {
              return response()->json(['message' => 'An error occurred while obtaining this data.'], 500);
          } 
    }
 
    public function store(Request $request)
    {
            $validated =Validator::make($request->all(), 
            [ 
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                
                'seo_title' => 'nullable|string|max:255',
                'image' => 'sometimes|file|required|mimetypes:image/jpeg,image/png,image/gif,image/svg+xml,image/webp,application/wbmp',
                'hide'=>'bool',
                
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

     
            $News = News::create($validated->validated());  
            if($request->hasFile('image') and $request->file('image')->isValid()){
                $News->image = $this->storeImage($request->file('image'),'images/news'); 
            }
           
          
            $News->save();
            return new NewsResource($News);
    }

    public function show(News $news)
    {
        try{
            return new NewsResource($news);
        }
        catch (ValidationException $e) {
              return response()->json(['errors' => $e->errors()], 422);
          } catch (\Exception $e) {
              return response()->json(['message' => 'An error occurred while obtaining this data.'], 500);
          } 
    }

    public function update(Request $request, News $News)
    {
        $validated =Validator::make($request->all(), 
            [ 
                'title' => 'sometimes|string|max:255',
                'description' => 'nullable|string',
                'seo_title' => 'nullable|string|max:255',
                'seo_description' => 'nullable|string',
                'image' => 'sometimes|file|required|mimetypes:image/jpeg,image/png,image/gif,image/svg+xml,image/webp,application/wbmp',
                'hide'=>'bool',
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

     
            $News->update($validated->validated());  
            if($request->hasFile('image') and $request->file('image')->isValid()){
                $News->image = $this->storeImage($request->file('image'),'images/news'); 
            }
            
            $News->save();
            return new NewsResource($News);
    }

    public function destroy(News $News)
    {
        $this->deleteImage($News->image);
        $News->delete();

        return response()->json(null, 204);
    }
}