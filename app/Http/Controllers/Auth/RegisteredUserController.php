<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        //Create user and store it to database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        //Generate Registered Event
        event(new Registered($user));

        //Get user who was registered logged in automatically
        Auth::login($user);

        // return response()->noContent();
        //create user token
        $token = $user->createToken('auth_token')->plainTextToken;

        //return token as json
        $response = response()->json([
            'token' => $token,
            'user_id' => $user->id,
        ]);

        // Set the cookies with the desired expiration time (in minutes)
        $response->withCookie('token', $token, 1440); // Expiration is 24 hours
        $response->withCookie('user_id', $user->id, 1440); // Expiration is 24 hours

        return $response;

    }
}
