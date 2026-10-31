<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Diagnostic Report - {{ $order->order_number }}</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #222; background: #f8f9fa; }
    .report-container { max-width: 820px; margin: 20px auto; background: #fff; padding: 35px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
    .hospital-header { border-bottom: 2px solid #0056b3; padding-bottom: 15px; margin-bottom: 20px; }
    .patient-bar { background: #f1f5f9; padding: 12px 16px; border-radius: 6px; margin-bottom: 25px; }
    .result-box { background: #fafafa; border: 1px solid #e2e8f0; border-radius: 6px; padding: 20px; font-family: 'Consolas', monospace; white-space: pre-wrap; }
    @media print {
      body { background: #fff; }
      .report-container { box-shadow: none; margin: 0; padding: 20px; max-width: 100%; }
      .no-print { display: none !important; }
    }
  </style>
</head>
<body>

<div class="container text-center my-3 no-print">
  <button onclick="window.print()" class="btn btn-primary px-4 me-2">
    <i class="fa fa-print me-1"></i> Print Diagnostic Report
  </button>
  <button onclick="window.close()" class="btn btn-outline-secondary">Close Window</button>
</div>

<div class="report-container">
  <!-- Header -->
  <div class="hospital-header d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center">
      <div class="me-3 text-primary">
        <i class="fa-solid fa-flask-vial" style="font-size: 3rem;"></i>
      </div>
      <div>
        <h3 class="fw-bold mb-0 text-primary">AL-SHIFA DIAGNOSTIC PATHOLOGY LAB</h3>
        <p class="text-muted small mb-0">Department of Laboratory Medicine & Molecular Diagnostics</p>
        <small class="text-muted">ISO 15189 Certified Reference Laboratory | Islamabad</small>
      </div>
    </div>
    <div class="text-end">
      <span class="badge bg-success fs-6">VERIFIED REPORT</span>
      <div class="small text-muted mt-1">Order: <code>{{ $order->order_number }}</code></div>
    </div>
  </div>

  <!-- Patient Details Bar -->
  <div class="patient-bar">
    <div class="row g-2">
      <div class="col-6 col-md-3">
        <small class="text-muted d-block">MRN #</small>
        <strong>{{ $order->patient->mrn ?? '' }}</strong>
      </div>
      <div class="col-6 col-md-3">
        <small class="text-muted d-block">Patient Name</small>
        <strong>{{ $order->patient->full_name ?? '' }}</strong>
      </div>
      <div class="col-6 col-md-3">
        <small class="text-muted d-block">Age / Gender / Blood</small>
        <span>{{ $order->patient->age ?? 'N/A' }}y / {{ ucfirst($order->patient->gender ?? '') }} / <strong>{{ $order->patient->blood_group ?? '-' }}</strong></span>
      </div>
      <div class="col-6 col-md-3 text-md-end">
        <small class="text-muted d-block">Order Date</small>
        <strong>{{ \Carbon\Carbon::parse($order->order_date)->format('d-M-Y') }}</strong>
      </div>
    </div>
    <div class="row g-2 mt-2 pt-2 border-top small text-muted">
      <div class="col-md-6">
        Referring Physician: <strong>Dr. {{ $order->doctor->first_name ?? '' }} {{ $order->doctor->last_name ?? '' }}</strong> ({{ $order->doctor->department->name ?? 'Dept' }})
      </div>
      <div class="col-md-6 text-md-end">
        Specimen Type: <strong>{{ $order->labTest->sample_type ?? 'Whole Blood / Serum' }}</strong>
      </div>
    </div>
  </div>

  <!-- Test Title -->
  <div class="border-bottom pb-2 mb-3">
    <h4 class="fw-bold text-dark mb-0">{{ $order->labTest->test_name ?? 'Diagnostic Test' }}</h4>
    <small class="text-muted">Test Code: <code>{{ $order->labTest->test_code ?? '' }}</code> | Category: {{ $order->labTest->category ?? '' }}</small>
  </div>

  <!-- Standard Biological Reference -->
  @if($order->labTest->reference_range)
    <div class="alert alert-info py-2 small mb-3">
      <strong>Standard Biological Reference Interval:</strong> {{ $order->labTest->reference_range }}
    </div>
  @endif

  <!-- Result Values -->
  <h6 class="fw-bold text-muted small text-uppercase mb-2">Diagnostic Quantitative & Qualitative Findings:</h6>
  <div class="result-box mb-4">
{{ $order->result_value ?: 'No result values entered.' }}
  </div>

  @if($order->result_notes)
    <div class="p-3 bg-light rounded mb-4">
      <strong class="d-block small text-muted text-uppercase mb-1">Pathologist Opinion & Clinical Remarks:</strong>
      <p class="mb-0 small">{{ $order->result_notes }}</p>
    </div>
  @endif

  <!-- Authorization Signatures -->
  <div class="d-flex justify-content-between align-items-end mt-5 pt-4">
    <div class="text-muted small">
      <div>Report Generated: {{ \Carbon\Carbon::parse($order->result_date ?? now())->format('d-M-Y h:i A') }}</div>
      <div>Electronic verification token: <code>{{ strtoupper(md5($order->id . $order->order_number)) }}</code></div>
    </div>
    <div class="text-end">
      <div style="border-top: 1px dashed #777; width: 220px; padding-top: 5px;">
        <strong>Dr. Sarah Khan, MD</strong>
        <div class="small text-muted">Consultant Pathologist & Lab Director</div>
      </div>
    </div>
  </div>
</div>

</body>
</html>
