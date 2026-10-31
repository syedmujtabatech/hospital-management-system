@extends('layouts.app')

@section('title', 'Patient EMR Records')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h4 class="fw-bold mb-1"><i class="bx bx-user me-2 text-primary"></i> Patient Directory (EMR)</h4>
    <p class="text-muted mb-0">Search, manage and review complete Electronic Medical Records</p>
  </div>
  <a href="{{ route('patients.create') }}" class="btn btn-primary">
    <i class="bx bx-user-plus me-1"></i> Register New Patient
  </a>
</div>

<!-- Search & Filter Card -->
<div class="card mb-4">
  <div class="card-body">
    <form action="{{ route('patients.index') }}" method="GET" class="row g-3">
      <div class="col-md-5">
        <label class="form-label fw-semibold">Search by MRN, Name or Phone</label>
        <div class="input-group">
          <span class="input-group-text"><i class="bx bx-search"></i></span>
          <input type="text" name="search" class="form-control" placeholder="e.g. MRN-2026-0001 or Ahmed or 0300..." value="{{ request('search') }}">
        </div>
      </div>
      <div class="col-md-3">
        <label class="form-label fw-semibold">Blood Group</label>
        <select name="blood_group" class="form-select">
          <option value="">All Blood Groups</option>
          @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)
            <option value="{{ $bg }}" {{ request('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label fw-semibold">Gender</label>
        <select name="gender" class="form-select">
          <option value="">All</option>
          <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Male</option>
          <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Female</option>
          <option value="other" {{ request('gender') == 'other' ? 'selected' : '' }}>Other</option>
        </select>
      </div>
      <div class="col-md-2 d-flex align-items-end gap-2">
        <button type="submit" class="btn btn-primary w-100"><i class="bx bx-filter-alt"></i> Filter</button>
        @if(request()->hasAny(['search', 'blood_group', 'gender']))
          <a href="{{ route('patients.index') }}" class="btn btn-outline-secondary" title="Clear Filters"><i class="bx bx-reset"></i></a>
        @endif
      </div>
    </form>
  </div>
</div>

<!-- Patients Table Card -->
<div class="card">
  <div class="table-responsive text-nowrap">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>MRN (Medical Record #)</th>
          <th>Full Name</th>
          <th>Age / Gender</th>
          <th>Blood Group</th>
          <th>Phone</th>
          <th>Emergency Contact</th>
          <th class="text-center">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($patients as $patient)
          <tr>
            <td>
              <span class="mrn-badge">{{ $patient->mrn }}</span>
            </td>
            <td>
              <a href="{{ route('patients.show', $patient->id) }}" class="fw-bold text-primary">
                {{ $patient->full_name }}
              </a>
              @if($patient->allergies)
                <span class="badge bg-label-danger ms-1" title="Allergies: {{ $patient->allergies }}">
                  <i class="bx bx-error-alt"></i> Allergy
                </span>
              @endif
            </td>
            <td>{{ $patient->age ?? 'N/A' }} yrs / <span class="text-capitalize">{{ $patient->gender }}</span></td>
            <td>
              @if($patient->blood_group)
                <span class="badge bg-label-danger fw-bold">{{ $patient->blood_group }}</span>
              @else
                <span class="text-muted">-</span>
              @endif
            </td>
            <td>{{ $patient->phone }}</td>
            <td>
              <small>{{ $patient->emergency_contact_name ?? '-' }}</small>
              <div class="small text-muted">{{ $patient->emergency_contact_phone ?? '' }}</div>
            </td>
            <td class="text-center">
              <div class="btn-group">
                <a href="{{ route('patients.show', $patient->id) }}" class="btn btn-sm btn-outline-primary" title="View 360 EMR Profile">
                  <i class="bx bx-folder-open"></i> EMR
                </a>
                <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                  <i class="bx bx-edit"></i>
                </a>
                <a href="{{ route('appointments.create', ['patient_id' => $patient->id]) }}" class="btn btn-sm btn-outline-info" title="Book Appointment">
                  <i class="bx bx-calendar-plus"></i>
                </a>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center py-5 text-muted">
              <i class="bx bx-user-x fs-1 d-block mb-2"></i>
              No patients found matching your search.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($patients->hasPages())
    <div class="card-footer py-2">
      {{ $patients->links('pagination::bootstrap-5') }}
    </div>
  @endif
</div>
@endsection
