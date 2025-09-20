<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller

{

    public function __construct(){
        $this->middleware('auth:api',['except'=>['register','login','logout','profile']]);
    }
    public function register(Request $request)  {
        $validator=Validator::make($request->all(),[
            'name'=>'required',
            'email'=>'required|string|email|unique:users',
            'password'=>'required|string|confirmed|min:6'
        ]);
        if($validator->failed()){
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validator->errors()
            ], 400);
        }
        $user=User::create(array_merge(
            $validator->validated(),
            ['password'=>bcrypt($request->password)]
        ));



        return response()->json(
           [ 'message'=>'Logged in successfully',
             'user'=>$user  
            ],201);
        
    }
    public function login(Request $request)  {
      
        $validator=Validator::make($request->all(),[
            'email'=>'required|email',
            'password'=>'required|string|min:6']);
            
        if($validator->failed()){
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
            }
        $user=User::where('email',$request->email)->first();
        if($user) {
                 
            if(Hash::check($request->password, $user->password)){
                $token=auth()->attempt($validator->validated());
                return $this->createNewToken($token);}
            else{
                return response()->json([
                    'status' => false,
                    'message' =>  'Password is not Correct',
                     
                    ], 422);
                }
        }
        else{
            return response()->json([
                'status' => false,
                'message' =>  'Email is not correct',
                 
                ], 422);
        }    
        
    }
    protected function createNewToken($token) {
        auth('api')->factory()->setTTL(180);
        return response()->json([
            'access_token'=>$token,
            'user'=>auth()->user(),
             
        ]);

        
    }
    public function logout() {
        auth()->logout();
        return response()->json(
            [ 'message'=>'Logout Successfully'
              
             ]);

        
    }
    public function profile(){
        return response()->json(auth()->user());
    }
    public function refresh(){

        return $this->createNewToken(auth()->refresh());
        
    }
    public function reset_password(Request $request){
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string',
            'new_password' => 'required|string|confirmed|min:6',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                 
                'errors' => $validator->errors(),
            ], 422);
        }
    
        $user = auth()->user();
    
        if (Hash::check($request->old_password, $user->password)) {
            $user->update([
                'password' => bcrypt($request->new_password),
            ]);
    
            return response()->json([
                'status' => true,
                'message' => 'Password changed successfully',
            ],200);
        } else {
            return response()->json([
             
                'errors' => ['old_password'=> 'Old Password is not correct'],
            ], 422);
        }
    }
    
}