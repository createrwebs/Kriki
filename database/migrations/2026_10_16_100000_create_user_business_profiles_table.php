<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_business_profiles', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('company_name', 255)->nullable();
            $table->text('business_description')->nullable();
            $table->decimal('website_budget_amount', 14, 2)->nullable();
            $table->char('website_budget_currency', 3)->default('THB');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_business_profiles');
    }
};
