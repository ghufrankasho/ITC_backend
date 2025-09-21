<?php
 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Middleware\JwtMiddleware;
class AuthController extends Controller
{

    
    // Register
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|unique:users,email',
            'password' => 'required|string|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $token = JWTAuth::fromUser($user); // Create token from new user

        return $this->createNewToken($token, $user);
    }

    // Login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $validator = Validator::make($credentials, [
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors()
            ], 422);
        }

        $token = JWTAuth::attempt($validator->validated());

        if (!$token) {
            return response()->json([
                'status'  => false,
                'message' => 'Invalid email or password'
            ], 401);
        }

        $user = JWTAuth::user();
        return $this->createNewToken($token, $user);
    }

    // Logout
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json([
            'status'  => true,
            'message' => 'Logged out successfully'
        ]);
    }

    // Get current user
    public function profile()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            return response()->json($user);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['status' => false, 'message' => 'Token error: '.$e->getMessage()], 401);
        }
    }

    // Refresh token
    public function refresh()
    {
        try {
            
             $user = JWTAuth::user();
            $token = JWTAuth::refresh(JWTAuth::getToken());
            
            
            
            
            return $this->createNewToken($token, $user);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['status' => false, 'message' => 'Token error: '.$e->getMessage()], 401);
        }
    }

    // Reset password
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string',
            'new_password' => 'required|string|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = JWTAuth::parseToken()->authenticate();

        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'status' => false,
                'errors' => ['old_password' => 'Old password is incorrect']
            ], 422);
        }

        $user->password = bcrypt($request->new_password);
        $user->save();

        return response()->json([
            'status'  => true,
            'message' => 'Password updated successfully'
        ]);
    }

    // Helper to return token + user info
    protected function createNewToken($token, $user)
    {
        JWTAuth::factory()->setTTL(180); // 3 hours

        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => JWTAuth::factory()->getTTL() * 60,
            'user'         => $user,
        ]);
    }
}