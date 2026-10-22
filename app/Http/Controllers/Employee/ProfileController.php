<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash; // Added Hash facade

class ProfileController extends Controller
{
    public function index()
    {
        $employee = Auth::user()->load(['department', 'designation', 'shift']);
        return view('employee.profile', compact('employee'));
    }

    public function update(Request $request)
    {
        $employee = Auth::user();

        // Added password validation (nullable, minimum 8 characters)
        $request->validate([
            'emp_name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', 'unique:employees,email,' . $employee->id],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'password' => ['nullable', 'string', 'min:8'], // Password rule
        ]);

        if ($request->hasFile('profile_photo')) {
            $uploadPath = public_path('uploads/profiles');

            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            if ($employee->profile_photo && File::exists(public_path($employee->profile_photo))) {
                File::delete(public_path($employee->profile_photo));
            }

            $file = $request->file('profile_photo');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadPath, $filename);

            $employee->profile_photo = 'uploads/profiles/' . $filename;
        }

        // Check if user provided a new password and hash it
        if ($request->filled('password')) {
            $employee->password = Hash::make($request->password);
        }

        $employee->emp_name = $request->emp_name;
        $employee->email = $request->email;
        $employee->phone_number = $request->phone_number;

        $employee->save();

        return back()->with('success', 'Profile updated successfully!');
    }
}
