<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Extend Employees Table for 360° CRM
        Schema::table('employees', function (Blueprint $table) {
            $table->string('employment_type')->default('Full-Time')->after('joining_date'); // Full-Time, Probation, Intern, Contract
            $table->decimal('salary_ctc', 12, 2)->nullable()->after('employment_type');
            $table->text('skills')->nullable()->after('salary_ctc'); // Comma-separated or text
            $table->text('previous_experience')->nullable()->after('skills');
            $table->string('emergency_contact_name')->nullable()->after('previous_experience');
            $table->string('emergency_contact_phone', 20)->nullable()->after('emergency_contact_name');
            $table->string('emergency_contact_relation', 50)->nullable()->after('emergency_contact_phone');
            $table->date('exit_date')->nullable()->after('is_active');
        });

        // 2. Company Assets Table
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('asset_code')->unique(); // e.g., AST-LAP-001
            $table->string('asset_name'); // e.g., MacBook Pro M3
            $table->string('category'); // Laptop, Monitor, Mobile, SIM, Headset, ID Card, Accessories
            $table->string('serial_number')->nullable();
            $table->foreignId('employee_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->date('issue_date')->nullable();
            $table->date('return_date')->nullable();
            $table->enum('condition', ['New', 'Good', 'Fair', 'Damaged'])->default('Good');
            $table->enum('status', ['Available', 'Assigned', 'Under Repair', 'Retired'])->default('Available');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 3. Employee Documents Vault
        Schema::create('employee_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->string('title');
            $table->string('document_type'); // Offer Letter, NDA, ID Proof, Appraisal, Experience Letter, Warning, Other
            $table->string('file_path');
            $table->date('expiry_date')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        // 4. Employee 360° Timeline Events (Appraisals, Warnings, Promotions, Notes, Exit)
        Schema::create('employee_timelines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->string('event_type'); // Joining, Confirmation, Promotion, Appraisal, Warning, Asset, Exit, Note
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('event_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_timelines');
        Schema::dropIfExists('employee_documents');
        Schema::dropIfExists('assets');

        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'employment_type',
                'salary_ctc',
                'skills',
                'previous_experience',
                'emergency_contact_name',
                'emergency_contact_phone',
                'emergency_contact_relation',
                'exit_date'
            ]);
        });
    }
};
