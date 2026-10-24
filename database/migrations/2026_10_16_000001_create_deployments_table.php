<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deployments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_project_id')->constrained('lab_projects')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('provider', 32)->default('local');
            $table->string('provider_deployment_id')->nullable();
            $table->unsignedInteger('version')->default(1);
            $table->string('strategy', 32)->default('immediate');
            $table->string('region', 32)->nullable();
            $table->string('vmtype', 64)->nullable();
            $table->string('hostname')->nullable();
            $table->unsignedInteger('min_instances')->nullable();
            $table->unsignedInteger('max_instances')->nullable();
            $table->text('description')->nullable();
            $table->string('status', 32)->default('pending')->index();
            $table->string('url')->nullable();
            $table->string('artifact_path')->nullable();
            $table->unsignedBigInteger('artifact_size')->default(0);
            $table->text('last_error')->nullable();
            $table->foreignId('rollback_from_deployment_id')->nullable()->constrained('deployments')->nullOnDelete();
            $table->foreignId('rollback_to_deployment_id')->nullable()->constrained('deployments')->nullOnDelete();
            $table->timestamp('deployed_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->timestamps();

            $table->index(['lab_project_id', 'status']);
            $table->index(['lab_project_id', 'version']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deployments');
    }
};
