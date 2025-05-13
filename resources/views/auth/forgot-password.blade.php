<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GuideBot - Forgot Password</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Link to external CSS file -->
    <link rel="stylesheet" href="{{ asset('login_and_register/forgot-password.css') }}">
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
                    <h1>Forgot Password?</h1>
                    <p>Don't worry! It happens to the best of us.<br>Enter your email and we'll send you a reset link.</p>
                    <img src="{{ asset('assets/images/group104.png') }}" alt="Forgot password illustration" class="panel-image">
                </div>
            </div>

            <!-- Right panel (dark) -->
            <div class="form-panel right-panel">
                <div class="logo">
                    <i class="fas fa-robot"></i>
                    <span>GuideBot</span>
                </div>

                <div class="form-header">
                    <h2>Reset Password</h2>
                    <p>Enter your email to receive a password reset link</p>
                </div>

                <!-- Alert messages container -->
                <div id="alert-container">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i>
                            <div class="alert-content">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <span class="close-alert">&times;</span>
                        </div>
                    @endif

                    @if (session('status'))
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            <div class="alert-content">
                                <p>{{ session('status') }}</p>
                            </div>
                            <span class="close-alert">&times;</span>
                        </div>
                    @endif
                </div>

                <!-- Forgot password form -->
                <form class="forgot-form" method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-icon"><i class="fas fa-envelope"></i></span>
                            <input type="email" name="email" id="email" placeholder="Email Address" required value="{{ old('email') }}">
                            <label for="email">Email Address</label>
                            <span class="focus-border"></span>
                        </div>
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn-submit" id="submit-btn">
                        <span class="btn-text">Send Reset Link</span>
                        <span class="btn-icon"><i class="fas fa-arrow-right"></i></span>
                        <div class="spinner"></div>
                    </button>

                    <div class="form-footer">
                        <div class="security-note">
                            <i class="fas fa-shield-alt"></i>
                            <p>We'll never share your email with anyone else.</p>
                        </div>

                        <div class="alternate-action">
                            <a href="{{ route('login') }}" class="back-to-login">
                                <i class="fas fa-chevron-left"></i> Back to Sign In
                            </a>
                        </div>
                    </div>
                </form>

                <div class="password-tips">
                    <h3>Password Security Tips</h3>
                    <ul class="tips-list">
                        <li class="tip-item">
                            <div class="tip-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="tip-content">
                                <h4>Use Strong Passwords</h4>
                                <p>Create passwords that are at least 12 characters long with a mix of letters, numbers, and special characters.</p>
                            </div>
                        </li>
                        <li class="tip-item">
                            <div class="tip-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="tip-content">
                                <h4>Don't Reuse Passwords</h4>
                                <p>Use different passwords for different accounts to prevent multiple accounts from being compromised.</p>
                            </div>
                        </li>
                        <li class="tip-item">
                            <div class="tip-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="tip-content">
                                <h4>Update Passwords Regularly</h4>
                                <p>Change important passwords every 3-6 months, especially for accounts containing sensitive information.</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const emailInput = document.getElementById('email');
            const submitBtn = document.getElementById('submit-btn');
            const form = document.querySelector('.forgot-form');

            // Close alert messages
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
            emailInput.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });

            emailInput.addEventListener('blur', function() {
                this.parentElement.classList.remove('focused');
                if (this.value.trim() !== '') {
                    this.classList.add('has-value');
                } else {
                    this.classList.remove('has-value');
                }
                validateEmail();
            });

            // Email validation
            emailInput.addEventListener('input', function() {
                validateEmail();
            });

            function validateEmail() {
                const email = emailInput.value.trim();
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                const inputField = emailInput.parentElement;

                if (email === '') {
                    inputField.classList.remove('valid', 'invalid');
                } else if (emailRegex.test(email)) {
                    // Apply the valid class immediately without animation
                    inputField.classList.add('valid');
                    inputField.classList.remove('invalid');
                } else {
                    // Apply the invalid class immediately without animation
                    inputField.classList.add('invalid');
                    inputField.classList.remove('valid');
                }
            }

            // Form submission with loading animation
            form.addEventListener('submit', function(e) {
                const email = emailInput.value.trim();
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if (!emailRegex.test(email)) {
                    e.preventDefault();
                    emailInput.parentElement.classList.add('shake');
                    setTimeout(() => {
                        emailInput.parentElement.classList.remove('shake');
                    }, 600);
                    return;
                }

                submitBtn.classList.add('loading');
                // Form will submit normally
            });

            // Activate security tips immediately
            const tipItems = document.querySelectorAll('.tip-item');
            tipItems.forEach((item) => {
                item.classList.add('active');
            });
        });
    </script>
</body>
</html>
