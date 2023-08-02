<?php

namespace App\Http\Controllers;


use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VoteController extends Controller
{
    public function index(string $id)
    {
        $votes = Vote::where('votes.micropost_id', $id)
            ->pluck('status'); //return selected column as array(laravel collection)

        $voteCounts = $votes->countBy(); //countBy() is a collection method to count the number of occurrences of each value in a collection

        $agreeCount = $voteCounts->get('Agree', 0);
        $notSureCount = $voteCounts->get('Not Sure', 0);
        $disagreeCount = $voteCounts->get('Disagree', 0);


        return response()->json([
            'agree_count' => $agreeCount,
            'not_sure_count' => $notSureCount,
            'disagree_count' => $disagreeCount,
        ], 200);
    }

    public function store(Request $request, $micropost_id)
    {
        $user_id = $request->input('user_id');
        $status = $request->input('status');

        // Check if the vote with the same combination of user_id and micropost_id exists
        $existingVote = Vote::where('user_id', $user_id)->where('micropost_id', $micropost_id)->first();

        if ($existingVote) {
            return response()->json(['message' => 'The vote already exists.'], 409); // 409 Conflict status code for duplicate entry
        }

        $vote = new Vote();
        $vote->micropost_id = $micropost_id;
        $vote->user_id = $user_id;
        $vote->status = $status;
        $vote->save();
        return response()->json($vote, 200);
    }
}
