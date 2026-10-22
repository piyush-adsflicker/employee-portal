@extends('layouts.admin')
@section('title', 'Manage Leaves')
@section('header_title', 'Leave Configuration & Balances')

@section('content')
    <div class="space-y-4 max-w-7xl mx-auto">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-xl font-bold text-slate-800">Leave Administration</h3>
                <p class="text-sm text-slate-500 mt-1">Configure leave categories and assign balances to employees.</p>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-2 flex gap-2 w-fit">
            <button onclick="switchTab('balances')" id="tab-balances"
                class="tab-btn px-5 py-2.5 rounded-lg text-sm font-bold bg-indigo-50 text-indigo-700 transition-colors">
                <i class="fa-solid fa-user-plus mr-2"></i> Assign Balances
            </button>
            <button onclick="switchTab('types')" id="tab-types"
                class="tab-btn px-5 py-2.5 rounded-lg text-sm font-bold text-slate-600 hover:bg-slate-50 transition-colors">
                <i class="fa-solid fa-list-check mr-2"></i> Leave Types
            </button>
        </div>

        <!-- TAB 1: ASSIGN BALANCES -->
        <div id="content-balances" class="tab-content block space-y-4">
            <div
                class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col lg:flex-row relative">

                <!-- Allocate Balance Form (Fixed on Left) -->
                <div class="w-full lg:w-1/3 border-r border-slate-100 p-4 bg-slate-50/50 relative z-20">
                    <h4 class="font-bold text-slate-800 mb-4">Allocate Leave Balance</h4>
                    <form action="{{ route('admin.leaves.balances.update') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Employee</label>
                            <select name="employee_id" required
                                class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                                <option value="">Select Employee</option>
                                @foreach ($employees as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->emp_name }} ({{ $emp->emp_id }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Leave Type</label>
                            <select name="leave_type_id" id="leaveTypeSelect" onchange="autoFillDefaultDays()" required
                                class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                                <option value="" data-default="">Select Leave Type</option>
                                @foreach ($leaveTypes as $type)
                                    <option value="{{ $type->id }}" data-default="{{ $type->default_days }}">
                                        {{ $type->name }} (Default: {{ $type->default_days }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <div class="mb-1">
                                <label class="block text-xs font-bold text-slate-500 uppercase">Total Days Assigned</label>
                            </div>
                            <input type="number" step="0.5" name="balance" id="balanceInput" required
                                placeholder="e.g. 12"
                                class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <button type="submit"
                            class="w-full px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow-sm mt-4">
                            Save Balance
                        </button>
                    </form>
                </div>

                <!-- Balances List (Scrollable on Right) -->
                <div class="w-full lg:w-2/3 max-h-[500px] overflow-y-auto">
                    <table class="w-full text-left text-sm relative">
                        <thead
                            class="bg-slate-50 text-slate-500 text-xs uppercase border-b border-slate-200 sticky top-0 z-10 shadow-sm">
                            <tr>
                                <th class="px-5 py-3">Employee Details</th>
                                <th class="px-5 py-3">Leave Type</th>
                                <th class="px-5 py-3 text-center">Remaining Balance</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($leaveBalances as $bal)
                                <tr class="hover:bg-slate-50/50">
                                    <td class="px-5 py-3">
                                        <p class="font-bold text-slate-800">{{ $bal->employee->emp_name }}</p>
                                        <p class="text-xs text-slate-500">{{ $bal->employee->emp_id }}</p>
                                    </td>
                                    <td class="px-5 py-3 font-medium text-slate-600">{{ $bal->leaveType->name }}</td>
                                    <td class="px-5 py-3 text-center">
                                        <span
                                            class="bg-indigo-50 text-indigo-700 px-3 py-1 rounded-lg font-bold">{{ $bal->balance }}
                                            Days</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center text-slate-500">No leave balances
                                        assigned yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB 2: LEAVE TYPES -->
        <div id="content-types" class="tab-content hidden space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <!-- Add New Type (Fixed on Left) -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 h-fit">
                    <h4 class="font-bold text-slate-800 mb-4">Create Leave Type</h4>
                    <form action="{{ route('admin.leaves.types.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Leave Name</label>
                            <input type="text" name="name" required placeholder="e.g. Sick Leave"
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Default Allowance
                                (Days)</label>
                            <input type="number" name="default_days" required placeholder="e.g. 12"
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                            <p class="text-[10px] text-slate-400 mt-1">Used to auto-fill the balance assignment form.</p>
                        </div>
                        <button type="submit"
                            class="w-full px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow-sm">
                            Add Leave Type
                        </button>
                    </form>
                </div>

                <!-- List Types (Scrollable on Right) -->
                <div
                    class="md:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden max-h-[500px] overflow-y-auto">
                    <table class="w-full text-left text-sm relative">
                        <thead
                            class="bg-slate-50 text-slate-500 text-xs uppercase border-b border-slate-200 sticky top-0 z-10 shadow-sm">
                            <tr>
                                <th class="px-5 py-3">Leave Category</th>
                                <th class="px-5 py-3 text-center">Default Days</th>
                                <th class="px-5 py-3 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($leaveTypes as $type)
                                <tr class="hover:bg-slate-50/50">
                                    <td class="px-5 py-4 font-bold text-slate-800">{{ $type->name }}</td>
                                    <td class="px-5 py-4 text-center font-bold text-slate-600">{{ $type->default_days }}
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        <span
                                            class="bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-full text-xs font-bold">Active</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center text-slate-500">No leave types created
                                        yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function switchTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.tab-btn').forEach(el => {
                el.className =
                    'tab-btn px-5 py-2.5 rounded-lg text-sm font-bold text-slate-600 hover:bg-slate-50 transition-colors';
            });
            document.getElementById('content-' + tabId).classList.remove('hidden');
            document.getElementById('tab-' + tabId).className =
                'tab-btn px-5 py-2.5 rounded-lg text-sm font-bold bg-indigo-50 text-indigo-700 transition-colors';
        }

        function autoFillDefaultDays() {
            const select = document.getElementById('leaveTypeSelect');
            const defaultDays = select.options[select.selectedIndex].getAttribute('data-default');
            if (defaultDays) {
                document.getElementById('balanceInput').value = defaultDays;
            } else {
                document.getElementById('balanceInput').value = '';
            }
        }
    </script>
@endsection
