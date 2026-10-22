<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\Attendance;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\EmployeeDocument;
use App\Models\EmployeeTimeline;
use App\Models\Shift;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with(['department', 'designation', 'shift'])->latest()->get();
        return view('admin.employees.index', compact('employees'));
    }

    public function create()
    {
        $departments  = Department::where('is_active', true)->get();
        $designations = Designation::where('is_active', true)->get();
        $shifts       = Shift::all();

        return view('admin.employees.create', compact('departments', 'designations', 'shifts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'emp_id'                     => 'required|string|max:50|unique:employees,emp_id',
            'emp_name'                   => 'required|string|max:255',
            'email'                      => 'nullable|email|max:255|unique:employees,email',
            'phone_number'               => 'nullable|string|max:20',
            'department_id'              => 'nullable|exists:departments,id',
            'designation_id'             => 'nullable|exists:designations,id',
            'shift_id'                   => 'nullable|exists:shifts,id',
            'joining_date'               => 'nullable|date',
            'employment_type'            => 'required|string|max:50',
            'salary_ctc'                 => 'nullable|numeric|min:0',
            'skills'                     => 'nullable|string',
            'previous_experience'        => 'nullable|string',
            'emergency_contact_name'     => 'nullable|string|max:255',
            'emergency_contact_phone'    => 'nullable|string|max:20',
            'emergency_contact_relation' => 'nullable|string|max:50',
            'password'                   => 'required|string|min:8',
        ]);

        $data              = $request->except('password');
        $data['password']  = Hash::make($request->password);
        $data['is_active'] = true;

        $employee = Employee::create($data);

        // Automatically create Joining Milestone in 360° Timeline
        EmployeeTimeline::create([
            'employee_id' => $employee->id,
            'event_type'  => 'Joining',
            'title'       => 'Joined the Organization',
            'description' => 'Joined as ' . ($employee->designation->designation_name ?? 'Staff') . ' (' . $employee->employment_type . ').',
            'event_date'  => $employee->joining_date ?? now(),
        ]);

        return redirect()->route('admin.employees.index')->with('success', 'Employee registered successfully!');
    }

    public function show(Employee $employee)
    {
        $employee->load([
            'department',
            'designation',
            'shift',
            'assets',
            'documents',
            'timelines',
            'leaveBalances.leaveType',
        ]);

        // Attendance Summary for 360° View
        $attendanceStats = [
            'present' => $employee->attendances()->where('status', 'Present')->count(),
            'late'    => $employee->attendances()->where('status', 'Late')->count(),
            'absent'  => $employee->attendances()->where('status', 'Absent')->count(),
            'leave'   => $employee->attendances()->where('status', 'Leave')->count(),
        ];

        $recentLeaves = $employee->leaveRequests()->with('leaveType')->latest()->take(5)->get();
        $availableAssets = Asset::where('status', 'Available')->get();

        // Build Unified 360° Timeline Feed (Custom Events + Approved Leaves)
        $unifiedTimeline = collect();

        foreach ($employee->timelines as $item) {
            $unifiedTimeline->push([
                'id'          => $item->id,
                'is_custom'   => true,
                'type'        => $item->event_type,
                'title'       => $item->title,
                'description' => $item->description,
                'date'        => $item->event_date,
            ]);
        }

        foreach ($employee->leaveRequests()->where('status', 'Approved')->get() as $leave) {
            $unifiedTimeline->push([
                'id'          => null,
                'is_custom'   => false,
                'type'        => 'Leave',
                'title'       => "Approved Leave: {$leave->leaveType->name} ({$leave->days} Days)",
                'description' => "From {$leave->start_date->format('d M Y')} to {$leave->end_date->format('d M Y')} — {$leave->reason}",
                'date'        => $leave->start_date,
            ]);
        }

        $unifiedTimeline = $unifiedTimeline->sortByDesc('date')->values();

        return view('admin.employees.show', compact(
            'employee',
            'attendanceStats',
            'recentLeaves',
            'availableAssets',
            'unifiedTimeline'
        ));
    }

    public function edit(Employee $employee)
    {
        $departments  = Department::where('is_active', true)->get();
        $designations = Designation::where('is_active', true)->get();
        $shifts       = Shift::all();

        return view('admin.employees.edit', compact('employee', 'departments', 'designations', 'shifts'));
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'emp_id'                     => 'required|string|max:50|unique:employees,emp_id,' . $employee->id,
            'emp_name'                   => 'required|string|max:255',
            'email'                      => 'nullable|email|max:255|unique:employees,email,' . $employee->id,
            'phone_number'               => 'nullable|string|max:20',
            'department_id'              => 'nullable|exists:departments,id',
            'designation_id'             => 'nullable|exists:designations,id',
            'shift_id'                   => 'nullable|exists:shifts,id',
            'joining_date'               => 'nullable|date',
            'employment_type'            => 'required|string|max:50',
            'salary_ctc'                 => 'nullable|numeric|min:0',
            'skills'                     => 'nullable|string',
            'previous_experience'        => 'nullable|string',
            'emergency_contact_name'     => 'nullable|string|max:255',
            'emergency_contact_phone'    => 'nullable|string|max:20',
            'emergency_contact_relation' => 'nullable|string|max:50',
            'exit_date'                  => 'nullable|date',
            'is_active'                  => 'required|boolean',
            'password'                   => 'nullable|string|min:8',
        ]);

        $data = $request->except('password');

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $employee->update($data);

        return redirect()->route('admin.employees.show', $employee->id)->with('success', 'Employee 360° profile updated!');
    }

    // --- 360° DOCUMENT UPLOAD ---
    public function storeDocument(Request $request, Employee $employee)
    {
        $request->validate([
            'title'         => 'required|string|max:255',
            'document_type' => 'required|string|max:100',
            'document_file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
            'expiry_date'   => 'nullable|date',
            'remarks'       => 'nullable|string|max:500',
        ]);

        $uploadPath = public_path('uploads/documents');
        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0755, true);
        }

        $file = $request->file('document_file');
        $filename = $employee->emp_id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move($uploadPath, $filename);

        EmployeeDocument::create([
            'employee_id'   => $employee->id,
            'title'         => $request->title,
            'document_type' => $request->document_type,
            'file_path'     => 'uploads/documents/' . $filename,
            'expiry_date'   => $request->expiry_date,
            'remarks'       => $request->remarks,
        ]);

        return back()->with('success', 'Document uploaded to employee vault!');
    }

    public function destroyDocument(EmployeeDocument $document)
    {
        if (File::exists(public_path($document->file_path))) {
            File::delete(public_path($document->file_path));
        }
        $document->delete();
        return back()->with('success', 'Document deleted.');
    }

    // --- 360° TIMELINE MILESTONES (Appraisals, Warnings, Promotions, Exit) ---
    public function storeTimeline(Request $request, Employee $employee)
    {
        $request->validate([
            'event_type'  => 'required|in:Promotion,Appraisal,Warning,Performance,Note,Exit',
            'title'       => 'required|string|max:255',
            'event_date'  => 'required|date',
            'description' => 'nullable|string|max:1000',
        ]);

        EmployeeTimeline::create([
            'employee_id' => $employee->id,
            'event_type'  => $request->event_type,
            'title'       => $request->title,
            'event_date'  => $request->event_date,
            'description' => $request->description,
        ]);

        // If event is Exit, mark exit date
        if ($request->event_type === 'Exit') {
            $employee->update([
                'exit_date' => $request->event_date,
                'is_active' => false,
            ]);
        }

        return back()->with('success', 'Timeline event logged successfully!');
    }

    public function destroyTimeline(EmployeeTimeline $timeline)
    {
        $timeline->delete();
        return back()->with('success', 'Timeline entry removed.');
    }

    // --- 360° ASSET QUICK ASSIGN & RETURN ---
    public function assignAsset(Request $request, Employee $employee)
    {
        if (!$employee->is_active) {
            return back()->with('error', 'Action Denied: Cannot assign assets to an inactive or suspended employee.');
        }
        
        $request->validate([
            'asset_id' => 'required|exists:assets,id',
        ]);

        $asset = Asset::findOrFail($request->asset_id);
        $asset->update([
            'employee_id' => $employee->id,
            'status'      => 'Assigned',
            'issue_date'  => now()->format('Y-m-d'),
        ]);

        EmployeeTimeline::create([
            'employee_id' => $employee->id,
            'event_type'  => 'Asset',
            'title'       => "Asset Assigned: {$asset->asset_name} ({$asset->asset_code})",
            'description' => "Category: {$asset->category} | Condition: {$asset->condition}",
            'event_date'  => now(),
        ]);

        return back()->with('success', 'Asset successfully assigned to employee!');
    }

    public function returnAsset(Request $request, Employee $employee, Asset $asset)
    {
        $asset->update([
            'employee_id' => null,
            'status'      => 'Available',
            'return_date' => now()->format('Y-m-d'),
        ]);

        EmployeeTimeline::create([
            'employee_id' => $employee->id,
            'event_type'  => 'Asset',
            'title'       => "Asset Returned: {$asset->asset_name} ({$asset->asset_code})",
            'description' => "Category: {$asset->category}. Marked as Available in inventory.",
            'event_date'  => now(),
        ]);

        return back()->with('success', 'Asset returned and placed back in available inventory!');
    }

    public function attendance(Employee $employee)
    {
        $startOfYear = Carbon::now()->startOfYear();
        $endOfYear = Carbon::now()->endOfYear();

        // Get Attendance Records
        $attendances = Attendance::with('shift')
            ->where('employee_id', $employee->id)
            ->whereBetween('attendance_date', [$startOfYear, $endOfYear])
            ->get();

        // Get Holidays
        $holidays = \App\Models\Holiday::where('is_active', true)
            ->whereBetween('date', [$startOfYear, $endOfYear])
            ->get();

        $calendarEvents = [];

        foreach ($attendances as $att) {
            $color = '#94a3b8';
            if ($att->status == 'Present') $color = '#059669'; // Emerald
            if ($att->status == 'Late') $color = '#e78b22'; // Amber
            if ($att->status == 'Absent') $color = '#dc2626'; // Red
            if ($att->status == 'Leave') $color = '#2563eb'; // Blue

            $calendarEvents[] = [
                'id' => $att->id,
                'title' => $att->status,
                'start' => $att->attendance_date->format('Y-m-d'),
                'backgroundColor' => $color,
                'borderColor' => $color,
                'extendedProps' => [
                    'type' => 'attendance',
                    'record_id' => $att->id,
                    'check_in' => $att->check_in_time ? $att->check_in_time->format('H:i') : '',
                    'check_out' => $att->check_out_time ? $att->check_out_time->format('H:i') : '',
                    'working_hours' => $att->working_hours,
                    'status' => $att->status,
                    'date_formatted' => $att->attendance_date->format('d M, Y')
                ]
            ];
        }

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

        // Map Sundays (Weekly Offs)
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

        return view('admin.employees.attendance', compact('employee', 'calendarEvents'));
    }
}
