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

        // Get all microposts with username
        $microposts = Micropost::join('users', 'microposts.user_id', '=', 'users.id')
            ->select('microposts.*', 'users.name as user_name')
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
    public function show(Micropost $micropost)
    {
        //
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
    public function update(Request $request, Micropost $micropost)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Micropost $micropost)
    {
        //
    }
}
