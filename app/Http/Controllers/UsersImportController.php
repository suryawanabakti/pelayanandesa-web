<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use App\Exports\UsersExportTemplate;
use App\Imports\UsersImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class UsersImportController extends Controller
{
    public function export()
    {
        return Excel::download(new UsersExport(), 'user_export.xlsx');
    }

    public function template()
    {
        return Excel::download(new UsersExportTemplate(), 'user_import_template.xlsx');
    }


    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:10240', // Max 10MB
        ]);

        $import = new UsersImport();
        Excel::import($import, $request->file('file'));
    }
}
