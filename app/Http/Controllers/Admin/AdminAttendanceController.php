<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Department;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminAttendanceController extends Controller
{
    public function index(Request $request)
    {
        // Eager load relationships
        $query = Attendance::with(['employee.department', 'employee.designation', 'shift']);

        // Set Default Date to Today if no date filters are applied
        $defaultDate = Carbon::today()->format('Y-m-d');
        $fromDate = $request->input('from_date', $defaultDate);
        $toDate = $request->input('to_date', $defaultDate);

        // Apply Date Range Filter (Acts as exact date if both are same)
        $query->whereDate('attendance_date', '>=', $fromDate)
            ->whereDate('attendance_date', '<=', $toDate);

        // Apply Employee Filter
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        // Apply Department Filter
        if ($request->filled('department_id')) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }

        // Apply Status Filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Calculate Summary Statistics for the current filtered view
        $statsQuery = clone $query;
        $stats = [
            'total' => (clone $statsQuery)->count(),
            'present' => (clone $statsQuery)->where('status', 'Present')->count(),
            'late' => (clone $statsQuery)->where('status', 'Late')->count(),
            'absent' => (clone $statsQuery)->where('status', 'Absent')->count(),
            'leave' => (clone $statsQuery)->where('status', 'Leave')->count(),
        ];

        // Fetch Paginated Results
        $attendances = $query->orderBy('attendance_date', 'desc')
            ->orderBy('check_in_time', 'asc')
            ->paginate(50)
            ->withQueryString();

        // Data for Filter Dropdowns
        $departments = Department::where('is_active', true)->get();
        // Employee model uses 'emp_name'
        $employees = Employee::where('is_active', true)->orderBy('emp_name')->get();

        return view('admin.attendance-history', compact('attendances', 'stats', 'departments', 'employees', 'fromDate', 'toDate'));
    }

    public function store(Request $request, Employee $employee)
    {
        $request->validate([
            'attendance_date' => 'required|date',
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i',
        ]);

        $date = Carbon::parse($request->attendance_date)->format('Y-m-d');

        if (Attendance::where('employee_id', $employee->id)->where('attendance_date', $date)->exists()) {
            return back()->with('error', 'An attendance record already exists for this date.');
        }

        $checkInTime = $request->check_in ? Carbon::parse($date . ' ' . $request->check_in) : null;
        $checkOutTime = $request->check_out ? Carbon::parse($date . ' ' . $request->check_out) : null;

        $shift = $employee->shift;
        $lateMinutes = 0;
        $workingHours = 0;
        $status = 'Absent';

        if ($checkInTime && $shift) {
            $shiftStart = Carbon::parse($date . ' ' . $shift->start_time);
            $lateThreshold = $shiftStart->copy()->addMinutes($shift->grace_period);

            if ($checkInTime->greaterThan($lateThreshold)) {
                $status = 'Late';
                $lateMinutes = $shiftStart->diffInMinutes($checkInTime);
            } else {
                $status = 'Present';
            }
        }

        if ($checkInTime && $checkOutTime) {
            $workingHours = round($checkInTime->diffInMinutes($checkOutTime) / 60, 2);
        }

        Attendance::create([
            'employee_id' => $employee->id,
            'attendance_date' => $date,
            'check_in_time' => $checkInTime,
            'check_out_time' => $checkOutTime,
            'shift_id' => $employee->shift_id,
            'working_hours' => $workingHours,
            'late_minutes' => $lateMinutes,
            'status' => $status
        ]);

        return back()->with('success', 'Attendance record created successfully!');
    }

    public function updateTime(Request $request, Attendance $attendance)
    {
        $request->validate([
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i',
        ]);

        $date = $attendance->attendance_date->format('Y-m-d');

        $checkInTime = $request->check_in ? Carbon::parse($date . ' ' . $request->check_in) : null;
        $checkOutTime = $request->check_out ? Carbon::parse($date . ' ' . $request->check_out) : null;

        $shift = $attendance->shift;
        $lateMinutes = 0;
        $workingHours = 0;
        $status = $attendance->status;

        if ($checkInTime && $shift) {
            $shiftStart = Carbon::parse($date . ' ' . $shift->start_time);
            $lateThreshold = $shiftStart->copy()->addMinutes($shift->grace_period);

            if ($checkInTime->greaterThan($lateThreshold)) {
                $status = 'Late';
                $lateMinutes = $shiftStart->diffInMinutes($checkInTime);
            } else {
                $status = 'Present';
            }
        } elseif (!$checkInTime) {
            $status = 'Absent';
        }

        if ($checkInTime && $checkOutTime) {
            $workingHours = round($checkInTime->diffInMinutes($checkOutTime) / 60, 2);
        }

        $attendance->update([
            'check_in_time' => $checkInTime,
            'check_out_time' => $checkOutTime,
            'late_minutes' => $lateMinutes,
            'working_hours' => $workingHours,
            'status' => $status
        ]);

        return back()->with('success', 'Attendance record updated successfully!');
    }
}
