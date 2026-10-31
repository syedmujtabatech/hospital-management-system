@extends('layouts.app')

@section('title', 'Hospital Analytics Dashboard')

@section('content')
<div class="row">
  <!-- Welcome Banner -->
  <div class="col-12 mb-4">
    <div class="card border-0 bg-primary text-white shadow-sm overflow-hidden" style="background: linear-gradient(135deg, #696cff 0%, #4338ca 100%);">
      <div class="card-body p-4 position-relative">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h3 class="fw-bold text-white mb-1">Welcome to Al-Shifa Hospital Portal, {{ Auth::user()->name }}! 👋</h3>
            <p class="mb-0 opacity-75">Real-time Clinical Operations, Inpatient Wards, Diagnostics, and Financial Overview.</p>
          </div>
          <div class="d-none d-md-flex gap-2">
            <a href="{{ route('patients.create') }}" class="btn btn-light text-primary fw-semibold">
              <i class="bx bx-user-plus me-1"></i> New Patient
            </a>
            <a href="{{ route('opd.create') }}" class="btn btn-warning fw-semibold">
              <i class="bx bx-notepad me-1"></i> Start OPD
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- KPI Cards Row 1 -->
  <div class="col-lg-3 col-md-6 col-12 mb-4">
    <div class="card h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <span class="fw-semibold text-muted">Total Patients</span>
          <div class="avatar bg-label-primary p-2 rounded">
            <i class="bx bx-user text-primary fs-3"></i>
          </div>
        </div>
        <h3 class="card-title mb-1 fw-bold">{{ $stats['total_patients'] }}</h3>
        <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> EMR Registered</small>
      </div>
    </div>
  </div>

  <div class="col-lg-3 col-md-6 col-12 mb-4">
    <div class="card h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <span class="fw-semibold text-muted">Active Doctors</span>
          <div class="avatar bg-label-success p-2 rounded">
            <i class="bx bx-plus-medical text-success fs-3"></i>
          </div>
        </div>
        <h3 class="card-title mb-1 fw-bold">{{ $stats['total_doctors'] }}</h3>
        <small class="text-muted">Across 6 Specialties</small>
      </div>
    </div>
  </div>

  <div class="col-lg-3 col-md-6 col-12 mb-4">
    <div class="card h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <span class="fw-semibold text-muted">Today's Appointments</span>
          <div class="avatar bg-label-info p-2 rounded">
            <i class="bx bx-calendar-check text-info fs-3"></i>
          </div>
        </div>
        <h3 class="card-title mb-1 fw-bold">{{ $stats['today_appointments'] }}</h3>
        <small class="text-info fw-semibold"><i class="bx bx-time-five"></i> Scheduled OPD</small>
      </div>
    </div>
  </div>

  <div class="col-lg-3 col-md-6 col-12 mb-4">
    <div class="card h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <span class="fw-semibold text-muted">Bed Occupancy</span>
          <div class="avatar bg-label-warning p-2 rounded">
            <i class="bx bx-bed text-warning fs-3"></i>
          </div>
        </div>
        <h3 class="card-title mb-1 fw-bold">{{ $stats['occupied_beds'] }} / {{ $stats['total_beds'] }}</h3>
        <small class="text-success fw-semibold">{{ $stats['available_beds'] }} Beds Available</small>
      </div>
    </div>
  </div>

  <!-- KPI Cards Row 2 -->
  <div class="col-lg-4 col-md-6 col-12 mb-4">
    <div class="card h-100 border-start border-primary border-4">
      <div class="card-body d-flex justify-content-between align-items-center">
        <div>
          <span class="fw-semibold text-muted d-block mb-1">Total Revenue Collected</span>
          <h4 class="card-title mb-0 fw-bolder text-primary">${{ number_format($stats['total_revenue'], 2) }}</h4>
          <small class="text-muted">Today: ${{ number_format($stats['today_revenue'], 2) }}</small>
        </div>
        <div class="avatar bg-label-primary p-2 rounded">
          <i class="bx bx-wallet text-primary fs-2"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-4 col-md-6 col-12 mb-4">
    <div class="card h-100 border-start border-warning border-4">
      <div class="card-body d-flex justify-content-between align-items-center">
        <div>
          <span class="fw-semibold text-muted d-block mb-1">Pending Lab Orders</span>
          <h4 class="card-title mb-0 fw-bolder text-warning">{{ $stats['pending_labs'] }} Orders</h4>
          <small class="text-muted">Samples in processing</small>
        </div>
        <div class="avatar bg-label-warning p-2 rounded">
          <i class="bx bx-test-tube text-warning fs-2"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-4 col-md-6 col-12 mb-4">
    <div class="card h-100 border-start border-danger border-4">
      <div class="card-body d-flex justify-content-between align-items-center">
        <div>
          <span class="fw-semibold text-muted d-block mb-1">Low Stock Medicines</span>
          <h4 class="card-title mb-0 fw-bolder text-danger">{{ $stats['low_stock_medicines'] }} Items</h4>
          <small class="text-danger">Need immediate replenishment</small>
        </div>
        <div class="avatar bg-label-danger p-2 rounded">
          <i class="bx bx-capsule text-danger fs-2"></i>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Main Section: Appointments Queue & Wards Grid -->
