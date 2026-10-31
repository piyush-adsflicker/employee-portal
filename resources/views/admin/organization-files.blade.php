@extends('layouts.admin')
@section('title', 'Company Files')
@section('header_title', 'Organization Files & Documents')

@section('content')
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-x-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Company Files</h3>
                <p class="text-sm text-slate-500 mt-1">Upload and manage policies, handbooks, and announcements.</p>
            </div>
            <button onclick="openModal('add')"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-cloud-arrow-up"></i> Upload File
            </button>
        </div>

        @if ($errors->any())
            <div class="p-4 bg-red-50 text-red-600 border-b border-red-100 text-sm">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-200">
                        <th class="px-6 py-4 font-semibold">Document Details</th>
                        <th class="px-6 py-4 font-semibold">Category</th>
                        <th class="px-6 py-4 font-semibold">Visibility Restrictions</th>
                        <th class="px-6 py-4 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($files as $file)
                        <tr class="hover:bg-slate-50/50 transition-colors align-top">
                            <td class="px-6 py-4">
                                <div class="flex items-start gap-3">
                                    <div
                                        class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                                        <i class="fa-solid fa-file-pdf text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800">{{ $file->title }}</p>
                                        @if ($file->description)
                                            <p class="text-xs text-slate-500 mt-1 max-w-xs line-clamp-2"
                                                title="{{ $file->description }}">{{ $file->description }}</p>
                                        @endif
                                        <p class="text-[10px] text-slate-400 mt-1 font-semibold">Uploaded:
                                            {{ $file->created_at->format('M d, Y') }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2.5 py-1 bg-slate-100 text-slate-600 rounded text-xs font-bold">{{ $file->category }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if ($file->visibility_type === 'Global')
                                    <span
                                        class="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-full text-[10px] font-bold uppercase"><i
                                            class="fa-solid fa-globe mr-1"></i> Global (All)</span>
                                @else
                                    <span
                                        class="px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-200 rounded-full text-[10px] font-bold uppercase mb-2 inline-block"><i
                                            class="fa-solid fa-lock mr-1"></i> Restricted Access</span>

                                    @if ($file->departments->count() > 0)
                                        <div class="text-[10px] text-slate-500 font-semibold max-w-xs leading-tight mb-1">
                                            <span class="text-indigo-500"><i class="fa-solid fa-building"></i> Depts:</span>
                                            {{ $file->departments->pluck('dept_name')->join(', ') }}
                                        </div>
                                    @endif

                                    @if ($file->designations->count() > 0)
                                        <div class="text-[10px] text-slate-500 font-semibold max-w-xs leading-tight">
                                            <span class="text-indigo-500"><i class="fa-solid fa-id-badge"></i> Roles:</span>
                                            {{ $file->designations->pluck('designation_name')->join(', ') }}
                                        </div>
                                    @endif
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-1 whitespace-nowrap">
                                <a href="{{ asset($file->file_path) }}" target="_blank"
                                    class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors inline-flex items-center justify-center"
                                    title="View/Download">
                                    <i class="fa-solid fa-download"></i>
                                </a>
                                <!-- The Edit Button dynamically passes the file data AND the arrays of pivot IDs -->
                                <button
                                    onclick='openModal("edit", @json($file), @json($file->departments->pluck('id')), @json($file->designations->pluck('id')))'
                                    class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition-colors inline-flex items-center justify-center"
                                    title="Edit Properties">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <form action="{{ route('admin.organization.files.destroy', $file->id) }}" method="POST"
                                    class="inline-block"
                                    onsubmit="return confirm('Are you sure you want to delete this file from the organization?');">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 transition-colors inline-flex items-center justify-center"
                                        title="Delete">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fa-solid fa-folder-open text-4xl text-slate-300 mb-3"></i>
                                    <p class="text-base font-medium">No files have been shared with the organization yet.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Upload/Edit Modal -->
    <div id="fileModal"
        class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm overflow-y-auto w-full h-full flex items-center justify-center opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl transform scale-95 transition-transform duration-300 m-4"
            id="modalContent">
            <div class="flex items-center justify-between p-6 border-b border-slate-100 bg-slate-50/50 rounded-t-2xl">
                <h3 id="modalTitle" class="text-lg font-bold text-slate-800">Share Company File</h3>
                <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>

            <form id="fileForm" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                @csrf
                <div id="methodContainer"></div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="block text-slate-700 text-sm font-semibold mb-2">Document Title <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="title" id="title" required
                            placeholder="e.g. Holidays List 2026"
                            class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-slate-700 text-sm font-semibold mb-2">Category <span
                                class="text-red-500">*</span></label>
                        <select name="category" id="category" required
                            class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 text-sm">
                            <option value="Policy">Policy</option>
                            <option value="Handbook">Handbook</option>
                            <option value="Announcement">Announcement</option>
                            <option value="Form/Template">Form / Template</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-slate-700 text-sm font-semibold mb-2">File (PDF, DOCX) <span
                                id="fileRequiredStar" class="text-red-500">*</span></label>
                        <input type="file" name="document_file" id="document_file"
                            class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-indigo-50 file:text-indigo-700 file:font-semibold hover:file:bg-indigo-100">
                        <p id="fileHelpText" class="text-[10px] text-slate-400 mt-1 hidden">Leave blank to keep the
                            existing file.</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-slate-700 text-sm font-semibold mb-2">Description / Notes</label>
                        <textarea name="description" id="description" rows="2"
                            class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm resize-none"
                            placeholder="Brief context about this file..."></textarea>
                    </div>
                </div>

                <!-- Access Control Rules -->
                <div class="pt-4 border-t border-slate-100">
                    <label class="block text-slate-700 text-sm font-bold mb-3"><i
                            class="fa-solid fa-lock text-indigo-600 mr-1"></i> Access & Visibility Rules</label>

                    <div class="flex flex-col sm:flex-row gap-4 mb-4">
                        <label
                            class="flex items-center gap-2 cursor-pointer p-3 border border-slate-200 rounded-lg hover:bg-slate-50 flex-1">
                            <input type="radio" name="visibility_type" id="vis_global" value="Global" checked
                                onchange="toggleAccessRules()" class="w-4 h-4 text-indigo-600">
                            <span class="text-sm font-bold text-slate-700">Global (All Employees)</span>
                        </label>
                        <label
                            class="flex items-center gap-2 cursor-pointer p-3 border border-slate-200 rounded-lg hover:bg-slate-50 flex-1">
                            <input type="radio" name="visibility_type" id="vis_restricted" value="Restricted"
                                onchange="toggleAccessRules()" class="w-4 h-4 text-indigo-600">
                            <span class="text-sm font-bold text-slate-700">Restricted Access</span>
                        </label>
                    </div>

                    <!-- Hidden Combinable Checkboxes -->
                    <div id="restricted-rules" class="hidden space-y-4">
                        <div class="bg-slate-50 p-4 rounded-lg border border-slate-200">
                            <p class="text-xs font-bold text-slate-500 mb-3 uppercase tracking-wider">Visible to
                                Departments:</p>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 max-h-32 overflow-y-auto">
                                @foreach ($departments as $dept)
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="department_ids[]" value="{{ $dept->id }}"
                                            class="dept-checkbox text-indigo-600 rounded border-slate-300">
                                        <span class="text-sm text-slate-600">{{ $dept->dept_name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="bg-slate-50 p-4 rounded-lg border border-slate-200">
                            <p class="text-xs font-bold text-slate-500 mb-3 uppercase tracking-wider">Visible to Roles:</p>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 max-h-32 overflow-y-auto">
                                @foreach ($designations as $desig)
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="designation_ids[]" value="{{ $desig->id }}"
                                            class="desig-checkbox text-indigo-600 rounded border-slate-300">
                                        <span class="text-sm text-slate-600">{{ $desig->designation_name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" onclick="closeModal()"
                        class="px-5 py-2 text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg font-semibold transition-colors text-sm">Cancel</button>
                    <button type="submit" id="submitBtn"
                        class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-semibold transition-colors shadow-sm flex items-center gap-2 text-sm">
                        <i class="fa-solid fa-cloud-arrow-up"></i> Upload File
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('fileModal');
        const modalContent = document.getElementById('modalContent');
        const restrictedRules = document.getElementById('restricted-rules');

        const form = document.getElementById('fileForm');
        const methodContainer = document.getElementById('methodContainer');
        const modalTitle = document.getElementById('modalTitle');
        const submitBtn = document.getElementById('submitBtn');

        function openModal(mode, data = null, deptIds = [], desigIds = []) {
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modalContent.classList.remove('scale-95');
            }, 10);

            // Reset all checkboxes first
            document.querySelectorAll('.dept-checkbox').forEach(cb => cb.checked = false);
            document.querySelectorAll('.desig-checkbox').forEach(cb => cb.checked = false);

            if (mode === 'add') {
                modalTitle.textContent = 'Share Company File';
                submitBtn.innerHTML = '<i class="fa-solid fa-cloud-arrow-up"></i> Upload File';
                form.action = "{{ route('admin.organization.files.store') }}";
                methodContainer.innerHTML = '';

                document.getElementById('title').value = '';
                document.getElementById('category').value = 'Policy';
                document.getElementById('description').value = '';
                document.getElementById('document_file').required = true;
                document.getElementById('fileRequiredStar').classList.remove('hidden');
                document.getElementById('fileHelpText').classList.add('hidden');

                document.getElementById('vis_global').checked = true;
                toggleAccessRules();

            } else if (mode === 'edit') {
                modalTitle.textContent = 'Edit File Settings';
                submitBtn.innerHTML = '<i class="fa-solid fa-save"></i> Save Changes';
                form.action = `/admin/organization-files/${data.id}`;
                methodContainer.innerHTML = '<input type="hidden" name="_method" value="PUT">';

                document.getElementById('title').value = data.title;
                document.getElementById('category').value = data.category;
                document.getElementById('description').value = data.description || '';

                // File is optional on edit
                document.getElementById('document_file').required = false;
                document.getElementById('fileRequiredStar').classList.add('hidden');
                document.getElementById('fileHelpText').classList.remove('hidden');

                // Apply Visibility Logic
                if (data.visibility_type === 'Restricted') {
                    document.getElementById('vis_restricted').checked = true;

                    // Re-check saved Departments
                    document.querySelectorAll('.dept-checkbox').forEach(cb => {
                        if (deptIds.includes(parseInt(cb.value))) cb.checked = true;
                    });
                    // Re-check saved Designations
                    document.querySelectorAll('.desig-checkbox').forEach(cb => {
                        if (desigIds.includes(parseInt(cb.value))) cb.checked = true;
                    });
                } else {
                    document.getElementById('vis_global').checked = true;
                }
                toggleAccessRules();
            }
        }

        function closeModal() {
            modal.classList.add('opacity-0');
            modalContent.classList.add('scale-95');
            setTimeout(() => modal.classList.add('hidden'), 300);
        }

        function toggleAccessRules() {
            const isRestricted = document.getElementById('vis_restricted').checked;
            if (isRestricted) {
                restrictedRules.classList.remove('hidden');
            } else {
                restrictedRules.classList.add('hidden');
            }
        }
    </script>
@endsection
