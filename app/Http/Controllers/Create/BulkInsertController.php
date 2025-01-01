<?php

namespace App\Http\Controllers\Create;

use Exception;
use App\Models\Mahasiswa;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class BulkInsertController extends Controller
{
    public function bulkInsertMahasiswaWithEloquent(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'mahasiswa' => 'required|array',
                'mahasiswa.*.nim' => 'required|unique:mahasiswa,nim|max:20',
                'mahasiswa.*.nama' => 'required|max:100',
                'mahasiswa.*.jurusan' => 'required|max:100',
                'mahasiswa.*.angkatan' => 'required|integer',
                'mahasiswa.*.status' => 'required|in:Aktif,Lulus,Cuti,Drop Out',
            ]);

            Mahasiswa::insert(array_map(function ($data) {
                return [
                    'nim' => $data['nim'],
                    'nama' => $data['nama'],
                    'jurusan' => $data['jurusan'],
                    'angkatan' => $data['angkatan'],
                    'status' => $data['status'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }, $validatedData['mahasiswa']));

            return response()->json([
                'message' => 'Mahasiswa created successfully with Eloquent',
                'data' => $validatedData['mahasiswa']
            ], 201);
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 422);
        }
    }

    public function bulkInsertMahasiswaWithQueryBuilder(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'mahasiswa' => 'required|array',
                'mahasiswa.*.nim' => 'required|unique:mahasiswa,nim|max:20',
                'mahasiswa.*.nama' => 'required|max:100',
                'mahasiswa.*.jurusan' => 'required|max:100',
                'mahasiswa.*.angkatan' => 'required|integer',
                'mahasiswa.*.status' => 'required|in:Aktif,Lulus,Cuti,Drop Out',
            ]);

            DB::table('mahasiswa')->insert(array_map(function ($data) {
                return [
                    'nim' => $data['nim'],
                    'nama' => $data['nama'],
                    'jurusan' => $data['jurusan'],
                    'angkatan' => $data['angkatan'],
                    'status' => $data['status'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }, $validatedData['mahasiswa']));

            return response()->json([
                'message' => 'Mahasiswa created successfully with Query Builder',
                'data' => $validatedData['mahasiswa']
            ], 201);
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 422);
        }
    }

    public function bulkInsertMahasiswaWithRawSQL(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'mahasiswa' => 'required|array',
                'mahasiswa.*.nim' => 'required|unique:mahasiswa,nim|max:20',
                'mahasiswa.*.nama' => 'required|max:100',
                'mahasiswa.*.jurusan' => 'required|max:100',
                'mahasiswa.*.angkatan' => 'required|integer',
                'mahasiswa.*.status' => 'required|in:Aktif,Lulus,Cuti,Drop Out',
            ]);

            $query = "INSERT INTO mahasiswa (nim, nama, jurusan, angkatan, status, created_at, updated_at) VALUES ";
            $values = [];
            $bindings = [];

            foreach ($validatedData['mahasiswa'] as $data) {
                $values[] = "(?, ?, ?, ?, ?, NOW(), NOW())";
                $bindings[] = $data['nim'];
                $bindings[] = $data['nama'];
                $bindings[] = $data['jurusan'];
                $bindings[] = $data['angkatan'];
                $bindings[] = $data['status'];
            }

            $query .= implode(", ", $values);
            DB::statement($query, $bindings);

            return response()->json([
                'message' => 'Mahasiswa created successfully with Raw SQL',
                'data' => $validatedData['mahasiswa']
            ], 201);
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 422);
        }
    }
}
