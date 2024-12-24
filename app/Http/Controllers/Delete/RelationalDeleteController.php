<?php

namespace App\Http\Controllers\Delete;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class RelationalDeleteController extends Controller
{
    public function deleteRelationalWithEloquent(Request $request)
    {
        // Validasi input dari request untuk foreign key constraints
        $validatedData = $request->validate([
            'author_id' => 'nullable|exists:users,id',
            'title' => 'nullable|string',
            'slug' => 'nullable|string',
        ]);

        // Memulai query menggunakan model Eloquent Post
        $query = Post::query();

        // Menambahkan filter berdasarkan author_id
        if (!empty($validatedData['author_id'])) {
            $query->where('author_id', $validatedData['author_id']);
        }

        // Menambahkan filter berdasarkan title
        if (!empty($validatedData['title'])) {
            $query->where('title', 'LIKE', '%' . $validatedData['title'] . '%');
        }

        // Menambahkan filter berdasarkan slug
        if (!empty($validatedData['slug'])) {
            $query->where('slug', 'LIKE', '%' . $validatedData['slug'] . '%');
        }

        // Menghapus data yang sesuai dengan query
        $deletedPosts = $query->delete();

        // Jika tidak ada post yang dihapus
        if ($deletedPosts === 0) {
            return response()->json(['message' => 'No posts found for the given conditions'], 404);
        }

        return response()->json([
            'message' => 'Posts with relational data deleted successfully with Eloquent',
            'deleted_count' => $deletedPosts,  // Mengembalikan jumlah post yang dihapus
        ], 200);
    }

    public function deleteRelationalWithQueryBuilder(Request $request)
    {
        // Validasi input dari request
        $validatedData = $request->validate([
            'author_id' => 'required|exists:users,id',  // Validasi foreign key (relasi ke tabel users)
            'title' => 'nullable|string',  // Optional, filter berdasarkan title
            'slug' => 'nullable|string',   // Optional, filter berdasarkan slug
        ]);

        // Query dasar untuk menghapus data dengan relasi author_id
        $query = DB::table('posts')->where('author_id', $validatedData['author_id']);

        // Cek jika ada filter title, tambahkan kondisi
        if (!empty($validatedData['title'])) {
            $query->where('title', $validatedData['title']);
        }

        // Cek jika ada filter slug, tambahkan kondisi
        if (!empty($validatedData['slug'])) {
            $query->where('slug', $validatedData['slug']);
        }

        // Eksekusi penghapusan
        $deleted = $query->delete();

        // Cek apakah ada data yang dihapus
        if ($deleted) {
            return response()->json([
                'message' => 'Posts with relational data deleted successfully with Query Builder',
                'deleted_count' => $deleted
            ], 200);
        } else {
            return response()->json(['message' => 'No posts found to delete'], 404);
        }
    }

    public function deleteRelationalWithRawSQL(Request $request)
    {
        // Validasi input dari request
        $validatedData = $request->validate([
            'author_id' => 'required|exists:users,id',  // Validasi foreign key (relasi ke tabel users)
            'title' => 'nullable|string',  // Optional, filter berdasarkan title
            'slug' => 'nullable|string',   // Optional, filter berdasarkan slug
        ]);

        // Siapkan query dasar
        $query = "DELETE FROM posts WHERE author_id = ?";
        $bindings = [$validatedData['author_id']];

        // Jika ada filter title, tambahkan ke query dan bindings
        if (!empty($validatedData['title'])) {
            $query .= " AND title = ?";
            $bindings[] = $validatedData['title'];
        }

        // Jika ada filter slug, tambahkan ke query dan bindings
        if (!empty($validatedData['slug'])) {
            $query .= " AND slug = ?";
            $bindings[] = $validatedData['slug'];
        }

        // Eksekusi penghapusan
        $deleted = DB::delete($query, $bindings);

        // Cek apakah ada data yang dihapus
        if ($deleted) {
            return response()->json([
                'message' => 'Post(s) deleted successfully with Raw SQL',
                'deleted_count' => $deleted
            ], 200);
        } else {
            return response()->json(['message' => 'No posts found to delete'], 404);
        }
    }
}
