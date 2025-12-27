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
                    'profile_image' => $user->profile_image,
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

    public function uploadProfileImage(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
                'profile_image' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 400);
            }

            $user_id = $request->user_id;
            $base64_image = $request->profile_image;

            // Check if it's a data URL
            if (preg_match('/^data:image\/(\w+);base64,/', $base64_image, $type)) {
                $base64_image = substr($base64_image, strpos($base64_image, ',') + 1);
                $type = strtolower($type[1]); // jpg, png, gif
                if (!in_array($type, ['jpg', 'jpeg', 'png', 'gif'])) {
                    $type = 'png'; // Default
                }
            } else {
                // If the user sent image_type separately or it's raw base64
                $type = $request->input('image_type', 'png');
            }

            $type = strtolower($type);
            if ($type == 'jpeg')
                $type = 'jpg';

            $image_base64 = base64_decode($base64_image);

            if ($image_base64 === false) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Invalid base64 string',
                ], 400);
            }

            $fileName = 'user_' . $user_id . '_' . time() . '_' . uniqid() . '.' . $type;
            $path = public_path('users');

            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            file_put_contents($path . '/' . $fileName, $image_base64);

            // Update user profile_image in DB
            $dbPath = 'users/' . $fileName;
            DB::table('users')
                ->where('id', $user_id)
                ->update(['profile_image' => $dbPath]);
            // uploadResponse.data?.fileName;
            return response()->json([
                'status' => 200,
                'message' => 'Image uploaded successfully',
                'data' => [
                    'fileName' => $dbPath
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
