<?php

namespace App\Http\Controllers\Read;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SingleReadController extends Controller
{
    public function getSinglePostWithEloquent($id){
        try{
            $post = Post::findOrFail($id);
            return response()->json($post, 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Post not found'], 404);
        }
    }

    public function getSinglePostWithQueryBuilder($id){
        $post = DB::where('id', $id)->first();
        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }
        return response()->json($post, 200);
    }

    public function getSinglePostWithRawSQL($id){
        $post = DB::select('SELECT * from users where id = ?', [$id]);
        if (empty($post)) {
            return response()->json(['message' => 'Post not found'], 404);
        }
        return response()->json($post[0], 200);
    }
}
