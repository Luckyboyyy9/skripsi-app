<?php

namespace App\Http\Controllers\Update;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class BulkUpdateController extends Controller
{
    public function bulkUpdateWithEloquent(Request $request)
    {
        $validatedData = $request->validate([
            'author_id' => 'required|exists:users,id', // Filter berdasarkan author_id
            'title' => 'nullable|max:255',
            'body' => 'nullable|string',
        ]);

        // Cek apakah ada kolom yang ingin di-update
        $updates = [];

        if ($request->has('title')) {
            $updates['title'] = $validatedData['title'];
        }

        if ($request->has('body')) {
            $updates['body'] = $validatedData['body'];
        }

        if (!empty($updates)) {
            // Update semua post berdasarkan author_id
            $affectedRows = Post::where('author_id', $validatedData['author_id'])
                ->update($updates);

            if ($affectedRows > 0) {
                return response()->json([
                    'message' => "$affectedRows posts updated successfully with Eloquent",
                    'post' => $updates
                ], 200);
            } else {
                return response()->json(['message' => 'No posts were updated'], 404);
            }
        }

        return response()->json(['message' => 'No valid data to update'], 400);
    }

    public function bulkUpdateWithQueryBuilder(Request $request)
    {
        $validatedData = $request->validate([
            'author_id' => 'required|exists:users,id', // Filter berdasarkan author_id
            'title' => 'nullable|max:255',
            'body' => 'nullable|string',
        ]);

        // Cek apakah ada kolom yang ingin di-update
        $updates = [];

        if ($request->has('title')) {
            $updates['title'] = $validatedData['title'];
        }

        if ($request->has('body')) {
            $updates['body'] = $validatedData['body'];
        }

        // Jika ada kolom yang ingin di-update
        if (!empty($updates)) {
            // Update semua post berdasarkan author_id
            $affectedRows = DB::table('posts')
                ->where('author_id', $validatedData['author_id'])
                ->update($updates);

            if ($affectedRows > 0) {
                return response()->json([
                    'message' => "$affectedRows posts updated successfully with Query Builder",
                ], 200);
            } else {
                return response()->json(['message' => 'No posts were updated'], 404);
            }
        }
        return response()->json(['message' => 'No valid data to update'], 400);
    }

    public function bulkUpdateWithRawSQL(Request $request)
    {
        // Validasi data yang diterima dari request
        $validatedData = $request->validate([
            'author_id' => 'required|exists:users,id', // Filter berdasarkan author_id
            'title' => 'nullable|max:255', // Kolom yang ingin di-update
            'body' => 'nullable|string',   // Kolom yang ingin di-update
        ]);

        // Cek apakah ada kolom yang ingin di-update
        $updates = [];

        if ($request->has('title')) {
            $updates['title'] = $validatedData['title'];
        }

        if ($request->has('body')) {
            $updates['body'] = $validatedData['body'];
        }

        if (!empty($updates)) {
            // Buat query SQL untuk bulk update
            $query = "UPDATE posts SET ";
            $bindings = [];

            // Tambahkan kolom yang di-update ke dalam query
            if (!empty($updates['title'])) {
                $query .= "title = ?, ";
                $bindings[] = $updates['title'];
            }

            if (!empty($updates['body'])) {
                $query .= "body = ?, ";
                $bindings[] = $updates['body'];
            }

            // Tambahkan updated_at
            $query .= "updated_at = NOW() WHERE author_id = ?";
            $bindings[] = $validatedData['author_id'];

            // Jalankan query
            $affectedRows = DB::update($query, $bindings);

            if ($affectedRows > 0) {
                return response()->json([
                    'message' => "$affectedRows posts updated successfully with Raw SQL",
                ], 200);
            } else {
                return response()->json(['message' => 'No posts were updated'], 404);
            }
        }

        return response()->json(['message' => 'No valid data to update'], 400);
    }
}
