<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function index()
    {
        $shifts = Shift::latest()->get();
        return view('admin.shifts', compact('shifts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'shift_name' => ['required', 'string', 'max:255', 'unique:shifts,shift_name'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i'],
            'working_hours' => ['required', 'numeric', 'min:0', 'max:24'],
            'grace_period' => ['required', 'integer', 'min:0'],
        ]);

        Shift::create($request->all());

        return back()->with('success', 'Shift added successfully!');
    }

    public function update(Request $request, Shift $shift)
    {
        $request->validate([
            'shift_name' => ['required', 'string', 'max:255', 'unique:shifts,shift_name,' . $shift->id],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i'],
            'working_hours' => ['required', 'numeric', 'min:0', 'max:24'],
            'grace_period' => ['required', 'integer', 'min:0'],
        ]);

        $shift->update($request->all());

        return back()->with('success', 'Shift updated successfully!');
    }

    public function destroy(Shift $shift)
    {
        $shift->delete();
        return back()->with('success', 'Shift deleted successfully!');
    }
}
