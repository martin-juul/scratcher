<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLibrariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('libraries', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->text('name');
            $table->text('slug')->unique();
            $table->text('path');

            $table->timestampsTz();
        });
        autogen_uuidv4('libraries');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('libraries');
    }
}
