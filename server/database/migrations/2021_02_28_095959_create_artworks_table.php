<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArtworksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('artworks', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->text('basename')->unique();
            $table->text('mime');
            $table->integer('size');
            $table->integer('height');
            $table->integer('width');

            $table->nullableUuidMorphs('model');

            $table->timestampsTz();
        });
        autogen_uuidv4('artworks');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('artworks');
    }
}
