<?php

namespace App\Http\Controllers;

use App\Models\Micropost;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Http\Request;

class MicropostController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = 10; // Set the number of records per page
        $page = $request->query('page', 1); // Get the page number from the request query parameters, or default to 1

        // Get all microposts with username. Use pagination to limit the number of records and allow Infinite Scroll
        $microposts = Micropost::join('users', 'microposts.user_id', '=', 'users.id')
            ->select('microposts.*', 'users.name as user_name')
            ->withCount('likes')
            ->with('likes')
            ->leftJoin('votes', 'microposts.id', '=', 'votes.micropost_id')
            ->selectRaw("
            microposts.*,
            COUNT(CASE WHEN votes.status = 'Agree' THEN 1 END) as agree_count,
            COUNT(CASE WHEN votes.status = 'Not Sure' THEN 1 END) as not_sure_count,
            COUNT(CASE WHEN votes.status = 'Disagree' THEN 1 END) as disagree_count
        ")
            ->groupBy('microposts.id', 'users.name')
            ->orderByDesc('microposts.created_at')
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json($microposts, 200);
    }

    // public function index(Request $request)
    // {
    //     $perPage = 10; // Set the number of records per page
    //     $page = $request->query('page', 1); // Get the page number from the request query parameters, or default to 1

    //     // Get all microposts with username. Use pagination to limit the number of records and allow Infinite Scroll
    //     $microposts = Micropost::join('users', 'microposts.user_id', '=', 'users.id')
    //         ->select('microposts.*', 'users.name as user_name')
    //         ->withCount('likes')
    //         ->with('likes')
    //         ->orderByDesc('microposts.created_at')
    //         ->paginate($perPage, ['*'], 'page', $page);

    //     // Collect vote counts for each micropost and add them to the micropost data
    //     $microposts->each(function ($micropost) {
    //         $votes = Vote::where('votes.micropost_id', $micropost->id)
    //             ->pluck('status'); // Return selected column as array (Laravel collection)

    //         $voteCounts = $votes->countBy(); // countBy() is a collection method to count the number of occurrences of each value in a collection

    //         // Add vote counts to the micropost data
    //         $micropost->agree_count = $voteCounts->get('Agree', 0);
    //         $micropost->not_sure_count = $voteCounts->get('Not Sure', 0);
    //         $micropost->disagree_count = $voteCounts->get('Disagree', 0);
    //     });

    //     return response()->json($microposts, 200);
    // }



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


        $micropost->save();
        return response()->json($micropost, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $microposts = Micropost::find($id)->orderByDesc('microposts.created_at'); // To get the latest posts first;

        if (!$microposts) {

            return response()->json(['error' => 'Micropost not found'], 404);
        }

        return response()->json($microposts);
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
