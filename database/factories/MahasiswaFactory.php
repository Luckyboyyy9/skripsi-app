<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mahasiswa>
 */
class MahasiswaFactory extends Factory
{
    protected static $increment = 1; // Variabel untuk auto-increment NIM

    public function definition()
    {
        return [
            'nim' => static::$increment++, // Menggunakan angka bertambah (1, 2, 3, ...)
            'nama' => fake()->name(),
            'jurusan' => fake()->randomElement(['Teknik Informatika', 'Teknik Elektro']),
            'angkatan' => fake()->randomElement([2019, 2020, 2021, 2022]),
            'status' => fake()->randomElement(['Aktif', 'Lulus', 'Cuti', 'Drop Out']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
