@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-5">
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
                                <label for="name" class="form-label">Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Enter name"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" name="email" id="email" class="form-control"
                                    placeholder="Enter email address" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" id="password" class="form-control"
                                    placeholder="Enter password" required>
                                <small id="password-error" class="text-danger"></small>
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="form-control" placeholder="Confirm password" required>
                            </div>

                            <div class="mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select name="role" id="role" class="form-select" required>
                                    <option value="">Select Role</option>
                                    <option value="user">User</option>
                                    <option value="admin">Admin</option>

                                </select>
                                @error('role')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row mb-3">
                                <!-- Year Level -->
                                <div class="col-md-6 mb-3">
                                    <label for="year" class="form-label">Year Level</label>
                                    <select name="year_id" id="year" class="form-select">
                                        <option value="">Select Year (Optional)</option>
                                        @foreach ($years as $year)
                                            <option value="{{ $year->yearID }}">{{ $year->year_level }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Course -->
                                <div class="col-md-6 mb-3">
                                    <label for="course" class="form-label">Course</label>
                                    <select name="course_id" id="course" class="form-select">
                                        <option value="">Select Course (Optional)</option>
                                        @foreach ($courses as $course)
                                            <option value="{{ $course->courseID }}">{{ $course->course_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Add User</button>
                                <a href="{{ route('admin.user_management') }}" class="btn btn-outline-secondary">Back to
                                    Users List</a>
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

    <script>
        const yearSelect = document.getElementById('year');
        const courseSelect = document.getElementById('course');

        function handleYearChange() {
            const selectedValue = yearSelect.value;

            if (!selectedValue || selectedValue === '0') {
                // If year is empty or 0, disable and clear course
                courseSelect.value = '';
                courseSelect.disabled = true;
            } else {
                // Enable course selection
                courseSelect.disabled = false;
            }
        }

        // Attach event listener
        yearSelect.addEventListener('change', handleYearChange);

        // Check on page load
        handleYearChange();
    </script>
@endsection