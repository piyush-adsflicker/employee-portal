<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Attendance System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-slate-50 flex items-center justify-center h-screen relative overflow-hidden text-slate-800">

    <div class="bg-white p-8 sm:p-10 rounded-2xl shadow-xl w-full max-w-md z-10 border border-slate-100">
        
        <div class="text-center mb-8">
            <div class="w-14 h-14 bg-indigo-600 text-white rounded-xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-indigo-200">
                <i class="fa-solid fa-user-clock text-3xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-slate-900">Admin Workspace</h2>
            <p class="text-slate-500 text-sm mt-2">Sign in to manage employee attendance</p>
        </div>

        <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-5">
            @csrf
            
            <div>
                <label class="block text-slate-700 text-sm font-semibold mb-2" for="email">Email Address</label>
                <div class="relative">
                    <i class="fa-solid fa-envelope absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400 text-lg"></i>
                    <input type="email" name="email" id="email" required value="{{ old('email') }}"
                           class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                </div>
            </div>

            <div>
                <label class="block text-slate-700 text-sm font-semibold mb-2" for="password">Password</label>
                <div class="relative">
                    <i class="fa-solid fa-lock absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400 text-lg"></i>
                    <input type="password" name="password" id="password" required 
                           class="w-full pl-10 pr-12 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                    <!-- Password Toggle Button -->
                    <button type="button" id="togglePassword" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-slate-400 hover:text-indigo-600 focus:outline-none transition-colors">
                        <i class="fa-solid fa-eye text-lg" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500">
                    <span class="text-sm text-slate-600">Remember me</span>
                </label>
            </div>

            <button type="submit" class="w-full bg-indigo-600 text-white font-semibold py-2.5 px-4 rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-100 transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                <span>Sign In</span>
                <i class="fa-solid fa-arrow-right"></i>
            </button>
        </form>
    </div>

    <!-- Password Visibility Script -->
    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const eyeIcon = document.querySelector('#eyeIcon');

        togglePassword.addEventListener('click', function () {
            // Toggle the type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            
            // Toggle the eye icon
            eyeIcon.classList.toggle('fa-eye');
            eyeIcon.classList.toggle('fa-eye-slash');
        });
    </script>

    @if($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: '{{ $errors->first() }}',
                confirmButtonColor: '#4f46e5',
                heightAuto: false,
                customClass: { popup: 'rounded-2xl' }
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Access Denied',
                text: "{{ session('error') }}",
                confirmButtonColor: '#4f46e5',
                heightAuto: false,
                customClass: { popup: 'rounded-2xl' }
            });
        </script>
    @endif
</body>
</html>