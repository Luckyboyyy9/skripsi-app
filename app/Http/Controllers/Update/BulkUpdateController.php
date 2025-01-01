<?php

namespace App\Http\Controllers\Update;

use Exception;
use App\Models\Post;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class BulkUpdateController extends Controller
{
    public function bulkUpdateWithEloquent(Request $request)
    {
        $validatedData = $request->validate([
            'angkatan' => 'required|integer', // Filter berdasarkan angkatan
            'status' => 'required|in:Aktif,Lulus,Cuti,Drop Out', // Status baru
        ]);

        try {
            // Update semua mahasiswa berdasarkan angkatan
            $affectedRows = Mahasiswa::where('angkatan', $validatedData['angkatan'])
                ->update(['status' => $validatedData['status'], 'updated_at' => now()]);

            if ($affectedRows > 0) {
                return response()->json([
                    'message' => "$affectedRows mahasiswa updated successfully with Eloquent",
                    'angkatan' => $validatedData['angkatan'],
                    'status' => $validatedData['status']
                ], 200);
            } else {
                return response()->json(['message' => 'No mahasiswa were updated'], 404);
            }
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 422);
        }
    }

    public function bulkUpdateMahasiswaWithQueryBuilder(Request $request)
    {
        $validatedData = $request->validate([
            'angkatan' => 'required|integer', // Filter berdasarkan angkatan
            'status' => 'required|in:Aktif,Lulus,Cuti,Drop Out'
        ]);

        try {
            // Update semua mahasiswa berdasarkan angkatan
            $affectedRows = DB::table('mahasiswa')
                ->where('angkatan', $validatedData['angkatan'])
                ->update(['status' => $validatedData['status'], 'updated_at' => now()]);

            if ($affectedRows > 0) {
                return response()->json([
                    'message' => "$affectedRows mahasiswa updated successfully with Query Builder",
                    'angkatan' => $validatedData['angkatan'],
                    'status' => $validatedData['status']
                ], 200);
            } else {
                return response()->json(['message' => 'No mahasiswa were updated'], 404);
            }
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function bulkUpdateMahasiswaWithRawSQL(Request $request)
    {
        $validatedData = $request->validate([
            'angkatan' => 'required|integer', // Filter berdasarkan angkatan
            'status' => 'required|in:Aktif,Lulus,Cuti,Drop Out'
        ]);

        try {
            // Buat query SQL untuk bulk update
            $query = "UPDATE mahasiswa SET status = ?, updated_at = NOW() WHERE angkatan = ?";
            $bindings = [$validatedData['status'], $validatedData['angkatan']];

            // Jalankan query
            $affectedRows = DB::update($query, $bindings);

            if ($affectedRows > 0) {
                return response()->json([
                    'message' => "$affectedRows mahasiswa updated successfully with Raw SQL",
                    'angkatan' => $validatedData['angkatan'],
                    'status' => $validatedData['status']
                ], 200);
            } else {
                return response()->json(['message' => 'No mahasiswa were updated'], 404);
            }
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 422);
        }
    }
}
