<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lab_forms', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->foreignId('lab_project_id')->constrained()->restrictOnDelete();
            $table->string('supabase_project_ref', 64);
            $table->string('template', 40)->default('table_booking');
            $table->string('title', 120);
            $table->string('status', 16)->default('draft');
            $table->unsignedInteger('active_version')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'lab_project_id']);
        });
        Schema::create('lab_form_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_form_id')->constrained()->restrictOnDelete();
            $table->unsignedInteger('version');
            $table->json('definition');
            $table->timestamps();
            $table->unique(['lab_form_id', 'version']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lab_form_versions');
        Schema::dropIfExists('lab_forms');
    }
};
