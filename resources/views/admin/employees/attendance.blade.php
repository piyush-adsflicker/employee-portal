@extends('layouts.admin')

@section('title', 'Employee Attendance Calendar')
@section('header_title', 'Employee Attendance Record')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

    <div class="max-w-7xl mx-auto space-y-4">

        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Attendance: {{ $employee->emp_name }}</h2>
                <p class="text-slate-500 text-sm mt-1">Review and manage specific attendance logs for this employee.</p>
            </div>
            <a href="{{ route('admin.employees.index') }}"
                class="px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-lg hover:bg-slate-50 transition-colors font-medium flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-arrow-left"></i> Back to List
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4">
            <div class="flex flex-wrap gap-4 mb-6 pb-4 border-b border-slate-100 text-xs font-bold uppercase tracking-wider">
                <div class="flex items-center gap-2"><span class="w-3.5 h-3.5 rounded-md bg-emerald-600"></span> Present
                </div>
                <div class="flex items-center gap-2"><span class="w-3.5 h-3.5 rounded-md bg-amber-500"></span> Late</div>
                <div class="flex items-center gap-2"><span class="w-3.5 h-3.5 rounded-md bg-red-500"></span> Absent</div>
                <div class="flex items-center gap-2"><span class="w-3.5 h-3.5 rounded-md bg-blue-600"></span> Leave</div>
                <div class="flex items-center gap-2"><span class="w-3.5 h-3.5 rounded-md bg-violet-500"></span> Holiday
                </div>
                <div class="flex items-center gap-2"><span
                        class="w-3.5 h-3.5 rounded-md bg-slate-200 border border-slate-300"></span> Weekly Off</div>
            </div>

            <!-- Calendar Container -->
            <div id="attendanceCalendar" class="font-sans"></div>
        </div>
    </div>

    <!-- Edit Attendance Modal -->
    <div id="editAttendanceModal"
        class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm overflow-y-auto w-full h-full flex items-center justify-center opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md transform scale-95 transition-transform duration-300 m-4"
            id="editModalContent">

            <div class="flex items-center justify-between p-4 border-b border-slate-100 bg-slate-50/50 rounded-t-2xl">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Correct Attendance</h3>
                    <p class="text-xs font-semibold text-indigo-600 mt-1" id="modalDateDisplay"></p>
                </div>
                <button onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>

            <form id="editAttendanceForm" method="POST" class="p-4">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-slate-700 text-sm font-semibold mb-2" for="check_in">Check In Time</label>
                        <input type="time" name="check_in" id="check_in"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all text-sm">
                    </div>

                    <div>
                        <label class="block text-slate-700 text-sm font-semibold mb-2" for="check_out">Check Out
                            Time</label>
                        <input type="time" name="check_out" id="check_out"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all text-sm">
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3 border-t border-slate-100 pt-6">
                    <button type="button" onclick="closeEditModal()"
                        class="px-5 py-2.5 text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg font-medium transition-colors">
                        Cancel
                    </button>
                    <button type="button" onclick="confirmUpdate()"
                        class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-colors shadow-sm">
                        Update Time
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Attendance Modal -->
    <div id="createAttendanceModal"
        class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm overflow-y-auto w-full h-full flex items-center justify-center opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md transform scale-95 transition-transform duration-300 m-4"
            id="createModalContent">
            <div class="flex items-center justify-between p-4 border-b border-slate-100 bg-slate-50/50 rounded-t-2xl">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Add Attendance</h3>
                    <p class="text-xs font-semibold text-emerald-600 mt-1" id="createModalDateDisplay"></p>
                </div>
                <button onclick="closeCreateModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            <form action="{{ route('admin.employees.attendance.store', $employee->id) }}" method="POST" class="p-4">
                @csrf
                <input type="hidden" name="attendance_date" id="create_attendance_date">

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-slate-700 text-sm font-semibold mb-2" for="create_check_in">Check In
                            Time</label>
                        <input type="time" name="check_in" id="create_check_in"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all text-sm">
                    </div>
                    <div>
                        <label class="block text-slate-700 text-sm font-semibold mb-2" for="create_check_out">Check Out
                            Time</label>
                        <input type="time" name="check_out" id="create_check_out"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all text-sm">
                    </div>
                </div>
                <div class="mt-8 flex justify-end gap-3 border-t border-slate-100 pt-6">
                    <button type="button" onclick="closeCreateModal()"
                        class="px-5 py-2.5 text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg font-medium transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-medium transition-colors shadow-sm">
                        Add Attendance
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const createModal = document.getElementById('createAttendanceModal');
        const createContent = document.getElementById('createModalContent');

        function openCreateModal() {
            createModal.classList.remove('hidden');
            setTimeout(() => {
                createModal.classList.remove('opacity-0');
                createContent.classList.remove('scale-95');
            }, 10);
        }

        function closeCreateModal() {
            createModal.classList.add('opacity-0');
            createContent.classList.add('scale-95');
            setTimeout(() => {
                createModal.classList.add('hidden');
            }, 300);
        }

        createModal.addEventListener('click', (e) => {
            if (e.target === createModal) closeCreateModal();
        });
        
        const editModal = document.getElementById('editAttendanceModal');
        const editContent = document.getElementById('editModalContent');
        const editForm = document.getElementById('editAttendanceForm');

        document.addEventListener('DOMContentLoaded', function() {
            const rawEvents = @json($calendarEvents);
            const calendarEl = document.getElementById('attendanceCalendar');

            const calendarInstance = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                height: 'auto',
                contentHeight: 'auto',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth'
                },
                events: rawEvents,
                dateClick: function(info) {
                    document.getElementById('createModalDateDisplay').innerText = info.dateStr;
                    document.getElementById('create_attendance_date').value = info.dateStr;
                    openCreateModal();
                },
                eventClick: function(info) {
                    let props = info.event.extendedProps;

                    if (props.type === 'holiday') {
                        Swal.fire({
                            title: info.event.title,
                            text: props.description || 'Public Holiday',
                            icon: 'info',
                            confirmButtonColor: '#4f46e5',
                            customClass: {
                                popup: 'rounded-2xl'
                            }
                        });
                    } else {
                        // Attendance Data - Show Correction Modal
                        document.getElementById('modalDateDisplay').innerText = props.date_formatted;
                        document.getElementById('check_in').value = props.check_in || '';
                        document.getElementById('check_out').value = props.check_out || '';

                        // Set form action dynamically based on the record ID
                        editForm.action = `/admin/attendance/${props.record_id}/update-time`;

                        openEditModal();
                    }
                }
            });

            calendarInstance.render();
        });

        function openEditModal() {
            editModal.classList.remove('hidden');
            setTimeout(() => {
                editModal.classList.remove('opacity-0');
                editContent.classList.remove('scale-95');
            }, 10);
        }

        function closeEditModal() {
            editModal.classList.add('opacity-0');
            editContent.classList.add('scale-95');
            setTimeout(() => {
                editModal.classList.add('hidden');
            }, 300);
        }

        // SwatAlert Confirmation Before Updating
        function confirmUpdate() {
            Swal.fire({
                title: 'Confirm Correction',
                text: "Are you sure you want to update these times? The system will overwrite the original log and recalculate the status.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Yes, update it!',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-lg px-4 py-2 font-semibold',
                    cancelButton: 'rounded-lg px-4 py-2 font-semibold'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    editForm.submit();
                }
            });
        }

        // Close modal on outside click
        editModal.addEventListener('click', (e) => {
            if (e.target === editModal) closeEditModal();
        });
    </script>

    <style>
        .fc .fc-toolbar-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
        }

        .fc .fc-button-primary {
            background-color: #4f46e5;
            border-color: #4f46e5;
            font-weight: 600;
            text-transform: capitalize;
        }

        .fc .fc-button-primary:hover {
            background-color: #4338ca;
            border-color: #4338ca;
        }

        .fc .fc-button-primary:disabled {
            background-color: #94a3b8;
            border-color: #94a3b8;
        }

        .fc-theme-standard th {
            padding: 10px 0;
            background: #f8fafc;
            color: #64748b;
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 700;
            border-color: #f1f5f9;
        }

        .fc-event {
            cursor: pointer;
            padding: 3px 5px;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 4px;
            text-align: center;
        }
    </style>
@endsection
