<?php

namespace App\Http\Controllers;

use App\Models\Micropost;
use App\Models\User;
use Illuminate\Http\Request;

class MicropostController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = 10; // Set the number of records per page

        // Get the page number from the request query parameters, or default to 1
        $page = $request->query('page', 1);

        // Get all microposts with username. Use pagination to limit the number of records and allow Infinite Scroll
        $microposts = Micropost::join('users', 'microposts.user_id', '=', 'users.id')
            ->select('microposts.*', 'users.name as user_name')
            ->orderByDesc('microposts.created_at') // To get the latest posts first
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json($microposts, 200);
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
    {
        $micropost = new Micropost();
        $micropost->title = $request->input('title');
        $micropost->content = $request->input('content');
        $micropost->user_id = $request->input('user_id');
        $micropost->likes = 0;

        $micropost->save();
        return response()->json($micropost, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $micropost = Micropost::find($id);
        if (!$micropost) {

            return response()->json(['error' => 'Micropost not found'], 404);
        }

        return response()->json($micropost);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Micropost $micropost)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //Just for testing for updating a micropost data.
        //I dont know why put action by multipart form in Insomnia does not include the value corresponding to the key.
        $micropost = Micropost::find($id);
        $micropost->title = $request->input('title');
        $micropost->content = $request->input('content');
        $micropost->save();
        return response()->json($micropost, 200);
    }

    //only add likes functionality. This likes column is placed inside of Microposts table. So, there is no relation with User table, which would need revisions like making another table for enhancing the like functionality.
    //
    public function addLikes(Request $request, $id)
    {
        $micropost = Micropost::find($id);
        $micropost->increment('likes');
        $micropost->save();
        //If returning all json info for a micropost
        // return response()->json($micropost, 200);
        //return response()->json($micropost->likes, 200); Only returning value
        return response()->json(['likes' => $micropost->likes], 200);
    }

    public function removeLikes(Request $request, $id)
    {
        $micropost = Micropost::find($id);

        if (!$micropost) {
            return response()->json(['message' => 'Micropost not found'], 404);
        }

        // Check if the micropost has any likes before decrementing
        if ($micropost->likes > 0) {
            $micropost->decrement('likes');
            $micropost->save();
        }

        return response()->json(['likes' => $micropost->likes], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Micropost $micropost)
    {
        // Detach all likes associated with the micropost. This works because of the relationship defined in Micropost.php
        $micropost->likes()->detach();

        $micropost->delete();

        return response()->json(['message' => 'Post deleted successfully'], 200);
    }
}
