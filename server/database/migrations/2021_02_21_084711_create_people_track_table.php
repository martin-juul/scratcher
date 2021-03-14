<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeopleTrackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('people_track', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('person_id')
                ->references('id')->on('people')
                ->cascadeOnDelete();

            $table->foreignUuid('track_id')
                ->references('id')->on('tracks')
                ->cascadeOnDelete();

            $table->text('role');

            $table->timestampsTz();

            $table->unique(['person_id', 'track_id', 'role']);
        });
        autogen_uuidv4('people_track');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('people_track');
    }
}
