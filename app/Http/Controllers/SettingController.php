<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
 
use App\Http\Resources\SettingResource;
use App\Models\Vistor;
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
                $Setting->image = $this->storeImage($request->file('image'),'images/sliders'); 
                $Setting->save();
            }
           
          
           
            return new SettingResource($Setting);
    }

    public function show(string $key)
    {
        try {
            // Detect if the key is numeric (id) or a string (group)
            if (is_numeric($key)) {
                // Validate ID
                $validated = Validator::make(['id' => $key], [
                    'id' => 'required|integer|exists:settings,id',
                ]);

                if ($validated->fails()) {
                    return response()->json([
                        'status'  => false,
                        'message' => 'Validation Error',
                        'errors'  => $validated->errors()
                    ], 422);
                }

                // Fetch by ID
                $setting = Setting::find($key);

                return new SettingResource($setting);
            } else {
                // Validate group name
                $validated = Validator::make(['group' => $key], [
                    'group' => 'required|string|exists:settings,group',
                ]);

                if ($validated->fails()) {
                    return response()->json([
                        'status'  => false,
                        'message' => 'Validation Error',
                        'errors'  => $validated->errors()
                    ], 422);
                }

                // Fetch all by group name
                $settings = Setting::where('group', $key)->get();

                return SettingResource::collection($settings);
            }
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while obtaining this data.',
                'error'   => $e->getMessage(), // Optional for debugging
            ], 500);
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
                $this->deleteImage($Setting->image);
                $Setting->image = $this->storeImage($request->file('image'),'images/sliders'); 
                $Setting->save();
            }
            
           
            return new SettingResource($Setting);
    }

    public function destroy (Setting $setting)
    {
        if($setting->image!==null){
            $this->deleteImage($setting->image);
        }
        
        $setting->delete();

        return response()->json(null, 204);
    }
    public function visitors(){
        
         try {
            
        $users=Vistor::get();
        $users_count=count($users);
        $visits_count=0;
        foreach($users as $user){
           $visits_count +=$user->visitor_count;
        }
        
        return response()->json(['data'=>[
            'users_count'=>$users_count,
            'visits_count'=>$visits_count
            
        ] ],200);
   
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while obtaining this data.',
                'error'   => $e->getMessage(), // Optional for debugging
            ], 500);
        }
        
        
    }
}