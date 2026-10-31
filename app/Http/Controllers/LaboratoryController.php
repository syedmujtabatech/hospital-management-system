<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LabOrder;
use App\Models\LabTest;
use App\Models\Patient;
use App\Models\Doctor;
use Carbon\Carbon;

class LaboratoryController extends Controller
{
    public function index(Request $request)
    {
        $query = LabOrder::with(['patient', 'doctor', 'labTest']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $orders = $query->orderByDesc('order_date')->paginate(15)->withQueryString();

        $stats = [
            'total_orders' => LabOrder::count(),
            'pending' => LabOrder::where('status', 'pending')->count(),
            'in_progress' => LabOrder::whereIn('status', ['sample_collected', 'in_progress'])->count(),
            'completed' => LabOrder::where('status', 'completed')->count(),
        ];

        return view('laboratory.index', compact('orders', 'stats'));
    }

    public function tests()
    {
        $tests = LabTest::orderBy('category')->orderBy('test_name')->get();
        return view('laboratory.tests', compact('tests'));
    }

    public function createOrder(Request $request)
    {
        $patients = Patient::orderBy('first_name')->get();
        $doctors = Doctor::where('status', 'active')->get();
        $tests = LabTest::where('status', 'active')->get();

        $selectedPatientId = $request->query('patient_id');

        return view('laboratory.create_order', compact('patients', 'doctors', 'tests', 'selectedPatientId'));
    }

    public function storeOrder(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'lab_test_id' => 'required|exists:lab_tests,id',
            'order_date' => 'required|date',
            'priority' => 'required|in:routine,urgent,stat',
            'clinical_notes' => 'nullable|string',
        ]);

        $count = LabOrder::whereDate('order_date', Carbon::today())->count();
        $validated['order_number'] = 'LAB-' . date('Ymd') . '-' . str_pad($count + 1, 3, '0', STR_PAD_LEFT);
        $validated['status'] = 'pending';

        $order = LabOrder::create($validated);

        return redirect()->route('laboratory.index')
            ->with('success', "Lab Order {$order->order_number} created successfully.");
    }

    public function editResult($id)
    {
        $order = LabOrder::with(['patient', 'doctor', 'labTest'])->findOrFail($id);
        return view('laboratory.result', compact('order'));
    }

    public function updateResult(Request $request, $id)
    {
        $order = LabOrder::findOrFail($id);

        $validated = $request->validate([
            'result_value' => 'required|string',
            'result_notes' => 'nullable|string',
            'status' => 'required|in:sample_collected,in_progress,completed',
        ]);

        if ($validated['status'] === 'completed') {
            $validated['result_date'] = Carbon::now();
        }

        $order->update($validated);

        return redirect()->route('laboratory.index')
            ->with('success', "Lab order {$order->order_number} result saved.");
    }

    public function printReport($id)
    {
        $order = LabOrder::with(['patient', 'doctor', 'labTest'])->findOrFail($id);
        return view('laboratory.report_print', compact('order'));
    }
}
