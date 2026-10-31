@extends('layouts.app')

@section('title', 'Admit Patient (IPD)')

@section('content')
<div class="mb-3">
  <a href="{{ route('ipd.index') }}" class="btn btn-outline-secondary btn-sm mb-2">
    <i class="bx bx-arrow-back me-1"></i> Back to Admissions
  </a>
  <h4 class="fw-bold mb-1"><i class="bx bx-bed me-2 text-primary"></i> Patient Inpatient (IPD) Admission</h4>
  <p class="text-muted mb-0">Assign hospital bed, attending consultant, and admission clinical indications</p>
</div>

<div class="card">
  <div class="card-body p-4">
    <form action="{{ route('ipd.store') }}" method="POST">
      @csrf

      <div class="row g-3 mb-4">
        <div class="col-md-6">
          <label class="form-label fw-semibold">Patient to Admit <span class="text-danger">*</span></label>
          <select name="patient_id" class="form-select" required>
            <option value="">Select Patient by Name / MRN</option>
            @foreach($patients as $p)
              <option value="{{ $p->id }}" {{ (old('patient_id', request('patient_id')) == $p->id) ? 'selected' : '' }}>
                {{ $p->full_name }} ({{ $p->mrn }}) - Blood: {{ $p->blood_group ?? '-' }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="col-md-6">
          <label class="form-label fw-semibold">Assign Available Room / Bed <span class="text-danger">*</span></label>
          <select name="room_id" class="form-select" required>
            <option value="">Select Available Bed</option>
            @foreach($availableRooms as $rm)
              <option value="{{ $rm->id }}" {{ (old('room_id', $selectedRoomId) == $rm->id) ? 'selected' : '' }}>
                Bed {{ $rm->room_number }} - {{ $rm->ward_name }} ({{ ucfirst($rm->room_type) }} - ${{ number_format($rm->daily_charge, 0) }}/day)
              </option>
            @endforeach
          </select>
        </div>

        <div class="col-md-6">
          <label class="form-label fw-semibold">Attending Consultant Doctor <span class="text-danger">*</span></label>
          <select name="doctor_id" class="form-select" required>
            <option value="">Select Doctor</option>
            @foreach($doctors as $doc)
              <option value="{{ $doc->id }}" {{ old('doctor_id') == $doc->id ? 'selected' : '' }}>
                Dr. {{ $doc->first_name }} {{ $doc->last_name }} ({{ $doc->department->name ?? 'Dept' }})
              </option>
            @endforeach
          </select>
        </div>

        <div class="col-md-6">
          <label class="form-label fw-semibold">Admission Date & Time <span class="text-danger">*</span></label>
          <input type="datetime-local" name="admission_date" class="form-control" value="{{ old('admission_date', date('Y-m-d\TH:i')) }}" required>
        </div>

        <div class="col-md-6">
          <label class="form-label fw-semibold">Reason for Hospitalization / Admission <span class="text-danger">*</span></label>
          <textarea name="reason" rows="3" class="form-control" required placeholder="e.g. Acute appendicitis scheduled for surgical intervention, high fever under observation...">{{ old('reason') }}</textarea>
        </div>

        <div class="col-md-6">
          <label class="form-label fw-semibold">Initial Clinical Diagnosis</label>
          <textarea name="initial_diagnosis" rows="3" class="form-control" placeholder="e.g. Acute Appendicitis / Post-operative monitoring">{{ old('initial_diagnosis') }}</textarea>
        </div>
      </div>

      <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('ipd.index') }}" class="btn btn-outline-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary px-4">
          <i class="bx bx-check-circle me-1"></i> Confirm Inpatient Admission
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
