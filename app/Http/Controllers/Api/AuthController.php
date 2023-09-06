<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;


use function Laravel\Prompts\error;

class AuthController extends Controller
{
    public function signup(SignupRequest $request)
    {
        $data = $request->validated();
        // Check if image was given and save on local file system
        if (isset($data['profile_image'])) {
            $relativePath = $this->saveImage($data['profile_image']);
            $data['profile_image'] = $relativePath;
        }
        /** @var \App\Models\User $user */
        $user = User::create($data);
        $token = $user->createToken('ACCESS_TOKEN')->plainTextToken;
        return response([
            'message' => 'User add sucufuly',
            'user' => $user,
            'token' => $token
        ]);
    }


    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        if(!Auth::attempt($credentials)){
            return response([
                'message'=> 'Provide email and password not incorrect'
            ],422);
        }

        /** @var \App\Models\User */
        $user = Auth::user();
        $token = $user->createToken('ACCESS_TOKEN')->plainTextToken;

        return response([
            'message' => 'Your are login sucufuly',
            'user' => $user,
            'token' => $token
        ]);

    }
    public function logout(Request $request)
    {   /** @var \App\Models\User $user */
        $user = $request->user();
        $user->currentAccessToken()->delete();
        return response([
            'message'=> 'Your are logout',
        ],204);

    }

    private function saveImage($image)
    {
        // Check if image is valid base64 string
        if (preg_match('/^data:image\/(\w+);base64,/', $image, $type)) {
            // Take out the base64 encoded text without mime type
            $image = substr($image, strpos($image, ',') + 1);
            // Get file extension
            $type = strtolower($type[1]); // jpg, png, gif

            // Check if file is an image
            if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png'])) {
                throw new \Exception('invalid image type');
            }
            $image = str_replace(' ', '+', $image);
            $image = base64_decode($image);

            if ($image === false) {
                throw new \Exception('base64_decode failed');
            }
        } else {
            throw new \Exception('did not match data URI with image data');
        }

        $dir = 'images/profiles/';
        $file = time() . '-profile' . '.' . $type;
        $absolutePath = public_path($dir);
        $relativePath = $dir . $file;
        if (!File::exists($absolutePath)) {
            File::makeDirectory($absolutePath, 0755, true);
        }
        file_put_contents($relativePath, $image);

        return $relativePath;
    }
}
