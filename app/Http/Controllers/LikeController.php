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
    //Return Number of likes
    public function index()
    {
        // $likes = Like::all();

        $likes = Like::join('users', 'likes.user_id', '=', 'users.id')
            // ->join('microposts', 'likes.micropost_id', '=', 'microposts.id')
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
        $like = new Like;
        // $like->micropost_id = $id;
        $like->micropost_id = $request->input('micropost_id'); //How can I get these ids?
        $like->user_id = $request->input('user_id');
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
    public function destroy(string $id)
    {
        //
    }
}
