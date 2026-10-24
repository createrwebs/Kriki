<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lab_operation_leases', function (Blueprint $table) {
            $table->id(); $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lab_project_id')->constrained()->cascadeOnDelete();
            $table->string('operation', 24); $table->uuid('operation_key');
            $table->string('status', 16)->default('active'); $table->timestamp('expires_at'); $table->timestamps();
            $table->unique(['lab_project_id', 'operation', 'operation_key'], 'lab_operation_key_unique');
            $table->index(['user_id', 'status', 'expires_at']);
        });
        Schema::create('lab_change_proposals', function (Blueprint $table) {
            $table->id(); $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lab_project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lab_module_installation_id')->constrained()->cascadeOnDelete();
            $table->string('kind', 32); $table->unsignedInteger('base_version');
            $table->json('payload'); $table->char('impact_hash', 64); $table->timestamp('expires_at');
            $table->string('state', 16)->default('prepared'); $table->json('receipt')->nullable();
            $table->timestamp('applied_at')->nullable(); $table->timestamps();
            $table->index(['lab_project_id', 'state']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('lab_change_proposals'); Schema::dropIfExists('lab_operation_leases');
    }
};
