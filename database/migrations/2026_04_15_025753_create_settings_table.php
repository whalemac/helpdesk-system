<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('value')->nullable();
            $table->timestamps();
        });

        // Seed defaults
        DB::table('settings')->insert([
            ['key' => 'registration_enabled', 'value' => '1', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'default_ticket_priority', 'value' => 'medium', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'default_ticket_status', 'value' => 'open', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
