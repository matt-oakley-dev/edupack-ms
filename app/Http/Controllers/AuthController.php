<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class AuthController extends Controller
{
    /**
     * Register a user
     *
     * @param Request $request
     * @version 1.0.0
     */
    public function register(Request $request) {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    /**
     * Login a user
     *
     * @param Request $request
     * @version 1.0.0
     */
    public function login(Request $request) {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        // Check email
        $user = User::where('email', $fields['email'])->first();

        // Check password
        if ( ! $user || ! Hash::check($fields['password'], $user->password) ) {
            return response( [
                'message' => 'Bad Credentials' 
            ], 401);
        }

        // Delete any previous tokens and create a new one.
        auth()->user()->tokens()->delete();
        $token = $user->createToken('edupacktoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    /**
     * Logout a user (Deletes all tokens)
     *
     * @param Request $request
     * @version 1.0.0
     */
    public function logout(Request $request) {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logged OUt'
        ];
    }

    /**
     * Deletes all users login tokens and creates a fresh one
     * 
     * @param Request $request
     * @version 1.0.0
     */
    public function refresh(Request $request) {
        $request->user()->tokens()->delete();

        return response()->json([
            'access_token' => $request->user()->createToken('api')->plainTextToken,
        ]);
    }
}
