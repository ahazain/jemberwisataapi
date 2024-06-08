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
            $table->unsignedBigInteger('jenis_wisata_id')->after('id')->nullable();
            $table->foreign('jenis_wisata_id')->references('id')->on('jenis_wisata');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wisata', function (Blueprint $table) {
            $table->dropForeign(['jenis_wisata_id']);
            $table->dropColumn('jenis_wisata_id');
        });
    }
};
