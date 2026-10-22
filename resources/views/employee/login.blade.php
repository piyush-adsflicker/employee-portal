<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Login | Attendance System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-slate-50 flex items-center justify-center h-screen relative overflow-hidden text-slate-800">

    <div class="bg-white p-8 sm:p-10 rounded-2xl shadow-xl w-full max-w-md z-10 border border-slate-100">

        <div class="text-center mb-8">
            <div
                class="w-14 h-14 bg-teal-600 text-white rounded-xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-teal-200">
                <i class="fa-solid fa-user-tie text-3xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-slate-900">Employee Login</h2>
            <p class="text-slate-500 text-sm mt-2">Sign in to your daily workspace</p>
        </div>

        <form action="{{ route('login.submit') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-slate-700 text-sm font-semibold mb-2" for="emp_id">Employee ID</label>
                <div class="relative">
                    <i
                        class="fa-solid fa-id-badge absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400 text-lg"></i>
                    <input type="text" name="emp_id" id="emp_id" required value="{{ old('emp_id') }}"
                        placeholder="Enter your employee ID"
                        class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all uppercase">
                </div>
            </div>

            <div>
                <label class="block text-slate-700 text-sm font-semibold mb-2" for="password">Password</label>
                <div class="relative">
                    <i
                        class="fa-solid fa-lock absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400 text-lg"></i>
                    <input type="password" name="password" id="password" required placeholder="Enter your password"
                        class="w-full pl-10 pr-12 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
                    <button type="button" id="togglePassword"
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-slate-400 hover:text-teal-600 focus:outline-none transition-colors">
                        <i class="fa-solid fa-eye text-lg" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember"
                        class="w-4 h-4 text-teal-600 rounded border-slate-300 focus:ring-teal-500">
                    <span class="text-sm text-slate-600">Remember me</span>
                </label>
            </div>

            <button type="submit"
                class="w-full bg-teal-600 text-white font-semibold py-2.5 px-4 rounded-lg hover:bg-teal-700 focus:ring-4 focus:ring-teal-100 transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                <span>Sign In</span>
                <i class="fa-solid fa-arrow-right"></i>
            </button>
        </form>

        <div class="mt-6 text-center">
            <a href="{{ route('home') }}" class="text-sm text-slate-500 hover:text-teal-600 transition-colors"><i
                    class="fa-solid fa-arrow-left text-xs mr-1"></i> Back to Portal</a>
        </div>
    </div>

    <!-- Scripts for Password Toggle & Alerts -->
    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const eyeIcon = document.querySelector('#eyeIcon');

        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            eyeIcon.classList.toggle('fa-eye');
            eyeIcon.classList.toggle('fa-eye-slash');
        });
    </script>

    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: '{{ $errors->first() }}',
                confirmButtonColor: '#0d9488',
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
                title: 'Access Denied',
                text: "{{ session('error') }}",
                confirmButtonColor: '#0d9488',
                heightAuto: false,
                customClass: {
                    popup: 'rounded-2xl'
                }
            });
        </script>
    @endif

</body>

</html>
