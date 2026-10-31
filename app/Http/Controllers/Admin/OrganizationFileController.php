<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Designation;
use App\Models\OrganizationFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class OrganizationFileController extends Controller
{
    public function index()
    {
        $files        = OrganizationFile::with(['departments', 'designations'])->latest()->get();
        $departments  = Department::where('is_active', true)->get();
        $designations = Designation::where('is_active', true)->get();

        return view('admin.organization-files', compact('files', 'departments', 'designations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'           => 'required|string|max:255',
            'category'        => 'required|string|max:100',
            'document_file'   => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:10240',
            'visibility_type' => 'required|in:Global,Restricted',
            'department_ids'  => 'nullable|array',
            'designation_ids' => 'nullable|array',
            'description'     => 'nullable|string|max:1000',
        ]);

        DB::transaction(function () use ($request) {
            $uploadPath = public_path('uploads/organization');
            if (! File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            $file     = $request->file('document_file');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadPath, $filename);

            $orgFile = OrganizationFile::create([
                'title'           => $request->title,
                'category'        => $request->category,
                'description'     => $request->description,
                'file_path'       => 'uploads/organization/' . $filename,
                'visibility_type' => $request->visibility_type,
            ]);

            if ($request->visibility_type === 'Restricted') {
                $orgFile->departments()->sync($request->department_ids ?? []);
                $orgFile->designations()->sync($request->designation_ids ?? []);
            }
        });

        return back()->with('success', 'Company file uploaded successfully.');
    }

    public function update(Request $request, OrganizationFile $organizationFile)
    {
        $request->validate([
            'title'           => 'required|string|max:255',
            'category'        => 'required|string|max:100',
            'document_file'   => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:10240', // Optional on edit
            'visibility_type' => 'required|in:Global,Restricted',
            'department_ids'  => 'nullable|array',
            'designation_ids' => 'nullable|array',
            'description'     => 'nullable|string|max:1000',
        ]);

        DB::transaction(function () use ($request, $organizationFile) {
            $data = [
                'title'           => $request->title,
                'category'        => $request->category,
                'description'     => $request->description,
                'visibility_type' => $request->visibility_type,
            ];

            if ($request->hasFile('document_file')) {
                // Delete old file
                if (File::exists(public_path($organizationFile->file_path))) {
                    File::delete(public_path($organizationFile->file_path));
                }

                // Upload new file
                $file     = $request->file('document_file');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/organization'), $filename);
                $data['file_path'] = 'uploads/organization/' . $filename;
            }

            $organizationFile->update($data);

            // Sync restricted access
            if ($request->visibility_type === 'Restricted') {
                $organizationFile->departments()->sync($request->department_ids ?? []);
                $organizationFile->designations()->sync($request->designation_ids ?? []);
            } else {
                $organizationFile->departments()->detach();
                $organizationFile->designations()->detach();
            }
        });

        return back()->with('success', 'Company file settings updated successfully.');
    }

    public function destroy(OrganizationFile $organizationFile)
    {
        if (File::exists(public_path($organizationFile->file_path))) {
            File::delete(public_path($organizationFile->file_path));
        }
        $organizationFile->delete();

        return back()->with('success', 'File removed from the organization.');
    }
}