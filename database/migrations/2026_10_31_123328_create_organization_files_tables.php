<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organization_files', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path');
            $table->string('category')->default('General'); // Policy, Handbook, Announcement, etc.
            $table->enum('visibility_type', ['Global', 'Department', 'Designation'])->default('Global');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('organization_file_departments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_file_id')->constrained()->cascadeOnDelete();
            $table->foreignId('department_id')->constrained()->cascadeOnDelete();
        });

        Schema::create('organization_file_designations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_file_id')->constrained()->cascadeOnDelete();
            $table->foreignId('designation_id')->constrained()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organization_file_designations');
        Schema::dropIfExists('organization_file_departments');
        Schema::dropIfExists('organization_files');
    }
};