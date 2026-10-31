@extends('layouts.app')

@section('title', 'Hospital Appointments')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h4 class="fw-bold mb-1"><i class="bx bx-calendar-event me-2 text-primary"></i> Outpatient Appointments</h4>
    <p class="text-muted mb-0">Manage doctor schedules, patient visit queues, and OPD slots</p>
  </div>
  <a href="{{ route('appointments.create') }}" class="btn btn-primary">
    <i class="bx bx-calendar-plus me-1"></i> Book New Appointment
  </a>
</div>

<!-- Filters -->
<div class="card mb-4">
  <div class="card-body">
    <form action="{{ route('appointments.index') }}" method="GET" class="row g-3">
      <div class="col-md-3">
        <label class="form-label fw-semibold">Date</label>
        <input type="date" name="date" class="form-control" value="{{ request('date') }}">
      </div>
      <div class="col-md-3">
        <label class="form-label fw-semibold">Doctor</label>
        <select name="doctor_id" class="form-select">
          <option value="">All Doctors</option>
          @foreach($doctors as $doc)
            <option value="{{ $doc->id }}" {{ request('doctor_id') == $doc->id ? 'selected' : '' }}>
              Dr. {{ $doc->first_name }} {{ $doc->last_name }} ({{ $doc->department->name ?? '' }})
            </option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label fw-semibold">Status</label>
        <select name="status" class="form-select">
          <option value="">All Statuses</option>
          <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
          <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
          <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
          <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label fw-semibold">Priority</label>
        <select name="priority" class="form-select">
          <option value="">All Priorities</option>
          <option value="normal" {{ request('priority') == 'normal' ? 'selected' : '' }}>Normal</option>
          <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
          <option value="emergency" {{ request('priority') == 'emergency' ? 'selected' : '' }}>Emergency</option>
        </select>
      </div>
      <div class="col-md-2 d-flex align-items-end gap-2">
        <button type="submit" class="btn btn-primary w-100"><i class="bx bx-filter"></i> Filter</button>
        @if(request()->hasAny(['date', 'doctor_id', 'status', 'priority']))
          <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary" title="Clear Filters"><i class="bx bx-reset"></i></a>
        @endif
      </div>
    </form>
  </div>
</div>

<!-- Table -->
<div class="card">
  <div class="table-responsive text-nowrap">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>Apt #</th>
          <th>Date & Time</th>
          <th>Patient Name</th>
          <th>Doctor & Dept</th>
          <th>Priority</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($appointments as $apt)
          <tr>
            <td><strong class="text-primary">{{ $apt->appointment_number }}</strong></td>
            <td>
              <div>{{ \Carbon\Carbon::parse($apt->appointment_date)->format('M d, Y') }}</div>
              <small class="badge bg-label-info">{{ $apt->time_slot }}</small>
            </td>
            <td>
              @if($apt->patient)
                <a href="{{ route('patients.show', $apt->patient->id) }}" class="fw-bold text-dark">
                  {{ $apt->patient->full_name }}
                </a>
                <div class="small text-muted">{{ $apt->patient->mrn }}</div>
              @else
                <span class="text-muted">N/A</span>
              @endif
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
                <span class="badge bg-label-secondary">Normal</span>
              @endif
            </td>
            <td>
              <form action="{{ route('appointments.status', $apt->id) }}" method="POST" class="d-inline">
                @csrf
                <select name="status" class="form-select form-select-sm d-inline-block w-auto" onchange="this.form.submit()">
                  <option value="scheduled" {{ $apt->status == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                  <option value="confirmed" {{ $apt->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                  <option value="completed" {{ $apt->status == 'completed' ? 'selected' : '' }}>Completed</option>
                  <option value="cancelled" {{ $apt->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
              </form>
            </td>
            <td>
              <a href="{{ route('opd.create', ['appointment_id' => $apt->id]) }}" class="btn btn-sm btn-primary" title="Start OPD Checkup">
                <i class="bx bx-notepad me-1"></i> Checkup
              </a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center py-5 text-muted">
              <i class="bx bx-calendar-x fs-1 d-block mb-2"></i>
              No appointments found matching your filter criteria.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($appointments->hasPages())
    <div class="card-footer py-2">
      {{ $appointments->links('pagination::bootstrap-5') }}
    </div>
  @endif
</div>
@endsection
