<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\LeaveType;
use App\Models\LeaveBalance;
use App\Models\LeaveRequest;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LeaveController extends Controller
{
    public function index()
    {
        $employee = Auth::user();

        // Get Leave Balances assigned to the employee
        $leaveBalances = LeaveBalance::with('leaveType')
            ->where('employee_id', $employee->id)
            ->get();

        // Get Active Leave Types for the dropdown (only those where employee has balance)
        $availableLeaveTypes = $leaveBalances->pluck('leaveType')->filter(function ($type) {
            return $type->is_active;
        });

        // Get Employee's Leave Request History
        $leaveRequests = LeaveRequest::with('leaveType')
            ->where('employee_id', $employee->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('employee.leave', compact('leaveBalances', 'availableLeaveTypes', 'leaveRequests'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:500'
        ]);

        $employeeId = Auth::id();
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        $hasOverlap = LeaveRequest::where('employee_id', $employeeId)
            ->whereIn('status', ['Pending', 'Approved'])
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                        $q->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                    });
            })->exists();

        if ($hasOverlap) {
            return back()->with('error', 'You already have a Pending or Approved leave request that overlaps with these dates.');
        }

        // 1. Calculate actual working days (excluding Sundays and Holidays)
        $daysRequested = 0;
        $currentDate = clone $startDate;

        while ($currentDate->lte($endDate)) {
            if (!$currentDate->isSunday()) {
                $isHoliday = Holiday::where('date', $currentDate->format('Y-m-d'))->where('is_active', true)->exists();
                if (!$isHoliday) {
                    $daysRequested++;
                }
            }
            $currentDate->addDay();
        }

        if ($daysRequested == 0) {
            return back()->with('error', 'The selected dates fall entirely on weekends or holidays. No leave request is needed.');
        }

        // 2. Validate against employee's current balance
        $balanceRecord = LeaveBalance::where('employee_id', $employeeId)
            ->where('leave_type_id', $request->leave_type_id)
            ->first();

        if (!$balanceRecord || $balanceRecord->balance < $daysRequested) {
            $available = $balanceRecord ? $balanceRecord->balance : 0;
            return back()->with('error', "Insufficient leave balance. You are requesting {$daysRequested} days, but you only have {$available} days available.");
        }

        // 3. Create the Leave Request
        LeaveRequest::create([
            'employee_id' => $employeeId,
            'leave_type_id' => $request->leave_type_id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'days' => $daysRequested,
            'reason' => $request->reason,
            'status' => 'Pending'
        ]);

        return back()->with('success', "Leave request for {$daysRequested} days submitted successfully. Awaiting administration approval.");
    }
}
