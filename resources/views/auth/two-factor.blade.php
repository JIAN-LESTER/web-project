@extends('layouts.app')

@section('content')

<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-lg p-4 rounded-3" style="max-width: 400px; width: 100%; background: transparent; border: 2px solid white; box-shadow: 3px 3px 5px gray;">
        <div class="card-header text-center bg-white rounded-3">
            <h4 class="fw-bold text-dark">Two-Factor Authentication</h4>
        </div>

        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <form method="POST" action="{{ route('2fa.verify') }}">
                @csrf
                <div class="mb-3">
                    <label for="two_factor_code" class="form-label fw-bold text-dark">Enter 2FA Code:</label>
                    <div class="input-group">
                        <input type="text" name="two_factor_code" class="form-control form-control-lg text-center shadow-sm" required autofocus placeholder="6-digit code" 
                        style="background-color: #FFF; border: 2px solid #FFC107; outline: none; border-radius: 6px; padding: 10px; font-size: 18px;">
                    </div>
                </div>

            <!-- Black Verify Button -->
            <button type="submit" class="btn w-100 fw-bold mt-3"
                    style="background-color: black; color: white; border-radius: 8px; transition: 0.3s; box-shadow: 0 3px 5px rgba(0,0,0,0.2);"
                    onmouseover="this.style.backgroundColor='#222';"
                    onmouseout="this.style.backgroundColor='black';">
                    Verify
                </button>
            </form>

            <div class="text-center mt-3">
                <p class="mb-2 fw-bold text-dark">or</p>
                
         
                <a href="{{ route('login') }}" class="btn fw-bold w-100" 
                    style="background-color: #f39c12; color: white; border-radius: 8px; border: none; transition: 0.3s; box-shadow: 0 3px 5px rgba(0,0,0,0.2);"
                    onmouseover="this.style.backgroundColor='#e08e0b';"
                    onmouseout="this.style.backgroundColor='#f39c12';">
                    Re-login
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
