<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') | Attendance System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- SweetAlert2 CDN -->
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

    <!-- Sidebar -->
    <aside id="sidebar"
        class="fixed lg:relative inset-y-0 left-0 w-64 bg-slate-900 text-slate-300 flex flex-col transition-transform duration-300 z-40 shadow-xl -translate-x-full lg:translate-x-0">

        <div class="h-16 flex items-center justify-between px-6 border-b border-slate-800 bg-slate-950/50">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-indigo-500 rounded flex items-center justify-center mr-3">
                    <i class="fa-solid fa-user-clock text-white text-lg"></i>
                </div>
                <h1 class="text-lg font-bold text-white tracking-wide">EMS Admin</h1>
            </div>
            <!-- Close button for mobile -->
            <button onclick="toggleSidebar()" class="lg:hidden text-slate-400 hover:text-white transition-colors">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto py-6 px-4 space-y-1">
            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 px-2">Core</div>
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600/10 text-indigo-400' : 'hover:bg-slate-800 text-white' }} rounded-lg group transition-colors">
                <i class="fa-solid fa-table-cells-large text-lg w-5 text-center"></i>
                <span class="font-medium">Dashboard</span>
            </a>

            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mt-6 mb-2 px-2">Organization</div>
            <a href="{{ route('admin.departments.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('admin.departments.index') ? 'bg-indigo-600/10 text-indigo-400' : 'hover:bg-slate-800 hover:text-white' }} rounded-lg group transition-colors">
                <i
                    class="fa-solid fa-building text-lg w-5 text-center group-hover:text-indigo-400 transition-colors"></i>
                <span class="font-medium">Departments</span>
            </a>
            <a href="{{ route('admin.designations.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('admin.designations.index') ? 'bg-indigo-600/10 text-indigo-400' : 'hover:bg-slate-800 hover:text-white' }} rounded-lg group transition-colors">
                <i
                    class="fa-solid fa-id-badge text-lg w-5 text-center group-hover:text-indigo-400 transition-colors"></i>
                <span class="font-medium">Designations</span>
            </a>
            <a href="{{ route('admin.employees.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('admin.employees.*') ? 'bg-indigo-600/10 text-indigo-400' : 'hover:bg-slate-800 hover:text-white' }} rounded-lg group transition-colors">
                <i class="fa-solid fa-users text-lg w-5 text-center group-hover:text-indigo-400 transition-colors"></i>
                <span class="font-medium">Employees</span>
            </a>

            <a href="{{ route('admin.assets.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('admin.assets.*') ? 'bg-indigo-600/10 text-indigo-400' : 'hover:bg-slate-800 hover:text-white' }} rounded-lg group transition-colors">
                <i class="fa-solid fa-laptop text-lg w-5 text-center group-hover:text-indigo-400 transition-colors"></i>
                <span class="font-medium">Assets</span>
            </a>

            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mt-6 mb-2 px-2">Attendance</div>
            <a href="{{ route('admin.shifts.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('admin.shifts.index') ? 'bg-indigo-600/10 text-indigo-400' : 'hover:bg-slate-800 hover:text-white' }} rounded-lg group transition-colors">
                <i class="fa-solid fa-clock text-lg w-5 text-center group-hover:text-indigo-400 transition-colors"></i>
                <span class="font-medium">Shifts</span>
            </a>
            <a href="{{ route('admin.holidays.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('admin.holidays.index') ? 'bg-indigo-600/10 text-indigo-400' : 'hover:bg-slate-800 hover:text-white' }} rounded-lg group transition-colors">
                <i class="fa-solid fa-clock text-lg w-5 text-center group-hover:text-indigo-400 transition-colors"></i>
                <span class="font-medium">Holidays</span>
            </a>
            <a href="{{ route('admin.attendance.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('admin.attendance.index') ? 'bg-indigo-600/10 text-indigo-400' : 'hover:bg-slate-800 hover:text-white' }} rounded-lg group transition-colors">
                <i
                    class="fa-solid fa-calendar-check text-lg w-5 text-center group-hover:text-indigo-400 transition-colors"></i>
                <span class="font-medium">Daily Attendance</span>
            </a>
            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mt-6 mb-2 px-2">Leave Management
            </div>

            <a href="{{ route('admin.leaves.requests') }}"
                class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('admin.leaves.requests') ? 'bg-indigo-600/10 text-indigo-400' : 'hover:bg-slate-800 hover:text-white' }} rounded-lg group transition-colors">
                <i class="fa-solid fa-inbox text-lg w-5 text-center group-hover:text-indigo-400 transition-colors"></i>
                <span class="font-medium">Leave Requests</span>
            </a>

            <a href="{{ route('admin.leaves.manage') }}"
                class="flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('admin.leaves.manage') ? 'bg-indigo-600/10 text-indigo-400' : 'hover:bg-slate-800 hover:text-white' }} rounded-lg group transition-colors">
                <i
                    class="fa-solid fa-scale-balanced text-lg w-5 text-center group-hover:text-indigo-400 transition-colors"></i>
                <span class="font-medium">Manage Leaves</span>
            </a>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden w-full">
        <!-- Top Header -->
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 sm:px-8 z-10">

            <div class="flex items-center gap-3 sm:gap-4">
                <!-- Hamburger Menu Button -->
                <button onclick="toggleSidebar()"
                    class="lg:hidden text-slate-500 hover:text-slate-700 focus:outline-none transition-colors">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
                <h2 class="text-lg sm:text-xl font-bold text-slate-800 truncate">@yield('header_title', 'Overview')</h2>
            </div>

            <div class="flex items-center gap-4 sm:gap-6">
                <button class="relative text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fa-regular fa-bell text-xl"></i>
                    <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full border border-white"></span>
                </button>

                <!-- User Profile Pill -->
                <div class="flex items-center gap-3 pl-4 sm:pl-6 border-l border-slate-200">
                    <!-- Hidden on extra small screens -->
                    <div class="hidden sm:flex flex-col items-end">
                        <span
                            class="text-sm font-semibold text-slate-800">{{ Auth::guard('admin')->user()->name ?? 'System Admin' }}</span>
                        <span class="text-xs text-slate-500">Administrator</span>
                    </div>
                    <a href="{{ route('admin.profile') }}">
                        <div
                            class="w-9 h-9 sm:w-10 sm:h-10 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center font-bold border border-indigo-200 shrink-0">
                            {{ substr(Auth::guard('admin')->user()->name ?? 'S', 0, 1) }}
                        </div>
                    </a>

                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="ml-1 sm:ml-2">
                        @csrf
                        <button type="button" onclick="confirmLogout()"
                            class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all"
                            title="Logout">
                            <i class="fa-solid fa-arrow-right-from-bracket text-lg"></i>
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Dynamic Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50 p-3 sm:p-4 lg:p-6">
            @yield('content')
        </main>
    </div>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: "{{ session('success') }}",
                confirmButtonColor: '#4f46e5',
                timer: 3000,
                timerProgressBar: true,
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
                confirmButtonColor: '#4f46e5',
                customClass: {
                    popup: 'rounded-2xl'
                }
            });
        </script>
    @endif

    <script>
        // Sidebar Toggle Logic
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
                title: 'Are you sure?',
                text: 'You will be logged out of your session.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, log out',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-lg px-4 py-2 text-sm font-semibold',
                    cancelButton: 'rounded-lg px-4 py-2 text-sm font-semibold'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        }
    </script>
</body>

</html>
