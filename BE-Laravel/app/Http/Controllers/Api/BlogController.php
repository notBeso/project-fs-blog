<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Blog;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
	$newFileName = null;
	$thumbs = $request->thumbs;

	\Log::info(strlen($thumbs));
	\Log::info(is_null($thumbs) ? "NULL" : "NOT NULL");

	if(!is_null($thumbs)) {
	    \Log::info($thumbs);
            $newFileName = Str::uuid()->toString() . '.' . $thumbs->extension();
	    Storage::disk('public')->put($newFileName, $thumbs->get());
	}

        $blog->title = $request->title;
        $blog->des = $request->des;
        $blog->detail = $request->detail;
        $blog->category = $request->category;
        $blog->public = $request->public;
        $blog->data_public = $request->data_public;
        $blog->position = $request->position;

	if($newFileName) {
	    $blog->thumbs = $newFileName;
	}

        $blog->save();

        return $blog;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Blog::where('id', $id)->first();
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
        // $blog->position = $request->position;
        // $blog->thumbs = $request->thumbs;

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
        // return $blog;
    }

    public function search(Request $request)
    {
        $searchTerm = $request->input('q');

        $results = Blog::where('title', 'LIKE', "%{$searchTerm}%")
                           ->get();

        return response()->json($results);
    }

    public function locations() {
        return [
    		[ "id" => '1', "label" =>'Việt Nam' ],
        	[ "id" => '2', "label" => 'Châu Á' ],
        	[ "id" => '3', "label" => 'Châu Âu' ],
        	[ "id" => '4', "label" =>'Châu Mỹ' ],
	];
    }

    public function options() {
    	return [
		[ "value" => '1', "label" => 'Kinh Doanh' ],
		[ "value" => '2', "label" => 'Giải Trí' ],
		[ "value" => '3', "label" => 'Thế Giới' ],
		[ "value" => '4', "label" => 'Thời Sự' ],
	];
    }
}
