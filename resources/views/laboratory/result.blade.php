@extends('layouts.app')

@section('title', 'Enter Lab Results - ' . $order->order_number)

@section('content')
<div class="mb-3">
  <a href="{{ route('laboratory.index') }}" class="btn btn-outline-secondary btn-sm mb-2">
    <i class="bx bx-arrow-back me-1"></i> Back to Lab Orders
  </a>
  <h4 class="fw-bold mb-1"><i class="bx bx-edit me-2 text-primary"></i> Enter Diagnostic Test Result</h4>
  <p class="text-muted mb-0">Record specimen findings and validate diagnostic values for <span class="fw-bold">{{ $order->labTest->test_name ?? 'Test' }}</span></p>
</div>

<!-- Order Summary Banner -->
<div class="card mb-4 bg-light">
  <div class="card-body p-3">
    <div class="row align-items-center">
      <div class="col-md-3">
        <small class="text-muted d-block">Order #</small>
        <strong class="text-primary fs-5">{{ $order->order_number }}</strong>
      </div>
      <div class="col-md-3">
        <small class="text-muted d-block">Patient</small>
        <strong>{{ $order->patient->full_name ?? 'N/A' }}</strong> ({{ $order->patient->mrn ?? '' }})
      </div>
      <div class="col-md-3">
        <small class="text-muted d-block">Test Name</small>
        <strong>{{ $order->labTest->test_name ?? '' }}</strong>
      </div>
      <div class="col-md-3">
        <small class="text-muted d-block">Normal Reference Range</small>
        <span class="badge bg-secondary">{{ $order->labTest->reference_range ?? 'Standard' }}</span>
      </div>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-body p-4">
    <form action="{{ route('laboratory.updateResult', $order->id) }}" method="POST">
      @csrf

      <div class="row g-3 mb-4">
        <div class="col-md-6">
          <label class="form-label fw-semibold">Status / Progress <span class="text-danger">*</span></label>
          <select name="status" class="form-select" required>
            <option value="in_progress" {{ old('status', $order->status) == 'in_progress' ? 'selected' : '' }}>In Progress (Under Testing)</option>
            <option value="completed" {{ old('status', $order->status) == 'completed' ? 'selected' : '' }}>Completed (Verified & Signed Off)</option>
          </select>
        </div>

        <div class="col-md-6">
          <label class="form-label fw-semibold">Sample Type Used</label>
          <input type="text" class="form-control bg-light" value="{{ $order->labTest->sample_type ?? 'Whole Blood / Serum' }}" readonly>
        </div>

        <div class="col-12">
          <label class="form-label fw-semibold">Test Quantitative / Qualitative Result Values <span class="text-danger">*</span></label>
          <textarea name="result_value" rows="4" class="form-control font-monospace" required placeholder="e.g.:
Hemoglobin: 14.2 g/dL (Normal: 13.0 - 17.0)
WBC Count: 7,500 /uL (Normal: 4,000 - 11,000)
Platelets: 240,000 /uL (Normal: 150,000 - 450,000)">{{ old('result_value', $order->result_value) }}</textarea>
        </div>

        <div class="col-12">
          <label class="form-label fw-semibold">Pathologist Remarks / Diagnostic Impression</label>
          <textarea name="result_notes" rows="3" class="form-control" placeholder="e.g. Findings are within normal biological physiological limits. No abnormal cellular morphology seen.">{{ old('result_notes', $order->result_notes) }}</textarea>
        </div>
      </div>

      <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('laboratory.index') }}" class="btn btn-outline-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary px-4">
          <i class="bx bx-check-circle me-1"></i> Save & Authorize Results
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
