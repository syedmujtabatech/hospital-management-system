@extends('layouts.app')

@section('title', 'Visual Bed & Ward Matrix')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h4 class="fw-bold mb-1"><i class="bx bx-grid-alt me-2 text-primary"></i> Visual Ward & Bed Matrix</h4>
    <p class="text-muted mb-0">Real-time room occupancy, bed availability status, and direct inpatient allocation</p>
  </div>
  <div class="d-flex gap-2">
    <a href="{{ route('ipd.index') }}" class="btn btn-outline-secondary">
      <i class="bx bx-list-ul me-1"></i> Admissions List
    </a>
    <a href="{{ route('ipd.create') }}" class="btn btn-primary">
      <i class="bx bx-plus me-1"></i> Admit Patient
    </a>
  </div>
</div>

<!-- Bed Status Overview Cards -->
<div class="row g-3 mb-4">
  <div class="col-md-3 col-6">
    <div class="card border-0 bg-primary text-white shadow-sm">
      <div class="card-body p-3 d-flex justify-content-between align-items-center">
        <div>
          <span class="d-block small text-white-50">Total Hospital Beds</span>
          <h3 class="fw-bold mb-0 text-white">{{ $stats['total'] }}</h3>
        </div>
        <i class="bx bx-bed fs-1 text-white-50"></i>
      </div>
    </div>
  </div>

  <div class="col-md-3 col-6">
    <div class="card border-0 bg-success text-white shadow-sm">
      <div class="card-body p-3 d-flex justify-content-between align-items-center">
        <div>
          <span class="d-block small text-white-50">Available Beds (Green)</span>
          <h3 class="fw-bold mb-0 text-white">{{ $stats['available'] }}</h3>
        </div>
        <i class="bx bx-check-circle fs-1 text-white-50"></i>
      </div>
    </div>
  </div>

  <div class="col-md-3 col-6">
    <div class="card border-0 bg-danger text-white shadow-sm">
      <div class="card-body p-3 d-flex justify-content-between align-items-center">
        <div>
          <span class="d-block small text-white-50">Occupied Beds (Red)</span>
          <h3 class="fw-bold mb-0 text-white">{{ $stats['occupied'] }}</h3>
        </div>
        <i class="bx bx-user-check fs-1 text-white-50"></i>
      </div>
    </div>
  </div>

  <div class="col-md-3 col-6">
    <div class="card border-0 bg-warning text-white shadow-sm">
      <div class="card-body p-3 d-flex justify-content-between align-items-center">
        <div>
          <span class="d-block small text-white-50">Maintenance (Yellow)</span>
          <h3 class="fw-bold mb-0 text-white">{{ $stats['maintenance'] }}</h3>
        </div>
        <i class="bx bx-wrench fs-1 text-white-50"></i>
      </div>
    </div>
  </div>
</div>

<!-- Wards Matrix Grid -->
@foreach($wards as $ward)
  @php
    $wardRooms = $rooms->where('ward_name', $ward);
  @endphp
  <div class="card mb-4">
    <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
      <h6 class="mb-0 fw-bold text-dark">
        <i class="bx bx-buildings text-primary me-2"></i> {{ $ward }}
        <span class="badge bg-secondary ms-2 small">{{ $wardRooms->count() }} Beds</span>
      </h6>
      <small class="text-muted">
        {{ $wardRooms->where('status', 'available')->count() }} Available | 
        {{ $wardRooms->where('status', 'occupied')->count() }} Occupied
      </small>
    </div>
    <div class="card-body p-4">
      <div class="row g-3">
        @foreach($wardRooms as $rm)
          <div class="col-lg-3 col-md-4 col-sm-6">
            @if($rm->status === 'available')
              <!-- Available Bed (Green Card) -->
              <div class="card border border-success border-2 h-100 shadow-sm" style="background-color: #f6fdf8;">
                <div class="card-body p-3 text-center">
                  <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="badge bg-success">Available</span>
                    <strong class="text-success">${{ number_format($rm->daily_charge, 0) }}/day</strong>
                  </div>
                  <i class="bx bx-bed text-success fs-1 mb-1"></i>
                  <h5 class="fw-bold mb-1 text-dark">{{ $rm->room_number }}</h5>
                  <small class="text-muted text-capitalize d-block mb-3">{{ $rm->room_type }} Room</small>

                  <a href="{{ route('ipd.create', ['room_id' => $rm->id]) }}" class="btn btn-sm btn-success w-100">
                    <i class="bx bx-user-plus me-1"></i> Admit Here
                  </a>
                </div>
              </div>
            @elseif($rm->status === 'occupied')
              <!-- Occupied Bed (Red Card) -->
              <div class="card border border-danger border-2 h-100 shadow-sm" style="background-color: #fff8f8;">
                <div class="card-body p-3 text-center">
                  <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="badge bg-danger">Occupied</span>
                    <small class="text-muted">${{ number_format($rm->daily_charge, 0) }}/day</small>
                  </div>
                  <i class="bx bx-user-pin text-danger fs-1 mb-1"></i>
                  <h5 class="fw-bold mb-0 text-dark">{{ $rm->room_number }}</h5>
                  @if($rm->currentAdmission)
                    <div class="fw-semibold text-danger small mt-1">
                      {{ $rm->currentAdmission->patient->full_name ?? 'Admitted Patient' }}
                    </div>
                    <small class="text-muted d-block" style="font-size: 0.75rem;">
                      Dr. {{ $rm->currentAdmission->doctor->last_name ?? '' }} | {{ \Carbon\Carbon::parse($rm->currentAdmission->admission_date)->diffForHumans() }}
                    </small>
                  @endif

                  <div class="mt-2">
                    <a href="{{ route('ipd.index') }}" class="btn btn-xs btn-outline-danger w-100">
                      View Admission
                    </a>
                  </div>
                </div>
              </div>
            @else
              <!-- Maintenance Bed (Yellow Card) -->
              <div class="card border border-warning border-2 h-100 shadow-sm" style="background-color: #fffdf5;">
                <div class="card-body p-3 text-center">
                  <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="badge bg-warning text-dark">Maintenance</span>
                    <small class="text-muted">{{ $rm->room_number }}</small>
                  </div>
                  <i class="bx bx-wrench text-warning fs-1 mb-1"></i>
                  <h5 class="fw-bold mb-1 text-dark">{{ $rm->room_number }}</h5>
                  <small class="text-muted d-block mb-3">Cleaning / Service</small>

                  <form action="{{ route('rooms.status', $rm->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="available">
                    <button type="submit" class="btn btn-xs btn-outline-warning w-100">
                      Mark Available
                    </button>
                  </form>
                </div>
              </div>
            @endif
          </div>
        @endforeach
      </div>
    </div>
  </div>
@endforeach
@endsection
