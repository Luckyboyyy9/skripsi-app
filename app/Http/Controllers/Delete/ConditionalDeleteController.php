<?php

namespace App\Http\Controllers\Delete;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ConditionalDeleteController extends Controller
{
    public function deleteByConditionWithEloquent(Request $request)
    {
        // Validasi input dari request untuk kondisi tertentu
        $validatedData = $request->validate([
            'author_id' => 'nullable|exists:users,id',
            'title' => 'nullable|string',
            'slug' => 'nullable|string',
        ]);

        // Query dasar untuk menghapus post dengan kondisi tertentu
        $query = Post::query();

        // Menambahkan kondisi berdasarkan author_id, jika diberikan
        if (!empty($validatedData['author_id'])) {
            $query->where('author_id', $validatedData['author_id']);
        }

        // Menambahkan kondisi berdasarkan title, jika diberikan
        if (!empty($validatedData['title'])) {
            $query->where('title', 'LIKE', '%' . $validatedData['title'] . '%');
        }

        // Menambahkan kondisi berdasarkan slug, jika diberikan
        if (!empty($validatedData['slug'])) {
            $query->where('slug', 'LIKE', '%' . $validatedData['slug'] . '%');
        }

        // Eksekusi penghapusan data berdasarkan kondisi yang ditentukan
        $deletedPosts = $query->delete();

        // Jika tidak ada post yang dihapus
        if ($deletedPosts === 0) {
            return response()->json(['message' => 'No posts found for the given conditions'], 404);
        }

        return response()->json([
            'message' => 'Posts deleted successfully with Eloquent',
            'deleted_count' => $deletedPosts,  // Mengembalikan jumlah post yang dihapus
        ], 200);
    }

    public function deleteByConditionWithQueryBuilder(Request $request)
    {
        // Validasi input dari request untuk kondisi tertentu
        $validatedData = $request->validate([
            'author_id' => 'nullable|exists:users,id',
            'title' => 'nullable|string',
            'slug' => 'nullable|string',
        ]);

        // Menyusun query dasar untuk menghapus post dengan kondisi tertentu
        $query = DB::table('posts');

        // Menambahkan kondisi berdasarkan author_id, jika diberikan
        if (!empty($validatedData['author_id'])) {
            $query->where('author_id', $validatedData['author_id']);
        }

        // Menambahkan kondisi berdasarkan title, jika diberikan
        if (!empty($validatedData['title'])) {
            $query->where('title', 'LIKE', '%' . $validatedData['title'] . '%');
        }

        // Menambahkan kondisi berdasarkan slug, jika diberikan
        if (!empty($validatedData['slug'])) {
            $query->where('slug', 'LIKE', '%' . $validatedData['slug'] . '%');
        }

        // Eksekusi penghapusan data berdasarkan kondisi yang ditentukan
        $deletedPosts = $query->delete();

        // Jika tidak ada post yang dihapus
        if ($deletedPosts === 0) {
            return response()->json(['message' => 'No posts found for the given conditions'], 404);
        }

        return response()->json([
            'message' => 'Posts deleted successfully with Query Builder',
            'deleted_count' => $deletedPosts,  // Mengembalikan jumlah post yang dihapus
        ], 200);
    }


    public function deleteByConditionWithRawSQL(Request $request)
    {
        // Validasi input dari request untuk kondisi tertentu
        $validatedData = $request->validate([
            'author_id' => 'nullable|exists:users,id',
            'title' => 'nullable|string',
            'slug' => 'nullable|string',
        ]);

        // Menyusun query dasar untuk penghapusan
        $query = "DELETE FROM posts WHERE 1=1";
        $bindings = [];

        // Menambahkan kondisi berdasarkan author_id, jika diberikan
        if (!empty($validatedData['author_id'])) {
            $query .= " AND author_id = ?";
            $bindings[] = $validatedData['author_id'];
        }

        // Menambahkan kondisi berdasarkan title, jika diberikan
        if (!empty($validatedData['title'])) {
            $query .= " AND title LIKE ?";
            $bindings[] = '%' . $validatedData['title'] . '%';
        }

        // Menambahkan kondisi berdasarkan slug, jika diberikan
        if (!empty($validatedData['slug'])) {
            $query .= " AND slug LIKE ?";
            $bindings[] = '%' . $validatedData['slug'] . '%';
        }

        // Menjalankan query penghapusan
        $deletedPosts = DB::delete($query, $bindings);

        // Jika tidak ada post yang dihapus
        if ($deletedPosts === 0) {
            return response()->json(['message' => 'No posts found for the given conditions'], 404);
        }

        return response()->json([
            'message' => 'Posts deleted successfully with Raw SQL',
            'deleted_count' => $deletedPosts,  // Mengembalikan jumlah post yang dihapus
        ], 200);
    }
}
