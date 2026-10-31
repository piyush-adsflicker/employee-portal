<?php
namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\OrganizationFile;
use Illuminate\Support\Facades\Auth;

class CompanyFileController extends Controller
{
    public function index()
    {
        $employee = Auth::user();

        // 1. Organization-wide files (Global)
        $globalFiles = OrganizationFile::where('is_active', true)
            ->where('visibility_type', 'Global')
            ->latest()
            ->get();

        // 2. Files shared specifically with this employee's Role or Department
        $restrictedFiles = OrganizationFile::where('is_active', true)
            ->where('visibility_type', 'Restricted')
            ->where(function ($query) use ($employee) {
                $query->whereHas('departments', function ($q) use ($employee) {
                    $q->where('department_id', $employee->department_id);
                })
                    ->orWhereHas('designations', function ($q) use ($employee) {
                        $q->where('designation_id', $employee->designation_id);
                    });
            })
            ->latest()
            ->get();

        return view('employee.company-files', compact('globalFiles', 'restrictedFiles'));
    }
}
