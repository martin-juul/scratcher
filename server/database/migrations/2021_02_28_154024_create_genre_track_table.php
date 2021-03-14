<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGenreTrackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('genre_track', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('genre_id')
                ->references('id')->on('genres')
                ->cascadeOnDelete();

            $table->foreignUuid('track_id')
                ->references('id')->on('tracks')
                ->cascadeOnDelete();

            $table->timestampsTz();
        });
        autogen_uuidv4('genre_track');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('genre_track');
    }
}
