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
    public function store(Request $request)
    // public function store(Request $request, $id)  
    {
        $micropost_id = $request->input('micropost_id'); //How can I get these ids?
        $user_id = $request->input('user_id');

        // Check if the like with the same combination of user_id and micropost_id exists
        $existingLike = Like::where('user_id', $user_id)->where('micropost_id', $micropost_id)->first();

        if ($existingLike) {
            // The like already exists
            return response()->json(['message' => 'The like already exists.'], 409); // 409 Conflict status code for duplicate entry
        }

        $like = new Like;
        // $like->micropost_id = $id;
        $like->micropost_id = $micropost_id;
        $like->user_id = $user_id;
        $like->save();
        return response()->json($like, 200);
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
    public function destroy(Request $request, string $id)
    {
        // Get the authenticated user's ID
        //$user_id = $request->user()->id;
        // $user_id = $request->input('user_id'); this does not work. to delete, usually seems to need to pass data by URL...
        $user_id = 3;
        //This is Temporary!!!!!!!!!!!!!!!!!!!!!!Need to change. 

        // Find the like that matches the provided micropost_id and user_id
        $like = Like::where('likes.micropost_id', $id)
            ->where('likes.user_id', $user_id)
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
