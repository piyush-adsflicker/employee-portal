<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * View 1: Attendance History List with Summary Stats
     */
    public function history(Request $request)
    {
        $employee = Auth::user();
        $query = Attendance::with('shift')->where('employee_id', $employee->id);

        // Set defaults to current month if no dates are provided
        $fromDate = $request->input('from_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $toDate = $request->input('to_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $query->whereDate('attendance_date', '>=', $fromDate)->whereDate('attendance_date', '<=', $toDate);

        // Calculate Summary Statistics based on the filtered query
        $presentCount = (clone $query)->where('status', 'Present')->count();
        $lateCount = (clone $query)->where('status', 'Late')->count();
        $absentCount = (clone $query)->where('status', 'Absent')->count();
        $leaveCount = (clone $query)->where('status', 'Leave')->count();

        // Fetch paginated attendance records
        $attendances = $query->orderBy('attendance_date', 'desc')->paginate(30)->withQueryString();

        return view('employee.attendance-history', compact(
            'attendances',
            'presentCount',
            'lateCount',
            'absentCount',
            'leaveCount',
            'fromDate',
            'toDate'
        ));
    }

    /**
     * View 2: Full Attendance Calendar
     */
    public function calendar()
    {
        $employee = Auth::user();
        $startOfYear = Carbon::now()->startOfYear();
        $endOfYear = Carbon::now()->endOfYear();

        $calendarAttendances = Attendance::where('employee_id', $employee->id)
            ->whereBetween('attendance_date', [$startOfYear, $endOfYear])
            ->get();

        $holidays = Holiday::where('is_active', true)
            ->whereBetween('date', [$startOfYear, $endOfYear])
            ->get();

        $calendarEvents = [];

        // Map Attendance Records
        foreach ($calendarAttendances as $att) {
            $color = '#94a3b8'; // Default slate
            if ($att->status == 'Present') $color = '#0d9488'; // Teal
            if ($att->status == 'Late') $color = '#f59e0b'; // Amber
            if ($att->status == 'Absent') $color = '#ef4444'; // Red
            if ($att->status == 'Leave') $color = '#3b82f6'; // Blue

            $calendarEvents[] = [
                'title' => $att->status,
                'start' => $att->attendance_date->format('Y-m-d'),
                'backgroundColor' => $color,
                'borderColor' => $color,
                'extendedProps' => [
                    'type' => 'attendance',
                    'check_in' => $att->check_in_time ? $att->check_in_time->format('h:i A') : '--',
                    'check_out' => $att->check_out_time ? $att->check_out_time->format('h:i A') : '--',
                    'working_hours' => $att->working_hours,
                    'status' => $att->status
                ]
            ];
        }

        // Map Holidays
        foreach ($holidays as $hol) {
            $calendarEvents[] = [
                'title' => $hol->holiday_name,
                'start' => $hol->date->format('Y-m-d'),
                'backgroundColor' => '#8b5cf6',
                'borderColor' => '#7c3aed',
                'extendedProps' => [
                    'type' => 'holiday',
                    'description' => $hol->description
                ]
            ];
        }

        // 3. Map Sundays (Weekly Offs)
        // Find the first Sunday of the year, then loop by adding 1 week
        $currentSunday = clone $startOfYear;
        if (!$currentSunday->isSunday()) {
            $currentSunday->next(Carbon::SUNDAY);
        }

        while ($currentSunday->lte($endOfYear)) {
            $calendarEvents[] = [
                'title' => 'Weekly Off',
                'start' => $currentSunday->format('Y-m-d'),
                'backgroundColor' => '#f1f5f9', 
                'borderColor' => '#e2e8f0',     
                'textColor' => '#64748b',       
                'extendedProps' => [
                    'type' => 'holiday',
                    'description' => 'Standard Weekly Off (Sunday)'
                ]
            ];
            $currentSunday->addWeek();
        }

        return view('employee.attendance-calendar', compact('calendarEvents'));
    }
}
