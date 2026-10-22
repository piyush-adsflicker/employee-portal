@extends('layouts.admin')
@section('title', 'Shifts')
@section('header_title', 'Shift Management')

@section('content')
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Working Shifts</h3>
                <p class="text-sm text-slate-500 mt-1">Configure daily working hours, half-days, and grace periods.</p>
            </div>
            <button onclick="openModal('add')"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-plus"></i> Add Shift
            </button>
        </div>

        @if ($errors->any())
            <div class="p-4 bg-red-50 text-red-600 border-b border-red-100 text-sm">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-200">
                        <th class="px-4 py-3 font-semibold">Shift Name</th>
                        <th class="px-4 py-3 font-semibold">Start Time</th>
                        <th class="px-4 py-3 font-semibold">End Time</th>
                        <th class="px-4 py-3 font-semibold text-center">Full / Half Day Hrs</th>
                        <th class="px-4 py-3 font-semibold text-center">Grace Period</th>
                        <th class="px-4 py-3 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($shifts as $shift)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-4 py-3 font-medium text-slate-800">{{ $shift->shift_name }}</td>
                            <td class="px-4 py-3 text-slate-600">
                                {{ \Carbon\Carbon::parse($shift->start_time)->format('h:i A') }}</td>
                            <td class="px-4 py-3 text-slate-600">
                                {{ \Carbon\Carbon::parse($shift->end_time)->format('h:i A') }}</td>
                            <td class="px-4 py-3 text-center text-slate-600">
                                <span
                                    class="bg-slate-100 text-slate-600 px-2.5 py-1 rounded-md font-medium">{{ $shift->working_hours }}h
                                    / {{ $shift->half_day_hours ?? 4 }}h</span>
                            </td>
                            <td class="px-4 py-3 text-center text-slate-600">
                                <span
                                    class="bg-indigo-50 text-indigo-600 px-2.5 py-1 rounded-md font-medium">{{ $shift->grace_period }}
                                    mins</span>
                            </td>
                            <td class="px-4 py-3 text-right space-x-2">
                                <button onclick="openModal('edit', {{ $shift }})"
                                    class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition-colors inline-flex items-center justify-center"
                                    title="Edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <form action="{{ route('admin.shifts.destroy', $shift->id) }}" method="POST"
                                    class="inline-block"
                                    onsubmit="return confirm('Are you sure you want to delete this shift?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 transition-colors inline-flex items-center justify-center"
                                        title="Delete">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fa-solid fa-clock text-4xl text-slate-300 mb-3"></i>
                                    <p>No shifts configured. Create one to calculate attendance.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add/Edit Shift Modal -->
    <div id="shiftModal"
        class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm overflow-y-auto w-full h-full flex items-center justify-center opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg transform scale-95 transition-transform duration-300"
            id="modalContent">
            <div class="flex items-center justify-between p-6 border-b border-slate-100">
                <h3 id="modalTitle" class="text-lg font-bold text-slate-800">Add Shift</h3>
                <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            <form id="shiftForm" method="POST" action="{{ route('admin.shifts.store') }}" class="p-6">
                @csrf
                <div id="methodContainer"></div>
                <div class="space-y-5">
                    <div>
                        <label class="block text-slate-700 text-sm font-semibold mb-2" for="shift_name">Shift Name</label>
                        <input type="text" name="shift_name" id="shift_name" required placeholder="e.g. Morning Shift"
                            class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-slate-700 text-sm font-semibold mb-2" for="start_time">Start
                                Time</label>
                            <input type="time" name="start_time" id="start_time" required
                                class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                        </div>
                        <div>
                            <label class="block text-slate-700 text-sm font-semibold mb-2" for="end_time">End Time</label>
                            <input type="time" name="end_time" id="end_time" required
                                class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-slate-700 text-sm font-semibold mb-2">Total Hours</label>
                            <div class="relative">
                                <input type="number" step="0.5" name="working_hours" id="working_hours" required
                                    placeholder="8"
                                    class="w-full pl-3 pr-8 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                                <span
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-slate-400 text-sm">h</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-slate-700 text-sm font-semibold mb-2">Half-Day Hours</label>
                            <div class="relative">
                                <input type="number" step="0.5" name="half_day_hours" id="half_day_hours" required
                                    placeholder="4"
                                    class="w-full pl-3 pr-8 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                                <span
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-slate-400 text-sm">h</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-slate-700 text-sm font-semibold mb-2">Grace Period</label>
                            <div class="relative">
                                <input type="number" name="grace_period" id="grace_period" required placeholder="15"
                                    class="w-full pl-3 pr-8 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                                <span
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-slate-400 text-sm">m</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" onclick="closeModal()"
                        class="px-4 py-2 text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg font-medium transition-colors">
                        Cancel
                    </button>
                    <button type="submit" id="submitBtn"
                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-colors shadow-sm">
                        Save Shift
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('shiftModal');
        const modalContent = document.getElementById('modalContent');
        const form = document.getElementById('shiftForm');
        const modalTitle = document.getElementById('modalTitle');
        const methodContainer = document.getElementById('methodContainer');
        const submitBtn = document.getElementById('submitBtn');

        function openModal(mode, data = null) {
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modalContent.classList.remove('scale-95');
            }, 10);

            if (mode === 'add') {
                modalTitle.textContent = 'Add Shift';
                submitBtn.textContent = 'Save Shift';
                form.action = "{{ route('admin.shifts.store') }}";
                methodContainer.innerHTML = '';
                document.getElementById('shift_name').value = '';
                document.getElementById('start_time').value = '';
                document.getElementById('end_time').value = '';
                document.getElementById('working_hours').value = '';
                document.getElementById('half_day_hours').value = '4.00';
                document.getElementById('grace_period').value = '';
            } else if (mode === 'edit') {
                modalTitle.textContent = 'Edit Shift';
                submitBtn.textContent = 'Update Shift';
                form.action = `/admin/shifts/${data.id}`;
                methodContainer.innerHTML = '<input type="hidden" name="_method" value="PUT">';
                document.getElementById('shift_name').value = data.shift_name;
                document.getElementById('start_time').value = data.start_time.substring(0, 5);
                document.getElementById('end_time').value = data.end_time.substring(0, 5);
                document.getElementById('working_hours').value = data.working_hours;
                document.getElementById('half_day_hours').value = data.half_day_hours || '4.00';
                document.getElementById('grace_period').value = data.grace_period;
            }
        }

        function closeModal() {
            modal.classList.add('opacity-0');
            modalContent.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        modal.addEventListener('click', function(e) {
            if (e.target === modal) closeModal();
        });
    </script>
@endsection