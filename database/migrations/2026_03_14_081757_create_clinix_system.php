<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. PACKAGES (Super Admin)
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('billing_cycle')->default('monthly');
            $table->integer('duration_days');
            $table->boolean('is_active')->default(true);
            $table->integer('max_clinics');
            $table->integer('max_labs');
            $table->integer('max_doctors');
            $table->integer('max_staff');
            $table->integer('max_patients_per_month')->nullable();
            $table->integer('storage_limit_mb')->nullable();
            $table->boolean('api_access')->default(false);
            $table->boolean('white_label')->default(false);
            $table->boolean('advanced_reporting')->default(false);
            $table->boolean('sms_notifications')->default(false);
            $table->boolean('telemedicine')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. CLINICS (Tenants)
        Schema::create('clinics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Clinic Admin
            $table->foreignId('package_id')->nullable()->constrained();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('email');
            $table->string('phone');
            $table->string('address');
            $table->string('city');
            $table->string('state');
            $table->string('country');
            $table->string('logo')->nullable();
            $table->json('theme_settings')->nullable();
            $table->timestamp('package_expires_at')->nullable();
            $table->string('status')->default('active'); // active, suspended, expired
            $table->timestamps();
            $table->softDeletes();
        });

        // 3. DEPARTMENTS
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('code')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 4. SPECIALTIES
        Schema::create('specialties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 5. STAFF (Doctors, Nurses, Managers, etc.)
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('clinic_id')->constrained()->onDelete('cascade');
            $table->foreignId('department_id')->nullable()->constrained();
            $table->string('employee_id')->nullable();
            $table->string('role'); // doctor, nurse, lab_manager, pharmacy_manager, reception_manager, lab_worker, pharmacy_worker, receptionist
            $table->string('qualification')->nullable();
            $table->string('license_number')->nullable();
            $table->date('joining_date');
            $table->decimal('consultation_fee', 10, 2)->nullable();
            $table->json('working_hours')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // 6. PATIENTS
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('patient_code')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable();
            $table->string('phone');
            $table->date('date_of_birth');
            $table->string('gender');
            $table->string('blood_group')->nullable();
            $table->text('address')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->json('allergies')->nullable();
            $table->json('medical_history')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // 7. CLINIC_PATIENT (Many-to-Many with details)
        Schema::create('clinic_patient', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->onDelete('cascade');
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->timestamp('registered_at');
            $table->string('registration_type')->default('walk_in'); // walk_in, online, referral
            $table->timestamps();
            $table->unique(['clinic_id', 'patient_id']);
        });

        // 8. APPOINTMENTS
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->onDelete('cascade');
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('staff')->onDelete('cascade');
            $table->date('appointment_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('type')->default('consultation');
            $table->string('status')->default('scheduled'); // scheduled, confirmed, completed, cancelled, no_show
            $table->text('chief_complaint')->nullable();
            $table->text('notes')->nullable();
            $table->decimal('fee', 10, 2)->nullable();
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 9. MEDICAL_RECORDS
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->constrained()->onDelete('cascade');
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('staff')->onDelete('cascade');
            $table->text('diagnosis');
            $table->text('symptoms')->nullable();
            $table->text('treatment_plan')->nullable();
            $table->text('notes')->nullable();
            $table->json('attachments')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 10. PRESCRIPTIONS
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medical_record_id')->constrained()->onDelete('cascade');
            $table->string('prescription_no')->unique();
            $table->date('prescribed_date');
            $table->text('notes')->nullable();
            $table->boolean('is_dispensed')->default(false);
            $table->timestamp('dispensed_at')->nullable();
            $table->timestamps();
        });

        // 11. MEDICINES
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('generic_name');
            $table->string('category');
            $table->string('dosage_form');
            $table->string('strength');
            $table->decimal('price', 10, 2);
            $table->integer('stock_quantity')->default(0);
            $table->integer('reorder_level')->default(10);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // 12. PRESCRIPTION_ITEMS
        Schema::create('prescription_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prescription_id')->constrained()->onDelete('cascade');
            $table->foreignId('medicine_id')->constrained()->onDelete('cascade');
            $table->string('dosage');
            $table->string('frequency');
            $table->string('duration');
            $table->text('instructions')->nullable();
            $table->integer('quantity');
            $table->timestamps();
        });

        // 13. LAB_TESTS
        Schema::create('lab_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('code')->nullable();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('normal_range')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // 14. LAB_ORDERS
        Schema::create('lab_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->onDelete('cascade');
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('staff')->onDelete('cascade');
            $table->foreignId('appointment_id')->nullable()->constrained();
            $table->string('order_no')->unique();
            $table->string('status')->default('pending'); // pending, in_progress, completed, cancelled
            $table->decimal('total_amount', 10, 2);
            $table->timestamps();
            $table->softDeletes();
        });

        // 15. LAB_ORDER_ITEMS
        Schema::create('lab_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('lab_test_id')->constrained()->onDelete('cascade');
            $table->string('result_value')->nullable();
            $table->string('result_status')->default('pending'); // pending, normal, abnormal, critical
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        // 16. INVOICES
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->onDelete('cascade');
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->string('invoice_no')->unique();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->string('status')->default('unpaid'); // unpaid, partial, paid, cancelled
            $table->timestamps();
            $table->softDeletes();
        });

        // 17. PAYMENTS
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('payment_method'); // cash, card, online, insurance
            $table->string('transaction_id')->nullable();
            $table->timestamps();
        });

        // 18. NOTIFICATIONS
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type');
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        // 19. ACTIVITY_LOGS
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->string('action');
            $table->string('entity_type');
            $table->unsignedBigInteger('entity_id');
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('lab_order_items');
        Schema::dropIfExists('lab_orders');
        Schema::dropIfExists('lab_tests');
        Schema::dropIfExists('prescription_items');
        Schema::dropIfExists('prescriptions');
        Schema::dropIfExists('medical_records');
        Schema::dropIfExists('appointments');
        Schema::dropIfExists('clinic_patient');
        Schema::dropIfExists('patients');
        Schema::dropIfExists('staff');
        Schema::dropIfExists('specialties');
        Schema::dropIfExists('departments');
        Schema::dropIfExists('clinics');
        Schema::dropIfExists('packages');
    }
};