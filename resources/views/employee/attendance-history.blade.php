@extends('layouts.employee')

@section('title', 'Attendance History')
@section('header_title', 'Attendance History')

@section('content')
    <div class="max-w-7xl mx-auto space-y-6">

        <div class="mb-6">
            <h2 class="text-2xl font-bold text-slate-800">Attendance Records</h2>
            <p class="text-sm text-slate-500 mt-1">Review your historical attendance logs and filter by date range.</p>
        </div>

        <!-- Summary Statistic Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex flex-col">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Present</span>
                <div class="flex items-end gap-2">
                    <span class="text-3xl font-extrabold text-teal-600">{{ $presentCount }}</span>
                    <span class="text-sm text-slate-400 font-medium mb-1">Days</span>
                </div>
            </div>
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex flex-col">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Late</span>
                <div class="flex items-end gap-2">
                    <span class="text-3xl font-extrabold text-amber-500">{{ $lateCount }}</span>
                    <span class="text-sm text-slate-400 font-medium mb-1">Days</span>
                </div>
            </div>
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex flex-col">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Absent</span>
                <div class="flex items-end gap-2">
                    <span class="text-3xl font-extrabold text-red-500">{{ $absentCount }}</span>
                    <span class="text-sm text-slate-400 font-medium mb-1">Days</span>
                </div>
            </div>
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex flex-col">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">On Leave</span>
                <div class="flex items-end gap-2">
                    <span class="text-3xl font-extrabold text-blue-500">{{ $leaveCount }}</span>
                    <span class="text-sm text-slate-400 font-medium mb-1">Days</span>
                </div>
            </div>
        </div>

        <!-- Filter Form -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100">
            <form action="{{ route('employee.attendance.history') }}" method="GET"
                class="flex flex-col sm:flex-row items-end gap-4">
                <div class="w-full sm:w-auto">
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">From Date</label>
                    <div class="relative">
                        <i class="fa-regular fa-calendar absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="date" name="from_date" value="{{ $fromDate }}"
                            class="w-full sm:w-48 pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm">
                    </div>
                </div>
                <div class="w-full sm:w-auto">
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">To Date</label>
                    <div class="relative">
                        <i class="fa-regular fa-calendar absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="date" name="to_date" value="{{ $toDate }}"
                            class="w-full sm:w-48 pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm">
                    </div>
                </div>
                <div class="w-full sm:w-auto flex gap-3">
                    <button type="submit"
                        class="px-6 py-2.5 bg-teal-600 hover:bg-teal-700 text-white font-medium rounded-lg transition-colors w-full sm:w-auto shadow-sm">
                        Apply Filter
                    </button>
                    <a href="{{ route('employee.attendance.history') }}"
                        class="px-6 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 font-medium rounded-lg transition-colors text-center w-full sm:w-auto">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Attendance Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-200">
                            <th class="px-6 py-4 font-semibold">Date</th>
                            <th class="px-6 py-4 font-semibold">Check In</th>
                            <th class="px-6 py-4 font-semibold">Check Out</th>
                            <th class="px-6 py-4 font-semibold text-center">Working Hours</th>
                            <th class="px-6 py-4 font-semibold text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($attendances as $att)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="font-bold text-slate-800">{{ $att->attendance_date->format('M d, Y') }}</p>
                                    <p class="text-xs text-slate-500">{{ $att->attendance_date->format('l') }}</p>
                                </td>
                                <td class="px-6 py-4 font-medium text-slate-700">
                                    {{ $att->check_in_time ? $att->check_in_time->format('h:i A') : '--' }}
                                </td>
                                <td class="px-6 py-4 font-medium text-slate-700">
                                    {{ $att->check_out_time ? $att->check_out_time->format('h:i A') : '--' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="bg-slate-100 text-slate-600 px-3 py-1.5 rounded-lg font-bold text-xs">{{ $att->working_hours }}
                                        hrs</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if ($att->status == 'Present')
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-teal-100 text-teal-700 border border-teal-200">Present</span>
                                    @elseif($att->status == 'Late')
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700 border border-amber-200">Late</span>
                                    @elseif($att->status == 'Absent')
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200">Absent</span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700 border border-blue-200">{{ $att->status }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fa-solid fa-folder-open text-4xl text-slate-300 mb-3"></i>
                                        <p class="text-base font-medium">No attendance records found for this period.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Links -->
            @if ($attendances->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
                    {{ $attendances->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection
