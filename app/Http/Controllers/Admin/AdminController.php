<?php
namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Shift;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $today = Carbon::today()->format('Y-m-d');

        // 1. Top Stat Cards
        $totalEmployees = Employee::where('is_active', true)->count();
        
        $presentToday = Attendance::where('attendance_date', $today)
            ->whereIn('status', ['Present', 'Late'])
            ->count();
            
        $absentToday = Attendance::where('attendance_date', $today)
            ->where('status', 'Absent')
            ->count();
            
        $onLeaveToday = Attendance::where('attendance_date', $today)
            ->where('status', 'Leave')
            ->count();

        // 2. Real-time Activity (Latest 5 check-ins today)
        $recentCheckins = Attendance::with(['employee.designation'])
            ->where('attendance_date', $today)
            ->whereNotNull('check_in_time')
            ->orderBy('check_in_time', 'desc')
            ->take(5)
            ->get();

        // 3. Setup Progress Indicators
        $setup = [
            'departments' => Department::count(),
            'designations' => Designation::count(),
            'shifts' => Shift::count(),
        ];

        return view('admin.dashboard', compact(
            'totalEmployees', 
            'presentToday', 
            'absentToday', 
            'onLeaveToday', 
            'recentCheckins', 
            'setup'
        ));
    }

    public function profile()
    {
        $admin = Auth::guard('admin')->user();
        return view('admin.profile', compact('admin'));
    }

    public function updateProfile(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:admins,email,' . $admin->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $admin->name = $request->name;
        $admin->email = $request->email;

        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }

        $admin->save();

        return back()->with('success', 'Admin profile updated successfully!');
    }
}