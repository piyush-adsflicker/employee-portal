@extends('layouts.employee')

@section('title', 'Leave Application')
@section('header_title', 'Leave Management')

@section('content')
    <div class="max-w-7xl mx-auto space-y-3">

        <div class="mb-4">
            <h2 class="text-2xl font-bold text-slate-800">My Leave Dashboard</h2>
            <p class="text-sm text-slate-500 mt-1">Check your balances, apply for time off, and track your request statuses.
            </p>
        </div>

        <!-- Top Section: Balances & Application Form -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-3">

            <!-- Leave Balances -->
            <div class="lg:col-span-1 space-y-2">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-wallet text-teal-600"></i> Available Balances
                </h3>

                @forelse($leaveBalances as $balance)
                    <div
                        class="bg-white p-3 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between transition-transform hover:-translate-y-1">
                        <div>
                            <p class="font-bold text-slate-700">{{ $balance->leaveType->name }}</p>
                            <p class="text-xs text-slate-400 mt-0.5">Remaining Balance</p>
                        </div>
                        <div
                            class="w-12 h-12 bg-teal-50 rounded-full flex items-center justify-center border border-teal-100">
                            <span class="text-xl font-extrabold text-teal-600">{{ (float) $balance->balance }}</span>
                        </div>
                    </div>
                @empty
                    <div
                        class="bg-slate-50 border border-slate-200 border-dashed rounded-2xl p-6 text-center text-slate-500">
                        <i class="fa-solid fa-face-frown-open text-3xl mb-2 text-slate-300"></i>
                        <p class="text-sm font-medium">No leave balances assigned.</p>
                        <p class="text-xs mt-1">Please contact administration.</p>
                    </div>
                @endforelse

            </div>

            <!-- Apply for Leave Form -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden h-fit">
                <div class="px-4 py-3 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="font-bold text-slate-800">Apply for Leave</h3>
                </div>

                <form action="{{ route('employee.leaves.apply') }}" method="POST" class="p-3 space-y-3">
                    @csrf

                    @if ($errors->any())
                        <div class="p-2 bg-red-50 text-red-600 border border-red-100 rounded-lg text-sm mb-4">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div>
                        <label class="block text-slate-700 text-sm font-semibold mb-1" for="leave_type_id">Leave Type <span
                                class="text-red-500">*</span></label>
                        <div class="relative">
                            <i class="fa-solid fa-tag absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 z-10"></i>
                            <select name="leave_type_id" id="leave_type_id" required
                                class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all text-sm appearance-none">
                                <option value="">-- Select Category --</option>
                                @foreach ($availableLeaveTypes as $type)
                                    <option value="{{ $type->id }}"
                                        {{ old('leave_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                            <i
                                class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-slate-700 text-sm font-semibold mb-1" for="start_date">Start Date <span
                                    class="text-red-500">*</span></label>
                            <div class="relative">
                                <i
                                    class="fa-regular fa-calendar-check absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input type="date" name="start_date" id="start_date" required min="{{ date('Y-m-d') }}"
                                    value="{{ old('start_date') }}"
                                    class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all text-sm">
                            </div>
                        </div>
                        <div>
                            <label class="block text-slate-700 text-sm font-semibold mb-1" for="end_date">End Date <span
                                    class="text-red-500">*</span></label>
                            <div class="relative">
                                <i
                                    class="fa-regular fa-calendar-xmark absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input type="date" name="end_date" id="end_date" required min="{{ date('Y-m-d') }}"
                                    value="{{ old('end_date') }}"
                                    class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all text-sm">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-slate-700 text-sm font-semibold mb-1" for="reason">Reason for Leave <span
                                class="text-red-500">*</span></label>
                        <textarea name="reason" id="reason" rows="3" required placeholder="Provide a brief reason for your request..."
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all text-sm resize-none">{{ old('reason') }}</textarea>
                    </div>

                    <div class="pt-1">
                        <button type="submit"
                            class="w-full sm:w-auto px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-xl shadow-md transition-colors flex items-center justify-center gap-2">
                            <i class="fa-regular fa-paper-plane"></i> Submit Request
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Request History Section -->
        <div class="pt-3">
            <h3 class="font-bold text-slate-800 mb-3 flex items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left text-teal-600"></i> My Leave History
            </h3>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm border-collapse">
                        <thead
                            class="bg-slate-50 text-slate-500 text-xs uppercase border-b border-slate-200 tracking-wider">
                            <tr>
                                <th class="px-4 py-2 font-semibold">Date Requested</th>
                                <th class="px-4 py-2 font-semibold">Leave Type</th>
                                <th class="px-4 py-2 font-semibold">Leave Reason</th>
                                <th class="px-4 py-2 font-semibold">Duration</th>
                                <th class="px-4 py-2 font-semibold text-center">Days</th>
                                <th class="px-4 py-2 font-semibold text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($leaveRequests as $req)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-4 py-2">
                                        <span
                                            class="text-slate-600 font-medium">{{ $req->created_at->format('M d, Y') }}</span>
                                    </td>
                                    <td class="px-4 py-2">
                                        <p class="font-bold text-slate-800">{{ $req->leaveType->name }}</p>
                                    </td>
                                    <td class="px-4 py-2">
                                        <p class="text-xs text-slate-500 mt-1 max-w-[200px]"
                                            title="{{ $req->reason }}">{{ $req->reason }}</p>
                                    </td>
                                    <td class="px-4 py-2">
                                        <span class="text-slate-700 font-medium">{{ $req->start_date->format('M d') }} -
                                            {{ $req->end_date->format('M d, Y') }}</span>
                                    </td>
                                    <td class="px-4 py-2 text-center font-bold text-slate-700">
                                        {{ (float) $req->days }}
                                    </td>
                                    <td class="px-4 py-2 text-right">
                                        @if ($req->status == 'Pending')
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-100 text-amber-700 border border-amber-200">Pending</span>
                                        @elseif($req->status == 'Approved')
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">Approved</span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-red-100 text-red-700 border border-red-200">Rejected</span>
                                            @if ($req->admin_remark)
                                                <p class="text-[10px] text-red-500 mt-1 cursor-help"
                                                    title="{{ $req->admin_remark }}"><i
                                                        class="fa-solid fa-circle-info"></i> Hover to see reason</p>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                        <i class="fa-solid fa-inbox text-3xl mb-3 text-slate-300"></i>
                                        <p>You have not submitted any leave requests.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <!-- Auto-fill end date if empty when start date is selected -->
    <script>
        document.getElementById('start_date').addEventListener('change', function() {
            const endDate = document.getElementById('end_date');
            if (!endDate.value) {
                endDate.value = this.value;
            }
            // Ensure end date cannot be before start date
            endDate.min = this.value;
        });
    </script>
@endsection
