@extends('layouts.admin')
@section('title', 'Edit Employee')
@section('header_title', 'Update Employee Profile')

@section('content')
    <div class="max-w-7xl mx-auto pb-12">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Edit Profile: {{ $employee->emp_name }}</h2>
                <p class="text-slate-500 text-sm mt-1">Modify employee information and system access credentials.</p>
            </div>
            <a href="{{ route('admin.employees.index') }}"
                class="px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-lg hover:bg-slate-50 transition-colors font-medium flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-arrow-left"></i> Back to list
            </a>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 text-red-600 border-l-4 border-red-500 rounded-r-lg text-sm shadow-sm">
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

        <form action="{{ route('admin.employees.update', $employee->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- PERSONAL INFO CARD -->
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center"><i
                                class="fa-solid fa-address-card"></i></div>
                        <h3 class="font-bold text-slate-800">Personal Information</h3>
                    </div>
                    <div class="p-6 space-y-5">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-slate-700 text-sm font-semibold mb-2">Employee ID <span
                                        class="text-xs text-slate-400 font-normal">(Locked)</span></label>
                                <input type="text" name="emp_id" readonly value="{{ $employee->emp_id }}"
                                    class="w-full px-4 py-2.5 bg-slate-100 border border-slate-200 text-slate-500 rounded-lg cursor-not-allowed text-sm">
                            </div>
                            <div>
                                <label class="block text-slate-700 text-sm font-semibold mb-2">Joining Date</label>
                                <input type="date" name="joining_date"
                                    value="{{ old('joining_date', $employee->joining_date ? $employee->joining_date->format('Y-m-d') : '') }}"
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                            </div>
                        </div>
                        <div>
                            <label class="block text-slate-700 text-sm font-semibold mb-2">Full Name <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="emp_name" required
                                value="{{ old('emp_name', $employee->emp_name) }}"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-slate-700 text-sm font-semibold mb-2">Email Address</label>
                                <input type="email" name="email" value="{{ old('email', $employee->email) }}"
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                            </div>
                            <div>
                                <label class="block text-slate-700 text-sm font-semibold mb-2">Phone Number</label>
                                <input type="text" name="phone_number"
                                    value="{{ old('phone_number', $employee->phone_number) }}"
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- EMPLOYMENT DETAILS CARD -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center"><i
                                class="fa-solid fa-briefcase"></i></div>
                        <h3 class="font-bold text-slate-800">Employment Details</h3>
                    </div>
                    <div class="p-6 space-y-5">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-slate-700 text-sm font-semibold mb-2">Department</label>
                                <select name="department_id"
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 text-sm">
                                    <option value="">-- Select Department --</option>
                                    @foreach ($departments as $dept)
                                        <option value="{{ $dept->id }}"
                                            {{ old('department_id', $employee->department_id) == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->dept_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-slate-700 text-sm font-semibold mb-2">Designation</label>
                                <select name="designation_id"
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 text-sm">
                                    <option value="">-- Select Designation --</option>
                                    @foreach ($designations as $desig)
                                        <option value="{{ $desig->id }}"
                                            {{ old('designation_id', $employee->designation_id) == $desig->id ? 'selected' : '' }}>
                                            {{ $desig->designation_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-slate-700 text-sm font-semibold mb-2">Assigned Shift</label>
                                <select name="shift_id"
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 text-sm">
                                    <option value="">-- Select Shift --</option>
                                    @foreach ($shifts as $shift)
                                        <option value="{{ $shift->id }}"
                                            {{ old('shift_id', $employee->shift_id) == $shift->id ? 'selected' : '' }}>
                                            {{ $shift->shift_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-slate-700 text-sm font-semibold mb-2">Account Status</label>
                                <select name="is_active"
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 text-sm">
                                    <option value="1" {{ old('is_active', $employee->is_active) ? 'selected' : '' }}>
                                        Active (Can Login)</option>
                                    <option value="0" {{ old('is_active', $employee->is_active) ? '' : 'selected' }}>
                                        Inactive (Suspended)</option>
                                </select>
                            </div>
                        </div>
                        <div class="pt-4 border-t border-slate-100">
                            <label class="block text-slate-700 text-sm font-semibold mb-1">Change Password</label>
                            <p class="text-[11px] text-slate-500 mb-2">Leave blank if you do not want to change the current
                                password.</p>
                            <div class="relative">
                                <i class="fa-solid fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input type="password" name="password" id="password"
                                    class="w-full pl-10 pr-12 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all text-sm">
                                <button type="button" id="togglePassword"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-indigo-600 focus:outline-none transition-colors">
                                    <i class="fa-solid fa-eye" id="eyeIcon"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 360 CRM & KYC -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mt-6">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center"><i
                            class="fa-solid fa-user-shield"></i></div>
                    <h3 class="font-bold text-slate-800">360° CRM: Compensation, KYC & Emergency</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-4 gap-5">
                    <div>
                        <label class="block text-slate-700 text-sm font-semibold mb-2">Employment Type *</label>
                        <select name="employment_type"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm">
                            @foreach (['Full-Time', 'Probation', 'Intern', 'Contract'] as $type)
                                <option value="{{ $type }}"
                                    {{ old('employment_type', $employee->employment_type) == $type ? 'selected' : '' }}>
                                    {{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-slate-700 text-sm font-semibold mb-2">Annual CTC (₹)</label>
                        <input type="number" step="0.01" name="salary_ctc"
                            value="{{ old('salary_ctc', $employee->salary_ctc) }}"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-slate-700 text-sm font-semibold mb-2">PAN Number</label>
                        <input type="text" name="pan_number" value="{{ old('pan_number', $employee->pan_number) }}"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm uppercase">
                    </div>
                    <div>
                        <label class="block text-slate-700 text-sm font-semibold mb-2">Aadhaar Number</label>
                        <input type="text" name="aadhaar_number"
                            value="{{ old('aadhaar_number', $employee->aadhaar_number) }}"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-slate-700 text-sm font-semibold mb-2">Skills (Comma separated)</label>
                        <input type="text" name="skills" value="{{ old('skills', $employee->skills) }}"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-slate-700 text-sm font-semibold mb-2">Emergency Contact</label>
                        <input type="text" name="emergency_contact_name"
                            value="{{ old('emergency_contact_name', $employee->emergency_contact_name) }}"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-slate-700 text-sm font-semibold mb-2">Emergency Phone</label>
                        <input type="text" name="emergency_contact_phone"
                            value="{{ old('emergency_contact_phone', $employee->emergency_contact_phone) }}"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm">
                    </div>

                    <div class="md:col-span-4 border-t border-slate-100 pt-4 mt-2 grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div>
                            <label class="block text-slate-700 text-sm font-semibold mb-2">Bank Name</label>
                            <input type="text" name="bank_name" value="{{ old('bank_name', $employee->bank_name) }}"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-slate-700 text-sm font-semibold mb-2">Account Number</label>
                            <input type="text" name="bank_account_no"
                                value="{{ old('bank_account_no', $employee->bank_account_no) }}"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-slate-700 text-sm font-semibold mb-2">IFSC Code</label>
                            <input type="text" name="bank_ifsc" value="{{ old('bank_ifsc', $employee->bank_ifsc) }}"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm uppercase">
                        </div>
                    </div>
                </div>
            </div>

            <!-- DYNAMIC EDUCATION -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mt-6">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center"><i
                                class="fa-solid fa-graduation-cap"></i></div>
                        <h3 class="font-bold text-slate-800">Education Details</h3>
                    </div>
                    <button type="button" onclick="addEducationRow()"
                        class="px-3 py-1.5 bg-amber-50 text-amber-600 font-bold rounded border border-amber-200 text-xs flex items-center gap-1 hover:bg-amber-100 transition-colors">
                        <i class="fa-solid fa-plus"></i> Add Education
                    </button>
                </div>
                <div class="p-6 space-y-4" id="education-wrapper">
                    @php $oldEducations = old('educations', $employee->educations->toArray()); @endphp
                    @foreach ($oldEducations as $index => $edu)
                        <div class="bg-slate-50 border border-slate-200 p-4 rounded-xl relative group">
                            <input type="hidden" name="educations[{{ $index }}][id]"
                                value="{{ $edu['id'] ?? '' }}">
                            <button type="button" onclick="this.parentElement.remove()"
                                class="absolute top-2 right-2 w-8 h-8 rounded-lg bg-rose-50 text-rose-500 flex items-center justify-center hover:bg-rose-500 hover:text-white transition-colors"><i
                                    class="fa-solid fa-trash"></i></button>
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-2">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Level *</label>
                                    <select name="educations[{{ $index }}][level]" required
                                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm">
                                        <option value="10th" {{ ($edu['level'] ?? '') == '10th' ? 'selected' : '' }}>
                                            10th (Matriculation)</option>
                                        <option value="12th" {{ ($edu['level'] ?? '') == '12th' ? 'selected' : '' }}>
                                            12th (Intermediate)</option>
                                        <option value="Diploma"
                                            {{ ($edu['level'] ?? '') == 'Diploma' ? 'selected' : '' }}>Diploma</option>
                                        <option value="Graduation"
                                            {{ ($edu['level'] ?? '') == 'Graduation' ? 'selected' : '' }}>Graduation (UG)
                                        </option>
                                        <option value="Post-Graduation"
                                            {{ ($edu['level'] ?? '') == 'Post-Graduation' ? 'selected' : '' }}>
                                            Post-Graduation (PG)</option>
                                        <option value="Doctorate"
                                            {{ ($edu['level'] ?? '') == 'Doctorate' ? 'selected' : '' }}>Doctorate (PhD)
                                        </option>
                                    </select>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Institution Name
                                        *</label>
                                    <input type="text" name="educations[{{ $index }}][institution]"
                                        value="{{ $edu['institution'] ?? '' }}" required
                                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm">
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-bold text-slate-500 uppercase mb-1">Specialization</label>
                                    <input type="text" name="educations[{{ $index }}][specialization]"
                                        value="{{ $edu['specialization'] ?? '' }}"
                                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Start
                                        Month/Year</label>
                                    <input type="month" name="educations[{{ $index }}][start_year]"
                                        value="{{ $edu['start_year'] ?? '' }}"
                                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">End
                                        Month/Year</label>
                                    <input type="month" name="educations[{{ $index }}][end_year]"
                                        value="{{ $edu['end_year'] ?? '' }}"
                                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- DYNAMIC EXPERIENCE -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mt-6">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center"><i
                                class="fa-solid fa-business-time"></i></div>
                        <h3 class="font-bold text-slate-800">Previous Work Experience</h3>
                    </div>
                    <button type="button" onclick="addExperienceRow()"
                        class="px-3 py-1.5 bg-blue-50 text-blue-600 font-bold rounded border border-blue-200 text-xs flex items-center gap-1 hover:bg-blue-100 transition-colors">
                        <i class="fa-solid fa-plus"></i> Add Experience
                    </button>
                </div>
                <div class="p-6 space-y-4" id="experience-wrapper">
                    @php $oldExperiences = old('experiences', $employee->experiences->toArray()); @endphp
                    @foreach ($oldExperiences as $index => $exp)
                        <div class="bg-slate-50 border border-slate-200 p-4 rounded-xl relative group">
                            <input type="hidden" name="experiences[{{ $index }}][id]"
                                value="{{ $exp['id'] ?? '' }}">
                            <button type="button" onclick="this.parentElement.remove()"
                                class="absolute top-2 right-2 w-8 h-8 rounded-lg bg-rose-50 text-rose-500 flex items-center justify-center hover:bg-rose-500 hover:text-white transition-colors"><i
                                    class="fa-solid fa-trash"></i></button>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Company Name
                                        *</label>
                                    <input type="text" name="experiences[{{ $index }}][company_name]"
                                        value="{{ $exp['company_name'] ?? '' }}" required
                                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Job Role *</label>
                                    <input type="text" name="experiences[{{ $index }}][job_role]"
                                        value="{{ $exp['job_role'] ?? '' }}" required
                                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">From Date
                                        *</label>
                                    @php $fromDateVal = isset($exp['from_date']) ? (is_string($exp['from_date']) ? substr($exp['from_date'], 0, 10) : $exp['from_date']->format('Y-m-d')) : ''; @endphp
                                    <input type="date" name="experiences[{{ $index }}][from_date]"
                                        value="{{ $fromDateVal }}" required
                                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">To Date *</label>
                                    @php $toDateVal = isset($exp['to_date']) ? (is_string($exp['to_date']) ? substr($exp['to_date'], 0, 10) : $exp['to_date']->format('Y-m-d')) : ''; @endphp
                                    <input type="date" name="experiences[{{ $index }}][to_date]"
                                        value="{{ $toDateVal }}" required
                                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <button type="submit"
                    class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold transition-colors shadow-sm flex items-center gap-2">
                    <i class="fa-solid fa-save"></i> Save Changes
                </button>
            </div>
        </form>
    </div>

    <script>
        function addEducationRow() {
            const wrapper = document.getElementById('education-wrapper');
            const id = Date.now() + Math.floor(Math.random() * 100);
            const html = `
            <div class="bg-slate-50 border border-slate-200 p-4 rounded-xl relative group">
                <button type="button" onclick="this.parentElement.remove()" class="absolute top-2 right-2 w-8 h-8 rounded-lg bg-rose-50 text-rose-500 flex items-center justify-center hover:bg-rose-500 hover:text-white transition-colors">
                    <i class="fa-solid fa-trash"></i>
                </button>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Level *</label>
                        <select name="educations[${id}][level]" required class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm">
                            <option value="10th">10th (Matriculation)</option>
                            <option value="12th">12th (Intermediate)</option>
                            <option value="Diploma">Diploma</option>
                            <option value="Graduation" selected>Graduation (UG)</option>
                            <option value="Post-Graduation">Post-Graduation (PG)</option>
                            <option value="Doctorate">Doctorate (PhD)</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Institution Name *</label>
                        <input type="text" name="educations[${id}][institution]" required class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Specialization</label>
                        <input type="text" name="educations[${id}][specialization]" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Start Month/Year</label>
                        <input type="month" name="educations[${id}][start_year]" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">End Month/Year</label>
                        <input type="month" name="educations[${id}][end_year]" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm">
                    </div>
                </div>
            </div>
        `;
            wrapper.insertAdjacentHTML('beforeend', html);
        }

        function addExperienceRow() {
            const wrapper = document.getElementById('experience-wrapper');
            const id = Date.now() + Math.floor(Math.random() * 100);
            const html = `
            <div class="bg-slate-50 border border-slate-200 p-4 rounded-xl relative group">
                <button type="button" onclick="this.parentElement.remove()" class="absolute top-2 right-2 w-8 h-8 rounded-lg bg-rose-50 text-rose-500 flex items-center justify-center hover:bg-rose-500 hover:text-white transition-colors">
                    <i class="fa-solid fa-trash"></i>
                </button>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Company Name *</label>
                        <input type="text" name="experiences[${id}][company_name]" required class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Job Role *</label>
                        <input type="text" name="experiences[${id}][job_role]" required class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">From Date *</label>
                        <input type="date" name="experiences[${id}][from_date]" required class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">To Date *</label>
                        <input type="date" name="experiences[${id}][to_date]" required class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm">
                    </div>
                </div>
            </div>
        `;
            wrapper.insertAdjacentHTML('beforeend', html);
        }

        // Password Toggle Logic
        const togglePasswordBtn = document.querySelector('#togglePassword');
        const passwordInput = document.querySelector('#password');
        const eyeIconEl = document.querySelector('#eyeIcon');

        if (togglePasswordBtn && passwordInput && eyeIconEl) {
            togglePasswordBtn.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                eyeIconEl.classList.toggle('fa-eye');
                eyeIconEl.classList.toggle('fa-eye-slash');
            });
        }
    </script>
@endsection
