@extends('layouts.admin')
@section('title', 'Admin Profile')
@section('header_title', 'My Profile')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        @if ($errors->any())
            <div class="p-4 bg-red-50 text-red-600 border-l-4 border-red-500 rounded-r-lg text-sm shadow-sm">
                <div class="flex items-center gap-2 font-bold mb-1">
                    <i class="fa-solid fa-circle-exclamation"></i> Please fix the following errors:
                </div>
                <ul class="list-disc pl-6 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Profile Overview Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden relative">
            <div class="h-32 bg-gradient-to-r from-indigo-600 to-blue-500"></div>
            <div class="px-8 pb-8 relative">
                <div class="flex flex-col sm:flex-row items-center sm:items-end gap-6 -mt-16 mb-6">
                    <!-- Avatar -->
                    <div class="relative group">
                        <div
                            class="w-32 h-32 rounded-full border-4 border-white shadow-lg overflow-hidden flex items-center justify-center bg-indigo-50 text-indigo-600 text-5xl font-bold">
                            {{ substr($admin->name, 0, 1) }}
                        </div>
                    </div>
                    <div class="flex-1 text-center sm:text-left mt-4 sm:mt-0">
                        <h2 class="text-3xl font-extrabold text-slate-800">{{ $admin->name }}</h2>
                        <p class="text-indigo-600 font-semibold text-lg">System Administrator</p>
                    </div>
                    <div class="mt-4 sm:mt-0">
                        <button onclick="openProfileModal()"
                            class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-semibold transition-colors shadow-sm flex items-center gap-2">
                            <i class="fa-solid fa-pen-to-square"></i> Edit Profile
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-slate-100 pt-6">
                    <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl border border-slate-100">
                        <div
                            class="w-12 h-12 rounded-full bg-white flex items-center justify-center text-slate-400 shadow-sm shrink-0">
                            <i class="fa-solid fa-envelope text-lg"></i>
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-xs text-slate-500 font-bold uppercase mb-0.5">Email Address</p>
                            <p class="text-slate-800 font-medium truncate">{{ $admin->email }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl border border-slate-100">
                        <div
                            class="w-12 h-12 rounded-full bg-white flex items-center justify-center text-slate-400 shadow-sm shrink-0">
                            <i class="fa-solid fa-shield-halved text-lg"></i>
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-xs text-slate-500 font-bold uppercase mb-0.5">Account Role</p>
                            <p class="text-slate-800 font-medium truncate">Full Access</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div id="profileModal"
        class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm overflow-y-auto w-full h-full flex items-center justify-center opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg transform scale-95 transition-transform duration-300 m-4"
            id="profileModalContent">
            <div class="flex items-center justify-between p-5 border-b border-slate-100 bg-slate-50/50 rounded-t-2xl">
                <h3 class="text-lg font-bold text-slate-800">Update Profile</h3>
                <button onclick="closeProfileModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>

            <form action="{{ route('admin.profile.update') }}" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-slate-700 text-sm font-semibold mb-1" for="name">Full Name *</label>
                    <div class="relative">
                        <i class="fa-solid fa-user absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" name="name" id="name" required value="{{ old('name', $admin->name) }}"
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-slate-700 text-sm font-semibold mb-1" for="email">Email Address *</label>
                    <div class="relative">
                        <i class="fa-solid fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="email" name="email" id="email" required
                            value="{{ old('email', $admin->email) }}"
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all text-sm">
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 mt-2">
                    <h4 class="text-sm font-bold text-slate-800 mb-3">Security</h4>
                    <p class="text-[11px] text-slate-500 mb-3">Leave password fields blank if you do not wish to change your
                        current password.</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-slate-700 text-xs font-semibold mb-1" for="password">New
                                Password</label>
                            <div class="relative">
                                <i class="fa-solid fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input type="password" name="password" id="password" minlength="8"
                                    class="w-full pl-10 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all text-sm">
                                <button type="button" onclick="togglePassword('password', 'eyeIcon1')"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-indigo-600 focus:outline-none transition-colors">
                                    <i class="fa-solid fa-eye" id="eyeIcon1"></i>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-slate-700 text-xs font-semibold mb-1"
                                for="password_confirmation">Confirm Password</label>
                            <div class="relative">
                                <i
                                    class="fa-solid fa-shield absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    minlength="8"
                                    class="w-full pl-10 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all text-sm">
                                <button type="button" onclick="togglePassword('password_confirmation', 'eyeIcon2')"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-indigo-600 focus:outline-none transition-colors">
                                    <i class="fa-solid fa-eye" id="eyeIcon2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" onclick="closeProfileModal()"
                        class="px-5 py-2 text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg font-semibold transition-colors text-sm">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-semibold transition-colors shadow-sm flex items-center gap-2 text-sm">
                        <i class="fa-solid fa-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('profileModal');
        const modalContent = document.getElementById('profileModalContent');

        function openProfileModal() {
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modalContent.classList.remove('scale-95');
            }, 10);
        }

        function closeProfileModal() {
            modal.classList.add('opacity-0');
            modalContent.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeProfileModal();
        });

        // Reusable Password Toggle
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
@endsection