<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Services\AttendanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EmpDashboardController extends Controller
{
    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function index()
    {
        $employee = Auth::user();

        // Fetch today's attendance record
        $todayAttendance = Attendance::where('employee_id', $employee->id)
            ->where('attendance_date', Carbon::today()->format('Y-m-d'))
            ->first();

        // Calculate dynamic greeting based on current time
        $hour = Carbon::now()->format('H');
        if ($hour < 12) {
            $greeting = 'Good Morning';
        } elseif ($hour < 17) {
            $greeting = 'Good Afternoon';
        } else {
            $greeting = 'Good Evening';
        }

        // Basic stats for the dashboard (Current Month)
        $currentMonth = Carbon::now()->format('m');
        $monthlyPresent = Attendance::where('employee_id', $employee->id)
            ->whereMonth('attendance_date', $currentMonth)
            ->whereIn('status', ['Present', 'Late'])
            ->count();

        return view('employee.dashboard', compact('employee', 'todayAttendance', 'greeting', 'monthlyPresent'));
    }

    public function checkIn(Request $request)
    {
        $result = $this->attendanceService->processCheckIn(Auth::user(), $request);
        return $result['success'] ? back()->with('success', $result['message']) : back()->with('error', $result['message']);
    }

    public function checkOut(Request $request)
    {
        $result = $this->attendanceService->processCheckOut(Auth::user(), $request);
        return $result['success'] ? back()->with('success', $result['message']) : back()->with('error', $result['message']);
    }

    public function startBreak(Request $request)
    {
        $result = $this->attendanceService->processBreakStart(Auth::user());
        return back()->with('success', $result['message']);
    }

    public function endBreak(Request $request)
    {
        $result = $this->attendanceService->processBreakEnd(Auth::user());
        return back()->with('success', $result['message']);
    }
}
