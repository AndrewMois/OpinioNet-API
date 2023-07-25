<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): JsonResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // return response()->noContent();
        $token = $request->user()->createToken('api-token')->plainTextToken;
        $response = response()->json([
            'token' => $token,
            'user_id' => $request->user()->id,
        ]);

        // Set the cookies with the desired expiration time (in minutes)
        $response->withCookie('token', $token, 1440); // Expiration is 24 hours
        $response->withCookie('user_id', $request->user()->id, 1440); // Expiration is 24 hours

        return $response;
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): Response
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->noContent();
    }
}
