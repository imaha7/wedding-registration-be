<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $post = null;
        $response = null;
        $guestCount = Post::pluck('invited_guests_count')->sum();
        $userCount = Post::count();

        if ($request->name) {
            $post = Post::where('name', 'LIKE', "%{$request->name}%")->get();
        } else {
            $post = Post::all();
        }

        if ($post) {
            $response = response()->json(['status' => 200, 'message' => 'Users Found', 'data' => PostResource::collection($post), 'total' => ['guestCount' => $guestCount, 'userCount' => $userCount, 'userGuestCount' => $guestCount + $userCount]], 200);
        } else {
            $response = response()->json(['status' => 404, 'message' => 'Users Not Found'], 404);
        }
        return $response;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function check(Request $request)
    {
        $userExist = Post::where('username', '=', $request->username)->exists();
        $response = null;
        if ($userExist) {
            $response = response()->json(['status' => 200, 'message' => 'User Found', 'data' => Post::where('username', $request->username)->get()], 200);
        } else {
            $response = response()->json(['status' => 404, 'message' => 'User Not Found'], 404);
        }
        return $response;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostRequest $request)
    {
        $post = Post::create($request->all());
        $response = null;
        if ($request) {
            $response = response()->json(['status' => 200, 'message' => 'User created successfully', 'data' => new PostResource($post)], 200);
        } else {
            $response = response()->json(['status' => 500, 'message' => 'Failed to create user'], 500);
        }
        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        $response = null;
        if ($post) {
            $response = response()->json(['status' => 200, 'message' => 'User Found', 'data' => new PostResource($post)], 200);
        } else {
            $response = response()->json(['status' => 404, 'message' => 'User Not Found'], 404);
        }
        return $response;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(StorePostRequest $request, Post $post)
    {
        $post->update($request->all());
        $response = null;
        if ($request) {
            $response = response()->json(['status' => 200, 'message' => 'User updated successfully', 'data' => new PostResource($post)], 200);
        } else {
            $response = response()->json(['status' => 500, 'message' => 'Failed to update user'], 500);
        }
        return $response;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(StorePostRequest $request, $id)
    {
        $post = Post::find($id)->update(['status' => $request->status]);
        $response = null;
        if ($request) {
            $response = response()->json(['status' => 200, 'message' => 'Status updated successfully', 'data' => new PostResource($post)], 200);
        } else {
            $response = response()->json(['status' => 500, 'message' => 'Failed to update status user'], 500);
        }
        return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();
        $response = null;
        if ($post) {
            $response = response()->json(['status' => 200, 'message' => 'User deleted successfully'], 200);
        } else {
            $response = response()->json(['status' => 500, 'message' => 'Failed to delete user'], 500);
        }
        return $response;
    }
}
