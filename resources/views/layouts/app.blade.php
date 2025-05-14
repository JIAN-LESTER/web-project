<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>OASP Assist</title>
  <link rel="stylesheet" href="{{ asset('login_and_register/login.css') }}">

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <link href="https://cdn.jsdelivr.net/npm/@coreui/coreui@5.3.1/dist/css/coreui.min.css" rel="stylesheet"
    integrity="sha384-PDUiPu3vDllMfrUHnurV430Qg8chPZTNhY8RUpq89lq22R3PzypXQifBpcpE1eoB" crossorigin="anonymous">


  <!-- CoreUI CSS -->

  <style>
    /* Reset margin and overflow */
    html, body {
      margin: 0;
      padding: 0;
      overflow-x: hidden;
    }

    body {
      overflow-y: auto;
    }

    .swal2-container {
      position: fixed !important;
      z-index: 10000000 !important;
      /* Higher than any z-index in Bootstrap or CoreUI */
      padding: 0 !important;
    }

    /* Make sure the modal dialog appears properly */
    .swal2-popup {
      position: relative !important;
      box-sizing: border-box !important;
      display: flex !important;
      flex-direction: column !important;
      justify-content: center !important;
      width: 32em !important;
      max-width: 100% !important;
      padding: 1.25em !important;
      border-radius: 5px !important;
      background: #fff !important;
      font-family: inherit !important;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.4) !important;
    }

    /* Make sure the backdrop covers everything */
    .swal2-backdrop-show {
      background: rgba(0, 0, 0, 0.4) !important;
    }

    /* Stop page from scrolling when alert is shown */
    body.swal2-shown {
      overflow-y: hidden !important;
    }

    /* Override any container styles that might interfere */
    body.swal2-shown .container,
    body.swal2-shown .container-real,
    body.swal2-shown .wrapper {
      position: static !important;
    }

    .container-real {
      width: 100%;
      height: 100%;
      position: relative;
      display: flex;
      flex-direction: column;
    }

    /* Sidebar styling */
    #sidebar {
      width: 240px;
      background-color: #1e1e2d;
      color: white;
      padding: 0;
      margin: 0;
    }

    /* Fixed sidebar for large screens */
    .sidebar-fixed {
      position: fixed;
      top: 0;
      bottom: 0;
      left: 0;
      z-index: 1030;
      transition: transform 0.3s ease;
    }

    /* Content wrapper for push layout */
    .wrapper {
      transition: margin-left 0.3s ease, width 0.3s ease;
      margin: 0;
      padding: 0;
      max-width: 100%;
      overflow-x: hidden;
      min-height: 100vh;
      padding-bottom: 50px; /* Add margin at the bottom */
    }

    /* Large screen behavior (≥992px) */
    @media (min-width: 992px) {
      .wrapper {
        margin-left: 240px;
        width: calc(100% - 240px);
      }

      body.sidebar-hidden .wrapper {
        margin-left: 0;
        width: 100%;
      }

      body.sidebar-hidden #sidebar {
        transform: translateX(-100%);
      }
    }

    /* Small screen behavior (<992px) */
    @media (max-width: 991.98px) {
      #sidebar {
        transform: translateX(-100%);
      }

      #sidebar.show {
        transform: translateX(0);
        z-index: 1040;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
      }

      .wrapper {
        margin-left: 0 !important;
        width: 100%;
      }

      .sidebar-backdrop {
        content: "";
        position: fixed;
        inset: 0;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1035;
        display: none;
      }

      .sidebar-backdrop.show {
        display: block;
      }
    }

    /* Navbar styling fixes */
    .header {
      margin: 0 !important;
      padding: 0 !important;
      width: 100% !important;
      max-width: 100% !important;
      overflow: visible !important; /* Changed from hidden to visible */
      position: fixed !important; /* Changed to fixed so it stays at the top */
      top: 0 !important;
      left: 0 !important;
      right: 0 !important;
      z-index: 1500 !important; /* Extra high z-index for the navbar */
      transition: left 0.3s ease, width 0.3s ease; /* Smooth transition */
    }

    .header .container-fluid {
      margin: 0 !important;
      padding: 0 !important;
      border: none !important;
      width: 100% !important;
      max-width: 100% !important;
      overflow: visible !important; /* Added overflow visible */
      position: relative;
      z-index: 1500 !important; /* Matching z-index */
    }

    .header-nav {
      margin: 0 !important;
      padding: 0 !important;
      position: relative;
      z-index: 1500 !important; /* Matching z-index */
    }

    .mb-4 {
      margin-bottom: 0 !important;
    }

    /* Container width fix */
    .container {
      max-width: 100%;
      padding: 1rem;
      margin: 0;
      padding-top: 56px; /* Add padding for fixed header */
      margin-bottom: 50px; /* Add bottom margin */
    }

    /* Dropdown styling to match screenshot, with higher z-index */
    .dropdown-menu {
      z-index: 2000 !important; /* Higher than the header */
      min-width: 10rem;
      padding: 0.5rem 0;
      border: 1px solid rgba(0,0,0,.15);
      border-radius: 0.25rem;
      margin-top: 0.125rem;
      box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15);
      background-color: #fff;
      position: absolute !important;
    }

    /* Ensure the dropdown always appears on top */
    /* This is crucial for compatibility with user management styles */
    .header .dropdown-menu {
      position: absolute !important;
      z-index: 2000 !important; /* Much higher than any table z-index */
    }

    /* Also ensure the dropdown-toggle button is properly visible */
    .header .nav-link {
      position: relative;
      z-index: 1500 !important; /* Matching navbar z-index */
    }

    .dropdown-item {
      display: block;
      width: 100%;
      padding: 0.25rem 1.5rem;
      clear: both;
      color: #212529;
      text-align: inherit;
      white-space: nowrap;
      background-color: transparent;
      border: 0;
      font-size: 0.9rem;
    }

    .dropdown-divider {
      height: 0;
      margin: 0.5rem 0;
      overflow: hidden;
      border-top: 1px solid #e9ecef;
    }

    /* Reset all specific filter-menu z-index */
    .filter-menu,
    .users-table thead th,
    .users-table tbody td,
    .user-row,
    .table-responsive {
      /* Ensure these don't interfere with our dropdown */
      z-index: auto !important;
    }

    /* Make sure form button for logout looks like regular menu item */
    button.dropdown-item {
      text-align: left;
      background: none;
      border: none;
      width: 100%;
      font-size: 0.9rem;
      padding: 0.25rem 1.5rem;
    }

    /* Additional fixes to prevent scrolling */
    .min-vh-100 {
      min-height: 100vh !important;
      overflow-x: hidden !important;
    }

    /* Adjust header position based on sidebar state */
    @media (min-width: 992px) {
      .header {
        left: 240px !important;
        width: calc(100% - 240px) !important;
      }

      body.sidebar-hidden .header {
        left: 0 !important;
        width: 100% !important;
      }
    }
  </style>
