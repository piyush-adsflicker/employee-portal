@extends('layouts.employee')
@section('title', 'My Profile')
@section('header_title', 'My Profile')

@section('content')
    <div class="max-w-6xl mx-auto space-y-6 pb-12">
        @if ($errors->any())
            <div class="p-4 bg-red-50 text-red-600 border-l-4 border-red-500 rounded-r-lg text-sm shadow-sm mb-6">
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

        <!-- Profile Header Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden relative">
            <div class="h-28 bg-gradient-to-r from-teal-500 to-emerald-400"></div>
            <div class="px-8 pb-5 relative">
                <div class="flex flex-col sm:flex-row items-center sm:items-end gap-6 -mt-16 mb-4">
                    <!-- Profile Avatar (Clickable for Lightbox) -->
                    <div class="relative group cursor-zoom-in hover:opacity-90 transition-opacity"
                        onclick="openPhotoLightbox()">
                        <div
                            class="w-32 h-32 rounded-full border-4 border-white shadow-lg overflow-hidden flex items-center justify-center bg-slate-50">
                            @if ($employee->profile_photo)
                                <img src="{{ asset($employee->profile_photo) }}" alt="Profile Photo"
                                    class="w-full h-full object-cover">
                            @else
                                <span
                                    class="text-5xl font-bold text-teal-600">{{ substr($employee->emp_name, 0, 1) }}</span>
                            @endif
                        </div>
                        <div
                            class="absolute inset-0 rounded-full flex items-center justify-center bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity">
                            <i class="fa-solid fa-expand text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="flex-1 text-center sm:text-left">
                        <h2 class="text-3xl font-extrabold text-slate-800">{{ $employee->emp_name }}</h2>
                        <p class="text-teal-600 font-semibold text-lg">
                            {{ $employee->designation->designation_name ?? 'Designation Not Set' }}
                        </p>
                    </div>
                    <div class="mt-4 sm:mt-0">
                        <button onclick="openProfileModal()"
                            class="px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white rounded-lg font-medium transition-colors shadow-sm flex items-center gap-2">
                            <i class="fa-solid fa-user-pen"></i> Edit Profile
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Contact Information -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-teal-100 text-teal-600 flex items-center justify-center"><i
                            class="fa-solid fa-address-book"></i></div>
                    <h3 class="font-bold text-slate-800">Contact Information</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center gap-4 py-2 border-b border-slate-50">
                        <div
                            class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 shrink-0">
                            <i class="fa-solid fa-envelope"></i></div>
                        <div class="overflow-hidden">
                            <p class="text-xs text-slate-500 font-bold uppercase mb-0.5">Email Address</p>
                            <p class="text-slate-800 font-medium truncate">{{ $employee->email ?? 'Not provided' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 py-2 border-b border-slate-50">
                        <div
                            class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 shrink-0">
                            <i class="fa-solid fa-phone"></i></div>
                        <div class="overflow-hidden">
                            <p class="text-xs text-slate-500 font-bold uppercase mb-0.5">Phone Number</p>
                            <p class="text-slate-800 font-medium truncate">{{ $employee->phone_number ?? 'Not provided' }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 py-2 border-b border-slate-50">
                        <div
                            class="w-10 h-10 rounded-full bg-rose-50 flex items-center justify-center text-rose-500 shrink-0">
                            <i class="fa-solid fa-truck-medical"></i></div>
                        <div class="overflow-hidden">
                            <p class="text-xs text-slate-500 font-bold uppercase mb-0.5">Emergency Contact
                                ({{ $employee->emergency_contact_relation ?: 'N/A' }})</p>
                            <p class="text-slate-800 font-medium truncate">{{ $employee->emergency_contact_name ?: 'N/A' }}
                                - {{ $employee->emergency_contact_phone ?: 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Employment Details -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center"><i
                            class="fa-solid fa-briefcase"></i></div>
                    <h3 class="font-bold text-slate-800">Employment Details</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between items-center py-2 border-b border-slate-50">
                        <span class="text-sm text-slate-500">Employee ID</span>
                        <span
                            class="font-bold text-slate-800 bg-slate-100 px-2.5 py-1 rounded-md">{{ $employee->emp_id }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-slate-50">
                        <span class="text-sm text-slate-500">Department</span>
                        <span class="font-semibold text-slate-800">{{ $employee->department->dept_name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-slate-50">
                        <span class="text-sm text-slate-500">Assigned Shift</span>
                        <span class="font-semibold text-slate-800">
                            {{ $employee->shift->shift_name ?? 'N/A' }}
                            @if ($employee->shift)
                                ({{ \Carbon\Carbon::parse($employee->shift->start_time)->format('h:i A') }} -
                                {{ \Carbon\Carbon::parse($employee->shift->end_time)->format('h:i A') }})
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm text-slate-500">Joining Date</span>
                        <span
                            class="font-semibold text-slate-800">{{ $employee->joining_date ? $employee->joining_date->format('F d, Y') : 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Identity & Bank (Masked) -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center"><i
                            class="fa-solid fa-building-columns"></i></div>
                    <h3 class="font-bold text-slate-800">Identity & Payroll Information</h3>
                </div>
                <div class="p-6">
                    <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider mb-3">Statutory Identity</p>
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between items-center py-1">
                            <span class="text-sm text-slate-500"><i class="fa-solid fa-id-card text-slate-300 w-5"></i>
                                PAN</span>
                            <span
                                class="font-mono font-bold text-slate-800 uppercase">{{ $employee->pan_number ? Str::mask($employee->pan_number, '*', 2, -2) : '--' }}</span>
                        </div>
                        <div class="flex justify-between items-center py-1">
                            <span class="text-sm text-slate-500"><i class="fa-solid fa-fingerprint text-slate-300 w-5"></i>
                                Aadhaar</span>
                            <span
                                class="font-mono font-bold text-slate-800">{{ $employee->aadhaar_number ? Str::mask($employee->aadhaar_number, '*', 0, -4) : '--' }}</span>
                        </div>
                    </div>
                    <p
                        class="text-[10px] text-slate-400 uppercase font-bold tracking-wider mb-3 border-t border-slate-100 pt-4">
                        Bank Details</p>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center py-1">
                            <span class="text-sm text-slate-500">Bank Name</span>
                            <span class="font-semibold text-slate-800">{{ $employee->bank_name ?: '--' }}</span>
                        </div>
                        <div class="flex justify-between items-center py-1">
                            <span class="text-sm text-slate-500">Account No.</span>
                            <span
                                class="font-mono font-bold text-slate-800">{{ $employee->bank_account_no ? Str::mask($employee->bank_account_no, '*', 0, -4) : '--' }}</span>
                        </div>
                        <div class="flex justify-between items-center py-1">
                            <span class="text-sm text-slate-500">IFSC Code</span>
                            <span class="font-mono font-semibold text-slate-700">{{ $employee->bank_ifsc ?: '--' }}</span>
                        </div>
                    </div>
                    <div class="mt-4 p-2 bg-slate-50 border border-slate-100 rounded text-center">
                        <p class="text-[10px] text-slate-400"><i class="fa-solid fa-lock text-slate-300"></i> Sensitive data
                            is masked for security. Contact HR for updates.</p>
                    </div>
                </div>
            </div>

            <!-- Education & Experience Lists -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 space-y-4 h-fit">
                    <h3 class="font-bold text-slate-800 border-b border-slate-100 pb-3 flex items-center gap-2">
                        <i class="fa-solid fa-graduation-cap text-amber-500"></i> Education History
                    </h3>
                    <div class="space-y-4 relative border-l border-slate-200 ml-2">
                        @forelse($employee->educations as $edu)
                            <div class="pl-4 relative">
                                <div
                                    class="absolute w-2.5 h-2.5 bg-amber-400 rounded-full -left-[5px] top-1.5 border-2 border-white">
                                </div>
                                <h4 class="font-bold text-slate-800 text-sm">{{ $edu->level }} -
                                    {{ $edu->specialization }}</h4>
                                <p class="text-xs text-slate-600 font-semibold">{{ $edu->institution }}</p>
                                <p class="text-[11px] text-slate-400 mt-1">
                                    {{ $edu->start_year ? \Carbon\Carbon::parse($edu->start_year)->format('M Y') : 'N/A' }}
                                    -
                                    {{ $edu->end_year ? \Carbon\Carbon::parse($edu->end_year)->format('M Y') : 'N/A' }}
                                </p>
                            </div>
                        @empty
                            <p class="pl-4 text-xs text-slate-400 italic">No education details recorded.</p>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 space-y-4 h-fit">
                    <h3 class="font-bold text-slate-800 border-b border-slate-100 pb-3 flex items-center gap-2">
                        <i class="fa-solid fa-business-time text-blue-500"></i> Work Experience
                    </h3>
                    <div class="space-y-4 relative border-l border-slate-200 ml-2">
                        @forelse($employee->experiences as $exp)
                            <div class="pl-4 relative">
                                <div
                                    class="absolute w-2.5 h-2.5 bg-blue-500 rounded-full -left-[5px] top-1.5 border-2 border-white">
                                </div>
                                <h4 class="font-bold text-slate-800 text-sm">{{ $exp->job_role }}</h4>
                                <p class="text-xs text-slate-600 font-semibold">{{ $exp->company_name }}</p>
                                <p class="text-[11px] text-slate-400 mt-1">
                                    {{ $exp->from_date->format('M Y') }} -
                                    {{ $exp->to_date ? $exp->to_date->format('M Y') : 'N/A' }}
                                </p>
                            </div>
                        @empty
                            <p class="pl-4 text-xs text-slate-400 italic">No prior experience recorded.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- NEW: Photo Lightbox Modal -->
    @if ($employee->profile_photo)
        <div id="photoLightbox" onclick="closePhotoLightbox()"
            class="fixed inset-0 z-[100] hidden bg-slate-900/90 backdrop-blur-sm flex items-center justify-center opacity-0 transition-opacity duration-300 cursor-zoom-out">
            <img id="lightbox-img" src="{{ asset($employee->profile_photo) }}"
                class="max-w-[90%] max-h-[90vh] rounded-xl shadow-2xl transform scale-95 transition-transform duration-300 object-contain">
            <button class="absolute top-6 right-6 text-white hover:text-slate-300 text-3xl"><i
                    class="fa-solid fa-xmark"></i></button>
        </div>
    @endif

    <!-- Keep Existing Edit Modal Here (Removed for brevity, keep the existing one in your code) -->
    <!-- Edit Profile Modal -->
    <div id="profileModal"
        class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm overflow-y-auto w-full h-full flex items-center justify-center opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg transform scale-95 transition-transform duration-300 m-4"
            id="profileModalContent">
            <div class="flex items-center justify-between p-4 border-b border-slate-100">
                <h3 class="text-lg font-bold text-slate-800">Edit Profile</h3>
                <button onclick="closeProfileModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            <form action="{{ route('employee.profile.update') }}" method="POST" enctype="multipart/form-data"
                class="px-5 py-3 space-y-3">
                @csrf @method('PUT')
                <div>
                    <label class="block text-slate-700 text-sm font-semibold mb-1" for="profile_photo">Profile
                        Photo</label>
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-full border border-slate-200 overflow-hidden bg-slate-50 shrink-0 relative"
                            id="imagePreviewContainer">
                            @if ($employee->profile_photo)
                                <img id="imagePreview" src="{{ asset($employee->profile_photo) }}"
                                    class="w-full h-full object-cover">
                            @else
                                <div id="imagePlaceholder"
                                    class="w-full h-full flex items-center justify-center text-slate-400"><i
                                        class="fa-solid fa-image text-xl"></i></div>
                                <img id="imagePreview" src="" class="w-full h-full object-cover hidden">
                            @endif
                        </div>
                        <div class="flex-1">
                            <input type="file" name="profile_photo" id="profile_photo"
                                accept="image/jpeg,image/png,image/jpg,image/webp" onchange="previewImage(event)"
                                class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 transition-colors">
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-slate-700 text-sm font-semibold mb-1">Full Name *</label>
                    <div class="relative">
                        <i class="fa-solid fa-user absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" name="emp_name" required
                            value="{{ old('emp_name', $employee->emp_name) }}"
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-500 text-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-slate-700 text-sm font-semibold mb-1">Email Address</label>
                    <div class="relative">
                        <i class="fa-solid fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="email" name="email" value="{{ old('email', $employee->email) }}"
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-500 text-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-slate-700 text-sm font-semibold mb-1">Phone Number</label>
                    <div class="relative">
                        <i class="fa-solid fa-phone absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" name="phone_number"
                            value="{{ old('phone_number', $employee->phone_number) }}"
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-500 text-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-slate-700 text-sm font-semibold mb-1">Change Password</label>
                    <div class="relative">
                        <i class="fa-solid fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="password" name="password" id="password" minlength="8"
                            class="w-full pl-10 pr-12 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-500 text-sm">
                        <button type="button" id="togglePassword"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-teal-600"><i
                                class="fa-solid fa-eye" id="eyeIcon"></i></button>
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3 border-t border-slate-100 pt-5">
                    <button type="button" onclick="closeProfileModal()"
                        class="px-5 py-2.5 text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg font-medium transition-colors">Cancel</button>
                    <button type="submit"
                        class="px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white rounded-lg font-medium transition-colors shadow-sm flex items-center gap-2"><i
                            class="fa-solid fa-save"></i> Save Changes</button>
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
            setTimeout(() => modal.classList.add('hidden'), 300);
        }

        // Lightbox Functions
        function openPhotoLightbox() {
            const lightbox = document.getElementById('photoLightbox');
            if (!lightbox) return;
            const img = document.getElementById('lightbox-img');
            lightbox.classList.remove('hidden');
            document.body.classList.add('overflow-hidden'); // Prevent background scrolling
            setTimeout(() => {
                lightbox.classList.remove('opacity-0');
                img.classList.remove('scale-95');
            }, 10);
        }

        function closePhotoLightbox() {
            const lightbox = document.getElementById('photoLightbox');
            if (!lightbox) return;
            const img = document.getElementById('lightbox-img');
            lightbox.classList.add('opacity-0');
            img.classList.add('scale-95');
            document.body.classList.remove('overflow-hidden');
            setTimeout(() => lightbox.classList.add('hidden'), 300);
        }

        // Close when pressing Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeProfileModal();
                closePhotoLightbox();
            }
        });

        // Close on outside click
        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeProfileModal();
        });

        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('imagePreview');
            const placeholder = document.getElementById('imagePlaceholder');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    if (placeholder) placeholder.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

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
@endsection