<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->input('year', now()->year);

        $holidays = Holiday::whereYear('date', $year)
            ->orderBy('date', 'asc')
            ->get();

        // Calendar payload formatted for simple frontend rendering
        $calendarEvents = $holidays->map(function ($holiday) {
            return [
                'id' => $holiday->id,
                'title' => $holiday->holiday_name,
                'start' => $holiday->date->format('Y-m-d'),
                'description' => $holiday->description ?? '',
                'is_active' => (bool) $holiday->is_active,
            ];
        });

        return view('admin.holidays', compact('holidays', 'calendarEvents', 'year'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'holiday_name' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date', 'unique:holidays,date'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        Holiday::create([
            'holiday_name' => $request->holiday_name,
            'date' => $request->date,
            'description' => $request->description,
            'is_active' => true,
        ]);

        return back()->with('success', 'Holiday created successfully!');
    }

    public function update(Request $request, Holiday $holiday)
    {
        $request->validate([
            'holiday_name' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date', 'unique:holidays,date,' . $holiday->id],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $holiday->update([
            'holiday_name' => $request->holiday_name,
            'date' => $request->date,
            'description' => $request->description,
        ]);

        return back()->with('success', 'Holiday updated successfully!');
    }

    public function destroy(Holiday $holiday)
    {
        $holiday->delete();
        return back()->with('success', 'Holiday deleted successfully!');
    }

    public function toggleStatus(Holiday $holiday)
    {
        $holiday->is_active = !$holiday->is_active;
        $holiday->save();

        return response()->json([
            'success' => true,
            'is_active' => $holiday->is_active,
            'message' => 'Holiday status updated successfully!'
        ]);
    }
}