<div class="row">
  <!-- Today's Appointments Queue -->
  <div class="col-lg-8 mb-4">
    <div class="card h-100">
      <div class="card-header d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 fw-bold"><i class="bx bx-list-check me-2 text-primary"></i> Today's Appointment Queue</h5>
        <a href="{{ route('appointments.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
      </div>
      <div class="table-responsive text-nowrap">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>Time</th>
              <th>Patient</th>
              <th>Doctor</th>
              <th>Priority</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse($todayAppointments as $apt)
              <tr>
                <td><strong class="text-primary">{{ $apt->time_slot }}</strong></td>
                <td>
                  <div class="fw-semibold">{{ $apt->patient->full_name ?? 'N/A' }}</div>
                  <small class="text-muted">{{ $apt->patient->mrn ?? '' }}</small>
                </td>
                <td>
                  <div>Dr. {{ $apt->doctor->first_name ?? '' }} {{ $apt->doctor->last_name ?? '' }}</div>
                  <small class="text-muted">{{ $apt->doctor->department->name ?? '' }}</small>
                </td>
                <td>
                  @if($apt->priority === 'emergency')
                    <span class="badge bg-danger">Emergency</span>
                  @elseif($apt->priority === 'urgent')
                    <span class="badge bg-warning">Urgent</span>
                  @else
                    <span class="badge bg-label-info">Normal</span>
                  @endif
                </td>
                <td>
                  @if($apt->status === 'completed')
                    <span class="badge bg-success">Completed</span>
                  @elseif($apt->status === 'cancelled')
                    <span class="badge bg-secondary">Cancelled</span>
                  @else
                    <span class="badge bg-label-primary">Scheduled</span>
                  @endif
                </td>
                <td>
                  <a href="{{ route('opd.create', ['appointment_id' => $apt->id]) }}" class="btn btn-xs btn-primary" title="Start Checkup">
                    <i class="bx bx-play me-1"></i> Checkup
                  </a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center py-4 text-muted">
                  <i class="bx bx-calendar-x fs-2 d-block mb-1"></i>
                  No appointments scheduled for today yet.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Bed Availability Matrix Quick Widget -->
  <div class="col-lg-4 mb-4">
    <div class="card h-100">
      <div class="card-header d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 fw-bold"><i class="bx bx-bed me-2 text-warning"></i> Ward & Bed Status</h5>
        <a href="{{ route('ipd.rooms') }}" class="btn btn-sm btn-outline-warning">Matrix</a>
      </div>
      <div class="card-body">
        <div class="d-flex justify-content-between mb-3 text-center">
          <div class="p-2 border rounded flex-fill me-2 bg-label-success">
            <span class="fw-bold d-block fs-5 text-success">{{ $stats['available_beds'] }}</span>
            <small class="text-muted">Available</small>
          </div>
          <div class="p-2 border rounded flex-fill me-2 bg-label-danger">
            <span class="fw-bold d-block fs-5 text-danger">{{ $stats['occupied_beds'] }}</span>
            <small class="text-muted">Occupied</small>
          </div>
          <div class="p-2 border rounded flex-fill bg-label-warning">
            <span class="fw-bold d-block fs-5 text-warning">{{ $rooms->where('status', 'maintenance')->count() }}</span>
            <small class="text-muted">Maint.</small>
          </div>
        </div>

        <h6 class="fw-semibold text-muted mb-2 small text-uppercase">Room Occupancy Quick List:</h6>
        <div class="list-group list-group-flush">
          @foreach($rooms->take(6) as $rm)
            <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
              <div>
                <span class="fw-bold">{{ $rm->room_number }}</span>
                <span class="text-muted small">({{ $rm->ward_name }})</span>
                @if($rm->status === 'occupied' && $rm->currentAdmission)
                  <div class="small text-danger"><i class="bx bx-user"></i> {{ $rm->currentAdmission->patient->full_name ?? 'Patient' }}</div>
                @endif
              </div>
              <div>
                @if($rm->status === 'available')
                  <span class="badge bg-label-success">Available</span>
                @elseif($rm->status === 'occupied')
                  <span class="badge bg-danger">Occupied</span>
                @else
                  <span class="badge bg-warning">Maintenance</span>
                @endif
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Secondary Row: Active Inpatients & Low Stock Alerts -->
<div class="row">
  <!-- Active Admitted Patients -->
  <div class="col-lg-6 mb-4">
    <div class="card h-100">
      <div class="card-header d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 fw-bold"><i class="bx bx-user-check me-2 text-info"></i> Currently Admitted Inpatients</h5>
        <a href="{{ route('ipd.index') }}" class="btn btn-sm btn-outline-info">All IPD</a>
      </div>
      <div class="table-responsive text-nowrap">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>Patient</th>
              <th>Bed</th>
              <th>Doctor</th>
              <th>Admitted On</th>
            </tr>
          </thead>
          <tbody>
            @forelse($recentAdmissions as $adm)
              <tr>
                <td>
                  <span class="fw-semibold">{{ $adm->patient->full_name ?? '' }}</span>
                  <div class="small text-muted">{{ $adm->patient->mrn ?? '' }}</div>
                </td>
                <td>
                  <span class="badge bg-label-primary fw-bold">{{ $adm->room->room_number ?? 'Bed' }}</span>
                  <small class="text-muted d-block">{{ $adm->room->ward_name ?? '' }}</small>
                </td>
                <td>Dr. {{ $adm->doctor->last_name ?? '' }}</td>
                <td>{{ \Carbon\Carbon::parse($adm->admission_date)->format('M d, Y') }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="text-center py-3 text-muted">No active inpatient admissions.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Low Stock Pharmacy Alerts -->
  <div class="col-lg-6 mb-4">
    <div class="card h-100">
      <div class="card-header d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 fw-bold text-danger"><i class="bx bx-error-alt me-2"></i> Pharmacy Stock Warnings</h5>
        <a href="{{ route('pharmacy.index', ['stock_status' => 'low']) }}" class="btn btn-sm btn-outline-danger">Inventory</a>
      </div>
      <div class="table-responsive text-nowrap">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>Medicine</th>
              <th>Category</th>
              <th>Stock</th>
              <th>Alert Qty</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @forelse($lowStockMedicines as $med)
              <tr>
                <td>
                  <strong>{{ $med->name }}</strong>
                  <div class="small text-muted">{{ $med->generic_name }}</div>
                </td>
                <td>{{ $med->category }}</td>
                <td class="text-danger fw-bold fs-6">{{ $med->stock_quantity }}</td>
                <td>{{ $med->alert_quantity }}</td>
                <td>
                  <span class="badge bg-danger">Critical Low</span>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center py-3 text-success">
                  <i class="bx bx-check-double me-1"></i> All medicines are well-stocked!
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
