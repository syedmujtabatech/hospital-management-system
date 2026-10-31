<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Invoice - {{ $bill->bill_number }}</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #333; background: #f8f9fa; }
    .invoice-container { max-width: 820px; margin: 20px auto; background: #fff; padding: 35px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
    .hospital-header { border-bottom: 2px solid #2b548b; padding-bottom: 15px; margin-bottom: 20px; }
    .patient-bar { background: #f1f5f9; padding: 12px 16px; border-radius: 6px; margin-bottom: 25px; }
    @media print {
      body { background: #fff; }
      .invoice-container { box-shadow: none; margin: 0; padding: 20px; max-width: 100%; }
      .no-print { display: none !important; }
    }
  </style>
</head>
<body>

<div class="container text-center my-3 no-print">
  <button onclick="window.print()" class="btn btn-primary px-4 me-2">
    <i class="fa fa-print me-1"></i> Print Invoice Receipt
  </button>
  <button onclick="window.close()" class="btn btn-outline-secondary">Close Window</button>
</div>

<div class="invoice-container">
  <!-- Header -->
  <div class="hospital-header d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center">
      <div class="me-3 text-primary">
        <i class="fa-solid fa-hospital-user" style="font-size: 3rem;"></i>
      </div>
      <div>
        <h3 class="fw-bold mb-0 text-primary">AL-SHIFA INTERNATIONAL HOSPITAL</h3>
        <p class="text-muted small mb-0">H-8/4, Islamabad | NTN: 0891234-7 | Phone: +92 (51) 111-222-333</p>
        <small class="text-muted">Official Medical Services Tax Invoice & Cash Receipt</small>
      </div>
    </div>
    <div class="text-end">
      <h4 class="fw-bold mb-0 text-dark">{{ $bill->bill_number }}</h4>
      <div class="small text-muted">Date: {{ \Carbon\Carbon::parse($bill->bill_date)->format('d-M-Y') }}</div>
      @if($bill->status === 'paid')
        <span class="badge bg-success mt-1">PAID IN FULL</span>
      @elseif($bill->status === 'partial')
        <span class="badge bg-warning text-dark mt-1">PARTIAL PAYMENT</span>
      @else
        <span class="badge bg-danger mt-1">UNPAID</span>
      @endif
    </div>
  </div>

  <!-- Patient Details Bar -->
  <div class="patient-bar">
    <div class="row g-2">
      <div class="col-6 col-md-3">
        <small class="text-muted d-block">MRN #</small>
        <strong>{{ $bill->patient->mrn ?? '' }}</strong>
      </div>
      <div class="col-6 col-md-3">
        <small class="text-muted d-block">Patient Name</small>
        <strong>{{ $bill->patient->full_name ?? '' }}</strong>
      </div>
      <div class="col-6 col-md-3">
        <small class="text-muted d-block">Age / Gender / Blood</small>
        <span>{{ $bill->patient->age ?? 'N/A' }}y / {{ ucfirst($bill->patient->gender ?? '') }} / <strong>{{ $bill->patient->blood_group ?? '-' }}</strong></span>
      </div>
      <div class="col-6 col-md-3 text-md-end">
        <small class="text-muted d-block">Contact Phone</small>
        <strong>{{ $bill->patient->phone ?? '' }}</strong>
      </div>
    </div>
  </div>

  <!-- Itemized Table -->
  <table class="table table-bordered align-middle mb-4">
    <thead class="table-light">
      <tr>
        <th style="width: 5%;">#</th>
        <th style="width: 50%;">Service Description</th>
        <th style="width: 15%;">Category</th>
        <th style="width: 10%;" class="text-center">Qty</th>
        <th style="width: 10%;" class="text-end">Rate</th>
        <th style="width: 10%;" class="text-end">Total</th>
      </tr>
    </thead>
    <tbody>
      @foreach($bill->items as $idx => $it)
        <tr>
          <td>{{ $idx + 1 }}</td>
          <td class="fw-semibold">{{ $it->description }}</td>
          <td><span class="badge bg-light text-dark border text-capitalize">{{ str_replace('_', ' ', $it->item_type) }}</span></td>
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
          <th colspan="5" class="text-end">Sales Tax / VAT:</th>
          <td class="text-end">+ ${{ number_format($bill->tax, 2) }}</td>
        </tr>
      @endif
      <tr class="table-light fs-5">
        <th colspan="5" class="text-end text-dark">Net Payable:</th>
        <td class="text-end text-primary fw-bolder">${{ number_format($bill->total_amount, 2) }}</td>
      </tr>
      <tr>
        <th colspan="5" class="text-end text-success">Total Amount Paid:</th>
        <td class="text-end text-success fw-bold">${{ number_format($bill->paid_amount, 2) }}</td>
      </tr>
      <tr class="table-warning">
        <th colspan="5" class="text-end text-danger fw-bold">Balance Due:</th>
        <td class="text-end text-danger fw-bolder">${{ number_format($bill->due_amount, 2) }}</td>
      </tr>
    </tfoot>
  </table>

  <!-- Payments Received Summary -->
  @if($bill->payments->count() > 0)
    <div class="mb-4">
      <h6 class="fw-bold small text-muted text-uppercase mb-2">Payment Receipts Applied:</h6>
      <table class="table table-sm table-bordered">
        <thead class="table-light">
          <tr>
            <th>Receipt #</th>
            <th>Date</th>
            <th>Mode</th>
            <th>Reference</th>
            <th class="text-end">Amount</th>
          </tr>
        </thead>
        <tbody>
          @foreach($bill->payments as $pay)
            <tr>
              <td>{{ $pay->receipt_number }}</td>
              <td>{{ \Carbon\Carbon::parse($pay->payment_date)->format('d-M-Y') }}</td>
              <td class="text-capitalize">{{ str_replace('_', ' ', $pay->payment_method) }}</td>
              <td><code>{{ $pay->transaction_reference ?: 'Direct' }}</code></td>
              <td class="text-end fw-bold">${{ number_format($pay->amount, 2) }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @endif

  <!-- Terms & Signatures -->
  <div class="d-flex justify-content-between align-items-end mt-5 pt-4">
    <div class="text-muted small">
      <div>Terms & Conditions:</div>
      <div>1. Goods/Medicines once sold cannot be returned without original receipt.</div>
      <div>2. Computer generated tax invoice; does not require physical signature.</div>
    </div>
    <div class="text-end">
      <div style="border-top: 1px dashed #777; width: 200px; padding-top: 5px;">
        <strong>Authorized Cashier</strong>
        <div class="small text-muted">Hospital Billing Counter</div>
      </div>
    </div>
  </div>
</div>

</body>
</html>
