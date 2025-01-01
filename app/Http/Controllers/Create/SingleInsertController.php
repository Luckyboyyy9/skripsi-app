<?php

namespace App\Http\Controllers\Create;

use Exception;
use App\Models\Post;

use App\Models\Mahasiswa;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SingleInsertController extends Controller
{
    public function insertSingleMahasiswaWithEloquent(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nim' => 'required|unique:mahasiswa,nim|max:20',
                'nama' => 'required|max:100',
                'jurusan' => 'required|max:100',
                'angkatan' => 'required|integer',
                'status' => 'required|in:Aktif,Lulus,Cuti,Drop Out',
            ]);

            $mahasiswa = Mahasiswa::create($validatedData);

            return response()->json([
                'message' => 'Mahasiswa created successfully with Eloquent',
                'mahasiswa' => $mahasiswa
            ], 201);
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 422);
        }
    }

    public function insertSingleMahasiswaWithQueryBuilder(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nim' => 'required|unique:mahasiswa,nim|max:20',
                'nama' => 'required|max:100',
                'jurusan' => 'required|max:100',
                'angkatan' => 'required|integer',
                'status' => 'required|in:Aktif,Lulus,Cuti,Drop Out',
            ]);

            DB::table('mahasiswa')->insert([
                'nim' => $validatedData['nim'],
                'nama' => $validatedData['nama'],
                'jurusan' => $validatedData['jurusan'],
                'angkatan' => $validatedData['angkatan'],
                'status' => $validatedData['status'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'message' => 'Mahasiswa created successfully with Query Builder',
                'mahasiswa' => $validatedData
            ], 201);
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 422);
        }
    }

    public function insertSingleMahasiswaWithRawSQL(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nim' => 'required|unique:mahasiswa,nim|max:20',
                'nama' => 'required|max:100',
                'jurusan' => 'required|max:100',
                'angkatan' => 'required|integer',
                'status' => 'required|in:Aktif,Lulus,Cuti,Drop Out',
            ]);

            DB::insert('INSERT INTO mahasiswa (nim, nama, jurusan, angkatan, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())', [
                $validatedData['nim'],
                $validatedData['nama'],
                $validatedData['jurusan'],
                $validatedData['angkatan'],
                $validatedData['status'],
            ]);

            return response()->json([
                'message' => 'Mahasiswa created successfully with Raw SQL',
                'mahasiswa' => $validatedData
            ], 201);
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 422);
        }
    }
}
