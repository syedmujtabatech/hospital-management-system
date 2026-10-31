@extends('layouts.app')

@section('title', 'OPD Encounter Summary')

@section('content')
<div class="mb-3 d-flex justify-content-between align-items-center">
  <div>
    <a href="{{ route('opd.index') }}" class="btn btn-outline-secondary btn-sm mb-2">
      <i class="bx bx-arrow-back me-1"></i> Back to Encounters
    </a>
    <h4 class="fw-bold mb-0">OPD Consultation Summary</h4>
  </div>

  @if($encounter->prescription)
    <a href="{{ route('prescriptions.print', $encounter->prescription->id) }}" target="_blank" class="btn btn-primary">
      <i class="bx bx-printer me-1"></i> Print Official Rx Slip
    </a>
  @endif
</div>

<!-- Details Card -->
<div class="card mb-4">
  <div class="card-header bg-light d-flex justify-content-between align-items-center py-3">
    <div>
      <span class="badge bg-primary me-2">OPD Visit</span>
      <span class="text-muted">Date: <strong>{{ \Carbon\Carbon::parse($encounter->encounter_date)->format('l, d F Y') }}</strong></span>
    </div>
    <span class="text-muted small">Recorded: {{ $encounter->created_at->format('h:i A') }}</span>
  </div>
  <div class="card-body p-4">
    <!-- Patient & Doctor Row -->
    <div class="row border-bottom pb-3 mb-3">
      <div class="col-md-6 border-end">
        <h6 class="text-muted small text-uppercase fw-bold mb-1">Patient Details:</h6>
        <h5 class="fw-bold text-primary mb-1">{{ $encounter->patient->full_name ?? 'N/A' }}</h5>
        <div class="small">MRN: <span class="mrn-badge">{{ $encounter->patient->mrn ?? '' }}</span></div>
        <div class="small text-muted mt-1">{{ $encounter->patient->age ?? 'N/A' }} Years, {{ ucfirst($encounter->patient->gender ?? '') }} | Blood: {{ $encounter->patient->blood_group ?? '-' }}</div>
        <div class="small text-muted">Phone: {{ $encounter->patient->phone ?? '' }}</div>
      </div>
      <div class="col-md-6 ps-md-4">
        <h6 class="text-muted small text-uppercase fw-bold mb-1">Consulting Physician:</h6>
        <h5 class="fw-bold mb-1">Dr. {{ $encounter->doctor->first_name ?? '' }} {{ $encounter->doctor->last_name ?? '' }}</h5>
        <div class="small text-primary fw-semibold">{{ $encounter->doctor->department->name ?? 'Department' }}</div>
        <div class="small text-muted">{{ $encounter->doctor->specialization ?? '' }}</div>
        <div class="small text-muted">Clinic: {{ $encounter->doctor->room_no ?: 'OPD Room' }}</div>
      </div>
    </div>

    <!-- Vitals Row -->
    <h6 class="text-muted small text-uppercase fw-bold mb-2">Recorded Clinical Vitals:</h6>
    <div class="row g-2 mb-4">
      <div class="col-md-2 col-4">
        <div class="border rounded p-2 text-center bg-light">
          <small class="text-muted d-block">Blood Pressure</small>
          <strong class="text-primary">{{ $encounter->blood_pressure ?: '-' }}</strong> <small>mmHg</small>
        </div>
      </div>
      <div class="col-md-2 col-4">
        <div class="border rounded p-2 text-center bg-light">
          <small class="text-muted d-block">Pulse Rate</small>
          <strong class="text-success">{{ $encounter->pulse_rate ?: '-' }}</strong> <small>bpm</small>
        </div>
      </div>
      <div class="col-md-2 col-4">
        <div class="border rounded p-2 text-center bg-light">
          <small class="text-muted d-block">Temperature</small>
          <strong class="text-warning">{{ $encounter->temperature ?: '-' }}</strong> <small>°F</small>
        </div>
      </div>
      <div class="col-md-2 col-4">
        <div class="border rounded p-2 text-center bg-light">
          <small class="text-muted d-block">Oxygen (SpO2)</small>
          <strong class="text-info">{{ $encounter->spo2 ?: '-' }}</strong> <small>%</small>
        </div>
      </div>
      <div class="col-md-2 col-4">
        <div class="border rounded p-2 text-center bg-light">
          <small class="text-muted d-block">Respiratory</small>
          <strong class="text-secondary">{{ $encounter->respiratory_rate ?: '-' }}</strong> <small>/min</small>
        </div>
      </div>
      <div class="col-md-2 col-4">
        <div class="border rounded p-2 text-center bg-light">
          <small class="text-muted d-block">Weight</small>
          <strong class="text-dark">{{ $encounter->weight ?: '-' }}</strong> <small>kg</small>
        </div>
      </div>
    </div>

    <!-- Symptoms & Diagnosis -->
    <div class="row mb-4">
      <div class="col-md-6 mb-3">
        <div class="p-3 border rounded bg-white h-100">
          <h6 class="fw-bold text-muted small text-uppercase mb-1"><i class="bx bx-message-error text-warning me-1"></i> Symptoms & Chief Complaints</h6>
          <p class="mb-0">{{ $encounter->symptoms }}</p>
        </div>
      </div>
      <div class="col-md-6 mb-3">
        <div class="p-3 border rounded bg-white h-100">
          <h6 class="fw-bold text-muted small text-uppercase mb-1"><i class="bx bx-check-shield text-success me-1"></i> Clinical Diagnosis</h6>
          <p class="mb-0 text-primary fw-bold">{{ $encounter->diagnosis }}</p>
        </div>
      </div>
      @if($encounter->treatment_notes)
        <div class="col-12">
          <div class="p-3 border rounded bg-white">
            <h6 class="fw-bold text-muted small text-uppercase mb-1"><i class="bx bx-notepad text-info me-1"></i> Treatment Plan & Lifestyle Instructions</h6>
            <p class="mb-0">{{ $encounter->treatment_notes }}</p>
          </div>
        </div>
      @endif
    </div>

    <!-- Prescribed Medications -->
    @if($encounter->prescription && $encounter->prescription->items->count() > 0)
      <h6 class="text-muted small text-uppercase fw-bold mb-2"><i class="bx bx-capsule me-1 text-primary"></i> Prescribed Medications ({{ $encounter->prescription->prescription_number }}):</h6>
      <div class="table-responsive">
        <table class="table table-bordered align-middle">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Medicine Name</th>
              <th>Dosage</th>
              <th>Frequency</th>
              <th>Duration</th>
              <th>Instructions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($encounter->prescription->items as $idx => $item)
              <tr>
                <td>{{ $idx + 1 }}</td>
                <td>
                  <strong>{{ $item->medicine->name ?? 'Medicine' }}</strong>
                  <div class="small text-muted">{{ $item->medicine->generic_name ?? '' }}</div>
                </td>
                <td>{{ $item->dosage }}</td>
                <td><span class="badge bg-label-primary">{{ $item->frequency }}</span></td>
                <td>{{ $item->duration }}</td>
                <td><small class="text-muted">{{ $item->instructions ?: 'As advised' }}</small></td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>
</div>
@endsection
