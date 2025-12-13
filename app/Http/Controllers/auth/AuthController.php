<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $data = $request->json()->all();

            $validator = Validator::make($data, [
                'mobile_no' => 'required|size:10',
                'password' => 'required|min:6|max:100',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 400);
            }

            $user = DB::table('users')
                ->where('mobile', $data['mobile_no'])
                ->where('status', 'active')
                ->first();

            if (empty($user)) {
                return response()->json([
                    'status' => 400,
                    'message' => 'User not found',
                    'user' => []
                ], 400);
            }

            if ($data['password'] != $user->password) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Invalid password',
                    'user' => []
                ], 400);
            }

            DB::table('users')
                ->where('id', $user->id)
                ->update(['last_login' => now()]);

            return response()->json([
                'status' => 200,
                'message' => 'Login successful',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'mobile' => $user->mobile,
                    'role_name' => $user->role,
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Internal Server Error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
