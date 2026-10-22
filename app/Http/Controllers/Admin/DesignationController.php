<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Designation;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    public function index()
    {
        $designations = Designation::latest()->get();

        return view('admin.designations', compact('designations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'designation_id' => 'required|string|max:50|unique:designations,designation_id',
            'designation_name' => 'required|string|max:255'
        ]);

        Designation::create($request->only(['designation_id', 'designation_name']));

        return redirect()->route('admin.designations.index')->with('success', 'Designation created successfully.');
    }

    public function update(Request $request, Designation $designation)
    {
        $request->validate([
            'designation_id' => 'required|string|max:50|unique:designations,designation_id,' . $designation->id,
            'designation_name' => 'required|string|max:255',
        ]);

        $designation->update($request->only(['designation_id', 'designation_name']));

        return back()->with('success', 'Designation updated successfully!');
    }

    public function toggleStatus(Designation $designation)
    {
        $designation->is_active = !$designation->is_active;
        $designation->save();

        return response()->json([
            'success' => true,
            'is_active' => $designation->is_active,
            'message' => 'Designation status updated successfully!'
        ]);
    }
}
