@extends('layouts.app')

@section('title', 'OPD Encounters & Prescriptions')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h4 class="fw-bold mb-1"><i class="bx bx-notepad me-2 text-primary"></i> OPD Consultations & Prescriptions</h4>
    <p class="text-muted mb-0">Doctor clinical examination records, vitals measurements, and prescription slips</p>
  </div>
  <a href="{{ route('opd.create') }}" class="btn btn-primary">
    <i class="bx bx-plus me-1"></i> Start New OPD Checkup
  </a>
</div>

<!-- Filters -->
<div class="card mb-4">
  <div class="card-body">
    <form action="{{ route('opd.index') }}" method="GET" class="row g-3">
      <div class="col-md-4">
        <label class="form-label fw-semibold">Consultation Date</label>
        <input type="date" name="date" class="form-control" value="{{ request('date') }}">
      </div>
      <div class="col-md-5">
        <label class="form-label fw-semibold">Doctor</label>
        <select name="doctor_id" class="form-select">
          <option value="">All Consulting Doctors</option>
          @foreach($doctors as $doc)
            <option value="{{ $doc->id }}" {{ request('doctor_id') == $doc->id ? 'selected' : '' }}>
              Dr. {{ $doc->first_name }} {{ $doc->last_name }} ({{ $doc->department->name ?? '' }})
            </option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3 d-flex align-items-end gap-2">
        <button type="submit" class="btn btn-primary w-100"><i class="bx bx-filter"></i> Filter</button>
        @if(request()->hasAny(['date', 'doctor_id']))
          <a href="{{ route('opd.index') }}" class="btn btn-outline-secondary" title="Clear Filters"><i class="bx bx-reset"></i></a>
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
          <th>Date</th>
          <th>Patient Name (MRN)</th>
          <th>Doctor</th>
          <th>Recorded Vitals</th>
          <th>Clinical Diagnosis</th>
          <th>Prescription</th>
          <th class="text-center">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($encounters as $enc)
          <tr>
            <td>
              <span class="fw-bold">{{ \Carbon\Carbon::parse($enc->encounter_date)->format('M d, Y') }}</span>
              <small class="text-muted d-block">{{ \Carbon\Carbon::parse($enc->created_at)->format('h:i A') }}</small>
            </td>
            <td>
              @if($enc->patient)
                <a href="{{ route('patients.show', $enc->patient->id) }}" class="fw-bold text-dark">
                  {{ $enc->patient->full_name }}
                </a>
                <div class="small text-muted">{{ $enc->patient->mrn }}</div>
              @else
                <span class="text-muted">N/A</span>
              @endif
            </td>
            <td>
              Dr. {{ $enc->doctor->first_name ?? '' }} {{ $enc->doctor->last_name ?? '' }}
              <small class="text-muted d-block">{{ $enc->doctor->department->name ?? '' }}</small>
            </td>
            <td>
              <div class="small">
                <span>BP: <strong>{{ $enc->blood_pressure ?: '-' }}</strong></span> | 
                <span>Pulse: <strong>{{ $enc->pulse_rate ?: '-' }}</strong></span>
              </div>
              <div class="small text-muted">
                <span>Temp: {{ $enc->temperature ?: '-' }}°F</span> | 
                <span>SpO2: {{ $enc->spo2 ?: '-' }}%</span>
              </div>
            </td>
            <td>
              <span class="fw-semibold text-primary">{{ Str::limit($enc->diagnosis, 35) }}</span>
            </td>
            <td>
              @if($enc->prescription)
                <span class="badge bg-label-success">{{ $enc->prescription->items->count() }} Medicines</span>
              @else
                <span class="badge bg-label-secondary">No Rx</span>
              @endif
            </td>
            <td class="text-center">
              <div class="btn-group">
                <a href="{{ route('opd.show', $enc->id) }}" class="btn btn-sm btn-outline-primary" title="View Encounter">
                  <i class="bx bx-show"></i>
                </a>
                @if($enc->prescription)
                  <a href="{{ route('prescriptions.print', $enc->prescription->id) }}" target="_blank" class="btn btn-sm btn-outline-success" title="Print Prescription">
                    <i class="bx bx-printer"></i> Rx
                  </a>
                @endif
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center py-5 text-muted">
              <i class="bx bx-notepad fs-1 d-block mb-2"></i>
              No OPD encounters found matching criteria.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($encounters->hasPages())
    <div class="card-footer py-2">
      {{ $encounters->links('pagination::bootstrap-5') }}
    </div>
  @endif
</div>
@endsection
