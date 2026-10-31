@extends('layouts.app')

@section('title', 'Edit Doctor')

@section('content')
<div class="mb-3">
  <a href="{{ route('doctors.show', $doctor->id) }}" class="btn btn-outline-secondary btn-sm mb-2">
    <i class="bx bx-arrow-back me-1"></i> Back to Doctor Profile
  </a>
  <h4 class="fw-bold mb-1"><i class="bx bx-edit me-2 text-primary"></i> Edit Doctor Details</h4>
  <p class="text-muted mb-0">Update profile, fees, and consultation details for Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}</p>
</div>

<div class="card">
  <div class="card-body p-4">
    <form action="{{ route('doctors.update', $doctor->id) }}" method="POST">
      @csrf
      @method('PUT')

      <div class="row g-3 mb-4">
        <div class="col-md-4">
          <label class="form-label fw-semibold">First Name <span class="text-danger">*</span></label>
          <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $doctor->first_name) }}" required>
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Last Name <span class="text-danger">*</span></label>
          <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $doctor->last_name) }}" required>
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Phone <span class="text-danger">*</span></label>
          <input type="text" name="phone" class="form-control" value="{{ old('phone', $doctor->phone) }}" required>
        </div>

        <div class="col-md-4">
          <label class="form-label fw-semibold">Department <span class="text-danger">*</span></label>
          <select name="department_id" class="form-select" required>
            @foreach($departments as $dept)
              <option value="{{ $dept->id }}" {{ old('department_id', $doctor->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Specialization <span class="text-danger">*</span></label>
          <input type="text" name="specialization" class="form-control" value="{{ old('specialization', $doctor->specialization) }}" required>
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Degree / Qualification <span class="text-danger">*</span></label>
          <input type="text" name="qualification" class="form-control" value="{{ old('qualification', $doctor->qualification) }}" required>
        </div>

        <div class="col-md-4">
          <label class="form-label fw-semibold">Consultation Fee ($) <span class="text-danger">*</span></label>
          <input type="number" step="0.01" name="consultation_fee" class="form-control" value="{{ old('consultation_fee', $doctor->consultation_fee) }}" required>
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">OPD Clinic / Room #</label>
          <input type="text" name="room_no" class="form-control" value="{{ old('room_no', $doctor->room_no) }}">
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
          <select name="status" class="form-select" required>
            <option value="active" {{ old('status', $doctor->status) == 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status', $doctor->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
          </select>
        </div>

        <div class="col-12">
          <label class="form-label fw-semibold">Professional Bio & Experience</label>
          <textarea name="bio" rows="3" class="form-control">{{ old('bio', $doctor->bio) }}</textarea>
        </div>
      </div>

      <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('doctors.show', $doctor->id) }}" class="btn btn-outline-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary px-4">
          <i class="bx bx-save me-1"></i> Update Doctor
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
