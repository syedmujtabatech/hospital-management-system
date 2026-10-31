@extends('layouts.app')

@section('title', 'Edit Medicine - ' . $medicine->name)

@section('content')
<div class="mb-3">
  <a href="{{ route('pharmacy.index') }}" class="btn btn-outline-secondary btn-sm mb-2">
    <i class="bx bx-arrow-back me-1"></i> Back to Pharmacy
  </a>
  <h4 class="fw-bold mb-1"><i class="bx bx-edit me-2 text-primary"></i> Edit Medicine Details</h4>
  <p class="text-muted mb-0">Update pricing, batch information, or alert limits for <span class="fw-bold">{{ $medicine->name }}</span></p>
</div>

<div class="card">
  <div class="card-body p-4">
    <form action="{{ route('pharmacy.update', $medicine->id) }}" method="POST">
      @csrf
      @method('PUT')

      <div class="row g-3 mb-4">
        <div class="col-md-6">
          <label class="form-label fw-semibold">Brand / Trade Name <span class="text-danger">*</span></label>
          <input type="text" name="name" class="form-control" value="{{ old('name', $medicine->name) }}" required>
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Generic Formula</label>
          <input type="text" name="generic_name" class="form-control" value="{{ old('generic_name', $medicine->generic_name) }}">
        </div>

        <div class="col-md-4">
          <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
          <select name="category" class="form-select" required>
            @foreach(['Tablet', 'Syrup', 'Capsule', 'Injection', 'Ointment / Cream', 'Inhaler / Drops', 'IV Fluid'] as $c)
              <option value="{{ $c }}" {{ old('category', $medicine->category) == $c ? 'selected' : '' }}>{{ $c }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Dosage Form / Packaging</label>
          <input type="text" name="dosage_form" class="form-control" value="{{ old('dosage_form', $medicine->dosage_form) }}">
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Manufacturer</label>
          <input type="text" name="manufacturer" class="form-control" value="{{ old('manufacturer', $medicine->manufacturer) }}">
        </div>

        <div class="col-md-4">
          <label class="form-label fw-semibold">Batch Number <span class="text-danger">*</span></label>
          <input type="text" name="batch_number" class="form-control" value="{{ old('batch_number', $medicine->batch_number) }}" required>
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Expiry Date <span class="text-danger">*</span></label>
          <input type="date" name="expiry_date" class="form-control" value="{{ old('expiry_date', $medicine->expiry_date) }}" required>
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Alert / Reorder Quantity <span class="text-danger">*</span></label>
          <input type="number" name="alert_quantity" class="form-control" value="{{ old('alert_quantity', $medicine->alert_quantity) }}" min="1" required>
        </div>

        <div class="col-md-4">
          <label class="form-label fw-semibold">Cost Price ($) <span class="text-danger">*</span></label>
          <input type="number" step="0.01" name="cost_price" class="form-control" value="{{ old('cost_price', $medicine->cost_price) }}" required>
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Selling / Retail Price ($) <span class="text-danger">*</span></label>
          <input type="number" step="0.01" name="selling_price" class="form-control" value="{{ old('selling_price', $medicine->selling_price) }}" required>
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Stock Quantity <span class="text-danger">*</span></label>
          <input type="number" name="stock_quantity" class="form-control" value="{{ old('stock_quantity', $medicine->stock_quantity) }}" min="0" required>
        </div>
      </div>

      <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('pharmacy.index') }}" class="btn btn-outline-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary px-4">
          <i class="bx bx-save me-1"></i> Update Medicine
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
