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
        Schema::create('dmarc_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->foreignId('domain_id')->constrained()->cascadeOnDelete();
            $table->string('report_id');
            $table->string('reporter_org');
            $table->string('reporter_email')->nullable();
            $table->timestamp('date_begin');
            $table->timestamp('date_end');
            $table->string('domain_policy')->nullable();
            $table->string('subdomain_policy')->nullable();
            $table->integer('pct')->nullable();
            $table->unsignedInteger('total_messages')->default(0);
            $table->unsignedInteger('pass_count')->default(0);
            $table->unsignedInteger('fail_count')->default(0);
            $table->mediumText('raw_xml')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
            $table->unique(['domain_id', 'report_id', 'reporter_org']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dmarc_reports');
    }
};
