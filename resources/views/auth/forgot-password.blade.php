<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('login_and_register/forgot-password.css') }}">
</head>
<body>
    <div class="page-container">
        <div class="left-panel">
            <div class="brand-logo">
                <div class="logo-container">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M12 1.5C6.20101 1.5 1.5 6.20101 1.5 12C1.5 17.799 6.20101 22.5 12 22.5C17.799 22.5 22.5 17.799 22.5 12C22.5 6.20101 17.799 1.5 12 1.5ZM12 7.5C9.51472 7.5 7.5 9.51472 7.5 12C7.5 14.4853 9.51472 16.5 12 16.5C14.4853 16.5 16.5 14.4853 16.5 12C16.5 9.51472 14.4853 7.5 12 7.5Z" fill="#5A3092"/>
                    </svg>
                </div>
                <span>GuideBot</span>
            </div>

            <div class="reset-card">
                <div class="card-header">
                    <div class="header-icon">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 15.5C11.0717 15.5 10.1815 15.1313 9.52513 14.4749C8.86875 13.8185 8.5 12.9283 8.5 12C8.5 11.0717 8.86875 10.1815 9.52513 9.52513C10.1815 8.86875 11.0717 8.5 12 8.5C12.9283 8.5 13.8185 8.86875 14.4749 9.52513C15.1313 10.1815 15.5 11.0717 15.5 12C15.5 12.9283 15.1313 13.8185 14.4749 14.4749C13.8185 15.1313 12.9283 15.5 12 15.5Z" fill="#5A3092"/>
                            <path d="M4 8C4 7.20435 4.31607 6.44129 4.87868 5.87868C5.44129 5.31607 6.20435 5 7 5H17C17.7956 5 18.5587 5.31607 19.1213 5.87868C19.6839 6.44129 20 7.20435 20 8V16C20 16.7956 19.6839 17.5587 19.1213 18.1213C18.5587 18.6839 17.7956 19 17 19H7C6.20435 19 5.44129 18.6839 4.87868 18.1213C4.31607 17.5587 4 16.7956 4 16V8ZM7 7C6.73478 7 6.48043 7.10536 6.29289 7.29289C6.10536 7.48043 6 7.73478 6 8V16C6 16.2652 6.10536 16.5196 6.29289 16.7071C6.48043 16.8946 6.73478 17 7 17H17C17.2652 17 17.5196 16.8946 17.7071 16.7071C17.8946 16.5196 18 16.2652 18 16V8C18 7.73478 17.8946 7.48043 17.7071 7.29289C17.5196 7.10536 17.2652 7 17 7H7Z" fill="#5A3092"/>
                        </svg>
                    </div>
                    <h1>Forgot Password?</h1>
                    <p>Enter your email address below and we'll send you a link to reset your password.</p>
                </div>

                <div id="form-messages">
                    @if($errors->any())
                        <div class="error-message">
                            <div class="message-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22ZM12 20C16.4183 20 20 16.4183 20 12C20 7.58172 16.4183 4 12 4C7.58172 4 4 7.58172 4 12C4 16.4183 7.58172 20 12 20ZM11 15H13V17H11V15ZM11 7H13V13H11V7Z" fill="#e74c3c"/>
                                </svg>
                            </div>
                            <div class="message-content">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <button class="message-close" aria-label="Close message">×</button>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="success-message">
                            <div class="message-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22ZM12 20C16.4183 20 20 16.4183 20 12C20 7.58172 16.4183 4 12 4C7.58172 4 4 7.58172 4 12C4 16.4183 7.58172 20 12 20ZM11.0026 16L6.75999 11.7574L8.17421 10.3431L11.0026 13.1716L16.6595 7.51472L18.0737 8.92893L11.0026 16Z" fill="#2ecc71"/>
                                </svg>
                            </div>
                            <div class="message-content">
                                <p>{{ session('success') }}</p>
                            </div>
                            <button class="message-close" aria-label="Close message">×</button>
                        </div>
                    @endif
                </div>

                <form id="forgot-password-form" action="{{ route('password.email') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <div class="input-wrapper">
                            <div class="input-icon-left">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3 5.5C3 4.67157 3.67157 4 4.5 4H19.5C20.3284 4 21 4.67157 21 5.5V18.5C21 19.3284 20.3284 20 19.5 20H4.5C3.67157 20 3 19.3284 3 18.5V5.5ZM4.5 5.5V18.5H19.5V5.5H4.5Z" fill="#5A3092"/>
                                    <path d="M3.73682 5.2818C4.13173 4.91436 4.73145 4.94002 5.0911 5.33903L12 12.8709L18.9089 5.33903C19.2686 4.94002 19.8683 4.91436 20.2632 5.2818C20.6581 5.64924 20.6828 6.2593 20.3232 6.6583L12.7071 15C12.3166 15.4261 11.6834 15.4261 11.2929 15L3.67682 6.6583C3.31717 6.2593 3.3419 5.64924 3.73682 5.2818Z" fill="#5A3092"/>
                                </svg>
                            </div>
                            <input type="email" name="email" id="email" placeholder="Enter your email" required>
                            <div class="validation-icons">
                                <svg class="valid-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22ZM12 20C16.4183 20 20 16.4183 20 12C20 7.58172 16.4183 4 12 4C7.58172 4 4 7.58172 4 12C4 16.4183 7.58172 20 12 20ZM11.0026 16L6.75999 11.7574L8.17421 10.3431L11.0026 13.1716L16.6595 7.51472L18.0737 8.92893L11.0026 16Z" fill="#2ecc71"/>
                                </svg>
                                <svg class="invalid-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22ZM12 20C16.4183 20 20 16.4183 20 12C20 7.58172 16.4183 4 12 4C7.58172 4 4 7.58172 4 12C4 16.4183 7.58172 20 12 20ZM11 15H13V17H11V15ZM11 7H13V13H11V7Z" fill="#e74c3c"/>
                                </svg>
                            </div>
                            <div class="focus-bar"></div>
                        </div>
                    </div>

                    <button type="submit" id="submit-btn" class="submit-button">
                        <span class="btn-text">Send Reset Link</span>
                        <span class="btn-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M13.1714 12.0007L8.22168 7.05093L9.63589 5.63672L15.9999 12.0007L9.63589 18.3646L8.22168 16.9504L13.1714 12.0007Z" fill="currentColor"/>
                            </svg>
                        </span>
                        <div class="loading-spinner"></div>
                    </button>

                    <div class="form-footer">
                        <p>Remember your password? <a href="{{ route('login') }}">Sign In</a></p>
                    </div>
                </form>
            </div>

            <div class="security-note">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12 1.5C9.21 1.5 6.75 2.61 4.92 4.44C3.09 6.27 1.98 8.73 1.98 11.52V11.52C1.98 14.31 3.09 16.77 4.92 18.6C6.75 20.43 9.21 21.54 12 21.54C14.79 21.54 17.25 20.43 19.08 18.6C20.91 16.77 22.02 14.31 22.02 11.52V11.52C22.02 8.73 20.91 6.27 19.08 4.44C17.25 2.61 14.79 1.5 12 1.5V1.5ZM12 6.75C11.0335 6.75 10.25 7.5335 10.25 8.5C10.25 9.4665 11.0335 10.25 12 10.25C12.9665 10.25 13.75 9.4665 13.75 8.5C13.75 7.5335 12.9665 6.75 12 6.75ZM10 12C10 11.4477 10.4477 11 11 11H12H13C13.5523 11 14 11.4477 14 12C14 12.5523 13.5523 13 13 13V16C13 16.5523 12.5523 17 12 17C11.4477 17 11 16.5523 11 16V13C10.4477 13 10 12.5523 10 12Z" fill="#5A3092"/>
                </svg>
                <p>We'll never share your email with anyone else. Learn more about our <a href="#">privacy policy</a>.</p>
            </div>
        </div>

        <div class="right-panel">
            <div class="info-card">
                <h2>Password Security Tips</h2>
                <ul class="security-tips">
                    <li>
                        <div class="tip-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22ZM12 20C16.4183 20 20 16.4183 20 12C20 7.58172 16.4183 4 12 4C7.58172 4 4 7.58172 4 12C4 16.4183 7.58172 20 12 20ZM11.0026 16L6.75999 11.7574L8.17421 10.3431L11.0026 13.1716L16.6595 7.51472L18.0737 8.92893L11.0026 16Z" fill="#FFFFFF"/>
                            </svg>
                        </div>
                        <div class="tip-content">
                            <h3>Use Strong Passwords</h3>
                            <p>Create passwords that are at least 12 characters long with a mix of letters, numbers, and special characters.</p>
                        </div>
                    </li>
                    <li>
                        <div class="tip-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22ZM12 20C16.4183 20 20 16.4183 20 12C20 7.58172 16.4183 4 12 4C7.58172 4 4 7.58172 4 12C4 16.4183 7.58172 20 12 20ZM11.0026 16L6.75999 11.7574L8.17421 10.3431L11.0026 13.1716L16.6595 7.51472L18.0737 8.92893L11.0026 16Z" fill="#FFFFFF"/>
                            </svg>
                        </div>
                        <div class="tip-content">
                            <h3>Don't Reuse Passwords</h3>
                            <p>Use different passwords for different accounts to prevent multiple accounts from being compromised.</p>
                        </div>
                    </li>
                    <li>
                        <div class="tip-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22ZM12 20C16.4183 20 20 16.4183 20 12C20 7.58172 16.4183 4 12 4C7.58172 4 4 7.58172 4 12C4 16.4183 7.58172 20 12 20ZM11.0026 16L6.75999 11.7574L8.17421 10.3431L11.0026 13.1716L16.6595 7.51472L18.0737 8.92893L11.0026 16Z" fill="#FFFFFF"/>
                            </svg>
                        </div>
                        <div class="tip-content">
                            <h3>Update Passwords Regularly</h3>
                            <p>Change important passwords every 3-6 months, especially for accounts containing sensitive personal information.</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const emailInput = document.getElementById('email');
            const submitBtn = document.getElementById('submit-btn');
            const form = document.getElementById('forgot-password-form');

            // Close buttons for messages
            const closeButtons = document.querySelectorAll('.message-close');
            closeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    this.closest('.error-message, .success-message').classList.add('fade-out');
                    setTimeout(() => {
                        this.closest('.error-message, .success-message').style.display = 'none';
                    }, 500);
                });
            });

            // Focus effect for input
            emailInput.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });

            emailInput.addEventListener('blur', function() {
                this.parentElement.classList.remove('focused');
                validateEmail();
            });

            emailInput.addEventListener('input', function() {
                validateEmail();
            });

            function validateEmail() {
                const email = emailInput.value.trim();
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                const inputWrapper = emailInput.parentElement;

                if (email === '') {
                    inputWrapper.classList.remove('valid', 'invalid');
                } else if (emailRegex.test(email)) {
                    inputWrapper.classList.add('valid');
                    inputWrapper.classList.remove('invalid');
                } else {
                    inputWrapper.classList.add('invalid');
                    inputWrapper.classList.remove('valid');
                }
            }

            // Form submission with animation
            form.addEventListener('submit', function(e) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(emailInput.value.trim())) {
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

            // Automatic dismiss of alert messages
            const alertMessages = document.querySelectorAll('.error-message, .success-message');
            alertMessages.forEach(message => {
                setTimeout(() => {
                    message.classList.add('fade-out');
                    setTimeout(() => {
                        message.style.display = 'none';
                    }, 500);
                }, 5000);
            });

            // Card hover effect
            const resetCard = document.querySelector('.reset-card');
            resetCard.addEventListener('mouseenter', function() {
                this.classList.add('card-hover');
            });
            resetCard.addEventListener('mouseleave', function() {
                this.classList.remove('card-hover');
            });

            // Security tips animation
            const securityTips = document.querySelectorAll('.security-tips li');
            securityTips.forEach((tip, index) => {
                setTimeout(() => {
                    tip.classList.add('active');
                }, 300 * (index + 1));
            });

            // Tip hover animation
            securityTips.forEach(tip => {
                tip.addEventListener('mouseenter', function() {
                    this.classList.add('tip-hover');
                });
                tip.addEventListener('mouseleave', function() {
                    this.classList.remove('tip-hover');
                });
            });
        });
    </script>
</body>
</html>
