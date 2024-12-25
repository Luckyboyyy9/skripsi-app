<?php

namespace App\Http\Controllers\Create;

use Exception;
use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class BulkInsertController extends Controller
{
    public function bulkInsertWithEloquent(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'posts' => 'required|array',
                'posts.*.title' => 'required|max:255',
                'posts.*.author_id' => 'required|exists:users,id',
                'posts.*.body' => 'required|string',
            ]);

            Post::insert(array_map(function ($post) {
                return [
                    'title' => $post['title'],
                    'author_id' => $post['author_id'],
                    'slug' => Str::uuid7(),
                    'body' => $post['body'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }, $validatedData['posts']));

            return response()->json([
                'message' => 'Posts created successfully with Eloquent',
                'posts' => $validatedData['posts']
            ], 201);
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 422);
        }
    }

    public function bulkInsertWithQueryBuilder(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'posts' => 'required|array',
                'posts.*.title' => 'required|max:255',
                'posts.*.author_id' => 'required|exists:users,id',
                'posts.*.body' => 'required|string',
            ]);

            $insertData = array_map(function ($post) {
                return [
                    'title' => $post['title'],
                    'author_id' => $post['author_id'],
                    'slug' => Str::uuid7(),
                    'body' => $post['body'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }, $validatedData['posts']);

            DB::table('posts')->insert($insertData);

            return response()->json([
                'message' => 'Posts created successfully with Query Builder',
                'posts' => $validatedData['posts']
            ], 201);
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 422);
        }
    }

    public function bulkInsertWithRawSQL(Request $request)
    {
        try {

            $validatedData = $request->validate([
                'posts' => 'required|array',
                'posts.*.title' => 'required|max:255',
                'posts.*.author_id' => 'required|exists:users,id',
                'posts.*.body' => 'required|string',
            ]);

            // Menyiapkan bagian query dan nilai
            $query = "INSERT INTO posts (title, author_id, slug, body, created_at, updated_at) VALUES ";
            $values = [];
            $bindings = [];

            foreach ($validatedData['posts'] as $post) {
                $values[] = "(?, ?, ?, ?, NOW(), NOW())";
                $bindings[] = $post['title'];
                $bindings[] = $post['author_id'];
                $bindings[] = Str::uuid7();
                $bindings[] = $post['body'];
            }

            // Menggabungkan query
            $query .= implode(", ", $values);

            // Menjalankan query dengan bindings
            DB::statement($query, $bindings);

            return response()->json([
                'message' => 'Posts created successfully with Raw SQL',
                'posts' => $validatedData['posts']
            ], 201);
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 422);
        }
    }
}
