<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add strict KYC and Bank columns to Employees
        Schema::table('employees', function (Blueprint $table) {
            $table->string('pan_number', 20)->nullable()->after('previous_experience');
            $table->string('aadhaar_number', 20)->nullable()->after('pan_number');
            $table->string('pf_uan', 20)->nullable()->after('aadhaar_number'); // Provident Fund UAN
            $table->string('bank_name')->nullable()->after('pf_uan');
            $table->string('bank_account_no')->nullable()->after('bank_name');
            $table->string('bank_ifsc')->nullable()->after('bank_account_no');
        });

        // 2. Education History Table (1-to-Many)
        Schema::create('employee_educations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->string('level'); // e.g., 10th, 12th, Graduation, Post-Graduation
            $table->string('institution');
            $table->string('specialization')->nullable(); // e.g., B.Tech Computer Science
            $table->string('start_year', 10)->nullable();
            $table->string('end_year', 10)->nullable();
            $table->timestamps();
        });

        // 3. Work Experience Table (1-to-Many)
        Schema::create('employee_experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->string('company_name');
            $table->string('job_role');
            $table->date('from_date');
            $table->date('to_date');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_experiences');
        Schema::dropIfExists('employee_educations');
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['pan_number', 'aadhaar_number', 'pf_uan', 'bank_name', 'bank_account_no', 'bank_ifsc']);
        });
    }
};
