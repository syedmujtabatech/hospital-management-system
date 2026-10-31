@extends('layouts.app')

@section('title', 'Add Medicine')

@section('content')
<div class="mb-3">
  <a href="{{ route('pharmacy.index') }}" class="btn btn-outline-secondary btn-sm mb-2">
    <i class="bx bx-arrow-back me-1"></i> Back to Pharmacy
  </a>
  <h4 class="fw-bold mb-1"><i class="bx bx-capsule me-2 text-primary"></i> Add Medicine to Inventory</h4>
  <p class="text-muted mb-0">Record drug formulation, batch, pricing, and reorder alert thresholds</p>
</div>

<div class="card">
  <div class="card-body p-4">
    <form action="{{ route('pharmacy.store') }}" method="POST">
      @csrf

      <div class="row g-3 mb-4">
        <div class="col-md-6">
          <label class="form-label fw-semibold">Brand / Trade Name <span class="text-danger">*</span></label>
          <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="e.g. Panadol Extra 500mg">
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Generic Formula</label>
          <input type="text" name="generic_name" class="form-control" value="{{ old('generic_name') }}" placeholder="e.g. Paracetamol + Caffeine">
        </div>

        <div class="col-md-4">
          <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
          <select name="category" class="form-select" required>
            <option value="Tablet">Tablet</option>
            <option value="Syrup">Syrup</option>
            <option value="Capsule">Capsule</option>
            <option value="Injection">Injection</option>
            <option value="Ointment / Cream">Ointment / Cream</option>
            <option value="Inhaler / Drops">Inhaler / Drops</option>
            <option value="IV Fluid">IV Fluid</option>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Dosage Form / Packaging</label>
          <input type="text" name="dosage_form" class="form-control" value="{{ old('dosage_form') }}" placeholder="e.g. Box of 100 Tabs">
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Manufacturer</label>
          <input type="text" name="manufacturer" class="form-control" value="{{ old('manufacturer') }}" placeholder="e.g. GSK, Abbott, Getz">
        </div>

        <div class="col-md-4">
          <label class="form-label fw-semibold">Batch Number <span class="text-danger">*</span></label>
          <input type="text" name="batch_number" class="form-control" value="{{ old('batch_number') }}" required placeholder="e.g. BATCH-2026-X9">
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Expiry Date <span class="text-danger">*</span></label>
          <input type="date" name="expiry_date" class="form-control" value="{{ old('expiry_date') }}" required>
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Alert / Reorder Quantity <span class="text-danger">*</span></label>
          <input type="number" name="alert_quantity" class="form-control" value="{{ old('alert_quantity', 20) }}" min="1" required>
        </div>

        <div class="col-md-4">
          <label class="form-label fw-semibold">Cost Price ($) <span class="text-danger">*</span></label>
          <input type="number" step="0.01" name="cost_price" class="form-control" value="{{ old('cost_price') }}" required placeholder="e.g. 1.20">
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Selling / Retail Price ($) <span class="text-danger">*</span></label>
          <input type="number" step="0.01" name="selling_price" class="form-control" value="{{ old('selling_price') }}" required placeholder="e.g. 2.50">
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Initial Stock Quantity <span class="text-danger">*</span></label>
          <input type="number" name="stock_quantity" class="form-control" value="{{ old('stock_quantity', 100) }}" min="0" required>
        </div>
      </div>

      <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('pharmacy.index') }}" class="btn btn-outline-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary px-4">
          <i class="bx bx-check-circle me-1"></i> Save Medicine
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
