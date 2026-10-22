@extends('layouts.admin')
@section('title', 'Attendance Management')
@section('header_title', 'Employee Attendance Log')

@section('content')
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-xl font-bold text-slate-800">Attendance Log</h3>
                <p class="text-sm text-slate-500 mt-1">Monitor, filter, and review organization-wide employee attendance.</p>
            </div>
        </div>

        <!-- Overview Stats Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-3">
            <div class="bg-white p-3 rounded-xl shadow-sm border border-slate-100 flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Total Records</p>
                    <h4 class="text-2xl font-bold text-slate-800">{{ $stats['total'] }}</h4>
                </div>
                <div class="w-10 h-10 bg-slate-50 text-slate-400 rounded-lg flex items-center justify-center"><i
                        class="fa-solid fa-users"></i></div>
            </div>
            <div class="bg-white p-3 rounded-xl shadow-sm border border-slate-100 flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Present</p>
                    <h4 class="text-2xl font-bold text-emerald-600">{{ $stats['present'] }}</h4>
                </div>
                <div class="w-10 h-10 bg-emerald-50 text-emerald-500 rounded-lg flex items-center justify-center"><i
                        class="fa-solid fa-user-check"></i></div>
            </div>
            <div class="bg-white p-3 rounded-xl shadow-sm border border-slate-100 flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Late</p>
                    <h4 class="text-2xl font-bold text-amber-500">{{ $stats['late'] }}</h4>
                </div>
                <div class="w-10 h-10 bg-amber-50 text-amber-500 rounded-lg flex items-center justify-center"><i
                        class="fa-solid fa-user-clock"></i></div>
            </div>
            <div class="bg-white p-3 rounded-xl shadow-sm border border-slate-100 flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Absent</p>
                    <h4 class="text-2xl font-bold text-red-500">{{ $stats['absent'] }}</h4>
                </div>
                <div class="w-10 h-10 bg-red-50 text-red-500 rounded-lg flex items-center justify-center"><i
                        class="fa-solid fa-user-xmark"></i></div>
            </div>
            <div class="bg-white p-3 rounded-xl shadow-sm border border-slate-100 flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">On Leave</p>
                    <h4 class="text-2xl font-bold text-blue-500">{{ $stats['leave'] }}</h4>
                </div>
                <div class="w-10 h-10 bg-blue-50 text-blue-500 rounded-lg flex items-center justify-center"><i
                        class="fa-solid fa-plane"></i></div>
            </div>
        </div>

        <!-- Advanced Filter Form -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-3">
            <form action="{{ route('admin.attendance.index') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                    <!-- From Date -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">From Date</label>
                        <input type="date" name="from_date" value="{{ $fromDate }}"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm transition-all">
                    </div>
                    <!-- To Date -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">To Date</label>
                        <input type="date" name="to_date" value="{{ $toDate }}"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm transition-all">
                    </div>
                    <!-- Employee Filter -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Employee</label>
                        <select name="employee_id"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm transition-all">
                            <option value="">All Employees</option>
                            @foreach ($employees as $emp)
                                <option value="{{ $emp->id }}"
                                    {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                                    {{ $emp->emp_name }} ({{ $emp->emp_id }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Department Filter -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Department</label>
                        <select name="department_id"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm transition-all">
                            <option value="">All Departments</option>
                            @foreach ($departments as $dept)
                                <option value="{{ $dept->id }}"
                                    {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->dept_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Status Filter -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Status</label>
                        <select name="status"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm transition-all">
                            <option value="">All Statuses</option>
                            <option value="Present" {{ request('status') == 'Present' ? 'selected' : '' }}>Present</option>
                            <option value="Late" {{ request('status') == 'Late' ? 'selected' : '' }}>Late</option>
                            <option value="Half Day" {{ request('status') == 'Half Day' ? 'selected' : '' }}>Half Day
                            </option>
                            <option value="Absent" {{ request('status') == 'Absent' ? 'selected' : '' }}>Absent</option>
                            <option value="Leave" {{ request('status') == 'Leave' ? 'selected' : '' }}>On Leave</option>
                        </select>
                    </div>
                    <div class="md:col-span-2 lg:col-span-3 flex items-end justify-end gap-3 pt-4 lg:pt-0">
                        <a href="{{ route('admin.attendance.index') }}"
                            class="px-5 py-2 bg-white border border-slate-200 text-slate-600 rounded-lg font-medium hover:bg-slate-50 transition-colors text-sm">
                            Reset Filters
                        </a>
                        <button type="submit"
                            class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-colors shadow-sm text-sm flex items-center gap-2">
                            <i class="fa-solid fa-filter"></i> Apply Filters
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Attendance Data Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden pb-12">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-200">
                            <th class="px-4 py-3 font-semibold">Date / Shift</th>
                            <th class="px-4 py-3 font-semibold">Employee Details</th>
                            <th class="px-4 py-3 font-semibold">Check In</th>
                            <th class="px-4 py-3 font-semibold">Check Out</th>
                            <th class="px-4 py-3 font-semibold text-center">Working Hours</th>
                            <th class="px-4 py-3 font-semibold text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($attendances as $att)
                            <tr class="hover:bg-slate-50/50 transition-colors align-top">
                                <td class="px-4 py-3">
                                    <p class="font-bold text-slate-800">{{ $att->attendance_date->format('M d, Y') }}</p>
                                    <p class="text-[11px] text-slate-500 font-medium mt-0.5"><i
                                            class="fa-regular fa-clock"></i> {{ $att->shift->shift_name ?? 'No Shift' }}
                                    </p>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-xs shrink-0 overflow-hidden">
                                            @if ($att->employee->profile_photo)
                                                <img src="{{ asset($att->employee->profile_photo) }}"
                                                    class="w-full h-full object-cover">
                                            @else
                                                {{ substr($att->employee->emp_name, 0, 1) }}
                                            @endif
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-800">{{ $att->employee->emp_name }}</p>
                                            <p class="text-[11px] text-slate-500">{{ $att->employee->emp_id }} &bull;
                                                {{ $att->employee->department->dept_name ?? 'No Dept' }}</p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Direct View Check In Column -->
                                <td class="px-4 py-3">
                                    @if ($att->check_in_time)
                                        <p class="font-semibold text-slate-800">{{ $att->check_in_time->format('h:i A') }}
                                        </p>
                                        @if ($att->late_minutes > 0)
                                            <p class="text-[10px] text-red-500 font-bold mb-1">{{ $att->late_minutes }}
                                                mins late</p>
                                        @endif

                                        <div class="mt-1.5 space-y-1">
                                            @if ($att->check_in_location)
                                                <p class="text-[10px] text-slate-500 leading-tight">
                                                    <i class="fa-solid fa-map-location-dot text-indigo-400 w-3"></i>
                                                    {{ $att->check_in_location }}
                                                    <a href="https://maps.google.com/?q={{ $att->check_in_coordinates }}"
                                                        target="_blank" class="hover:text-indigo-600 hover:underline">
                                                        {{ $att->check_in_coordinates }}
                                                    </a>
                                                </p>
                                            @endif
                                            @if ($att->check_in_ip)
                                                <p class="text-[10px] text-slate-500 leading-tight">
                                                    <i class="fa-solid fa-network-wired text-indigo-400 w-3"></i>
                                                    {{ $att->check_in_ip }}
                                                </p>
                                            @endif
                                            @if ($att->check_in_device)
                                                <p class="text-[10px] text-slate-500 leading-tight"
                                                    title="{{ $att->check_in_device }}">
                                                    <i class="fa-solid fa-display text-indigo-400 w-3"></i>
                                                    {{ $att->check_in_device }}
                                                </p>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-slate-400">--:--</span>
                                    @endif
                                </td>

                                <!-- Direct View Check Out Column -->
                                <td class="px-4 py-3">
                                    @if ($att->check_out_time)
                                        <p class="font-semibold text-slate-800">
                                            {{ $att->check_out_time->format('h:i A') }}</p>

                                        <div class="mt-1.5 space-y-1">
                                            @if ($att->check_out_location)
                                                <p class="text-[10px] text-slate-500 leading-tight">
                                                    <i class="fa-solid fa-map-location-dot text-indigo-400 w-3"></i>
                                                    {{ $att->check_out_location }}
                                                    <a href="https://maps.google.com/?q={{ $att->check_out_coordinates }}"
                                                        target="_blank" class="hover:text-indigo-600 hover:underline">
                                                        {{ $att->check_out_coordinates }}
                                                    </a>
                                                </p>
                                            @endif
                                            @if ($att->check_out_ip)
                                                <p class="text-[10px] text-slate-500 leading-tight">
                                                    <i class="fa-solid fa-network-wired text-indigo-400 w-3"></i>
                                                    {{ $att->check_out_ip }}
                                                </p>
                                            @endif
                                            @if ($att->check_out_device)
                                                <p class="text-[10px] text-slate-500 leading-tight"
                                                    title="{{ $att->check_out_device }}">
                                                    <i class="fa-solid fa-display text-indigo-400 w-3"></i>
                                                    {{ $att->check_out_device }}
                                                </p>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-slate-400">--:--</span>
                                    @endif
                                </td>

                                <!-- Working Hours & Deductions Column -->
                                <td class="px-4 py-3 text-center">
                                    @if ($att->working_hours > 0)
                                        <div class="flex flex-col items-center">
                                            <span
                                                class="bg-slate-100 text-slate-700 px-2.5 py-1 rounded font-bold text-xs">{{ $att->working_hours }}h</span>
                                            @if ($att->total_break_minutes > 0 || $att->early_logout_minutes > 0)
                                                <div class="text-[9px] mt-1 space-x-1 font-semibold flex items-center">
                                                    @if ($att->total_break_minutes > 0)
                                                        <span class="text-amber-600" title="Total Break Time"><i
                                                                class="fa-solid fa-mug-hot"></i>
                                                            {{ $att->total_break_minutes }}m</span>
                                                    @endif
                                                    @if ($att->early_logout_minutes > 0)
                                                        <span class="text-rose-600" title="Early Logout Time"><i
                                                                class="fa-solid fa-person-running"></i>
                                                            {{ $att->early_logout_minutes }}m</span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-slate-400">--</span>
                                    @endif
                                </td>

                                <!-- Status Column -->
                                <td class="px-4 py-3 text-right">
                                    @if ($att->status == 'Present')
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">Present</span>
                                    @elseif($att->status == 'Half Day')
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-100 text-amber-700 border border-amber-200">Half
                                            Day</span>
                                    @elseif($att->status == 'Late')
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-orange-100 text-orange-700 border border-orange-200">Late</span>
                                    @elseif($att->status == 'Absent')
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-red-100 text-red-700 border border-red-200">Absent</span>
                                    @elseif($att->status == 'Leave')
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-100 text-blue-700 border border-blue-200">On
                                            Leave</span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600 border border-slate-200">{{ $att->status }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fa-solid fa-clipboard-list text-4xl text-slate-300 mb-3"></i>
                                        <p class="text-base font-medium">No attendance records found.</p>
                                        <p class="text-xs mt-1">Try adjusting your filters or date range.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($attendances->hasPages())
                <div class="px-4 py-3 border-t border-slate-100 bg-slate-50">
                    {{ $attendances->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
