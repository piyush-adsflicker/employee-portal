@extends('layouts.admin')
@section('title', 'Employee 360° | ' . $employee->emp_name)
@section('header_title', 'Employee 360° CRM Profile')

@section('content')
    <div class="max-w-7xl mx-auto space-y-5 pb-12">
        <!-- Top Action Bar -->
        <div class="flex flex-wrap items-center justify-between gap-3">
            <a href="{{ route('admin.employees.index') }}"
                class="px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50 font-medium flex items-center gap-2 shadow-sm text-sm">
                <i class="fa-solid fa-arrow-left"></i> Back to Directory
            </a>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.employees.attendance', $employee->id) }}"
                    class="px-4 py-2 bg-teal-50 hover:bg-teal-100 text-teal-700 rounded-xl font-semibold flex items-center gap-2 text-sm">
                    <i class="fa-solid fa-calendar-days"></i> Attendance Calendar
                </a>
                <a href="{{ route('admin.employees.edit', $employee->id) }}"
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-semibold flex items-center gap-2 shadow-sm text-sm">
                    <i class="fa-solid fa-pen-to-square"></i> Edit 360° Profile
                </a>
            </div>
        </div>

        <!-- Hero 360 Header Card -->
        <div
            class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 flex flex-col lg:flex-row gap-4 items-center lg:items-start justify-between">
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 text-center sm:text-left">
                <div onclick="openPhotoLightbox()"
                    class="cursor-pointer hover:opacity-90 transition-opacity w-24 h-24 rounded-2xl bg-indigo-100 border-4 border-slate-50 shadow flex items-center justify-center text-3xl font-bold text-indigo-600 shrink-0 overflow-hidden">
                    @if ($employee->profile_photo)
                        <img src="{{ asset($employee->profile_photo) }}" class="w-full h-full object-cover">
                    @else
                        {{ substr($employee->emp_name, 0, 1) }}
                    @endif
                </div>
                <div class="space-y-1.5">
                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                        <h2 class="text-2xl font-extrabold text-slate-800">{{ $employee->emp_name }}</h2>
                        <span
                            class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">{{ $employee->emp_id }}</span>
                        <span
                            class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $employee->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                            {{ $employee->is_active ? 'ACTIVE' : 'INACTIVE / EXITED' }}
                        </span>
                        <span
                            class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-700">{{ $employee->employment_type }}</span>
                    </div>
                    <p class="text-sm font-semibold text-slate-600">
                        {{ $employee->designation->designation_name ?? 'No Designation' }} &bull;
                        {{ $employee->department->dept_name ?? 'No Department' }}
                    </p>
                    <div
                        class="flex flex-wrap items-center justify-center sm:justify-start gap-4 text-xs text-slate-500 pt-1">
                        <span><i class="fa-solid fa-envelope mr-1 text-slate-400"></i>
                            {{ $employee->email ?? 'N/A' }}</span>
                        <span><i class="fa-solid fa-phone mr-1 text-slate-400"></i>
                            {{ $employee->phone_number ?? 'N/A' }}</span>
                        <span><i class="fa-solid fa-calendar-check mr-1 text-slate-400"></i> Joined:
                            {{ $employee->joining_date ? $employee->joining_date->format('d M, Y') : 'N/A' }}</span>
                        @if ($employee->salary_ctc)
                            <span class="font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded"><i
                                    class="fa-solid fa-indian-rupee-sign mr-1"></i> CTC:
                                ₹{{ number_format($employee->salary_ctc, 2) }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div
                class="grid grid-cols-4 gap-2 bg-slate-50 p-3 rounded-xl border border-slate-100 text-center w-full lg:w-auto">
                <div class="px-3">
                    <p class="text-[10px] font-bold text-slate-400 uppercase">Present</p>
                    <p class="text-lg font-extrabold text-emerald-600">{{ $attendanceStats['present'] }}</p>
                </div>
                <div class="px-3 border-l border-slate-200">
                    <p class="text-[10px] font-bold text-slate-400 uppercase">Late</p>
                    <p class="text-lg font-extrabold text-amber-500">{{ $attendanceStats['late'] }}</p>
                </div>
                <div class="px-3 border-l border-slate-200">
                    <p class="text-[10px] font-bold text-slate-400 uppercase">Leave</p>
                    <p class="text-lg font-extrabold text-blue-600">{{ $attendanceStats['leave'] }}</p>
                </div>
                <div class="px-3 border-l border-slate-200">
                    <p class="text-[10px] font-bold text-slate-400 uppercase">Assets</p>
                    <p class="text-lg font-extrabold text-indigo-600">{{ $employee->assets->count() }}</p>
                </div>
            </div>
        </div>

        <!-- 360 Navigation Tabs -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-1.5 flex flex-wrap gap-2">
            <button onclick="switchCrmTab('overview')" id="crm-tab-overview"
                class="crm-tab px-4 py-2 rounded-lg text-sm font-bold bg-indigo-600 text-white transition-all">
                <i class="fa-solid fa-id-card-clip mr-1.5"></i> 360° Overview
            </button>
            <button onclick="switchCrmTab('timeline')" id="crm-tab-timeline"
                class="crm-tab px-4 py-2 rounded-lg text-sm font-bold text-slate-600 hover:bg-slate-50 transition-all">
                <i class="fa-solid fa-timeline mr-1.5"></i> Career Timeline ({{ $unifiedTimeline->count() }})
            </button>
            <button onclick="switchCrmTab('assets')" id="crm-tab-assets"
                class="crm-tab px-4 py-2 rounded-lg text-sm font-bold text-slate-600 hover:bg-slate-50 transition-all">
                <i class="fa-solid fa-laptop mr-1.5"></i> Assigned Assets ({{ $employee->assets->count() }})
            </button>
            <button onclick="switchCrmTab('documents')" id="crm-tab-documents"
                class="crm-tab px-4 py-2 rounded-lg text-sm font-bold text-slate-600 hover:bg-slate-50 transition-all">
                <i class="fa-solid fa-folder-open mr-1.5"></i> Documents Vault ({{ $employee->documents->count() }})
            </button>
        </div>

        <!-- TAB 1: 360 OVERVIEW -->
        <div id="crm-content-overview" class="crm-content space-y-4">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <!-- Professional & Skills -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 space-y-4">
                    <h3 class="font-bold text-slate-800 border-b border-slate-100 pb-3 flex items-center gap-2">
                        <i class="fa-solid fa-briefcase text-indigo-600"></i> Professional Profile
                    </h3>
                    <div class="space-y-2.5 text-sm">
                        <div class="flex justify-between"><span class="text-slate-500">Employment Type</span><span
                                class="font-bold text-slate-800">{{ $employee->employment_type }}</span></div>
                        <div class="flex justify-between"><span class="text-slate-500">Assigned Shift</span><span
                                class="font-bold text-slate-800">{{ $employee->shift->shift_name ?? 'Unassigned' }}</span>
                        </div>
                        <div class="pt-2 border-t border-slate-100">
                            <p class="text-xs font-bold text-slate-400 uppercase mb-1.5">Skills & Competencies</p>
                            @if ($employee->skills)
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach (explode(',', $employee->skills) as $skill)
                                        <span
                                            class="px-2.5 py-1 bg-indigo-50 text-indigo-700 rounded-lg text-xs font-semibold">{{ trim($skill) }}</span>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-xs text-slate-400 italic">No skills listed yet.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Identity & Bank Details -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 space-y-4">
                    <h3 class="font-bold text-slate-800 border-b border-slate-100 pb-3 flex items-center gap-2">
                        <i class="fa-solid fa-building-columns text-emerald-600"></i> Identity & Bank
                    </h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between"><span class="text-slate-500">PAN</span><span
                                class="font-mono font-bold text-slate-800 uppercase">{{ $employee->pan_number ?: '--' }}</span>
                        </div>
                        <div class="flex justify-between"><span class="text-slate-500">Aadhaar</span><span
                                class="font-mono font-bold text-slate-800">{{ $employee->aadhaar_number ?: '--' }}</span>
                        </div>
                        <div class="flex justify-between"><span class="text-slate-500">PF UAN</span><span
                                class="font-mono font-bold text-slate-800">{{ $employee->pf_uan ?: '--' }}</span></div>
                    </div>
                    <div class="space-y-3 text-sm pt-3 border-t border-slate-100">
                        <div class="flex justify-between"><span class="text-slate-500">Bank Name</span><span
                                class="font-bold text-slate-800">{{ $employee->bank_name ?: '--' }}</span></div>
                        <div class="flex justify-between"><span class="text-slate-500">Account No.</span><span
                                class="font-mono font-semibold text-slate-700">{{ $employee->bank_account_no ?: '--' }}</span>
                        </div>
                        <div class="flex justify-between"><span class="text-slate-500">IFSC Code</span><span
                                class="font-mono font-semibold text-slate-700 uppercase">{{ $employee->bank_ifsc ?: '--' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Emergency Contact -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 space-y-4">
                    <h3 class="font-bold text-slate-800 border-b border-slate-100 pb-3 flex items-center gap-2">
                        <i class="fa-solid fa-truck-medical text-rose-500"></i> Emergency Contact
                    </h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between"><span class="text-slate-500">Contact Person</span><span
                                class="font-bold text-slate-800">{{ $employee->emergency_contact_name ?: 'Not Provided' }}</span>
                        </div>
                        <div class="flex justify-between"><span class="text-slate-500">Relationship</span><span
                                class="font-semibold text-slate-700">{{ $employee->emergency_contact_relation ?: '--' }}</span>
                        </div>
                        <div class="flex justify-between"><span class="text-slate-500">Emergency Phone</span><span
                                class="font-bold text-rose-600">{{ $employee->emergency_contact_phone ?: '--' }}</span>
                        </div>
                    </div>
                    <h3 class="font-bold text-slate-800 border-b border-slate-100 pb-2 pt-4 flex items-center gap-2">
                        <i class="fa-solid fa-scale-balanced text-teal-600"></i> Leave Balances
                    </h3>
                    <div class="grid grid-cols-2 gap-2">
                        @forelse($employee->leaveBalances as $bal)
                            <div
                                class="p-2.5 bg-slate-50 rounded-xl border border-slate-100 flex justify-between items-center">
                                <span class="text-xs font-semibold text-slate-600">{{ $bal->leaveType->name }}</span>
                                <span class="text-sm font-extrabold text-indigo-600">{{ (float) $bal->balance }}</span>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 col-span-2">No leave balances allocated.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                <!-- Education History List -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 space-y-4">
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
                                <p class="text-xs text-slate-400">
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

                <!-- Work Experience List -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 space-y-4">
                    <h3 class="font-bold text-slate-800 border-b border-slate-100 pb-3 flex items-center gap-2">
                        <i class="fa-solid fa-business-time text-blue-500"></i> Previous Work Experience
                    </h3>
                    <div class="space-y-4 relative border-l border-slate-200 ml-2">
                        @forelse($employee->experiences as $exp)
                            <div class="pl-4 relative">
                                <div
                                    class="absolute w-2.5 h-2.5 bg-blue-500 rounded-full -left-[5px] top-1.5 border-2 border-white">
                                </div>
                                <h4 class="font-bold text-slate-800 text-sm">{{ $exp->job_role }}</h4>
                                <p class="text-xs text-slate-600 font-semibold">{{ $exp->company_name }}</p>
                                <p class="text-xs text-slate-400">
                                    {{ $exp->from_date->format('M Y') }} -
                                    {{ $exp->to_date ? $exp->to_date->format('M Y') : 'N/A' }}
                                </p>
                                @if ($exp->description)
                                    <p class="text-[11px] text-slate-500 mt-1 italic">{{ $exp->description }}</p>
                                @endif
                            </div>
                        @empty
                            <p class="pl-4 text-xs text-slate-400 italic">No prior experience recorded.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 2: EMPLOYEE 360 TIMELINE -->
        <div id="crm-content-timeline" class="crm-content hidden grid grid-cols-1 lg:grid-cols-3 gap-4">
            <!-- Add Milestone Form -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 h-fit">
                <h3 class="font-bold text-slate-800 mb-4">Log Career Event / Note</h3>
                <form action="{{ route('admin.employees.timeline.store', $employee->id) }}" method="POST"
                    class="space-y-3 text-sm">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Event Category *</label>
                        <select name="event_type" required
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg">
                            <option value="Appraisal">Appraisal / Salary Revision</option>
                            <option value="Promotion">Promotion / Role Change</option>
                            <option value="Performance">Performance Review</option>
                            <option value="Warning">Warning / Disciplinary Notice</option>
                            <option value="Note">HR Remark / Internal Note</option>
                            <option value="Exit">Resignation / Exit</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Event Title *</label>
                        <input type="text" name="title" required placeholder="e.g. Promoted to Senior Developer"
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Event Date *</label>
                        <input type="date" name="event_date" value="{{ date('Y-m-d') }}" required
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Details / Remarks</label>
                        <textarea name="description" rows="3" placeholder="Add appraisal notes, warning reason, or feedback..."
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg"></textarea>
                    </div>
                    <button type="submit"
                        class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-sm">
                        Add to 360° Timeline
                    </button>
                </form>
            </div>
            <!-- Chronological Timeline Feed -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 p-4">
                <h3 class="font-bold text-slate-800 mb-4">Complete Employee Lifecycle Timeline</h3>
                <div class="relative border-l-2 border-indigo-100 ml-3 space-y-3">
                    @forelse($unifiedTimeline as $event)
                        <div class="relative pl-5">
                            <span
                                class="absolute -left-2.5 top-1 w-5 h-5 rounded-full bg-indigo-600 border-4 border-white shadow"></span>
                            <div
                                class="bg-slate-50 p-3 rounded-xl border border-slate-100 flex justify-between items-start gap-4">
                                <div>
                                    <span
                                        class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded bg-indigo-100 text-indigo-700">{{ $event['type'] }}</span>
                                    <span
                                        class="text-xs text-slate-400 font-semibold ml-2">{{ \Carbon\Carbon::parse($event['date'])->format('d M, Y') }}</span>
                                    <h4 class="font-bold text-slate-800 mt-1 text-sm">{{ $event['title'] }}</h4>
                                    @if ($event['description'])
                                        <p class="text-xs text-slate-600 mt-1">{{ $event['description'] }}</p>
                                    @endif
                                </div>
                                @if ($event['is_custom'])
                                    <form action="{{ route('admin.employees.timeline.destroy', $event['id']) }}"
                                        method="POST" onsubmit="return confirm('Delete this timeline entry?');">
                                        @csrf @method('DELETE')
                                        <button class="text-slate-400 hover:text-rose-600 text-xs"><i
                                                class="fa-solid fa-trash"></i></button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-400 pl-5">No timeline events recorded yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- TAB 3: ASSIGNED ASSETS -->
        <div id="crm-content-assets"
            class="crm-content hidden bg-white rounded-2xl shadow-sm border border-slate-100 p-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-slate-800">Hardware & Company Assets Allocated</h3>
                <div class="flex gap-2">
                    @if ($employee->is_active)
                        <button onclick="openAssignAssetModal()"
                            class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold shadow-sm flex items-center gap-1.5 transition-colors">
                            <i class="fa-solid fa-link"></i> Assign Asset
                        </button>
                    @else
                        <span
                            class="px-3 py-1.5 bg-rose-50 text-rose-600 border border-rose-100 rounded-lg text-xs font-bold shadow-sm flex items-center gap-1.5">
                            <i class="fa-solid fa-ban"></i> Suspended
                        </span>
                    @endif
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($employee->assets as $asset)
                    <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/50 space-y-2 relative group">
                        <div class="flex justify-between items-start">
                            <div>
                                <span
                                    class="text-[10px] font-bold uppercase px-2 py-0.5 rounded bg-indigo-100 text-indigo-700">{{ $asset->category }}</span>
                                <h4 class="font-bold text-slate-800 mt-1 pr-12">{{ $asset->asset_name }}</h4>
                            </div>
                            <form
                                action="{{ route('admin.employees.assets.return', ['employee' => $employee->id, 'asset' => $asset->id]) }}"
                                method="POST"
                                onsubmit="return confirm('Mark this asset as returned and remove from this employee?');"
                                class="absolute top-4 right-4">
                                @csrf
                                <button
                                    class="text-[10px] bg-rose-600 border border-rose-100 px-2 py-1 rounded-lg font-bold hover:bg-rose-700 text-white transition-colors shadow-sm">
                                    Return
                                </button>
                            </form>
                        </div>
                        <p class="text-xs font-mono font-bold text-slate-500">{{ $asset->asset_code }}</p>
                        <p class="text-xs text-slate-500">Serial: {{ $asset->serial_number ?: 'N/A' }} &bull; Condition:
                            <strong>{{ $asset->condition }}</strong></p>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-8">
                        <p class="text-sm font-bold text-slate-600">No assets currently assigned to this employee.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- TAB 4: DOCUMENTS VAULT -->
        <div id="crm-content-documents" class="crm-content hidden grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 h-fit">
                <h3 class="font-bold text-slate-800 mb-4">Upload Document</h3>
                <form action="{{ route('admin.employees.documents.store', $employee->id) }}" method="POST"
                    enctype="multipart/form-data" class="space-y-3 text-sm">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Document Title *</label>
                        <input type="text" name="title" required
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Document Type *</label>
                        <select name="document_type" required
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg">
                            <option value="Offer Letter">Offer Letter</option>
                            <option value="Appointment Letter">Appointment Letter</option>
                            <option value="KYC / ID Proof">KYC / ID Proof</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">File *</label>
                        <input type="file" name="document_file" required
                            class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-indigo-50 file:text-indigo-700 file:font-semibold">
                    </div>
                    <button type="submit"
                        class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-sm">
                        Upload
                    </button>
                </form>
            </div>
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 p-4">
                <h3 class="font-bold text-slate-800 mb-4">Stored HR Documents</h3>
                <div class="divide-y divide-slate-100">
                    @forelse($employee->documents as $doc)
                        <div class="py-3 flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                    <i class="fa-solid fa-file-contract"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800 text-sm">{{ $doc->title }}</p>
                                    <p class="text-xs text-slate-500">{{ $doc->document_type }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ asset($doc->file_path) }}" target="_blank"
                                    class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold">View</a>
                                <form action="{{ route('admin.employees.documents.destroy', $doc->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="p-1.5 text-rose-500 hover:bg-rose-50 rounded-lg text-xs"><i
                                            class="fa-solid fa-trash"></i></button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-400 py-6 text-center">No documents uploaded.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    @if ($employee->profile_photo)
        <!-- Photo Lightbox Modal -->
        <div id="photoLightbox" onclick="closePhotoLightbox()"
            class="fixed inset-0 z-[100] hidden bg-slate-900/90 backdrop-blur-sm flex items-center justify-center opacity-0 transition-opacity duration-300 cursor-zoom-out">
            <img id="lightbox-img" src="{{ asset($employee->profile_photo) }}"
                class="max-w-[90%] max-h-[90vh] rounded-xl shadow-2xl transform scale-95 transition-transform duration-300 object-contain">
            <button class="absolute top-6 right-6 text-white hover:text-slate-300 text-3xl"><i
                    class="fa-solid fa-xmark"></i></button>
        </div>
    @endif

    <!-- Assign Asset Modal -->
    <div id="assignAssetModal"
        class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm overflow-y-auto w-full h-full flex items-center justify-center opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md transform scale-95 transition-transform duration-300 m-4"
            id="assignAssetContent">
            <div class="flex items-center justify-between p-4 border-b border-slate-100 bg-slate-50/50 rounded-t-2xl">
                <h3 class="text-lg font-bold text-slate-800">Assign Available Asset</h3>
                <button onclick="closeAssignAssetModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            <form action="{{ route('admin.employees.assets.assign', $employee->id) }}" method="POST"
                class="p-4 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Select from Inventory</label>
                    <select name="asset_id" required
                        class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 text-sm">
                        <option value="">-- Choose Available Asset --</option>
                        @foreach ($availableAssets as $availAsset)
                            <option value="{{ $availAsset->id }}">{{ $availAsset->asset_code }} &bull;
                                {{ $availAsset->asset_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mt-6 flex justify-end gap-3 border-t border-slate-100 pt-4">
                    <button type="button" onclick="closeAssignAssetModal()"
                        class="px-4 py-2 text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg font-medium transition-colors text-sm">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-colors shadow-sm text-sm"
                        {{ $availableAssets->isEmpty() ? 'disabled' : '' }}>Assign to Employee</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function switchCrmTab(tab) {
            document.querySelectorAll('.crm-content').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.crm-tab').forEach(el => {
                el.className =
                    'crm-tab px-4 py-2 rounded-lg text-sm font-bold text-slate-600 hover:bg-slate-50 transition-all';
            });
            document.getElementById('crm-content-' + tab).classList.remove('hidden');
            document.getElementById('crm-tab-' + tab).className =
                'crm-tab px-4 py-2 rounded-lg text-sm font-bold bg-indigo-600 text-white transition-all';
        }

        const assignModal = document.getElementById('assignAssetModal');
        const assignContent = document.getElementById('assignAssetContent');

        function openAssignAssetModal() {
            assignModal.classList.remove('hidden');
            setTimeout(() => {
                assignModal.classList.remove('opacity-0');
                assignContent.classList.remove('scale-95');
            }, 10);
        }

        function closeAssignAssetModal() {
            assignModal.classList.add('opacity-0');
            assignContent.classList.add('scale-95');
            setTimeout(() => {
                assignModal.classList.add('hidden');
            }, 300);
        }

        function openPhotoLightbox() {
            const lbox = document.getElementById('photoLightbox');
            const img = document.getElementById('lightbox-img');
            if (!lbox) return;
            lbox.classList.remove('hidden');
            setTimeout(() => {
                lbox.classList.remove('opacity-0');
                img.classList.remove('scale-95');
            }, 10);
        }

        function closePhotoLightbox() {
            const lbox = document.getElementById('photoLightbox');
            const img = document.getElementById('lightbox-img');
            if (!lbox) return;
            lbox.classList.add('opacity-0');
            img.classList.add('scale-95');
            setTimeout(() => lbox.classList.add('hidden'), 300);
        }
    </script>
@endsection