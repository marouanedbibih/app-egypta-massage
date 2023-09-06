<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\File;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /** @var \App\Models\User */
        $user = User::query()->orderBy('id', 'desc')->paginate(10);
        return UserResource::collection($user);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        // Check if image was given and save on local file system
        if (isset($data['profile_image'])) {
            $relativePath = $this->saveImage($data['profile_image']);
            $data['profile_image'] = $relativePath;
        }
        /** @var \App\Models\User $user */
        $user = User::create($data);
        return response([
            'message' => 'User add sucufuly',
            'user' => new UserResource($user),
        ]);
    }

    /**
     * Display the specified resource.
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }
        // Check if image was given and save on local file system
        if (isset($data['profile_image'])) {
            $relativePath = $this->saveImage($data['profile_image']);
            $data['profile_image'] = $relativePath;

            if ($user->profile_image) {
                $absolutePath = public_path($user->profile_image);
                File::delete($absolutePath);
            }
        }
        /** @var \App\Models\User $user */
        $user->update($data);
        return response([
            'message' => 'User update sucufuly',
            'user' => new UserResource($user),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user->profile_image) {
            $absolutePath = public_path($user->profile_image);
            File::delete($absolutePath);
        }
        $user->delete();
        return response([
            "message" => "user delete succufuly"
        ], 204);
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

    public function getClientUsers()
    {
        // Assuming role_id 0 represents client users
        $clientUsers = User::where('role', 0)
            ->select('id', 'name', 'email', 'phone', 'sex','profile_image') // Add the columns you want to select here
            ->orderBy('id', 'desc')
            ->paginate(10);
    
        return UserResource::collection($clientUsers);
    }
}
