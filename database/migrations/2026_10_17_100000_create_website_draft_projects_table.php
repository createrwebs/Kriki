<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('website_draft_projects', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('website_draft_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('draft_revision');
            $table->foreignId('lab_project_id')->constrained()->cascadeOnDelete();
            $table->char('blueprint_hash', 64);
            $table->unsignedInteger('renderer_version');
            $table->timestamps();
            $table->unique(['website_draft_id', 'draft_revision']);
            $table->unique('lab_project_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('website_draft_projects');
    }
};
