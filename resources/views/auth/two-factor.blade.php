<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Two-Factor Authentication - GuideBot</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('login_and_register/two-factor.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container">
        <div class="background-pattern"></div>

        <div class="auth-card">
            <div class="auth-icon-container">
                <svg class="shield-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/>
                    <path class="lock-path" d="M11 14.6v2.4h2v-2.4c.6-.35 1-1 1-1.7 0-1.1-.9-2-2-2s-2 .9-2 2c0 .7.4 1.35 1 1.7z"/>
                </svg>
            </div>

            <h1 class="auth-heading">Two-Factor Authentication</h1>
            <p class="auth-message">
                Please enter the 6-digit code from your authentication app to verify your identity.
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

            @if(session('message'))
                <div class="alert-box alert-success">
                    <p>{{ session('message') }}</p>
                    <button type="button" class="close-btn" onclick="this.parentElement.style.display='none'">
                        <span>&times;</span>
                    </button>
                </div>
            @endif

            <form method="POST" action="{{ route('2fa.verify') }}" class="auth-form">
                @csrf
                <div class="form-group">
                    <label for="two_factor_code">Enter 2FA Code:</label>
                    <div class="code-input-container">
                        <div class="code-input-wrapper">
                            <input type="text"
                                  name="two_factor_code"
                                  id="two_factor_code"
                                  class="code-input"
                                  required
                                  autofocus
                                  maxlength="6"
                                  pattern="[0-9]{6}"
                                  autocomplete="off"
                                  oninput="updateCodeInputUI(this)">
                            <div class="code-digits">
                                <div class="digit-box"></div>
                                <div class="digit-box"></div>
                                <div class="digit-box"></div>
                                <div class="digit-box"></div>
                                <div class="digit-box"></div>
                                <div class="digit-box"></div>
                            </div>
                        </div>
                    </div>
                    <div class="code-expire-timer">
                        <div class="timer-circle">
                            <svg class="timer-svg" viewBox="0 0 36 36">
                                <path class="timer-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"></path>
                                <path class="timer-fill" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"></path>
                            </svg>
                            <span class="timer-text">5:00</span>
                        </div>
                        <span class="timer-label">Code expires in</span>
                    </div>

                    <div class="resend-container">
                        <p class="resend-text">Didn't receive a code?</p>
                        <a href="{{ route('2fa.resend') }}" class="resend-link">
                            <span class="resend-icon">↻</span> Resend verification code
                        </a>
                    </div>
                </div>

                <div class="buttons-container">
                    <button type="submit" class="primary-button">
                        <span class="button-icon">✓</span> Verify
                    </button>
                </div>
            </form>

            <div class="secondary-options">
                <span class="divider-text">or</span>
                <a href="{{ route('login') }}" class="secondary-button">
                    <span class="button-icon">↩</span> Back to Login
                </a>
            </div>
        </div>
    </div>

    <script>
        // Countdown timer for code expiration
        document.addEventListener('DOMContentLoaded', function() {
            let totalSeconds = 5 * 60; // 3 minutes in seconds
            const timerText = document.querySelector('.timer-text');
            const timerFill = document.querySelector('.timer-fill');
            const resendLink = document.querySelector('.resend-link');
            const codeInput = document.getElementById('two_factor_code');
            const digitBoxes = document.querySelectorAll('.digit-box');

            // Initially disable resend link for 30 seconds
            resendLink.classList.add('disabled');
            let resendCounter = 30;

            const updateResendText = () => {
                if (resendCounter > 0) {
                    resendLink.textContent = `Wait ${resendCounter}s to resend`;
                } else {
                    resendLink.textContent = '↻ Resend verification code';
                    resendLink.classList.remove('disabled');
                }
            };

            // Set up pulsing effect on input focus
            codeInput.addEventListener('focus', function() {
                document.querySelector('.code-input-wrapper').classList.add('pulse');
            });

            codeInput.addEventListener('blur', function() {
                document.querySelector('.code-input-wrapper').classList.remove('pulse');
            });

            // Focus the input field when clicking anywhere in the digit boxes
            document.querySelector('.code-digits').addEventListener('click', function() {
                codeInput.focus();
            });

            // Start resend countdown
            updateResendText();
            const resendTimer = setInterval(function() {
                resendCounter--;
                updateResendText();

                if (resendCounter <= 0) {
                    clearInterval(resendTimer);
                }
            }, 1000);

            // Expiration timer
            const timer = setInterval(function() {
                totalSeconds--;

                // Format minutes and seconds
                const minutes = Math.floor(totalSeconds / 60);
                const seconds = totalSeconds % 60;
                timerText.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;

                // Update the circle fill
                const percent = (totalSeconds / (5 * 60)) * 100;
                const dashoffset = 100 - percent;
                timerFill.style.strokeDashoffset = dashoffset;

                // Change color when time is running out
                if (totalSeconds <= 30) {
                    timerFill.style.stroke = '#dc3545';
                    timerText.style.color = '#dc3545';

                    if (!document.querySelector('.timer-pulse')) {
                        timerText.classList.add('timer-pulse');
                    }
                }

                if (totalSeconds <= 0) {
                    clearInterval(timer);
                    timerText.textContent = '0:00';
                    document.querySelector('.auth-card').classList.add('expired');

                    // Show expiration message
                    const expiredMsg = document.createElement('div');
                    expiredMsg.className = 'alert-box alert-danger';
                    expiredMsg.innerHTML = '<p>Your verification code has expired. Please request a new one.</p>';

                    const formGroup = document.querySelector('.form-group');
                    formGroup.insertBefore(expiredMsg, formGroup.firstChild);

                    // Disable the verify button
                    document.querySelector('.primary-button').disabled = true;
                }
            }, 1000);

            // Focus the input field when page loads
            document.getElementById('two_factor_code').focus();
        });

        // Function to update the digit boxes when user types
        function updateCodeInputUI(input) {
            const digits = input.value.split('');
            const boxes = document.querySelectorAll('.digit-box');

            // Clear all boxes first
            boxes.forEach(box => {
                box.textContent = '';
                box.classList.remove('filled');
            });

            // Fill boxes with entered digits
            digits.forEach((digit, index) => {
                if (index < boxes.length) {
                    boxes[index].textContent = digit;
                    boxes[index].classList.add('filled');

                    // Add the "pop" animation
                    boxes[index].classList.remove('pop');
                    void boxes[index].offsetWidth; // Trigger reflow
                    boxes[index].classList.add('pop');
                }
            });

            // If all digits are entered, briefly show success animation
            if (digits.length === 6) {
                document.querySelector('.code-input-wrapper').classList.add('complete');
                setTimeout(() => {
                    document.querySelector('.code-input-wrapper').classList.remove('complete');
                }, 1000);
            }
        }

        // Add confetti animation when form is submitted
        document.querySelector('.auth-form').addEventListener('submit', function(e) {
            // Don't actually submit yet
            e.preventDefault();

            // Only proceed if code is 6 digits
            const code = document.getElementById('two_factor_code').value;
            if (code.length !== 6 || !/^\d+$/.test(code)) {
                document.querySelector('.code-input-wrapper').classList.add('error');
                setTimeout(() => {
                    document.querySelector('.code-input-wrapper').classList.remove('error');
                }, 500);
                return;
            }

            // Show loading state
            const submitBtn = document.querySelector('.primary-button');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<div class="spinner"></div> Verifying...';
            submitBtn.disabled = true;

            // Simulate verification (you'd remove this timeout in production)
            setTimeout(() => {
                // Submit the form after showing animation
                this.submit();
            }, 1500);
        });
    </script>
    </script>
</body>
</html>
