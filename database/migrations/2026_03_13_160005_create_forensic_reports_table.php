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
        Schema::create('forensic_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->foreignId('domain_id')->constrained()->cascadeOnDelete();
            $table->string('source_ip')->nullable();
            $table->timestamp('arrival_date')->nullable();
            $table->string('auth_failure')->nullable();
            $table->string('delivery_result')->nullable();
            $table->string('dkim_domain')->nullable();
            $table->string('dkim_selector')->nullable();
            $table->string('feedback_type')->nullable();
            $table->string('original_mail_from')->nullable();
            $table->string('original_rcpt_to')->nullable();
            $table->string('subject')->nullable();
            $table->foreignId('sending_source_id')->nullable()->constrained()->nullOnDelete();
            $table->text('raw_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forensic_reports');
    }
};
