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
        Schema::create('report_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dmarc_report_id')->constrained()->cascadeOnDelete();
            $table->string('source_ip');
            $table->unsignedInteger('count')->default(1);
            $table->string('disposition')->nullable();
            $table->string('dkim_result')->nullable();
            $table->string('spf_result')->nullable();
            $table->string('dkim_domain')->nullable();
            $table->string('spf_domain')->nullable();
            $table->boolean('dkim_aligned')->default(false);
            $table->boolean('spf_aligned')->default(false);
            $table->string('header_from')->nullable();
            $table->string('envelope_from')->nullable();
            $table->foreignId('sending_source_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_records');
    }
};
