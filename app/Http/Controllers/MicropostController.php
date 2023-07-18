<?php

namespace App\Http\Controllers;

use App\Models\Micropost;
use Illuminate\Http\Request;

class MicropostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $microposts = Micropost::all();
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
