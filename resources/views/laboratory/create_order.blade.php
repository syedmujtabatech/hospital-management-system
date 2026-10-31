@extends('layouts.app')

@section('title', 'Order Diagnostic Lab Test')

@section('content')
<div class="mb-3">
  <a href="{{ route('laboratory.index') }}" class="btn btn-outline-secondary btn-sm mb-2">
    <i class="bx bx-arrow-back me-1"></i> Back to Lab Orders
  </a>
  <h4 class="fw-bold mb-1"><i class="bx bx-test-tube me-2 text-primary"></i> Order Diagnostic / Pathology Test</h4>
  <p class="text-muted mb-0">Generate laboratory requisition order for outpatient or inpatient</p>
</div>

<div class="card">
  <div class="card-body p-4">
    <form action="{{ route('laboratory.storeOrder') }}" method="POST">
      @csrf

      <div class="row g-3 mb-4">
        <div class="col-md-6">
          <label class="form-label fw-semibold">Patient <span class="text-danger">*</span></label>
          <select name="patient_id" class="form-select" required>
            <option value="">Select Patient by Name or MRN</option>
            @foreach($patients as $p)
              <option value="{{ $p->id }}" {{ (old('patient_id', $selectedPatientId) == $p->id) ? 'selected' : '' }}>
                {{ $p->full_name }} ({{ $p->mrn }}) - Phone: {{ $p->phone }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="col-md-6">
          <label class="form-label fw-semibold">Diagnostic Test to Perform <span class="text-danger">*</span></label>
          <select name="lab_test_id" class="form-select" required>
            <option value="">Select Test from Catalog</option>
            @foreach($tests as $t)
              <option value="{{ $t->id }}" {{ old('lab_test_id') == $t->id ? 'selected' : '' }}>
                {{ $t->test_name }} ({{ $t->category }} - ${{ number_format($t->price, 2) }})
              </option>
            @endforeach
          </select>
        </div>

        <div class="col-md-4">
          <label class="form-label fw-semibold">Ordering / Referring Doctor <span class="text-danger">*</span></label>
          <select name="doctor_id" class="form-select" required>
            <option value="">Select Doctor</option>
            @foreach($doctors as $doc)
              <option value="{{ $doc->id }}" {{ old('doctor_id') == $doc->id ? 'selected' : '' }}>
                Dr. {{ $doc->first_name }} {{ $doc->last_name }} ({{ $doc->department->name ?? 'Dept' }})
              </option>
            @endforeach
          </select>
        </div>

        <div class="col-md-4">
          <label class="form-label fw-semibold">Requisition Date <span class="text-danger">*</span></label>
          <input type="date" name="order_date" class="form-control" value="{{ old('order_date', date('Y-m-d')) }}" required>
        </div>

        <div class="col-md-4">
          <label class="form-label fw-semibold">Priority Level <span class="text-danger">*</span></label>
          <select name="priority" class="form-select" required>
            <option value="routine" {{ old('priority', 'routine') == 'routine' ? 'selected' : '' }}>Routine</option>
            <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
            <option value="stat" {{ old('priority') == 'stat' ? 'selected' : '' }}>STAT / Critical Emergency</option>
          </select>
        </div>

        <div class="col-12">
          <label class="form-label fw-semibold">Clinical Indication / Special Instructions for Lab</label>
          <textarea name="clinical_notes" rows="3" class="form-control" placeholder="e.g. Fasting sample required, check for anemia/leukocytosis...">{{ old('clinical_notes') }}</textarea>
        </div>
      </div>

      <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('laboratory.index') }}" class="btn btn-outline-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary px-4">
          <i class="bx bx-check-circle me-1"></i> Generate Requisition Order
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
