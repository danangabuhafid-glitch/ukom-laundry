<!DOCTYPE html>
<html lang="en" dir="ltr" data-bs-theme="light" data-color-theme="Blue_Theme" data-layout="vertical">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" type="image/png" href="{{ asset('modernize_v5/modernize-bootstrap/dist/assets/images/logos/jeeves-logo.png') }}" />
  <link rel="stylesheet" href="{{ asset('modernize_v5/modernize-bootstrap/dist/assets/css/styles.css') }}" />
  <title>@yield('title', 'Jeeves')</title>
</head>
<body>
  <div class="preloader">
    <img src="{{ asset('modernize_v5/modernize-bootstrap/dist/assets/images/logos/jeeves-logo.png') }}" alt="loader" class="lds-ripple img-fluid" style="width: 80px;" />
  </div>
  <div id="main-wrapper">
    <!-- Sidebar Start -->
    <aside class="left-sidebar with-vertical">
      <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
          <a href="{{ route('dashboard') }}" class="text-nowrap logo-img">
            <img src="{{ asset('modernize_v5/modernize-bootstrap/dist/assets/images/logos/jeeves-logo.png') }}" class="dark-logo" alt="Logo-Dark" style="width: 150px;" />
            <img src="{{ asset('modernize_v5/modernize-bootstrap/dist/assets/images/logos/jeeves-logo.png') }}" class="light-logo" alt="Logo-light" style="width: 150px;" />
          </a>
          <a href="javascript:void(0)" class="sidebartoggler ms-auto text-decoration-none fs-5 d-block d-xl-none">
            <i class="ti ti-x"></i>
          </a>
        </div>
        <nav class="sidebar-nav scroll-sidebar" data-simplebar>
          <ul id="sidebarnav">
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">MENU</span>
            </li>
            <!-- Dummy element to prevent sidebarmenu.js from crashing and overwriting dashboard link -->
            <a href="#" id="get-url" class="d-none"></a>

            <!-- Dashboard -->
            <li class="sidebar-item">
              <a class="sidebar-link" href="{{ route('dashboard') }}" aria-expanded="false">
                <span><i class="ti ti-aperture"></i></span>
                <span class="hide-menu">Dashboard</span>
              </a>
            </li>

            @if(auth()->user()->level->level_name == 'Administrator')
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">MASTER DATA</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="{{ route('master.customers.index') }}" aria-expanded="false">
                <span><i class="ti ti-users"></i></span>
                <span class="hide-menu">Customer</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="{{ route('master.services.index') }}" aria-expanded="false">
                <span><i class="ti ti-wash-machine"></i></span>
                <span class="hide-menu">Service</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="{{ route('master.users.index') }}" aria-expanded="false">
                <span><i class="ti ti-user-circle"></i></span>
                <span class="hide-menu">User</span>
              </a>
            </li>
            @endif

            @if(in_array(auth()->user()->level->level_name, ['Operator', 'Administrator']))
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">OPERATIONS</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="{{ route('transactions.index') }}" aria-expanded="false">
                <span><i class="ti ti-shopping-cart"></i></span>
                <span class="hide-menu">Transaction</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="{{ route('pickups.index') }}" aria-expanded="false">
                <span><i class="ti ti-package"></i></span>
                <span class="hide-menu">Pickup</span>
              </a>
            </li>
            @endif

            @if(in_array(auth()->user()->level->level_name, ['Pimpinan', 'Administrator']))
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">MANAGEMENT</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="{{ route('reports.index') }}" aria-expanded="false">
                <span><i class="ti ti-file-text"></i></span>
                <span class="hide-menu">Reports</span>
              </a>
            </li>
            @endif
          </ul>
        </nav>

        <div class="fixed-profile p-3 mx-4 mb-2 bg-secondary-subtle rounded mt-3">
          <div class="hstack gap-3">
            <div class="john-img">
              <img src="{{ asset('modernize_v5/modernize-bootstrap/dist/assets/images/profile/user-1.jpg') }}" class="rounded-circle" width="40" height="40" alt="user" />
            </div>
            <div class="john-title">
              <h6 class="mb-0 fs-4 fw-semibold">{{ auth()->user()->name }}</h6>
              <span class="fs-2">{{ auth()->user()->level->level_name }}</span>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="ms-auto">
              @csrf
              <button type="submit" class="border-0 bg-transparent text-primary" tabindex="0" aria-label="logout" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="logout">
                <i class="ti ti-power fs-6"></i>
              </button>
            </form>
          </div>
        </div>
      </div>
    </aside>
    <!-- Sidebar End -->
    
    <div class="page-wrapper">
      <!-- Header Start -->
      <header class="topbar">
        <div class="with-vertical">
          <nav class="navbar navbar-expand-lg p-0">
            <ul class="navbar-nav">
              <li class="nav-item nav-icon-hover-bg rounded-circle ms-n2">
                <a class="nav-link sidebartoggler" id="headerCollapse" href="javascript:void(0)">
                  <i class="ti ti-menu-2"></i>
                </a>
              </li>
            </ul>
            <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
              <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
                <li class="nav-item dropdown">
                  <a class="nav-link pe-0" href="javascript:void(0)" id="drop1" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="d-flex align-items-center">
                      <div class="user-profile-img">
                        <img src="{{ asset('modernize_v5/modernize-bootstrap/dist/assets/images/profile/user-1.jpg') }}" class="rounded-circle" width="35" height="35" alt="user" />
                      </div>
                    </div>
                  </a>
                  <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop1">
                    <div class="message-body">
                      <a href="{{ route('profile.edit') }}" class="d-flex align-items-center gap-2 dropdown-item">
                        <i class="ti ti-user fs-6"></i>
                        <p class="mb-0 fs-3">My Profile</p>
                      </a>
                      <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary mx-3 mt-2 d-block w-85">Logout</button>
                      </form>
                    </div>
                  </div>
                </li>
              </ul>
            </div>
          </nav>
        </div>
      </header>
      <!-- Header End -->
      
      <div class="body-wrapper position-relative min-vh-100 pb-5">
        <div class="container-fluid">

          @yield('content')
        </div>
        
        <!-- Footer -->
        <footer class="footer py-3 text-center bg-white border-top mt-auto" style="position: absolute; bottom: 0; width: 100%;">
          <div class="container-fluid">
            <span class="text-muted fw-semibold">Danang Abu Hafid 2026 | WP | Ukom</span>
          </div>
        </footer>
      </div>
    </div>
  </div>

  <style>
    .premium-toast {
      background: linear-gradient(135deg, #166534 0%, #15803d 50%, #16a34a 100%);
      color: #fff;
      border-radius: 14px;
      box-shadow: 0 12px 35px rgba(34,197,94,.35);
      display: flex;
      align-items: center;
      padding: 16px 20px;
      gap: 16px;
      position: relative;
      overflow: hidden;
      min-width: 320px;
      max-width: 400px;
      margin-bottom: 1rem;
    }
    
    .premium-toast.premium-danger {
      background: linear-gradient(135deg, #991b1b 0%, #b91c1c 50%, #ef4444 100%);
      box-shadow: 0 12px 35px rgba(239,68,68,.35);
    }

    .premium-toast .icon {
      font-size: 24px;
      background: rgba(255,255,255,0.2);
      width: 40px;
      height: 40px;
      display: flex;
      justify-content: center;
      align-items: center;
      border-radius: 50%;
      flex-shrink: 0;
    }

    .premium-toast .content {
      flex: 1;
    }

    .premium-toast .content h4 {
      margin: 0 0 4px 0;
      font-size: 16px;
      font-weight: 700;
      color: #fff;
    }

    .premium-toast .content p {
      margin: 0;
      font-size: 13px;
      opacity: 0.9;
      line-height: 1.4;
    }

    .premium-toast .close-btn {
      cursor: pointer;
      font-size: 20px;
      opacity: 0.7;
      padding: 5px;
      display: flex;
      align-items: flex-start;
      height: 100%;
      align-self: flex-start;
    }
    .premium-toast .close-btn:hover {
      opacity: 1;
    }

    .premium-toast .progress-bar-custom {
      position: absolute;
      bottom: 0;
      left: 0;
      height: 4px;
      width: 100%;
      background: rgba(255,255,255,0.2);
    }

    .premium-toast .progress-bar-custom::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      height: 100%;
      width: 100%;
      background: #7ed49e;
      transform: scaleX(0);
      transform-origin: left;
      animation: fillProgress 10s linear forwards;
    }

    .premium-toast.premium-danger .progress-bar-custom::after {
      background: #fca5a5;
    }

    @keyframes fillProgress {
      100% { transform: scaleX(1); }
    }
  </style>

  <!-- Toast Wrapper -->
  <div style="position: fixed !important; top: 80px !important; right: 20px !important; z-index: 99999 !important; display: flex; flex-direction: column; align-items: flex-end; pointer-events: none;">
    @if(session('success'))
      <div class="toast premium-toast border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="10000" style="pointer-events: auto;">
        <div class="icon">
          <i class="ti ti-check"></i>
        </div>
        <div class="content">
          <h4>Success</h4>
          <p>{{ session('success') }}</p>
        </div>
        <div class="close-btn" data-bs-dismiss="toast" aria-label="Close">
          <i class="ti ti-x"></i>
        </div>
        <div class="progress-bar-custom"></div>
      </div>
    @endif

    @if(session('error'))
      <div class="toast premium-toast premium-danger border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="10000" style="pointer-events: auto;">
        <div class="icon">
          <i class="ti ti-alert-circle"></i>
        </div>
        <div class="content">
          <h4>Error</h4>
          <p>{{ session('error') }}</p>
        </div>
        <div class="close-btn" data-bs-dismiss="toast" aria-label="Close">
          <i class="ti ti-x"></i>
        </div>
        <div class="progress-bar-custom"></div>
      </div>
    @endif
  </div>

  <script src="{{ asset('modernize_v5/modernize-bootstrap/dist/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('modernize_v5/modernize-bootstrap/dist/assets/libs/simplebar/dist/simplebar.min.js') }}"></script>
  <script src="{{ asset('modernize_v5/modernize-bootstrap/dist/assets/js/theme/app.init.js') }}"></script>
  <script src="{{ asset('modernize_v5/modernize-bootstrap/dist/assets/js/theme/theme.js') }}"></script>
  <script src="{{ asset('modernize_v5/modernize-bootstrap/dist/assets/js/theme/app.min.js') }}"></script>
  <script src="{{ asset('modernize_v5/modernize-bootstrap/dist/assets/js/theme/sidebarmenu.js') }}"></script>
  
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      var toastElList = [].slice.call(document.querySelectorAll('.toast'));
      var toastList = toastElList.map(function (toastEl) {
        return new bootstrap.Toast(toastEl);
      });
      toastList.forEach(toast => toast.show());
    });
  </script>
  @stack('scripts')
</body>
</html>
