<?php

namespace App\Http\Controllers\Update;

use id;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SingleUpdateController extends Controller
{
    public function updateSingleWithEloquent(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'nullable|max:255',
            'slug' => 'nullable|string|unique:posts,slug,' . $id,
            'body' => 'nullable|string',
        ]);

        try {
            // Menggunakan findOrFail, tetapi menangani exception
            $post = Post::findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        // Cek jika request mengirim 'title', update title
        if ($request->has('title')) {
            $post->title = $validatedData['title'];
        }

        // Cek jika request mengirim 'slug', update slug
        if ($request->has('slug')) {
            $post->slug = $validatedData['slug'];
        }

        // Cek jika request mengirim 'body', update body
        if ($request->has('body')) {
            $post->body = $validatedData['body'];
        }

        $post->save();

        return response()->json([
            'message' => 'Post updated successfully with Eloquent',
            'post' => $post
        ], 200);
    }

    public function updateSingleWithQueryBuilder(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'nullable|max:255',
            'slug' => 'nullable|string|unique:posts,slug,' . $id,
            'body' => 'nullable|string',
        ]);

        // Cek apakah post ada di database
        $post = DB::table('posts')->where('id', $id)->first();

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $updates = [];

        // Cek jika request mengirim 'title'
        if ($request->has('title')) {
            $updates['title'] = $validatedData['title'];
        }

        // Cek jika request mengirim 'slug'
        if ($request->has('slug')) {
            $updates['slug'] = $validatedData['slug'];
        }

        // Cek jika request mengirim 'body'
        if ($request->has('body')) {
            $updates['body'] = $validatedData['body'];
        }

        if (!empty($updates)) {
            $updates['updated_at'] = now(); // Menambahkan nilai updated_at jika ada data yang di-update
            DB::table('posts')
                ->where('id', $id)
                ->update($updates); // Menjalankan update jika ada data yang dikirim
        }

        return response()->json([
            'message' => 'Post updated successfully with Query Builder',
            'post' => $validatedData
        ], 200);
    }

    public function updateSingleWithRawSQL(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'nullable|max:255',
            'slug' => 'nullable|string|unique:posts,slug,' . $id,
            'body' => 'nullable|string',
        ]);

        // Cek apakah ada data yang di-update
        $fields = [];
        $bindings = [];

        // Cek jika ada 'title' untuk di-update
        if (!empty($validatedData['title'])) {
            $fields[] = "title = ?";
            $bindings[] = $validatedData['title'];
        }

        // Cek jika ada 'slug' untuk di-update
        if (!empty($validatedData['slug'])) {
            $fields[] = "slug = ?";
            $bindings[] = $validatedData['slug'];
        }

        // Cek jika ada 'body' untuk di-update
        if (!empty($validatedData['body'])) {
            $fields[] = "body = ?";
            $bindings[] = $validatedData['body'];
        }

        // Jika tidak ada field yang diisi, kembalikan response error
        if (empty($fields)) {
            return response()->json(['message' => 'No fields to update'], 400);
        }

        // Tambahkan updated_at secara otomatis
        $fields[] = "updated_at = NOW()";

        // Tambahkan ID sebagai binding terakhir
        $bindings[] = $id;

        // Bangun query SQL dengan kolom yang akan di-update
        $sql = "UPDATE posts SET " . implode(", ", $fields) . " WHERE id = ?";

        // Jalankan query menggunakan DB::statement
        DB::statement($sql, $bindings);

        return response()->json([
            'message' => 'Post updated successfully with Raw SQL',
            'post' => $validatedData
        ], 200);
    }
}
