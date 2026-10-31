<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Department;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::with(['patient', 'doctor.department']);

        if ($request->filled('date')) {
            $query->whereDate('appointment_date', $request->date);
        }

        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $appointments = $query->orderBy('appointment_date', 'desc')
            ->orderBy('time_slot', 'asc')
            ->paginate(15)
            ->withQueryString();

        $doctors = Doctor::where('status', 'active')->get();

        return view('appointments.index', compact('appointments', 'doctors'));
    }

    public function create(Request $request)
    {
        $patients = Patient::orderBy('first_name')->get();
        $doctors = Doctor::with('department')->where('status', 'active')->get();
        $selectedPatientId = $request->query('patient_id');

        return view('appointments.create', compact('patients', 'doctors', 'selectedPatientId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date',
            'time_slot' => 'required|string',
            'priority' => 'required|in:normal,urgent,emergency',
            'problem_description' => 'nullable|string',
        ]);

        $doctor = Doctor::findOrFail($validated['doctor_id']);
        $validated['department_id'] = $doctor->department_id;
        $validated['status'] = 'scheduled';

        // Auto Appointment Number
        $count = Appointment::whereDate('appointment_date', $validated['appointment_date'])->count();
        $validated['appointment_number'] = 'APT-' . date('Ymd', strtotime($validated['appointment_date'])) . '-' . str_pad($count + 1, 3, '0', STR_PAD_LEFT);

        $appointment = Appointment::create($validated);

        return redirect()->route('appointments.index')
            ->with('success', "Appointment {$appointment->appointment_number} booked successfully!");
    }

    public function updateStatus(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        $status = $request->validate([
            'status' => 'required|in:scheduled,confirmed,completed,cancelled,no_show',
        ])['status'];

        $appointment->update(['status' => $status]);

        return back()->with('success', "Appointment status updated to " . ucfirst($status));
    }

    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment deleted.');
    }
}
