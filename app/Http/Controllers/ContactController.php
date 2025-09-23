<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Http\Resources\ContactResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class ContactController extends Controller
{
       public function index()
    {
      try{
            return ContactResource::collection(Contact::all());
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
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'message' => 'nullable|string',
                'phone' => 'nullable|string',
                'email' =>  'required|string|email|unique:contacts,email',
                
                
            ]);
          
            if ($validated->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Validation Error',
                    'errors'  => $validated->errors()
                ], 422);
            }

     
            $Contact = Contact::create($validated->validated());  
            
           
          
         
            return new ContactResource($Contact);
    }

    public function show(Contact $Contact)
    {
        try{
            return new ContactResource($Contact);
        }
        catch (ValidationException $e) {
              return response()->json(['errors' => $e->errors()], 422);
          } catch (\Exception $e) {
              return response()->json(['message' => 'An error occurred while obtaining this data.'], 500);
          } 
    }

    // public function update(Request $request, Contact $Contact)
    // {
    //     $validated =Validator::make($request->all(), 
    //         [ 
    //             'title' => 'sometimes|string|max:255',
    //             'description' => 'nullable|string',
    //             'seo_title' => 'nullable|string|max:255',
    //             'seo_description' => 'nullable|string',
    //             'image' => 'sometimes|file|required|mimetypes:image/jpeg,image/png,image/gif,image/svg+xml,image/webp,application/wbmp',
    //             'hide'=>'bool',
    //          ]);
    //         $validated->sometimes('image', 'required|mimetypes:image/vnd.wap.wbmp', function ($input) {
    //             return $input->file('image') !== null && $input->file('image')->getClientOriginalExtension() === 'wbmp';
    //         });
    //         if ($validated->fails()) {
    //             return response()->json([
    //                 'status'  => false,
    //                 'message' => 'Validation Error',
    //                 'errors'  => $validated->errors()
    //             ], 422);
    //         }

     
    //         $Contact->update($validated->validated());  
    //         if($request->hasFile('image') and $request->file('image')->isValid()){
    //             $Contact->image = $this->storeImage($request->file('image'),'images/Contact'); 
    //         }
            
    //         $Contact->save();
    //         return new ContactResource($Contact);
    // }

    public function destroy(Contact $Contact)
    {
        
        $Contact->delete();

        return response()->json(null, 204);
    }
}