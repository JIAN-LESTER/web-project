<link rel="stylesheet" href="{{ asset('login_and_register/reset-password.css') }}">

<div class="container">
    <div class="background-pattern"></div>

    <div class="password-reset-card">
        <div class="password-icon-container">
            <svg class="lock-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path class="lock-body" d="M19 10h-1V7c0-3.9-3.1-7-7-7S4 3.1 4 7v3H3c-1.1 0-2 .9-2 2v8c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-8c0-1.1-.9-2-2-2zm-7 9c-1.7 0-3-1.3-3-3s1.3-3 3-3 3 1.3 3 3-1.3 3-3 3zM6.5 10V7c0-3 2.5-5.5 5.5-5.5S17.5 4 17.5 7v3h-11z"/>
                <circle class="lock-keyhole" cx="12" cy="16" r="3"/>
            </svg>
        </div>

        <h1 class="reset-heading">Reset Your Password</h1>
        <p class="reset-message">
            Enter a new password for your account
        </p>

        @if($errors->any())
            <div class="alert-box alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('password.update') }}" method="POST" class="reset-form">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group">
                <label for="email">Email Address</label>
                <div class="input-wrapper">
                    <input type="email" name="email" id="email" value="{{ session('email') }}" readonly>
                    <div class="input-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M3 3h18a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1zm17 4.238l-7.928 7.1L4 7.216V19h16V7.238zM4.511 5l7.55 6.662L19.502 5H4.511z" fill="#5A3092"/></svg>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="password">New Password</label>
                <div class="input-wrapper password-wrapper">
                    <input type="password" name="password" id="password" placeholder="Enter your new password" required>
                    <div class="input-icon password-toggle" id="togglePassword">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" id="eyeIcon"><path d="M12 3c5.392 0 9.878 3.88 10.819 9-.94 5.12-5.427 9-10.819 9-5.392 0-9.878-3.88-10.819-9C2.121 6.88 6.608 3 12 3zm0 16c3.691 0 6.915-2.534 7.875-6C18.915 9.534 15.691 7 12 7s-6.915 2.534-7.875 6c.96 3.466 4.184 6 7.875 6zm0-10c2.21 0 4 1.79 4 4s-1.79 4-4 4-4-1.79-4-4 1.79-4 4-4zm0 2c-1.105 0-2 .895-2 2s.895 2 2 2 2-.895 2-2-.895-2-2-2z" fill="#5A3092"/></svg>
                    </div>
                </div>
                <div class="password-strength">
                    <div class="strength-meter">
                        <div class="strength-segment" id="segment1"></div>
                        <div class="strength-segment" id="segment2"></div>
                        <div class="strength-segment" id="segment3"></div>
                        <div class="strength-segment" id="segment4"></div>
                    </div>
                    <div class="strength-text" id="strengthText">Password strength</div>
                </div>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm New Password</label>
                <div class="input-wrapper password-wrapper">
                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm your new password" required>
                    <div class="input-icon password-toggle" id="toggleConfirmPassword">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" id="confirmEyeIcon"><path d="M12 3c5.392 0 9.878 3.88 10.819 9-.94 5.12-5.427 9-10.819 9-5.392 0-9.878-3.88-10.819-9C2.121 6.88 6.608 3 12 3zm0 16c3.691 0 6.915-2.534 7.875-6C18.915 9.534 15.691 7 12 7s-6.915 2.534-7.875 6c.96 3.466 4.184 6 7.875 6zm0-10c2.21 0 4 1.79 4 4s-1.79 4-4 4-4-1.79-4-4 1.79-4 4-4zm0 2c-1.105 0-2 .895-2 2s.895 2 2 2 2-.895 2-2-.895-2-2-2z" fill="#5A3092"/></svg>
                    </div>
                </div>
                <div class="match-message" id="passwordMatch"></div>
            </div>

            <button type="submit" class="submit-button" id="submitButton" disabled>
                <span class="button-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M6 18.7V21a1 1 0 0 1-2 0v-5a1 1 0 0 1 1-1h5a1 1 0 1 1 0 2H7.1A7 7 0 0 0 19 12a1 1 0 1 1 2 0 9 9 0 0 1-15 6.7z" fill="#ffffff"/></svg>
                </span>
                Reset Password
            </button>
        </form>

        <div class="back-link">
            <a href="{{ route('login') }}">
                <span class="back-icon">←</span> Back to Login
            </a>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('password_confirmation');
        const togglePassword = document.getElementById('togglePassword');
        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
        const eyeIcon = document.getElementById('eyeIcon');
        const confirmEyeIcon = document.getElementById('confirmEyeIcon');
        const submitButton = document.getElementById('submitButton');
        const passwordMatch = document.getElementById('passwordMatch');

        // Password visibility toggle
        togglePassword.addEventListener('click', function() {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = '<path d="M12 3c5.392 0 9.878 3.88 10.819 9-.94 5.12-5.427 9-10.819 9-5.392 0-9.878-3.88-10.819-9C2.121 6.88 6.608 3 12 3zm0 16c3.691 0 6.915-2.534 7.875-6C18.915 9.534 15.691 7 12 7s-6.915 2.534-7.875 6c.96 3.466 4.184 6 7.875 6z" fill="#5A3092"/><path d="M19 12c0 3.866-3.134 7-7 7s-7-3.134-7-7 3.134-7 7-7 7 3.134 7 7zm-7 5c2.761 0 5-2.239 5-5s-2.239-5-5-5-5 2.239-5 5 2.239 5 5 5z" fill="#5A3092"/><path d="M12 15a3 3 0 1 1 0-6 3 3 0 0 1 0 6z" fill="#5A3092"/>';
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = '<path d="M12 3c5.392 0 9.878 3.88 10.819 9-.94 5.12-5.427 9-10.819 9-5.392 0-9.878-3.88-10.819-9C2.121 6.88 6.608 3 12 3zm0 16c3.691 0 6.915-2.534 7.875-6C18.915 9.534 15.691 7 12 7s-6.915 2.534-7.875 6c.96 3.466 4.184 6 7.875 6zm0-10c2.21 0 4 1.79 4 4s-1.79 4-4 4-4-1.79-4-4 1.79-4 4-4zm0 2c-1.105 0-2 .895-2 2s.895 2 2 2 2-.895 2-2-.895-2-2-2z" fill="#5A3092"/>';
            }
        });

        toggleConfirmPassword.addEventListener('click', function() {
            if (confirmInput.type === 'password') {
                confirmInput.type = 'text';
                confirmEyeIcon.innerHTML = '<path d="M12 3c5.392 0 9.878 3.88 10.819 9-.94 5.12-5.427 9-10.819 9-5.392 0-9.878-3.88-10.819-9C2.121 6.88 6.608 3 12 3zm0 16c3.691 0 6.915-2.534 7.875-6C18.915 9.534 15.691 7 12 7s-6.915 2.534-7.875 6c.96 3.466 4.184 6 7.875 6z" fill="#5A3092"/><path d="M19 12c0 3.866-3.134 7-7 7s-7-3.134-7-7 3.134-7 7-7 7 3.134 7 7zm-7 5c2.761 0 5-2.239 5-5s-2.239-5-5-5-5 2.239-5 5 2.239 5 5 5z" fill="#5A3092"/><path d="M12 15a3 3 0 1 1 0-6 3 3 0 0 1 0 6z" fill="#5A3092"/>';
            } else {
                confirmInput.type = 'password';
                confirmEyeIcon.innerHTML = '<path d="M12 3c5.392 0 9.878 3.88 10.819 9-.94 5.12-5.427 9-10.819 9-5.392 0-9.878-3.88-10.819-9C2.121 6.88 6.608 3 12 3zm0 16c3.691 0 6.915-2.534 7.875-6C18.915 9.534 15.691 7 12 7s-6.915 2.534-7.875 6c.96 3.466 4.184 6 7.875 6zm0-10c2.21 0 4 1.79 4 4s-1.79 4-4 4-4-1.79-4-4 1.79-4 4-4zm0 2c-1.105 0-2 .895-2 2s.895 2 2 2 2-.895 2-2-.895-2-2-2z" fill="#5A3092"/>';
            }
        });

        // Password strength checker
        function checkPasswordStrength(password) {
            const segment1 = document.getElementById('segment1');
            const segment2 = document.getElementById('segment2');
            const segment3 = document.getElementById('segment3');
            const segment4 = document.getElementById('segment4');
            const strengthText = document.getElementById('strengthText');

            // Reset all segments
            segment1.className = 'strength-segment';
            segment2.className = 'strength-segment';
            segment3.className = 'strength-segment';
            segment4.className = 'strength-segment';

            if (!password) {
                strengthText.textContent = 'Password strength';
                strengthText.className = 'strength-text';
                return 0;
            }

            let strength = 0;

            // Has length greater than 7
            if (password.length > 7) strength++;

            // Has lowercase letters
            if (password.match(/[a-z]+/)) strength++;

            // Has uppercase letters
            if (password.match(/[A-Z]+/)) strength++;

            // Has numbers
            if (password.match(/[0-9]+/)) strength++;

            // Has special characters
            if (password.match(/[^a-zA-Z0-9]+/)) strength++;

            // Final strength value from 0 to 4
            const finalStrength = Math.min(4, Math.floor(strength * 0.8));

            // Update the UI based on strength
            switch(finalStrength) {
                case 0:
                    segment1.className = 'strength-segment weak';
                    strengthText.textContent = 'Very weak';
                    strengthText.className = 'strength-text weak-text';
                    break;
                case 1:
                    segment1.className = 'strength-segment weak';
                    strengthText.textContent = 'Weak';
                    strengthText.className = 'strength-text weak-text';
                    break;
                case 2:
                    segment1.className = 'strength-segment medium';
                    segment2.className = 'strength-segment medium';
                    strengthText.textContent = 'Medium';
                    strengthText.className = 'strength-text medium-text';
                    break;
                case 3:
                    segment1.className = 'strength-segment strong';
                    segment2.className = 'strength-segment strong';
                    segment3.className = 'strength-segment strong';
                    strengthText.textContent = 'Strong';
                    strengthText.className = 'strength-text strong-text';
                    break;
                case 4:
                    segment1.className = 'strength-segment very-strong';
                    segment2.className = 'strength-segment very-strong';
                    segment3.className = 'strength-segment very-strong';
                    segment4.className = 'strength-segment very-strong';
                    strengthText.textContent = 'Very strong';
                    strengthText.className = 'strength-text very-strong-text';
                    break;
            }

            return finalStrength;
        }

        // Check if passwords match
        function checkPasswordsMatch() {
            const password = passwordInput.value;
            const confirm = confirmInput.value;

            if (!confirm) {
                passwordMatch.textContent = '';
                passwordMatch.className = 'match-message';
                return false;
            }

            if (password === confirm) {
                passwordMatch.textContent = 'Passwords match';
                passwordMatch.className = 'match-message match';
                return true;
            } else {
                passwordMatch.textContent = 'Passwords don\'t match';
                passwordMatch.className = 'match-message no-match';
                return false;
            }
        }

        // Check if form can be submitted
        function updateSubmitButton() {
            const password = passwordInput.value;
            const strength = checkPasswordStrength(password);
            const passwordsMatch = checkPasswordsMatch();

            if (strength >= 2 && passwordsMatch) {
                submitButton.disabled = false;
                submitButton.classList.add('active');
            } else {
                submitButton.disabled = true;
                submitButton.classList.remove('active');
            }
        }

        // Event listeners for input fields
        passwordInput.addEventListener('input', function() {
            checkPasswordStrength(this.value);
            updateSubmitButton();
        });

        confirmInput.addEventListener('input', function() {
            checkPasswordsMatch();
            updateSubmitButton();
        });

        // Animation for the lock icon
        const lockIcon = document.querySelector('.lock-icon');
        setTimeout(() => {
            lockIcon.classList.add('unlocked');
        }, 1000);

        // Focus effects for inputs
        const inputs = document.querySelectorAll('input');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focus');
            });

            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('focus');
            });
        });

        // Add animation to submit button on state change
        submitButton.addEventListener('click', function(e) {
            if (!this.disabled) {
                this.classList.add('submitting');
                // Form will submit normally
            }
        });
    });
</script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '{{ session('success') }}'
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: '{{ session('error') }}'
    });
</script>
@endif
