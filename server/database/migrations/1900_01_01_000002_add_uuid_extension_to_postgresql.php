<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;

class AddUuidExtensionToPostgresql extends Migration
{
    public function up(): void
    {
        pg_create_extension('uuid-ossp');
    }

    public function down(): void
    {
        pg_drop_extension('uuid-ossp');
    }
}
