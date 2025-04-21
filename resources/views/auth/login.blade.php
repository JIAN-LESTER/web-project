@extends('layouts.app')

@section('content')
<head>
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<div class="container d-flex justify-content-center align-items-center vh-100" >
    <div class="card shadow-lg p-4 rounded-4" style="width: 100%; max-width: 400px; background: white;">
        <div class="text-center mb-3">
            <h3 class="fw-bold text-dark">Login</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label fw-bold">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0">
                            <i class="bi bi-envelope"></i>
                        </span>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label fw-bold">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0">
                            <i class="bi bi-lock"></i>
                        </span>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <i id="eyeIcon" class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <a href="{{ route('password.request') }}" class="text-decoration-none">Forgot Password?</a>
                </div>
                
                <button type="submit" class="btn btn-dark w-100">Login</button>
            </form>
            
            <div class="text-center mt-3">
                <p>Don't have an account yet?</p>
                <a href="{{ route('register') }}" class="btn btn-warning w-100" style="color: white; background-color: #f39c12;">Register</a>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById("togglePassword").addEventListener("click", function () {
        let passwordInput = document.getElementById("password");
        let eyeIcon = document.getElementById("eyeIcon");
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            eyeIcon.classList.replace("bi-eye", "bi-eye-slash");
        } else {
            passwordInput.type = "password";
            eyeIcon.classList.replace("bi-eye-slash", "bi-eye");
        }
    });
</script>

@if(session('success'))
    <script>
        Swal.fire({ icon: 'success', title: 'Success!', text: '{{ session('success') }}' });
    </script>
@endif
@if(session('not_verified'))
    <script>
        Swal.fire({ icon: 'warning', title: 'Email Not Verified', text: '{{ session('not_verified') }}' });
    </script>
@endif
@if(session('error'))
    <script>
        Swal.fire({ icon: 'error', title: 'Oops...', text: '{{ session('error') }}' });
    </script>
@endif
@endsection
