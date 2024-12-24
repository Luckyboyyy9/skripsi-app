<?php

namespace App\Http\Controllers\Create;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class RelationalInsertController extends Controller
{
    public function insertRelationalWithEloquent(Request $request)
    {
        $validatedData = $request->validate(
            [
                'title' => 'required|max:255',
                'author_id' => 'required|exists:users,id',
                'slug' => 'required|string|unique:posts,slug|max:255',
                'body' => 'required|string',
            ]
        );

        $post = new Post();
        $post->title = $validatedData['title'];  // Menyimpan title
        $post->author_id = $validatedData['author_id'];  // Relasi ke user
        $post->slug = $validatedData['slug'];  // Menyimpan slug
        $post->body = $validatedData['body'];  // Menyimpan body konten
        $post->save();

        return response()->json(
            [
                'message' => 'Post created successfully with Eloquent',
                'post' => $post
            ],
            201
        );
    }

    public function insertRelationalWithQueryBuilder(Request $request)
    {
        $validatedData = $request->validate(
            [
                'title' => 'required|max:255',
                'author_id' => 'required|exists:users,id',
                'slug' => 'required|string|unique:posts,slug|max:255',
                'body' => 'required|string',
            ]
        );

        DB::table('posts')->insert(
            [
                'title' => $validatedData['title'],
                'author_id' => $validatedData['author_id'],
                'slug' => $validatedData['slug'],
                'body' => $validatedData['body'],
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        return response()->json(
            [
                'message' => 'Post created successfully with Query Builder',
                'post' => $validatedData
            ],
            201
        );
    }

    public function insertRelationalWithRawSQL(Request $request)
    {
        $validatedData = $request->validate(
            [
                'title' => 'required|max:255',
                'author_id' => 'required|exists:users,id',
                'slug' => 'required|string|unique:posts,slug|max:255',
                'body' => 'required|string',
            ]
        );

        $query = "INSERT INTO posts (title,author_id,slug,body,created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW()";

        $bindings = [
            $validatedData['title'],
            $validatedData['author_id'],
            $validatedData['slug'],
            $validatedData['body']
        ];

        DB::insert($query, $bindings);

        return response()->json(
            [
                'message' => 'Post created successfully with Raw SQL',
                'post' => $validatedData
            ],
            201
        );
    }
}
