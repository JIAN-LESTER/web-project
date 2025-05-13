<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Successful - GuideBot</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Link to your CSS file -->
    <link rel="stylesheet" href="{{ asset('login_and_register/registration-success.css') }}">
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

        <div class="success-card">
            <div class="logo">
                <i class="fas fa-robot"></i>
                <span>GuideBot</span>
            </div>

            <div class="success-icon-container">
                <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                    <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
                    <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                </svg>
            </div>

            <h1 class="success-heading">Registration Successful!</h1>
            <p class="success-message">
                Welcome to <span class="highlight">GuideBot</span>! Your account has been created successfully.
            </p>

            <div class="success-steps">
                <div class="step">
                    <div class="step-number">1</div>
                    <div class="step-text">Check your email inbox for a verification link we just sent you.</div>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                    <div class="step-text">Click the verification link to activate your account.</div>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <div class="step-text">Once verified, you can sign in and start using GuideBot's features.</div>
                </div>
            </div>

            <div class="buttons-container">
                <a href="{{ route('login') }}" class="primary-button">Go to Login</a>
                {{-- <a href="{{ url('/') }}" class="secondary-button">Back to Home</a> --}}
            </div>
        </div>
    </div>

    <script>
        // Confetti animation
        function createConfetti() {
            const colors = ['#FFFFFF', '#4ADE80', '#12823e', '#0D6832', '#134E4A'];
            const confettiCount = 200;

            for (let i = 0; i < confettiCount; i++) {
                const confetti = document.createElement('div');
                confetti.className = 'confetti';

                // Random position, color, size, and animation delay
                const left = Math.random() * 100;
                const width = Math.random() * 10 + 5;
                const height = Math.random() * 10 + 5;
                const color = colors[Math.floor(Math.random() * colors.length)];
                const delay = Math.random() * 5;

                confetti.style.left = left + 'vw';
                confetti.style.width = width + 'px';
                confetti.style.height = height + 'px';
                confetti.style.backgroundColor = color;
                confetti.style.top = '-20px';
                confetti.style.opacity = '0';

                document.querySelector('.container').appendChild(confetti);

                // Animate the confetti
                setTimeout(() => {
                    confetti.style.transition = 'all ' + (Math.random() * 3 + 2) + 's ease-out';
                    confetti.style.transform = 'translateY(' + (Math.random() * 100 + 400) + 'px) rotate(' + (Math.random() * 360) + 'deg)';
                    confetti.style.opacity = '1';

                    // Remove confetti after animation
                    setTimeout(() => {
                        confetti.style.opacity = '0';
                        setTimeout(() => {
                            confetti.remove();
                        }, 1000);
                    }, 3000);
                }, delay * 1000);
            }
        }

        // Run confetti when page loads
        window.addEventListener('load', createConfetti);
    </script>
</body>
</html>
