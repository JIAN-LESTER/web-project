<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AcadBot Register</title>
    <!-- Link to external CSS file -->
    <link rel="stylesheet" href="{{ asset('login_and_register/register.css') }}">
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
                    Have an Account? <br><a href="{{ route('login') }}" class="signup-link">Sign In</a>
                </div>
            </div>

            <!-- Main heading -->
            <h1 class="card-heading">Sign Up</h1>

            <!-- Login form -->
            <form class="form-container">
                <!-- Email input group -->
                <div class="input-group">
                    <label class="input-label">Enter your email address</label>
                    <input type="text" class="input-field" placeholder="Email address" required>
                </div>

                <!-- Password input group -->
                <div class="input-group">
                    <label class="input-label">Enter your Password</label>
                    <input type="password" class="input-field" placeholder="Password" required>
                    <div class="forgot-password-container">
                    </div>

                 <!-- Confirm Password input group -->
                 <div class="input-group">
                    <label class="input-label">Confirm your Password</label>
                    <input type="password" class="input-field" placeholder="Confirm Password" required>
                    <div class="forgot-password-container">
                    </div>


                </div>

                <!-- Submit button -->
                <button class="login-button">Sign Up</button>
            </form>
        </div>
    </div>
</body>
</html>
