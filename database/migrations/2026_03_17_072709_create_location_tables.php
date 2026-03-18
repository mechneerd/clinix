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
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('subregions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('region_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('region_id')->constrained()->onDelete('cascade');
            $table->foreignId('subregion_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('areas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('city_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->timestamps();
        });

        // Add foreign keys to patients and users
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('region_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('subregion_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('city_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('area_id')->nullable()->constrained()->onDelete('set null');
        });

        Schema::table('patients', function (Blueprint $table) {
            $table->foreignId('region_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('subregion_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('city_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('area_id')->nullable()->constrained()->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropForeign(['region_id']);
            $table->dropForeign(['subregion_id']);
            $table->dropForeign(['city_id']);
            $table->dropForeign(['area_id']);
            $table->dropColumn(['region_id', 'subregion_id', 'city_id', 'area_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['region_id']);
            $table->dropForeign(['subregion_id']);
            $table->dropForeign(['city_id']);
            $table->dropForeign(['area_id']);
            $table->dropColumn(['region_id', 'subregion_id', 'city_id', 'area_id']);
        });

        Schema::dropIfExists('areas');
        Schema::dropIfExists('cities');
        Schema::dropIfExists('subregions');
        Schema::dropIfExists('regions');
    }
};
