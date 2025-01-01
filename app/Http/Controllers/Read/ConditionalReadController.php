<?php

namespace App\Http\Controllers\Read;

use Exception;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ConditionalReadController extends Controller
{
    public function getMahasiswaByJurusanWithEloquent(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'jurusan' => 'required|string|max:100',
            ]);

            $mahasiswa = Mahasiswa::where('jurusan', $validatedData['jurusan'])->get();

            if ($mahasiswa->isEmpty()) {
                return response()->json(['message' => 'No mahasiswa found for the specified jurusan'], 404);
            }

            return response()->json($mahasiswa, 200);
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 422);
        }
    }

    public function getMahasiswaByJurusanWithQueryBuilder(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'jurusan' => 'required|string|max:100',
            ]);

            $mahasiswa = DB::table('mahasiswa')->where('jurusan', $validatedData['jurusan'])->get();

            if ($mahasiswa->isEmpty()) {
                return response()->json(['message' => 'No mahasiswa found for the specified jurusan'], 404);
            }

            return response()->json($mahasiswa, 200);
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 422);
        }
    }

    public function getMahasiswaByJurusanWithRawSQL(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'jurusan' => 'required|string|max:100',
            ]);

            $mahasiswa = DB::select('SELECT * FROM mahasiswa WHERE jurusan = ?', [$validatedData['jurusan']]);

            if (empty($mahasiswa)) {
                return response()->json(['message' => 'No mahasiswa found for the specified jurusan'], 404);
            }

            return response()->json($mahasiswa, 200);
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 422);
        }
    }
}
