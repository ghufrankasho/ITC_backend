<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
 
use App\Http\Resources\SettingResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
class SettingController extends Controller
{
   public function index()
    {
      try{
            return SettingResource::collection(Setting::all());
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
                'key' => 'required|string|max:255|unique:settings,key',
                'value' => 'nullable|string',
                'group' => 'required|string|max:255',
                'image' => 'sometimes|file|required|mimetypes:image/jpeg,image/png,image/gif,image/svg+xml,image/webp,application/wbmp',
                'hide'=>'boolean',
                
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

     
            $Setting = Setting::create($validated->validated());  
            if($request->hasFile('image') and $request->file('image')->isValid()){
                $Setting->value = $this->storeImage($request->file('image'),'images/sliders'); 
                $Setting->save();
            }
           
          
           
            return new SettingResource($Setting);
    }

    public function show(String $group)
    {
        try{
             $request=['group'=>$group];
             $validatedkey =Validator::make($request, 
            [ 
                'group' => 'required|string|exists:settings,group',
                
            ]);
            
            if ($validatedkey->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Validation Error',
                    'errors'  => $validatedkey->errors()
                ], 422);
            }
            $Setting=Setting::where('group',$group)->get();
            return SettingResource::collection($Setting);
        }
        catch (ValidationException $e) {
              return response()->json(['errors' => $e->errors()], 422);
          } catch (\Exception $e) {
              return response()->json(['message' => 'An error occurred while obtaining this data.'], 500);
          } 
    }

    public function update(Request $request, Setting $Setting)
    {
        $validated =Validator::make($request->all(), 
            [ 
                'key' => 'sometimes|string|max:255',
                'value' => 'nullable|string',
                'group' => 'sometimes|string|max:255',
                'image' => 'sometimes|file|required|mimetypes:image/jpeg,image/png,image/gif,image/svg+xml,image/webp,application/wbmp',
                'hide'=>'boolean',
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

     
            $Setting->update($validated->validated());  
            if($request->hasFile('image') and $request->file('image')->isValid()){
                $this->deleteImage($Setting->value);
                $Setting->value = $this->storeImage($request->file('image'),'images/sliders'); 
                $Setting->save();
            }
            
           
            return new SettingResource($Setting);
    }

    public function destroy (Setting $setting)
    {
        if($setting->group==='slider' and $setting->value!==null){
            $this->deleteImage($setting->value);
        }
        
        $setting->delete();

        return response()->json(null, 204);
    }
}