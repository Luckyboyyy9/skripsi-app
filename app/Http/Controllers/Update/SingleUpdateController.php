<?php

namespace App\Http\Controllers\Update;

use Exception;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SingleUpdateController extends Controller
{
    public function updateMahasiswaWithEloquent(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama' => 'nullable|string|max:100',
            'jurusan' => 'nullable|string|max:100',
            'status' => 'nullable|in:Aktif,Lulus,Cuti,Drop Out',
        ]);

        try {
            // Menggunakan findOrFail untuk mendapatkan mahasiswa berdasarkan ID
            $mahasiswa = Mahasiswa::findOrFail($id);

            // Update hanya field yang ada di request
            $mahasiswa->update($validatedData);

            return response()->json([
                'message' => 'Mahasiswa updated successfully with Eloquent',
                'mahasiswa' => array_merge($validatedData, ['updated_at' => now()->toDateTimeString()])
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Mahasiswa not found'], 404);
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 422);
        }
    }

    public function updateMahasiswaWithQueryBuilder(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama' => 'nullable|string|max:100',
            'jurusan' => 'nullable|string|max:100',
            'status' => 'nullable|in:Aktif,Lulus,Cuti,Drop Out',
        ]);

        try {
            // Cek apakah mahasiswa ada di database
            $mahasiswa = DB::table('mahasiswa')->where('id', $id)->first();

            if (!$mahasiswa) {
                return response()->json(['message' => 'Mahasiswa not found'], 404);
            }

            // Update hanya field yang ada di request
            $updates = array_filter($validatedData);

            if (!empty($updates)) {
                $updates['updated_at'] = now();
                DB::table('mahasiswa')->where('id', $id)->update($updates);
            }

            return response()->json([
                'message' => 'Mahasiswa updated successfully with Query Builder',
                'mahasiswa' => array_merge($validatedData, ['updated_at' => now()->toDateTimeString()])
            ], 200);
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 422);
        }
    }

    public function updateMahasiswaWithRawSQL(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama' => 'nullable|string|max:100',
            'jurusan' => 'nullable|string|max:100',
            'status' => 'nullable|in:Aktif,Lulus,Cuti,Drop Out',
        ]);

        try {
            $fields = [];
            $bindings = [];

            if (!empty($validatedData['nama'])) {
                $fields[] = "nama = ?";
                $bindings[] = $validatedData['nama'];
            }
            if (!empty($validatedData['jurusan'])) {
                $fields[] = "jurusan = ?";
                $bindings[] = $validatedData['jurusan'];
            }
            if (!empty($validatedData['status'])) {
                $fields[] = "status = ?";
                $bindings[] = $validatedData['status'];
            }

            // **Tambahkan validasi jika tidak ada field yang dikirim**
            if (empty($fields)) {
                return response()->json(['message' => 'No fields to update'], 400);
            }

            // Tambahkan updated_at agar tetap ada field yang di-update
            $fields[] = "updated_at = NOW()";
            $bindings[] = $id;

            $sql = "UPDATE mahasiswa SET " . implode(", ", $fields) . " WHERE id = ?";
            DB::statement($sql, $bindings);

            // Ambil kembali data mahasiswa setelah update
            $updatedMahasiswa = DB::select("SELECT * FROM mahasiswa WHERE id = ?", [$id]);

            return response()->json([
                'message' => 'Mahasiswa updated successfully with Raw SQL',
                'mahasiswa' => array_merge($validatedData, ['updated_at' => now()->toDateTimeString()])
            ], 200);
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 422);
        }
    }
}
