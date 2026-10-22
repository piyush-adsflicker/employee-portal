@extends('layouts.admin')
@section('title', 'Asset Management')
@section('header_title', 'Asset Management')

@section('content')
    <div class="space-y-5">
        <!-- Top Summary Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
            <div class="bg-white p-3 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase">Total Assets</p>
                    <h4 class="text-2xl font-bold text-slate-800 mt-1">{{ $stats['total'] }}</h4>
                </div>
                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center"><i
                        class="fa-solid fa-boxes-stacked text-lg"></i></div>
            </div>
            <div class="bg-white p-3 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase">Assigned</p>
                    <h4 class="text-2xl font-bold text-blue-600 mt-1">{{ $stats['assigned'] }}</h4>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center"><i
                        class="fa-solid fa-user-tag text-lg"></i></div>
            </div>
            <div class="bg-white p-3 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase">Available</p>
                    <h4 class="text-2xl font-bold text-emerald-600 mt-1">{{ $stats['available'] }}</h4>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center"><i
                        class="fa-solid fa-circle-check text-lg"></i></div>
            </div>
            <div class="bg-white p-3 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase">Repair / Retired</p>
                    <h4 class="text-2xl font-bold text-amber-600 mt-1">{{ $stats['repair'] }}</h4>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center"><i
                        class="fa-solid fa-screwdriver-wrench text-lg"></i></div>
            </div>
        </div>

        <!-- Filter & Action Bar -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div
                class="p-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/50">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Company Assets Inventory</h3>
                    <p class="text-sm text-slate-500">Track laptops, monitors, mobiles, SIMs, headsets, and ID cards.</p>
                </div>
                <button onclick="openAssetModal('add')"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-xl font-medium transition-colors flex items-center gap-2 shadow-sm w-fit">
                    <i class="fa-solid fa-plus"></i> Add New Asset
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-200">
                            <th class="px-4 py-2.5 font-semibold">Asset ID & Name</th>
                            <th class="px-4 py-2.5 font-semibold">Category / S.No</th>
                            <th class="px-4 py-2.5 font-semibold">Assigned Employee</th>
                            <th class="px-4 py-2.5 font-semibold">Issue / Return</th>
                            <th class="px-4 py-2.5 font-semibold text-center">Condition</th>
                            <th class="px-4 py-2.5 font-semibold text-center">Status</th>
                            <th class="px-4 py-2.5 font-semibold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($assets as $asset)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-4 py-2.5">
                                    <p class="font-bold text-slate-800">{{ $asset->asset_name }}</p>
                                    <span
                                        class="text-xs font-semibold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">{{ $asset->asset_code }}</span>
                                </td>
                                <td class="px-4 py-2.5">
                                    <p class="font-medium text-slate-700">{{ $asset->category }}</p>
                                    <p class="text-xs text-slate-400">SN: {{ $asset->serial_number ?: 'N/A' }}</p>
                                </td>
                                <td class="px-4 py-2.5">
                                    @if ($asset->employee)
                                        <a href="{{ route('admin.employees.show', $asset->employee_id) }}"
                                            class="font-bold text-slate-800 hover:text-indigo-600 block">
                                            {{ $asset->employee->emp_name }}
                                        </a>
                                        <span class="text-xs text-slate-500">{{ $asset->employee->emp_id }}</span>
                                    @else
                                        <span class="text-xs text-slate-400 italic">Unassigned</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2.5 text-xs text-slate-600">
                                    <div><strong>Issued:</strong>
                                        {{ $asset->issue_date ? $asset->issue_date->format('d M, Y') : '--' }}</div>
                                    <div><strong>Return:</strong>
                                        {{ $asset->return_date ? $asset->return_date->format('d M, Y') : '--' }}</div>
                                </td>
                                <td class="px-4 py-2.5 text-center">
                                    <span
                                        class="px-2.5 py-1 rounded-md text-xs font-semibold bg-slate-100 text-slate-700">{{ $asset->condition }}</span>
                                </td>
                                <td class="px-4 py-2.5 text-center">
                                    @php
                                        $statusColors = [
                                            'Available' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'Assigned' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'Under Repair' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'Retired' => 'bg-rose-50 text-rose-700 border-rose-200',
                                        ];
                                    @endphp
                                    <span
                                        class="px-2.5 py-1 rounded-full text-xs font-bold border {{ $statusColors[$asset->status] ?? 'bg-slate-100' }}">
                                        {{ $asset->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-2.5 text-right space-x-1 whitespace-nowrap">
                                    <button onclick='openAssetModal("edit", @json($asset))'
                                        class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 inline-flex items-center justify-center">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <form action="{{ route('admin.assets.destroy', $asset->id) }}" method="POST"
                                        class="inline-block" onsubmit="return confirm('Delete this asset?');">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 inline-flex items-center justify-center">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                    <i class="fa-solid fa-laptop text-4xl text-slate-300 mb-3 block"></i>
                                    No assets recorded yet. Click "Add New Asset" to start tracking inventory.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add/Edit Asset Modal -->
    <div id="assetModal"
        class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm overflow-y-auto w-full h-full flex items-center justify-center">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl m-4 overflow-hidden">
            <div class="flex items-center justify-between p-4 border-b border-slate-100 bg-slate-50/50">
                <h3 id="assetModalTitle" class="text-lg font-bold text-slate-800">Add Asset</h3>
                <button onclick="closeAssetModal()" class="text-slate-400 hover:text-slate-600"><i
                        class="fa-solid fa-xmark text-xl"></i></button>
            </div>
            <form id="assetForm" method="POST" action="{{ route('admin.assets.store') }}" class="p-6 space-y-4">
                @csrf
                <div id="assetMethodContainer"></div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Asset Code *</label>
                        <input type="text" name="asset_code" id="asset_code" required placeholder="AST-LAP-01"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Asset Name / Model *</label>
                        <input type="text" name="asset_name" id="asset_name" required
                            placeholder="e.g. Dell Latitude 5420"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Category *</label>
                        <select name="category" id="category" required
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm">
                            <option value="Laptop">Laptop</option>
                            <option value="Monitor">Monitor</option>
                            <option value="Mobile">Mobile</option>
                            <option value="SIM">SIM Card</option>
                            <option value="Headset">Headset</option>
                            <option value="ID Card">ID Card</option>
                            <option value="Accessories">Accessories</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Serial Number</label>
                        <input type="text" name="serial_number" id="serial_number" placeholder="Optional"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Condition *</label>
                        <select name="condition" id="condition" required
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm">
                            <option value="New">New</option>
                            <option value="Good" selected>Good</option>
                            <option value="Fair">Fair</option>
                            <option value="Damaged">Damaged</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2 border-t border-slate-100">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Assign to Employee</label>
                        <select name="employee_id" id="employee_id"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm">
                            <option value="">-- Unassigned --</option>
                            @foreach ($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->emp_name }} ({{ $emp->emp_id }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Issue Date</label>
                        <input type="date" name="issue_date" id="issue_date"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Return Date</label>
                        <input type="date" name="return_date" id="return_date"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Current Status *</label>
                        <select name="status" id="status" required
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm">
                            <option value="Available">Available</option>
                            <option value="Assigned">Assigned</option>
                            <option value="Under Repair">Under Repair</option>
                            <option value="Retired">Retired</option>
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Remarks / Configuration
                            Notes</label>
                        <input type="text" name="notes" id="notes"
                            placeholder="e.g. 16GB RAM, Charger included"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm">
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" onclick="closeAssetModal()"
                        class="px-4 py-2 bg-slate-100 text-slate-600 rounded-lg text-sm font-semibold">Cancel</button>
                    <button type="submit"
                        class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-semibold">Save
                        Asset</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const assetModal = document.getElementById('assetModal');
        const assetForm = document.getElementById('assetForm');
        const assetMethod = document.getElementById('assetMethodContainer');
        const assetTitle = document.getElementById('assetModalTitle');

        function openAssetModal(mode, data = null) {
            assetModal.classList.remove('hidden');
            if (mode === 'add') {
                assetTitle.textContent = 'Add New Asset';
                assetForm.action = "{{ route('admin.assets.store') }}";
                assetMethod.innerHTML = '';
                assetForm.reset();
            } else {
                assetTitle.textContent = 'Edit Asset: ' + data.asset_code;
                assetForm.action = `/admin/assets/${data.id}`;
                assetMethod.innerHTML = '<input type="hidden" name="_method" value="PUT">';
                document.getElementById('asset_code').value = data.asset_code;
                document.getElementById('asset_name').value = data.asset_name;
                document.getElementById('category').value = data.category;
                document.getElementById('serial_number').value = data.serial_number || '';
                document.getElementById('condition').value = data.condition;
                document.getElementById('employee_id').value = data.employee_id || '';
                document.getElementById('issue_date').value = data.issue_date ? data.issue_date.substring(0, 10) : '';
                document.getElementById('return_date').value = data.return_date ? data.return_date.substring(0, 10) : '';
                document.getElementById('status').value = data.status;
                document.getElementById('notes').value = data.notes || '';
            }
        }

        function closeAssetModal() {
            assetModal.classList.add('hidden');
        }
    </script>
@endsection
