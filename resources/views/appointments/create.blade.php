@extends('layouts.app')

@section('title', 'Book Appointment')

@section('content')
<div class="mb-3">
  <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary btn-sm mb-2">
    <i class="bx bx-arrow-back me-1"></i> Back to Appointments
  </a>
  <h4 class="fw-bold mb-1"><i class="bx bx-calendar-plus me-2 text-primary"></i> Schedule Doctor Appointment</h4>
  <p class="text-muted mb-0">Select patient, consulting physician, and desired outpatient time slot.</p>
</div>

<div class="card">
  <div class="card-body p-4">
    <form action="{{ route('appointments.store') }}" method="POST">
      @csrf

      <div class="row g-3 mb-4">
        <div class="col-md-6">
          <label class="form-label fw-semibold">Patient <span class="text-danger">*</span></label>
          <select name="patient_id" class="form-select" required>
            <option value="">Select Patient by Name / MRN</option>
            @foreach($patients as $p)
              <option value="{{ $p->id }}" {{ (old('patient_id', $selectedPatientId) == $p->id) ? 'selected' : '' }}>
                {{ $p->full_name }} ({{ $p->mrn }}) - Phone: {{ $p->phone }}
              </option>
            @endforeach
          </select>
          <div class="form-text">Patient not registered? <a href="{{ route('patients.create') }}" target="_blank">Register new patient here</a></div>
        </div>

        <div class="col-md-6">
          <label class="form-label fw-semibold">Consulting Doctor <span class="text-danger">*</span></label>
          <select name="doctor_id" class="form-select" required>
            <option value="">Select Doctor</option>
            @foreach($doctors as $doc)
              <option value="{{ $doc->id }}" {{ old('doctor_id') == $doc->id ? 'selected' : '' }}>
                Dr. {{ $doc->first_name }} {{ $doc->last_name }} ({{ $doc->department->name ?? 'Dept' }} - ${{ number_format($doc->consultation_fee, 2) }})
              </option>
            @endforeach
          </select>
        </div>

        <div class="col-md-4">
          <label class="form-label fw-semibold">Appointment Date <span class="text-danger">*</span></label>
          <input type="date" name="appointment_date" class="form-control" value="{{ old('appointment_date', date('Y-m-d')) }}" required>
        </div>

        <div class="col-md-4">
          <label class="form-label fw-semibold">Time Slot <span class="text-danger">*</span></label>
          <select name="time_slot" class="form-select" required>
            <option value="09:00 AM" {{ old('time_slot') == '09:00 AM' ? 'selected' : '' }}>09:00 AM</option>
            <option value="09:30 AM" {{ old('time_slot') == '09:30 AM' ? 'selected' : '' }}>09:30 AM</option>
            <option value="10:00 AM" {{ old('time_slot', '10:00 AM') == '10:00 AM' ? 'selected' : '' }}>10:00 AM</option>
            <option value="10:30 AM" {{ old('time_slot') == '10:30 AM' ? 'selected' : '' }}>10:30 AM</option>
            <option value="11:00 AM" {{ old('time_slot') == '11:00 AM' ? 'selected' : '' }}>11:00 AM</option>
            <option value="11:30 AM" {{ old('time_slot') == '11:30 AM' ? 'selected' : '' }}>11:30 AM</option>
            <option value="12:00 PM" {{ old('time_slot') == '12:00 PM' ? 'selected' : '' }}>12:00 PM</option>
            <option value="02:00 PM" {{ old('time_slot') == '02:00 PM' ? 'selected' : '' }}>02:00 PM</option>
            <option value="02:30 PM" {{ old('time_slot') == '02:30 PM' ? 'selected' : '' }}>02:30 PM</option>
            <option value="03:00 PM" {{ old('time_slot') == '03:00 PM' ? 'selected' : '' }}>03:00 PM</option>
            <option value="04:00 PM" {{ old('time_slot') == '04:00 PM' ? 'selected' : '' }}>04:00 PM</option>
            <option value="05:00 PM" {{ old('time_slot') == '05:00 PM' ? 'selected' : '' }}>05:00 PM</option>
          </select>
        </div>

        <div class="col-md-4">
          <label class="form-label fw-semibold">Priority Level <span class="text-danger">*</span></label>
          <select name="priority" class="form-select" required>
            <option value="normal" {{ old('priority', 'normal') == 'normal' ? 'selected' : '' }}>Normal</option>
            <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
            <option value="emergency" {{ old('priority') == 'emergency' ? 'selected' : '' }}>Emergency</option>
          </select>
        </div>

        <div class="col-12">
          <label class="form-label fw-semibold">Patient Problem / Chief Complaints</label>
          <textarea name="problem_description" rows="3" class="form-control" placeholder="Describe symptoms or reasons for consultation (e.g. Fever for 3 days, acute headache)">{{ old('problem_description') }}</textarea>
        </div>
      </div>

      <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary px-4">
          <i class="bx bx-calendar-check me-1"></i> Confirm Appointment
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
