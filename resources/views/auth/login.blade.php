<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GuideBot Login</title>
    <!-- Link to external CSS file -->
    <link rel="stylesheet" href="{{ asset('login_and_register/login.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <!-- Main container -->
    <div class="container">
        <!-- Top purple section -->
        <div class="top-section">
            <div class="logo">Logo</div>
            <div class="sign-in-text">Sign In to</div>
            <div class="brand-name">GuideBot</div>

            <!-- Saly illustration image -->
            <div class="illustration-container">
                <img src="assets/images/Saly.png" alt="Rocket illustration" class="rocket-illustration">
            </div>
        </div>

        <!-- Bottom white section -->
        <div class="bottom-section"></div>

        <!-- Login card that overlaps both sections -->
        <div class="login-card">
            <!-- Header with welcome text and sign up link -->
            <div class="header-section">
                <div class="welcome-text">Welcome to <span class="brand-highlight">GuideBot</span></div>
                <div class="signup-section">
                    No Account?<br><a href="{{ route('register') }}" class="signup-link">Sign Up</a>
                </div>
            </div>

            <!-- Main heading -->
            <h1 class="card-heading">Sign In</h1>

            <!-- Login form -->
            <form class="form-container" method="POST" action="{{ route('login') }}">
                @csrf

                <div class="input-group">
                    <label for="email" class="input-label">Email address</label>
                    <input type="email" name="email" id="email" class="input-field" placeholder="Enter your email address" required>
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="input-group">
                    <label for="password" class="input-label-password">Password</label>
                    <input type="password" name="password" id="password" class="input-field" placeholder="Enter your password" required>
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                    <div class="forgot-password-container">
                        <a href="{{ route('password.request') }}" class="forgot-password">Forgot Password?</a>
                    </div>
                </div>

                <button type="submit" class="login-button">Sign In</button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                allowOutsideClick: false,
                backdrop: true
            });
        </script>
    @endif

    @if(session('not_verified'))
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'Email Not Verified',
                text: '{{ session('not_verified') }}'
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session('error') }}',
                allowOutsideClick: false,
                backdrop: true
            });
        </script>
    @endif
</body>
</html>
