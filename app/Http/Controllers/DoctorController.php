<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $query = Doctor::with('department', 'user');

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('specialization', 'like', "%{$search}%");
            });
        }

        $doctors = $query->orderBy('first_name')->paginate(12)->withQueryString();
        $departments = Department::all();

        return view('doctors.index', compact('doctors', 'departments'));
    }

    public function create()
    {
        $departments = Department::all();
        return view('doctors.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'phone' => 'required|string|max:20',
            'department_id' => 'required|exists:departments,id',
            'specialization' => 'required|string|max:150',
            'qualification' => 'required|string|max:150',
            'license_number' => 'required|string|unique:doctors,license_number',
            'consultation_fee' => 'required|numeric|min:0',
            'room_no' => 'nullable|string|max:50',
            'bio' => 'nullable|string',
        ]);

        // Create User account
        $user = User::create([
            'name' => 'Dr. ' . $validated['first_name'] . ' ' . $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'doctor',
            'phone' => $validated['phone'],
            'status' => 'active',
        ]);

        // Create Doctor Profile
        $validated['user_id'] = $user->id;
        $validated['status'] = 'active';

        Doctor::create($validated);

        return redirect()->route('doctors.index')
            ->with('success', "Doctor Dr. {$validated['first_name']} {$validated['last_name']} added successfully!");
    }

    public function show($id)
    {
        $doctor = Doctor::with([
            'department',
            'user',
            'appointments.patient',
            'encounters.patient'
        ])->findOrFail($id);

        return view('doctors.show', compact('doctor'));
    }

    public function edit($id)
    {
        $doctor = Doctor::findOrFail($id);
        $departments = Department::all();
        return view('doctors.edit', compact('doctor', 'departments'));
    }

    public function update(Request $request, $id)
    {
        $doctor = Doctor::findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'department_id' => 'required|exists:departments,id',
            'specialization' => 'required|string|max:150',
            'qualification' => 'required|string|max:150',
            'consultation_fee' => 'required|numeric|min:0',
            'room_no' => 'nullable|string|max:50',
            'status' => 'required|in:active,inactive',
            'bio' => 'nullable|string',
        ]);

        $doctor->update($validated);

        return redirect()->route('doctors.show', $doctor->id)
            ->with('success', 'Doctor profile updated successfully.');
    }

    public function destroy($id)
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->delete();

        return redirect()->route('doctors.index')
            ->with('success', 'Doctor removed.');
    }
}
