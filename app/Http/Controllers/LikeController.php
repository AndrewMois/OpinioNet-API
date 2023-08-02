<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use App\Models\Micropost;
use App\Models\User;
use PHPUnit\Framework\Constraint\Count;

class LikeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    //Return Number of likes to a specific micropost
    public function index(string $id)
    {
        // $likes = Like::all();

        $likes = Like::join('users', 'likes.user_id', '=', 'users.id')
            // ->join('microposts', 'likes.micropost_id', '=', 'microposts.id')
            ->where('likes.micropost_id', $id)
            ->select('likes.*', 'users.name as user_name')
            ->orderByDesc('likes.created_at')
            ->get();

        $likesCount = $likes->count();

        return response()->json([
            'likes' => $likes,
            'likes_count' => $likesCount,
        ], 200);

        //So far, only number of likes to be returned

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $micropost_id)
    // public function store(Request $request, $id)
    {
        $user_id = $request->input('user_id');

        // Check if the like with the same combination of user_id and micropost_id exists
        $existingLike = Like::where('user_id', $user_id)->where('micropost_id', $micropost_id)->first();

        if ($existingLike) {
            // The like already exists
            return response()->json(['message' => 'The like already exists.'], 409); // 409 Conflict status code for duplicate entry
        }

        $like = new Like;
        $like->micropost_id = $micropost_id;
        $like->user_id = $user_id;
        $like->save();

        // Rename the keys "user_id" to "id" and "id" to "post_id" in the response JSON
        // This is needed on front to check if post liked correctly
        $likeResponse = [
            'id' => $like->user_id,
            'post_id' => $like->micropost_id,
            'like_id' => $like->id,
        ];

        return response()->json($likeResponse, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $micropost_id)
    {
        $user_id = $request->query('user_id');
        // Find the like that matches the provided micropost_id and user_id
        $like = Like::where('micropost_id', $micropost_id)
            ->where('user_id', $user_id)
            ->first();

        // If the like is found, delete it
        if ($like) {
            $like->delete();
            return response()->json(['message' => 'Like deleted successfully.'], 200);
        } else {
            return response()->json(['message' => 'Like not found.'], 404);
        }
    }
}
