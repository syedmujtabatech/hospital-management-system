@extends('layouts.app')

@section('title', 'Laboratory & Diagnostic Orders')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h4 class="fw-bold mb-1"><i class="bx bx-test-tube me-2 text-primary"></i> Pathology & Diagnostic Lab Orders</h4>
    <p class="text-muted mb-0">Specimen collection, diagnostic processing, and certified lab reports</p>
  </div>
  <div class="d-flex gap-2">
    <a href="{{ route('laboratory.tests') }}" class="btn btn-outline-primary">
      <i class="bx bx-list-check me-1"></i> Test Catalog
    </a>
    <a href="{{ route('laboratory.order') }}" class="btn btn-primary">
      <i class="bx bx-plus me-1"></i> Order Lab Test
    </a>
  </div>
</div>

<!-- Stats -->
<div class="row g-3 mb-4">
  <div class="col-md-3 col-6">
    <div class="card border-0 bg-primary text-white shadow-sm">
      <div class="card-body p-3 d-flex justify-content-between align-items-center">
        <div>
          <span class="small text-white-50">Total Orders</span>
          <h3 class="fw-bold mb-0 text-white">{{ $stats['total_orders'] }}</h3>
        </div>
        <i class="bx bx-test-tube fs-1 text-white-50"></i>
      </div>
    </div>
  </div>

  <div class="col-md-3 col-6">
    <div class="card border-0 bg-warning text-white shadow-sm">
      <div class="card-body p-3 d-flex justify-content-between align-items-center">
        <div>
          <span class="small text-white-50">Pending Sample</span>
          <h3 class="fw-bold mb-0 text-white">{{ $stats['pending'] }}</h3>
        </div>
        <i class="bx bx-time fs-1 text-white-50"></i>
      </div>
    </div>
  </div>

  <div class="col-md-3 col-6">
    <div class="card border-0 bg-info text-white shadow-sm">
      <div class="card-body p-3 d-flex justify-content-between align-items-center">
        <div>
          <span class="small text-white-50">In Progress</span>
          <h3 class="fw-bold mb-0 text-white">{{ $stats['in_progress'] }}</h3>
        </div>
        <i class="bx bx-loader-alt fs-1 text-white-50"></i>
      </div>
    </div>
  </div>

  <div class="col-md-3 col-6">
    <div class="card border-0 bg-success text-white shadow-sm">
      <div class="card-body p-3 d-flex justify-content-between align-items-center">
        <div>
          <span class="small text-white-50">Completed & Ready</span>
          <h3 class="fw-bold mb-0 text-white">{{ $stats['completed'] }}</h3>
        </div>
        <i class="bx bx-check-double fs-1 text-white-50"></i>
      </div>
    </div>
  </div>
</div>

<!-- Table -->
<div class="card">
  <div class="table-responsive text-nowrap">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>Order #</th>
          <th>Patient Name (MRN)</th>
          <th>Diagnostic Test</th>
          <th>Referring Doctor</th>
          <th>Order Date</th>
          <th>Priority</th>
          <th>Status</th>
          <th class="text-center">Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse($orders as $o)
          <tr>
            <td><strong class="text-primary">{{ $o->order_number }}</strong></td>
            <td>
              @if($o->patient)
                <a href="{{ route('patients.show', $o->patient->id) }}" class="fw-bold text-dark">
                  {{ $o->patient->full_name }}
                </a>
                <div class="small text-muted">{{ $o->patient->mrn }}</div>
              @else
                <span class="text-muted">N/A</span>
              @endif
            </td>
            <td>
              <span class="fw-bold text-dark">{{ $o->labTest->test_name ?? 'Diagnostic Test' }}</span>
              <small class="text-muted d-block">{{ $o->labTest->category ?? '' }} (${{ number_format($o->labTest->price ?? 0, 2) }})</small>
            </td>
            <td>Dr. {{ $o->doctor->first_name ?? '' }} {{ $o->doctor->last_name ?? '' }}</td>
            <td>{{ \Carbon\Carbon::parse($o->order_date)->format('M d, Y') }}</td>
            <td>
              @if($o->priority === 'stat')
                <span class="badge bg-danger">STAT / Critical</span>
              @elseif($o->priority === 'urgent')
                <span class="badge bg-warning">Urgent</span>
              @else
                <span class="badge bg-label-secondary">Routine</span>
              @endif
            </td>
            <td>
              @if($o->status === 'completed')
                <span class="badge bg-success">Completed</span>
              @elseif($o->status === 'in_progress')
                <span class="badge bg-info">Processing</span>
              @else
                <span class="badge bg-warning">Pending</span>
              @endif
            </td>
            <td class="text-center">
              @if($o->status === 'completed')
                <a href="{{ route('laboratory.report', $o->id) }}" target="_blank" class="btn btn-sm btn-outline-success">
                  <i class="bx bx-printer me-1"></i> Report
                </a>
              @else
                <a href="{{ route('laboratory.result', $o->id) }}" class="btn btn-sm btn-primary">
                  <i class="bx bx-edit me-1"></i> Result
                </a>
              @endif
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" class="text-center py-5 text-muted">
              <i class="bx bx-test-tube fs-1 d-block mb-2"></i>
              No laboratory orders found.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($orders->hasPages())
    <div class="card-footer py-2">
      {{ $orders->links('pagination::bootstrap-5') }}
    </div>
  @endif
</div>
@endsection
