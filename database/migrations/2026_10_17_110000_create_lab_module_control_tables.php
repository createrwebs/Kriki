<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lab_projects', function (Blueprint $table) { $table->unsignedInteger('modules_revision')->default(0); });
        Schema::create('lab_project_capabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_project_id')->constrained()->cascadeOnDelete();
            $table->string('key', 48);
            $table->boolean('enabled')->default(false);
            $table->timestamps();
            $table->unique(['lab_project_id', 'key']);
        });
        Schema::create('lab_module_installations', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lab_project_id')->constrained()->cascadeOnDelete();
            $table->string('key', 48);
            $table->string('supabase_project_ref', 80);
            $table->unsignedInteger('revision')->default(1);
            $table->unsignedInteger('schema_version')->default(0);
            $table->string('status', 16)->default('prepared');
            $table->boolean('enabled')->default(false);
            $table->json('config');
            $table->uuid('idempotency_key');
            $table->char('request_hash', 64);
            $table->timestamps();
            $table->unique(['lab_project_id', 'key']);
            $table->unique(['lab_project_id', 'idempotency_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lab_module_installations');
        Schema::dropIfExists('lab_project_capabilities');
        Schema::table('lab_projects', fn (Blueprint $table) => $table->dropColumn('modules_revision'));
    }
};
