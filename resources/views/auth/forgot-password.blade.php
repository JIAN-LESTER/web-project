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
    <div class="full-container">
        <div class="white-section">
            <div class="content-box">
                <div class="illustration-container">
                    <img src="{{ asset('assets/images/fp.jpg') }}" alt="Forgot Password Illustration">
                </div>

                <div class="info-section">
                    <h3>Important Information</h3>
                    <p class="info-text">Please <span class="highlight">read</span> the information below and then kindly <span class="highlight">share</span> the requested information.</p>

                    <ul>
                        <li>Do &#8203<span class="highlight">not</span>&#8203 reveal your password to anybody</li>
                        <li>Do &#8203<span class="highlight">not</span>&#8203 reuse passwords</li>
                        <li>Use Alphanumeric passwords</li>

                    </ul>
                </div>
            </div>
        </div>

        <div class="purple-section">
            <div class="content-box">
                <h1 class="title">Forgot<br>password?</h1>
                <p class="subtitle">Don't worry. We can help.</p>

                @if($errors->any())
                    <div class="error-message">
                        <ul style="margin: 0; padding-left: 20px;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('success'))
                    <div class="success-message">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('password.email') }}" method="POST">
                    @csrf
                    <div class="email-input-container">
                        <input type="email" name="email" class="email-input" placeholder="Please fill in your email address" required>
                        <div class="email-icon">
                            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M20 4H4C2.9 4 2.01 4.9 2.01 6L2 18C2 19.1 2.9 20 4 20H20C21.1 20 22 19.1 22 18V6C22 4.9 21.1 4 20 4ZM20 8L12 13L4 8V6L12 11L20 6V8Z" fill="#AAAAAA"/>
                            </svg>
                        </div>
                    </div>

                    <div class="actions-container">
                        <div class="login-link">
                            <p>Remembered your password?</p>
                            <a href="{{ route('login') }}">Back to login</a>
                        </div>

                        <button type="submit" class="btn-continue">Continue</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
