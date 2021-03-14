<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlbumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('albums', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('library_id')
                ->references('id')->on('libraries')
                ->cascadeOnDelete();

            $table->text('title');

            // TODO format should end up as: artist-title-year
            $table->text('slug')->unique();

            $table->integer('year')->nullable();

            $table->timestampsTz();
        });
        autogen_uuidv4('albums');
        alter_column('albums', 'title', 'citext');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('albums');
    }
}
