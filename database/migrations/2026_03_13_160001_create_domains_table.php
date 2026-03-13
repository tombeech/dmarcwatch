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
        Schema::create('domains', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->string('rua_address')->unique();
            $table->text('dmarc_record')->nullable();
            $table->text('spf_record')->nullable();
            $table->json('dkim_selectors')->nullable();
            $table->string('dmarc_policy')->nullable();
            $table->string('spf_status')->nullable();
            $table->string('dkim_status')->nullable();
            $table->decimal('compliance_score', 5, 2)->nullable();
            $table->timestamp('last_dns_check_at')->nullable();
            $table->timestamp('next_dns_check_at')->nullable();
            $table->integer('dns_check_interval_minutes')->default(1440);
            $table->softDeletes();
            $table->timestamps();
            $table->unique(['team_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('domains');
    }
};
