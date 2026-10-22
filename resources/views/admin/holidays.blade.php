@extends('layouts.admin')

@section('title', 'Holidays')
@section('header_title', 'Holiday Management')

@section('content')
<!-- Include FullCalendar CDN -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

<div class="space-y-6">
    <!-- View Mode Selector & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-slate-800">Organization Holiday Calendar</h3>
            <p class="text-sm text-slate-500 mt-1">Configure non-working holidays utilized by the attendance engine.</p>
        </div>

        <div class="flex items-center gap-3">
            <!-- Switch Tabs -->
            <div class="bg-slate-200/80 p-1 rounded-xl flex items-center">
                <button type="button" id="tabListBtn" onclick="switchView('list')" class="px-3.5 py-1.5 rounded-lg text-sm font-semibold bg-white text-slate-800 shadow-sm transition-all flex items-center gap-2">
                    <i class="fa-solid fa-list text-xs"></i> List View
                </button>
                <button type="button" id="tabCalendarBtn" onclick="switchView('calendar')" class="px-3.5 py-1.5 rounded-lg text-sm font-semibold text-slate-600 hover:text-slate-900 transition-all flex items-center gap-2">
                    <i class="fa-regular fa-calendar text-xs"></i> Calendar View
                </button>
            </div>

            <button onclick="openHolidayModal('add')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl font-medium transition-colors flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-plus text-sm"></i> Add Holiday
            </button>
        </div>
    </div>

    @if($errors->any())
    <div class="p-4 bg-red-50 text-red-600 rounded-xl border border-red-100 text-sm">
        <ul class="list-disc pl-5 space-y-1">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- 1. List View Section -->
    <div id="listViewSection" class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-200">
                        <th class="px-2 sm:px-3 lg:px-5 py-2 sm:py-3 font-semibold">Holiday Date</th>
                        <th class="px-2 sm:px-3 lg:px-5 py-2 sm:py-3 font-semibold">Holiday Name</th>
                        <th class="px-2 sm:px-3 lg:px-5 py-2 sm:py-3 font-semibold">Description</th>
                        <th class="px-2 sm:px-3 lg:px-5 py-2 sm:py-3 font-semibold text-center">Status</th>
                        <th class="px-2 sm:px-3 lg:px-5 py-2 sm:py-3 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($holidays as $holiday)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-2 sm:px-3 lg:px-5 py-2 sm:py-3 whitespace-nowrap">
                            <div class="font-medium text-slate-800">{{ $holiday->date->format('M d, Y') }}</div>
                            <div class="text-xs text-slate-400">{{ $holiday->date->format('l') }}</div>
                        </td>
                        <td class="px-2 sm:px-3 lg:px-5 py-2 sm:py-3 font-semibold text-slate-800">{{ $holiday->holiday_name }}</td>
                        <td class="px-2 sm:px-3 lg:px-5 py-2 sm:py-3 text-slate-500 max-w-xs truncate">{{ $holiday->description ?: '-' }}</td>
                        <td class="px-2 sm:px-3 lg:px-5 py-2 sm:py-3 text-center">
                            <button onclick="toggleHolidayStatus({{ $holiday->id }}, this)"
                                class="relative inline-flex h-5 w-10 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2 {{ $holiday->is_active ? 'bg-emerald-500' : 'bg-slate-300' }}"
                                role="switch"
                                aria-checked="{{ $holiday->is_active ? 'true' : 'false' }}">
                                <span class="sr-only">Toggle status</span>
                                <span class="pointer-events-none relative inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $holiday->is_active ? 'translate-x-5' : 'translate-x-0' }}">
                                </span>
                            </button>
                            <div class="text-[9px] uppercase font-bold tracking-wider status-text {{ $holiday->is_active ? 'text-emerald-600' : 'text-slate-400' }}">
                                {{ $holiday->is_active ? 'Active' : 'Inactive' }}
                            </div>
                        </td>
                        <td class="px-2 sm:px-3 lg:px-5 py-2 sm:py-3 text-right space-x-2 whitespace-nowrap">
                            <button onclick='openHolidayModal("edit", @json($holiday))' class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition-colors inline-flex items-center justify-center" title="Edit">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>

                            <form action="{{ route('admin.holidays.destroy', $holiday->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this holiday?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 transition-colors inline-flex items-center justify-center" title="Delete">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fa-solid fa-calendar-xmark text-4xl text-slate-300 mb-3"></i>
                                <p class="text-base font-medium">No holidays scheduled</p>
                                <p class="text-xs text-slate-400 mt-1">Add holidays so the attendance engine recognizes non-working days.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 2. Calendar View Section -->
    <div id="calendarViewSection" class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 hidden">
        <div id="holidayCalendar"></div>
    </div>
