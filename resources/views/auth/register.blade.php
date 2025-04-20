@extends('layouts.app')

@section('content')

    <head>
        <title>Register</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>

    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow-lg p-4 rounded-4" style="width: 100%; max-width: 400px; background: white;">
            <div class="text-center mb-3">
                <h3 class="fw-bold text-dark">Register</h3>
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

                <form method="POST" action="{{ route('register') }}">
                    @csrf


                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">Name</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0">
                                <i class="bi bi-person"></i>
                            </span>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Enter your name"
                                required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0">
                                <i class="bi bi-envelope"></i>
                            </span>
                            <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email"
                                required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label fw-bold">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0">
                                <i class="bi bi-lock"></i>
                            </span>
                            <input type="password" name="password" id="password" class="form-control"
                                placeholder="Enter your password" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label fw-bold">Confirm Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0">
                                <i class="bi bi-lock"></i>
                            </span>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="form-control" placeholder="Confirm your password" required>
                        </div>
                    </div>

                  {{-- Year Level Dropdown --}}
<div class="mb-3">
    <label for="year" class="form-label fw-bold">Year Level (Optional)</label>
    <div class="input-group">
        <span class="input-group-text bg-light border-0">
            <i class="bi bi-calendar"></i>
        </span>
        <select name="year_id" id="year" class="form-select">
            <option value="">Select year level</option>
            @foreach ($years as $year)
                <option value="{{ $year->yearID }}">{{ $year->year_level }}</option>
            @endforeach
        </select>
    </div>
</div>

{{-- Course Dropdown --}}
<div class="mb-3">
    <label for="course" class="form-label fw-bold">Course (Optional)</label>
    <div class="input-group">
        <span class="input-group-text bg-light border-0">
            <i class="bi bi-book"></i>
        </span>
        <select name="course_id" id="course" class="form-select">
            <option value="">Select course</option>
            @foreach ($courses as $course)
                <option value="{{ $course->courseID }}">{{ $course->course_name }}</option>
            @endforeach
        </select>
    </div>
</div>




                    <button type="submit" class="btn w-100"
                        style="background-color: #000000; color: white;">Register</button>

                </form>

                <div class="text-center mt-3">
                    <p>Already have an account?</p>
                    <a href="{{ route('login') }}" class="btn w-100" style="background-color: #f39c12; color: white;">Back
                        to Login</a>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <script>
            Swal.fire({ icon: 'success', title: 'Success!', text: '{{ session('success') }}' });
        </script>
    @endif
@endsection