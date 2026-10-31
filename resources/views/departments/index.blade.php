@extends('layouts.app')

@section('title', 'Hospital Departments')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h4 class="fw-bold mb-1"><i class="bx bx-buildings me-2 text-primary"></i> Medical Departments & Specialties</h4>
    <p class="text-muted mb-0">Clinical specialty divisions, department heads, and staffing statistics</p>
  </div>
  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDeptModal">
    <i class="bx bx-plus me-1"></i> Add Department
  </button>
</div>

<div class="row g-4">
  @forelse($departments as $dept)
    <div class="col-md-4 col-sm-6">
      <div class="card h-100 shadow-none border">
        <div class="card-body p-4">
          <div class="d-flex align-items-center mb-3">
            <div class="avatar avatar-md bg-label-primary rounded p-2 me-3">
              <i class="fa-solid fa-hospital text-primary fs-3"></i>
            </div>
            <div>
              <h5 class="fw-bold mb-0 text-dark">{{ $dept->name }}</h5>
              <small class="text-muted">{{ $dept->doctors_count }} Active Physicians</small>
            </div>
          </div>
          <p class="text-muted small mb-3">{{ $dept->description ?: 'Comprehensive clinical diagnostics, outpatient consultation, and specialized inpatient care.' }}</p>
          <div class="border-top pt-2 d-flex justify-content-between align-items-center small">
            <span class="text-muted">Head of Dept:</span>
            <strong class="text-primary">{{ $dept->head_of_department ?: 'Appointed Consultant' }}</strong>
          </div>
        </div>
      </div>
    </div>
  @empty
    <div class="col-12 text-center py-5 text-muted">
      No departments created yet.
    </div>
  @endforelse
</div>

<!-- Add Department Modal -->
<div class="modal fade" id="addDeptModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('departments.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title fw-bold">Create Medical Department</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label fw-semibold">Department Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" placeholder="e.g. Oncology, Dermatology" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Head of Department (HOD)</label>
            <input type="text" name="head_of_department" class="form-control" placeholder="e.g. Prof. Dr. Tariq Aziz">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Description / Specialty Scope</label>
            <textarea name="description" rows="3" class="form-control" placeholder="Brief clinical scope of services..."></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Department</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
