<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Keep the priority enum aligned with spec: low, medium, high, urgent
        // This migration intentionally preserves the original 'urgent' value
        // and does NOT change it to 'critical' (which was a prior mistake).
        DB::statement("ALTER TABLE tickets MODIFY COLUMN priority ENUM('low','medium','high','urgent') NOT NULL DEFAULT 'medium'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE tickets MODIFY COLUMN priority ENUM('low','medium','high','urgent') NOT NULL DEFAULT 'medium'");
    }
};
