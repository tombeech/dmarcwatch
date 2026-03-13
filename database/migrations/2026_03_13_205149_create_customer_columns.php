<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Cashier columns already exist on the teams table (created in create_teams_table migration).
        // This migration is intentionally a no-op.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op — see up().
    }
};