</div>

<!-- Add/Edit Holiday Modal -->
<div id="holidayModal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm overflow-y-auto w-full h-full flex items-center justify-center opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md transform scale-95 transition-transform duration-300" id="holidayModalContent">

        <div class="flex items-center justify-between p-6 border-b border-slate-100">
            <h3 id="holidayModalTitle" class="text-lg font-bold text-slate-800">Add Holiday</h3>
            <button onclick="closeHolidayModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        <form id="holidayForm" method="POST" action="{{ route('admin.holidays.store') }}" class="p-6">
            @csrf
            <div id="holidayMethodContainer"></div>

            <div class="space-y-4">
                <div>
                    <label class="block text-slate-700 text-sm font-semibold mb-2" for="holiday_name">Holiday Name</label>
                    <input type="text" name="holiday_name" id="holiday_name" required placeholder="e.g. Independence Day"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                </div>

                <div>
                    <label class="block text-slate-700 text-sm font-semibold mb-2" for="holiday_date">Holiday Date</label>
                    <input type="date" name="date" id="holiday_date" required
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                </div>

                <div>
                    <label class="block text-slate-700 text-sm font-semibold mb-2" for="holiday_description">Description (Optional)</label>
                    <textarea name="description" id="holiday_description" rows="3" placeholder="Brief note or holiday announcement..."
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all resize-none"></textarea>
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <button type="button" onclick="closeHolidayModal()" class="px-4 py-2 text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg font-medium transition-colors">
                    Cancel
                </button>
                <button type="submit" id="holidaySubmitBtn" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-colors shadow-sm">
                    Save Holiday
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Tab and View Switcher
    let calendarInstance = null;
    const rawEvents = @json($calendarEvents);

    function switchView(view) {
        const listSec = document.getElementById('listViewSection');
        const calSec = document.getElementById('calendarViewSection');
        const listBtn = document.getElementById('tabListBtn');
        const calBtn = document.getElementById('tabCalendarBtn');

        if (view === 'list') {
            listSec.classList.remove('hidden');
            calSec.classList.add('hidden');
            listBtn.className = 'px-3.5 py-1.5 rounded-lg text-sm font-semibold bg-white text-slate-800 shadow-sm transition-all flex items-center gap-2';
            calBtn.className = 'px-3.5 py-1.5 rounded-lg text-sm font-semibold text-slate-600 hover:text-slate-900 transition-all flex items-center gap-2';
        } else {
            listSec.classList.add('hidden');
            calSec.classList.remove('hidden');
            calBtn.className = 'px-3.5 py-1.5 rounded-lg text-sm font-semibold bg-white text-slate-800 shadow-sm transition-all flex items-center gap-2';
            listBtn.className = 'px-3.5 py-1.5 rounded-lg text-sm font-semibold text-slate-600 hover:text-slate-900 transition-all flex items-center gap-2';

            if (!calendarInstance) {
                initCalendar();
            } else {
                calendarInstance.render();
            }
        }
    }

    function initCalendar() {
        const calendarEl = document.getElementById('holidayCalendar');
        calendarInstance = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            height: 'auto',
            contentHeight: 'auto',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,multiMonthYear'
            },
            events: rawEvents.map(evt => ({
                id: evt.id,
                title: evt.title,
                start: evt.start,
                backgroundColor: evt.is_active ? '#4f46e5' : '#94a3b8',
                borderColor: evt.is_active ? '#4338ca' : '#64748b',
                extendedProps: {
                    description: evt.description,
                    is_active: evt.is_active
                }
            })),
            eventClick: function(info) {
                Swal.fire({
                    title: info.event.title,
                    html: `
                        <div class="text-left text-sm text-slate-600 space-y-2 mt-2">
                            <p><strong>Date:</strong> ${info.event.startStr}</p>
                            <p><strong>Status:</strong> ${info.event.extendedProps.is_active ? '<span class="text-emerald-600 font-semibold">Active</span>' : '<span class="text-slate-400 font-semibold">Inactive</span>'}</p>
                            <p><strong>Description:</strong> ${info.event.extendedProps.description || 'No description provided.'}</p>
                        </div>
                    `,
                    icon: 'info',
                    confirmButtonColor: '#4f46e5',
                    heightAuto: false,
                    customClass: {
                        popup: 'rounded-2xl'
                    }
                });
            }
        });
        calendarInstance.render();
    }

    // Modal Handling
    const hModal = document.getElementById('holidayModal');
    const hModalContent = document.getElementById('holidayModalContent');
    const hForm = document.getElementById('holidayForm');
    const hTitle = document.getElementById('holidayModalTitle');
    const hMethod = document.getElementById('holidayMethodContainer');
    const hSubmitBtn = document.getElementById('holidaySubmitBtn');

    function openHolidayModal(mode, data = null) {
        hModal.classList.remove('hidden');
        setTimeout(() => {
            hModal.classList.remove('opacity-0');
            hModalContent.classList.remove('scale-95');
        }, 10);

        if (mode === 'add') {
            hTitle.textContent = 'Add Holiday';
            hSubmitBtn.textContent = 'Save Holiday';
            hForm.action = "{{ route('admin.holidays.store') }}";
            hMethod.innerHTML = '';

            document.getElementById('holiday_name').value = '';
            document.getElementById('holiday_date').value = '';
            document.getElementById('holiday_description').value = '';
        } else if (mode === 'edit') {
            hTitle.textContent = 'Edit Holiday';
            hSubmitBtn.textContent = 'Update Holiday';
            hForm.action = `/admin/holidays/${data.id}`;
            hMethod.innerHTML = '<input type="hidden" name="_method" value="PUT">';

            document.getElementById('holiday_name').value = data.holiday_name;
            document.getElementById('holiday_date').value = data.date.substring(0, 10);
            document.getElementById('holiday_description').value = data.description || '';
        }
    }

    function closeHolidayModal() {
        hModal.classList.add('opacity-0');
        hModalContent.classList.add('scale-95');
        setTimeout(() => {
            hModal.classList.add('hidden');
        }, 300);
    }

    hModal.addEventListener('click', (e) => {
        if (e.target === hModal) closeHolidayModal();
    });

    // Async Status Toggle
    function toggleHolidayStatus(id, btnElement) {
        Swal.fire({
            title: 'Change Status?',
            text: "Do you want to toggle the active status for this holiday?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4f46e5',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Yes, change it!',
            heightAuto: false,
            customClass: {
                popup: 'rounded-2xl'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/admin/holidays/${id}/toggle-status`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            const circle = btnElement.querySelector('span:nth-child(2)');
                            const textLabel = btnElement.nextElementSibling;

                            if (data.is_active) {
                                btnElement.classList.replace('bg-slate-300', 'bg-emerald-500');
                                circle.classList.replace('translate-x-0', 'translate-x-5');
                                textLabel.textContent = 'ACTIVE';
                                textLabel.classList.replace('text-slate-400', 'text-emerald-600');
                            } else {
                                btnElement.classList.replace('bg-emerald-500', 'bg-slate-300');
                                circle.classList.replace('translate-x-5', 'translate-x-0');
                                textLabel.textContent = 'INACTIVE';
                                textLabel.classList.replace('text-emerald-600', 'text-slate-400');
                            }

                            Swal.fire({
                                title: 'Updated!',
                                text: data.message,
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false,
                                heightAuto: false,
                                customClass: {
                                    popup: 'rounded-2xl'
                                }
                            });
                        }
                    })
                    .catch(() => {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Unable to update status.',
                            icon: 'error',
                            heightAuto: false
                        });
                    });
            }
        });
    }
</script>
@endsection