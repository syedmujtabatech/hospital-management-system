<!DOCTYPE html>
<html
  lang="en"
  class="layout-menu-fixed layout-compact"
  data-assets-path="{{ asset('assets') }}/"
  data-template="vertical-menu-template-free">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
  <title>@yield('title', 'Dashboard') - Al-Shifa International Hospital HMS</title>

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

  <!-- Boxicons & FontAwesome -->
  <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/iconify-icons.css') }}" />

  <!-- Core CSS -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />

  <!-- Helpers -->
  <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
  <script src="{{ asset('assets/js/config.js') }}"></script>

  <style>
    .app-brand-logo { font-size: 1.8rem; color: #696cff; }
    .badge-soft-success { background-color: #e8fadf; color: #71dd37; }
    .badge-soft-danger { background-color: #ffe0db; color: #ff3e1d; }
    .badge-soft-warning { background-color: #fff2d6; color: #ffab00; }
    .badge-soft-info { background-color: #d7f5fc; color: #03c3ec; }
    .badge-soft-primary { background-color: #e7e7ff; color: #696cff; }
    .card { border-radius: 0.75rem; box-shadow: 0 2px 6px 0 rgba(67, 89, 113, 0.12); }
    .table th { font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.5px; }
    .mrn-badge { font-family: monospace; font-weight: 700; letter-spacing: 0.5px; background: #e7e7ff; color: #696cff; padding: 4px 8px; border-radius: 4px; }
  </style>

  @stack('styles')
</head>

<body>
  <!-- Layout wrapper -->
  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
      
      <!-- Menu Sidebar -->
      <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
        <div class="app-brand demo">
          <a href="{{ route('dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo me-2">
              <i class="fa-solid fa-hospital-user text-primary" style="font-size: 1.75rem;"></i>
            </span>
            <span class="app-brand-text demo menu-text fw-bolder ms-2 text-primary" style="font-size: 1.25rem;">Al-Shifa HMS</span>
          </a>

          <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
          </a>
        </div>

        <div class="menu-inner-shadow"></div>

        <ul class="menu-inner py-1">
          <!-- Dashboard -->
          <li class="menu-item {{ request()->routeIs('dashboard', 'home') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="menu-link">
              <i class="menu-icon tf-icons bx bx-home-circle"></i>
              <div data-i18n="Analytics">Dashboard</div>
            </a>
          </li>

          <!-- CLINICAL SECTION -->
          <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Clinical & Patients</span>
          </li>

          <!-- Patients -->
          <li class="menu-item {{ request()->is('patients*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
              <i class="menu-icon tf-icons bx bx-user"></i>
              <div>Patients (EMR)</div>
            </a>
            <ul class="menu-sub">
              <li class="menu-item {{ request()->routeIs('patients.index') ? 'active' : '' }}">
                <a href="{{ route('patients.index') }}" class="menu-link">
                  <div>All Patients</div>
                </a>
              </li>
              <li class="menu-item {{ request()->routeIs('patients.create') ? 'active' : '' }}">
                <a href="{{ route('patients.create') }}" class="menu-link">
                  <div>Register Patient</div>
                </a>
              </li>
            </ul>
          </li>

          <!-- Doctors -->
          <li class="menu-item {{ request()->is('doctors*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
              <i class="menu-icon tf-icons bx bx-plus-medical"></i>
              <div>Doctors & Staff</div>
            </a>
            <ul class="menu-sub">
              <li class="menu-item {{ request()->routeIs('doctors.index') ? 'active' : '' }}">
                <a href="{{ route('doctors.index') }}" class="menu-link">
                  <div>Doctor Directory</div>
                </a>
              </li>
              <li class="menu-item {{ request()->routeIs('doctors.create') ? 'active' : '' }}">
                <a href="{{ route('doctors.create') }}" class="menu-link">
                  <div>Add New Doctor</div>
                </a>
              </li>
            </ul>
          </li>

          <!-- Appointments -->
          <li class="menu-item {{ request()->is('appointments*') ? 'active' : '' }}">
            <a href="{{ route('appointments.index') }}" class="menu-link">
              <i class="menu-icon tf-icons bx bx-calendar-event"></i>
              <div>Appointments</div>
            </a>
          </li>

          <!-- OPD Encounters & Prescriptions -->
          <li class="menu-item {{ request()->is('opd*') ? 'active' : '' }}">
            <a href="{{ route('opd.index') }}" class="menu-link">
              <i class="menu-icon tf-icons bx bx-notepad"></i>
              <div>OPD & Prescriptions</div>
            </a>
          </li>

          <!-- HOSPITAL OPERATIONS -->
          <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Operations & Inpatient</span>
          </li>

          <!-- IPD Admissions & Bed Matrix -->
          <li class="menu-item {{ request()->is('ipd*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
              <i class="menu-icon tf-icons bx bx-bed"></i>
              <div>IPD & Wards/Beds</div>
            </a>
            <ul class="menu-sub">
              <li class="menu-item {{ request()->routeIs('ipd.index') ? 'active' : '' }}">
                <a href="{{ route('ipd.index') }}" class="menu-link">
                  <div>Admitted Patients</div>
                </a>
              </li>
              <li class="menu-item {{ request()->routeIs('ipd.rooms') ? 'active' : '' }}">
                <a href="{{ route('ipd.rooms') }}" class="menu-link">
                  <div>Visual Bed Matrix</div>
                </a>
              </li>
              <li class="menu-item {{ request()->routeIs('ipd.create') ? 'active' : '' }}">
                <a href="{{ route('ipd.create') }}" class="menu-link">
                  <div>Admit Patient</div>
                </a>
              </li>
            </ul>
          </li>

          <!-- Pharmacy Inventory -->
          <li class="menu-item {{ request()->is('pharmacy*') ? 'active' : '' }}">
            <a href="{{ route('pharmacy.index') }}" class="menu-link">
              <i class="menu-icon tf-icons bx bx-capsule"></i>
              <div>Pharmacy Inventory</div>
            </a>
          </li>

          <!-- Laboratory & Diagnostics -->
          <li class="menu-item {{ request()->is('laboratory*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
              <i class="menu-icon tf-icons bx bx-test-tube"></i>
              <div>Laboratory</div>
            </a>
            <ul class="menu-sub">
              <li class="menu-item {{ request()->routeIs('laboratory.index') ? 'active' : '' }}">
                <a href="{{ route('laboratory.index') }}" class="menu-link">
                  <div>Lab Orders & Results</div>
                </a>
              </li>
              <li class="menu-item {{ request()->routeIs('laboratory.tests') ? 'active' : '' }}">
                <a href="{{ route('laboratory.tests') }}" class="menu-link">
                  <div>Test Catalog</div>
                </a>
              </li>
            </ul>
          </li>

          <!-- BILLING & FINANCIALS -->
          <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Financial & Admin</span>
          </li>

          <!-- Billing & Invoicing -->
          <li class="menu-item {{ request()->is('billing*') ? 'active' : '' }}">
            <a href="{{ route('billing.index') }}" class="menu-link">
              <i class="menu-icon tf-icons bx bx-receipt"></i>
              <div>Billing & Invoices</div>
            </a>
          </li>

          <!-- Departments -->
          <li class="menu-item {{ request()->is('departments*') ? 'active' : '' }}">
            <a href="{{ route('departments.index') }}" class="menu-link">
              <i class="menu-icon tf-icons bx bx-buildings"></i>
              <div>Departments</div>
            </a>
          </li>
        </ul>
      </aside>
      <!-- / Menu -->

      <!-- Layout container -->
      <div class="layout-page">
        <!-- Navbar -->
        <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
          <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
              <i class="bx bx-menu bx-sm"></i>
            </a>
          </div>

          <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
            <!-- Search & Today Date -->
            <div class="navbar-nav align-items-center">
              <div class="nav-item d-flex align-items-center">
                <i class="bx bx-calendar text-primary me-2 fs-4"></i>
                <span class="text-muted fw-semibold">{{ date('l, d F Y') }}</span>
              </div>
            </div>

            <ul class="navbar-nav flex-row align-items-center ms-auto">
              <!-- Quick Action Dropdown -->
              <li class="nav-item dropdown me-3">
                <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                  <i class="bx bx-plus-circle me-1"></i> Quick Action
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li><a class="dropdown-item" href="{{ route('patients.create') }}"><i class="bx bx-user-plus me-2 text-primary"></i> Register Patient</a></li>
                  <li><a class="dropdown-item" href="{{ route('appointments.create') }}"><i class="bx bx-calendar-plus me-2 text-info"></i> Book Appointment</a></li>
                  <li><a class="dropdown-item" href="{{ route('opd.create') }}"><i class="bx bx-notepad me-2 text-success"></i> Start OPD Checkup</a></li>
                  <li><a class="dropdown-item" href="{{ route('ipd.create') }}"><i class="bx bx-bed me-2 text-warning"></i> Admit Patient (IPD)</a></li>
                  <li><hr class="dropdown-divider"></li>
                  <li><a class="dropdown-item" href="{{ route('billing.create') }}"><i class="bx bx-receipt me-2 text-danger"></i> Generate Bill</a></li>
                </ul>
              </li>

              <!-- User Dropdown -->
              <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                  <div class="avatar avatar-online">
                    <span class="avatar-initial rounded-circle bg-label-primary fw-bold">
                      {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                    </span>
                  </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li>
                    <a class="dropdown-item" href="#">
                      <div class="d-flex">
                        <div class="flex-shrink-0 me-3">
                          <div class="avatar avatar-online">
                            <span class="avatar-initial rounded-circle bg-label-primary fw-bold">
                              {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                            </span>
                          </div>
                        </div>
                        <div class="flex-grow-1">
                          <span class="fw-semibold d-block">{{ Auth::user()->name ?? 'User' }}</span>
                          <small class="text-muted text-uppercase badge bg-label-success">{{ Auth::user()->role ?? 'Staff' }}</small>
                        </div>
                      </div>
                    </a>
                  </li>
                  <li><div class="dropdown-divider"></div></li>
                  <li>
                    <form action="{{ route('logout') }}" method="POST">
                      @csrf
                      <button type="submit" class="dropdown-item text-danger">
                        <i class="bx bx-power-off me-2"></i>
                        <span class="align-middle">Log Out</span>
                      </button>
                    </form>
                  </li>
                </ul>
              </li>
              <!--/ User -->
            </ul>
          </div>
        </nav>
        <!-- / Navbar -->

        <!-- Content wrapper -->
        <div class="content-wrapper">
          <div class="container-xxl flex-grow-1 container-p-y">
            
            <!-- Global Flash Messages -->
            @if(session('success'))
              <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="bx bx-check-circle me-2 fs-4"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            @endif

            @if(session('error'))
              <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="bx bx-error-circle me-2 fs-4"></i>
                <div>{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            @endif

            @if($errors->any())
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <div class="fw-bold mb-1"><i class="bx bx-error me-1"></i> Please fix the following errors:</div>
                <ul class="mb-0 ps-3">
                  @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                  @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            @endif

            <!-- Main Page Content -->
            @yield('content')

          </div>
          <!-- / Content -->

          <!-- Footer -->
          <footer class="content-footer footer bg-footer-theme">
            <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
              <div class="mb-2 mb-md-0">
                © {{ date('Y') }} <strong>Al-Shifa International Hospital Management System</strong>. Built with Enterprise Standards.
              </div>
              <div>
                <span class="badge bg-label-primary">v2.5 Enterprise</span>
              </div>
            </div>
          </footer>
          <!-- / Footer -->

          <div class="content-backdrop fade"></div>
        </div>
        <!-- Content wrapper -->
      </div>
      <!-- / Layout page -->
    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>
  </div>
  <!-- / Layout wrapper -->

  <!-- Core JS -->
  <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
  <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
  <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
  <script src="{{ asset('assets/js/main.js') }}"></script>

  @stack('scripts')
</body>
</html>
