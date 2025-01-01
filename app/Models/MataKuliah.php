<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MataKuliah extends Model
{
    use HasFactory;

    protected $table = 'mata_kuliah'; // Nama tabel (opsional jika berbeda)
    protected $fillable = ['kode_mk', 'nama_mk', 'sks'];

    public function krs(): HasMany
    {
        return $this->hasMany(KRS::class, 'id_mk'); // Foreign key pada tabel KRS
    }
}
