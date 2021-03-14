<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeopleAlbumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('people_album', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('person_id')
                ->references('id')->on('people')
                ->cascadeOnDelete();

            $table->foreignUuid('album_id')
                ->references('id')->on('albums')
                ->cascadeOnDelete();

            $table->text('role');

            $table->timestampsTz();

            $table->unique(['person_id', 'album_id', 'role']);
        });
        autogen_uuidv4('people_album');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('people_album');
    }
}
