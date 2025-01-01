<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswa'; // Nama tabel (opsional jika berbeda)
    // Kolom yang dapat diisi melalui mass assignment
    protected $fillable = ['nim', 'nama', 'jurusan', 'angkatan', 'status'];

    // Relasi dengan tabel KRS
    public function krs(): HasMany
    {
        return $this->hasMany(KRS::class, 'id_mahasiswa');
    }
}
