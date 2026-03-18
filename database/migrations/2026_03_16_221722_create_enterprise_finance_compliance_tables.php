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
        // 1. Ledger Accounts (COA)
        Schema::create('ledger_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('code')->unique();
            $table->enum('type', ['asset', 'liability', 'equity', 'revenue', 'expense']);
            $table->decimal('balance', 15, 2)->default(0);
            $table->timestamps();
        });

        // 2. Financial Transactions (Double Entry)
        Schema::create('financial_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->onDelete('cascade');
            $table->foreignId('ledger_account_id')->constrained()->onDelete('cascade');
            $table->decimal('debit', 15, 2)->default(0);
            $table->decimal('credit', 15, 2)->default(0);
            $table->text('description')->nullable();
            $table->string('reference_type')->nullable(); // Invoice, Payment, Payroll
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->dateTime('transaction_date');
            $table->foreignId('recorded_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // 3. Bank Accounts
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->onDelete('cascade');
            $table->string('bank_name');
            $table->string('account_number');
            $table->string('account_name');
            $table->string('branch')->nullable();
            $table->string('swift_code')->nullable();
            $table->decimal('current_balance', 15, 2)->default(0);
            $table->timestamps();
        });

        // 4. Assets
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('asset_code')->unique();
            $table->string('category')->nullable(); // Equipment, Furniture, Vehicle
            $table->date('purchase_date');
            $table->decimal('purchase_cost', 15, 2);
            $table->decimal('current_value', 15, 2);
            $table->date('warranty_expiry')->nullable();
            $table->enum('status', ['operational', 'maintenance', 'disposed'])->default('operational');
            $table->timestamps();
        });

        // 5. Incidents (Clinical Safety)
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->enum('severity', ['low', 'medium', 'high', 'critical']);
            $table->dateTime('occurrence_time');
            $table->foreignId('reported_by')->constrained('users')->onDelete('cascade');
            $table->text('action_taken')->nullable();
            $table->enum('status', ['reported', 'investigated', 'resolved'])->default('reported');
            $table->timestamps();
        });

        // 6. Safety Reports
        Schema::create('safety_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->onDelete('cascade');
            $table->string('report_title');
            $table->text('summary');
            $table->text('recommendations')->nullable();
            $table->date('report_date');
            $table->foreignId('prepared_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('safety_reports');
        Schema::dropIfExists('incidents');
        Schema::dropIfExists('assets');
        Schema::dropIfExists('bank_accounts');
        Schema::dropIfExists('financial_transactions');
        Schema::dropIfExists('ledger_accounts');
    }
};
