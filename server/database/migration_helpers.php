<?php
declare(strict_types=1);

use Illuminate\Support\Facades\DB;

if (!function_exists('pg_create_extension')) {
    function pg_create_extension(string $name): void
    {
        if (config('database.skip_extensions')) {
            return;
        }

        DB::statement('CREATE EXTENSION IF NOT EXISTS "' . $name . '";');
    }
}

if (!function_exists('pg_drop_extension')) {
    function pg_drop_extension(string $name): void
    {
        if (config('database.skip_extensions')) {
            return;
        }

        DB::statement('DROP EXTENSION IFs EXISTS "' . $name . '";');
    }
}

if (!function_exists('alter_column')) {
    function alter_column(string $table, string $column, string $type): bool
    {
        return DB::statement('ALTER TABLE "' . $table . '" ALTER COLUMN "' . $column . '" TYPE ' . $type . ';');
    }
}

if (!function_exists('autogen_uuidv4')) {
    function autogen_uuidv4(string $table, string $column = 'id'): bool
    {
        return DB::statement('ALTER TABLE "' . $table . '" ALTER COLUMN "' . $column . '" SET DEFAULT uuid_generate_v4();');
    }
}
