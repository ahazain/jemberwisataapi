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
        Schema::table('wisata', function (Blueprint $table) {
              // Menambah kolom latitude
              $table->double('latitude')->nullable();

              // Menambah kolom longitude
              $table->double('longitude')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wisata', function (Blueprint $table) {
              // Menghapus kolom latitude
              $table->dropColumn('latitude');

              // Menghapus kolom longitude
              $table->dropColumn('longitude');
        });
    }
};
