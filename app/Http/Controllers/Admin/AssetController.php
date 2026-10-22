<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\Employee;
use App\Models\EmployeeTimeline;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $query = Asset::with('employee');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $assets = $query->latest()->get();
        $employees = Employee::orderBy('emp_name')->get();

        $stats = [
            'total' => Asset::count(),
            'assigned' => Asset::where('status', 'Assigned')->count(),
            'available' => Asset::where('status', 'Available')->count(),
            'repair' => Asset::whereIn('status', ['Under Repair', 'Retired'])->count(),
        ];

        return view('admin.assets.index', compact('assets', 'employees', 'stats'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'asset_code'    => 'required|string|max:50|unique:assets,asset_code',
            'asset_name'    => 'required|string|max:255',
            'category'      => 'required|string|max:100',
            'serial_number' => 'nullable|string|max:100',
            'employee_id'   => 'nullable|exists:employees,id',
            'issue_date'    => 'nullable|date',
            'return_date'   => 'nullable|date',
            'condition'     => 'required|in:New,Good,Fair,Damaged',
            'status'        => 'required|in:Available,Assigned,Under Repair,Retired',
            'notes'         => 'nullable|string|max:500',
        ]);

        // Auto-sync status if employee is selected
        if (!empty($data['employee_id']) && $data['status'] === 'Available') {
            $data['status'] = 'Assigned';
            $data['issue_date'] = $data['issue_date'] ?? now()->format('Y-m-d');
        }

        $asset = Asset::create($data);

        if ($asset->employee_id) {
            EmployeeTimeline::create([
                'employee_id' => $asset->employee_id,
                'event_type'  => 'Asset',
                'title'       => "Asset Assigned: {$asset->asset_name} ({$asset->asset_code})",
                'description' => "Category: {$asset->category} | Condition: {$asset->condition}",
                'event_date'  => $asset->issue_date ?? now(),
            ]);
        }

        return back()->with('success', 'Asset added successfully!');
    }

    public function update(Request $request, Asset $asset)
    {
        $data = $request->validate([
            'asset_code'    => 'required|string|max:50|unique:assets,asset_code,' . $asset->id,
            'asset_name'    => 'required|string|max:255',
            'category'      => 'required|string|max:100',
            'serial_number' => 'nullable|string|max:100',
            'employee_id'   => 'nullable|exists:employees,id',
            'issue_date'    => 'nullable|date',
            'return_date'   => 'nullable|date',
            'condition'     => 'required|in:New,Good,Fair,Damaged',
            'status'        => 'required|in:Available,Assigned,Under Repair,Retired',
            'notes'         => 'nullable|string|max:500',
        ]);

        $previousEmployeeId = $asset->employee_id;

        if (empty($data['employee_id']) && $data['status'] === 'Assigned') {
            $data['status'] = 'Available';
        } elseif (!empty($data['employee_id']) && $previousEmployeeId != $data['employee_id']) {
            $data['status'] = 'Assigned';
            $data['issue_date'] = $data['issue_date'] ?? now()->format('Y-m-d');
        }

        $asset->update($data);

        // Log timeline if newly assigned to someone
        if ($asset->employee_id && $previousEmployeeId != $asset->employee_id) {
            EmployeeTimeline::create([
                'employee_id' => $asset->employee_id,
                'event_type'  => 'Asset',
                'title'       => "Asset Assigned: {$asset->asset_name} ({$asset->asset_code})",
                'description' => "Category: {$asset->category} | Condition: {$asset->condition}",
                'event_date'  => $asset->issue_date ?? now(),
            ]);
        }

        return back()->with('success', 'Asset updated successfully!');
    }

    public function destroy(Asset $asset)
    {
        $asset->delete();
        return back()->with('success', 'Asset removed successfully!');
    }
}