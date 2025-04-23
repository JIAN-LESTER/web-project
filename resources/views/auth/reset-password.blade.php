@extends('layouts.app')

@section('content')

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow-lg p-4 rounded-4" style="width: 100%; max-width: 600px; background: white;">
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
                        
                            <input type="email" name="email" class="form-control flex-grow-1" value="{{ session('email') }}"
                                readonly style="height: 50px; width: 100%;">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label fw-bold">New Password</label>
                        <div class="input-group">

                            <input type="password" name="password" id="password" class="form-control"
                                placeholder="New password" required style="height: 50px; width: 100%;">

                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label fw-bold">Confirm New Password</label>
                        <div class="input-group">

                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="form-control" placeholder="Confirm new password" required
                                style="height: 50px; width: 100%;">
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