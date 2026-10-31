<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Prescription - {{ $prescription->prescription_number }}</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #333; background: #f8f9fa; }
    .rx-container { max-width: 800px; margin: 20px auto; background: #fff; padding: 35px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
    .hospital-header { border-bottom: 2px solid #2b548b; padding-bottom: 15px; margin-bottom: 20px; }
    .rx-symbol { font-family: 'Times New Roman', serif; font-size: 2.5rem; font-weight: bold; color: #2b548b; }
    .patient-bar { background: #f1f5f9; padding: 12px 16px; border-radius: 6px; margin-bottom: 25px; }
    .doc-sig { margin-top: 60px; border-top: 1px dashed #777; width: 220px; text-align: center; padding-top: 8px; }
    @media print {
      body { background: #fff; }
      .rx-container { box-shadow: none; margin: 0; padding: 20px; max-width: 100%; }
      .no-print { display: none !important; }
    }
  </style>
</head>
<body>

<div class="container text-center my-3 no-print">
  <button onclick="window.print()" class="btn btn-primary px-4 me-2">
    <i class="fa fa-print me-1"></i> Print Prescription
  </button>
  <button onclick="window.close()" class="btn btn-outline-secondary">Close Window</button>
</div>

<div class="rx-container">
  <!-- Hospital Header -->
  <div class="hospital-header d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center">
      <div class="me-3 text-primary">
        <i class="fa-solid fa-hospital-user" style="font-size: 3rem;"></i>
      </div>
      <div>
        <h3 class="fw-bold mb-0 text-primary">AL-SHIFA INTERNATIONAL HOSPITAL</h3>
        <p class="text-muted small mb-0">H-8/4, Islamabad | UAN: +92 (51) 111-222-333 | Emergency: 1122</p>
        <small class="text-muted">ISO 9001:2015 & JCI Accredited Medical Facility</small>
      </div>
    </div>
    <div class="text-end">
      <h5 class="fw-bold mb-0 text-dark">Dr. {{ $prescription->doctor->first_name ?? '' }} {{ $prescription->doctor->last_name ?? '' }}</h5>
      <div class="text-primary fw-semibold small">{{ $prescription->doctor->qualification ?? '' }}</div>
      <div class="text-muted small">{{ $prescription->doctor->specialization ?? '' }}</div>
      <div class="small">PMDC #: <code>{{ $prescription->doctor->license_number ?? '' }}</code></div>
    </div>
  </div>

  <!-- Patient Details Bar -->
  <div class="patient-bar">
    <div class="row g-2">
      <div class="col-6 col-md-3">
        <small class="text-muted d-block">MRN #</small>
        <strong>{{ $prescription->patient->mrn ?? '' }}</strong>
      </div>
      <div class="col-6 col-md-3">
        <small class="text-muted d-block">Patient Name</small>
        <strong>{{ $prescription->patient->full_name ?? '' }}</strong>
      </div>
      <div class="col-6 col-md-3">
        <small class="text-muted d-block">Age / Gender / Blood</small>
        <span>{{ $prescription->patient->age ?? 'N/A' }}y / {{ ucfirst($prescription->patient->gender ?? '') }} / <strong>{{ $prescription->patient->blood_group ?? '-' }}</strong></span>
      </div>
      <div class="col-6 col-md-3 text-md-end">
        <small class="text-muted d-block">Date</small>
        <strong>{{ \Carbon\Carbon::parse($prescription->created_at)->format('d-M-Y') }}</strong>
      </div>
    </div>
    @if($prescription->patient->allergies)
      <div class="mt-2 pt-2 border-top text-danger small">
        <i class="fa fa-triangle-exclamation me-1"></i> <strong>ALERT - Drug Allergies:</strong> {{ $prescription->patient->allergies }}
      </div>
    @endif
  </div>

  @if($prescription->encounter)
    <div class="row mb-3 pb-2 border-bottom small text-muted">
      <div class="col-md-8">
        <strong>Diagnosis:</strong> <span class="text-dark">{{ $prescription->encounter->diagnosis }}</span>
      </div>
      <div class="col-md-4 text-md-end">
        <span>BP: <strong>{{ $prescription->encounter->blood_pressure ?: '-' }}</strong> | Pulse: <strong>{{ $prescription->encounter->pulse_rate ?: '-' }}</strong> | Wt: <strong>{{ $prescription->encounter->weight ?: '-' }}kg</strong></span>
      </div>
    </div>
  @endif

  <!-- Rx Section -->
  <div class="rx-symbol mb-2">℞</div>

  <table class="table table-borderless align-middle mb-4">
    <thead>
      <tr class="border-bottom">
        <th style="width: 5%;">#</th>
        <th style="width: 40%;">Medicine Name & Strength</th>
        <th style="width: 20%;">Dosage / Frequency</th>
        <th style="width: 15%;">Duration</th>
        <th style="width: 20%;">Instructions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($prescription->items as $idx => $item)
        <tr>
          <td>{{ $idx + 1 }}.</td>
          <td>
            <div class="fw-bold">{{ $item->medicine->name ?? 'Medicine' }}</div>
            <small class="text-muted">{{ $item->medicine->generic_name ?? '' }}</small>
          </td>
          <td>
            <strong>{{ $item->dosage }}</strong>
            <div class="small text-muted">{{ $item->frequency }}</div>
          </td>
          <td>{{ $item->duration }}</td>
          <td><span class="badge bg-light text-dark border">{{ $item->instructions ?: 'As directed' }}</span></td>
        </tr>
      @empty
        <tr>
          <td colspan="5" class="text-center text-muted py-3">No specific medicines added.</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  @if($prescription->clinical_notes)
    <div class="p-3 bg-light rounded mb-4">
      <strong class="d-block small text-muted text-uppercase mb-1">Doctor Advice / Lifestyle Notes:</strong>
      <p class="mb-0 small">{{ $prescription->clinical_notes }}</p>
    </div>
  @endif

  <!-- Footer & Signature -->
  <div class="d-flex justify-content-between align-items-end mt-5 pt-4">
    <div class="text-muted small">
      <div>Prescription ID: <code>{{ $prescription->prescription_number }}</code></div>
      <div>Please present this prescription at the pharmacy for verification.</div>
    </div>
    <div class="text-end">
      <div class="doc-sig ms-auto">
        <strong>Dr. {{ $prescription->doctor->first_name ?? '' }} {{ $prescription->doctor->last_name ?? '' }}</strong>
        <div class="small text-muted">Consultant Physician</div>
      </div>
    </div>
  </div>
</div>

</body>
</html>
