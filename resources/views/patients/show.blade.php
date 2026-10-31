@extends('layouts.app')

@section('title', 'Patient 360 EMR - ' . $patient->full_name)

@section('content')
<div class="mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
  <div>
    <a href="{{ route('patients.index') }}" class="btn btn-outline-secondary btn-sm mb-1">
      <i class="bx bx-arrow-back me-1"></i> Back to Directory
    </a>
    <h4 class="fw-bold mb-0">
      <span class="mrn-badge me-2">{{ $patient->mrn }}</span>
      {{ $patient->full_name }}
    </h4>
  </div>

  <div class="d-flex gap-2 flex-wrap">
    <a href="{{ route('opd.create', ['patient_id' => $patient->id]) }}" class="btn btn-success btn-sm">
      <i class="bx bx-notepad me-1"></i> OPD Checkup
    </a>
    <a href="{{ route('appointments.create', ['patient_id' => $patient->id]) }}" class="btn btn-info btn-sm text-white">
      <i class="bx bx-calendar-plus me-1"></i> Book Appointment
    </a>
    <a href="{{ route('ipd.create', ['patient_id' => $patient->id]) }}" class="btn btn-warning btn-sm">
      <i class="bx bx-bed me-1"></i> Admit (IPD)
    </a>
    <a href="{{ route('laboratory.order', ['patient_id' => $patient->id]) }}" class="btn btn-secondary btn-sm">
      <i class="bx bx-test-tube me-1"></i> Order Lab
    </a>
    <a href="{{ route('billing.create', ['patient_id' => $patient->id]) }}" class="btn btn-primary btn-sm">
      <i class="bx bx-receipt me-1"></i> Bill
    </a>
  </div>
</div>

<!-- Patient Summary Banner -->
<div class="card mb-4">
  <div class="card-body p-4">
    <div class="row align-items-center">
      <div class="col-md-2 text-center border-end">
        <div class="avatar avatar-xl bg-label-primary rounded-circle mx-auto mb-2" style="width: 70px; height: 70px; display: flex; align-items: center; justify-content: center;">
          <i class="bx bx-user fs-1 text-primary"></i>
        </div>
        <span class="badge bg-label-danger fw-bold fs-6">{{ $patient->blood_group ?? 'Blood N/A' }}</span>
      </div>

      <div class="col-md-6 border-end">
        <h5 class="fw-bold mb-1">{{ $patient->full_name }}</h5>
        <div class="text-muted mb-2">
          <span class="text-capitalize">{{ $patient->gender }}</span> | {{ $patient->age ?? 'N/A' }} Years Old
          @if($patient->date_of_birth)
            ({{ \Carbon\Carbon::parse($patient->date_of_birth)->format('d M Y') }})
          @endif
        </div>
        <div class="small mb-1"><i class="bx bx-phone me-1 text-primary"></i> {{ $patient->phone }}</div>
        @if($patient->email)
          <div class="small mb-1"><i class="bx bx-envelope me-1 text-primary"></i> {{ $patient->email }}</div>
        @endif
        @if($patient->address)
          <div class="small text-muted"><i class="bx bx-map-pin me-1 text-primary"></i> {{ $patient->address }}</div>
        @endif
      </div>

      <div class="col-md-4">
        <div class="mb-2">
          <span class="fw-semibold small text-danger"><i class="bx bx-error me-1"></i> Drug Allergies:</span>
          <div class="small fw-bold text-danger">{{ $patient->allergies ?: 'None Recorded' }}</div>
        </div>
        <div class="mb-2">
          <span class="fw-semibold small text-primary"><i class="bx bx-pulse me-1"></i> Chronic Conditions:</span>
          <div class="small">{{ $patient->chronic_conditions ?: 'None Recorded' }}</div>
        </div>
        <div>
          <span class="fw-semibold small text-muted"><i class="bx bx-user-voice me-1"></i> Emergency Contact:</span>
          <div class="small">{{ $patient->emergency_contact_name ?? '-' }} ({{ $patient->emergency_contact_phone ?? '-' }})</div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Tabs: EMR Details -->
