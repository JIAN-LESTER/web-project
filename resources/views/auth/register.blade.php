<!-- @extends('layouts.app')
@section('content')

@endsection -->
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AcadBot Register</title>
    <!-- Link to external CSS file -->
    <link rel="stylesheet" href="{{ asset('login_and_register/register.css') }}">
</head>
<body>
    <!-- Main container -->
    
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
                    Have an Account? <br><a href="{{ route('login') }}" class="signup-link">Sign In</a>
                </div>
            </div>

            <!-- Main heading -->
            <h1 class="card-heading">Sign Up</h1>

            <!-- Login form -->
            <form class="form-container" method="POST" action="{{ route('register') }}">
    @csrf

    <div class="input-group">
    <label for="name" class="input-label">Name</label>
    <input type="text" name="name" id="name" class="input-field" placeholder="Enter your name" required>
</div>

<div class="input-group">
    <label for="email" class="input-label">Email</label>
    <input type="email" name="email" id="email" class="input-field" placeholder="Enter your email address" required>
</div>

<div class="input-group">
    <label for="password" class="input-label">Password</label>
    <input type="password" name="password" id="password" class="input-field" placeholder="Enter your password" required>
</div>

<div class="input-group">
    <label for="password-confirmation" class="input-label">Confirm Password</label>
    <input type="password" name="password_confirmation" id="password-confirmation" class="input-field" placeholder="Confirm your password" required>
</div>

<div class="input-group">
    <label for="year" class="input-label">Year Level (Optional)</label>
    <div class="input-wrapper">
        <span class="input-icon">
            <i class="bi bi-calendar"></i>
        </span>
        <select name="year_id" id="year" class="input-field">
            <option value="">Select year level</option>
            @foreach ($years as $year)
                <option value="{{ $year->yearID }}">{{ $year->year_level }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="input-group">
    <label for="course" class="input-label">Course (Optional)</label>
    <div class="input-wrapper">
        <span class="input-icon">
            <i class="bi bi-book"></i>
        </span>
        <select name="course_id" id="course" class="input-field">
            <option value="">Select course</option>
            @foreach ($courses as $course)
                <option value="{{ $course->courseID }}">{{ $course->course_name }}</option>
            @endforeach
        </select>
    </div>
</div>

    <button type="submit" class="login-button">Sign Up</button>
</form>

        </div>
    
</body>
</html>
