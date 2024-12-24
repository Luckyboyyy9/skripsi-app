<?php

namespace App\Http\Controllers\Delete;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SingleDeleteController extends Controller
{
    public function deletePostWithEloquent($id)
    {
        // Cari post berdasarkan ID atau return 404 jika tidak ditemukan
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        // Hapus post yang ditemukan
        $post->delete();

        return response()->json([
            'message' => 'Post deleted successfully',
            'post' => $post  // Optional: Mengembalikan data post yang dihapus
        ], 200);
    }


    public function deleteSingleWithQueryBuilder($id)
    {
        // Cari post berdasarkan ID menggunakan Query Builder
        $post = DB::table('posts')->where('id', $id)->delete();

        // Cek jika tidak ada post yang dihapus (post tidak ditemukan)
        if (!$post) {
            return response()->json(['message' => 'No posts found'], 404);  // Ubah dari 400 menjadi 404
        }

        return response()->json([
            'message' => 'Post deleted successfully',
            'deleted_count' => $post,  // Mengembalikan jumlah baris yang dihapus
        ], 200);
    }


    public function deleteSingleWithRawSQL($id)
    {
        // Gunakan Raw SQL untuk menghapus post berdasarkan id
        $deletedPost = DB::delete('DELETE FROM posts WHERE id = ?', [$id]);

        // Cek jika tidak ada post yang dihapus (post tidak ditemukan)
        if (!$deletedPost) {
            return response()->json(['message' => 'No posts found'], 404);
        }

        return response()->json([
            'message' => 'Post deleted successfully with Raw SQL',
            'deleted_count' => $deletedPost,  // Mengembalikan jumlah baris yang dihapus
        ], 200);
    }
}
