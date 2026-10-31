@extends('layouts.app')

@section('title', 'Invoice - ' . $bill->bill_number)

@section('content')
<div class="mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
  <div>
    <a href="{{ route('billing.index') }}" class="btn btn-outline-secondary btn-sm mb-2">
      <i class="bx bx-arrow-back me-1"></i> Back to Invoices
    </a>
    <h4 class="fw-bold mb-0">Hospital Invoice Details</h4>
  </div>

  <div class="d-flex gap-2">
    @if($bill->due_amount > 0)
      <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#paymentModal">
        <i class="bx bx-dollar-circle me-1"></i> Record Payment
      </button>
    @endif
    <a href="{{ route('billing.print', $bill->id) }}" target="_blank" class="btn btn-primary">
      <i class="bx bx-printer me-1"></i> Print Tax Invoice
    </a>
  </div>
</div>

<!-- Invoice Card -->
<div class="card mb-4">
  <div class="card-header bg-light d-flex justify-content-between align-items-center py-3">
    <div>
      <h5 class="fw-bold mb-0 text-primary">{{ $bill->bill_number }}</h5>
      <small class="text-muted">Issued on {{ \Carbon\Carbon::parse($bill->bill_date)->format('l, d F Y') }}</small>
    </div>
    <div>
      @if($bill->status === 'paid')
        <span class="badge bg-success fs-6">PAID IN FULL</span>
      @elseif($bill->status === 'partial')
        <span class="badge bg-warning fs-6">PARTIAL PAYMENT</span>
      @else
        <span class="badge bg-danger fs-6">UNPAID</span>
      @endif
    </div>
  </div>

  <div class="card-body p-4">
    <!-- Recipient & Hospital Info -->
    <div class="row mb-4">
      <div class="col-md-6 border-end">
        <h6 class="text-muted small text-uppercase fw-bold mb-1">Billed To (Patient):</h6>
        <h5 class="fw-bold text-dark mb-1">{{ $bill->patient->full_name ?? 'N/A' }}</h5>
        <div class="small">MRN: <span class="mrn-badge">{{ $bill->patient->mrn ?? '' }}</span></div>
        <div class="small text-muted mt-1">Phone: {{ $bill->patient->phone ?? '' }}</div>
        @if($bill->patient->address)
          <div class="small text-muted">Address: {{ $bill->patient->address }}</div>
        @endif
      </div>
      <div class="col-md-6 ps-md-4">
        <h6 class="text-muted small text-uppercase fw-bold mb-1">Hospital Facility:</h6>
        <h5 class="fw-bold text-primary mb-1">Al-Shifa International Hospital</h5>
        <div class="small text-muted">H-8/4, Islamabad | NTN: 0891234-7</div>
        <div class="small text-muted">Accounts Helpline: +92 (51) 111-222-333 Ext 402</div>
      </div>
    </div>

    <!-- Itemized Table -->
    <div class="table-responsive mb-4">
      <table class="table table-bordered align-middle">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Service Description</th>
            <th>Charge Category</th>
            <th class="text-center">Qty</th>
            <th class="text-end">Unit Price</th>
            <th class="text-end">Total Price</th>
          </tr>
        </thead>
        <tbody>
          @foreach($bill->items as $idx => $it)
            <tr>
              <td>{{ $idx + 1 }}</td>
              <td class="fw-semibold">{{ $it->description }}</td>
              <td><span class="badge bg-label-info text-capitalize">{{ str_replace('_', ' ', $it->item_type) }}</span></td>
              <td class="text-center">{{ $it->quantity }}</td>
              <td class="text-end">${{ number_format($it->unit_price, 2) }}</td>
              <td class="text-end fw-bold">${{ number_format($it->total_price, 2) }}</td>
            </tr>
          @endforeach
        </tbody>
        <tfoot>
          <tr>
            <th colspan="5" class="text-end">Subtotal:</th>
            <td class="text-end fw-bold">${{ number_format($bill->subtotal, 2) }}</td>
          </tr>
          @if($bill->discount > 0)
            <tr>
              <th colspan="5" class="text-end text-success">Discount:</th>
              <td class="text-end text-success">- ${{ number_format($bill->discount, 2) }}</td>
            </tr>
          @endif
          @if($bill->tax > 0)
            <tr>
              <th colspan="5" class="text-end text-muted">Tax / VAT:</th>
              <td class="text-end">+ ${{ number_format($bill->tax, 2) }}</td>
            </tr>
          @endif
          <tr class="table-light fs-5">
            <th colspan="5" class="text-end text-dark">Grand Total:</th>
            <td class="text-end text-primary fw-bolder">${{ number_format($bill->total_amount, 2) }}</td>
          </tr>
          <tr>
            <th colspan="5" class="text-end text-success">Total Amount Paid:</th>
            <td class="text-end text-success fw-bold">${{ number_format($bill->paid_amount, 2) }}</td>
          </tr>
          <tr class="table-warning fs-5">
            <th colspan="5" class="text-end text-danger">Outstanding Balance Due:</th>
            <td class="text-end text-danger fw-bolder">${{ number_format($bill->due_amount, 2) }}</td>
          </tr>
        </tfoot>
      </table>
    </div>

    <!-- Payments Receipts History -->
    <h6 class="fw-bold text-dark mb-2"><i class="bx bx-check-shield text-success me-1"></i> Recorded Payment Transactions</h6>
    <div class="table-responsive">
      <table class="table table-sm table-striped">
        <thead class="table-light">
          <tr>
            <th>Receipt #</th>
            <th>Date</th>
            <th>Payment Mode</th>
            <th>Reference #</th>
            <th class="text-end">Amount Paid</th>
          </tr>
        </thead>
        <tbody>
          @forelse($bill->payments as $pay)
            <tr>
              <td class="fw-bold">{{ $pay->receipt_number }}</td>
              <td>{{ \Carbon\Carbon::parse($pay->payment_date)->format('M d, Y') }}</td>
              <td><span class="badge bg-label-primary text-uppercase">{{ str_replace('_', ' ', $pay->payment_method) }}</span></td>
              <td><code>{{ $pay->transaction_reference ?: '-' }}</code></td>
              <td class="text-end text-success fw-bold">${{ number_format($pay->amount, 2) }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center py-2 text-muted">No payments recorded for this invoice yet.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal to Record Payment -->
@if($bill->due_amount > 0)
  <div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="{{ route('billing.payment', $bill->id) }}" method="POST">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title fw-bold">Record Payment for {{ $bill->bill_number }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="alert alert-info py-2 mb-3">
              Remaining Due Balance: <strong class="text-danger">${{ number_format($bill->due_amount, 2) }}</strong>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Amount to Pay ($) <span class="text-danger">*</span></label>
              <input type="number" step="0.01" name="amount" class="form-control" value="{{ $bill->due_amount }}" max="{{ $bill->due_amount }}" min="0.01" required>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Payment Mode <span class="text-danger">*</span></label>
              <select name="payment_method" class="form-select" required>
                <option value="cash">Cash</option>
                <option value="credit_card">Credit Card (POS)</option>
                <option value="debit_card">Debit Card</option>
                <option value="bank_transfer">Bank Transfer</option>
                <option value="online">Online / UPI</option>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Payment Date <span class="text-danger">*</span></label>
              <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Transaction / Card Reference #</label>
              <input type="text" name="transaction_id" class="form-control" placeholder="e.g. POS-AUTH-9871">
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Notes</label>
              <textarea name="notes" rows="2" class="form-control" placeholder="Optional notes"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-success">Save Payment Receipt</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endif
@endsection
