@extends('layouts.admin')

@section('title', 'Employees')
@section('header_title', 'Employee Directory')

@section('content')
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div>
                <h3 class="text-lg font-bold text-slate-800">All Employees</h3>
                <p class="text-sm text-slate-500 mt-1">Manage employee profiles and system access.</p>
            </div>
            <a href="{{ route('admin.employees.create') }}"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-user-plus"></i>
                Add Employee
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-200">
                        <th class="px-6 py-4 font-semibold">Employee</th>
                        <th class="px-6 py-4 font-semibold">ID & Contact</th>
                        <th class="px-6 py-4 font-semibold">Role / Dept</th>
                        <th class="px-6 py-4 font-semibold text-center">Status</th>
                        <th class="px-6 py-4 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($employees as $emp)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold shrink-0 overflow-hidden">
                                        @if ($emp->profile_photo)
                                            <img src="{{ asset($emp->profile_photo) }}" class="w-full h-full object-cover">
                                        @else
                                            {{ substr($emp->emp_name, 0, 1) }}
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800">{{ $emp->emp_name }}</p>
                                        <p class="text-xs text-slate-500">Joined:
                                            {{ $emp->joining_date ? $emp->joining_date->format('M d, Y') : 'N/A' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-medium text-slate-800">{{ $emp->emp_id }}</p>
                                <p class="text-xs text-slate-500">
                                    {{ $emp->email ?? ($emp->phone_number ?? 'No contact info') }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-slate-800 font-medium">
                                    {{ $emp->designation->designation_name ?? 'Unassigned' }}</p>
                                <p class="text-xs text-slate-500">{{ $emp->department->dept_name ?? 'No Department' }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if ($emp->is_active)
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Active
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('admin.employees.attendance', $emp->id) }}"
                                    class="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 hover:bg-teal-100 transition-colors inline-flex items-center justify-center"
                                    title="View Attendance">
                                    <i class="fa-solid fa-calendar-days"></i>
                                </a>
                                <a href="{{ route('admin.employees.show', $emp->id) }}"
                                    class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition-colors inline-flex items-center justify-center"
                                    title="View">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.employees.edit', $emp->id) }}"
                                    class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition-colors inline-flex items-center justify-center"
                                    title="Edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fa-solid fa-users-slash text-4xl text-slate-300 mb-3"></i>
                                    <p class="text-base font-medium">No employees found</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
