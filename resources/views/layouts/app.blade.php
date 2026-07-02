<!DOCTYPE html>
<html lang="en" dir="ltr" data-bs-theme="light" data-color-theme="Blue_Theme" data-layout="vertical">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" type="image/png" href="{{ isset($webSetting) && $webSetting->logo_path ? Storage::url($webSetting->logo_path) : asset('modernize_v5/modernize-bootstrap/dist/assets/images/logos/jeeves-logo.png') }}" />
  <link rel="stylesheet" href="{{ asset('modernize_v5/modernize-bootstrap/dist/assets/css/styles.css') }}" />
  <link rel="stylesheet" href="{{ asset('modernize_v5/modernize-bootstrap/dist/assets/css/custom-select.css') }}?v={{ time() }}" />
  <title>@yield('title', isset($webSetting) ? $webSetting->app_name : 'Jeeves Laundry')</title>
</head>
<body>
  <div class="preloader">
    <img src="{{ isset($webSetting) && $webSetting->logo_path ? Storage::url($webSetting->logo_path) : asset('modernize_v5/modernize-bootstrap/dist/assets/images/logos/jeeves-logo.png') }}" alt="loader" class="lds-ripple img-fluid" style="width: 80px; object-fit: contain;" />
  </div>
  <div id="main-wrapper">
    <!-- Sidebar Start -->
    <aside class="left-sidebar with-vertical">
      <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
          <a href="{{ route('dashboard') }}" class="text-nowrap logo-img">
            <img src="{{ isset($webSetting) && $webSetting->logo_path ? Storage::url($webSetting->logo_path) : asset('modernize_v5/modernize-bootstrap/dist/assets/images/logos/jeeves-logo.png') }}" class="dark-logo" alt="Logo-Dark" style="width: 150px; height: 50px; object-fit: contain;" />
            <img src="{{ isset($webSetting) && $webSetting->logo_path ? Storage::url($webSetting->logo_path) : asset('modernize_v5/modernize-bootstrap/dist/assets/images/logos/jeeves-logo.png') }}" class="light-logo" alt="Logo-light" style="width: 150px; height: 50px; object-fit: contain;" />
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

            <!-- Dynamic Menus -->
            @if(isset($dynamicMenus))
              @foreach($dynamicMenus as $parent)
                @php
                  // Check if user has permission for the parent or ANY of its children
                  $hasAccessToParent = false;
                  
                  if ($parent->permission_name && auth()->user()->can($parent->permission_name)) {
                      $hasAccessToParent = true;
                  }
                  
                  // Or if no specific permission is required for the parent itself, check if they can see any children
                  if (!$parent->permission_name) {
                      foreach ($parent->children as $child) {
                          if (!$child->permission_name || auth()->user()->can($child->permission_name)) {
                              $hasAccessToParent = true;
                              break;
                          }
                      }
                  }
                @endphp

                @if($hasAccessToParent)
                  <li class="sidebar-item hover-menu">
                    <a class="sidebar-link has-arrow" href="{{ $parent->route_name ? route($parent->route_name) : 'javascript:void(0)' }}" aria-expanded="false">
                      <span><i class="ti {{ $parent->icon ?? 'ti-dots' }}"></i></span>
                      <span class="hide-menu">{{ $parent->name }}</span>
                    </a>
                    
                    @if($parent->children->isNotEmpty())
                    <ul aria-expanded="false" class="collapse first-level">
                      @foreach($parent->children as $child)
                        @if(!$child->permission_name || auth()->user()->can($child->permission_name))
                          <li class="sidebar-item">
                            <a href="{{ $child->route_name ? route($child->route_name) : '#' }}" class="sidebar-link">
                              <div class="round-16 d-flex align-items-center justify-content-center"><i class="ti {{ $child->icon ?? 'ti-circle' }}"></i></div>
                              <span class="hide-menu">{{ $child->name }}</span>
                            </a>
                          </li>
                        @endif
                      @endforeach
                    </ul>
                    @endif
                  </li>
                @endif
              @endforeach
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
    /* Hover Sidebar Styles */
    @media (min-width: 992px) {
      .left-sidebar .sidebar-nav ul .sidebar-item.hover-menu:hover > .collapse.first-level {
        display: block !important;
        height: auto !important;
      }
      .left-sidebar .sidebar-nav ul .sidebar-item.hover-menu:hover > a.has-arrow::after {
        transform: rotate(-135deg);
      }
    }

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

  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
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
  <!-- Custom Select Dropdown Transformer -->
  <script>
  (function () {
      'use strict';
      var SEMANTIC_COLORS = {
          available:'#13deb9',clean:'#13deb9',clear:'#13deb9',active:'#13deb9',confirmed:'#13deb9',
          completed:'#13deb9',validated:'#13deb9',ready:'#13deb9',paid:'#13deb9',claimed:'#13deb9',
          connected:'#13deb9',approved:'#13deb9',low:'#13deb9',vacant_clean:'#13deb9',vacant_ready:'#13deb9',
          pending:'#ffae1f',dirty:'#ffae1f',processing:'#ffae1f',medium:'#ffae1f',warning:'#ffae1f',
          stored:'#ffae1f',waiting:'#ffae1f',review:'#ffae1f',vacant_dirty:'#ffae1f',occupied_dirty:'#ffae1f',stay_over:'#ffae1f',
          cancelled:'#fa896b',maintenance:'#fa896b',blocked:'#fa896b',damaged:'#fa896b',missing:'#fa896b',
          disposed:'#fa896b',high:'#fa896b',danger:'#fa896b',rejected:'#fa896b',closed:'#fa896b',
          expired:'#fa896b',overdue:'#fa896b',stained:'#fa896b',out_of_order:'#fa896b',out_of_service:'#fa896b',double_lock:'#fa896b',
          booked:'#5d87ff',occupied:'#5d87ff',cleaning:'#5d87ff',in_progress:'#5d87ff',info:'#5d87ff',
          transfer:'#5d87ff',handed_over:'#5d87ff',checked_in:'#5d87ff',cleaning_in_progress:'#5d87ff',
          complimentary:'#5d87ff',house_use:'#5d87ff',do_not_disturb:'#5d87ff',sleep_out:'#5d87ff',
          check_in:'#5d87ff',check_out:'#5d87ff',
          unassigned:'#7c8fac',none:'#7c8fac','default':'#7c8fac',checked_out:'#7c8fac',occupied_no_luggage:'#7c8fac'
      };
      var PALETTE = ['#5d87ff','#13deb9','#ffae1f','#fa896b','#8b5cf6','#539BFF','#49beff','#dfa974','#6610f2','#d63384'];

      function generateAbbreviation(text) {
          if (!text || !text.trim()) return '-';
          var abbrMatch = text.match(/^([A-Z]{2,4})\s*[\-–—]\s*.+/);
          if (abbrMatch) return abbrMatch[1].substring(0, 3);
          var cleaned = text.replace(/\([^)]*\)/g, '').replace(/[0-9]/g, '').replace(/[\(\)\/—\-–,.:;!?]/g, ' ').trim();
          var words = cleaned.split(/\s+/).filter(function(w){ return w.length > 0; });
          if (words.length === 0) return '-';
          if (words.length >= 2) {
              return words.slice(0, 3).map(function(w){ return w[0].toUpperCase(); }).join('');
          }
          var word = words[0];
          var vowels = 'aeiouAEIOU';
          var result = word[0].toUpperCase();
          var lastAdded = result.toLowerCase();
          for (var i = 1; i < word.length && result.length < 3; i++) {
              var ch = word[i];
              if (vowels.indexOf(ch) === -1 && ch.toLowerCase() !== lastAdded) {
                  result += ch.toUpperCase();
                  lastAdded = ch.toLowerCase();
              }
          }
          if (result.length < 2) result = word.substring(0, 3).toUpperCase();
          return result;
      }

      function resolveColor(val, txt) {
          var v = (val || '').toLowerCase().replace(/[\s\-]/g, '_');
          var t = (txt || '').toLowerCase();
          if (SEMANTIC_COLORS[v]) return SEMANTIC_COLORS[v];
          var keys = Object.keys(SEMANTIC_COLORS);
          for (var i = 0; i < keys.length; i++) { if (v.indexOf(keys[i]) !== -1) return SEMANTIC_COLORS[keys[i]]; }
          for (var j = 0; j < keys.length; j++) { if (t.indexOf(keys[j]) !== -1) return SEMANTIC_COLORS[keys[j]]; }
          var hash = 0;
          for (var k = 0; k < t.length; k++) { hash = ((hash << 5) - hash) + t.charCodeAt(k); hash |= 0; }
          return PALETTE[Math.abs(hash) % PALETTE.length];
      }

      function findLabel(sel) {
          if (sel.id) { var l = document.querySelector('label[for="' + sel.id + '"]'); if (l) return l.textContent.replace(/\*/g, '').trim(); }
          var p = sel.closest('.mb-3,.mb-2,.col-md-6,.col-md-4,.col-6,.form-group');
          if (p) { var lb = p.querySelector('label'); if (lb) return lb.textContent.replace(/\*/g, '').trim(); }
          var td = sel.closest('td');
          if (td) {
              var ci = Array.prototype.indexOf.call(td.parentElement.children, td);
              var tbl = sel.closest('table');
              if (tbl) { var hdr = tbl.querySelector('thead tr'); if (hdr && hdr.children[ci]) return hdr.children[ci].textContent.trim(); }
          }
          return '';
      }

      function transformSelect(sel) {
          if (sel.getAttribute('data-cs-done')) return;
          if (sel.closest('.flatpickr-calendar,.flatpickr-month')) return;
          if (sel.hasAttribute('data-cs-skip')) return;
          if (sel.multiple) return;
          sel.setAttribute('data-cs-done', '1');

          var opts = Array.prototype.slice.call(sel.options);
          var label = findLabel(sel);
          var hasOC = sel.hasAttribute('onchange');
          var ocAttr = sel.getAttribute('onchange');

          var wrap = document.createElement('div');
          wrap.className = 'cs-dropdown position-relative d-inline-block';
          wrap.style.width = '100%';

          var selOpt = opts[sel.selectedIndex] || opts[0];
          var selTxt = selOpt ? selOpt.textContent.trim() : 'Select';

          var btn = document.createElement('button');
          btn.type = 'button';
          btn.className = 'cs-dropdown-trigger';
          btn.innerHTML = '<i class="ti ti-selector cs-trigger-icon"></i> <span class="cs-trigger-text">' + selTxt + '</span> <i class="ti ti-chevron-down cs-trigger-caret"></i>';

          var menu = document.createElement('div');
          menu.className = 'cs-dropdown-menu';
          menu.style.display = 'none';

          if (label) {
              var hd = document.createElement('div');
              hd.className = 'cs-dropdown-header';
              hd.textContent = label.toUpperCase();
              menu.appendChild(hd);
          }

          opts.forEach(function(opt, idx) {
              var item = document.createElement('div');
              item.className = 'cs-dropdown-item' + (idx === sel.selectedIndex ? ' cs-active' : '');
              item.setAttribute('data-value', opt.value);
              item.setAttribute('data-idx', idx);

              var txt = opt.textContent.trim();
              var abbr = generateAbbreviation(txt);
              var color = resolveColor(opt.value, txt);

              item.innerHTML = '<div class="cs-item-left"><span class="cs-abbr-pill" style="background:' + color + ';">' + abbr + '</span><span class="cs-item-label">' + txt + '</span></div><i class="ti ti-check cs-check-icon"></i>';

              item.addEventListener('click', function(e) {
                  e.stopPropagation();
                  sel.selectedIndex = idx;
                  sel.dispatchEvent(new Event('change', { bubbles: true }));
                  if (hasOC && ocAttr) {
                      try { (new Function(ocAttr)).call(sel); } catch(err) { console.warn('CS onchange err', err); }
                  }
                  btn.querySelector('.cs-trigger-text').textContent = txt;
                  var items = menu.querySelectorAll('.cs-dropdown-item');
                  for (var x = 0; x < items.length; x++) {
                      if (parseInt(items[x].getAttribute('data-idx')) === idx) items[x].className = 'cs-dropdown-item cs-active';
                      else items[x].className = 'cs-dropdown-item';
                  }
                  menu.style.display = 'none';
              });
              menu.appendChild(item);
          });

          btn.addEventListener('click', function(e) {
              e.stopPropagation();
              var allMenus = document.querySelectorAll('.cs-dropdown-menu');
              for (var a = 0; a < allMenus.length; a++) { if (allMenus[a] !== menu) allMenus[a].style.display = 'none'; }
              menu.style.display = (menu.style.display === 'none') ? 'block' : 'none';
          });

          wrap.appendChild(btn);
          wrap.appendChild(menu);
          sel.style.display = 'none';
          sel.parentNode.insertBefore(wrap, sel.nextSibling);
      }

      document.addEventListener('click', function() {
          var menus = document.querySelectorAll('.cs-dropdown-menu');
          for (var i = 0; i < menus.length; i++) menus[i].style.display = 'none';
      });

      function initAll(root) {
          var sels = (root || document).querySelectorAll('select.form-select, select.form-control');
          for (var i = 0; i < sels.length; i++) transformSelect(sels[i]);
      }

      if (document.readyState === 'loading') {
          document.addEventListener('DOMContentLoaded', function() { initAll(); });
      } else {
          initAll();
      }

      if (typeof MutationObserver !== 'undefined') {
          var obs = new MutationObserver(function(muts) {
              for (var m = 0; m < muts.length; m++) {
                  for (var n = 0; n < muts[m].addedNodes.length; n++) {
                      var node = muts[m].addedNodes[n];
                      if (node.nodeType === 1) {
                          if (node.tagName === 'SELECT' && (node.classList.contains('form-select') || node.classList.contains('form-control'))) {
                              setTimeout(function(){ transformSelect(node); }, 50);
                          }
                          if (node.querySelectorAll) {
                              var ss = node.querySelectorAll('select.form-select, select.form-control');
                              if (ss.length) setTimeout(function(){ for(var i=0;i<ss.length;i++) transformSelect(ss[i]); }, 50);
                          }
                      }
                  }
              }
          });
          obs.observe(document.body || document.documentElement, { childList: true, subtree: true });
      }

      window.CustomSelectTransformer = { transform: transformSelect, initAll: initAll, generateAbbreviation: generateAbbreviation };
  })();
  </script>

  <!-- Auto Nominal Formatter -->
  <script>
  (function() {
      'use strict';

      function toNominal(val) {
          var num = parseFloat(val.replace(/\./g, '').replace(/,/g, '.'));
          if (isNaN(num)) return val;
          return num.toLocaleString('id-ID');
      }

      function toRaw(val) {
          var raw = val.replace(/\./g, '').replace(/,/g, '.');
          return raw;
      }

      function formatInput(el) {
          var cursorPos = el.selectionStart;
          var oldLen = el.value.length;
          el.value = toNominal(el.value);
          var newLen = el.value.length;
          var diff = newLen - oldLen;
          if (diff > 0 && cursorPos !== null) {
              el.setSelectionRange(cursorPos + diff, cursorPos + diff);
          }
      }

      function initNominalInputs(root) {
          var inputs = (root || document).querySelectorAll('.nominal-input');
          for (var i = 0; i < inputs.length; i++) {
              (function(inp) {
                  if (inp.getAttribute('data-nominal-done')) return;
                  inp.setAttribute('data-nominal-done', '1');

                  inp.addEventListener('input', function() { formatInput(inp); });
                  inp.addEventListener('blur', function() { formatInput(inp); });

                  if (inp.value) formatInput(inp);
              })(inputs[i]);
          }
      }

      document.addEventListener('submit', function(e) {
          var form = e.target;
          var noms = form.querySelectorAll('.nominal-input');
          for (var i = 0; i < noms.length; i++) {
              noms[i].value = toRaw(noms[i].value);
          }
      });

      // Parse nominal to number helper (exposed globally)
      window.parseNominal = function(val) {
          if (typeof val !== 'string') return parseFloat(val) || 0;
          return parseFloat(val.replace(/\./g, '').replace(/,/g, '.')) || 0;
      };

      if (document.readyState === 'loading') {
          document.addEventListener('DOMContentLoaded', function() { initNominalInputs(); });
      } else {
          initNominalInputs();
      }

      if (typeof MutationObserver !== 'undefined') {
          var nomObs = new MutationObserver(function(muts) {
              for (var m = 0; m < muts.length; m++) {
                  for (var n = 0; n < muts[m].addedNodes.length; n++) {
                      var node = muts[m].addedNodes[n];
                      if (node.nodeType === 1) {
                          if (node.classList && node.classList.contains('nominal-input')) {
                              setTimeout(function(){ initNominalInputs(node.parentNode); }, 50);
                          }
                          if (node.querySelectorAll) {
                              var found = node.querySelectorAll('.nominal-input');
                              if (found.length) setTimeout(function(){ initNominalInputs(node); }, 50);
                          }
                      }
                  }
              }
          });
          nomObs.observe(document.body || document.documentElement, { childList: true, subtree: true });
      }
  })();
  </script>

  @stack('scripts')
</body>
</html>
