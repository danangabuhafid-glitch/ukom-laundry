<!DOCTYPE html>
<html lang="en" dir="ltr" data-bs-theme="light" data-color-theme="Blue_Theme" data-layout="vertical">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ isset($webSetting) ? $webSetting->app_name : 'Jeeves Laundry' }} - Login</title>
  <link rel="shortcut icon" type="image/png" href="{{ isset($webSetting) && $webSetting->logo_path ? Storage::url($webSetting->logo_path) : asset('modernize_v5/modernize-bootstrap/dist/assets/images/logos/jeeves-logo.png') }}" />
  <link rel="stylesheet" href="{{ asset('modernize_v5/modernize-bootstrap/dist/assets/css/styles.css') }}" />
</head>

<body>
  <div class="preloader">
    <img src="{{ asset('modernize_v5/modernize-bootstrap/dist/assets/images/logos/jeeves-logo.png') }}" alt="loader" class="lds-ripple img-fluid" style="width: 80px;" />
  </div>
  <div id="main-wrapper" class="auth-customizer-none">
    <div class="position-relative overflow-hidden radial-gradient min-vh-100 w-100">
      <div class="position-relative z-index-5">
        <div class="row">
          <div class="col-xl-7 col-xxl-8">
            <div class="d-none d-xl-flex align-items-center justify-content-center h-n80 mt-5">
              <img src="{{ asset('modernize_v5/modernize-bootstrap/dist/assets/images/backgrounds/login-security.svg') }}" alt="modernize-img" class="img-fluid" width="500">
            </div>
          </div>
          <div class="col-xl-5 col-xxl-4">
            <div class="authentication-login min-vh-100 bg-body row justify-content-center align-items-center p-4">
              <div class="auth-max-width col-sm-8 col-md-6 col-xl-7 px-4">
                <div class="text-center mb-4">
                  <img src="{{ isset($webSetting) && $webSetting->logo_path ? Storage::url($webSetting->logo_path) : asset('modernize_v5/modernize-bootstrap/dist/assets/images/logos/jeeves-logo.png') }}" class="dark-logo" alt="Logo-Dark" style="width: 150px; max-height: 50px; object-fit: contain;" />
                </div>
                <h2 class="mb-1 fs-7 fw-bolder text-center">Welcome to {{ isset($webSetting) ? $webSetting->app_name : 'Jeeves' }}</h2>
                <p class="mb-7 text-center">Please login to continue</p>
                
                @if ($errors->any())
                  <div class="alert alert-danger" role="alert">
                    {{ $errors->first() }}
                  </div>
                @endif

                <form action="{{ route('login') }}" method="POST">
                  @csrf
                  <div class="mb-3">
                    <label for="login" class="form-label">Email / Username</label>
                    <input type="text" class="form-control" id="login" name="login" value="{{ old('login') }}" required autofocus>
                  </div>
                  <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                  </div>
                  <button type="submit" class="btn btn-primary w-100 py-8 mb-4 rounded-2">Sign In</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="{{ asset('modernize_v5/modernize-bootstrap/dist/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('modernize_v5/modernize-bootstrap/dist/assets/libs/simplebar/dist/simplebar.min.js') }}"></script>
  <script src="{{ asset('modernize_v5/modernize-bootstrap/dist/assets/js/theme/app.init.js') }}"></script>
  <script src="{{ asset('modernize_v5/modernize-bootstrap/dist/assets/js/theme/theme.js') }}"></script>
  <script src="{{ asset('modernize_v5/modernize-bootstrap/dist/assets/js/theme/app.min.js') }}"></script>
</body>
</html>
