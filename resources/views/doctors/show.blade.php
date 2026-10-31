@extends('layouts.app')

@section('title', 'Dr. ' . $doctor->first_name . ' ' . $doctor->last_name)

@section('content')
<div class="mb-3">
  <a href="{{ route('doctors.index') }}" class="btn btn-outline-secondary btn-sm mb-2">
    <i class="bx bx-arrow-back me-1"></i> Back to Doctor Directory
  </a>
  <h4 class="fw-bold mb-0">Doctor Profile</h4>
</div>

<div class="row">
  <div class="col-md-4 mb-4">
    <div class="card text-center p-4">
      <div class="avatar avatar-xl bg-label-primary rounded-circle mx-auto mb-3" style="width: 90px; height: 90px; display: flex; align-items: center; justify-content: center;">
        <i class="fa-solid fa-user-doctor text-primary fs-1"></i>
      </div>
      <h4 class="fw-bold mb-1">Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}</h4>
      <span class="badge bg-label-primary mb-2">{{ $doctor->department->name ?? 'Specialist' }}</span>
      <p class="text-muted small">{{ $doctor->specialization }}</p>

      <ul class="list-group list-group-flush text-start mt-3">
        <li class="list-group-item d-flex justify-content-between px-0">
          <span class="text-muted">Qualification:</span>
          <strong>{{ $doctor->qualification }}</strong>
        </li>
        <li class="list-group-item d-flex justify-content-between px-0">
          <span class="text-muted">License #:</span>
          <code>{{ $doctor->license_number }}</code>
        </li>
        <li class="list-group-item d-flex justify-content-between px-0">
          <span class="text-muted">Fee:</span>
          <strong class="text-success">${{ number_format($doctor->consultation_fee, 2) }}</strong>
        </li>
        <li class="list-group-item d-flex justify-content-between px-0">
          <span class="text-muted">Room #:</span>
          <span>{{ $doctor->room_no ?: 'Room 101' }}</span>
        </li>
        <li class="list-group-item d-flex justify-content-between px-0">
          <span class="text-muted">Phone:</span>
          <span>{{ $doctor->phone }}</span>
        </li>
        <li class="list-group-item d-flex justify-content-between px-0">
          <span class="text-muted">Email:</span>
          <span>{{ $doctor->user->email ?? 'N/A' }}</span>
        </li>
      </ul>

      <div class="mt-3">
        <a href="{{ route('doctors.edit', $doctor->id) }}" class="btn btn-outline-primary w-100">
          <i class="bx bx-edit me-1"></i> Edit Profile
        </a>
      </div>
    </div>
  </div>

  <div class="col-md-8 mb-4">
    <div class="card mb-4">
      <div class="card-header">
        <h5 class="mb-0 fw-bold"><i class="bx bx-calendar-event me-2 text-primary"></i> Doctor Appointments</h5>
      </div>
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th>Date</th>
              <th>Time</th>
              <th>Patient</th>
              <th>Priority</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @forelse($doctor->appointments as $apt)
              <tr>
                <td>{{ \Carbon\Carbon::parse($apt->appointment_date)->format('M d, Y') }}</td>
                <td class="fw-bold">{{ $apt->time_slot }}</td>
                <td>{{ $apt->patient->full_name ?? 'Patient' }}</td>
                <td><span class="badge bg-label-info">{{ ucfirst($apt->priority) }}</span></td>
                <td><span class="badge bg-label-primary">{{ ucfirst($apt->status) }}</span></td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center py-4 text-muted">No appointments booked yet.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
