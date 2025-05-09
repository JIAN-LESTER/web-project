<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GuideBot Register</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Link to external CSS file -->
    <link rel="stylesheet" href="{{ asset('login_and_register/register.css') }}">
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
                    <h1>Join GuideBot</h1>
                    <p>Create an account to start your journey with GuideBot and access personalized resources.</p>
                    <img src="{{ asset('assets/images/group105.png') }}" alt="Register illustration" class="panel-image">
                </div>
            </div>

            <!-- Right panel (dark) -->
            <div class="form-panel right-panel">
                <div class="logo">
                    <i class="fas fa-robot"></i>
                    <span>GuideBot</span>
                </div>

                <div class="form-header">
                    <h2>Create Account</h2>
                    <p>Fill in your details to get started</p>
                </div>

                <!-- Error messages -->
                @if(session('error'))
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <div>
                            <strong>Registration Failed</strong>
                            <p>{{ session('error') }}</p>
                        </div>
                        <span class="close-alert">&times;</span>
                    </div>
                @endif

                <!-- Registration form -->
                <form class="register-form" method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-icon"><i class="fas fa-user"></i></span>
                            <input type="text" name="name" id="name" required value="{{ old('name') }}">
                            <label for="name">Full Name</label>
                            <span class="focus-border"></span>
                        </div>
                        @error('name')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group half-width">
                            <div class="input-group">
                                <span class="input-icon"><i class="fas fa-graduation-cap"></i></span>
                                <select name="year_id" id="year" class="select-field">
                                    <option value="" selected disabled>Select Year</option>
                                    @foreach ($years as $year)
                                        <option value="{{ $year->yearID }}" {{ old('year_id') == $year->yearID ? 'selected' : '' }}>
                                            {{ $year->year_level }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="focus-border"></span>
                            </div>
                            @error('year_id')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group half-width">
                            <div class="input-group">
                                <span class="input-icon"><i class="fas fa-book"></i></span>
                                <select name="course_id" id="course" class="select-field" {{ old('year_id') ? '' : 'disabled' }}>
                                    <option value="" selected disabled>Select Course</option>
                                    @foreach ($courses as $course)
                                        <option value="{{ $course->courseID }}" {{ old('course_id') == $course->courseID ? 'selected' : '' }}>
                                            {{ $course->course_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="focus-border"></span>
                            </div>
                            @error('course_id')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

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
                            <span class="toggle-password" onclick="togglePassword('password')">
                                <i class="fa fa-eye-slash"></i>
                            </span>
                            <span class="focus-border"></span>
                        </div>
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-icon"><i class="fas fa-lock"></i></span>
                            <input type="password" name="password_confirmation" id="password-confirmation" required>
                            <label for="password-confirmation">Confirm Password</label>
                            <span class="toggle-password" onclick="togglePassword('password-confirmation')">
                                <i class="fa fa-eye-slash"></i>
                            </span>
                            <span class="focus-border"></span>
                        </div>
                        @error('password_confirmation')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group terms-checkbox">
                        <label class="checkbox-label">
                            <input type="checkbox" name="terms" id="terms" required>
                            <span class="checkbox-custom"></span>
                            <span class="checkbox-text">I agree to the <a href="#" class="terms-link">Terms of Service</a> and <a href="#" class="terms-link">Privacy Policy</a></span>
                        </label>
                        @error('terms')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn-register">
                        <span class="btn-text">Create Account</span>
                        {{-- <span class="btn-icon"><i class="fas fa-arrow-right"></i></span> --}}
                    </button>

                    <div class="separator">
                        <span>OR</span>
                    </div>

                    <div class="social-login">
                        <a href="#" class="social-btn google">
                            <i class="fab fa-google"></i>
                            <span>Sign up with Google</span>
                        </a>
                    </div>

                    <div class="login-link">
                        Already have an account? <a href="{{ route('login') }}">Sign In</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 Script -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- JavaScript -->
    <script>
        function togglePassword(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const icon = passwordInput.nextElementSibling.querySelector('i');

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

        document.addEventListener('DOMContentLoaded', function() {
            // Year and course dependency
            const yearSelect = document.getElementById('year');
            const courseSelect = document.getElementById('course');

            function handleYearChange() {
                if (yearSelect.value) {
                    courseSelect.disabled = false;
                } else {
                    courseSelect.disabled = true;
                    courseSelect.value = '';
                }
            }

            if (yearSelect) {
                yearSelect.addEventListener('change', handleYearChange);

                // Initialize on page load
                handleYearChange();
            }

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
