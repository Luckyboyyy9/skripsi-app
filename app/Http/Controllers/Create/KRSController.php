<?php

namespace App\Http\Controllers\Create;

use Exception;
use App\Models\KRS;
use App\Models\MataKuliah;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KRSController extends Controller
{
    public function registerToCourse(Request $request)
    {
        try {
            // Validasi input
            $validatedData = $request->validate([
                'id_mahasiswa' => 'required|exists:mahasiswa,id',
                'id_mk' => 'required|exists:mata_kuliah,id',
            ]);

            // Cek apakah sudah ada KRS untuk mahasiswa dan mata kuliah tertentu
            $existingKRS = KRS::where('id_mahasiswa', $validatedData['id_mahasiswa'])
                              ->where('id_mk', $validatedData['id_mk'])
                              ->first();

            if ($existingKRS) {
                return response()->json([
                    'message' => 'Mahasiswa is already registered to this mata kuliah',
                ], 409);
            }

            // Ambil informasi mata kuliah
            $mataKuliah = MataKuliah::find($validatedData['id_mk']);

            // Menambahkan data ke tabel KRS
            $krs = KRS::create($validatedData);

            return response()->json([
                'message' => "Mahasiswa registered to mata kuliah \"{$mataKuliah->nama_mk}\" berhasil",
                'data' => $krs
            ], 201);
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 422);
        }
    }
}
