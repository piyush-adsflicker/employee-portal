<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Portal | Attendance System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-slate-50 flex flex-col min-h-screen font-sans text-slate-800 relative overflow-x-hidden">
    
    <!-- Top Navigation -->
    <nav class="w-full px-6 py-4 md:px-12 flex items-center justify-between z-10">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-teal-600 text-white rounded-xl flex items-center justify-center shadow-lg shadow-teal-600/20">
                <i class="fa-solid fa-building-user text-lg"></i>
            </div>
            <span class="text-xl font-bold text-slate-800 tracking-tight">Staff<span class="text-teal-600">Portal</span></span>
        </div>
        <div>
            @if(Auth::guard('admin')->check())
                <a href="{{ route('admin.dashboard') }}"
                    class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm hover:shadow-md transition-all flex items-center gap-2">
                    Admin Dashboard <i class="fa-solid fa-arrow-right"></i>
                </a>
            @elseif(Auth::check())
                <a href="{{ route('employee.dashboard') }}"
                    class="px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white text-sm font-semibold rounded-lg shadow-sm hover:shadow-md transition-all flex items-center gap-2">
                    My Workspace <i class="fa-solid fa-arrow-right"></i>
                </a>
            @else
                <a href="{{ route('login') }}"
                    class="px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white text-sm font-semibold rounded-lg shadow-sm hover:shadow-md transition-all flex items-center gap-2">
                    Sign In <i class="fa-solid fa-arrow-right"></i>
                </a>
            @endif
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col items-center justify-center p-6 text-center z-10">
        <div class="max-w-3xl mx-auto space-y-6">
            <h1 class="text-4xl md:text-6xl font-extrabold text-slate-900 tracking-tight leading-tight">
                Your Daily <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-600 to-emerald-500">Workspace</span>
            </h1>
            <p class="text-lg md:text-xl text-slate-500 max-w-2xl mx-auto leading-relaxed">
                Welcome to your employee portal. Seamlessly log your daily attendance, review your assigned shifts, and
                manage your leave requests all in one secure place.
            </p>
            
            <div class="pt-8 flex flex-col sm:flex-row items-center justify-center gap-4">
                @if(Auth::guard('admin')->check())
                    <a href="{{ route('admin.dashboard') }}"
                        class="w-full sm:w-auto px-8 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white text-base font-semibold rounded-xl shadow-lg transition-all flex items-center justify-center gap-2">
                        Access Admin Panel <i class="fa-solid fa-shield-halved"></i>
                    </a>
                @elseif(Auth::check())
                    <a href="{{ route('employee.dashboard') }}"
                        class="w-full sm:w-auto px-8 py-3.5 bg-slate-900 hover:bg-slate-800 text-white text-base font-semibold rounded-xl shadow-lg transition-all flex items-center justify-center gap-2">
                        Access Dashboard <i class="fa-solid fa-fingerprint"></i>
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="w-full sm:w-auto px-8 py-3.5 bg-slate-900 hover:bg-slate-800 text-white text-base font-semibold rounded-xl shadow-lg transition-all flex items-center justify-center gap-2">
                        Employee Login <i class="fa-solid fa-fingerprint"></i>
                    </a>
                @endif
            </div>

            <div class="pt-16 grid grid-cols-1 md:grid-cols-3 gap-8 text-left max-w-4xl mx-auto">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                    <div class="w-12 h-12 bg-teal-50 text-teal-600 rounded-lg flex items-center justify-center mb-4 text-xl">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                    <h3 class="font-bold text-slate-800 mb-2">Track Attendance</h3>
                    <p class="text-sm text-slate-500">Mark your daily check-ins and check-outs with real-time logging.</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                    <div class="w-12 h-12 bg-teal-50 text-teal-600 rounded-lg flex items-center justify-center mb-4 text-xl">
                        <i class="fa-solid fa-calendar-check"></i>
                    </div>
                    <h3 class="font-bold text-slate-800 mb-2">Leave Management</h3>
                    <p class="text-sm text-slate-500">Apply for time off and track your approval status directly.</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                    <div class="w-12 h-12 bg-teal-50 text-teal-600 rounded-lg flex items-center justify-center mb-4 text-xl">
                        <i class="fa-solid fa-clipboard-user"></i>
                    </div>
                    <h3 class="font-bold text-slate-800 mb-2">Shift Schedule</h3>
                    <p class="text-sm text-slate-500">View your assigned working hours, grace periods, and holidays.</p>
                </div>
            </div>
        </div>
    </main>
</body>
</html>