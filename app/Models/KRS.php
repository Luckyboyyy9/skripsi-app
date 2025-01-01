<?php

namespace App\Models;

use App\Models\Mahasiswa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KRS extends Model
{
    use HasFactory;

    protected $table = 'krs';
    protected $fillable = ['id_mahasiswa', 'id_mk'];

    // Relasi ke Mahasiswa: KRS milik satu mahasiswa
    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa');
    }

    // Relasi ke Mata Kuliah: KRS milik satu mata kuliah
    public function mataKuliah(): BelongsTo
    {
        return $this->belongsTo(MataKuliah::class, 'id_mk');
    }
}
