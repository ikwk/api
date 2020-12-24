<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Namshi\JOSE\JWT;
use PhpParser\Node\Stmt\TryCatch;
use Tymon\JWTAuth\Facades\JWTAuth;
use Intervention\Image\ImageManagerStatic as Image;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $creds = $request->only(['nim', 'password']);

        if (!$token = auth()->attempt($creds)) {
            return response()->json([
                'success' => false,
                'message' => 'invalid credintials'
            ]);
        }
        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => Auth::user()
        ]);
    }

    public function register(Request $request)
    {
        $encryptedPass = Hash::make($request->password);

        $user = new User;

        try {
            $user->nim = $request->nim;
            $user->email = $request->email;
            $user->password = $encryptedPass;
            $user->save();
            return $this->login($request);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e
            ]);
        }
    }

    public function logout(Request $request)
    {
        try {
            JWTAuth::invalidate(JWTAuth::parseToken($request->token));
            return response()->json([
                'success' => true,
                'message' => 'logout berhasil'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '' . $e
            ]);
        }
    }
    //untuk nyimpen username, lastname dan foto
    public function saveUserInfo(Request $request)
    {
        $user = User::findOrFail(Auth::user()->id);

        $user->name = $request->name;
        $user->lastname = $request->lastname;
        $photo = '';

        if ($request->photo && $request->photo != '') {
            $photo = time() . '.jpg';
            Image::make(file_get_contents($request->photo))->save(storage_path()."/app/public/profiles/".$photo);

            $user->photo =  $photo;
        }

        $user->update();

        return response()->json([
            'success' => true,
            'photo' => $photo
        ]);
    }
}
