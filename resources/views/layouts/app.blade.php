<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Laravel App</title>
  <link rel="stylesheet" href="{{asset('app.css')}}">
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

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
      <a class="navbar-brand text-white" href="#">Laboratory Activity</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto" style="background-color: transparent;">

        @if (auth()->check() && auth()->user()->role === 'admin')
      <li class="nav-item"><a class="nav-link text-white" href="{{ route('products.index') }}">Products</a></li>
      <li class="nav-item"><a class="nav-link text-white" href="{{ route('admin.index')}}">Users</a></li>
      <li class="nav-item"><a class="nav-link text-white" href="{{ route('logs.index')}}">Logs</a></li>
      <li class="nav-item"><a class="nav-link text-white" href="{{ route('admin.profile')}}">Profile</a></li>

    @elseif(auth()->check() && auth()->user()->role === 'user')
    <li class="nav-item"><a class="nav-link text-white" href="{{ route('user.index') }}">Home</a></li>
    <li class="nav-item"><a class="nav-link text-white" href="{{ route('user.store') }}">Store</a></li>
    <li class="nav-item"><a class="nav-link text-white" href="{{ route('user.profile')}}">Profile</a></li>
  @endif

        <li class="nav-item">
        <a class="nav-link text-white" href="{{ route('logout') }}"
          onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
          Logout
        </a>
        </li>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
        </form>
      </ul>
      </div>
  @endif
    </div>
  </nav>
  <div class="container mt-4">@yield('content')</div>

</body>

</html>