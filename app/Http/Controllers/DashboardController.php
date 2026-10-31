<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\Room;
use App\Models\Admission;
use App\Models\Medicine;
use App\Models\LabOrder;
use App\Models\Bill;
use App\Models\Payment;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $stats = [
            'total_patients' => Patient::count(),
            'total_doctors' => Doctor::where('status', 'active')->count(),
            'today_appointments' => Appointment::whereDate('appointment_date', $today)->count(),
            'total_beds' => Room::count(),
            'occupied_beds' => Room::where('status', 'occupied')->count(),
            'available_beds' => Room::where('status', 'available')->count(),
            'low_stock_medicines' => Medicine::whereColumn('stock_quantity', '<=', 'alert_quantity')->count(),
            'pending_labs' => LabOrder::whereIn('status', ['pending', 'sample_collected'])->count(),
            'total_revenue' => Payment::sum('amount'),
            'today_revenue' => Payment::whereDate('payment_date', $today)->sum('amount'),
            'active_admissions' => Admission::where('status', 'admitted')->count(),
        ];

        $todayAppointments = Appointment::with(['patient', 'doctor.department'])
            ->whereDate('appointment_date', $today)
            ->orderBy('time_slot')
            ->limit(6)
            ->get();

        $recentAdmissions = Admission::with(['patient', 'room', 'doctor'])
            ->where('status', 'admitted')
            ->orderByDesc('admission_date')
            ->limit(5)
            ->get();

        $lowStockMedicines = Medicine::whereColumn('stock_quantity', '<=', 'alert_quantity')
            ->orderBy('stock_quantity')
            ->limit(5)
            ->get();

        $recentBills = Bill::with('patient')
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        $rooms = Room::with('currentAdmission.patient')->get();

        return view('dashboard', compact(
            'stats',
            'todayAppointments',
            'recentAdmissions',
            'lowStockMedicines',
            'recentBills',
            'rooms'
        ));
    }
}
