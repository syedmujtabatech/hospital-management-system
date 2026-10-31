@extends('layouts.app')

@section('title', 'Inpatient (IPD) Admissions')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h4 class="fw-bold mb-1"><i class="bx bx-bed me-2 text-primary"></i> Inpatient (IPD) Admissions</h4>
    <p class="text-muted mb-0">Admitted hospital patients, ward allotments, and discharge summaries</p>
  </div>
  <div class="d-flex gap-2">
    <a href="{{ route('ipd.rooms') }}" class="btn btn-outline-warning">
      <i class="bx bx-grid-alt me-1"></i> Visual Bed Matrix
    </a>
    <a href="{{ route('ipd.create') }}" class="btn btn-primary">
      <i class="bx bx-user-plus me-1"></i> Admit New Patient
    </a>
  </div>
</div>

<!-- Filters -->
<div class="card mb-4">
  <div class="card-body">
    <div class="d-flex gap-2">
      <a href="{{ route('ipd.index', ['status' => 'admitted']) }}" class="btn btn-sm {{ request('status', 'admitted') === 'admitted' ? 'btn-primary' : 'btn-outline-secondary' }}">
        <i class="bx bx-user-check me-1"></i> Currently Admitted
      </a>
      <a href="{{ route('ipd.index', ['status' => 'discharged']) }}" class="btn btn-sm {{ request('status') === 'discharged' ? 'btn-primary' : 'btn-outline-secondary' }}">
        <i class="bx bx-check-double me-1"></i> Discharged History
      </a>
    </div>
  </div>
</div>

<!-- Admissions Table -->
<div class="card">
  <div class="table-responsive text-nowrap">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>Admission #</th>
          <th>Patient Name (MRN)</th>
          <th>Ward / Room #</th>
          <th>Attending Doctor</th>
          <th>Admission Date</th>
          <th>Daily Rate</th>
          <th>Status</th>
          <th class="text-center">Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse($admissions as $adm)
          <tr>
            <td><strong class="text-primary">{{ $adm->admission_number }}</strong></td>
            <td>
              @if($adm->patient)
                <a href="{{ route('patients.show', $adm->patient->id) }}" class="fw-bold text-dark">
                  {{ $adm->patient->full_name }}
                </a>
                <div class="small text-muted">{{ $adm->patient->mrn }}</div>
              @else
                <span class="text-muted">N/A</span>
              @endif
            </td>
            <td>
              <span class="badge bg-label-primary fs-6">{{ $adm->room->room_number ?? 'Bed' }}</span>
              <small class="text-muted d-block">{{ $adm->room->ward_name ?? '' }} ({{ ucfirst($adm->room->room_type ?? '') }})</small>
            </td>
            <td>
              Dr. {{ $adm->doctor->first_name ?? '' }} {{ $adm->doctor->last_name ?? '' }}
              <small class="text-muted d-block">{{ $adm->doctor->department->name ?? '' }}</small>
            </td>
            <td>
              {{ \Carbon\Carbon::parse($adm->admission_date)->format('M d, Y') }}
              <div class="small text-muted">{{ \Carbon\Carbon::parse($adm->admission_date)->diffForHumans() }}</div>
            </td>
            <td><strong>${{ number_format($adm->daily_rate, 2) }}</strong>/day</td>
            <td>
              @if($adm->status === 'admitted')
                <span class="badge bg-success">Admitted</span>
              @else
                <span class="badge bg-secondary">Discharged</span>
                <div class="small text-muted">{{ \Carbon\Carbon::parse($adm->discharge_date)->format('M d, Y') }}</div>
              @endif
            </td>
            <td class="text-center">
              @if($adm->status === 'admitted')
                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#dischargeModal{{ $adm->id }}">
                  <i class="bx bx-log-out me-1"></i> Discharge
                </button>

                <!-- Discharge Modal -->
                <div class="modal fade" id="dischargeModal{{ $adm->id }}" tabindex="-1" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content text-start">
                      <form action="{{ route('ipd.discharge', $adm->id) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                          <h5 class="modal-title fw-bold">Discharge Patient - {{ $adm->patient->full_name ?? '' }}</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                          <p class="mb-3 text-muted">
                            Discharging will release <strong>Bed {{ $adm->room->room_number ?? '' }}</strong> back to Available status.
                          </p>
                          <div class="mb-3">
                            <label class="form-label fw-semibold">Discharge Date</label>
                            <input type="date" name="discharge_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                          </div>
                          <div class="mb-3">
                            <label class="form-label fw-semibold">Patient Discharge Condition</label>
                            <select name="discharge_condition" class="form-select">
                              <option value="Recovered / Stable">Recovered / Stable</option>
                              <option value="Improved">Improved</option>
                              <option value="Transferred to another facility">Transferred to another facility</option>
                              <option value="Discharged against medical advice (LAMA)">Discharged against medical advice (LAMA)</option>
                            </select>
                          </div>
                          <div class="mb-3">
                            <label class="form-label fw-semibold">Discharge Summary & Instructions</label>
                            <textarea name="discharge_notes" rows="3" class="form-control" placeholder="Post-discharge medication, follow-up visit dates..."></textarea>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                          <button type="submit" class="btn btn-danger">Confirm Discharge</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              @else
                <span class="text-muted small">Completed</span>
              @endif
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" class="text-center py-5 text-muted">
              <i class="bx bx-bed fs-1 d-block mb-2"></i>
              No inpatient admissions found.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($admissions->hasPages())
    <div class="card-footer py-2">
      {{ $admissions->links('pagination::bootstrap-5') }}
    </div>
  @endif
</div>
@endsection
