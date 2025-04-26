<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GuideBot Register</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Link to external CSS file -->
    <link rel="stylesheet" href="{{ asset('login_and_register/register.css') }}">
</head>
<body>
    <!-- Main container -->
    <div class="container">
        <!-- Top purple section -->
        <div class="top-section">
            <div class="logo">Logo</div>
            <div class="sign-in-text">Sign Up to</div>
            <div class="brand-name">GuideBot</div>

            <!-- Saly illustration image -->
            <div class="illustration-container">
                <img src="{{ asset('assets/images/Saly.png') }}" alt="Rocket illustration" class="rocket-illustration">
            </div>
        </div>

        <!-- Bottom white section -->
        <div class="bottom-section"></div>

        <!-- Register card that overlaps both sections -->
        <div class="login-card">
            <!-- Header with welcome text and sign in link -->
            <div class="header-section">
                <div class="welcome-text">Welcome to <span class="brand-highlight">GuideBot</span></div>
                <div class="signup-section">
                    Have an Account?<br><a href="{{ route('login') }}" class="signup-link">Sign In</a>
                </div>
            </div>

            <!-- Main heading -->
            <h1 class="card-heading">Sign Up</h1>

            <!-- Register form -->
            <form class="form-container" method="POST" action="{{ route('register') }}">
    @csrf

    <div class="input-group">
        <label for="name" class="input-label">Name</label>
        <div class="input-field-wrapper">
            <i class="fas fa-user input-icon"></i>
            <input type="text" name="name" id="name" class="input-field" placeholder="Enter your name" value="{{ old('name') }}" required>
        </div>
        @error('name')
            <div class="error-message">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-row">
        <div class="input-group half-width">
            <label for="year" class="input-label">Year Level</label>
            <div class="input-field-wrapper">
                <i class="fas fa-graduation-cap input-icon"></i>
                <select name="year_id" id="year" class="input-field">
                    <option value="">Select Year (Optional)</option>
                    @foreach ($years as $year)
                        <option value="{{ $year->yearID }}" {{ old('year_id') == $year->yearID ? 'selected' : '' }}>
                            {{ $year->year_level }}
                        </option>
                    @endforeach
                </select>
            </div>
            @error('year_id')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="input-group half-width">
            <label for="course" class="input-label">Course</label>
            <div class="input-field-wrapper">
                <i class="fas fa-book input-icon"></i>
                <select name="course_id" id="course" class="input-field" {{ old('year_id') ? '' : 'disabled' }}>
                    <option value="">Select Course (Optional)</option>
                    @foreach ($courses as $course)
                        <option value="{{ $course->courseID }}" {{ old('course_id') == $course->courseID ? 'selected' : '' }}>
                            {{ $course->course_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            @error('course_id')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="input-group">
        <label for="email" class="input-label">Email address</label>
        <div class="input-field-wrapper">
            <i class="fas fa-envelope input-icon"></i>
            <input type="email" name="email" id="email" class="input-field" placeholder="Enter your email address" value="{{ old('email') }}" required>
        </div>
        @error('email')
            <div class="error-message">{{ $message }}</div>
        @enderror
    </div>

    <div class="input-group">
        <label for="password" class="input-label">Password</label>
        <div class="input-field-wrapper">
            <i class="fas fa-lock input-icon"></i>
            <input type="password" name="password" id="password" class="input-field" placeholder="Enter your password" required>
            <i class="fas fa-eye-slash toggle-password" id="togglePassword"></i>
        </div>
        @error('password')
            <div class="error-message">{{ $message }}</div>
        @enderror
    </div>

    <div class="input-group">
        <label for="password-confirmation" class="input-label">Confirm Password</label>
        <div class="input-field-wrapper">
            <i class="fas fa-lock input-icon"></i>
            <input type="password" name="password_confirmation" id="password-confirmation" class="input-field" placeholder="Confirm your password" required>
            <i class="fas fa-eye-slash toggle-password" id="toggleConfirmPassword"></i>
        </div>
        @error('password_confirmation')
            <div class="error-message">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="login-button">Sign Up</button>
</form>

        </div>
    </div>

    <!-- SweetAlert2 Script -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Year and course dependency
            const yearSelect = document.getElementById('year');
            const courseSelect = document.getElementById('course');

            function handleYearChange() {
                const selectedValue = yearSelect.value;

                if (!selectedValue || selectedValue === '0') {
                    // If year is empty OR equals 0, disable and clear course
                    courseSelect.value = '';
                    courseSelect.disabled = true;
                } else {
                    // Else, enable course selection
                    courseSelect.disabled = false;
                }
            }

            // Listen for changes
            yearSelect.addEventListener('change', handleYearChange);

            // Check initially on page load
            handleYearChange();

            // Toggle password visibility for password field
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');

            if (togglePassword && passwordInput) {
                togglePassword.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);

                    this.classList.toggle('fa-eye');
                    this.classList.toggle('fa-eye-slash');
                });
            }

            // Toggle password visibility for confirm password field
            const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
            const confirmPasswordInput = document.getElementById('password-confirmation');

            if (toggleConfirmPassword && confirmPasswordInput) {
                toggleConfirmPassword.addEventListener('click', function() {
                    const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    confirmPasswordInput.setAttribute('type', type);

                    this.classList.toggle('fa-eye');
                    this.classList.toggle('fa-eye-slash');
                });
            }
        });
    </script>

    <!-- Display success message -->
    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            customClass: {
                container: 'my-swal-container'
            },
            didOpen: () => {
                document.querySelector('.swal2-container').style.zIndex = '10000000';
            }
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ session('error') }}',
            customClass: {
                container: 'my-swal-container'
            },
            didOpen: () => {
                document.querySelector('.swal2-container').style.zIndex = '10000000';
            }
        });
    </script>
    @endif
</body>
</html>
