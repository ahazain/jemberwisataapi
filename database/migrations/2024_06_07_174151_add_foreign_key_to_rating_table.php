<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddForeignKeyToRatingTable extends Migration
{
    public function up()
    {
        Schema::table('rating', function (Blueprint $table) {
            $table->unsignedBigInteger('wisata_id')->after('id')->nullable()->default(1);;
            $table->foreign('wisata_id')->references('id')->on('wisata');
        });
    }

    public function down()
    {
        Schema::table('rating', function (Blueprint $table) {
            $table->dropForeign(['wisata_id']);
            $table->dropColumn('wisata_id');
        });
    }
}
