<?php

namespace App\Http\Controllers\Read;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ConditionalReadController extends Controller
{
    public function getPostsByConditionWithEloquent(Request $request)
    {
        $validatedData = $request->validate([
            'author_id' => 'nullable|exists:users,id',
            'title' => 'nullable|string',
            'slug' => 'nullable|string'
        ]);

        $query = Post::query();

        if (!empty($validatedData['author_id'])) {
            $query->where('author_id', $validatedData['author_id']);
        }

        if (!empty($validatedData['title'])) {
            $query->where('title', 'LIKE', '%' . $validatedData['title'] . '%');
        }

        if (!empty($validatedData['slug'])) {
            $query->where('slug', 'LIKE', '%' . $validatedData['slug'] . '%');
        }

        $posts = $query->get();

        if ($posts->isEmpty()) {
            return response()->json(['message' => 'No posts found'], 404);
        }

        return response()->json($posts, 200);
    }

    public function getPostsByConditionWithQueryBuilder(Request $request)
    {
        $validatedData = $request->validate([
            'author_id' => 'nullable|exists:users,id',
            'title' => 'nullable|string',
            'slug' => 'nullable|string'
        ]);

        $query = DB::table('posts');

        if (!empty($validatedData['author_id'])) {
            $query->where('author_id', $validatedData['author_id']);
        }

        if (!empty($validatedData['title'])) {
            $query->where('title', 'LIKE', '%' . $validatedData['title'] . '%');
        }

        if (!empty($validatedData['slug'])) {
            $query->where('slug', 'LIKE', '%' . $validatedData['slug'] . '%');
        }

        $posts = $query->get();

        if ($posts->isEmpty()) {
            return response()->json(['message' => 'No posts found'], 404);
        }

        return response()->json($posts, 200);
    }

    public function getPostsByConditionWithRawSQL(Request $request)
    {
        $validatedData = $request->validate([
            'author_id' => 'nullable|exists:users,id',
            'title' => 'nullable|string',
            'slug' => 'nullable|string'
        ]);

        $query = "SELECT * FROM posts WHERE 1=1";

        $bindings = [];

        if (!empty($validatedData['author_id'])) {
            $query .= " AND author_id = ?";
            $bindings[] = $validatedData['author_id'];
        }

        if (!empty($validatedData['title'])) {
            $query .= " AND title LIKE ?";
            $bindings[] = '%' . $validatedData['title'] . '%';
        }

        if (!empty($validatedData['slug'])) {
            $query .= " AND slug LIKE ?";
            $bindings[] = '%' . $validatedData['slug'] . '%';
        }

        $posts = DB::select($query, $bindings);

        if (empty($posts)) {
            return response()->json(['message' => 'No posts found'], 404);
        }

        return response()->json($posts, 200);
    }
}
