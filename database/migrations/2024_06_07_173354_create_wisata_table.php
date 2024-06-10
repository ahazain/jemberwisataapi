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
        Schema::create('wisata', function (Blueprint $table) {
            $table->id();
            $table->string('nama_wisata', 100)->collation('utf8mb4_general_ci');
            $table->string('gambar', 500)->collation('utf8mb4_general_ci');
            $table->text('deskripsi')->nullable()->collation('utf8mb4_general_ci');
            $table->string('alamat', 255)->nullable()->collation('utf8mb4_general_ci');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wisata');
    }
};
