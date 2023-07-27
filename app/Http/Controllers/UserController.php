<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        return response()->json($users, 200);
    }

    public function show($id)
    {
        $user = User::find($id);

        return response()->json($user, 200);
    }

    public function userShowMicroposts($id)
    {
        $user = User::find($id);
        $microposts = $user->microposts;

        return response()->json($microposts, 200);
    }
}
