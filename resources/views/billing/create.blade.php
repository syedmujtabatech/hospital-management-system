@extends('layouts.app')

@section('title', 'Generate Hospital Invoice')

@section('content')
<div class="mb-3">
  <a href="{{ route('billing.index') }}" class="btn btn-outline-secondary btn-sm mb-2">
    <i class="bx bx-arrow-back me-1"></i> Back to Invoices
  </a>
  <h4 class="fw-bold mb-1"><i class="bx bx-receipt me-2 text-primary"></i> Create Patient Billing Invoice</h4>
  <p class="text-muted mb-0">Generate itemized hospital bill for consultations, procedures, beds, lab tests, and pharmacy</p>
</div>

<form action="{{ route('billing.store') }}" method="POST" id="billForm">
  @csrf

  <!-- Patient & Date -->
  <div class="card mb-4">
    <div class="card-header bg-light py-2">
      <h6 class="mb-0 fw-bold text-primary"><i class="bx bx-user me-1"></i> Invoice Recipient</h6>
    </div>
    <div class="card-body p-4">
      <div class="row g-3">
        <div class="col-md-8">
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

        <div class="col-md-4">
          <label class="form-label fw-semibold">Invoice Date <span class="text-danger">*</span></label>
          <input type="date" name="bill_date" class="form-control" value="{{ old('bill_date', date('Y-m-d')) }}" required>
        </div>
      </div>
    </div>
  </div>

  <!-- Itemized Charges -->
  <div class="card mb-4">
    <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center">
      <h6 class="mb-0 fw-bold text-primary"><i class="bx bx-list-plus me-1"></i> Itemized Medical Services & Supplies</h6>
      <button type="button" class="btn btn-sm btn-primary" id="addBillItemBtn">
        <i class="bx bx-plus me-1"></i> Add Service / Item
      </button>
    </div>
    <div class="card-body p-4">
      <div class="table-responsive">
        <table class="table table-bordered align-middle" id="billItemsTable">
          <thead class="table-light">
            <tr>
              <th style="width: 38%;">Service Description</th>
              <th style="width: 20%;">Charge Type</th>
              <th style="width: 12%;">Qty</th>
              <th style="width: 15%;">Unit Price ($)</th>
              <th style="width: 10%;">Total ($)</th>
              <th style="width: 5%;"></th>
            </tr>
          </thead>
          <tbody id="billItemRows">
            <!-- Initial Row -->
            <tr>
              <td>
                <input type="text" name="item_description[]" class="form-control" placeholder="e.g. Specialist Consultation Fee" required value="Specialist Consultation Fee">
              </td>
              <td>
                <select name="item_type[]" class="form-select">
                  <option value="consultation">Consultation</option>
                  <option value="room_charge">Room / Bed Charge</option>
                  <option value="medicine">Pharmacy / Medicine</option>
                  <option value="lab_test">Lab Diagnostic</option>
                  <option value="procedure">Nursing / Procedure</option>
                  <option value="other">Other Service</option>
                </select>
              </td>
              <td>
                <input type="number" name="quantity[]" class="form-control item-qty" value="1" min="1" required>
              </td>
              <td>
                <input type="number" step="0.01" name="unit_price[]" class="form-control item-price" value="50.00" min="0" required>
              </td>
              <td class="text-end fw-bold">
                <span class="item-total">$50.00</span>
              </td>
              <td class="text-center">
                <button type="button" class="btn btn-sm btn-outline-danger removeBillItemBtn"><i class="bx bx-trash"></i></button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Financial Summary & Payment -->
  <div class="row">
    <div class="col-md-6 mb-4">
      <div class="card h-100">
        <div class="card-header bg-light py-2">
          <h6 class="mb-0 fw-bold text-primary"><i class="bx bx-note me-1"></i> Billing Notes</h6>
        </div>
        <div class="card-body p-4">
          <label class="form-label fw-semibold">Invoice Remarks / Hospital Policy Notes</label>
          <textarea name="notes" rows="4" class="form-control" placeholder="e.g. Emergency visit consultation and diagnostics. Insurance claim submitted."></textarea>
        </div>
      </div>
    </div>

    <div class="col-md-6 mb-4">
      <div class="card h-100">
        <div class="card-header bg-light py-2">
          <h6 class="mb-0 fw-bold text-primary"><i class="bx bx-calculator me-1"></i> Invoice Summary & Payment</h6>
        </div>
        <div class="card-body p-4">
          <div class="d-flex justify-content-between mb-2">
            <span class="text-muted">Subtotal:</span>
            <strong class="fs-6" id="summarySubtotal">$50.00</strong>
          </div>
          <div class="row g-2 align-items-center mb-2">
            <div class="col-6">
              <span class="text-muted">Discount ($):</span>
            </div>
            <div class="col-6">
              <input type="number" step="0.01" name="discount_amount" id="discountInput" class="form-control form-control-sm text-end" value="0.00" min="0">
            </div>
          </div>
          <div class="row g-2 align-items-center mb-3">
            <div class="col-6">
              <span class="text-muted">Tax / VAT ($):</span>
            </div>
            <div class="col-6">
              <input type="number" step="0.01" name="tax_amount" id="taxInput" class="form-control form-control-sm text-end" value="0.00" min="0">
            </div>
          </div>

          <div class="d-flex justify-content-between py-2 border-top border-bottom mb-3">
            <span class="fw-bold fs-5 text-dark">Grand Total:</span>
            <strong class="fs-4 text-primary" id="summaryTotal">$50.00</strong>
          </div>

          <div class="row g-2 align-items-center mb-2">
            <div class="col-6">
              <span class="fw-semibold text-success">Paid Now Amount ($):</span>
            </div>
            <div class="col-6">
              <input type="number" step="0.01" name="paid_now" id="paidNowInput" class="form-control form-control-sm text-end fw-bold text-success" value="50.00" min="0">
            </div>
          </div>

          <div class="row g-2 align-items-center mb-3">
            <div class="col-6">
              <span class="text-muted small">Payment Mode:</span>
            </div>
            <div class="col-6">
              <select name="payment_method" class="form-select form-select-sm">
                <option value="cash">Cash Counter</option>
                <option value="credit_card">Credit Card (POS)</option>
                <option value="debit_card">Debit Card</option>
                <option value="bank_transfer">Bank Transfer</option>
                <option value="online">Online / UPI</option>
              </select>
            </div>
          </div>

          <div class="d-flex justify-content-between text-muted small pt-2 border-top">
            <span>Remaining Due Balance:</span>
            <strong class="text-danger fs-6" id="summaryDue">$0.00</strong>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="d-flex justify-content-end gap-2 mb-4">
    <a href="{{ route('billing.index') }}" class="btn btn-outline-secondary">Cancel</a>
    <button type="submit" class="btn btn-primary btn-lg px-4">
      <i class="bx bx-check-circle me-1"></i> Generate & Issue Invoice
    </button>
  </div>
