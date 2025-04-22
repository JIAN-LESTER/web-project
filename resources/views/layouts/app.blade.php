<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Web Project</title>

  <!-- CoreUI CSS -->
  <link href="https://cdn.jsdelivr.net/npm/@coreui/coreui@5.3.1/dist/css/coreui.min.css" rel="stylesheet" integrity="sha384-PDUiPu3vDllMfrUHnurV430Qg8chPZTNhY8RUpq89lq22R3PzypXQifBpcpE1eoB" crossorigin="anonymous">

  <style>
    /* Reset margin */
    body {
      margin: 0;
    }

    /* Sidebar styling */
    #sidebar {
      width: 240px;
      background-color: #1e1e2d;
      color: white;
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
      transition: margin-left 0.3s ease;
    }

    /* Large screen behavior (≥992px) */
    @media (min-width: 992px) {
      .wrapper {
        margin-left: 240px;
      }

      body.sidebar-hidden .wrapper {
        margin-left: 0;
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

    /* Offset for sticky header */
    .body {
      margin-top: 70px;
    }

    /* Icon size */
    .icon-lg {
      width: 24px;
      height: 24px;
    }
  </style>
</head>
<body>
@if (!request()->routeIs('home') && !request()->routeIs('login') && !request()->routeIs('register') && !request()->routeIs('password.request') && !request()->routeIs('password.update') && !request()->routeIs('password.reset') && !request()->routeIs('2fa.verify.form'))


  <!-- Sidebar Content -->
  <div class="sidebar sidebar-dark sidebar-fixed" id="sidebar">
    @include('partials.menu') <!-- Loads sidebar menu content -->
  </div>

  <!-- Backdrop overlay for small screen sidebar -->
  <div class="sidebar-backdrop" id="sidebar-backdrop" onclick="toggleSidebar()"></div>



    <!-- Header/Navbar -->
    <header class="header header-sticky p-0 mb-4 bg-white shadow-sm">
      <div class="container-fluid border-bottom px-4 d-flex align-items-center justify-content-between">

        <!--  MENU ICON (BURGER ICON) -->
        <!-- This button toggles the sidebar on both desktop and mobile -->
        <!-- The SVG below is the visible black burger icon -->
        <button class="header-toggler" type="button" onclick="toggleSidebar()" style="margin-inline-start: -14px;">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg" viewBox="0 0 24 24" fill="black">
            <path d="M4 6h16M4 12h16M4 18h16" stroke="black" stroke-width="2" stroke-linecap="round" />
          </svg>
        </button>
        <!-- END MENU ICON -->

        <!-- Header right-side icons (notifications, profile, etc.) -->
        <ul class="header-nav ms-auto d-flex align-items-center">
          <li class="nav-item">
            <a class="nav-link" href="#">
              <svg class="icon icon-lg">
                <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-bell"></use>
              </svg>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
              <svg class="icon icon-lg">
                <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-list-rich"></use>
              </svg>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
              <svg class="icon icon-lg">
                <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-envelope-open"></use>
              </svg>
            </a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-coreui-toggle="dropdown">
              <div class="avatar avatar-md">
                <img class="avatar-img" src="assets/img/avatars/8.jpg" alt="user@email.com">
              </div>
            </a>
            <div class="dropdown-menu dropdown-menu-end">
              <a class="dropdown-item" href="#">Profile</a>
              <a class="dropdown-item" href="#">Settings</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="#">Logout</a>
            </div>
          </li>
        </ul>
      </div>
    </header>

    @endif

    <!-- Main Page Content -->
  
    <div class=" container-fluid px-0">
    @yield('content') <!-- Content placeholder -->
    </div>

  <!-- CoreUI Bundle JS -->
  <script src="https://cdn.jsdelivr.net/npm/@coreui/coreui@5.3.1/dist/js/coreui.bundle.min.js" crossorigin="anonymous"></script>

  <!-- Sidebar Toggle Script -->
  <script>
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      const backdrop = document.getElementById('sidebar-backdrop');
      const isLarge = window.innerWidth >= 992;

      if (isLarge) {
        document.body.classList.toggle('sidebar-hidden');
      } else {
        sidebar.classList.toggle('show');
        backdrop.classList.toggle('show');
      }
    }

    // Automatically hide mobile sidebar on resize to desktop
    window.addEventListener('resize', () => {
      const sidebar = document.getElementById('sidebar');
      const backdrop = document.getElementById('sidebar-backdrop');

      if (window.innerWidth >= 992) {
        sidebar.classList.remove('show');
        backdrop.classList.remove('show');
      }
    });
    </script>

</body>
</html>
