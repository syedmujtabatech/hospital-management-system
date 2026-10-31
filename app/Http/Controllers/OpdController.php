<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Encounter;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\Medicine;
use App\Models\Bill;
use App\Models\BillItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OpdController extends Controller
{
    public function index(Request $request)
    {
        $query = Encounter::with(['patient', 'doctor.department', 'prescription.items']);

        if ($request->filled('date')) {
            $query->whereDate('encounter_date', $request->date);
        }

        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        $encounters = $query->orderByDesc('encounter_date')->paginate(15)->withQueryString();
        $doctors = Doctor::where('status', 'active')->get();

        return view('opd.index', compact('encounters', 'doctors'));
    }

    public function create(Request $request)
    {
        $patients = Patient::orderBy('first_name')->get();
        $doctors = Doctor::with('department')->where('status', 'active')->get();
        $medicines = Medicine::where('stock_quantity', '>', 0)->get();

        $selectedAppointment = null;
        if ($request->filled('appointment_id')) {
            $selectedAppointment = Appointment::with('patient', 'doctor')->find($request->appointment_id);
        }

        return view('opd.create', compact('patients', 'doctors', 'medicines', 'selectedAppointment'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'encounter_date' => 'required|date',
            'blood_pressure' => 'nullable|string|max:20',
            'pulse_rate' => 'nullable|string|max:20',
            'temperature' => 'nullable|string|max:20',
            'respiratory_rate' => 'nullable|string|max:20',
            'spo2' => 'nullable|string|max:20',
            'weight' => 'nullable|string|max:20',
            'symptoms' => 'required|string',
            'diagnosis' => 'required|string',
            'treatment_notes' => 'nullable|string',
            // Prescription items (arrays)
            'medicine_id' => 'nullable|array',
            'dosage' => 'nullable|array',
            'frequency' => 'nullable|array',
            'duration' => 'nullable|array',
            'instructions' => 'nullable|array',
        ]);

        DB::beginTransaction();
        try {
            // 1. Create Encounter
            $encounter = Encounter::create([
                'patient_id' => $request->patient_id,
                'doctor_id' => $request->doctor_id,
                'appointment_id' => $request->appointment_id,
                'encounter_date' => $request->encounter_date,
                'encounter_type' => 'OPD',
                'blood_pressure' => $request->blood_pressure,
                'pulse_rate' => $request->pulse_rate,
                'temperature' => $request->temperature,
                'respiratory_rate' => $request->respiratory_rate,
                'spo2' => $request->spo2,
                'weight' => $request->weight,
                'symptoms' => $request->symptoms,
                'diagnosis' => $request->diagnosis,
                'treatment_notes' => $request->treatment_notes,
            ]);

            // 2. Mark Appointment as Completed if linked
            if ($request->filled('appointment_id')) {
                Appointment::where('id', $request->appointment_id)->update(['status' => 'completed']);
            }

            // 3. Create Prescription if medicines provided
            if ($request->filled('medicine_id') && count($request->medicine_id) > 0) {
                $countRx = Prescription::whereDate('created_at', Carbon::today())->count();
                $rxNumber = 'RX-' . date('Ymd') . '-' . str_pad($countRx + 1, 3, '0', STR_PAD_LEFT);

                $prescription = Prescription::create([
                    'prescription_number' => $rxNumber,
                    'patient_id' => $request->patient_id,
                    'doctor_id' => $request->doctor_id,
                    'encounter_id' => $encounter->id,
                    'clinical_notes' => $request->treatment_notes,
                ]);

                foreach ($request->medicine_id as $index => $medId) {
                    if (!empty($medId)) {
                        PrescriptionItem::create([
                            'prescription_id' => $prescription->id,
                            'medicine_id' => $medId,
                            'dosage' => $request->dosage[$index] ?? '',
                            'frequency' => $request->frequency[$index] ?? '',
                            'duration' => $request->duration[$index] ?? '',
                            'instructions' => $request->instructions[$index] ?? '',
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('opd.show', $encounter->id)
                ->with('success', 'OPD Encounter and Prescription saved successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error saving encounter: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $encounter = Encounter::with([
            'patient',
            'doctor.department',
            'appointment',
            'prescription.items.medicine'
        ])->findOrFail($id);

        return view('opd.show', compact('encounter'));
    }

    public function printPrescription($id)
    {
        $prescription = Prescription::with([
            'patient',
            'doctor.department',
            'encounter',
            'items.medicine'
        ])->findOrFail($id);

        return view('opd.prescription_print', compact('prescription'));
    }
}
