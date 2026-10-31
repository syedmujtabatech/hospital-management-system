@extends('layouts.app')

@section('title', 'Hospital Billing & Invoicing')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h4 class="fw-bold mb-1"><i class="bx bx-receipt me-2 text-primary"></i> Billing, Invoicing & Payments</h4>
    <p class="text-muted mb-0">Consolidated patient accounts, itemized service charges, and payment receipts</p>
  </div>
  <a href="{{ route('billing.create') }}" class="btn btn-primary">
    <i class="bx bx-plus me-1"></i> Generate New Invoice
  </a>
</div>

<!-- Financial Summary Cards -->
<div class="row g-3 mb-4">
  <div class="col-md-3 col-6">
    <div class="card border-0 bg-primary text-white shadow-sm">
      <div class="card-body p-3 d-flex justify-content-between align-items-center">
        <div>
          <span class="small text-white-50">Total Billed</span>
          <h3 class="fw-bold mb-0 text-white">${{ number_format($stats['total_billed'], 2) }}</h3>
        </div>
        <i class="bx bx-receipt fs-1 text-white-50"></i>
      </div>
    </div>
  </div>

  <div class="col-md-3 col-6">
    <div class="card border-0 bg-success text-white shadow-sm">
      <div class="card-body p-3 d-flex justify-content-between align-items-center">
        <div>
          <span class="small text-white-50">Total Revenue Paid</span>
          <h3 class="fw-bold mb-0 text-white">${{ number_format($stats['total_paid'], 2) }}</h3>
        </div>
        <i class="bx bx-check-circle fs-1 text-white-50"></i>
      </div>
    </div>
  </div>

  <div class="col-md-3 col-6">
    <div class="card border-0 bg-danger text-white shadow-sm">
      <div class="card-body p-3 d-flex justify-content-between align-items-center">
        <div>
          <span class="small text-white-50">Outstanding Due</span>
          <h3 class="fw-bold mb-0 text-white">${{ number_format($stats['total_due'], 2) }}</h3>
        </div>
        <i class="bx bx-error fs-1 text-white-50"></i>
      </div>
    </div>
  </div>

  <div class="col-md-3 col-6">
    <div class="card border-0 bg-warning text-white shadow-sm">
      <div class="card-body p-3 d-flex justify-content-between align-items-center">
        <div>
          <span class="small text-white-50">Unpaid Invoices</span>
          <h3 class="fw-bold mb-0 text-white">{{ $stats['unpaid_count'] }} Bills</h3>
        </div>
        <i class="bx bx-time fs-1 text-white-50"></i>
      </div>
    </div>
  </div>
</div>

<!-- Filters -->
<div class="card mb-4">
  <div class="card-body">
    <form action="{{ route('billing.index') }}" method="GET" class="row g-3">
      <div class="col-md-6">
        <label class="form-label fw-semibold">Search by Invoice # or Patient Name / MRN</label>
        <div class="input-group">
          <span class="input-group-text"><i class="bx bx-search"></i></span>
          <input type="text" name="search" class="form-control" placeholder="e.g. INV-2026 or Tariq or MRN-2026-0001" value="{{ request('search') }}">
        </div>
      </div>
      <div class="col-md-4">
        <label class="form-label fw-semibold">Payment Status</label>
        <select name="status" class="form-select">
          <option value="">All Statuses</option>
          <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
          <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Partial</option>
          <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
        </select>
      </div>
      <div class="col-md-2 d-flex align-items-end gap-2">
        <button type="submit" class="btn btn-primary w-100"><i class="bx bx-filter"></i> Filter</button>
        @if(request()->hasAny(['search', 'status']))
          <a href="{{ route('billing.index') }}" class="btn btn-outline-secondary" title="Clear Filters"><i class="bx bx-reset"></i></a>
        @endif
      </div>
    </form>
  </div>
</div>

<!-- Invoices Table -->
<div class="card">
  <div class="table-responsive text-nowrap">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>Invoice #</th>
          <th>Patient Name (MRN)</th>
          <th>Date</th>
          <th>Total Amount</th>
          <th>Paid Amount</th>
          <th>Due Balance</th>
          <th>Status</th>
          <th class="text-center">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($bills as $b)
          <tr>
            <td><strong class="text-primary">{{ $b->bill_number }}</strong></td>
            <td>
              @if($b->patient)
                <a href="{{ route('patients.show', $b->patient->id) }}" class="fw-bold text-dark">
                  {{ $b->patient->full_name }}
                </a>
                <div class="small text-muted">{{ $b->patient->mrn }}</div>
              @else
                <span class="text-muted">N/A</span>
              @endif
            </td>
            <td>{{ \Carbon\Carbon::parse($b->bill_date)->format('M d, Y') }}</td>
            <td class="fw-bold">${{ number_format($b->total_amount, 2) }}</td>
            <td class="text-success fw-semibold">${{ number_format($b->paid_amount, 2) }}</td>
            <td class="fw-bold {{ $b->due_amount > 0 ? 'text-danger' : 'text-muted' }}">
              ${{ number_format($b->due_amount, 2) }}
            </td>
            <td>
              @if($b->status === 'paid')
                <span class="badge bg-success">Paid</span>
              @elseif($b->status === 'partial')
                <span class="badge bg-warning">Partial</span>
              @else
                <span class="badge bg-danger">Unpaid</span>
              @endif
            </td>
            <td class="text-center">
              <div class="btn-group">
                <a href="{{ route('billing.show', $b->id) }}" class="btn btn-sm btn-outline-primary" title="View Details & Pay">
                  <i class="bx bx-show"></i>
                </a>
                <a href="{{ route('billing.print', $b->id) }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="Print Invoice">
                  <i class="bx bx-printer"></i>
                </a>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" class="text-center py-5 text-muted">
              <i class="bx bx-receipt fs-1 d-block mb-2"></i>
              No billing invoices found.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($bills->hasPages())
    <div class="card-footer py-2">
      {{ $bills->links('pagination::bootstrap-5') }}
    </div>
  @endif
</div>
@endsection
