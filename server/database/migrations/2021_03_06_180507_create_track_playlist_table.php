<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrackPlaylistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('track_playlist', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('track_id')
                ->references('id')->on('tracks')
                ->cascadeOnDelete()
                ->index();

            $table->foreignUuid('playlist_id')
                ->references('id')->on('playlists')
                ->cascadeOnDelete();

            $table->integer('sort');

            $table->index(['track_id', 'playlist_id']);

            $table->timestampsTz();
        });
        autogen_uuidv4('track_playlist');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('track_playlist');
    }
}
