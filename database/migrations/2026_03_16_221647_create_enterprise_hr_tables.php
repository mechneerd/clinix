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
        // 1. Job Positions
        Schema::create('job_positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('min_salary', 15, 2)->nullable();
            $table->decimal('max_salary', 15, 2)->nullable();
            $table->timestamps();
        });

        // 2. Employee Leaves
        Schema::create('employee_leaves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('staff')->onDelete('cascade');
            $table->string('leave_type'); // Sick, Casual, Annual
            $table->date('start_date');
            $table->date('end_date');
            $table->string('reason')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        // 3. Attendances
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('staff')->onDelete('cascade');
            $table->date('date');
            $table->time('clock_in')->nullable();
            $table->time('clock_out')->nullable();
            $table->string('status')->default('present'); // present, late, absent
            $table->timestamps();
        });

        // 4. Payrolls
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('staff')->onDelete('cascade');
            $table->string('month_year'); // e.g., "March 2026"
            $table->decimal('basic_salary', 15, 2);
            $table->decimal('allowances', 15, 2)->default(0);
            $table->decimal('deductions', 15, 2)->default(0);
            $table->decimal('net_salary', 15, 2);
            $table->enum('payment_status', ['unpaid', 'paid'])->default('unpaid');
            $table->date('paid_at')->nullable();
            $table->timestamps();
        });

        // 5. Performance Reviews
        Schema::create('performance_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('staff')->onDelete('cascade');
            $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade');
            $table->date('review_date');
            $table->integer('rating'); // 1-10
            $table->text('comments')->nullable();
            $table->timestamps();
        });

        // 6. Warehouses
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('location')->nullable();
            $table->timestamps();
        });

        // 7. Requisitions (Internal stock requests)
        Schema::create('requisitions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->onDelete('cascade');
            $table->foreignId('requesting_department_id')->constrained('departments')->onDelete('cascade');
            $table->string('requisition_number')->unique();
            $table->enum('status', ['pending', 'approved', 'fulfilled', 'rejected'])->default('pending');
            $table->foreignId('requested_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // 8. Requisition Items
        Schema::create('requisition_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requisition_id')->constrained()->onDelete('cascade');
            $table->string('item_type'); // Medicine, Consumable
            $table->unsignedBigInteger('item_id');
            $table->integer('quantity_requested');
            $table->integer('quantity_fulfilled')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requisition_items');
        Schema::dropIfExists('requisitions');
        Schema::dropIfExists('warehouses');
        Schema::dropIfExists('performance_reviews');
        Schema::dropIfExists('payrolls');
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('employee_leaves');
        Schema::dropIfExists('job_positions');
    }
};
