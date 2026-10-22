<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::latest()->get();

        return view('admin.departments', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'dept_id' => 'required|string|max:50|unique:departments,dept_id',
            'dept_name' => 'required|string|max:255'
        ]);

        Department::create($request->all());

        return redirect()->route('admin.departments.index')->with('success', 'Department created successfully.');
    }

    public function update(Request $request, Department $department)
    {
        $request->validate([
            'dept_id' => 'required|string|max:50|unique:departments,dept_id,' . $department->id,
            'dept_name' => 'required|string|max:255'
        ]);

        $department->update($request->all());

        return back()->with('success', 'Department updated successfully!');
    }

    public function toggleStatus(Department $department)
    {
        $department->is_active = !$department->is_active;
        $department->save();

        return response()->json([
             'success' => true,
            'is_active' => $department->is_active,
            'message' => 'Department status updated successfully!'
        ]);
    }
}