<div class="nav-align-top mb-4">
  <ul class="nav nav-tabs nav-fill" role="tablist">
    <li class="nav-item">
      <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#tab-opd">
        <i class="bx bx-notepad me-1"></i> OPD Encounters ({{ $patient->encounters->count() }})
      </button>
    </li>
    <li class="nav-item">
      <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#tab-ipd">
        <i class="bx bx-bed me-1"></i> IPD Admissions ({{ $patient->admissions->count() }})
      </button>
    </li>
    <li class="nav-item">
      <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#tab-labs">
        <i class="bx bx-test-tube me-1"></i> Lab Reports ({{ $patient->labOrders->count() }})
      </button>
    </li>
    <li class="nav-item">
      <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#tab-billing">
        <i class="bx bx-receipt me-1"></i> Invoices & Bills ({{ $patient->bills->count() }})
      </button>
    </li>
  </ul>

  <div class="tab-content bg-white p-4 rounded-bottom shadow-sm">
    <!-- TAB 1: OPD Encounters -->
    <div class="tab-pane fade show active" id="tab-opd" role="tabpanel">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">Consultations & Prescriptions</h5>
        <a href="{{ route('opd.create', ['patient_id' => $patient->id]) }}" class="btn btn-sm btn-primary">
          <i class="bx bx-plus me-1"></i> New Encounter
        </a>
      </div>

      @forelse($patient->encounters as $enc)
        <div class="card border mb-3">
          <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
            <div>
              <span class="fw-bold text-primary">{{ \Carbon\Carbon::parse($enc->encounter_date)->format('M d, Y') }}</span>
              <span class="text-muted ms-2">| Doctor: Dr. {{ $enc->doctor->first_name ?? '' }} {{ $enc->doctor->last_name ?? '' }}</span>
            </div>
            @if($enc->prescription)
              <a href="{{ route('prescriptions.print', $enc->prescription->id) }}" target="_blank" class="btn btn-xs btn-outline-primary">
                <i class="bx bx-printer me-1"></i> Print Rx
              </a>
            @endif
          </div>
          <div class="card-body p-3">
            <!-- Vitals Badges -->
            <div class="d-flex flex-wrap gap-2 mb-3">
              <span class="badge bg-label-info"><i class="bx bx-heart"></i> BP: {{ $enc->blood_pressure ?: 'N/A' }}</span>
              <span class="badge bg-label-success"><i class="bx bx-pulse"></i> Pulse: {{ $enc->pulse_rate ?: 'N/A' }} bpm</span>
              <span class="badge bg-label-warning"><i class="bx bx-thermometer"></i> Temp: {{ $enc->temperature ?: 'N/A' }} °F</span>
              <span class="badge bg-label-primary"><i class="bx bx-wind"></i> SpO2: {{ $enc->spo2 ?: 'N/A' }}%</span>
              <span class="badge bg-label-secondary"><i class="bx bx-body"></i> Weight: {{ $enc->weight ?: 'N/A' }} kg</span>
            </div>

            <div class="row">
              <div class="col-md-6 mb-2">
                <strong class="text-muted small text-uppercase d-block">Symptoms / Complaints:</strong>
                <div>{{ $enc->symptoms }}</div>
              </div>
              <div class="col-md-6 mb-2">
                <strong class="text-muted small text-uppercase d-block">Clinical Diagnosis:</strong>
                <div class="text-primary fw-semibold">{{ $enc->diagnosis }}</div>
              </div>
            </div>

            @if($enc->prescription && $enc->prescription->items->count() > 0)
              <div class="mt-3 border-top pt-2">
                <strong class="text-muted small text-uppercase d-block mb-1">Prescribed Medicines:</strong>
                <div class="table-responsive">
                  <table class="table table-sm table-bordered mb-0">
                    <thead class="table-light">
                      <tr>
                        <th>Medicine</th>
                        <th>Dosage</th>
                        <th>Frequency</th>
                        <th>Duration</th>
                        <th>Instructions</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($enc->prescription->items as $item)
                        <tr>
                          <td class="fw-bold">{{ $item->medicine->name ?? 'Medicine' }}</td>
                          <td>{{ $item->dosage }}</td>
                          <td><span class="badge bg-label-primary">{{ $item->frequency }}</span></td>
                          <td>{{ $item->duration }}</td>
                          <td><small class="text-muted">{{ $item->instructions }}</small></td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            @endif
          </div>
        </div>
      @empty
        <div class="text-center py-4 text-muted">
          <i class="bx bx-notepad fs-1 d-block mb-2"></i>
          No OPD encounters recorded yet.
        </div>
      @endforelse
    </div>

    <!-- TAB 2: IPD Admissions -->
    <div class="tab-pane fade" id="tab-ipd" role="tabpanel">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">Inpatient Admission History</h5>
        <a href="{{ route('ipd.create', ['patient_id' => $patient->id]) }}" class="btn btn-sm btn-warning">
          <i class="bx bx-plus me-1"></i> Admit to Ward
        </a>
      </div>

      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th>Record #</th>
              <th>Bed / Ward</th>
              <th>Doctor</th>
              <th>Admitted</th>
              <th>Discharged</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @forelse($patient->admissions as $adm)
              <tr>
                <td class="fw-bold">{{ $adm->admission_number }}</td>
                <td>
                  <span class="badge bg-label-primary">{{ $adm->room->room_number ?? 'Bed' }}</span>
                  <small class="text-muted">{{ $adm->room->ward_name ?? '' }}</small>
                </td>
                <td>Dr. {{ $adm->doctor->last_name ?? '' }}</td>
                <td>{{ \Carbon\Carbon::parse($adm->admission_date)->format('M d, Y') }}</td>
                <td>
                  @if($adm->discharge_date)
                    {{ \Carbon\Carbon::parse($adm->discharge_date)->format('M d, Y') }}
                  @else
                    <span class="badge bg-label-warning">Still Admitted</span>
                  @endif
                </td>
                <td>
                  @if($adm->status === 'admitted')
                    <span class="badge bg-success">Active Inpatient</span>
                  @else
                    <span class="badge bg-secondary">Discharged</span>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center py-4 text-muted">No inpatient admissions on file.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- TAB 3: Lab Reports -->
    <div class="tab-pane fade" id="tab-labs" role="tabpanel">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">Pathology & Diagnostic Orders</h5>
        <a href="{{ route('laboratory.order', ['patient_id' => $patient->id]) }}" class="btn btn-sm btn-info text-white">
          <i class="bx bx-plus me-1"></i> Order New Test
        </a>
      </div>

      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th>Order #</th>
              <th>Test Name</th>
              <th>Order Date</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse($patient->labOrders as $lo)
              <tr>
                <td class="fw-bold">{{ $lo->order_number }}</td>
                <td>
                  <div class="fw-semibold">{{ $lo->labTest->test_name ?? 'Test' }}</div>
                  <small class="text-muted">{{ $lo->labTest->category ?? '' }}</small>
                </td>
                <td>{{ \Carbon\Carbon::parse($lo->order_date)->format('M d, Y') }}</td>
                <td>
                  @if($lo->status === 'completed')
                    <span class="badge bg-success">Completed</span>
                  @elseif($lo->status === 'in_progress')
                    <span class="badge bg-info">In Progress</span>
                  @else
                    <span class="badge bg-warning">Pending</span>
                  @endif
                </td>
                <td>
                  @if($lo->status === 'completed')
                    <a href="{{ route('laboratory.report', $lo->id) }}" target="_blank" class="btn btn-xs btn-outline-primary">
                      <i class="bx bx-printer me-1"></i> View Report
                    </a>
                  @else
                    <a href="{{ route('laboratory.result', $lo->id) }}" class="btn btn-xs btn-primary">
                      <i class="bx bx-edit me-1"></i> Enter Result
                    </a>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center py-4 text-muted">No lab orders placed.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- TAB 4: Billing History -->
    <div class="tab-pane fade" id="tab-billing" role="tabpanel">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">Hospital Invoices & Payments</h5>
        <a href="{{ route('billing.create', ['patient_id' => $patient->id]) }}" class="btn btn-sm btn-primary">
          <i class="bx bx-plus me-1"></i> Generate Invoice
        </a>
      </div>

      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th>Invoice #</th>
              <th>Date</th>
              <th>Total Amount</th>
              <th>Paid</th>
              <th>Due</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse($patient->bills as $bill)
              <tr>
                <td class="fw-bold">{{ $bill->bill_number }}</td>
                <td>{{ \Carbon\Carbon::parse($bill->bill_date)->format('M d, Y') }}</td>
                <td class="fw-bold">${{ number_format($bill->total_amount, 2) }}</td>
                <td class="text-success">${{ number_format($bill->paid_amount, 2) }}</td>
                <td class="text-danger">${{ number_format($bill->due_amount, 2) }}</td>
                <td>
                  @if($bill->status === 'paid')
                    <span class="badge bg-success">Paid</span>
                  @elseif($bill->status === 'partial')
                    <span class="badge bg-warning">Partial</span>
                  @else
                    <span class="badge bg-danger">Unpaid</span>
                  @endif
                </td>
                <td>
                  <a href="{{ route('billing.show', $bill->id) }}" class="btn btn-xs btn-outline-primary">
                    <i class="bx bx-show me-1"></i> View
                  </a>
                  <a href="{{ route('billing.print', $bill->id) }}" target="_blank" class="btn btn-xs btn-outline-secondary">
                    <i class="bx bx-printer"></i>
                  </a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center py-4 text-muted">No invoices generated for this patient.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
