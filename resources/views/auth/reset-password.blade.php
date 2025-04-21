@extends('layouts.app')

@section('content')

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow-lg p-4 rounded-4" style="width: 100%; max-width: 400px; background: white;">
        <div class="text-center mb-3">
            <h3 class="fw-bold text-dark">Reset Password</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="mb-3">
                    <label for="email" class="form-label fw-bold">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#6c757d" viewBox="0 0 256 256">
                                <path d="M224,48H32a8,8,0,0,0-8,8V192a16,16,0,0,0,16,16H216a16,16,0,0,0,16-16V56A8,8,0,0,0,224,48Zm-96,85.15L52.57,64H203.43ZM98.71,128,40,181.81V74.19Zm11.84,10.85,12,11.05a8,8,0,0,0,10.82,0l12-11.05,58,53.15H52.57ZM157.29,128,216,74.18V181.82Z"></path>
                            </svg>
                        </span>
                        <input type="email" name="email" class="form-control" value="{{ session('email') }}" readonly>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label fw-bold">New Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#6c757d" viewBox="0 0 256 256">
                                <path d="M208,80H176V56a48,48,0,0,0-96,0V80H48A16,16,0,0,0,32,96V208a16,16,0,0,0,16,16H208a16,16,0,0,0,16-16V96A16,16,0,0,0,208,80ZM96,56a32,32,0,0,1,64,0V80H96ZM208,208H48V96H208V208Zm-68-56a12,12,0,1,1-12-12A12,12,0,0,1,140,152Z"></path>
                            </svg>
                        </span>
                        <input type="password" name="password" id="password" class="form-control" placeholder="New password" required>
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#6c757d" viewBox="0 0 256 256">
                                <path d="M128,64C45.3,64,0,128,0,128s45.3,64,128,64,128-64,128-64S210.7,64,128,64Zm0,112c-26.51,0-48-21.49-48-48s21.49-48,48-48,48,21.49,48,48S154.51,176,128,176Zm0-80a32,32,0,1,0,32,32A32,32,0,0,0,128,96Z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label fw-bold">Confirm New Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#6c757d" viewBox="0 0 256 256">
                                <path d="M208,80H176V56a48,48,0,0,0-96,0V80H48A16,16,0,0,0,32,96V208a16,16,0,0,0,16,16H208a16,16,0,0,0,16-16V96A16,16,0,0,0,208,80ZM96,56a32,32,0,0,1,64,0V80H96ZM208,208H48V96H208V208Zm-68-56a12,12,0,1,1-12-12A12,12,0,0,1,140,152Z"></path>
                            </svg>
                        </span>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Confirm new password" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-dark w-100">Reset Password</button>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById("togglePassword").addEventListener("click", function () {
        let passwordInput = document.getElementById("password");
        let eyeIcon = document.getElementById("eyeIcon");
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            eyeIcon.innerHTML = `<path d="M128,64C45.3,64,0,128,0,128s45.3,64,128,64,128-64,128-64S210.7,64,128,64Zm0,112c-26.51,0-48-21.49-48-48s21.49-48,48-48,48,21.49,48,48S154.51,176,128,176Zm73.95-64c-9.3,8.8-37.1,32-73.95,32S63.35,120.8,54.05,112C63.35,103.2,91.1,80,128,80S192.65,103.2,201.95,112Z"></path>`;
        } else {
            passwordInput.type = "password";
            eyeIcon.innerHTML = `<path d="M128,64C45.3,64,0,128,0,128s45.3,64,128,64,128-64,128-64S210.7,64,128,64Zm0,112c-26.51,0-48-21.49-48-48s21.49-48,48-48,48,21.49,48,48S154.51,176,128,176Zm0-80a32,32,0,1,0,32,32A32,32,0,0,0,128,96Z"></path>`;
        }
    });
</script>

@if(session('success'))
    <script>
        Swal.fire({ icon: 'success', title: 'Success!', text: '{{ session('success') }}' });
    </script>
@endif
@if(session('error'))
    <script>
        Swal.fire({ icon: 'error', title: 'Oops...', text: '{{ session('error') }}' });
    </script>
@endif

@endsection
