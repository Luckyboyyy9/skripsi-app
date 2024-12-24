<?php

namespace App\Http\Controllers\Update;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class RelationalUpdateController extends Controller
{
    public function updateRelationalWithEloquent(Request $request, $id)
    {
        // Validasi input dari request
        $validatedData = $request->validate([
            'title' => 'nullable|max:255',
            'slug' => 'nullable|string|unique:posts,slug,' . $id,
            'body' => 'nullable|string',
            'author_id' => 'nullable|exists:users,id'  // Validasi foreign key
        ]);

        // Temukan post berdasarkan id atau fail
        $post = Post::findOrFail($id);

        // Cek jika request mengirim 'title'
        if ($request->has('title')) {
            $post->title = $validatedData['title'];
        }

        // Cek jika request mengirim 'slug'
        if ($request->has('slug')) {
            $post->slug = $validatedData['slug'];
        }

        // Cek jika request mengirim 'body'
        if ($request->has('body')) {
            $post->body = $validatedData['body'];
        }

        // Cek jika request mengirim 'author_id' (relational data)
        if ($request->has('author_id')) {
            $post->author_id = $validatedData['author_id'];
        }

        // Simpan perubahan
        $post->save();

        return response()->json([
            'message' => 'Post updated successfully with Eloquent',
            'post' => $post
        ], 200);
    }

    public function updateRelationalWithQueryBuilder(Request $request, $id)
    {
        // Validasi input dari request
        $validatedData = $request->validate([
            'title' => 'nullable|max:255',
            'slug' => 'nullable|string|unique:posts,slug,' . $id,
            'body' => 'nullable|string',
            'author_id' => 'nullable|exists:users,id'  // Validasi foreign key
        ]);

        // Cek apakah post ada di database
        $post = DB::table('posts')->where('id', $id)->first();

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        // Siapkan data update
        $updates = [];

        // Cek jika request mengirim 'title', update title
        if ($request->has('title')) {
            $updates['title'] = $validatedData['title'];
        }

        // Cek jika request mengirim 'slug', update slug
        if ($request->has('slug')) {
            $updates['slug'] = $validatedData['slug'];
        }

        // Cek jika request mengirim 'body', update body
        if ($request->has('body')) {
            $updates['body'] = $validatedData['body'];
        }

        // Cek jika request mengirim 'author_id', update author_id
        if ($request->has('author_id')) {
            $updates['author_id'] = $validatedData['author_id'];
        }

        // Menambahkan timestamp untuk updated_at
        if (!empty($updates)) {
            $updates['updated_at'] = now(); // Menyimpan waktu saat diupdate
            DB::table('posts')
                ->where('id', $id)
                ->update($updates); // Melakukan update
        }

        return response()->json([
            'message' => 'Post updated successfully with Query Builder',
            'post' => $updates
        ], 200);
    }

    public function updateRelationalWithRawSQL(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'nullable|max:255',
            'slug' => 'nullable|string|unique:posts,slug,' . $id,
            'body' => 'nullable|string',
            'author_id' => 'nullable|exists:users,id'
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

        // Cek jika ada 'author_id' untuk di-update
        if (!empty($validatedData['author_id'])) {
            $fields[] = "author_id = ?";
            $bindings[] = $validatedData['author_id'];
        }

        // Menambahkan updated_at ke query
        $fields[] = "updated_at = NOW()";

        // Jika tidak ada fields yang di-update, return response 400
        if (empty($fields)) {
            return response()->json(['message' => 'No fields to update'], 400);
        }

        // Menambahkan 'id' untuk digunakan di WHERE
        $bindings[] = $id;

        // Membuat query SQL
        $sql = "UPDATE posts SET " . implode(", ", $fields) . " WHERE id = ?";

        // Menjalankan SQL update
        DB::statement($sql, $bindings);

        return response()->json([
            'message' => 'Post updated successfully with Raw SQL',
            'post' => $validatedData
        ], 200);
    }
}
