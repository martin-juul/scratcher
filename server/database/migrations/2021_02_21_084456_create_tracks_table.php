<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTracksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('tracks', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('album_id')
                ->references('id')->on('albums')
                ->cascadeOnDelete();

            $table->text('title');
            $table->text('sha256')->unique();
            $table->text('path')->unique();
            $table->text('file_format')->nullable();
            $table->text('mime_type')->nullable();
            $table->text('isrc')->nullable();
            $table->integer('file_size')->nullable();
            $table->integer('bitrate')->nullable();
            $table->integer('length')->nullable();
            $table->integer('track_number')->nullable();

            $table->timestampsTz();
        });
        autogen_uuidv4('tracks');
        alter_column('tracks', 'title', 'citext');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('tracks');
    }
}
