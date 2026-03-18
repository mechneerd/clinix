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
        // Patient Insurance
        Schema::create('patient_insurance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->string('provider_name');
            $table->string('policy_number');
            $table->string('group_number')->nullable();
            $table->text('coverage_details')->nullable();
            $table->date('expiry_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Patient Documents
        Schema::create('patient_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->string('document_type')->comment('X-Ray, Lab Report, Scan, etc.');
            $table->string('name');
            $table->string('mime_type');
            $table->string('file_path');
            $table->integer('file_size');
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patient_documents');
        Schema::dropIfExists('patient_insurance');
    }
};