</head>
@if (!request()->routeIs('login') && !request()->routeIs('home') && !request()->routeIs('register') && !request()->routeIs('password.request') && !request()->routeIs('password.update') && !request()->routeIs('password.reset') && !request()->routeIs('2fa.verify.form'))
    @php
    $user = Auth::user();
    @endphp

    @if (!request()->routeIs('profile') && !request()->routeIs('profile.edit'))
    <div class="sidebar sidebar-dark sidebar-fixed" id="sidebar">
        @include('partials.menu') <!-- Loads sidebar menu content -->
    </div>

    <!-- Backdrop overlay for small screen sidebar -->
    <div class="sidebar-backdrop" id="sidebar-backdrop" onclick="toggleSidebar()"></div>
    @endif

    <!-- Main Page Wrapper -->
    <div class="wrapper d-flex flex-column min-vh-100">

        <!-- Header/Navbar -->
        <header class="header header-sticky bg-white shadow-sm border-bottom" id="header">
            <div class="container-fluid d-flex align-items-center justify-content-between" style="height: 56px;">
                <!-- Left side with toggle button -->
                <button class="header-toggler" type="button" onclick="toggleSidebar()" style="margin: 0; padding-left: 12px;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg" viewBox="0 0 24 24" fill="black">
                        <path d="M4 6h16M4 12h16M4 18h16" stroke="black" stroke-width="2" stroke-linecap="round" />
                    </svg>
                </button>

                @if ($user->role === 'user' && (!request()->routeIs('profile') && !request()->routeIs('profile.edit')))
                <a href="{{ route('chat.new') }}"
                   class="btn btn-outline-primary d-none d-lg-inline-flex align-items-center gap-2 rounded-pill shadow-sm px-3 py-2"
                   style="transition: background-color 0.2s ease, box-shadow 0.2s ease;"
                   data-bs-toggle="tooltip" data-bs-placement="bottom" title="Start a new conversation">
                    <i class="bi bi-chat-dots-fill"></i>
                    <span class="fw-medium">New Chat</span>
                </a>
                @endif

                <!-- Right side with avatar and logout -->
                <div class="ms-auto d-flex align-items-center" style="z-index: 2000; gap: 1rem; padding-right: 1rem;">
                    <!-- Avatar links to Profile -->
                    <a href="{{ route('profile') }}" class="d-flex align-items-center" title="Profile"
                       style="text-decoration: none;">
                        <img src="{{ asset('storage/' . $user->avatar) }}"
                             class="rounded-circle border"
                             width="36" height="36"
                             alt="{{ $user->email }}">
                    </a>

                    <!-- Logout icon button -->
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" title="Logout"
                                class="btn btn-outline-danger d-flex align-items-center justify-content-center"
                                style="width: 36px; height: 36px; border-radius: 50%; padding: 0;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                 class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                      d="M6 3a.5.5 0 0 0 0 1h5.293L9.146 6.146a.5.5 0 1 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L11.293 3H6z"/>
                                <path fill-rule="evenodd"
                                      d="M13 8a.5.5 0 0 1-.5.5H1.5A.5.5 0 0 1 1 8v6a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V9a.5.5 0 0 1 1 0v5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V8a1 1 0 0 1 1-1h11.5a.5.5 0 0 1 .5.5z"/>
                            </svg>
                        </button>
                    </form>
                </div>

            </div>
        </header>
    @endif

    <!-- Main Page Content -->
    @php
    $route = Route::currentRouteName();
    $containerClass = in_array($route, ['login', 'register']) ? 'container-real' : 'container';
    @endphp

    <div class="{{ $containerClass }}" style="position: relative; z-index: 1;">
        @yield('content')
    </div>

    <!-- CoreUI Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/@coreui/coreui@5.3.1/dist/js/coreui.bundle.min.js"
            crossorigin="anonymous"></script>

    <!-- Sidebar Toggle Script -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Detect if we're on the profile page and close the sidebar
        if (window.location.href.includes("user/profile")) {
            // Close sidebar if the route is 'profile'
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            const header = document.getElementById('header');

            // Hide the sidebar and remove backdrop
            sidebar.classList.remove('show');
            backdrop.classList.remove('show');
            if (header) {
                header.style.left = '0';
                header.style.width = '100%';
            }
        }
    });

    // Sidebar Toggle Function
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const backdrop = document.getElementById('sidebar-backdrop');
        const header = document.getElementById('header');
        const isLarge = window.innerWidth >= 992;

        if (isLarge) {
            document.body.classList.toggle('sidebar-hidden');
            if (document.body.classList.contains('sidebar-hidden')) {
                // Adjust the header and content width when sidebar is hidden
                header.style.left = '0';
                header.style.width = '100%';
            } else {
                header.style.left = '240px';
                header.style.width = 'calc(100% - 240px)';
            }
        } else {
            sidebar.classList.toggle('show');
            backdrop.classList.toggle('show');
        }
    }

    // Handle sidebar close button
    document.addEventListener('DOMContentLoaded', function() {
        const closeBtn = document.querySelector('.btn-close');
        if (closeBtn) {
            closeBtn.onclick = function(e) {
                e.preventDefault();
                toggleSidebar();
            };
        }

        // Ensure the navbar has highest z-index and fixed position
        const header = document.querySelector('.header');
        if (header) {
            header.style.zIndex = '1500';
            header.style.position = 'fixed';
            header.style.top = '0';

            // Set initial position based on screen size
            if (window.innerWidth >= 992 && !document.body.classList.contains('sidebar-hidden')) {
                header.style.left = '240px';
                header.style.width = 'calc(100% - 240px)';
            } else {
                header.style.left = '0';
                header.style.width = '100%';
            }
        }
    });

    // Automatically hide mobile sidebar on resize to desktop
    window.addEventListener('resize', () => {
        const sidebar = document.getElementById('sidebar');
        const backdrop = document.getElementById('sidebar-backdrop');
        const header = document.querySelector('.header');

        if (window.innerWidth >= 992) {
            sidebar.classList.remove('show');
            backdrop.classList.remove('show');

            // Update header position on resize
            if (header) {
                if (document.body.classList.contains('sidebar-hidden')) {
                    header.style.left = '0';
                    header.style.width = '100%';
                } else {
                    header.style.left = '240px';
                    header.style.width = 'calc(100% - 240px)';
                }
            }
        } else {
            if (header) {
                header.style.left = '0';
                header.style.width = '100%';
            }
        }
    });
</script>

</body>

</html>
