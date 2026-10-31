@extends('layouts.app')

@section('title', 'Register New Patient')

@section('content')
<div class="mb-3">
  <a href="{{ route('patients.index') }}" class="btn btn-outline-secondary btn-sm mb-2">
    <i class="bx bx-arrow-back me-1"></i> Back to Patient Directory
  </a>
  <h4 class="fw-bold mb-1"><i class="bx bx-user-plus me-2 text-primary"></i> Patient Registration (EMR)</h4>
  <p class="text-muted mb-0">Create new patient file. An official Medical Record Number (MRN) will be automatically generated.</p>
</div>

<div class="card">
  <div class="card-body p-4">
    <form action="{{ route('patients.store') }}" method="POST">
      @csrf

      <h5 class="fw-bold text-primary mb-3 border-bottom pb-2">
        <i class="bx bx-id-card me-1"></i> Personal Demographics
      </h5>
      <div class="row g-3 mb-4">
        <div class="col-md-4">
          <label class="form-label fw-semibold">First Name <span class="text-danger">*</span></label>
          <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}" required placeholder="e.g. Tariq">
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Last Name <span class="text-danger">*</span></label>
          <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}" required placeholder="e.g. Mehmood">
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Gender <span class="text-danger">*</span></label>
          <select name="gender" class="form-select" required>
            <option value="">Select Gender</option>
            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
          </select>
        </div>

        <div class="col-md-4">
          <label class="form-label fw-semibold">Date of Birth</label>
          <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth') }}">
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Age (Years)</label>
          <input type="number" name="age" class="form-control" value="{{ old('age') }}" min="0" max="130" placeholder="e.g. 45">
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Blood Group</label>
          <select name="blood_group" class="form-select">
            <option value="">Select Blood Group</option>
            @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)
              <option value="{{ $bg }}" {{ old('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
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
          <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required placeholder="e.g. +92 300 1234567">
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Email Address</label>
          <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="e.g. patient@email.com">
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Residential Address</label>
          <input type="text" name="address" class="form-control" value="{{ old('address') }}" placeholder="House #, Street, City">
        </div>

        <div class="col-md-6">
          <label class="form-label fw-semibold">Emergency Contact Person</label>
          <input type="text" name="emergency_contact_name" class="form-control" value="{{ old('emergency_contact_name') }}" placeholder="e.g. Mrs. Fatima (Spouse)">
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Emergency Contact Phone</label>
          <input type="text" name="emergency_contact_phone" class="form-control" value="{{ old('emergency_contact_phone') }}" placeholder="e.g. +92 321 9876543">
        </div>
      </div>

      <h5 class="fw-bold text-primary mb-3 border-bottom pb-2">
        <i class="bx bx-health me-1"></i> Medical History & Clinical Alerts
      </h5>
      <div class="row g-3 mb-4">
        <div class="col-md-6">
          <label class="form-label fw-semibold text-danger"><i class="bx bx-error me-1"></i> Known Drug / Food Allergies</label>
          <textarea name="allergies" rows="2" class="form-control" placeholder="e.g. Penicillin, Sulfa drugs, Peanuts (Leave empty if none)">{{ old('allergies') }}</textarea>
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold"><i class="bx bx-pulse me-1"></i> Chronic Conditions / Pre-existing Diseases</label>
          <textarea name="chronic_conditions" rows="2" class="form-control" placeholder="e.g. Hypertension, Type 2 Diabetes, Asthma">{{ old('chronic_conditions') }}</textarea>
        </div>
        <div class="col-12">
          <label class="form-label fw-semibold">Special Instructions or Notes</label>
          <textarea name="notes" rows="2" class="form-control" placeholder="Optional clinical observations or remarks">{{ old('notes') }}</textarea>
        </div>
      </div>

      <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('patients.index') }}" class="btn btn-outline-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary px-4">
          <i class="bx bx-check-circle me-1"></i> Save & Generate MRN
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
