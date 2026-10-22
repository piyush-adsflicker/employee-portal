<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add Half Day config to Shifts
        Schema::table('shifts', function (Blueprint $table) {
            $table->decimal('half_day_hours', 4, 2)->default(4.00)->after('working_hours');
        });

        // Add tracking & break logic to Attendances
        Schema::table('attendances', function (Blueprint $table) {
            $table->string('check_in_ip', 45)->nullable()->after('check_in_time');
            $table->text('check_in_device')->nullable()->after('check_in_ip');
            $table->string('check_in_location')->nullable()->after('check_in_device'); // Stores Lat,Lng

            $table->string('check_out_ip', 45)->nullable()->after('check_out_time');
            $table->text('check_out_device')->nullable()->after('check_out_ip');
            $table->string('check_out_location')->nullable()->after('check_out_device');

            $table->integer('early_logout_minutes')->default(0)->after('late_minutes');

            $table->dateTime('current_break_start')->nullable()->after('early_logout_minutes');
            $table->integer('total_break_minutes')->default(0)->after('current_break_start');
        });
    }

    public function down(): void
    {
        Schema::table('shifts', function (Blueprint $table) {
            $table->dropColumn('half_day_hours');
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn([
                'check_in_ip',
                'check_in_device',
                'check_in_location',
                'check_out_ip',
                'check_out_device',
                'check_out_location',
                'early_logout_minutes',
                'current_break_start',
                'total_break_minutes'
            ]);
        });
    }
};
