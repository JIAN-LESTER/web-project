@extends('layouts.app')

@section('content')

    <div class="container min-vh-100">

        <div class="row justify-content-center">
            <div class="col-md-6" style="margin-top:8%;">
                <div class="card" style="background: transparent;border: 2px solid white; box-shadow: 3px 3px 5px gray; border-radius: 10px;">
                    <div class="card-header text-black text-center" style="background-color: white;">
                        <h4>Forgot Password</h4>
                    </div>
                    <div class="card-body">

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(session('success'))
                            <div class="alert alert-success text-center">
                                {{ session('success') }}
                            </div>
                        @endif

                        <p class=" text-center" style="color:black;">Enter your email address below and we'll send you a password reset link.</p>

                        <form action="{{ route('password.email') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#000000"
                                            viewBox="0 0 256 256">
                                            <path
                                                d="M224,48H32a8,8,0,0,0-8,8V192a16,16,0,0,0,16,16H216a16,16,0,0,0,16-16V56A8,8,0,0,0,224,48Zm-96,85.15L52.57,64H203.43ZM98.71,128,40,181.81V74.19Zm11.84,10.85,12,11.05a8,8,0,0,0,10.82,0l12-11.05,58,53.15H52.57ZM157.29,128,216,74.18V181.82Z">
                                            </path>
                                        </svg>
                                    </span>
                                    <input type="email" name="email" id="email" class="form-control" placeholder="Email"
                                        required style="background-color: #F5F7F8;">
                                </div>
                            </div>

                            <!-- Apply black background for the button -->
                            <button type="submit" class="btn w-100" style="background-color: #000000; color: white; border:none;">Send Reset Link</button>
                        </form>
                        <hr>

                        <div class="text-center mt-3">
                            <!-- Apply yellow gradient for the 'Back to Login' button -->
                            <button style="background: linear-gradient(to right, #f39c12, #e67e22); width: 200px; height: 35px; border:none; border-radius: 8px;">
                                <a href="{{ route('login') }}" class="text-decoration-none" style="color: white;">Back to Login</a>
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
