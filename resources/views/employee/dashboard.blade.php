@extends('layouts.employee')
@section('title', 'My Dashboard')
@section('header_title', 'Dashboard')

@section('content')
    <div class="max-w-7xl mx-auto space-y-5 pb-10">
        <!-- Greeting Section -->
        <div
            class="flex flex-col md:flex-row md:items-center justify-between gap-3 bg-white p-4 rounded-2xl shadow-sm border border-slate-100 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-teal-50 rounded-bl-full -z-10"></div>
            <div>
                <h2 class="text-2xl font-extrabold text-slate-800">
                    {{ $greeting }}, <span class="text-teal-600">{{ explode(' ', $employee->emp_name)[0] }}</span>
                </h2>
                <p class="text-slate-500 mt-1">Here is your attendance summary for {{ now()->format('l, F jS, Y') }}.</p>
            </div>
            <div class="flex items-center gap-3 bg-slate-50 px-4 py-2 rounded-xl border border-slate-100">
                <div class="w-10 h-10 bg-white shadow-sm rounded-lg flex items-center justify-center text-teal-600">
                    <i class="fa-solid fa-calendar-check text-lg"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">Days Present (Month)</p>
                    <p class="text-lg font-bold text-slate-800">{{ $monthlyPresent }} Days</p>
                </div>
            </div>
        </div>

        <!-- CURRENT WEEK WIDGET -->
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100">
            <h3 class="font-bold text-slate-800 mb-3 flex items-center gap-2">
                <i class="fa-solid fa-calendar-week text-teal-600"></i> Current Week Attendance
            </h3>

            <div class="grid grid-cols-2 md:grid-cols-7 gap-3 w-full">
                @foreach ($currentWeek as $day)
                    @php
                        $bgColor = 'bg-slate-50 border-slate-200 text-slate-400';
                        $iconColor = 'text-slate-400';
                        $icon = 'fa-minus';

                        if ($day['status'] == 'Present') {
                            $bgColor = 'bg-emerald-50 border-emerald-200 text-emerald-700';
                            $iconColor = 'text-emerald-500';
                            $icon = 'fa-check';
                        } elseif (in_array($day['status'], ['Late', 'Half Day'])) {
                            $bgColor = 'bg-amber-50 border-amber-200 text-amber-700';
                            $iconColor = 'text-amber-500';
                            $icon = 'fa-clock';
                        } elseif ($day['status'] == 'Absent') {
                            $bgColor = 'bg-rose-50 border-rose-200 text-rose-700';
                            $iconColor = 'text-rose-500';
                            $icon = 'fa-xmark';
                        } elseif ($day['status'] == 'Leave') {
                            $bgColor = 'bg-blue-50 border-blue-200 text-blue-700';
                            $iconColor = 'text-blue-500';
                            $icon = 'fa-plane';
                        } elseif ($day['status'] == 'Holiday') {
                            $bgColor = 'bg-violet-50 border-violet-200 text-violet-700';
                            $iconColor = 'text-violet-500';
                            $icon = 'fa-gift';
                        } elseif ($day['status'] == 'Weekend') {
                            $bgColor = 'bg-slate-100 border-slate-300 text-slate-600';
                            $iconColor = 'text-slate-500';
                            $icon = 'fa-couch';
                        }
                    @endphp

                    <div
                        class="flex flex-col items-center p-2 rounded-xl border {{ $bgColor }} {{ $day['isToday'] ? 'ring-2 ring-teal-500 ring-offset-2' : '' }} relative">
                        @if ($day['isToday'])
                            <span
                                class="absolute -top-2 bg-teal-500 text-white text-[9px] font-bold px-2 py-0.5 rounded-full shadow">TODAY</span>
                        @endif

                        <p class="text-xs font-bold uppercase">{{ $day['day'] }}</p>
                        <p class="text-[10px] opacity-70 font-medium mb-2">{{ $day['date'] }}</p>

                        <div class="w-8 h-8 rounded-full bg-white shadow-sm flex items-center justify-center mb-3">
                            <i class="fa-solid {{ $icon }} {{ $iconColor }}"></i>
                        </div>

                        <div class="w-full text-center space-y-1">
                            <div
                                class="bg-white/60 rounded px-1.5 py-1 text-[9px] font-semibold text-slate-600 flex justify-between">
                                <span>In:</span> <span>{{ $day['check_in'] }}</span>
                            </div>
                            <div
                                class="bg-white/60 rounded px-1.5 py-1 text-[9px] font-semibold text-slate-600 flex justify-between">
                                <span>Out:</span> <span>{{ $day['check_out'] }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Legend -->
            <div
                class="mt-2 pt-2 border-t border-slate-100 flex flex-wrap justify-center gap-3 text-[10px] font-bold uppercase text-slate-500">
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded bg-emerald-400"></span>
                    Present</span>
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded bg-amber-400"></span> Late/Half
                    Day</span>
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded bg-rose-400"></span> Absent</span>
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded bg-blue-400"></span> Leave</span>
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded bg-violet-400"></span>
                    Holiday</span>
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded bg-slate-300"></span>
                    Weekend</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <!-- Core Attendance Engine Card -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden h-fit">
                <div class="p-3 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-teal-100 text-teal-600 flex items-center justify-center"><i
                                class="fa-solid fa-fingerprint"></i></div>
                        <h3 class="font-bold text-slate-800">Daily Action</h3>
                    </div>
                    @if ($todayAttendance)
                        <span
                            class="px-3 py-1 text-xs font-bold rounded-full {{ in_array($todayAttendance->status, ['Late', 'Half Day']) ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}">
                            Status: {{ strtoupper($todayAttendance->status) }}
                        </span>
                    @endif
                </div>
                <div class="p-6 text-center flex flex-col items-center justify-center">

                    @if (!$todayAttendance)
                        <div
                            class="w-18 h-18 bg-slate-50 rounded-full flex items-center justify-center mb-3 border-4 border-slate-100">
                            <i class="fa-solid fa-clock text-3xl text-slate-400"></i>
                        </div>
                        <h4 class="text-xl font-bold text-slate-800 mb-2">You haven't checked in yet</h4>
                        <p class="text-slate-500 mb-6 max-w-xl">Please mark your attendance to start recording your working
                            hours for today.</p>

                        <form id="checkin-form" action="{{ route('employee.check-in') }}" method="POST">
                            @csrf
                            <input type="hidden" name="location" id="checkin_location" value="">
                            <input type="hidden" name="coordinates" id="checkin_coordinates" value="">
                            <button type="button"
                                onclick="submitWithLocation('checkin-form', 'checkin_location', 'checkin_coordinates')"
                                class="group relative px-8 py-4 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-2xl shadow-lg shadow-teal-500/30 transition-all flex items-center gap-3 hover:-translate-y-1">
                                <i class="fa-solid fa-location-dot relative z-10"></i>
                                <span class="text-lg relative z-10">Check In Now</span>
                            </button>
                        </form>
                    @elseif($todayAttendance && !$todayAttendance->check_out_time && !$todayAttendance->current_break_start)
                        <p class="text-sm font-bold text-teal-600 uppercase tracking-widest mb-2"><i
                                class="fa-solid fa-circle-dot animate-pulse mr-1"></i> Shift in progress</p>
                        <div class="font-mono text-5xl md:text-6xl font-extrabold text-slate-800 tracking-tight my-5"
                            id="live-timer">00:00:00</div>

                        <div class="flex flex-wrap justify-center gap-3 mt-6">
                            <form action="{{ route('employee.start-break') }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="px-6 py-4 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-2xl shadow-lg transition-all flex items-center gap-2 hover:-translate-y-1">
                                    <i class="fa-solid fa-mug-hot"></i> Start Break
                                </button>
                            </form>
                            <form id="checkout-form" action="{{ route('employee.check-out') }}" method="POST">
                                @csrf
                                <input type="hidden" name="location" id="checkout_location" value="">
                                <input type="hidden" name="coordinates" id="checkout_coordinates" value="">
                                <button type="button" onclick="confirmCheckOut()"
                                    class="px-6 py-4 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-2xl shadow-lg transition-all flex items-center gap-2 hover:-translate-y-1">
                                    <i class="fa-solid fa-right-from-bracket"></i> Check Out
                                </button>
                            </form>
                        </div>
                    @elseif($todayAttendance && !$todayAttendance->check_out_time && $todayAttendance->current_break_start)
                        <div
                            class="w-18 h-18 bg-amber-50 rounded-full flex items-center justify-center mb-4 border-4 border-amber-100 animate-pulse">
                            <i class="fa-solid fa-mug-hot text-3xl text-amber-500"></i>
                        </div>
                        <h4 class="text-2xl font-bold text-slate-800 mb-2">You are currently on break</h4>
                        <p class="text-slate-500 mb-6">Break started at
                            {{ $todayAttendance->current_break_start->format('h:i A') }}</p>

                        <form action="{{ route('employee.end-break') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="px-8 py-4 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-2xl shadow-lg transition-all flex items-center gap-3 hover:-translate-y-1">
                                <i class="fa-solid fa-play"></i> End Break & Resume Work
                            </button>
                        </form>
                    @else
                        <div
                            class="w-18 h-18 bg-emerald-50 rounded-full flex items-center justify-center mb-4 border-4 border-emerald-100">
                            <i class="fa-solid fa-clipboard-check text-4xl text-emerald-500"></i>
                        </div>
                        <h4 class="text-2xl font-bold text-slate-800 mb-2">Shift Completed!</h4>
                        <p class="text-slate-500 mb-6">Great job today. Your attendance has been successfully recorded.</p>

                        <div class="grid grid-cols-3 gap-3 w-full max-w-lg">
                            <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                                <p class="text-[10px] text-slate-500 font-bold uppercase mb-1">Work Time</p>
                                <p class="text-lg font-bold text-teal-600">{{ $todayAttendance->working_hours }}<span
                                        class="text-sm">h</span></p>
                            </div>
                            <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                                <p class="text-[10px] text-slate-500 font-bold uppercase mb-1">Break Time</p>
                                <p class="text-lg font-bold text-amber-500">
                                    {{ $todayAttendance->total_break_minutes }}<span class="text-sm">m</span></p>
                            </div>
                            <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                                <p class="text-[10px] text-slate-500 font-bold uppercase mb-1">Check Out</p>
                                <p class="text-lg font-bold text-slate-700">
                                    {{ $todayAttendance->check_out_time->format('h:i A') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Side Widgets -->
            <div class="space-y-5">
                <!-- Shift Information Panel -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center"><i
                                class="fa-solid fa-clipboard-list"></i></div>
                        <h3 class="font-bold text-slate-800">Shift Details</h3>
                    </div>
                    <div class="p-4">
                        @if ($employee->shift)
                            <div class="text-center mb-6">
                                <h4 class="text-lg font-bold text-slate-800">{{ $employee->shift->shift_name }}</h4>
                                <span
                                    class="inline-block mt-1 px-3 py-1 bg-teal-50 text-teal-700 text-xs font-bold rounded-full border border-teal-100">
                                    {{ $employee->shift->working_hours }} Hours / Day
                                </span>
                            </div>
                            <div class="space-y-4">
                                <div
                                    class="flex justify-between items-center p-3 bg-slate-50 rounded-lg border border-slate-100">
                                    <div class="flex items-center gap-2 text-slate-500 text-sm font-medium">
                                        <i class="fa-regular fa-sun"></i> Start Time
                                    </div>
                                    <span
                                        class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($employee->shift->start_time)->format('h:i A') }}</span>
                                </div>
                                <div
                                    class="flex justify-between items-center p-3 bg-slate-50 rounded-lg border border-slate-100">
                                    <div class="flex items-center gap-2 text-slate-500 text-sm font-medium">
                                        <i class="fa-regular fa-moon"></i> End Time
                                    </div>
                                    <span
                                        class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($employee->shift->end_time)->format('h:i A') }}</span>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-6 text-slate-500">
                                <i class="fa-solid fa-circle-info text-3xl mb-2 text-slate-300"></i>
                                <p>No shift assigned.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- TODAY'S LOGS TIMELINE -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center"><i
                                class="fa-solid fa-list-ul"></i></div>
                        <h3 class="font-bold text-slate-800">Today's Logs</h3>
                    </div>
                    <div class="p-4">
                        <div class="relative border-l-2 border-slate-100 ml-3 space-y-5">
                            @if ($todayAttendance && $todayAttendance->check_in_time)
                                <!-- Check In Log -->
                                <div class="relative pl-5">
                                    <span
                                        class="absolute -left-2.5 top-1 w-4 h-4 rounded-full bg-teal-500 border-4 border-white"></span>
                                    <h4 class="text-sm font-bold text-slate-800">Checked In</h4>
                                    <p class="text-xs text-slate-500 mt-0.5"><i class="fa-regular fa-clock mr-1"></i>
                                        {{ $todayAttendance->check_in_time->format('h:i A') }}</p>
                                </div>

                                <!-- Break Log -->
                                @if ($todayAttendance->current_break_start)
                                    <div class="relative pl-5">
                                        <span
                                            class="absolute -left-2.5 top-1 w-4 h-4 rounded-full bg-amber-500 border-4 border-white"></span>
                                        <h4 class="text-sm font-bold text-slate-800">Break Started</h4>
                                        <p class="text-xs text-slate-500 mt-0.5"><i class="fa-solid fa-mug-hot mr-1"></i>
                                            {{ $todayAttendance->current_break_start->format('h:i A') }}</p>
                                    </div>
                                @elseif($todayAttendance->total_break_minutes > 0)
                                    <div class="relative pl-5">
                                        <span
                                            class="absolute -left-2.5 top-1 w-4 h-4 rounded-full bg-amber-500 border-4 border-white"></span>
                                        <h4 class="text-sm font-bold text-slate-800">Breaks Taken</h4>
                                        <p class="text-xs text-slate-500 mt-0.5"><i
                                                class="fa-solid fa-stopwatch mr-1"></i>
                                            {{ $todayAttendance->total_break_minutes }} minutes total</p>
                                    </div>
                                @endif

                                <!-- Check Out Log -->
                                @if ($todayAttendance->check_out_time)
                                    <div class="relative pl-5">
                                        <span
                                            class="absolute -left-2.5 top-1 w-4 h-4 rounded-full bg-slate-800 border-4 border-white"></span>
                                        <h4 class="text-sm font-bold text-slate-800">Checked Out</h4>
                                        <p class="text-xs text-slate-500 mt-0.5"><i class="fa-regular fa-clock mr-1"></i>
                                            {{ $todayAttendance->check_out_time->format('h:i A') }}</p>
                                    </div>
                                @endif
                            @else
                                <p class="text-sm text-slate-400 italic ml-2">No activity logged yet today.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Live Timer Logic
        @if ($todayAttendance && !$todayAttendance->check_out_time && !$todayAttendance->current_break_start)
            const checkInTime = new Date("{{ $todayAttendance->check_in_time->toIso8601String() }}").getTime();

            function updateTimer() {
                const now = new Date().getTime();
                const distance = now - checkInTime;
                if (distance > 0) {
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    document.getElementById("live-timer").innerHTML =
                        String(hours).padStart(2, '0') + "<span class='text-slate-400 text-3xl md:text-4xl'>h</span> " +
                        String(minutes).padStart(2, '0') + "<span class='text-slate-400 text-3xl md:text-4xl'>m</span> " +
                        String(seconds).padStart(2, '0') + "<span class='text-slate-400 text-3xl md:text-4xl'>s</span>";
                }
            }
            updateTimer();
            setInterval(updateTimer, 1000);
        @endif

        // Silent Geolocation & Smart Error Handling
        function submitWithLocation(formId, locationInputId, coordsInputId) {
            if (navigator.geolocation) {
                Swal.fire({
                    title: 'Please wait...',
                    text: 'Processing your attendance...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                });

                navigator.geolocation.getCurrentPosition(
                    async function(position) {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;
                            const exactCoords = lat + ',' + lng;
                            let addressText = 'Coordinates Only';

                            try {
                                const response = await fetch(
                                    `https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=${lat}&longitude=${lng}&localityLanguage=en`
                                    );
                                const data = await response.json();
                                let locality = data.locality || '';
                                let city = data.city || '';
                                let addressParts = [];
                                if (locality) addressParts.push(locality);
                                if (city && city !== locality) addressParts.push(city);
                                if (addressParts.length > 0) {
                                    addressText = addressParts.join(', ');
                                }
                            } catch (error) {
                                console.error("Data fetch failed");
                            }

                            document.getElementById(locationInputId).value = addressText;
                            document.getElementById(coordsInputId).value = exactCoords;
                            Swal.close();
                            document.getElementById(formId).submit();
                        },
                        function(error) {
                            // SMART ERROR HANDLING
                            let errorMsg = 'Please allow location permissions to mark attendance.';
                            if (error.code === 1) errorMsg =
                                'You denied location permissions. Please click the site settings icon (lock icon) in your URL bar and allow Location.';
                            if (error.code === 2) errorMsg =
                                'Your device cannot determine its location (No GPS or Network).';
                            if (error.code === 3) errorMsg = 'Location request timed out.';

                            // Check if they are trying to access via HTTP network IP (e.g. 192.168.x.x)
                            if (window.location.protocol !== 'https:' && window.location.hostname !== 'localhost') {
                                errorMsg +=
                                    ' Note: Browsers strictly block GPS location if you access the portal over HTTP on a local network. Use HTTPS or localhost.';
                            }

                            Swal.fire('Action Denied', errorMsg, 'error');
                        }, {
                            enableHighAccuracy: true,
                            timeout: 15000,
                            maximumAge: 0
                        }
                );
            } else {
                Swal.fire('Error', 'Your browser does not support geolocation.', 'error');
            }
        }

        // Checkout Confirmation
        function confirmCheckOut() {
            Swal.fire({
                title: 'End your shift?',
                text: "Are you sure you want to check out for the day?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#1e293b',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Yes, Check Out',
                heightAuto: false,
                customClass: {
                    popup: 'rounded-2xl'
                }
            }).then((result) => {
                if (result.isConfirmed) submitWithLocation('checkout-form', 'checkout_location',
                    'checkout_coordinates');
            });
        }
    </script>
@endsection