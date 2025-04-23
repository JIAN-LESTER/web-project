<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GuideBot Register</title>
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
                    Have an Account ?<br><a href="{{ route('login') }}" class="signup-link">Sign In</a>
                </div>
            </div>

            <!-- Main heading -->
            <h1 class="card-heading">Sign Up</h1>

            <!-- Login form -->
            <form class="form-container" method="POST" action="{{ route('register') }}">
                @csrf

                <div class="input-group">
                    <label for="name" class="input-label">Name</label>
                    <input type="text" name="name" id="name" class="input-field" placeholder="Enter Name" required>
                </div>

                <div class="form-row">
                    <div class="input-group half-width">
                        <label for="year" class="input-label">Year Level</label>
                        <div class="input-wrapper">
                            <select name="year_id" id="year" class="input-field">
                                <option value="">Select Year  (Optional)</option>
                                @foreach ($years as $year)
                                    <option value="{{ $year->yearID }}">{{ $year->year_level }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="input-group half-width">
                        <label for="course" class="input-label">Course</label>
                        <div class="input-wrapper">
                            <select name="course_id" id="course" class="input-field">
                                <option value="">Select Course (Optional)</option>
                                @foreach ($courses as $course)
                                    <option value="{{ $course->courseID }}">{{ $course->course_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="input-group">
                    <label for="email" class="input-label">Email address</label>
                    <input type="email" name="email" id="email" class="input-field" placeholder="Enter Email address" required>
                </div>

                <div class="input-group">
                    <label for="password" class="input-label">Password</label>
                    <input type="password" name="password" id="password" class="input-field" placeholder="Enter Password" required>
                </div>

                <div class="input-group">
                    <label for="password-confirmation" class="input-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password-confirmation" class="input-field" placeholder="Confirm Password" required>
                </div>

                <button type="submit" class="login-button">Sign Up</button>
            </form>
        </div>
    </div>

    <!-- SweetAlert2 Script -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Display success message -->
    @if(session('success'))
    <script>
        Swal.fire({ icon: 'success', title: 'Success!', text: '{{ session('success') }}' });
    </script>
@endif
</body>
</html>
