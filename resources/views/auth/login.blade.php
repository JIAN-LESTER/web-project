<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GuideBot Login</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Link to external CSS file -->
    <link rel="stylesheet" href="{{ asset('login_and_register/login.css') }}">
    <!-- Add animate.css for animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <!-- Custom styles for SweetAlert popups -->
    <style>
        /* Custom styles for SweetAlert popups */
        .custom-popup-class {
            font-family: 'Inter', sans-serif;
            border-radius: 12px;
        }

        .custom-popup-class .swal2-title {
            font-weight: 700;
            color: #333;
        }

        .custom-popup-class .swal2-html-container {
            font-size: 1rem;
            color: #555;
        }

        /* Success popup styling */
        .verification-popup .swal2-icon {
            border-color: #7669F8;
            color: #7669F8;
        }

        .verification-popup .swal2-confirm {
            background-color: #7669F8 !important;
            border-radius: 8px;
            font-weight: 600;
            padding: 12px 24px;
            transition: all 0.3s ease;
        }

        .verification-popup .swal2-confirm:hover {
            background-color: #655DF5 !important;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(118, 105, 248, 0.4);
        }

        /* Logout popup styling */
        .logout-popup .swal2-actions {
            gap: 12px;
        }

        .logout-popup .swal2-confirm {
            background-color: #f44336 !important;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .logout-popup .swal2-confirm:hover {
            background-color: #e53935 !important;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(229, 57, 53, 0.4);
        }

        .logout-popup .swal2-cancel {
            background-color: #f5f5f5 !important;
            color: #333 !important;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .logout-popup .swal2-cancel:hover {
            background-color: #e0e0e0 !important;
            transform: translateY(-2px);
        }

        /* Animation for icons */
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        .animated-icon {
            animation: pulse 1.5s infinite;
        }

        /* Add bounce animation */
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {transform: translateY(0);}
            40% {transform: translateY(-20px);}
            60% {transform: translateY(-10px);}
        }

        .bounce {
            animation: bounce 2s infinite;
        }

        /* Custom styles for progress bar */
        .progress-bar-container {
            width: 100%;
            height: 5px;
            background-color: #f0f0f0;
            border-radius: 5px;
            margin-top: 15px;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            width: 0%;
            background-color: #7669F8;
            border-radius: 5px;
            transition: width 5s linear;
        }

        /* Option styling for logout */
        .option {
            cursor: pointer;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #ddd;
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 45%;
            transition: all 0.3s ease;
        }

        .option:hover {
            background-color: #f8f8f8;
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .option.selected {
            border-color: #7669F8;
            background-color: rgba(118, 105, 248, 0.05);
        }

        /* Confetti styles */
        .confetti {
            position: absolute;
            width: 10px;
            height: 10px;
            background-color: #7669F8;
            border-radius: 50%;
            opacity: 0.8;
            animation: fall 4s ease-out forwards;
            z-index: 9999;
        }

        @keyframes fall {
            to {
                transform: translateY(100vh) rotate(720deg);
            }
        }
    </style>
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
                <img src="{{ asset('assets/images/Saly.png') }}" alt="Rocket illustration" class="rocket-illustration">
            </div>
        </div>

        <!-- Bottom white section -->
        <div class="bottom-section"></div>

        <!-- Improved login card that overlaps both sections -->
        <div class="login-card">
            <!-- Header with welcome text and sign up link -->
            <div class="header-section">
                <div class="welcome-text">Welcome to <span class="brand-highlight">GuideBot</span></div>
                <div class="signup-section">
                    No Account?<br><a href="{{ route('register') }}" class="signup-link">Sign Up</a>
                </div>
            </div>

            <!-- Main heading -->
            <h1 class="card-heading">Sign In</h1>

            <!-- Login form -->
            <form class="form-container" method="POST" action="{{ route('login') }}">
                @csrf

                @if(session('error'))
                <div class="custom-error-box">
                    <span class="custom-error-close" onclick="this.parentElement.style.display='none'">&times;</span>
                    <div class="custom-error-title">Wrong credentials</div>
                    <div class="custom-error-subtitle">Invalid username or password</div>
                </div>
                @endif

                <div class="input-group">
                    <label for="email" class="input-label">Email address</label>
                    <div class="input-field-wrapper">
                        <i class="fas fa-envelope input-icon"></i>
                        <input type="email" name="email" id="email" class="input-field" placeholder="Enter your email address" required>
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
                    <div class="forgot-password-container">
                        <a href="{{ route('password.request') }}" class="forgot-password">Forgot Password?</a>
                    </div>
                </div>

                <div class="remember-me-container">
                    <label class="remember-me-label">
                        <input type="checkbox" name="remember" id="remember">
                        <span class="checkbox-custom"></span>
                        Remember me
                    </label>
                </div>

                <button type="submit" class="login-button">
                    Sign In
                </button>
            </form>
        </div>
    </div>

    <!-- SweetAlert2 Script -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- ENHANCED EMAIL VERIFICATION SUCCESS POPUP -->
    @if(session('email_verified'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            createConfetti();

            Swal.fire({
                icon: 'success',
                iconHtml: '<i class="fas fa-check-circle animated-icon" style="color:#7669F8;font-size:3rem;"></i>',
                title: 'Email Verified Successfully!',
                html: `
                    <div style="margin-bottom:15px">
                        <p>Your email has been verified and your account is now active.</p>
                        <p>You can now access all features of GuideBot.</p>
                        <div class="progress-bar-container">
                            <div class="progress-bar" id="progressBar"></div>
                        </div>
                    </div>
                `,
                showConfirmButton: true,
                confirmButtonText: '<i class="fas fa-thumbs-up"></i> Continue to Dashboard',
                customClass: {
                    popup: 'custom-popup-class verification-popup',
                    confirmButton: 'verification-confirm-button',
                },
                allowOutsideClick: false,
                backdrop: `rgba(118, 105, 248, 0.4)`,
                didOpen: () => {
                    document.querySelector('.swal2-container').style.zIndex = '10000000';
                    // Animate progress bar
                    document.getElementById('progressBar').style.width = '100%';
                }
            });
        });

        function createConfetti() {
            const confettiContainer = document.createElement('div');
            confettiContainer.id = 'confetti-container';
            confettiContainer.style.position = 'fixed';
            confettiContainer.style.top = '0';
            confettiContainer.style.left = '0';
            confettiContainer.style.width = '100%';
            confettiContainer.style.height = '100%';
            confettiContainer.style.pointerEvents = 'none';
            confettiContainer.style.zIndex = '9999999';
            document.body.appendChild(confettiContainer);

            const colors = ['#7669F8', '#FF9900', '#36B37E', '#FF5630'];

            for (let i = 0; i < 150; i++) {
                setTimeout(() => {
                    const confetti = document.createElement('div');
                    confetti.className = 'confetti';
                    confetti.style.left = Math.random() * 100 + 'vw';
                    confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                    confetti.style.width = Math.random() * 10 + 5 + 'px';
                    confetti.style.height = Math.random() * 10 + 5 + 'px';
                    confetti.style.opacity = Math.random() * 0.8 + 0.2;
                    confetti.style.animationDuration = Math.random() * 3 + 2 + 's';

                    confettiContainer.appendChild(confetti);

                    // Remove confetti after animation
                    setTimeout(() => {
                        if (confetti && confetti.parentNode) {
                            confetti.parentNode.removeChild(confetti);
                        }
                    }, 5000);
                }, i * 20);
            }

            // Remove container after all animations
            setTimeout(() => {
                if (confettiContainer && confettiContainer.parentNode) {
                    confettiContainer.parentNode.removeChild(confettiContainer);
                }
            }, 8000);
        }
    </script>
    @endif

    <!-- ENHANCED NOT VERIFIED EMAIL POPUP -->
    @if(session('not_verified'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'warning',
                iconHtml: '<i class="fas fa-envelope bounce" style="color:#FF9800;font-size:3rem;"></i>',
                title: 'Email Not Verified',
                html: `
                    <div style="margin-bottom:15px">
                        <p>{{ session('not_verified') }}</p>
                        <div style="margin-top:15px;padding:15px;background:#f7f7f7;border-radius:8px;text-align:left;font-size:0.9rem;">
                            <p><strong>Why verify your email?</strong></p>
                            <ul style="padding-left:20px;margin-top:5px;margin-bottom:0;">
                                <li style="margin-bottom:5px">Secure your account</li>
                                <li style="margin-bottom:5px">Access all GuideBot features</li>
                                <li style="margin-bottom:5px">Receive important notifications</li>
                                <li>Get personalized recommendations</li>
                            </ul>
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Resend Verification Email',
                cancelButtonText: 'I\'ll do it later',
                customClass: {
                    popup: 'custom-popup-class',
                    confirmButton: 'verification-confirm-button',
                    cancelButton: 'logout-cancel-button',
                },
                footer: '<a href="#" id="helpLink" style="color:#7669F8;font-weight:500;">Need help with verification?</a>',
                didOpen: () => {
                    document.querySelector('.swal2-container').style.zIndex = '10000000';

                    document.getElementById('helpLink').addEventListener('click', function(e) {
                        e.preventDefault();
                        Swal.fire({
                            title: 'Verification Help',
                            html: `
                                <div style="text-align:left">
                                    <div style="display:flex;align-items:flex-start;margin-bottom:12px">
                                        <div style="background:#f0f4ff;border-radius:50%;width:25px;height:25px;display:flex;align-items:center;justify-content:center;margin-right:10px">
                                            <span style="color:#7669F8;font-weight:bold">1</span>
                                        </div>
                                        <div>Check your inbox and spam folder</div>
                                    </div>
                                    <div style="display:flex;align-items:flex-start;margin-bottom:12px">
                                        <div style="background:#f0f4ff;border-radius:50%;width:25px;height:25px;display:flex;align-items:center;justify-content:center;margin-right:10px">
                                            <span style="color:#7669F8;font-weight:bold">2</span>
                                        </div>
                                        <div>Click the verification link in the email</div>
                                    </div>
                                    <div style="display:flex;align-items:flex-start;margin-bottom:12px">
                                        <div style="background:#f0f4ff;border-radius:50%;width:25px;height:25px;display:flex;align-items:center;justify-content:center;margin-right:10px">
                                            <span style="color:#7669F8;font-weight:bold">3</span>
                                        </div>
                                        <div>If you didn't receive an email, try resending</div>
                                    </div>

                                </div>
                            `,
                            icon: 'info',
                            confirmButtonText: 'Got it!',
                            customClass: {
                                popup: 'custom-popup-class',
                                confirmButton: 'verification-confirm-button',
                            },
                        });
                    });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading state when resending verification email
                    Swal.fire({
                        title: 'Sending Verification Email',
                        html: `
                            <div style="display:flex;flex-direction:column;align-items:center;gap:15px">
                                <i class="fas fa-paper-plane" style="font-size:2rem;color:#7669F8;animation:bounce 2s infinite"></i>
                                <p>Sending a new verification email to your inbox...</p>
                                <div class="progress-bar-container">
                                    <div class="progress-bar" id="emailProgressBar"></div>
                                </div>
                            </div>
                        `,
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        customClass: {
                            popup: 'custom-popup-class',
                        },
                        didOpen: () => {
                            document.getElementById('emailProgressBar').style.width = '100%';
                            // Here you would make an AJAX call to resend the verification email
                            // For demonstration, we'll just show a success message after a delay
                            setTimeout(() => {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Email Sent!',
                                    html: `
                                        <p>Please check your inbox and follow the verification link.</p>
                                        <p style="font-size:0.9rem;color:#666;margin-top:10px">
                                            <i class="fas fa-info-circle"></i>
                                            If you don't see the email within a few minutes, check your spam folder
                                        </p>
                                    `,
                                    customClass: {
                                        popup: 'custom-popup-class verification-popup',
                                        confirmButton: 'verification-confirm-button',
                                    },
                                });
                            }, 2000);
                        }
                    });
                }
            });
        });
    </script>
    @endif

    <!-- Display regular success message (keep this as is) -->
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



    <!-- ENHANCED LOGOUT POPUP FUNCTIONALITY -->
    <script>
        function confirmLogout() {
            let selectedOption = null;

            Swal.fire({
                icon: 'question',
                iconHtml: '<i class="fas fa-sign-out-alt" style="color:#f44336;font-size:3rem;"></i>',
                title: 'Confirm Logout',
                html: `
                    <div style="margin-bottom:15px">
                        <p>Are you sure you want to log out from GuideBot?</p>
                        <p style="font-size:0.9rem;color:#777">Your session will be terminated and you'll need to log in again.</p>
                    </div>
                    <div id="logoutOptions" style="display:flex;justify-content:center;gap:15px;margin-top:20px">
                        <div class="option" id="rememberOption">
                            <i class="fas fa-desktop" style="font-size:1.8rem;color:#7669F8;margin-bottom:10px"></i>
                            <span style="font-size:0.9rem;font-weight:500">Remember this device</span>
                            <span style="font-size:0.75rem;color:#777;margin-top:5px">Stay logged in here</span>
                        </div>
                        <div class="option" id="everywhereOption">
                            <i class="fas fa-globe" style="font-size:1.8rem;color:#f44336;margin-bottom:10px"></i>
                            <span style="font-size:0.9rem;font-weight:500">Logout everywhere</span>
                            <span style="font-size:0.75rem;color:#777;margin-top:5px">All devices & sessions</span>
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Logout',
                cancelButtonText: 'Cancel',
                customClass: {
                    popup: 'custom-popup-class logout-popup',
                    confirmButton: 'logout-confirm-button',
                    cancelButton: 'logout-cancel-button',
                },
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                },
                backdrop: `rgba(0,0,0,0.4)`,
                allowOutsideClick: true,
                didOpen: () => {
                    const rememberOption = document.getElementById('rememberOption');
                    const everywhereOption = document.getElementById('everywhereOption');

                    rememberOption.addEventListener('click', function() {
                        this.classList.add('selected');
                        everywhereOption.classList.remove('selected');
                        selectedOption = 'remember';
                    });

                    everywhereOption.addEventListener('click', function() {
                        this.classList.add('selected');
                        rememberOption.classList.remove('selected');
                        selectedOption = 'everywhere';
                    });

                    // Default selection
                    rememberOption.click();
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Process based on selection
                    if (selectedOption === 'remember') {
                        document.cookie = "remember_device=true; max-age=2592000; path=/";
                    } else if (selectedOption === 'everywhere') {
                        localStorage.setItem('logout_everywhere', 'true');
                    }

                    // Show loading state
                    Swal.fire({
                        title: 'Logging Out...',
                        html: `
                            <div style="display:flex;flex-direction:column;align-items:center;gap:15px">
                                <i class="fas fa-circle-notch fa-spin" style="font-size:2rem;color:#7669F8"></i>
                                <p>Please wait while we securely log you out...</p>
                                <div class="progress-bar-container">
                                    <div class="progress-bar" id="logoutProgressBar"></div>
                                </div>
                            </div>
                        `,
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        customClass: {
                            popup: 'custom-popup-class',
                        },
                        didOpen: () => {
                            document.getElementById('logoutProgressBar').style.width = '100%';
                            // Redirect after a short delay (simulating a logout process)
                            setTimeout(() => {
                                window.location.href = "{{ route('logout') }}";
                            }, 1500);
                        }
                    });
                }
            });
        }
    </script>

    <script>
        // Auto-remove error message after 15 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const errorBox = document.querySelector('.custom-error-box');
            if (errorBox) {
                setTimeout(() => {
                    errorBox.style.opacity = '0';
                    errorBox.style.transition = 'opacity 0.5s ease';
                    setTimeout(() => {
                        if (errorBox.parentNode) {
                            errorBox.parentNode.removeChild(errorBox);
                        }
                    }, 500);
                }, 15000);
            }

            // Toggle password visibility
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
        });
    </script>
</body>
</html>
