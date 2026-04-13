<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // MySQL: Modify the enum column to add 'critical'
        DB::statement("ALTER TABLE tickets MODIFY COLUMN priority ENUM('low','medium','high','critical') NOT NULL DEFAULT 'medium'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE tickets MODIFY COLUMN priority ENUM('low','medium','high','urgent') NOT NULL DEFAULT 'medium'");
    }
};
