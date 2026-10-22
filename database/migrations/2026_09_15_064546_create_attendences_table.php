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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->date('attendance_date');
            $table->dateTime('check_in_time')->nullable();
            $table->dateTime('check_out_time')->nullable();

            // Stores the shift applied on that specific day
            $table->foreignId('shift_id')->nullable()->constrained()->nullOnDelete();

            $table->decimal('working_hours', 5, 2)->default(0);
            $table->integer('late_minutes')->default(0);

            // Status: Present, Late, Absent, Leave, Holiday
            $table->string('status')->default('Absent');

            $table->timestamps();

            // Prevent duplicate check-ins for the same employee on the same date
            $table->unique(['employee_id', 'attendance_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }

};
