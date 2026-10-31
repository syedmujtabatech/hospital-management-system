<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admission;
use App\Models\Room;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Bill;
use App\Models\BillItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class IpdController extends Controller
{
    public function index(Request $request)
    {
        $query = Admission::with(['patient', 'room', 'doctor.department']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'admitted'); // default active admissions
        }

        $admissions = $query->orderByDesc('admission_date')->paginate(15)->withQueryString();

        return view('ipd.index', compact('admissions'));
    }

    public function rooms()
    {
        $rooms = Room::with('currentAdmission.patient', 'currentAdmission.doctor')->get();
        $wards = Room::select('ward_name')->distinct()->pluck('ward_name');

        $stats = [
            'total' => $rooms->count(),
            'available' => $rooms->where('status', 'available')->count(),
            'occupied' => $rooms->where('status', 'occupied')->count(),
            'maintenance' => $rooms->where('status', 'maintenance')->count(),
        ];

        return view('ipd.rooms', compact('rooms', 'wards', 'stats'));
    }

    public function create(Request $request)
    {
        $patients = Patient::orderBy('first_name')->get();
        $doctors = Doctor::where('status', 'active')->get();
        $availableRooms = Room::where('status', 'available')->get();

        $selectedRoomId = $request->query('room_id');

        return view('ipd.create', compact('patients', 'doctors', 'availableRooms', 'selectedRoomId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'room_id' => 'required|exists:rooms,id',
            'doctor_id' => 'required|exists:doctors,id',
            'admission_date' => 'required|date',
            'reason' => 'required|string',
            'initial_diagnosis' => 'nullable|string',
            'advance_payment' => 'nullable|numeric|min:0',
        ]);

        $room = Room::findOrFail($validated['room_id']);
        if ($room->status !== 'available') {
            return back()->withInput()->with('error', 'Selected room/bed is already occupied or under maintenance.');
        }

        DB::beginTransaction();
        try {
            $count = Admission::whereDate('admission_date', Carbon::today())->count();
            $admissionNumber = 'IPD-' . date('Ymd') . '-' . str_pad($count + 1, 3, '0', STR_PAD_LEFT);

            $admission = Admission::create([
                'admission_number' => $admissionNumber,
                'patient_id' => $validated['patient_id'],
                'room_id' => $validated['room_id'],
                'doctor_id' => $validated['doctor_id'],
                'admission_date' => $validated['admission_date'],
                'reason' => $validated['reason'],
                'initial_diagnosis' => $validated['initial_diagnosis'],
                'daily_rate' => $room->daily_charge,
                'status' => 'admitted',
            ]);

            // Update room status
            $room->update(['status' => 'occupied']);

            DB::commit();

            return redirect()->route('ipd.index')
                ->with('success', "Patient admitted successfully under record {$admission->admission_number}. Bed marked Occupied.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Admission failed: ' . $e->getMessage());
        }
    }

    public function discharge(Request $request, $id)
    {
        $admission = Admission::with('room', 'patient')->findOrFail($id);

        $request->validate([
            'discharge_date' => 'required|date|after_or_equal:' . Carbon::parse($admission->admission_date)->format('Y-m-d'),
            'discharge_notes' => 'nullable|string',
            'discharge_condition' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $admissionDate = Carbon::parse($admission->admission_date);
            $dischargeDate = Carbon::parse($request->discharge_date);
            $days = max(1, $admissionDate->diffInDays($dischargeDate));

            $admission->update([
                'discharge_date' => $request->discharge_date,
                'discharge_notes' => $request->discharge_notes,
                'discharge_condition' => $request->discharge_condition ?? 'Recovered / Stable',
                'status' => 'discharged',
            ]);

            // Free the room
            if ($admission->room) {
                $admission->room->update(['status' => 'available']);
            }

            DB::commit();

            return redirect()->route('ipd.index')
                ->with('success', "Patient {$admission->patient->full_name} discharged successfully! Bed {$admission->room->room_number} is now Available.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Discharge failed: ' . $e->getMessage());
        }
    }

    public function updateRoomStatus(Request $request, $id)
    {
        $room = Room::findOrFail($id);
        $status = $request->validate([
            'status' => 'required|in:available,occupied,maintenance',
        ])['status'];

        $room->update(['status' => $status]);

        return back()->with('success', "Room {$room->room_number} status updated to " . ucfirst($status));
    }
}
