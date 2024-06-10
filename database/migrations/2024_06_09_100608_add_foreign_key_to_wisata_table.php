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
            $table->unsignedBigInteger('id_admin')->nullable();
            $table->foreign('id_admin')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wisata', function (Blueprint $table) {
            $table->dropForeign(['id_admin']);
            $table->dropColumn('id_admin');
        });
    }
};
