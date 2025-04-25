@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h3 class="mb-0">Add User</h3>
                </div>
                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('admin.store') }}" method="POST" onsubmit="return validatePassword()">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" name="first_name" id="first_name" class="form-control" placeholder="Enter first name" required>
                        </div>

                        <div class="mb-3">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Enter last name" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="Enter email address" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Enter password" required>
                            <small id="password-error" class="text-danger"></small>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Confirm password" required>
                        </div>

                        <div class="form-row">
                            <div class="input-group half-width mb-3">
                                <label for="year" class="input-label">Year Level</label>
                                <div class="input-wrapper">
                                    <select name="year_id" id="year" class="input-field">
                                        <option value="">Select Year (Optional)</option>
                                        @foreach ($years as $year)
                                            <option value="{{ $year->yearID }}">{{ $year->year_level }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('year_id')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="input-group half-width mb-3">
                                <label for="course" class="input-label">Course</label>
                                <div class="input-wrapper">
                                    <select name="course_id" id="course" class="input-field">
                                        <option value="">Select Course (Optional)</option>
                                        @foreach ($courses as $course)
                                            <option value="{{ $course->courseID }}">{{ $course->course_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('course_id')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Add User</button>
                            <a href="{{ route('admin.user_management') }}" class="btn btn-outline-secondary">Back to Users List</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function validatePassword() {
        let password = document.getElementById("password").value;
        let errorMsg = document.getElementById("password-error");

        if (password.length < 6) {
            errorMsg.innerText = "Password must be at least 6 characters.";
            return false; 
        } else {
            errorMsg.innerText = "";
            return true; 
        }
    }
</script>
@endsection
