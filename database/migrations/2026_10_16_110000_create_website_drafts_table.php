<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('website_drafts', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->uuid('client_request_id');
            $table->char('creation_hash', 64);
            $table->json('blueprint');
            $table->unsignedInteger('revision')->default(1);
            $table->timestamps();
            $table->unique(['user_id', 'client_request_id']);
            $table->index(['user_id', 'updated_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('website_drafts');
    }
};
