@extends('layouts.admin')

@section('title', 'Edit Employee')
@section('header_title', 'Update Employee Profile')

@section('content')
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Edit Profile: {{ $employee->emp_name }}</h2>
                <p class="text-slate-500 text-sm mt-1">Modify employee information and system access credentials.</p>
            </div>
            <a href="{{ route('admin.employees.index') }}"
                class="px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-lg hover:bg-slate-50 transition-colors font-medium flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-arrow-left"></i> Back to list
            </a>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 text-red-600 border-l-4 border-red-500 rounded-r-lg text-sm shadow-sm">
                <div class="flex items-center gap-2 font-bold mb-1">
                    <i class="fa-solid fa-circle-exclamation"></i> Please fix the following errors:
                </div>
                <ul class="list-disc pl-6 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.employees.update', $employee->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                <!-- Basic Information Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center"><i
                                class="fa-solid fa-address-card"></i></div>
                        <h3 class="font-bold text-slate-800">Personal Information</h3>
                    </div>

                    <div class="p-6 space-y-5">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-slate-700 text-sm font-semibold mb-2" for="emp_id">Employee ID
                                    <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <i
                                        class="fa-solid fa-id-badge absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    <input type="text" name="emp_id" id="emp_id" required
                                        value="{{ old('emp_id', $employee->emp_id) }}"
                                        class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all text-sm">
                                </div>
                            </div>

                            <div>
                                <label class="block text-slate-700 text-sm font-semibold mb-2" for="joining_date">Joining
                                    Date</label>
                                <div class="relative">
                                    <i
                                        class="fa-solid fa-calendar-day absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    <input type="date" name="joining_date" id="joining_date"
                                        value="{{ old('joining_date', $employee->joining_date ? $employee->joining_date->format('Y-m-d') : '') }}"
                                        class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all text-sm">
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-slate-700 text-sm font-semibold mb-2" for="emp_name">Full Name <span
                                    class="text-red-500">*</span></label>
                            <div class="relative">
                                <i class="fa-solid fa-user absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input type="text" name="emp_name" id="emp_name" required
                                    value="{{ old('emp_name', $employee->emp_name) }}"
                                    class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all text-sm">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-slate-700 text-sm font-semibold mb-2" for="email">Email
                                    Address</label>
                                <div class="relative">
                                    <i
                                        class="fa-solid fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    <input type="email" name="email" id="email"
                                        value="{{ old('email', $employee->email) }}"
                                        class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all text-sm">
                                </div>
                            </div>
                            <div>
                                <label class="block text-slate-700 text-sm font-semibold mb-2" for="phone_number">Phone
                                    Number</label>
                                <div class="relative">
                                    <i
                                        class="fa-solid fa-phone absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    <input type="text" name="phone_number" id="phone_number"
                                        value="{{ old('phone_number', $employee->phone_number) }}"
                                        class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all text-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Work & Auth Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center"><i
                                class="fa-solid fa-briefcase"></i></div>
                        <h3 class="font-bold text-slate-800">Employment Details</h3>
                    </div>

                    <div class="p-6 space-y-5">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-slate-700 text-sm font-semibold mb-2"
                                    for="department_id">Department</label>
                                <div class="relative">
                                    <i
                                        class="fa-solid fa-building absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 z-10"></i>
                                    <select name="department_id" id="department_id"
                                        class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all text-sm appearance-none">
                                        <option value="">-- Select Department --</option>
                                        @foreach ($departments as $dept)
                                            <option value="{{ $dept->id }}"
                                                {{ old('department_id', $employee->department_id) == $dept->id ? 'selected' : '' }}>
                                                {{ $dept->dept_name }}</option>
                                        @endforeach
                                    </select>
                                    <i
                                        class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                                </div>
                            </div>
                            <div>
                                <label class="block text-slate-700 text-sm font-semibold mb-2"
                                    for="designation_id">Designation</label>
                                <div class="relative">
                                    <i
                                        class="fa-solid fa-id-badge absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 z-10"></i>
                                    <select name="designation_id" id="designation_id"
                                        class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all text-sm appearance-none">
                                        <option value="">-- Select Designation --</option>
                                        @foreach ($designations as $desig)
                                            <option value="{{ $desig->id }}"
                                                {{ old('designation_id', $employee->designation_id) == $desig->id ? 'selected' : '' }}>
                                                {{ $desig->designation_name }}</option>
                                        @endforeach
                                    </select>
                                    <i
                                        class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-slate-700 text-sm font-semibold mb-2" for="shift_id">Assigned
                                Shift</label>
                            <div class="relative">
                                <i
                                    class="fa-solid fa-clock absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 z-10"></i>
                                <select name="shift_id" id="shift_id"
                                    class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all text-sm appearance-none">
                                    <option value="">-- Select Shift --</option>
                                    @foreach ($shifts as $shift)
                                        <option value="{{ $shift->id }}"
                                            {{ old('shift_id', $employee->shift_id) == $shift->id ? 'selected' : '' }}>
                                            {{ $shift->shift_name }}
                                            ({{ \Carbon\Carbon::parse($shift->start_time)->format('h:i A') }} -
                                            {{ \Carbon\Carbon::parse($shift->end_time)->format('h:i A') }})
                                        </option>
                                    @endforeach
                                </select>
                                <i
                                    class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                            </div>
                        </div>

                        <div>
                            <label class="block text-slate-700 text-sm font-semibold mb-2" for="is_active">Account
                                Status</label>
                            <div class="relative">
                                <i
                                    class="fa-solid fa-power-off absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 z-10"></i>
                                <select name="is_active" id="is_active"
                                    class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all text-sm appearance-none">
                                    <option value="1" {{ old('is_active', $employee->is_active) ? 'selected' : '' }}>
                                        Active (Can Login)</option>
                                    <option value="0" {{ old('is_active', $employee->is_active) ? '' : 'selected' }}>
                                        Inactive (Suspended)</option>
                                </select>
                                <i
                                    class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                            </div>
                        </div>

                        <!-- Password with Toggle (Optional on Edit) -->
                        <div class="pt-4 mt-4 border-t border-slate-100">
                            <label class="block text-slate-700 text-sm font-semibold mb-1" for="password">Change
                                Password</label>
                            <p class="text-[11px] text-slate-500 mb-3">Leave blank if you do not want to change the current
                                password.</p>
                            <div class="relative">
                                <i class="fa-solid fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input type="password" name="password" id="password"
                                    class="w-full pl-10 pr-12 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all text-sm">
                                <button type="button" id="togglePassword"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-indigo-600 focus:outline-none transition-colors">
                                    <i class="fa-solid fa-eye" id="eyeIcon"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 360° CRM Extended Information Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mt-6">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center"><i
                            class="fa-solid fa-user-shield"></i></div>
                    <h3 class="font-bold text-slate-800">360° CRM: Compensation, Skills & Emergency Contact</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-slate-700 text-sm font-semibold mb-2">Employment Type *</label>
                        <select name="employment_type"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm">
                            @foreach (['Full-Time', 'Probation', 'Intern', 'Contract'] as $type)
                                <option value="{{ $type }}"
                                    {{ old('employment_type', $employee->employment_type ?? 'Full-Time') == $type ? 'selected' : '' }}>
                                    {{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-slate-700 text-sm font-semibold mb-2">Annual CTC / Salary (₹)</label>
                        <input type="number" step="0.01" name="salary_ctc"
                            value="{{ old('salary_ctc', $employee->salary_ctc ?? '') }}" placeholder="e.g. 600000"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-slate-700 text-sm font-semibold mb-2">Skills (Comma separated)</label>
                        <input type="text" name="skills" value="{{ old('skills', $employee->skills ?? '') }}"
                            placeholder="Laravel, React, MySQL"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-slate-700 text-sm font-semibold mb-2">Emergency Contact Name</label>
                        <input type="text" name="emergency_contact_name"
                            value="{{ old('emergency_contact_name', $employee->emergency_contact_name ?? '') }}"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-slate-700 text-sm font-semibold mb-2">Emergency Contact Phone</label>
                        <input type="text" name="emergency_contact_phone"
                            value="{{ old('emergency_contact_phone', $employee->emergency_contact_phone ?? '') }}"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-slate-700 text-sm font-semibold mb-2">Relationship</label>
                        <input type="text" name="emergency_contact_relation"
                            value="{{ old('emergency_contact_relation', $employee->emergency_contact_relation ?? '') }}"
                            placeholder="Father, Spouse, etc."
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm">
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-slate-700 text-sm font-semibold mb-2">Previous Experience Summary</label>
                        <textarea name="previous_experience" rows="2"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm">{{ old('previous_experience', $employee->previous_experience ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('admin.employees.index') }}"
                    class="px-6 py-2.5 text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 rounded-lg font-medium transition-colors shadow-sm">
                    Cancel
                </a>
                <button type="submit"
                    class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-colors shadow-sm flex items-center gap-2">
                    <i class="fa-solid fa-save"></i> Save Changes
                </button>
            </div>
        </form>
    </div>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const eyeIcon = document.querySelector('#eyeIcon');

        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            eyeIcon.classList.toggle('fa-eye');
            eyeIcon.classList.toggle('fa-eye-slash');
        });
    </script>
@endsection
