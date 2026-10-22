<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Employee Workspace') | Attendance System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>

<body class="bg-slate-50 flex h-screen font-sans text-slate-800 overflow-hidden">

    <!-- Mobile Backdrop -->
    <div id="mobileBackdrop" onclick="toggleSidebar()"
        class="fixed inset-0 bg-slate-900/50 z-30 hidden lg:hidden backdrop-blur-sm transition-opacity duration-300 opacity-0">
    </div>

    <!-- Light Theme Sidebar -->
    <aside id="sidebar"
        class="fixed lg:relative inset-y-0 left-0 w-64 bg-white border-r border-slate-200 text-slate-600 flex flex-col transition-transform duration-300 z-40 shadow-2xl lg:shadow-none -translate-x-full lg:translate-x-0">

        <!-- Brand Header -->
        <div class="h-16 flex items-center justify-between px-5 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-teal-600 rounded flex items-center justify-center shadow-md shadow-teal-600/20">
                    <i class="fa-solid fa-fingerprint text-white text-lg"></i>
                </div>
                <h1 class="text-lg font-bold text-slate-800 tracking-tight">Staff<span
                        class="text-teal-600">Portal</span></h1>
            </div>
            <button onclick="toggleSidebar()" class="lg:hidden text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        <!-- Clean Menu -->
        <div class="flex-1 overflow-y-auto py-6 px-4 space-y-1">
            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 px-3">Main Menu</div>

            <a href="{{ route('employee.dashboard') }}"
                class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('employee.dashboard') ? 'bg-teal-50 text-teal-700 font-semibold' : 'hover:bg-slate-50 hover:text-slate-900 font-medium' }} rounded-lg group transition-colors">
                <i
                    class="fa-solid fa-table-cells-large text-lg w-5 text-center {{ request()->routeIs('employee.dashboard') ? 'text-teal-600' : 'text-slate-400 group-hover:text-teal-600' }} transition-colors"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('employee.attendance.calendar') }}"
                class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('employee.attendance.calendar') ? 'bg-teal-50 text-teal-700 font-semibold' : 'hover:bg-slate-50 hover:text-slate-900 font-medium' }} rounded-lg group transition-colors">
                <i
                    class="fa-regular fa-calendar-days text-lg w-5 text-center {{ request()->routeIs('employee.attendance.calendar') ? 'text-teal-600' : 'text-slate-400 group-hover:text-teal-600' }} transition-colors"></i>
                <span>Attendance Calendar</span>
            </a>

            <a href="{{ route('employee.attendance.history') }}"
                class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('employee.attendance.history') ? 'bg-teal-50 text-teal-700 font-semibold' : 'hover:bg-slate-50 hover:text-slate-900 font-medium' }} rounded-lg group transition-colors">
                <i
                    class="fa-solid fa-clock-rotate-left text-lg w-5 text-center {{ request()->routeIs('employee.attendance.history') ? 'text-teal-600' : 'text-slate-400 group-hover:text-teal-600' }} transition-colors"></i>
                <span>Attendance History</span>
            </a>

            <a href="{{ route('employee.leaves') }}"
                class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('employee.leaves') ? 'bg-teal-50 text-teal-700 font-semibold' : 'hover:bg-slate-50 hover:text-slate-900 font-medium' }} hover:bg-slate-50 hover:text-slate-900 font-medium rounded-lg group transition-colors">
                <i
                    class="fa-solid fa-plane-departure text-lg w-5 text-center text-slate-400 group-hover:text-teal-600 transition-colors"></i>
                <span>Leave Application</span>
            </a>

            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-6 mb-2 px-3">Account</div>

            <a href="{{ route('employee.profile') }}"
                class="flex items-center gap-3 px-3 py-2.5 hover:bg-slate-50 hover:text-slate-900 font-medium rounded-lg group transition-colors">
                <i
                    class="fa-solid fa-user text-lg w-5 text-center text-slate-400 group-hover:text-teal-600 transition-colors"></i>
                <span>My Profile</span>
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden w-full">
        <!-- Top Header -->
        <header
            class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 sm:px-8 z-10 shadow-sm shadow-slate-100/50">
            <div class="flex items-center gap-3 sm:gap-4">
                <button onclick="toggleSidebar()"
                    class="lg:hidden text-slate-500 hover:text-slate-700 focus:outline-none transition-colors">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
                <h2 class="text-lg sm:text-xl font-bold text-slate-800 truncate">@yield('header_title', 'Overview')</h2>
            </div>

            <div class="flex items-center gap-4 sm:gap-6">
                <!-- Date/Time Display -->
                <div class="hidden md:flex flex-col text-right pr-4 border-r border-slate-200">
                    <span class="text-sm font-bold text-slate-800">{{ now()->format('l, M d') }}</span>
                    <span class="text-xs text-teal-600 font-semibold"
                        id="live-time">{{ now()->format('h:i A') }}</span>
                </div>

                <!-- User Profile & Logout -->
                <div class="flex items-center gap-2 pl-2">

                    <!-- Profile Link -->
                    <a href="{{ route('employee.profile') }}"
                        class="flex items-center gap-2 hover:bg-slate-50 p-1 pr-3 rounded-full transition-colors border border-transparent hover:border-slate-200">
                        <div
                            class="w-8 h-8 rounded-full bg-teal-100 text-teal-600 flex items-center justify-center font-bold text-sm overflow-hidden border border-teal-200 shrink-0">
                            @if (Auth::user()->profile_photo)
                                <img src="{{ asset(Auth::user()->profile_photo) }}" alt="Profile"
                                    class="w-full h-full object-cover">
                            @else
                                {{ substr(Auth::user()->emp_name ?? 'E', 0, 1) }}
                            @endif
                        </div>
                        <span
                            class="text-sm font-bold text-slate-700 hidden md:block">{{ explode(' ', Auth::user()->emp_name)[0] }}</span>
                    </a>

                    <!-- Logout Form -->
                    <form id="logout-form" action="{{ route('employee.logout') }}" method="POST">
                        @csrf
                        <button type="button" onclick="confirmLogout()"
                            class="flex items-center justify-center w-9 h-9 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all"
                            title="Logout">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        </button>
                    </form>

                </div>
            </div>
        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50 p-3 sm:p-4 lg:p-6">
            @yield('content')
        </main>
    </div>

    <!-- Session Alerts with Teal accents -->
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: "{{ session('success') }}",
                confirmButtonColor: '#0d9488',
                timer: 3000,
                timerProgressBar: true,
                heightAuto: false,
                customClass: {
                    popup: 'rounded-2xl'
                }
            });
        </script>
    @endif
    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Action Failed',
                text: "{{ session('error') }}",
                confirmButtonColor: '#0d9488',
                heightAuto: false,
                customClass: {
                    popup: 'rounded-2xl'
                }
            });
        </script>
    @endif

    <script>
        // Sidebar Toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('mobileBackdrop');

            sidebar.classList.toggle('-translate-x-full');

            if (sidebar.classList.contains('-translate-x-full')) {
                backdrop.classList.add('opacity-0');
                setTimeout(() => backdrop.classList.add('hidden'), 300);
            } else {
                backdrop.classList.remove('hidden');
                setTimeout(() => backdrop.classList.remove('opacity-0'), 10);
            }
        }

        // Logout Confirmation
        function confirmLogout() {
            Swal.fire({
                title: 'Ready to Leave?',
                text: 'You will be logged out of your workspace.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Yes, log out',
                heightAuto: false,
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-lg px-4 py-2 text-sm font-semibold',
                    cancelButton: 'rounded-lg px-4 py-2 text-sm font-semibold'
                }
            }).then((result) => {
                if (result.isConfirmed) document.getElementById('logout-form').submit();
            });
        }

        // Live Clock
        setInterval(() => {
            const timeEl = document.getElementById('live-time');
            if (timeEl) {
                timeEl.innerText = new Date().toLocaleTimeString('en-US', {
                    hour: 'numeric',
                    minute: '2-digit',
                    hour12: true
                });
            }
        }, 60000);
    </script>
</body>

</html>
