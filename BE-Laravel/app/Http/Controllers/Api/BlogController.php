<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Blog;

class BlogController
{


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Blog::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $blog = new Blog;

        $blog->title = $request->title;
        $blog->des = $request->des;
        $blog->detail = $request->detail;
        $blog->category = $request->category;
        $blog->public = $request->public;
        $blog->data_public = $request->data_public;
        $blog->position = $request->position;
        // $blog->thumbs = $request->thumbs;

        if ($request->hasFile('thumbs')) {
            $path = $request->file('thumbs')->store('blog/thumbs', 'public');
            $blog->thumbs = $path;
        }

        $blog->save();

        return $blog;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $blog = Blog::where('id', $id)->first();

        return $blog;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $blog = Blog::where('id', $id)->first();

        $blog->title = $request->title;
        $blog->des = $request->des;
        $blog->detail = $request->detail;
        $blog->category = $request->category;
        $blog->public = $request->public;
        $blog->data_public = $request->data_public;
        $blog->position = $request->position;
        // $blog->thumbs = $request->thumbs;
        if ($request->hasFile('thumbs')) {
            // Delete old file if exists
            if ($blog->thumbs) {
                Storage::disk('public')->delete($blog->thumbs);
            }
            // Store new file
            $path = $request->file('thumbs')->store('blog/thumbs', 'public');
            $blog->thumbs = $path;
        }

        $blog->save();

        return $blog;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $blog = Blog::find($id);
        $blog ->forceDelete();

    }

    public function search(Request $request)
    {
        $searchTerm = $request->input('q');

        $results = Blog::where('title', 'LIKE', "%{$searchTerm}%")
                           ->get();

        return response()->json($results);
    }
}
