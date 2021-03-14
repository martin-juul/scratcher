<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonalAccessTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuidMorphs('tokenable');

            $table->text('name');
            $table->text('token')->unique();
            $table->text('abilities')->nullable();
            $table->timestampTz('last_used_at')->nullable();

            $table->timestampsTz();
        });
        autogen_uuidv4('personal_access_tokens');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_access_tokens');
    }
}
