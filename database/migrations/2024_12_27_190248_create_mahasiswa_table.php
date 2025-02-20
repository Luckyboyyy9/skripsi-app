<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->bigIncrements('nim'); // Menggunakan auto-increment
            $table->string('nama');
            $table->string('jurusan');
            $table->integer('angkatan');
            $table->enum('status', ['Aktif', 'Lulus', 'Cuti', 'Drop Out']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswa');
    }
};
