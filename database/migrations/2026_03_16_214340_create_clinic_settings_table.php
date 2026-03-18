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
        Schema::create('clinic_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->onDelete('cascade');
            $table->string('key');
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, json, boolean, etc.
            $table->timestamps();
            $table->unique(['clinic_id', 'key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clinic_settings');
    }
};
