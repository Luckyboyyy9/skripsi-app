<?php

namespace App\Http\Controllers\Read;

use Exception;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class RelationalReadController extends Controller
{
    public function getMataKuliahByMahasiswaWithEloquent($id)
    {
        try {
            $mahasiswa = Mahasiswa::with('krs.mataKuliah')->findOrFail($id);

            // Format data krs agar hanya menampilkan detail mata kuliah
            $mataKuliah = $mahasiswa->krs->map(function ($krs) {
                return [
                    'id' => $krs->mataKuliah->id,
                    'kode_mk' => $krs->mataKuliah->kode_mk,
                    'nama_mk' => $krs->mataKuliah->nama_mk,
                    'sks' => $krs->mataKuliah->sks,
                ];
            });

            return response()->json([
                'id' => $mahasiswa->id,
                'nim' => $mahasiswa->nim,
                'nama' => $mahasiswa->nama,
                'jurusan' => $mahasiswa->jurusan,
                'angkatan' => $mahasiswa->angkatan,
                'status' => $mahasiswa->status,
                'mata_kuliah' => $mataKuliah,
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Mahasiswa not found'], 404);
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 422);
        }
    }

    public function getMataKuliahByMahasiswaWithQueryBuilder($id)
    {
        try {
            $mahasiswa = DB::table('mahasiswa')->where('id', $id)->first();

            if (!$mahasiswa) {
                return response()->json(['message' => 'Mahasiswa not found'], 404);
            }

            $mataKuliah = DB::table('krs')
                ->join('mata_kuliah', 'krs.id_mk', '=', 'mata_kuliah.id')
                ->where('krs.id_mahasiswa', $id)
                ->select('mata_kuliah.id', 'mata_kuliah.kode_mk', 'mata_kuliah.nama_mk', 'mata_kuliah.sks')
                ->get();

            return response()->json([
                'id' => $mahasiswa->id,
                'nim' => $mahasiswa->nim,
                'nama' => $mahasiswa->nama,
                'jurusan' => $mahasiswa->jurusan,
                'angkatan' => $mahasiswa->angkatan,
                'status' => $mahasiswa->status,
                'mata_kuliah' => $mataKuliah,
            ], 200);
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 422);
        }
    }

    public function getMataKuliahByMahasiswaWithRawSQL($id)
    {
        try {
            $mahasiswaQuery = "SELECT * FROM mahasiswa WHERE id = ?";
            $mahasiswa = DB::select($mahasiswaQuery, [$id]);

            if (empty($mahasiswa)) {
                return response()->json(['message' => 'Mahasiswa not found'], 404);
            }

            $mataKuliahQuery = "SELECT mata_kuliah.id, mata_kuliah.kode_mk, mata_kuliah.nama_mk, mata_kuliah.sks 
                                FROM krs 
                                JOIN mata_kuliah ON krs.id_mk = mata_kuliah.id 
                                WHERE krs.id_mahasiswa = ?";
            $mataKuliah = DB::select($mataKuliahQuery, [$id]);

            return response()->json([
                'id' => $mahasiswa[0]->id,
                'nim' => $mahasiswa[0]->nim,
                'nama' => $mahasiswa[0]->nama,
                'jurusan' => $mahasiswa[0]->jurusan,
                'angkatan' => $mahasiswa[0]->angkatan,
                'status' => $mahasiswa[0]->status,
                'mata_kuliah' => $mataKuliah,
            ], 200);
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 422);
        }
    }
}
