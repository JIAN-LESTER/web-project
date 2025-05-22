<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OASP Chat Login</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Link to external CSS file -->
    <link rel="stylesheet" href="{{ asset('login_and_register/login.css') }}">
</head>

<body>
    <div class="container">
        <!-- Background animation elements -->
        <div class="background-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
            <div class="shape shape-4"></div>
        </div>

        <div class="form-container">
            <!-- Left panel (green) -->
            <div class="form-panel left-panel">
                <div class="content">
                    <h1>Welcome Back!</h1>
                    <p>Sign in to continue your journey with OASP Chat and access all your personalized content.</p>
                    <img src="{{ asset('assets/images/group101.png') }}" alt="Login illustration" class="panel-image">
                </div>
            </div>

            <!-- Right panel (dark) -->
            <div class="form-panel right-panel">
                <div class="logo">
                    <i class="fas fa-robot"></i>
                    <span>OASP Assist</span>
                </div>

                <div class="form-header">
                    <h2>Sign In</h2>
                    <p>Welcome back! Please login to your account</p>
                </div>

                <!-- Login form -->
                <form class="login-form" method="POST" action="{{ route('login') }}">
                    @csrf

                    @if(session('account_locked'))
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i>
                            <div>
                                <strong>Account Locked</strong>
                                <p>Please try again in {{ session('lockout_timer') }}.</p>
                            </div>
                            <span class="close-alert">&times;</span>
                        </div>
                    @elseif(session('error'))
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i>
                            <div>
                                <strong>Authentication Failed</strong>
                                <p>{{ session('error') }}
                                @if(session('remaining_attempts') !== null)
                                    <br>Remaining Attempts: {{ session('remaining_attempts') }}/5
                                @endif
                                </p>
                            </div>
                            <span class="close-alert">&times;</span>
                        </div>
                    @endif

                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-icon"><i class="fas fa-envelope"></i></span>
                            <input type="email" name="email" id="email" required value="{{ old('email') }}">
                            <label for="email">Email Address</label>
                            <span class="focus-border"></span>
                        </div>
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-icon"><i class="fas fa-lock"></i></span>
                            <input type="password" name="password" id="password" required>
                            <label for="password">Password</label>
                            <span class="toggle-password" onclick="togglePassword()">
                                <i class="fa fa-eye-slash"></i>
                            </span>
                            <span class="focus-border"></span>
                        </div>
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-options">
                        <div class="checkbox-wrapper">
                          
                        </div>
                        <a href="{{ route('password.request') }}" class="forgot-password">Forgot Password?</a>
                    </div>

                    <button type="submit" class="btn-login">
                        <span class="btn-text">Sign In</span>
                        {{-- <span class="btn-icon"><i class="fas fa-arrow-right"></i></span> --}}
                    </button>

                    <div class="separator">
                        <span>OR</span>
                    </div>

                    <!-- <div class="social-login">
                        <a href="#" class="social-btn google">
                            <i class="fab fa-google"></i>
                            <span>Sign in with Google</span>
                        </a>
                    </div> -->

                    <div class="register-link">
                        Don't have an account? <a href="{{ route('register') }}">Sign Up</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 Script -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- JavaScript -->
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const icon = document.querySelector('.toggle-password i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        }

        // Close alert messages
        document.addEventListener('DOMContentLoaded', function() {
            const closeButtons = document.querySelectorAll('.close-alert');
            closeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const alert = this.parentElement;
                    alert.classList.add('fade-out');
                    setTimeout(() => {
                        alert.style.display = 'none';
                    }, 300);
                });
            });

            // Auto-hide alerts after 8 seconds
            const alerts = document.querySelectorAll('.alert');
            if (alerts.length > 0) {
                setTimeout(() => {
                    alerts.forEach(alert => {
                        alert.classList.add('fade-out');
                        setTimeout(() => {
                            alert.style.display = 'none';
                        }, 300);
                    });
                }, 8000);
            }

            // Add focus animation to input fields
            const inputs = document.querySelectorAll('.input-group input');
            inputs.forEach(input => {
                // Check if input has value on page load
                if (input.value.trim() !== '') {
                    input.classList.add('has-value');
                }

                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('focused');
                });

                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('focused');
                    if (this.value.trim() !== '') {
                        this.classList.add('has-value');
                    } else {
                        this.classList.remove('has-value');
                    }
                });
            });
        });
    </script>

    <!-- Email verification success -->
    @if(session('email_verified'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Email Verified!',
                    text: 'Your email has been verified successfully. You can now access all features.',
                    confirmButtonText: 'Continue',
                    confirmButtonColor: '#0D6832',
                    timer: 5000,
                    timerProgressBar: true,
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    }
                });
            });
        </script>
    @endif

    <!-- Not verified email -->
    @if(session('not_verified'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'warning',
                    title: 'Email Not Verified',
                    html: `
                        <p>{{ session('not_verified') }}</p>
                        <div class="verification-benefits">
                            <p><strong>Why verify your email?</strong></p>
                            <ul>
                                <li>Secure your account</li>
                                <li>Access all OASP Assist features</li>
                                <li>Receive important notifications</li>
                            </ul>
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Resend Verification Email',
                    cancelButtonText: 'Later',
                    confirmButtonColor: '#0D6832',
                    cancelButtonColor: '#6c757d',
                    footer: '<a href="#">Need help with verification?</a>'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading state
                        Swal.fire({
                            title: 'Sending...',
                            html: 'Sending verification email to your inbox',
                            timerProgressBar: true,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Simulate sending email (replace with actual AJAX call)
                        setTimeout(() => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Email Sent!',
                                text: 'Please check your inbox for the verification link',
                                confirmButtonColor: '#0D6832'
                            });
                        }, 2000);
                    }
                });
            });
        </script>
    @endif

    <!-- Success message -->
    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#0D6832',
                timer: 3000,
                timerProgressBar: true
            });
        </script>
    @endif
</body>
</html>
