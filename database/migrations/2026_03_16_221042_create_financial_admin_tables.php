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
        // 1. Insurance Providers
        Schema::create('insurance_providers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->decimal('default_coverage_percent', 5, 2)->default(100);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        // 2. Taxes
        Schema::create('taxes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->decimal('rate_percent', 5, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 3. Discounts
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->enum('type', ['percentage', 'fixed']);
            $table->decimal('value', 15, 2);
            $table->date('valid_from')->nullable();
            $table->date('valid_until')->nullable();
            $table->timestamps();
        });

        // 4. Refunds
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->text('reason')->nullable();
            $table->enum('status', ['pending', 'completed', 'rejected'])->default('pending');
            $table->foreignId('processed_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // 5. SMS Logs
        Schema::create('sms_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->onDelete('cascade');
            $table->string('recipient_number');
            $table->text('message');
            $table->string('provider_reference')->nullable();
            $table->enum('status', ['pending', 'sent', 'failed', 'delivered'])->default('pending');
            $table->timestamps();
        });

        // 6. Email Logs
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->onDelete('cascade');
            $table->string('recipient_email');
            $table->string('subject');
            $table->text('content')->nullable();
            $table->enum('status', ['sent', 'failed'])->default('sent');
            $table->timestamps();
        });

        // 7. Announcements
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('content');
            $table->enum('target_role', ['all', 'doctor', 'nurse', 'staff', 'patient'])->default('all');
            $table->dateTime('expires_at')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // 8. Feedbacks
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->onDelete('cascade');
            $table->foreignId('patient_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('rating')->default(5); // 1-5
            $table->text('comment')->nullable();
            $table->enum('type', ['general', 'doctor', 'facility', 'app'])->default('general');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
        Schema::dropIfExists('announcements');
        Schema::dropIfExists('email_logs');
        Schema::dropIfExists('sms_logs');
        Schema::dropIfExists('refunds');
        Schema::dropIfExists('discounts');
        Schema::dropIfExists('taxes');
        Schema::dropIfExists('insurance_providers');
    }
};
