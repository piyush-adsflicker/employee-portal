<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeaveType;
use App\Models\LeaveBalance;
use App\Models\LeaveRequest;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminLeaveController extends Controller
{
    /**
     * PAGE 1: Manage Leave Types & Assign Balances
     */
    public function manageLeaves()
    {
        $leaveTypes = LeaveType::all();
        $employees = Employee::where('is_active', true)->orderBy('emp_name')->get();

        $leaveBalances = LeaveBalance::with(['employee', 'leaveType'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.leaves.manage', compact('leaveTypes', 'employees', 'leaveBalances'));
    }

    /**
     * PAGE 2: View and Approve/Reject Leave Requests
     */
    public function leaveRequests()
    {
        $pendingRequests = LeaveRequest::with(['employee', 'leaveType'])
            ->where('status', 'Pending')
            ->orderBy('created_at', 'desc')
            ->get();

        $historyRequests = LeaveRequest::with(['employee', 'leaveType'])
            ->whereIn('status', ['Approved', 'Rejected'])
            ->orderBy('updated_at', 'desc')
            ->limit(50)
            ->get();

        return view('admin.leaves.requests', compact('pendingRequests', 'historyRequests'));
    }

    // --- LEAVE TYPES LOGIC ---
    public function storeType(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:leave_types', 'default_days' => 'required|integer|min:0']);
        LeaveType::create($request->only('name', 'default_days'));
        return back()->with('success', 'Leave Type added successfully.');
    }

    // --- LEAVE BALANCES LOGIC ---
    public function updateBalance(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'balance' => 'required|numeric|min:0'
        ]);

        LeaveBalance::updateOrCreate(
            ['employee_id' => $request->employee_id, 'leave_type_id' => $request->leave_type_id],
            ['balance' => $request->balance]
        );

        return back()->with('success', 'Employee leave balance updated successfully.');
    }

    // --- LEAVE REQUEST APPROVAL LOGIC ---
    public function updateRequestStatus(Request $request, LeaveRequest $leaveRequest)
    {
        $request->validate([
            'status' => 'required|in:Approved,Rejected',
            'admin_remark' => 'nullable|string'
        ]);

        $leaveRequest->status = $request->status;
        $leaveRequest->admin_remark = $request->admin_remark;
        $leaveRequest->save();

        if ($request->status === 'Approved') {
            // Deduct Balance
            $balance = LeaveBalance::where('employee_id', $leaveRequest->employee_id)
                ->where('leave_type_id', $leaveRequest->leave_type_id)->first();
            if ($balance) {
                $balance->balance -= $leaveRequest->days;
                $balance->save();
            }

            // Mark Attendance
            $startDate = Carbon::parse($leaveRequest->start_date);
            $endDate = Carbon::parse($leaveRequest->end_date);
            $currentDate = clone $startDate;

            $employee = $leaveRequest->employee;

            while ($currentDate->lte($endDate)) {
                $dateStr = $currentDate->format('Y-m-d');
                if (!$currentDate->isSunday()) {
                    $isHoliday = Holiday::where('date', $dateStr)->where('is_active', true)->exists();
                    if (!$isHoliday) {
                        Attendance::updateOrCreate(
                            ['employee_id' => $employee->id, 'attendance_date' => $dateStr],
                            ['status' => 'Leave', 'shift_id' => $employee->shift_id, 'working_hours' => 0, 'late_minutes' => 0]
                        );
                    }
                }
                $currentDate->addDay();
            }
        }

        return back()->with('success', 'Leave request has been ' . strtolower($request->status) . '.');
    }
}
