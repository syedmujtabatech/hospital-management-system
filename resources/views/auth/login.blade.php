<!DOCTYPE html>
<html lang="en" class="layout-wide customizer-hide">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
  <title>Login - Al-Shifa Hospital Management System</title>

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

  <!-- Icons -->
  <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

  <!-- Core CSS -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}" />
</head>

<body style="background: linear-gradient(135deg, #f5f5f9 0%, #e7e7ff 100%); min-height: 100vh;">
  <div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
      <div class="authentication-inner py-4" style="max-width: 460px; margin: auto;">
        
        <!-- Login Card -->
        <div class="card shadow-lg border-0">
          <div class="card-body p-4 p-sm-5">
            <!-- Hospital Logo -->
            <div class="app-brand justify-content-center mb-4 text-center">
              <a href="#" class="app-brand-link gap-2 d-flex flex-column align-items-center">
                <div class="avatar avatar-lg bg-label-primary p-2 rounded-circle mb-2" style="width: 65px; height: 65px; display: flex; align-items: center; justify-content: center;">
                  <i class="fa-solid fa-hospital-user text-primary" style="font-size: 2.2rem;"></i>
                </div>
                <h4 class="fw-bolder mb-0 text-primary">Al-Shifa International</h4>
                <small class="text-muted text-uppercase fw-semibold" style="letter-spacing: 1.5px; font-size: 0.75rem;">Hospital Management System</small>
              </a>
            </div>

            @if(session('success'))
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
              </div>
            @endif

            @if($errors->any())
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bx bx-error-circle me-1"></i> {{ $errors->first() }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
              </div>
            @endif

            <form id="formAuthentication" class="mb-4" action="{{ route('login.post') }}" method="POST">
              @csrf
              <div class="mb-3">
                <label for="email" class="form-label fw-semibold">Email or Username</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                  <input type="email" class="form-control" id="email" name="email" value="{{ old('email', 'admin@hospital.com') }}" placeholder="Enter staff email" required autofocus />
                </div>
              </div>

              <div class="mb-3 form-password-toggle">
                <div class="d-flex justify-content-between">
                  <label class="form-label fw-semibold" for="password">Password</label>
                  <a href="javascript:void(0);" class="small text-muted">Forgot?</a>
                </div>
                <div class="input-group">
                  <span class="input-group-text"><i class="bx bx-lock-alt"></i></span>
                  <input type="password" id="password" class="form-control" name="password" value="admin123" placeholder="••••••••" required />
                </div>
              </div>

              <div class="mb-3 d-flex justify-content-between align-items-center">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="remember-me" name="remember" checked />
                  <label class="form-check-label small" for="remember-me"> Keep me signed in </label>
                </div>
              </div>

              <div class="mb-3">
                <button class="btn btn-primary d-grid w-100 py-2 fw-semibold shadow-sm" type="submit">
                  <i class="bx bx-log-in me-1"></i> Sign In to Portal
                </button>
              </div>
            </form>

            <div class="divider my-3">
              <div class="divider-text text-uppercase fw-semibold text-muted" style="font-size: 0.75rem;">1-Click Quick Demo Login</div>
            </div>

            <!-- Quick Demo Login Buttons -->
            <div class="d-flex flex-wrap gap-2 justify-content-center">
              <a href="{{ route('quick.login', 'admin') }}" class="btn btn-sm btn-outline-primary" title="Login as Administrator">
                <i class="bx bx-shield-quarter me-1"></i> Admin
              </a>
              <a href="{{ route('quick.login', 'doctor') }}" class="btn btn-sm btn-outline-success" title="Login as Doctor">
                <i class="bx bx-plus-medical me-1"></i> Doctor
              </a>
              <a href="{{ route('quick.login', 'receptionist') }}" class="btn btn-sm btn-outline-info" title="Login as Receptionist">
                <i class="bx bx-user-voice me-1"></i> Reception
              </a>
              <a href="{{ route('quick.login', 'pharmacist') }}" class="btn btn-sm btn-outline-warning" title="Login as Pharmacist">
                <i class="bx bx-capsule me-1"></i> Pharmacy
              </a>
              <a href="{{ route('quick.login', 'lab_technician') }}" class="btn btn-sm btn-outline-secondary" title="Login as Lab Technician">
                <i class="bx bx-test-tube me-1"></i> Lab
              </a>
            </div>

          </div>
        </div>
        <!-- / Login Card -->

      </div>
    </div>
  </div>

  <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
  <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
</body>
</html>
