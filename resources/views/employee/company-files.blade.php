@extends('layouts.employee')
@section('title', 'Company Files')
@section('header_title', 'Organization Documents')

@section('content')
    <div class="max-w-7xl mx-auto space-y-5 pb-12">
        <div class="mb-2">
            <h2 class="text-2xl font-bold text-slate-800">Company Files & Policies</h2>
            <p class="text-sm text-slate-500 mt-1">Access important documents, handbooks, and files shared with you.</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-1.5 flex flex-wrap gap-2">
            <button onclick="switchFileTab('global')" id="tab-global"
                class="file-tab px-5 py-2 rounded-lg text-sm font-bold bg-teal-600 text-white shadow-md shadow-teal-500/20 transition-all flex items-center gap-2">
                <i class="fa-solid fa-globe"></i> Organization ({{ $globalFiles->count() }})
            </button>
            <button onclick="switchFileTab('restricted')" id="tab-restricted"
                class="file-tab px-5 py-2 rounded-lg text-sm font-bold text-slate-600 hover:bg-slate-50 transition-all flex items-center gap-2">
                <i class="fa-solid fa-users"></i> Role & Department ({{ $restrictedFiles->count() }})
            </button>
        </div>

        <!-- TAB 1: Organization (Global) Files -->
        <div id="content-global" class="file-content">
            @if ($globalFiles->isEmpty())
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-12 text-center mt-2">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-folder-open text-3xl text-slate-300"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-700">No Organization Files</h3>
                    <p class="text-slate-500 mt-1 text-sm">There are currently no company-wide documents available.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mt-2">
                    @foreach ($globalFiles as $file)
                        <div
                            class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col transition-all hover:shadow-md hover:-translate-y-1">
                            <div class="p-5 flex-1">
                                <div class="flex justify-between items-start mb-4">
                                    <div
                                        class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center shrink-0">
                                        <i class="fa-solid fa-file-pdf text-2xl"></i>
                                    </div>
                                    <span
                                        class="px-2.5 py-1 bg-slate-100 text-slate-600 rounded text-[10px] font-bold uppercase tracking-wider">
                                        {{ $file->category }}
                                    </span>
                                </div>
                                <h4 class="font-bold text-slate-800 text-lg leading-tight mb-2">{{ $file->title }}</h4>
                                @if ($file->description)
                                    <p class="text-sm text-slate-500 line-clamp-3 mb-4">{{ $file->description }}</p>
                                @endif
                            </div>
                            <div
                                class="px-5 py-4 border-t border-slate-50 bg-slate-50/50 flex items-center justify-between">
                                <div class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">
                                    <i class="fa-solid fa-calendar-day mr-1"></i> {{ $file->created_at->format('M d, Y') }}
                                </div>
                                <a href="{{ asset($file->file_path) }}" target="_blank"
                                    class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg text-xs font-bold transition-colors shadow-sm flex items-center gap-1.5">
                                    <i class="fa-solid fa-cloud-arrow-down"></i> View
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- TAB 2: Restricted (Role/Department) Files -->
        <div id="content-restricted" class="file-content hidden">
            @if ($restrictedFiles->isEmpty())
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-12 text-center mt-2">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-lock text-3xl text-slate-300"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-700">No Restricted Files</h3>
                    <p class="text-slate-500 mt-1 text-sm">There are currently no files specifically shared with your
                        department or designation.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mt-2">
                    @foreach ($restrictedFiles as $file)
                        <div
                            class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col transition-all hover:shadow-md hover:-translate-y-1 relative">
                            <div
                                class="absolute top-0 right-0 bg-indigo-500 text-white text-[9px] font-bold px-3 py-1 rounded-bl-lg shadow-sm z-10 flex items-center gap-1">
                                <i class="fa-solid fa-shield-halved"></i> CONFIDENTIAL
                            </div>

                            <div class="p-5 flex-1 mt-2">
                                <div class="flex justify-between items-start mb-4">
                                    <div
                                        class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                                        <i class="fa-solid fa-file-pdf text-2xl"></i>
                                    </div>
                                    <span
                                        class="px-2.5 py-1 bg-slate-100 text-slate-600 rounded text-[10px] font-bold uppercase tracking-wider">
                                        {{ $file->category }}
                                    </span>
                                </div>
                                <h4 class="font-bold text-slate-800 text-lg leading-tight mb-2">{{ $file->title }}</h4>
                                @if ($file->description)
                                    <p class="text-sm text-slate-500 line-clamp-3 mb-4">{{ $file->description }}</p>
                                @endif
                            </div>
                            <div
                                class="px-5 py-4 border-t border-slate-50 bg-slate-50/50 flex items-center justify-between">
                                <div class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">
                                    <i class="fa-solid fa-calendar-day mr-1"></i> {{ $file->created_at->format('M d, Y') }}
                                </div>
                                <a href="{{ asset($file->file_path) }}" target="_blank"
                                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold transition-colors shadow-sm flex items-center gap-1.5">
                                    <i class="fa-solid fa-cloud-arrow-down"></i> View
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <script>
        function switchFileTab(tab) {
            document.querySelectorAll('.file-content').forEach(el => el.classList.add('hidden'));

            document.querySelectorAll('.file-tab').forEach(el => {
                el.className =
                    'file-tab px-5 py-2 rounded-lg text-sm font-bold text-slate-600 hover:bg-slate-50 transition-all flex items-center gap-2';
            });

            document.getElementById('content-' + tab).classList.remove('hidden');

            const activeTab = document.getElementById('tab-' + tab);
            activeTab.className =
                'file-tab px-5 py-2 rounded-lg text-sm font-bold bg-teal-600 text-white shadow-md shadow-teal-500/20 transition-all flex items-center gap-2';
        }
    </script>
@endsection