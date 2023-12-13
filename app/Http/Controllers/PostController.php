<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $quantity = request('qty');

        $posts = Post::with('user')->orderBy('created_at', 'desc')->paginate($quantity);

        return response()->json($posts, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $validated = $request->validated();

        $post = Post::create([
            'title' => $validated['title'],
            'body' => $validated['body'],
            'user_id' => $request->user_id,
        ]);

        return response()->json([
            "message" => "Post store successfull",
            "post" => $post
        ], 201);
    }

    /**
     * Display the specified resource.
     */

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {

        $validated = $request->validated();

        $post = Post::find($request->post_id);
        $post->update(['title' => $request->title, 'body' => $request->body]);

        return response()->json([
            "message" => "Post updated successfull",
            "post" => $post
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        Post::destroy($post->id);
        return response()->json([], 204);
    }
}
