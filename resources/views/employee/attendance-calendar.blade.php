@extends('layouts.employee')

@section('title', 'Attendance Calendar')
@section('header_title', 'Attendance Calendar')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

    <div class="max-w-7xl mx-auto space-y-6">

        <div class="mb-6">
            <h2 class="text-2xl font-bold text-slate-800">Monthly View</h2>
            <p class="text-sm text-slate-500 mt-1">A calendar overview of your attendance statuses and organization holidays.
            </p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">

            <!-- Interactive Legend -->
            <div class="flex flex-wrap gap-4 mb-6 pb-4 border-b border-slate-100 text-xs font-bold uppercase tracking-wider">
                <div class="flex items-center gap-2"><span class="w-3.5 h-3.5 rounded-md bg-teal-600"></span> Present</div>
                <div class="flex items-center gap-2"><span class="w-3.5 h-3.5 rounded-md bg-amber-500"></span> Late</div>
                <div class="flex items-center gap-2"><span class="w-3.5 h-3.5 rounded-md bg-red-500"></span> Absent</div>
                <div class="flex items-center gap-2"><span class="w-3.5 h-3.5 rounded-md bg-blue-500"></span> Leave</div>
                <div class="flex items-center gap-2"><span class="w-3.5 h-3.5 rounded-md bg-violet-500"></span> Holiday</div>
                <div class="flex items-center gap-2"><span class="w-3.5 h-3.5 rounded-md bg-slate-200 border border-slate-300"></span> Weekly Off</div>
            </div>

            <!-- FullCalendar Wrapper -->
            <div id="attendanceCalendar" class="font-sans"></div>

        </div>

    </div>

    <script>
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
                eventClick: function(info) {
                    let props = info.event.extendedProps;
                    let htmlContent = '';

                    if (props.type === 'holiday') {
                        htmlContent = `
                        <div class="text-left space-y-3 mt-4 text-slate-600 text-sm">
                            <div class="flex justify-between border-b border-slate-100 pb-2">
                                <span class="font-bold text-slate-800">Date</span> 
                                <span>${info.event.startStr}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-bold text-slate-800">Note</span> 
                                <span class="text-right max-w-[60%]">${props.description || 'Public Holiday'}</span>
                            </div>
                        </div>`;
                    } else {
                        htmlContent = `
                        <div class="text-left space-y-3 mt-4 text-slate-600 text-sm">
                            <div class="flex justify-between border-b border-slate-100 pb-2">
                                <span class="font-bold text-slate-800">Date</span> 
                                <span>${info.event.startStr}</span>
                            </div>
                            <div class="flex justify-between border-b border-slate-100 pb-2">
                                <span class="font-bold text-slate-800">Status</span> 
                                <span class="font-bold uppercase ${props.status === 'Late' ? 'text-amber-600' : 'text-teal-600'}">${props.status}</span>
                            </div>
                            <div class="flex justify-between border-b border-slate-100 pb-2">
                                <span class="font-bold text-slate-800">Check In</span> 
                                <span>${props.check_in}</span>
                            </div>
                            <div class="flex justify-between border-b border-slate-100 pb-2">
                                <span class="font-bold text-slate-800">Check Out</span> 
                                <span>${props.check_out}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-slate-800">Working Hours</span> 
                                <span class="bg-slate-100 text-slate-700 px-2 py-1 rounded font-bold">${props.working_hours} hrs</span>
                            </div>
                        </div>`;
                    }

                    Swal.fire({
                        title: info.event.title,
                        html: htmlContent,
                        icon: props.type === 'holiday' ? 'info' : 'success',
                        confirmButtonColor: '#0d9488', // Teal-600
                        heightAuto: false,
                        customClass: {
                            popup: 'rounded-2xl'
                        }
                    });
                }
            });

            calendarInstance.render();
        });
    </script>

    <style>
        /* Full Calendar Clean Overrides */
        .fc .fc-toolbar-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
        }

        .fc .fc-button-primary {
            background-color: #0d9488;
            border-color: #0d9488;
            text-transform: capitalize;
            font-weight: 600;
        }

        .fc .fc-button-primary:hover {
            background-color: #0f766e;
            border-color: #0f766e;
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
        }

        .fc-theme-standard td,
        .fc-theme-standard th {
            border-color: #f1f5f9;
        }

        .fc-event {
            cursor: pointer;
            padding: 2px 4px;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 4px;
        }
    </style>
@endsection
