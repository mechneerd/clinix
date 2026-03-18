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
        // 1. Pricing Plans (Packages 2.0)
        Schema::create('pricing_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('monthly_price', 15, 2);
            $table->decimal('yearly_price', 15, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Plan Features
        Schema::create('plan_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pricing_plan_id')->constrained()->onDelete('cascade');
            $table->string('feature_name');
            $table->string('feature_slug');
            $table->string('limit_value')->nullable(); // e.g. "10", "unlimited"
            $table->timestamps();
        });

        // 3. Deep Audit Logs
        Schema::create('system_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('event'); // Created, Updated, Deleted, Login, etc.
            $table->string('auditable_type');
            $table->unsignedBigInteger('auditable_id');
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('url')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_audit_logs');
        Schema::dropIfExists('plan_features');
        Schema::dropIfExists('pricing_plans');
    }
};
