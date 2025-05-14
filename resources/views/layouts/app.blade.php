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

                <!-- Right side with avatar dropdown -->
                <div class="ms-auto d-flex align-items-center" style="z-index: 2000; gap: 1rem; padding-right: 1rem;">
                    <!-- User dropdown menu -->
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle d-flex align-items-center text-decoration-none"
                           id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ asset('storage/' . $user->avatar) }}"
                                 class="rounded-circle border"
                                 width="36" height="36"
                                 alt="{{ $user->email }}">
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#profileModal">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person me-2" viewBox="0 0 16 16">
                                        <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664z"/>
                                    </svg>
                                    Profile
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-right me-2" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>
                                            <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
                                        </svg>
                                        Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
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

    <!-- Profile Modal -->
    <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="profileModalLabel">User Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <img src="{{ asset('storage/' . $user->avatar) }}" class="rounded-circle img-thumbnail" width="120" height="120" alt="{{ $user->email }}">
                        <h4 class="mt-2">{{ $user->name }}</h4>
                        <p class="text-muted">{{ $user->email }}</p>
                        <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : 'primary' }}">{{ ucfirst($user->role) }}</span>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">User ID</h6>
                            <p>{{ $user->userID }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Created On</h6>
                            <p>{{ $user->created_at->format('M d, Y') }}</p>
                        </div>
                        @if ($user->role === 'user')
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Year Level</h6>
                            <p>{{ $user->year ? $user->year->year_level : 'Not set' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Course</h6>
                            <p>{{ $user->course ? $user->course->course_name : 'Not set' }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal" data-bs-dismiss="modal">Edit Profile</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editProfileForm" action="{{ route('profile.update', $user->userID) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3 text-center">
                            <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}"
                                alt="User Avatar" class="rounded-circle mb-3 border" width="120" height="120">
                        </div>

                        <div class="mb-3">
                            <label for="avatar" class="form-label">Upload Avatar</label>
                            <input type="file" name="avatar" id="avatar" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}" required>
                        </div>

                        @if ($user->role === 'user')
                        <div class="mb-3">
                            <label for="year" class="form-label">Year</label>
                            <select name="year_id" id="year" class="form-select">
                                <option value="">Select Year</option>
                                @foreach ($years ?? [] as $year)
                                    <option value="{{ $year->yearID }}" {{ $user->yearID == $year->yearID ? 'selected' : '' }}>
                                        {{ $year->year_level }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="course" class="form-label">Course</label>
                            <select name="course_id" id="course" class="form-select">
                                <option value="">Select Course</option>
                                @foreach ($courses ?? [] as $course)
                                    <option value="{{ $course->courseID }}" {{ $user->courseID == $course->courseID ? 'selected' : '' }}>
                                        {{ $course->course_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <hr>
                        <h5>Change Password (Optional)</h5>

                        <div class="mb-3">
                            <label for="old_password" class="form-label">Current Password</label>
                            <input type="password" name="old_password" id="old_password" class="form-control">
                            <div class="invalid-feedback" id="old-password-error"></div>
                        </div>

                        <div class="mb-3">
                            <label for="new_password" class="form-label">New Password</label>
                            <input type="password" name="new_password" id="new_password" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" onclick="document.getElementById('editProfileForm').submit()">Update Profile</button>
                </div>
            </div>
        </div>
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

        // Initialize Bootstrap tooltips and dropdowns
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'))
        var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
            return new bootstrap.Dropdown(dropdownToggleEl)
        });

        // Handle success messages with SweetAlert
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}'
            });
        @endif

        // Handle validation errors for the profile form
        @if($errors->any())
            // If the errors are likely for the profile form, show the edit profile modal
            if ({{ $errors->has('name') || $errors->has('email') || $errors->has('avatar') ||
                  $errors->has('old_password') || $errors->has('new_password') ? 'true' : 'false' }}) {
                var editProfileModal = new bootstrap.Modal(document.getElementById('editProfileModal'));
                editProfileModal.show();

                // Display validation errors
                @foreach($errors->all() as $error)
                    // You could add code here to highlight the specific fields with errors
                    console.error('Validation error: {{ $error }}');
                @endforeach
            }
        @endif
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
        const closeBtn = document.querySelector('.sidebar .btn-close');
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
