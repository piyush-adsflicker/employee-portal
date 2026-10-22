@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header_title', 'Today\'s Overview')

@section('content')
    <!-- Stat Cards Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        <!-- Total Employees -->
        <div
            class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                <i class="fa-solid fa-users text-2xl"></i>
            </div>
            <div>
                <p class="text-slate-500 text-sm font-medium">Total Employees</p>
                <h3 class="text-2xl font-bold text-slate-800">124</h3>
            </div>
        </div>

        <!-- Present Today -->
        <div
            class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center">
                <i class="fa-solid fa-circle-check text-2xl"></i>
            </div>
            <div>
                <p class="text-slate-500 text-sm font-medium">Present Today</p>
                <h3 class="text-2xl font-bold text-slate-800">110</h3>
            </div>
        </div>

        <!-- Absent Today -->
        <div
            class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-14 h-14 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center">
                <i class="fa-solid fa-circle-xmark text-2xl"></i>
            </div>
            <div>
                <p class="text-slate-500 text-sm font-medium">Absent Today</p>
                <h3 class="text-2xl font-bold text-slate-800">4</h3>
            </div>
        </div>

        <!-- On Leave -->
        <div
            class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-14 h-14 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center">
                <i class="fa-solid fa-plane-departure text-2xl"></i>
            </div>
            <div>
                <p class="text-slate-500 text-sm font-medium">On Leave</p>
                <h3 class="text-2xl font-bold text-slate-800">10</h3>
            </div>
        </div>

    </div>

    <!-- Main Content Area Split -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Next Steps / Quick Actions -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-slate-800">System Setup Progress</h3>
                {{-- <span class="bg-indigo-50 text-indigo-700 text-xs font-bold px-3 py-1 rounded-full">Phase 1</span> --}}
            </div>

            {{-- <p class="text-slate-600 mb-6 text-sm leading-relaxed">
                The core project foundation and authentication are complete. Follow the dependency chain below to configure
                the master data before adding employees.
            </p> --}}

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <a href="#"
                    class="p-4 border border-indigo-100 bg-indigo-50/50 rounded-xl hover:bg-indigo-50 transition-colors group">
                    <i class="fa-solid fa-building text-2xl text-indigo-600 mb-2 block"></i>
                    <h4 class="font-semibold text-slate-800 group-hover:text-indigo-700 transition-colors">1. Departments
                    </h4>
                    <p class="text-xs text-slate-500 mt-1">Create organization structure</p>
                </a>

                <a href="#"
                    class="p-4 border border-slate-100 rounded-xl hover:border-slate-300 hover:bg-slate-50 transition-colors group">
                    <i
                        class="fa-solid fa-id-badge text-2xl text-slate-400 group-hover:text-slate-600 mb-2 block transition-colors"></i>
                    <h4 class="font-semibold text-slate-800">2. Designations</h4>
                    <p class="text-xs text-slate-500 mt-1">Add job titles and roles</p>
                </a>

                <a href="#"
                    class="p-4 border border-slate-100 rounded-xl hover:border-slate-300 hover:bg-slate-50 transition-colors group">
                    <i
                        class="fa-solid fa-clock text-2xl text-slate-400 group-hover:text-slate-600 mb-2 block transition-colors"></i>
                    <h4 class="font-semibold text-slate-800">3. Shifts</h4>
                    <p class="text-xs text-slate-500 mt-1">Configure working hours</p>
                </a>
            </div>
        </div>

        <!-- Real-time Activity Placeholder -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <h3 class="text-lg font-bold text-slate-800 mb-6">Recent Check-ins</h3>

            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center text-slate-600 font-semibold text-xs">
                        AS
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-semibold text-slate-800">Aarav Sharma</h4>
                        <p class="text-xs text-slate-500">Software Engineer</p>
                    </div>
                    <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full">09:00 AM</span>
                </div>

                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center text-slate-600 font-semibold text-xs">
                        PV
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-semibold text-slate-800">Pooja Verma</h4>
                        <p class="text-xs text-slate-500">HR Manager</p>
                    </div>
                    <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full">09:05 AM</span>
                </div>

                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center text-slate-600 font-semibold text-xs">
                        RP
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-semibold text-slate-800">Rohan Patel</h4>
                        <p class="text-xs text-slate-500">Marketing Lead</p>
                    </div>
                    <span class="text-xs font-bold text-amber-600 bg-amber-50 px-2.5 py-1 rounded-full">09:32 AM</span>
                </div>
            </div>

            <button
                class="w-full mt-6 py-2 border border-slate-200 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-50 hover:border-slate-300 transition-colors">
                View All Logs
            </button>
        </div>

    </div>
@endsection
