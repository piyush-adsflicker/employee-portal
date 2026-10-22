@extends('layouts.admin')

@section('title', 'Designations')
@section('header_title', 'Designation Management')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">

    <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
        <div>
            <h3 class="text-lg font-bold text-slate-800">Job Designations</h3>
            <p class="text-sm text-slate-500 mt-1">Manage company roles and job titles.</p>
        </div>
        <button onclick="openModal('add')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center gap-2 shadow-sm">
            <i class="fa-solid fa-plus"></i>
            Add Designation
        </button>
    </div>

    @if($errors->any())
    <div class="p-4 bg-red-50 text-red-600 border-b border-red-100 text-sm">
        <ul class="list-disc pl-5">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-200">
                    <th class="px-2 sm:px-3 lg:px-5 py-2 sm:py-3 font-semibold">Designation ID</th>
                    <th class="px-2 sm:px-3 lg:px-5 py-2 sm:py-3 font-semibold">Title</th>
                    <th class="px-2 sm:px-3 lg:px-5 py-2 sm:py-3 font-semibold text-center">Status</th>
                    <th class="px-2 sm:px-3 lg:px-5 py-2 sm:py-3 font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @forelse($designations as $desig)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-2 sm:px-3 lg:px-5 py-2 sm:py-3 font-medium text-slate-800">{{ $desig->designation_id }}</td>
                    <td class="px-2 sm:px-3 lg:px-5 py-2 sm:py-3 text-slate-600">{{ $desig->designation_name }}</td>

                    <!-- Async Status Toggle -->
                    <td class="px-2 sm:px-3 lg:px-5 py-2 sm:py-3 text-center">
                        <button onclick="toggleStatus({{ $desig->id }}, this)"
                            class="relative inline-flex h-5 w-10 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2 {{ $desig->is_active ? 'bg-emerald-500' : 'bg-slate-300' }}"
                            role="switch"
                            aria-checked="{{ $desig->is_active ? 'true' : 'false' }}">
                            <span class="sr-only">Toggle status</span>
                            <span class="pointer-events-none relative inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $desig->is_active ? 'translate-x-5' : 'translate-x-0' }}">
                            </span>
                        </button>
                        <div class="text-[9px] uppercase font-bold tracking-wider status-text {{ $desig->is_active ? 'text-emerald-600' : 'text-slate-400' }}">
                            {{ $desig->is_active ? 'Active' : 'Inactive' }}
                        </div>
                    </td>

                    <td class="px-2 sm:px-3 lg:px-5 py-2 sm:py-3 text-right">
                        <button onclick="openModal('edit', {{ $desig }})" class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition-colors inline-flex items-center justify-center" title="Edit">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-slate-500">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fa-solid fa-id-badge text-4xl text-slate-300 mb-3"></i>
                            <p>No designations found. Create one to get started.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Add/Edit Designation Modal -->
<div id="designationModal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm overflow-y-auto w-full h-full flex items-center justify-center opacity-0 transition-opacity duration-300">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md transform scale-95 transition-transform duration-300" id="modalContent">

        <div class="flex items-center justify-between p-6 border-b border-slate-100">
            <h3 id="modalTitle" class="text-lg font-bold text-slate-800">Add Designation</h3>
            <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        <form id="designationForm" method="POST" action="{{ route('admin.designations.store') }}" class="p-6">
            @csrf
            <div id="methodContainer"></div>

            <div class="space-y-4">
            
                <div>
                    <label class="block text-slate-700 text-sm font-semibold mb-2" for="designation_id">Designation ID</label>
                    <input type="text" name="designation_id" id="designation_id" required placeholder="e.g. DEV-01"
                        class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                </div>

                <div>
                    <label class="block text-slate-700 text-sm font-semibold mb-2" for="name">Designation Title</label>
                    <input type="text" name="designation_name" id="designation_name" required placeholder="e.g. Software Developer"
                        class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                </div>

            </div>

            <div class="mt-8 flex justify-end gap-3">
                <button type="button" onclick="closeModal()" class="px-4 py-2 text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg font-medium transition-colors">
                    Cancel
                </button>
                <button type="submit" id="submitBtn" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-colors shadow-sm">
                    Save Designation
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal & Async Logic -->
<script>
    const modal = document.getElementById('designationModal');
    const modalContent = document.getElementById('modalContent');
    const form = document.getElementById('designationForm');
    const modalTitle = document.getElementById('modalTitle');
    const methodContainer = document.getElementById('methodContainer');
    const submitBtn = document.getElementById('submitBtn');
    const statusNotice = document.getElementById('statusNotice');

    function openModal(mode, data = null) {
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modalContent.classList.remove('scale-95');
        }, 10);

        if (mode === 'add') {
            modalTitle.textContent = 'Add Designation';
            submitBtn.textContent = 'Save Designation';
            form.action = "{{ route('admin.designations.store') }}";
            methodContainer.innerHTML = '';

            document.getElementById('designation_id').value = '';
            document.getElementById('designation_name').value = '';
        } else if (mode === 'edit') {
            modalTitle.textContent = 'Edit Designation';
            submitBtn.textContent = 'Update Designation';
            form.action = `/admin/designations/${data.id}`;
            methodContainer.innerHTML = '<input type="hidden" name="_method" value="PUT">';

            document.getElementById('designation_id').value = data.designation_id;
            document.getElementById('designation_name').value = data.designation_name;
        }
    }

    function closeModal() {
        modal.classList.add('opacity-0');
        modalContent.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    modal.addEventListener('click', function(e) {
        if (e.target === modal) closeModal();
    });

    // Async Status Toggle Function
    function toggleStatus(id, btnElement) {
        Swal.fire({
            title: 'Change Status?',
            text: "Are you sure you want to change the active status of this designation?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4f46e5',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Yes, change it!',
            customClass: {
                popup: 'rounded-2xl'
            }
        }).then((result) => {
            if (result.isConfirmed) {

                fetch(`/admin/designations/${id}/toggle-status`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Visually update the toggle switch without reloading the page
                            const circle = btnElement.querySelector('span:nth-child(2)');
                            const textLabel = btnElement.nextElementSibling;

                            if (data.is_active) {
                                btnElement.classList.replace('bg-slate-300', 'bg-emerald-500');
                                circle.classList.replace('translate-x-0', 'translate-x-5');
                                textLabel.textContent = 'ACTIVE';
                                textLabel.classList.replace('text-slate-400', 'text-emerald-600');
                            } else {
                                btnElement.classList.replace('bg-emerald-500', 'bg-slate-300');
                                circle.classList.replace('translate-x-5', 'translate-x-0');
                                textLabel.textContent = 'INACTIVE';
                                textLabel.classList.replace('text-emerald-600', 'text-slate-400');
                            }

                            Swal.fire({
                                title: 'Updated!',
                                text: data.message,
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false,
                                customClass: {
                                    popup: 'rounded-2xl'
                                }
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire('Error!', 'Something went wrong while updating.', 'error');
                    });
            }
        });
    }
</script>
@endsection