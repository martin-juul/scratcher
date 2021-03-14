<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;

class AddCitextExtensionToPostgresql extends Migration
{
    public function up(): void
    {
        pg_create_extension('citext');
    }

    public function down(): void
    {
        pg_drop_extension('citext');
    }
}
