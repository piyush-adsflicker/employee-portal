<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Holiday;
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
        $today = Carbon::today()->format('Y-m-d');

        // Fetch today's attendance record
        $todayAttendance = Attendance::where('employee_id', $employee->id)
            ->where('attendance_date', $today)
            ->first();

        // Calculate dynamic greeting
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
            ->whereIn('status', ['Present', 'Late', 'Half Day'])
            ->count();

        // --- NEW: CURRENT WEEK WIDGET LOGIC (Sunday to Saturday) ---
        $startOfWeek = Carbon::today()->startOfWeek(Carbon::SUNDAY);
        $endOfWeek = clone $startOfWeek;
        $endOfWeek->addDays(6); // Saturday

        $attendances = Attendance::where('employee_id', $employee->id)
            ->whereBetween('attendance_date', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')])
            ->get()
            ->keyBy(function ($item) {
                return $item->attendance_date->format('Y-m-d');
            });
        $currentWeek = collect();

        for ($i = 0; $i <= 6; $i++) {
            $date = clone $startOfWeek;
            $date->addDays($i);
            $dateString = $date->format('Y-m-d');

            $status = 'Unmarked';
            $isHoliday = Holiday::where('date', $dateString)->where('is_active', true)->exists();

            $checkInTime = '--:--';
            $checkOutTime = '--:--';

            if ($attendances->has($dateString)) {
                $att = $attendances[$dateString];
                $status = $att->status;
                $checkInTime = $att->check_in_time ? $att->check_in_time->format('h:i A') : '--:--';
                $checkOutTime = $att->check_out_time ? $att->check_out_time->format('h:i A') : '--:--';
            } elseif ($isHoliday) {
                $status = 'Holiday';
            } elseif ($date->isSunday()) {
                $status = 'Weekend';
            } elseif ($dateString < $today) {
                $status = 'Absent';
            }

            $currentWeek->push([
                'day'       => $date->format('D'),
                'date'      => $date->format('d M'),
                'status'    => $status,
                'check_in'  => $checkInTime,
                'check_out' => $checkOutTime,
                'isToday'   => $dateString === $today
            ]);
        }

        return view('employee.dashboard', compact('employee', 'todayAttendance', 'greeting', 'monthlyPresent', 'currentWeek'));
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