</form>

@push('scripts')
<script>
  function recalculateTotals() {
    let subtotal = 0;
    document.querySelectorAll('#billItemRows tr').forEach(row => {
      const qty = parseFloat(row.querySelector('.item-qty').value) || 0;
      const price = parseFloat(row.querySelector('.item-price').value) || 0;
      const rowTotal = qty * price;
      row.querySelector('.item-total').textContent = '$' + rowTotal.toFixed(2);
      subtotal += rowTotal;
    });

    const discount = parseFloat(document.getElementById('discountInput').value) || 0;
    const tax = parseFloat(document.getElementById('taxInput').value) || 0;
    const grandTotal = Math.max(0, (subtotal - discount) + tax);
    const paidNow = parseFloat(document.getElementById('paidNowInput').value) || 0;
    const due = Math.max(0, grandTotal - paidNow);

    document.getElementById('summarySubtotal').textContent = '$' + subtotal.toFixed(2);
    document.getElementById('summaryTotal').textContent = '$' + grandTotal.toFixed(2);
    document.getElementById('summaryDue').textContent = '$' + due.toFixed(2);
  }

  document.getElementById('addBillItemBtn').addEventListener('click', function() {
    const tbody = document.getElementById('billItemRows');
    const firstRow = tbody.querySelector('tr');
    const newRow = firstRow.cloneNode(true);
    newRow.querySelector('input[name="item_description[]"]').value = '';
    newRow.querySelector('.item-qty').value = '1';
    newRow.querySelector('.item-price').value = '0.00';
    newRow.querySelector('.item-total').textContent = '$0.00';
    tbody.appendChild(newRow);
    recalculateTotals();
  });

  document.getElementById('billItemRows').addEventListener('click', function(e) {
    if (e.target.closest('.removeBillItemBtn')) {
      const rows = document.querySelectorAll('#billItemRows tr');
      if (rows.length > 1) {
        e.target.closest('tr').remove();
        recalculateTotals();
      } else {
        alert('An invoice must have at least one bill item.');
      }
    }
  });

  document.getElementById('billForm').addEventListener('input', function(e) {
    if (e.target.classList.contains('item-qty') || 
        e.target.classList.contains('item-price') || 
        e.target.id === 'discountInput' || 
        e.target.id === 'taxInput' || 
        e.target.id === 'paidNowInput') {
      recalculateTotals();
    }
  });
</script>
@endpush
@endsection
