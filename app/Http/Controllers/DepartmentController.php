<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::withCount('doctors')->get();
        return view('departments.index', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:departments,name|max:100',
            'description' => 'nullable|string',
            'head_of_department' => 'nullable|string|max:100',
        ]);

        Department::create($validated);

        return redirect()->route('departments.index')
            ->with('success', "Department '{$validated['name']}' created successfully.");
    }
}
