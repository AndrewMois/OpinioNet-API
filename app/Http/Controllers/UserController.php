<?php

namespace App\Http\Controllers;

use App\Models\Micropost;
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

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $microposts = $user->microposts()
            ->withCount('likes')
            ->with('likes')
            ->with('votes')
            ->selectRaw("
                microposts.*,
                COUNT(CASE WHEN votes.status = 'Agree' THEN 1 END) as agree_count,
                COUNT(CASE WHEN votes.status = 'Not Sure' THEN 1 END) as not_sure_count,
                COUNT(CASE WHEN votes.status = 'Disagree' THEN 1 END) as disagree_count
            ")
            ->leftJoin('votes', 'microposts.id', '=', 'votes.micropost_id')
            ->groupBy('microposts.id')
            ->orderByDesc('microposts.created_at')
            ->get();

        return response()->json(['data' => $microposts], 200);
    }
}
