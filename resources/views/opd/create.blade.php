@extends('layouts.app')

@section('title', 'OPD Clinical Checkup & Prescription')

@section('content')
<div class="mb-3">
  <a href="{{ route('opd.index') }}" class="btn btn-outline-secondary btn-sm mb-2">
    <i class="bx bx-arrow-back me-1"></i> Back to OPD Encounters
  </a>
  <h4 class="fw-bold mb-1"><i class="bx bx-notepad me-2 text-primary"></i> OPD Clinical Examination & Prescription (Rx)</h4>
  <p class="text-muted mb-0">Record patient vitals, diagnosis, and electronically generate prescription</p>
</div>

<form action="{{ route('opd.store') }}" method="POST" id="opdForm">
  @csrf
  @if($selectedAppointment)
    <input type="hidden" name="appointment_id" value="{{ $selectedAppointment->id }}">
  @endif

  <!-- Section 1: Patient & Doctor Selection -->
  <div class="card mb-4">
    <div class="card-header bg-light py-2">
      <h6 class="mb-0 fw-bold text-primary"><i class="bx bx-user-pin me-1"></i> Encounter Identification</h6>
    </div>
    <div class="card-body p-4">
      <div class="row g-3">
        <div class="col-md-5">
          <label class="form-label fw-semibold">Patient <span class="text-danger">*</span></label>
          <select name="patient_id" class="form-select" required>
            <option value="">Select Patient by Name or MRN</option>
            @foreach($patients as $p)
              <option value="{{ $p->id }}" {{ (old('patient_id', request('patient_id', $selectedAppointment->patient_id ?? '')) == $p->id) ? 'selected' : '' }}>
                {{ $p->full_name }} ({{ $p->mrn }}) - {{ $p->age }}y, {{ $p->gender }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="col-md-4">
          <label class="form-label fw-semibold">Consulting Doctor <span class="text-danger">*</span></label>
          <select name="doctor_id" class="form-select" required>
            <option value="">Select Doctor</option>
            @foreach($doctors as $doc)
              <option value="{{ $doc->id }}" {{ (old('doctor_id', $selectedAppointment->doctor_id ?? '') == $doc->id) ? 'selected' : '' }}>
                Dr. {{ $doc->first_name }} {{ $doc->last_name }} ({{ $doc->department->name ?? 'Dept' }})
              </option>
            @endforeach
          </select>
        </div>

        <div class="col-md-3">
          <label class="form-label fw-semibold">Encounter Date <span class="text-danger">*</span></label>
          <input type="date" name="encounter_date" class="form-control" value="{{ old('encounter_date', date('Y-m-d')) }}" required>
        </div>
      </div>
    </div>
  </div>

  <!-- Section 2: Clinical Vitals -->
  <div class="card mb-4">
    <div class="card-header bg-light py-2">
      <h6 class="mb-0 fw-bold text-primary"><i class="bx bx-pulse me-1"></i> Patient Clinical Vitals</h6>
    </div>
    <div class="card-body p-4">
      <div class="row g-3">
        <div class="col-md-2 col-6">
          <label class="form-label fw-semibold">Blood Pressure</label>
          <div class="input-group">
            <input type="text" name="blood_pressure" class="form-control" placeholder="120/80" value="{{ old('blood_pressure') }}">
            <span class="input-group-text small">mmHg</span>
          </div>
        </div>

        <div class="col-md-2 col-6">
          <label class="form-label fw-semibold">Pulse Rate</label>
          <div class="input-group">
            <input type="text" name="pulse_rate" class="form-control" placeholder="78" value="{{ old('pulse_rate') }}">
            <span class="input-group-text small">bpm</span>
          </div>
        </div>

        <div class="col-md-2 col-6">
          <label class="form-label fw-semibold">Temperature</label>
          <div class="input-group">
            <input type="text" name="temperature" class="form-control" placeholder="98.6" value="{{ old('temperature') }}">
            <span class="input-group-text small">°F</span>
          </div>
        </div>

        <div class="col-md-2 col-6">
          <label class="form-label fw-semibold">Oxygen (SpO2)</label>
          <div class="input-group">
            <input type="text" name="spo2" class="form-control" placeholder="98" value="{{ old('spo2') }}">
            <span class="input-group-text small">%</span>
          </div>
        </div>

        <div class="col-md-2 col-6">
          <label class="form-label fw-semibold">Respiratory</label>
          <div class="input-group">
            <input type="text" name="respiratory_rate" class="form-control" placeholder="18" value="{{ old('respiratory_rate') }}">
            <span class="input-group-text small">/min</span>
          </div>
        </div>

        <div class="col-md-2 col-6">
          <label class="form-label fw-semibold">Weight</label>
          <div class="input-group">
            <input type="text" name="weight" class="form-control" placeholder="70" value="{{ old('weight') }}">
            <span class="input-group-text small">kg</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Section 3: Clinical Diagnosis & Complaints -->
  <div class="card mb-4">
    <div class="card-header bg-light py-2">
      <h6 class="mb-0 fw-bold text-primary"><i class="bx bx-check-shield me-1"></i> Clinical Evaluation & Diagnosis</h6>
    </div>
    <div class="card-body p-4">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label fw-semibold">Symptoms & Chief Complaints <span class="text-danger">*</span></label>
          <textarea name="symptoms" rows="3" class="form-control" required placeholder="Patient presents with acute headache, persistent dry cough for 4 days...">{{ old('symptoms', $selectedAppointment->problem_description ?? '') }}</textarea>
        </div>

        <div class="col-md-6">
          <label class="form-label fw-semibold">Clinical Diagnosis <span class="text-danger">*</span></label>
          <textarea name="diagnosis" rows="3" class="form-control" required placeholder="e.g. Acute Upper Respiratory Tract Infection (URTI) with mild hypertension">{{ old('diagnosis') }}</textarea>
        </div>

        <div class="col-12">
          <label class="form-label fw-semibold">Treatment Plan, Clinical Notes & Lifestyle Advice</label>
          <textarea name="treatment_notes" rows="2" class="form-control" placeholder="Drink plenty of warm liquids, complete bed rest for 2 days, follow-up in 1 week.">{{ old('treatment_notes') }}</textarea>
        </div>
      </div>
    </div>
  </div>

  <!-- Section 4: Dynamic Prescription (Rx) Builder -->
  <div class="card mb-4">
    <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center">
      <h6 class="mb-0 fw-bold text-primary"><i class="bx bx-capsule me-1"></i> Medical Prescription (Rx)</h6>
      <button type="button" class="btn btn-sm btn-primary" id="addMedicineRow">
        <i class="bx bx-plus me-1"></i> Add Medicine
      </button>
    </div>
    <div class="card-body p-4">
      <div class="table-responsive">
        <table class="table table-bordered align-middle" id="prescriptionTable">
          <thead class="table-light">
            <tr>
              <th style="width: 30%;">Medicine Name</th>
              <th style="width: 15%;">Dosage</th>
              <th style="width: 18%;">Frequency</th>
              <th style="width: 15%;">Duration</th>
              <th style="width: 17%;">Instructions</th>
              <th style="width: 5%;"></th>
            </tr>
          </thead>
          <tbody id="prescriptionRows">
            <!-- Row 1 -->
            <tr>
              <td>
                <select name="medicine_id[]" class="form-select select-medicine">
                  <option value="">Select Medicine from Pharmacy</option>
                  @foreach($medicines as $med)
                    <option value="{{ $med->id }}">
                      {{ $med->name }} ({{ $med->generic_name }}) - Stock: {{ $med->stock_quantity }}
                    </option>
                  @endforeach
                </select>
              </td>
              <td>
                <input type="text" name="dosage[]" class="form-control" placeholder="e.g. 500mg, 1 tab">
              </td>
              <td>
                <select name="frequency[]" class="form-select">
                  <option value="1-0-1 (Twice a day)">1-0-1 (Twice a day)</option>
                  <option value="1-1-1 (Three times a day)">1-1-1 (Three times a day)</option>
                  <option value="1-0-0 (Morning only)">1-0-0 (Morning only)</option>
                  <option value="0-0-1 (Night only)">0-0-1 (Night only)</option>
                  <option value="SOS (As needed)">SOS (As needed)</option>
                </select>
              </td>
              <td>
                <input type="text" name="duration[]" class="form-control" placeholder="e.g. 5 days, 1 week">
              </td>
              <td>
                <input type="text" name="instructions[]" class="form-control" placeholder="e.g. After meal">
              </td>
              <td class="text-center">
                <button type="button" class="btn btn-sm btn-outline-danger removeRowBtn" title="Remove"><i class="bx bx-trash"></i></button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="d-flex justify-content-end gap-2 mb-4">
    <a href="{{ route('opd.index') }}" class="btn btn-outline-secondary">Cancel</a>
    <button type="submit" class="btn btn-primary btn-lg px-4">
      <i class="bx bx-check-double me-1"></i> Complete & Save Consultation
    </button>
  </div>
</form>

@push('scripts')
<script>
  document.getElementById('addMedicineRow').addEventListener('click', function() {
    const tbody = document.getElementById('prescriptionRows');
    const firstRow = tbody.querySelector('tr');
    const newRow = firstRow.cloneNode(true);
    // clear input values
    newRow.querySelectorAll('input').forEach(input => input.value = '');
    newRow.querySelector('select.select-medicine').selectedIndex = 0;
    tbody.appendChild(newRow);
  });

  document.getElementById('prescriptionRows').addEventListener('click', function(e) {
    if (e.target.closest('.removeRowBtn')) {
      const rows = document.querySelectorAll('#prescriptionRows tr');
      if (rows.length > 1) {
        e.target.closest('tr').remove();
      } else {
        alert('Prescription must have at least one row, or leave it blank.');
      }
    }
  });
</script>
@endpush
@endsection
