<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('albums', function (Blueprint $table) {
            $table->index('library_id');
        });

        Schema::table('tracks', function (Blueprint $table) {
            $table->index('album_id');
        });

        Schema::table('people_album', function (Blueprint $table) {
            $table->index(['person_id', 'album_id']);
        });

        Schema::table('people_track', function (Blueprint $table) {
            $table->index(['person_id', 'track_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        //
    }
}
