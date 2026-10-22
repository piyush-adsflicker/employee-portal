@extends('layouts.admin')
@section('title', 'Leave Requests')
@section('header_title', 'Employee Leave Requests')

@section('content')
    <div class="space-y-4 max-w-7xl mx-auto">
        <div>
            <h3 class="text-xl font-bold text-slate-800">Pending Leave Approvals</h3>
            <p class="text-sm text-slate-500 mt-1">Review and approve or reject employee leave requests.</p>
        </div>

        <!-- Pending Requests Section (Scrollable & Compact) -->
        <div class="space-y-3 max-h-[500px] overflow-y-auto pr-2 custom-scrollbar">
            @if ($pendingRequests->isEmpty())
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 text-center">
                    <i class="fa-solid fa-mug-hot text-4xl text-slate-300 mb-3"></i>
                    <h3 class="text-lg font-bold text-slate-700">All Caught Up!</h3>
                    <p class="text-slate-500 mt-1 text-sm">There are no pending leave requests to review.</p>
                </div>
            @else
                @foreach ($pendingRequests as $req)
                    <div
                        class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 flex flex-col lg:flex-row lg:items-center justify-between gap-4 transition-all">

                        <!-- Employee Info -->
                        <div class="flex items-start gap-4 flex-1">
                            <div
                                class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-lg shrink-0 overflow-hidden">
                                @if ($req->employee->profile_photo)
                                    <img src="{{ asset($req->employee->profile_photo) }}"
                                        class="w-full h-full object-cover">
                                @else
                                    {{ substr($req->employee->emp_name, 0, 1) }}
                                @endif
                            </div>
                            <div class="flex-1">
                                <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-3 mb-1">
                                    <h4 class="font-bold text-slate-800">{{ $req->employee->emp_name }}</h4>
                                    <span
                                        class="text-xs font-bold bg-indigo-50 text-indigo-600 px-2 py-0.5 rounded border border-indigo-100">
                                        {{ $req->leaveType->name }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-2 text-xs mb-2">
                                    <span class="font-bold text-slate-600">{{ $req->start_date->format('d M, Y') }}</span>
                                    <span class="text-slate-400"><i class="fa-solid fa-arrow-right"></i></span>
                                    <span class="font-bold text-slate-600">{{ $req->end_date->format('d M, Y') }}</span>
                                    <span class="text-slate-400">&bull;</span>
                                    <span class="font-bold text-indigo-600">{{ $req->days }} Days</span>
                                </div>
                                <div class="bg-slate-50 border border-slate-100 p-2.5 rounded-lg text-xs text-slate-600">
                                    <span class="font-bold text-slate-700">Reason:</span> {{ $req->reason }}
                                </div>
                            </div>
                        </div>

                        <!-- Dynamic Action Area -->
                        <div class="min-w-[240px] bg-slate-50 p-3 rounded-lg border border-slate-100 shrink-0">

                            <!-- State 1: Initial Buttons -->
                            <div id="action-buttons-{{ $req->id }}" class="flex gap-2">
                                <!-- Approve Button -->
                                <form id="form-approve-{{ $req->id }}"
                                    action="{{ route('admin.leaves.requests.update', $req->id) }}" method="POST"
                                    class="flex-1">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="status" value="Approved">
                                    <button type="button" onclick="confirmApprove({{ $req->id }})"
                                        class="w-full px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg transition-colors flex items-center justify-center gap-1.5 shadow-sm text-xs">
                                        <i class="fa-solid fa-check"></i> Approve
                                    </button>
                                </form>

                                <!-- Show Reject Input Button -->
                                <button type="button" onclick="toggleRejectForm({{ $req->id }}, true)"
                                    class="flex-1 px-3 py-2 bg-white border border-slate-200 hover:bg-red-50 hover:text-red-600 hover:border-red-200 text-slate-600 font-bold rounded-lg transition-all flex items-center justify-center gap-1.5 shadow-sm text-xs">
                                    <i class="fa-solid fa-xmark"></i> Reject
                                </button>
                            </div>

                            <!-- State 2: Rejection Reason Input (Hidden by default) -->
                            <form id="form-reject-{{ $req->id }}"
                                action="{{ route('admin.leaves.requests.update', $req->id) }}" method="POST"
                                class="hidden flex-col gap-2">
                                @csrf @method('PUT')
                                <input type="hidden" name="status" value="Rejected">
                                <input type="text" name="admin_remark" placeholder="Provide rejection reason..." required
                                    class="w-full px-2.5 py-1.5 bg-white border border-red-200 rounded text-xs focus:ring-2 focus:ring-red-500 outline-none">

                                <div class="flex gap-2">
                                    <button type="submit"
                                        class="flex-1 px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded flex items-center justify-center gap-1 shadow-sm text-xs">
                                        Confirm
                                    </button>
                                    <button type="button" onclick="toggleRejectForm({{ $req->id }}, false)"
                                        class="flex-1 px-3 py-1.5 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded flex items-center justify-center text-xs">
                                        Cancel
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <!-- Recent History Section -->
        <div class="pt-3">
            <h4 class="font-bold text-slate-800 mb-3">Request History</h4>
            <div
                class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden max-h-[400px] overflow-y-auto">
                <table class="w-full text-left text-sm relative">
                    <thead
                        class="bg-slate-50 text-slate-500 text-xs uppercase border-b border-slate-200 tracking-wider sticky top-0 z-10 shadow-sm">
                        <tr>
                            <th class="px-5 py-3 font-semibold bg-slate-50">Employee</th>
                            <th class="px-5 py-3 font-semibold bg-slate-50">Leave Details</th>
                            <th class="px-5 py-3 font-semibold text-center bg-slate-50">Days</th>
                            <th class="px-5 py-3 font-semibold text-right bg-slate-50">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($historyRequests as $hist)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-5 py-3">
                                    <p class="font-bold text-slate-800">{{ $hist->employee->emp_name }}</p>
                                    <p class="text-[11px] text-slate-500">{{ $hist->employee->emp_id }}</p>
                                </td>
                                <td class="px-5 py-3">
                                    <p class="font-semibold text-indigo-600 mb-0.5">{{ $hist->leaveType->name }}</p>
                                    <p class="text-[11px] text-slate-500 font-medium">
                                        {{ $hist->start_date->format('M d') }} -
                                        {{ $hist->end_date->format('M d, Y') }}
                                    </p>
                                </td>
                                <td class="px-5 py-3 font-bold text-slate-700 text-center">{{ $hist->days }}</td>
                                <td class="px-5 py-3 text-right">
                                    @if ($hist->status == 'Approved')
                                        <span
                                            class="inline-flex items-center bg-emerald-50 text-emerald-700 border border-emerald-200 px-2.5 py-1 rounded-full text-[11px] font-bold">Approved</span>
                                    @else
                                        <span
                                            class="inline-flex items-center bg-red-50 text-red-700 border border-red-200 px-2.5 py-1 rounded-full text-[11px] font-bold">Rejected</span>
                                    @endif
                                    @if ($hist->admin_remark)
                                        <p class="text-[10px] text-slate-400 mt-1 max-w-[150px] truncate ml-auto"
                                            title="{{ $hist->admin_remark }}">{{ $hist->admin_remark }}</p>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-8 text-center text-slate-500">No request history found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Interactive Scripts -->
    <script>
        // Toggle Reject View
        function toggleRejectForm(id, show) {
            const buttonsDiv = document.getElementById('action-buttons-' + id);
            const rejectForm = document.getElementById('form-reject-' + id);

            if (show) {
                buttonsDiv.classList.add('hidden');
                rejectForm.classList.remove('hidden');
                rejectForm.classList.add('flex');
            } else {
                rejectForm.classList.add('hidden');
                rejectForm.classList.remove('flex');
                buttonsDiv.classList.remove('hidden');
            }
        }

        // SweetAlert Confirmation for Approval
        function confirmApprove(id) {
            Swal.fire({
                title: 'Approve Leave?',
                text: "This will deduct the leave balance of the employee",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#059669', // emerald-600
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Yes, Approve',
                heightAuto: false,
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-lg px-4 py-2 font-bold',
                    cancelButton: 'rounded-lg px-4 py-2 font-bold'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-approve-' + id).submit();
                }
            });
        }
    </script>
@endsection
