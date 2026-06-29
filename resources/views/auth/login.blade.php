<!DOCTYPE html>
<html lang="en" dir="ltr" data-bs-theme="light" data-color-theme="Blue_Theme" data-layout="vertical">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" type="image/png" href="{{ asset('modernize_v5/modernize-bootstrap/dist/assets/images/logos/jeeves-logo.png') }}" />
  <link rel="stylesheet" href="{{ asset('modernize_v5/modernize-bootstrap/dist/assets/css/styles.css') }}" />
  <title>Jeeves - Login</title>
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
                  <img src="{{ asset('modernize_v5/modernize-bootstrap/dist/assets/images/logos/jeeves-logo.png') }}" class="dark-logo" alt="Logo-Dark" style="width: 150px;" />
                </div>
                <h2 class="mb-1 fs-7 fw-bolder text-center">Welcome to Jeeves</h2>
                <p class="mb-7 text-center">Please login to continue</p>
                
                @if ($errors->any())
                  <div class="alert alert-danger" role="alert">
                    {{ $errors->first() }}
                  </div>
                @endif

                <form action="{{ route('login') }}" method="POST">
                  @csrf
                  <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus>
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
