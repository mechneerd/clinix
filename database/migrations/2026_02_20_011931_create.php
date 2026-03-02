<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ============================================
        // 1. SUBSCRIPTION & BILLING MODULE
        // ============================================
        
        // Subscription Tiers/Packages (Free, Basic, Advanced, Custom)
        Schema::create('subscription_tiers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Free, Basic, Advanced
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('monthly_price', 10, 2)->default(0);
            $table->decimal('yearly_price', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            
            // Module Limits
            $table->integer('max_clinics')->default(1);
            $table->integer('max_labs')->default(0);
            $table->integer('max_pharmacies')->default(0);
            $table->integer('max_doctors')->default(1);
            $table->integer('max_nurses')->default(0);
            $table->integer('max_admins')->default(1);
            $table->integer('max_managers')->default(0);
            $table->integer('max_staff_total')->default(2); // Total staff limit
            
            // Feature Flags
            $table->boolean('has_sms_notifications')->default(false);
            $table->boolean('has_email_notifications')->default(true);
            $table->boolean('has_push_notifications')->default(false);
            $table->boolean('has_advanced_reports')->default(false);
            $table->boolean('has_api_access')->default(false);
            $table->boolean('has_custom_branding')->default(false);
            $table->boolean('has_priority_support')->default(false);
            
            // Trial
            $table->integer('trial_days')->default(14);
            
            $table->timestamps();
            $table->softDeletes();
        });

        // Available Modules that can be assigned to tiers
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Appointments, Lab, Pharmacy, etc.
            $table->string('code')->unique(); // unique identifier
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->boolean('is_core')->default(false); // Core modules can't be disabled
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Tier-Module relationship (which modules available in which tier)
        Schema::create('subscription_tier_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_tier_id')->constrained()->onDelete('cascade');
            $table->foreignId('module_id')->constrained()->onDelete('cascade');
            $table->json('settings')->nullable(); // Module-specific settings per tier
            $table->timestamps();
            
            $table->unique(['subscription_tier_id', 'module_id']);
        });

        // User Subscriptions (extends Laravel Cashier's subscriptions table concept)
        Schema::create('user_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('subscription_tier_id')->constrained();
            $table->string('stripe_subscription_id')->nullable()->unique();
            $table->string('status')->default('trial'); // trial, active, cancelled, expired, suspended
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->boolean('auto_renew')->default(true);
            $table->string('billing_cycle')->default('monthly'); // monthly, yearly
            $table->json('custom_limits')->nullable(); // Override tier limits
            $table->timestamps();
            
            $table->index(['user_id', 'status']);
        });

        // Subscription Usage Tracking (current usage vs limits)
        Schema::create('subscription_usage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_subscription_id')->constrained('user_subscriptions')->onDelete('cascade');
            $table->string('resource_type'); // clinic, lab, doctor, etc.
            $table->integer('used_count')->default(0);
            $table->integer('limit_count')->default(0);
            $table->timestamps();
            
            $table->unique(['user_subscription_id', 'resource_type']);
        });

        // Payments/Invoices
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('user_subscription_id')->nullable()->constrained('user_subscriptions');
            $table->string('stripe_payment_intent_id')->nullable();
            $table->string('stripe_invoice_id')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->string('status'); // pending, completed, failed, refunded
            $table->string('payment_method')->nullable(); // card, upi, etc.
            $table->timestamp('paid_at')->nullable();
            $table->text('failure_reason')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'status']);
        });

        // ============================================
        // 2. CLINIC & ORGANIZATION MODULE
        // ============================================

        // Clinics (Multi-tenant support)
        Schema::create('clinics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade'); // Admin who created
            $table->foreignId('user_subscription_id')->constrained('user_subscriptions')->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            
            // Branding
            $table->string('logo')->nullable();
            $table->string('banner_image')->nullable();
            $table->string('primary_color')->nullable()->default('#3B82F6');
            $table->string('secondary_color')->nullable()->default('#10B981');
            
            // Contact
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('alternate_phone')->nullable();
            $table->string('website')->nullable();
            
            // Address
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('postal_code')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            // Settings
            $table->json('working_hours')->nullable(); // {"monday": {"open": "09:00", "close": "18:00"}}
            $table->integer('appointment_duration')->default(30); // minutes
            $table->boolean('is_active')->default(true);
            $table->boolean('is_verified')->default(false); // Verified by super admin
            $table->boolean('is_featured')->default(false);
            $table->timestamp('featured_until')->nullable();
            
            // Public visibility
            $table->boolean('show_on_public_listing')->default(true);
            $table->integer('rating')->default(0);
            $table->integer('review_count')->default(0);
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['is_active', 'is_verified', 'show_on_public_listing']);
            $table->index(['latitude', 'longitude']);
        });

        // Departments within Clinic
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('code')->nullable(); // Internal code
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique(['clinic_id', 'name']);
        });

        // Labs
        Schema::create('labs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_subscription_id')->constrained('user_subscriptions')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->json('working_hours')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // Pharmacies
        Schema::create('pharmacies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_subscription_id')->constrained('user_subscriptions')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->json('working_hours')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // ============================================
        // 3. PHARMACY MODULE (MOVED UP - before prescriptions)
        // ============================================

        // Medicine Categories
        Schema::create('medicine_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pharmacy_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Medicines/Inventory Master
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pharmacy_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained('medicine_categories');
            
            // Basic Info
            $table->string('name');
            $table->string('generic_name')->nullable();
            $table->string('brand_name')->nullable();
            $table->string('code')->nullable(); // SKU/Internal code
            $table->text('description')->nullable();
            $table->text('composition')->nullable();
            
            // Classification
            $table->enum('type', ['tablet', 'capsule', 'syrup', 'injection', 'ointment', 'drops', 'inhaler', 'powder', 'consumable', 'other'])->default('tablet');
            $table->enum('category_type', ['medicine', 'consumable', 'equipment', 'implant'])->default('medicine');
            
            // Dosage Info
            $table->string('strength')->nullable(); // 500mg, 10ml
            $table->string('unit')->nullable(); // mg, ml, units
            $table->string('manufacturer')->nullable();
            $table->string('supplier')->nullable();
            
            // Inventory
            $table->integer('current_stock')->default(0);
            $table->integer('reorder_level')->default(10);
            $table->integer('reorder_quantity')->default(50);
            $table->string('storage_location')->nullable();
            $table->enum('storage_condition', ['room_temp', 'refrigerated', 'frozen'])->default('room_temp');
            
            // Pricing
            $table->decimal('purchase_price', 10, 2)->default(0);
            $table->decimal('selling_price', 10, 2)->default(0);
            $table->decimal('mrp', 10, 2)->default(0); // Maximum Retail Price
            $table->decimal('tax_percentage', 5, 2)->default(0);
            
            // Prescription & Regulatory
            $table->boolean('prescription_required')->default(true);
            $table->string('schedule_type')->nullable(); // H, X, etc.
            $table->date('expiry_date')->nullable();
            $table->string('batch_number')->nullable();
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['pharmacy_id', 'is_active']);
            $table->index(['pharmacy_id', 'current_stock', 'reorder_level']);
        });

        // ============================================
        // 4. USER MANAGEMENT & ROLES MODULE
        // ============================================

        // Extended User Profiles (additional fields for users table)
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('user_type', ['super_admin', 'admin', 'doctor', 'nurse', 'lab_technician', 'pharmacist', 'manager', 'receptionist', 'patient'])->default('patient');
            $table->string('avatar')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other', 'prefer_not_to_say'])->nullable();
            $table->string('blood_group')->nullable();
            $table->text('address')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->text('medical_history')->nullable(); // For patients
            $table->text('allergies')->nullable();
            $table->json('preferences')->nullable(); // Notification preferences, etc.
            $table->string('locale')->default('en');
            $table->string('timezone')->default('UTC');
            $table->timestamps();
        });

        // Staff Profiles (for doctors, nurses, etc.)
        Schema::create('staff_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('clinic_id')->constrained()->onDelete('cascade');
            $table->foreignId('department_id')->nullable()->constrained();
            
            // Professional Info
            $table->string('employee_id')->nullable();
            $table->string('qualification')->nullable(); // MD, MBBS, BSN, etc.
            $table->text('specializations')->nullable();
            $table->integer('experience_years')->default(0);
            $table->string('registration_number')->nullable(); // Medical council reg
            $table->string('license_number')->nullable();
            $table->date('license_expiry')->nullable();
            
            // For Doctors
            $table->text('biography')->nullable();
            $table->json('education')->nullable(); // [{"degree": "MD", "institution": "...", "year": 2010}]
            $table->json('awards')->nullable();
            $table->decimal('consultation_fee', 10, 2)->default(0);
            $table->integer('follow_up_days')->default(7); // Free follow-up within days
            $table->boolean('is_available_for_online')->default(false);
            
            // Employment
            $table->date('joining_date')->nullable();
            $table->date('leaving_date')->nullable();
            $table->enum('employment_type', ['full_time', 'part_time', 'contract', 'visiting'])->default('full_time');
            $table->decimal('salary', 10, 2)->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });

        // Role-Permission (using Spatie, but custom pivot for clinic-specific roles)
        Schema::create('clinic_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('guard_name')->default('web');
            $table->json('permissions')->nullable(); // Custom permissions JSON
            $table->text('description')->nullable();
            $table->boolean('is_system_role')->default(false); // Can't delete system roles
            $table->timestamps();
            
            $table->unique(['clinic_id', 'name']);
        });

        // User-Clinic-Role Assignment (User can have different roles in different clinics)
        Schema::create('clinic_user_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('clinic_id')->constrained()->onDelete('cascade');
            $table->foreignId('clinic_role_id')->constrained('clinic_roles')->onDelete('cascade');
            $table->foreignId('assigned_by')->constrained('users');
            $table->timestamp('expires_at')->nullable(); // Temporary access
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique(['user_id', 'clinic_id', 'clinic_role_id']);
        });

        // ============================================
        // 5. APPOINTMENT MODULE
        // ============================================

        // Appointment Slots/Schedules
        Schema::create('doctor_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('clinic_id')->constrained()->onDelete('cascade');
            $table->enum('day_of_week', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']);
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('slot_duration')->default(30); // minutes
            $table->integer('buffer_time')->default(5); // minutes between appointments
            $table->integer('max_patients')->default(10);
            $table->boolean('is_available')->default(true);
            $table->timestamps();
            
            $table->index(['doctor_id', 'clinic_id', 'day_of_week']);
        });

        // Appointments
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('appointment_number')->unique(); // CLINIC-YYYYMMDD-XXXX format
            
            // Relations
            $table->foreignId('clinic_id')->constrained();
            $table->foreignId('doctor_id')->constrained('users');
            $table->foreignId('patient_id')->constrained('users');
            $table->foreignId('department_id')->nullable()->constrained();
            $table->foreignId('booked_by')->nullable()->constrained('users'); // Receptionist or self
            
            // Timing
            $table->date('appointment_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('duration')->default(30);
            
            // Type & Status
            $table->enum('type', ['in_person', 'online', 'emergency', 'follow_up'])->default('in_person');
            $table->enum('status', [
                'pending', 'confirmed', 'checked_in', 'in_progress', 
                'completed', 'cancelled', 'no_show', 'rescheduled'
            ])->default('pending');
            $table->enum('payment_status', ['pending', 'paid', 'refunded', 'waived'])->default('pending');
            
            // Details
            $table->text('symptoms')->nullable();
            $table->text('notes')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->foreignId('cancelled_by')->nullable()->constrained('users');
            $table->timestamp('cancelled_at')->nullable();
            
            // Previous appointment (for follow-ups)
            $table->foreignId('previous_appointment_id')->nullable()->constrained('appointments');
            
            // Reminders
            $table->timestamp('reminder_sent_at')->nullable();
            $table->timestamp('sms_reminder_sent_at')->nullable();
            
            // Online consultation
            $table->string('meeting_link')->nullable();
            $table->string('meeting_id')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['clinic_id', 'appointment_date']);
            $table->index(['doctor_id', 'appointment_date', 'status']);
            $table->index(['patient_id', 'status']);
        });

        // Appointment Status History
        Schema::create('appointment_status_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->constrained()->onDelete('cascade');
            $table->string('from_status');
            $table->string('to_status');
            $table->text('remarks')->nullable();
            $table->foreignId('changed_by')->constrained('users');
            $table->timestamp('changed_at');
        });

        // ============================================
        // 6. MEDICAL RECORDS MODULE
        // ============================================

        // Patient Visits/Encounters
        Schema::create('patient_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->constrained()->onDelete('cascade');
            $table->foreignId('clinic_id')->constrained();
            $table->foreignId('patient_id')->constrained('users');
            $table->foreignId('doctor_id')->constrained('users');
            
            // Vitals
            $table->decimal('height', 5, 2)->nullable(); // cm
            $table->decimal('weight', 5, 2)->nullable(); // kg
            $table->decimal('bmi', 4, 2)->nullable();
            $table->integer('blood_pressure_systolic')->nullable();
            $table->integer('blood_pressure_diastolic')->nullable();
            $table->integer('pulse_rate')->nullable();
            $table->decimal('temperature', 4, 2)->nullable(); // celsius
            $table->integer('respiratory_rate')->nullable();
            $table->integer('oxygen_saturation')->nullable(); // SpO2
            
            // Examination
            $table->text('chief_complaints')->nullable();
            $table->text('presenting_history')->nullable();
            $table->text('past_history')->nullable();
            $table->text('family_history')->nullable();
            $table->text('personal_history')->nullable();
            $table->text('allergies')->nullable();
            $table->text('examination_findings')->nullable();
            $table->text('diagnosis')->nullable();
            $table->text('differential_diagnosis')->nullable();
            
            // ICD Codes (International Classification of Diseases)
            $table->json('icd_codes')->nullable(); // ["A00", "B99"]
            
            $table->enum('status', ['in_progress', 'completed', 'referred'])->default('in_progress');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        // Prescriptions
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_id')->constrained('patient_visits')->onDelete('cascade');
            $table->foreignId('clinic_id')->constrained();
            $table->foreignId('patient_id')->constrained('users');
            $table->foreignId('doctor_id')->constrained('users');
            $table->string('prescription_number')->unique();
            
            $table->text('clinical_notes')->nullable();
            $table->text('investigations_advised')->nullable();
            $table->text('special_instructions')->nullable();
            $table->text('dietary_advice')->nullable();
            $table->text('follow_up_instructions')->nullable();
            $table->date('follow_up_date')->nullable();
            $table->integer('follow_up_days')->nullable();
            
            $table->boolean('is_finalized')->default(false);
            $table->timestamp('finalized_at')->nullable();
            $table->foreignId('finalized_by')->nullable()->constrained('users');
            
            $table->timestamps();
            $table->softDeletes();
        });

        // Prescription Items/Medicines
        Schema::create('prescription_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prescription_id')->constrained()->onDelete('cascade');
            $table->foreignId('medicine_id')->nullable()->constrained('medicines'); // If from inventory
            
            // Medicine Details (denormalized for history)
            $table->string('medicine_name');
            $table->string('medicine_type'); // tablet, syrup, injection, etc.
            $table->string('generic_name')->nullable();
            $table->string('strength')->nullable(); // 500mg, 10ml, etc.
            
            // Dosage
            $table->string('dosage'); // 1 tablet, 5ml, etc.
            $table->string('frequency'); // OD, BD, TDS, QID, SOS, etc.
            $table->string('duration'); // 5 days, 1 week, etc.
            $table->string('route')->nullable(); // oral, iv, im, topical, etc.
            $table->text('instructions')->nullable(); // Before food, after food, etc.
            $table->integer('quantity')->default(1);
            
            // For pharmacy
            $table->enum('status', ['pending', 'dispensed', 'partially_dispensed', 'cancelled'])->default('pending');
            $table->foreignId('dispensed_by')->nullable()->constrained('users');
            $table->timestamp('dispensed_at')->nullable();
            
            $table->timestamps();
        });

        // ============================================
        // 7. LABORATORY MODULE
        // ============================================

        // Test Categories
        Schema::create('test_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Lab Tests Master
        Schema::create('lab_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained('test_categories');
            
            $table->string('name');
            $table->string('code')->nullable(); // Internal code
            $table->text('description')->nullable();
            $table->text('preparation_instructions')->nullable(); // Fasting required, etc.
            
            // Pricing
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('cost_price', 10, 2)->default(0); // For lab accounting
            
            // Sample Details
            $table->string('sample_type')->nullable(); // Blood, Urine, Stool, etc.
            $table->string('sample_volume')->nullable(); // 2ml, 5ml, etc.
            $table->integer('default_turnaround_time')->default(24); // hours
            
            // Result Configuration
            $table->enum('result_type', ['numeric', 'text', 'boolean', 'multiple_choice', 'file'])->default('text');
            $table->string('unit')->nullable(); // mg/dL, mmol/L, etc.
            $table->json('reference_range')->nullable(); // {"min": 70, "max": 110, "unit": "mg/dL"}
            $table->text('normal_values')->nullable();
            $table->text('abnormal_interpretation')->nullable();
            
            // Method & Equipment
            $table->string('test_method')->nullable();
            $table->string('equipment_required')->nullable();
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Lab Orders/Requests
        Schema::create('lab_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('lab_id')->constrained();
            $table->foreignId('clinic_id')->constrained();
            $table->foreignId('patient_id')->constrained('users');
            $table->foreignId('doctor_id')->constrained('users'); // Requesting doctor
            $table->foreignId('visit_id')->nullable()->constrained('patient_visits');
            
            // Order Details
            $table->enum('priority', ['routine', 'urgent', 'stat'])->default('routine');
            $table->text('clinical_notes')->nullable();
            $table->text('diagnosis')->nullable();
            
            // Status
            $table->enum('status', [
                'ordered', 'sample_collected', 'sample_received', 
                'in_progress', 'completed', 'cancelled', 'rejected'
            ])->default('ordered');
            
            // Financial
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('net_amount', 10, 2)->default(0);
            $table->enum('payment_status', ['pending', 'paid', 'waived'])->default('pending');
            
            // Timestamps
            $table->timestamp('ordered_at')->nullable();
            $table->timestamp('sample_collected_at')->nullable();
            $table->foreignId('sample_collected_by')->nullable()->constrained('users');
            $table->timestamp('completed_at')->nullable();
            $table->foreignId('completed_by')->nullable()->constrained('users');
            
            $table->timestamps();
            $table->softDeletes();
        });

        // Lab Order Items
        Schema::create('lab_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('lab_test_id')->constrained('lab_tests');
            
            $table->decimal('price', 10, 2);
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
            
            // Result Fields
            $table->text('result_value')->nullable();
            $table->string('result_unit')->nullable();
            $table->enum('result_status', ['normal', 'abnormal', 'critical'])->nullable();
            $table->text('remarks')->nullable();
            $table->text('notes_for_patient')->nullable();
            
            // Technician
            $table->foreignId('conducted_by')->nullable()->constrained('users');
            $table->timestamp('conducted_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->timestamp('verified_at')->nullable();
            
            $table->timestamps();
        });

        // Lab Reports (Final PDF/generated reports)
        Schema::create('lab_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_order_id')->constrained()->onDelete('cascade');
            $table->string('report_number')->unique();
            $table->string('file_path')->nullable(); // PDF storage path
            $table->text('summary')->nullable();
            $table->foreignId('generated_by')->constrained('users');
            $table->timestamp('generated_at');
            $table->boolean('is_printed')->default(false);
            $table->timestamp('printed_at')->nullable();
            $table->timestamps();
        });

        // ============================================
        // 8. PHARMACY MODULE CONTINUED
        // ============================================

        // Pharmacy Sales/Billing
        Schema::create('pharmacy_sales', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('pharmacy_id')->constrained();
            $table->foreignId('clinic_id')->constrained();
            $table->foreignId('patient_id')->constrained('users');
            
            // Sale Type
            $table->enum('sale_type', ['walk_in', 'prescription', 'internal'])->default('walk_in');
            $table->foreignId('prescription_id')->nullable()->constrained();
            
            // Financial
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->decimal('amount_due', 10, 2)->default(0);
            
            // Payment
            $table->enum('payment_method', ['cash', 'card', 'upi', 'insurance', 'credit', 'mixed'])->default('cash');
            $table->enum('payment_status', ['pending', 'partial', 'paid', 'refunded'])->default('pending');
            
            $table->foreignId('sold_by')->constrained('users');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Pharmacy Sale Items
        Schema::create('pharmacy_sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pharmacy_sale_id')->constrained()->onDelete('cascade');
            $table->foreignId('medicine_id')->constrained('medicines');
            
            $table->integer('quantity');
            $table->string('batch_number')->nullable();
            $table->date('expiry_date')->nullable();
            $table->decimal('unit_price', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('tax_percentage', 5, 2)->default(0);
            $table->decimal('total_price', 10, 2);
            
            // Prescription reference
            $table->foreignId('prescription_item_id')->nullable()->constrained('prescription_items');
            
            $table->timestamps();
        });

        // Stock Movements (Inventory tracking)
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicine_id')->constrained('medicines');
            $table->foreignId('pharmacy_id')->constrained();
            
            $table->enum('type', ['purchase', 'sale', 'return', 'adjustment', 'expired', 'transfer_in', 'transfer_out']);
            $table->integer('quantity'); // Positive for in, negative for out
            $table->integer('stock_before');
            $table->integer('stock_after');
            
            $table->string('reference_type')->nullable(); // Sale, PurchaseOrder, etc.
            $table->unsignedBigInteger('reference_id')->nullable();
            
            $table->text('reason')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        // ============================================
        // 9. NOTIFICATIONS & COMMUNICATION MODULE
        // ============================================

        // Notification Templates
        Schema::create('notification_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->nullable()->constrained(); // Null for system templates
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('channel', ['sms', 'email', 'push', 'whatsapp'])->default('email');
            $table->string('subject')->nullable();
            $table->text('content'); // Template with variables like {{patient_name}}
            $table->json('variables')->nullable(); // Available variables
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Notification Logs
        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained(); // Recipient
            $table->morphs('notifiable'); // Appointment, Prescription, etc.
            $table->enum('channel', ['sms', 'email', 'push', 'whatsapp', 'in_app']);
            $table->string('template_used')->nullable();
            $table->text('content');
            $table->string('recipient_contact'); // Phone or email
            $table->enum('status', ['pending', 'sent', 'delivered', 'failed', 'read'])->default('pending');
            $table->text('error_message')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        // OTP Records
        Schema::create('otps', function (Blueprint $table) {
            $table->id();
            $table->string('identifier'); // Phone or email
            $table->string('otp_code', 6);
            $table->enum('purpose', ['registration', 'login', 'password_reset', 'appointment_confirmation', 'prescription_access']);
            $table->timestamp('expires_at');
            $table->timestamp('verified_at')->nullable();
            $table->integer('attempts')->default(0);
            $table->boolean('is_used')->default(false);
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
            
            $table->index(['identifier', 'purpose', 'created_at']);
        });

        // ============================================
        // 10. ATTENDANCE & STAFF MANAGEMENT MODULE
        // ============================================

        // Attendance Records
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('clinic_id')->constrained();
            $table->date('date');
            $table->timestamp('check_in')->nullable();
            $table->timestamp('check_out')->nullable();
            $table->string('check_in_location')->nullable(); // GPS coordinates
            $table->string('check_out_location')->nullable();
            $table->enum('status', ['present', 'absent', 'late', 'half_day', 'on_leave', 'wfh'])->default('present');
            $table->text('notes')->nullable();
            $table->foreignId('marked_by')->nullable()->constrained('users'); // If marked by admin
            $table->timestamps();
            
            $table->unique(['user_id', 'date']);
        });

        // Leave Management
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('clinic_id')->constrained();
            $table->enum('type', ['sick', 'casual', 'annual', 'maternity', 'paternity', 'unpaid', 'other']);
            $table->date('from_date');
            $table->date('to_date');
            $table->integer('days')->default(1);
            $table->text('reason');
            $table->string('attachment')->nullable(); // Medical certificate, etc.
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });

        // ============================================
        // 11. SETTINGS & CONFIGURATION MODULE
        // ============================================

        // Clinic Settings (Key-Value store)
        Schema::create('clinic_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->onDelete('cascade');
            $table->string('group')->default('general'); // general, appointment, notification, etc.
            $table->string('key');
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, number, boolean, json
            $table->timestamps();
            
            $table->unique(['clinic_id', 'group', 'key']);
        });

        // Audit Logs (Activity tracking)
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->foreignId('clinic_id')->nullable()->constrained();
            $table->string('action'); // create, update, delete, login, etc.
            $table->string('entity_type'); // Model class name
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->index(['clinic_id', 'created_at']);
            $table->index(['user_id', 'action']);
        });

        // ============================================
        // 12. REVIEWS & RATINGS MODULE
        // ============================================

        // Clinic/Doctor Reviews
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained();
            $table->foreignId('doctor_id')->nullable()->constrained('users');
            $table->foreignId('patient_id')->constrained('users');
            $table->foreignId('appointment_id')->nullable()->constrained();
            
            $table->tinyInteger('rating')->unsigned(); // 1-5
            $table->text('review')->nullable();
            $table->json('categories')->nullable(); // {"staff": 5, "hygiene": 4, "doctor": 5}
            
            $table->boolean('is_verified')->default(false); // Verified purchase/visit
            $table->boolean('is_visible')->default(true);
            $table->text('reply')->nullable(); // Clinic reply
            $table->timestamp('replied_at')->nullable();
            $table->foreignId('replied_by')->nullable()->constrained('users');
            
            $table->timestamps();
            $table->softDeletes();
        });

        // ============================================
        // 13. FAVORITES & BOOKMARKS
        // ============================================

        // Patient Favorites (Doctors, Clinics)
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained(); // Patient
            $table->morphs('favoritable'); // Clinic or Doctor
            $table->timestamps();
            
            $table->unique(['user_id', 'favoritable_type', 'favoritable_id']);
        });

        // ============================================
        // SEED DEFAULT DATA
        // ============================================

        // Insert default subscription tiers
        DB::table('subscription_tiers')->insert([
            [
                'name' => 'Free',
                'slug' => 'free',
                'description' => 'Basic features for small clinics',
                'monthly_price' => 0,
                'yearly_price' => 0,
                'max_clinics' => 1,
                'max_labs' => 0,
                'max_pharmacies' => 0,
                'max_doctors' => 1,
                'max_nurses' => 0,
                'max_admins' => 1,
                'max_managers' => 0,
                'max_staff_total' => 2,
                'has_sms_notifications' => false,
                'has_email_notifications' => true,
                'has_push_notifications' => false,
                'has_advanced_reports' => false,
                'has_api_access' => false,
                'has_custom_branding' => false,
                'has_priority_support' => false,
                'trial_days' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Basic',
                'slug' => 'basic',
                'description' => 'Perfect for growing clinics',
                'monthly_price' => 49.99,
                'yearly_price' => 499.99,
                'max_clinics' => 2,
                'max_labs' => 1,
                'max_pharmacies' => 1,
                'max_doctors' => 5,
                'max_nurses' => 5,
                'max_admins' => 2,
                'max_managers' => 1,
                'max_staff_total' => 15,
                'has_sms_notifications' => true,
                'has_email_notifications' => true,
                'has_push_notifications' => true,
                'has_advanced_reports' => false,
                'has_api_access' => false,
                'has_custom_branding' => false,
                'has_priority_support' => false,
                'trial_days' => 14,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Advanced',
                'slug' => 'advanced',
                'description' => 'Complete solution for multi-branch clinics',
                'monthly_price' => 99.99,
                'yearly_price' => 999.99,
                'max_clinics' => 10,
                'max_labs' => 5,
                'max_pharmacies' => 5,
                'max_doctors' => 50,
                'max_nurses' => 50,
                'max_admins' => 10,
                'max_managers' => 10,
                'max_staff_total' => 200,
                'has_sms_notifications' => true,
                'has_email_notifications' => true,
                'has_push_notifications' => true,
                'has_advanced_reports' => true,
                'has_api_access' => true,
                'has_custom_branding' => true,
                'has_priority_support' => true,
                'trial_days' => 14,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // Insert default modules
        DB::table('modules')->insert([
            ['name' => 'Appointments', 'code' => 'appointments', 'description' => 'Appointment scheduling and management', 'is_core' => true, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Medical Records', 'code' => 'medical_records', 'description' => 'Patient visits and prescriptions', 'is_core' => true, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Laboratory', 'code' => 'laboratory', 'description' => 'Lab tests and reports', 'is_core' => false, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pharmacy', 'code' => 'pharmacy', 'description' => 'Medicine inventory and sales', 'is_core' => false, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Staff Management', 'code' => 'staff', 'description' => 'Staff and attendance management', 'is_core' => false, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Billing', 'code' => 'billing', 'description' => 'Advanced billing and invoicing', 'is_core' => false, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Telemedicine', 'code' => 'telemedicine', 'description' => 'Online consultations', 'is_core' => false, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Analytics', 'code' => 'analytics', 'description' => 'Advanced reports and analytics', 'is_core' => false, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Assign modules to tiers
        $freeTier = DB::table('subscription_tiers')->where('slug', 'free')->first()->id;
        $basicTier = DB::table('subscription_tiers')->where('slug', 'basic')->first()->id;
        $advancedTier = DB::table('subscription_tiers')->where('slug', 'advanced')->first()->id;

        $modules = DB::table('modules')->get();

        foreach ($modules as $module) {
            // Free tier gets only core modules
            if ($module->is_core) {
                DB::table('subscription_tier_modules')->insert([
                    'subscription_tier_id' => $freeTier,
                    'module_id' => $module->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            
            // Basic tier gets all except analytics and telemedicine
            if (!in_array($module->code, ['analytics', 'telemedicine'])) {
                DB::table('subscription_tier_modules')->insert([
                    'subscription_tier_id' => $basicTier,
                    'module_id' => $module->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            
            // Advanced tier gets everything
            DB::table('subscription_tier_modules')->insert([
                'subscription_tier_id' => $advancedTier,
                'module_id' => $module->id,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    public function down(): void
    {
        // Drop in reverse order
        Schema::dropIfExists('favorites');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('clinic_settings');
        Schema::dropIfExists('leaves');
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('otps');
        Schema::dropIfExists('notification_logs');
        Schema::dropIfExists('notification_templates');
        Schema::dropIfExists('stock_movements');
        Schema::dropIfExists('pharmacy_sale_items');
        Schema::dropIfExists('pharmacy_sales');
        Schema::dropIfExists('lab_reports');
        Schema::dropIfExists('lab_order_items');
        Schema::dropIfExists('lab_orders');
        Schema::dropIfExists('lab_tests');
        Schema::dropIfExists('test_categories');
        Schema::dropIfExists('prescription_items');
        Schema::dropIfExists('prescriptions');
        Schema::dropIfExists('patient_visits');
        Schema::dropIfExists('appointment_status_history');
        Schema::dropIfExists('appointments');
        Schema::dropIfExists('doctor_schedules');
        Schema::dropIfExists('clinic_user_roles');
        Schema::dropIfExists('clinic_roles');
        Schema::dropIfExists('staff_profiles');
        Schema::dropIfExists('user_profiles');
        Schema::dropIfExists('medicines');
        Schema::dropIfExists('medicine_categories');
        Schema::dropIfExists('pharmacies');
        Schema::dropIfExists('labs');
        Schema::dropIfExists('departments');
        Schema::dropIfExists('clinics');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('subscription_usage');
        Schema::dropIfExists('user_subscriptions');
        Schema::dropIfExists('subscription_tier_modules');
        Schema::dropIfExists('modules');
        Schema::dropIfExists('subscription_tiers');
    }
};