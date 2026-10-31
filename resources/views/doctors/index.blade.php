@extends('layouts.app')

@section('title', 'Doctors & Specialists')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h4 class="fw-bold mb-1"><i class="bx bx-plus-medical me-2 text-primary"></i> Doctors & Medical Staff</h4>
    <p class="text-muted mb-0">Specialist directory, availability schedules, and consultation profiles</p>
  </div>
  <a href="{{ route('doctors.create') }}" class="btn btn-primary">
    <i class="bx bx-plus me-1"></i> Add New Doctor
  </a>
</div>

<!-- Department Filters -->
<div class="card mb-4">
  <div class="card-body">
    <form action="{{ route('doctors.index') }}" method="GET" class="row g-3">
      <div class="col-md-5">
        <label class="form-label fw-semibold">Search by Name or Specialization</label>
        <div class="input-group">
          <span class="input-group-text"><i class="bx bx-search"></i></span>
          <input type="text" name="search" class="form-control" placeholder="e.g. Dr. Salman, Cardiology..." value="{{ request('search') }}">
        </div>
      </div>
      <div class="col-md-4">
        <label class="form-label fw-semibold">Department</label>
        <select name="department_id" class="form-select">
          <option value="">All Departments</option>
          @foreach($departments as $dept)
            <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3 d-flex align-items-end gap-2">
        <button type="submit" class="btn btn-primary w-100"><i class="bx bx-filter"></i> Filter</button>
        @if(request()->hasAny(['search', 'department_id']))
          <a href="{{ route('doctors.index') }}" class="btn btn-outline-secondary" title="Clear Filters"><i class="bx bx-reset"></i></a>
        @endif
      </div>
    </form>
  </div>
</div>

<!-- Doctors Grid -->
<div class="row">
  @forelse($doctors as $doc)
    <div class="col-lg-4 col-md-6 mb-4">
      <div class="card h-100 border shadow-none">
        <div class="card-body text-center p-4">
          <div class="avatar avatar-xl bg-label-primary rounded-circle mx-auto mb-3" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
            <i class="fa-solid fa-user-doctor text-primary fs-1"></i>
          </div>
          <h5 class="fw-bold mb-1">Dr. {{ $doc->first_name }} {{ $doc->last_name }}</h5>
          <span class="badge bg-label-info mb-2">{{ $doc->department->name ?? 'General' }}</span>
          <div class="text-muted small mb-2 fw-semibold">{{ $doc->specialization }}</div>
          <p class="small text-muted mb-3">{{ $doc->qualification }}</p>

          <div class="d-flex justify-content-around border-top border-bottom py-2 mb-3 bg-light rounded">
            <div>
              <small class="text-muted d-block">Consultation Fee</small>
              <strong class="text-success">${{ number_format($doc->consultation_fee, 2) }}</strong>
            </div>
            <div class="border-start"></div>
            <div>
              <small class="text-muted d-block">OPD Room</small>
              <strong class="text-primary">{{ $doc->room_no ?: 'Room 101' }}</strong>
            </div>
          </div>

          <div class="d-flex justify-content-center gap-2">
            <a href="{{ route('appointments.create', ['doctor_id' => $doc->id]) }}" class="btn btn-sm btn-primary">
              <i class="bx bx-calendar-plus me-1"></i> Book Slot
            </a>
            <a href="{{ route('doctors.show', $doc->id) }}" class="btn btn-sm btn-outline-secondary">
              Profile
            </a>
            <a href="{{ route('doctors.edit', $doc->id) }}" class="btn btn-sm btn-outline-secondary">
              <i class="bx bx-edit"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  @empty
    <div class="col-12 text-center py-5">
      <p class="text-muted">No doctors found matching criteria.</p>
    </div>
  @endforelse
</div>

@if($doctors->hasPages())
  <div class="d-flex justify-content-center">
    {{ $doctors->links('pagination::bootstrap-5') }}
  </div>
@endif
@endsection
