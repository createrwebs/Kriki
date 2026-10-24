<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deployment_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deployment_id')->constrained()->cascadeOnDelete();
            $table->string('level', 16)->default('info');
            $table->text('message');
            $table->timestamp('timestamp')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->index(['deployment_id', 'id']);
        });

        Schema::create('app_secrets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_project_id')->constrained('lab_projects')->cascadeOnDelete();
            $table->string('key', 255);
            $table->text('value');
            $table->timestamps();

            $table->unique(['lab_project_id', 'key']);
        });

        Schema::create('api_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name', 255);
            $table->string('token_hash', 64)->unique();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->json('abilities')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'expires_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_tokens');
        Schema::dropIfExists('app_secrets');
        Schema::dropIfExists('deployment_logs');
    }
};
