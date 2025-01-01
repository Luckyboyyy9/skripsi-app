<?php

namespace App\Http\Controllers\Read;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SingleReadController extends Controller
{
    public function getSingleMahasiswaWithEloquent($id)
    {
        try {
            $mahasiswa = Mahasiswa::findOrFail($id);
            return response()->json($mahasiswa, 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Mahasiswa not found'], 404);
        }
    }

    public function getSingleMahasiswaWithQueryBuilder($id)
    {
        $mahasiswa = DB::table('mahasiswa')->where('id', $id)->first();
        if (!$mahasiswa) {
            return response()->json(['message' => 'Mahasiswa not found'], 404);
        }
        return response()->json($mahasiswa, 200);
    }

    public function getSingleMahasiswaWithRawSQL($id)
    {
        $mahasiswa = DB::select('SELECT * FROM mahasiswa WHERE id = ?', [$id]);
        if (empty($mahasiswa)) {
            return response()->json(['message' => 'Mahasiswa not found'], 404);
        }
        return response()->json($mahasiswa[0], 200);
    }
}
