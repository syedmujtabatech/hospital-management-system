@extends('layouts.app')

@section('title', 'Diagnostic Test Catalog')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <a href="{{ route('laboratory.index') }}" class="btn btn-outline-secondary btn-sm mb-2">
      <i class="bx bx-arrow-back me-1"></i> Back to Lab Orders
    </a>
    <h4 class="fw-bold mb-1"><i class="bx bx-list-check me-2 text-primary"></i> Diagnostic & Pathology Test Catalog</h4>
    <p class="text-muted mb-0">Standardized reference intervals, sample types, and department pricing</p>
  </div>
  <a href="{{ route('laboratory.order') }}" class="btn btn-primary">
    <i class="bx bx-plus me-1"></i> Order a Test
  </a>
</div>

<div class="card">
  <div class="table-responsive text-nowrap">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>Test Code</th>
          <th>Test Name</th>
          <th>Category</th>
          <th>Sample Required</th>
          <th>Normal Reference Range</th>
          <th>Standard Price</th>
        </tr>
      </thead>
      <tbody>
        @forelse($tests as $t)
          <tr>
            <td><code>{{ $t->test_code }}</code></td>
            <td>
              <strong class="text-primary">{{ $t->test_name }}</strong>
            </td>
            <td><span class="badge bg-label-info">{{ $t->category }}</span></td>
            <td><i class="bx bx-vial text-danger me-1"></i> {{ $t->sample_type ?: 'Blood' }}</td>
            <td>
              <small class="text-muted">{{ $t->reference_range }}</small>
            </td>
            <td>
              <span class="fw-bold text-success">${{ number_format($t->price, 2) }}</span>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="text-center py-5 text-muted">No diagnostic tests configured in catalog.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
