<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Holiday;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceService
{
    public function processCheckIn(Employee $employee, Request $request)
    {
        $time = Carbon::now();
        $date = $time->format('Y-m-d');

        $existing = Attendance::where('employee_id', $employee->id)->where('attendance_date', $date)->first();
        if ($existing && $existing->check_in_time) {
            return ['success' => false, 'message' => 'Already checked in for today.'];
        }

        $shift = $employee->shift;
        if (!$shift) {
            return ['success' => false, 'message' => 'No shift assigned to employee.'];
        }

        $isHoliday = Holiday::where('date', $date)->where('is_active', true)->exists();
        if ($isHoliday) {
            return ['success' => false, 'message' => 'Today is an official holiday.'];
        }

        if ($time->isSunday()) {
            return ['success' => false, 'message' => 'Today is a non-working weekend.'];
        }

        // Late calculation
        $shiftStartTime = Carbon::parse($date . ' ' . $shift->start_time);
        $lateThreshold = $shiftStartTime->copy()->addMinutes($shift->grace_period);
        $lateMinutes = 0;
        $status = 'Present';

        if ($time->greaterThan($lateThreshold)) {
            $status = 'Late';
            $lateMinutes = $shiftStartTime->diffInMinutes($time);
        }

        $attendance = Attendance::create([
            'employee_id'          => $employee->id,
            'attendance_date'      => $date,
            'check_in_time'        => $time,
            'check_in_ip'          => $request->ip(),
            'check_in_device'      => $request->userAgent(),
            'check_in_location'    => $request->input('location'),
            'check_in_coordinates' => $request->input('coordinates'),
            'shift_id'             => $shift->id,
            'late_minutes'         => $lateMinutes,
            'status'               => $status,
        ]);

        return ['success' => true, 'message' => "Checked in successfully at {$time->format('h:i A')}", 'data' => $attendance];
    }

    public function processBreakStart(Employee $employee)
    {
        $attendance = Attendance::where('employee_id', $employee->id)->where('attendance_date', Carbon::today()->format('Y-m-d'))->first();
        if (!$attendance || $attendance->check_out_time || $attendance->current_break_start) {
            return ['success' => false, 'message' => 'Invalid action.'];
        }

        $attendance->update(['current_break_start' => Carbon::now()]);
        return ['success' => true, 'message' => 'Break started successfully.'];
    }

    public function processBreakEnd(Employee $employee)
    {
        $attendance = Attendance::where('employee_id', $employee->id)->where('attendance_date', Carbon::today()->format('Y-m-d'))->first();
        if (!$attendance || !$attendance->current_break_start) {
            return ['success' => false, 'message' => 'No active break found.'];
        }

        $breakMinutes = Carbon::parse($attendance->current_break_start)->diffInMinutes(Carbon::now());

        $attendance->update([
            'total_break_minutes' => $attendance->total_break_minutes + $breakMinutes,
            'current_break_start' => null
        ]);

        return ['success' => true, 'message' => 'Break ended. Welcome back!'];
    }

    public function processCheckOut(Employee $employee, Request $request)
    {
        $time = Carbon::now();
        $date = $time->format('Y-m-d');

        $attendance = Attendance::where('employee_id', $employee->id)->where('attendance_date', $date)->first();
        if (!$attendance || !$attendance->check_in_time) {
            return ['success' => false, 'message' => 'No check-in record found for today.'];
        }
        if ($attendance->check_out_time) {
            return ['success' => false, 'message' => 'Already checked out for today.'];
        }

        $shift = $attendance->shift;
        $shiftEndTime = Carbon::parse($date . ' ' . $shift->end_time);

        // 1. Calculate Early Logout
        $earlyLogoutMinutes = 0;
        if ($time->lessThan($shiftEndTime)) {
            $earlyLogoutMinutes = $time->diffInMinutes($shiftEndTime);
        }

        // 2. Auto-close any active break
        $totalBreak = $attendance->total_break_minutes;
        if ($attendance->current_break_start) {
            $totalBreak += Carbon::parse($attendance->current_break_start)->diffInMinutes($time);
        }

        // 3. Calculate Net Working Hours
        $checkInTime = Carbon::parse($attendance->check_in_time);
        $grossMinutes = $checkInTime->diffInMinutes($time);
        $netMinutes = max(0, $grossMinutes - $totalBreak);
        $workedHours = round($netMinutes / 60, 2);

        // 4. Half Day / Absent Status Recalculation
        $status = $attendance->status;
        if ($workedHours < $shift->working_hours) {
            if ($workedHours >= $shift->half_day_hours) {
                $status = 'Half Day';
            } else {
                // If they didn't even meet the half-day minimum
                $status = 'Absent';
            }
        }

        $attendance->update([
            'check_out_time'        => $time,
            'check_out_ip'          => $request->ip(),
            'check_out_device'      => $request->userAgent(),
            'check_out_location'    => $request->input('location'),
            'check_out_coordinates' => $request->input('coordinates'),
            'working_hours'         => $workedHours,
            'early_logout_minutes'  => $earlyLogoutMinutes,
            'current_break_start'   => null,
            'total_break_minutes'   => $totalBreak,
            'status'                => $status,
        ]);
        
        return ['success' => true, 'message' => "Checked out successfully. Hours logged: {$workedHours}"];
    }
}