@extends('layouts.app')

@section('title', 'Add New Doctor')

@section('content')
<div class="mb-3">
  <a href="{{ route('doctors.index') }}" class="btn btn-outline-secondary btn-sm mb-2">
    <i class="bx bx-arrow-back me-1"></i> Back to Doctor Directory
  </a>
  <h4 class="fw-bold mb-1"><i class="bx bx-plus-medical me-2 text-primary"></i> Add New Doctor</h4>
  <p class="text-muted mb-0">Create doctor profile and generate doctor portal login credentials.</p>
</div>

<div class="card">
  <div class="card-body p-4">
    <form action="{{ route('doctors.store') }}" method="POST">
      @csrf

      <h5 class="fw-bold text-primary mb-3 border-bottom pb-2">
        <i class="bx bx-user me-1"></i> Doctor Basic Info
      </h5>
      <div class="row g-3 mb-4">
        <div class="col-md-4">
          <label class="form-label fw-semibold">First Name <span class="text-danger">*</span></label>
          <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}" required placeholder="e.g. Salman">
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Last Name <span class="text-danger">*</span></label>
          <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}" required placeholder="e.g. Farooq">
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Phone <span class="text-danger">*</span></label>
          <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required placeholder="e.g. +92 300 1122334">
        </div>

        <div class="col-md-6">
          <label class="form-label fw-semibold">Portal Login Email <span class="text-danger">*</span></label>
          <input type="email" name="email" class="form-control" value="{{ old('email') }}" required placeholder="e.g. dr.salman@hospital.com">
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Portal Login Password <span class="text-danger">*</span></label>
          <input type="password" name="password" class="form-control" required placeholder="Minimum 6 characters" value="doctor123">
        </div>
      </div>

      <h5 class="fw-bold text-primary mb-3 border-bottom pb-2">
        <i class="bx bx-award me-1"></i> Medical Specialization & Licensing
      </h5>
      <div class="row g-3 mb-4">
        <div class="col-md-4">
          <label class="form-label fw-semibold">Department <span class="text-danger">*</span></label>
          <select name="department_id" class="form-select" required>
            <option value="">Select Department</option>
            @foreach($departments as $dept)
              <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Specialization <span class="text-danger">*</span></label>
          <input type="text" name="specialization" class="form-control" value="{{ old('specialization') }}" required placeholder="e.g. Interventional Cardiology">
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Degree / Qualification <span class="text-danger">*</span></label>
          <input type="text" name="qualification" class="form-control" value="{{ old('qualification') }}" required placeholder="e.g. MBBS, FCPS (Cardiology)">
        </div>

        <div class="col-md-4">
          <label class="form-label fw-semibold">Medical License # (PMDC) <span class="text-danger">*</span></label>
          <input type="text" name="license_number" class="form-control" value="{{ old('license_number') }}" required placeholder="e.g. PMDC-89764-A">
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Consultation Fee ($) <span class="text-danger">*</span></label>
          <input type="number" step="0.01" name="consultation_fee" class="form-control" value="{{ old('consultation_fee', '50.00') }}" required>
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">OPD Clinic / Room #</label>
          <input type="text" name="room_no" class="form-control" value="{{ old('room_no') }}" placeholder="e.g. Room 204, 2nd Floor">
        </div>

        <div class="col-12">
          <label class="form-label fw-semibold">Professional Bio & Experience</label>
          <textarea name="bio" rows="2" class="form-control" placeholder="Brief summary of doctor's background, clinical fellowship, and achievements">{{ old('bio') }}</textarea>
        </div>
      </div>

      <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('doctors.index') }}" class="btn btn-outline-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary px-4">
          <i class="bx bx-check-circle me-1"></i> Register Doctor
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
