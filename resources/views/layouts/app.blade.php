<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Laravel App</title>

  <link rel="stylesheet" href="{{ asset('app.css') }}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    .default-bg {
      background: #f8f9fa;
      background-size: cover;
      background-position: center;
      min-height: 100vh;
    }

    .sidebar {
      height: 100vh;
      position: fixed;
      left: 0;
      top: 0;
      bottom: 0;
      padding-top: 60px;
      width: 250px;
      background-color: #343a40;
      color: white;
    }

    .sidebar a {
      color: white;
      display: block;
      padding: 10px 20px;
      text-decoration: none;
    }

    .sidebar a:hover {
      background-color: #495057;
    }

    .content {
      margin-left: 250px;
      padding: 20px;
    }
  </style>
</head>

<body class="{{ !request()->routeIs('login')
  && !request()->routeIs('register')
  && !request()->routeIs('password.request')
  && !request()->routeIs('password.update')
  && !request()->routeIs('password.reset')
  && !request()->routeIs('2fa.verify.form')
  ? 'default-bg' : '' }}" style="@yield('page-background')">

  @if (!request()->routeIs('login') && !request()->routeIs('register') && !request()->routeIs('password.request') && !request()->routeIs('password.update') && !request()->routeIs('password.reset') && !request()->routeIs('2fa.verify.form'))

    <!-- Sidebar -->
    <div class="sidebar">
      <div class="text-center mb-4">
        <h4>Lab Activity</h4>
      </div>

      @if (auth()->check() && auth()->user()->role === 'admin')
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <a href="{{ route('admin.documents') }}">Documents</a>
        <a href="{{ route('admin.user_management') }}">Users</a>
        <a href="{{ route('admin.reports_analytics') }}">Reports and Analytics</a>
        <a href="{{ route('admin.logs') }}">Logs</a>
      @elseif(auth()->check() && auth()->user()->role === 'user')
        <a href="{{ route('user.index') }}">Home</a>
        <a href="{{ route('user.store') }}">Store</a>
        <a href="{{ route('user.profile') }}">Profile</a>
      @endif

      <a href="{{ route('logout') }}"
         onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        Logout
      </a>

      <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
      </form>
    </div>

    <!-- Main Content -->
    <div class="content">
      @yield('content')
    </div>

  @else
    <!-- Auth Pages -->
    <div class="container mt-5">
      @yield('content')
    </div>
  @endif

</body>
</html>
