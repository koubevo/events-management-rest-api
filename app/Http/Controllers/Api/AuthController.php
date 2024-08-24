<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Dotenv\Exception\ValidationException;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\MessageBag;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            $errors = new MessageBag([
                'email' => ['The provided credentials are incorrect.'],
            ]);

            $exception = new ValidationException($errors);

            throw $exception;

        }

        if (!Hash::check($request->password, $user->password)) {
            $errors = new MessageBag([
                'email' => ['The provided credentials are incorrect.'],
            ]);

            $exception = new ValidationException($errors);

            throw $exception;

        }

        $token = $user->createToken('api-token')->plainTextToken;
        return response()->json([
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            'message' => 'logged out!!!!!'
        ]);
    }
}
