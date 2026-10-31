@extends('layouts.app')

@section('title', 'Edit Patient - ' . $patient->full_name)

@section('content')
<div class="mb-3">
  <a href="{{ route('patients.show', $patient->id) }}" class="btn btn-outline-secondary btn-sm mb-2">
    <i class="bx bx-arrow-back me-1"></i> Back to Patient EMR
  </a>
  <h4 class="fw-bold mb-1"><i class="bx bx-edit me-2 text-primary"></i> Edit Patient Record</h4>
  <p class="text-muted mb-0">Update demographics, contact details, or allergy alerts for <span class="fw-bold">{{ $patient->full_name }}</span> ({{ $patient->mrn }})</p>
</div>

<div class="card">
  <div class="card-body p-4">
    <form action="{{ route('patients.update', $patient->id) }}" method="POST">
      @csrf
      @method('PUT')

      <h5 class="fw-bold text-primary mb-3 border-bottom pb-2">
        <i class="bx bx-id-card me-1"></i> Personal Demographics
      </h5>
      <div class="row g-3 mb-4">
        <div class="col-md-4">
          <label class="form-label fw-semibold">First Name <span class="text-danger">*</span></label>
          <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $patient->first_name) }}" required>
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Last Name <span class="text-danger">*</span></label>
          <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $patient->last_name) }}" required>
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Gender <span class="text-danger">*</span></label>
          <select name="gender" class="form-select" required>
            <option value="male" {{ old('gender', $patient->gender) == 'male' ? 'selected' : '' }}>Male</option>
            <option value="female" {{ old('gender', $patient->gender) == 'female' ? 'selected' : '' }}>Female</option>
            <option value="other" {{ old('gender', $patient->gender) == 'other' ? 'selected' : '' }}>Other</option>
          </select>
        </div>

        <div class="col-md-4">
          <label class="form-label fw-semibold">Date of Birth</label>
          <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', $patient->date_of_birth) }}">
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Age (Years)</label>
          <input type="number" name="age" class="form-control" value="{{ old('age', $patient->age) }}" min="0" max="130">
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Blood Group</label>
          <select name="blood_group" class="form-select">
            <option value="">Select Blood Group</option>
            @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)
              <option value="{{ $bg }}" {{ old('blood_group', $patient->blood_group) == $bg ? 'selected' : '' }}>{{ $bg }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <h5 class="fw-bold text-primary mb-3 border-bottom pb-2">
        <i class="bx bx-phone-call me-1"></i> Contact & Emergency Information
      </h5>
      <div class="row g-3 mb-4">
        <div class="col-md-4">
          <label class="form-label fw-semibold">Primary Phone <span class="text-danger">*</span></label>
          <input type="text" name="phone" class="form-control" value="{{ old('phone', $patient->phone) }}" required>
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Email Address</label>
          <input type="email" name="email" class="form-control" value="{{ old('email', $patient->email) }}">
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Residential Address</label>
          <input type="text" name="address" class="form-control" value="{{ old('address', $patient->address) }}">
        </div>

        <div class="col-md-6">
          <label class="form-label fw-semibold">Emergency Contact Person</label>
          <input type="text" name="emergency_contact_name" class="form-control" value="{{ old('emergency_contact_name', $patient->emergency_contact_name) }}">
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Emergency Contact Phone</label>
          <input type="text" name="emergency_contact_phone" class="form-control" value="{{ old('emergency_contact_phone', $patient->emergency_contact_phone) }}">
        </div>
      </div>

      <h5 class="fw-bold text-primary mb-3 border-bottom pb-2">
        <i class="bx bx-health me-1"></i> Medical History & Clinical Alerts
      </h5>
      <div class="row g-3 mb-4">
        <div class="col-md-6">
          <label class="form-label fw-semibold text-danger"><i class="bx bx-error me-1"></i> Known Drug / Food Allergies</label>
          <textarea name="allergies" rows="2" class="form-control">{{ old('allergies', $patient->allergies) }}</textarea>
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold"><i class="bx bx-pulse me-1"></i> Chronic Conditions</label>
          <textarea name="chronic_conditions" rows="2" class="form-control">{{ old('chronic_conditions', $patient->chronic_conditions) }}</textarea>
        </div>
        <div class="col-12">
          <label class="form-label fw-semibold">Special Instructions or Notes</label>
          <textarea name="notes" rows="2" class="form-control">{{ old('notes', $patient->notes) }}</textarea>
        </div>
      </div>

      <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('patients.show', $patient->id) }}" class="btn btn-outline-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary px-4">
          <i class="bx bx-save me-1"></i> Update Changes
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
