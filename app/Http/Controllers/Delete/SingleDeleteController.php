<?php

namespace App\Http\Controllers\Delete;

use Exception;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SingleDeleteController extends Controller
{
    public function deleteMahasiswaWithEloquent($id)
    {
        try {
            // Cari mahasiswa berdasarkan ID
            $mahasiswa = Mahasiswa::findOrFail($id);

            // Hapus mahasiswa
            $mahasiswa->delete();

            return response()->json([
                'message' => 'Mahasiswa deleted successfully with Eloquent',
                'data' => $mahasiswa
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Mahasiswa not found'], 404);
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 422);
        }
    }

    public function deleteMahasiswaWithQueryBuilder($id)
    {
        try {
            // Ambil data mahasiswa sebelum dihapus
            $mahasiswa = DB::table('mahasiswa')->where('id', $id)->first();

            if (!$mahasiswa) {
                return response()->json(['message' => 'Mahasiswa not found'], 404);
            }

            // Hapus data mahasiswa
            DB::table('mahasiswa')->where('id', $id)->delete();

            return response()->json([
                'message' => 'Mahasiswa deleted successfully with Query Builder',
                'data' => $mahasiswa
            ], 200);
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 422);
        }
    }

    public function deleteMahasiswaWithRawSQL($id)
    {
        try {
            // Ambil data mahasiswa sebelum dihapus
            $mahasiswa = DB::select('SELECT * FROM mahasiswa WHERE id = ?', [$id]);

            if (empty($mahasiswa)) {
                return response()->json(['message' => 'Mahasiswa not found'], 404);
            }

            // Hapus data mahasiswa
            DB::delete('DELETE FROM mahasiswa WHERE id = ?', [$id]);

            return response()->json([
                'message' => 'Mahasiswa deleted successfully with Raw SQL',
                'data' => $mahasiswa[0] // Ambil data pertama karena hasil SELECT adalah array
            ], 200);
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 422);
        }
    }
}
