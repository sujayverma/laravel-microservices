<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    //
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            $user = auth()->user();
            $token = $user->createToken('API Token')->accessToken;

            $cookie = cookie(
                'jwt',            // name
                $token,                  // value
                60 * 24 * 1,             // duration (in minutes) → 7 days
                '/',                     // path
                null,                    // domain (default current)
                false,                    // secure (only HTTPS)
                true,                    // httpOnly (inaccessible to JS)
                false,                   // raw
                'Strict'                 // SameSite policy
            );
           
            return response()->json([
                'message' => 'Login successful',
                'token' => $token,
                // 'user' => $user
            ], Response::HTTP_OK)->cookie($cookie);
        } else {
            return response()->json(['message' => 'Invalid credentials'], Response::HTTP_UNAUTHORIZED);
        }
    }

    public function logout(Request $request)
    {
        $request->user()?->token()?->revoke();
        $cookie = cookie()->forget('jwt');
        return response()->json(['message' => 'Logout successful'], Response::HTTP_OK)->cookie($cookie);
    }

    public function register(RegisterRequest $request)
    {
        // Registration logic here
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        return response()->json($user, Response::HTTP_CREATED);
    }
}
