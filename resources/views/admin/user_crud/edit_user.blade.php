@extends('layouts.app')

@section('content')
<div class="container-fluid mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-10">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-primary text-white text-center rounded-top-4">
                    <h3 class="mb-0">Edit User</h3>
                </div>
                <div class="card-body p-4">
                    {{-- Validation Errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.update', $user->userID) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Name --}}
                        <div class="mb-3">
                            <label for="edit-name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="edit-name" name="name"
                                value="{{ old('name', $user->name) }}" required>
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <label for="edit-email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="edit-email" name="email"
                                value="{{ old('email', $user->email) }}" required>
                        </div>

                        {{-- Password --}}
                        <div class="mb-3">
                            <label for="edit-password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="edit-password" name="password" placeholder="Leave blank to keep current password">

                        </div>

                        {{-- Role --}}
                        <div class="mb-3">
                            <label for="edit-role" class="form-label">Role</label>
                            <select class="form-select" id="edit-role" name="role" required>
                                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>User</option>
                            </select>
                        </div>

                        <div class="row">
    {{-- Year Level --}}
    <div class="col-md-6 mb-3">
        <label for="edit-year" class="form-label">Year Level</label>
        <select class="form-select" id="edit-year" name="year_id">
            @if ($user->year)
                <option value="{{ $user->yearID }}" selected>{{ $user->year->year_level }}</option>
            @else
                <option value="" selected>N/A</option>
            @endif
            <option disabled>──────────</option>
            <option value="">N/A</option>
            @foreach ($years as $year)
                @if (!$user->year || $year->yearID != $user->yearID)
                    <option value="{{ $year->yearID }}">{{ $year->year_level }}</option>
                @endif
            @endforeach
        </select>
    </div>

    {{-- Course --}}
    <div class="col-md-6 mb-3">
        <label for="edit-course" class="form-label">Course</label>
        <select class="form-select" id="edit-course" name="course_id">
            @if ($user->course)
                <option value="{{ $user->courseID }}" selected>{{ $user->course->course_name }}</option>
            @else
                <option value="" selected>N/A</option>
            @endif
            <option disabled>──────────</option>
            <option value="">N/A</option>
            @foreach ($courses as $course)
                @if (!$user->course || $course->courseID != $user->courseID)
                    <option value="{{ $course->courseID }}">{{ $course->course_name }}</option>
                @endif
            @endforeach
        </select>
    </div>
</div>

                        {{-- Current Avatar --}}
                        <div class="mb-3">
                            <label class="form-label">Current Avatar</label><br>
                            @if ($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" width="100" height="100"
                                    class="rounded-circle border">
                            @else
                                <p class="text-muted">No avatar uploaded.</p>
                            @endif
                        </div>

                        {{-- Change Avatar --}}
                        <div class="mb-4">
                            <label for="edit-avatar" class="form-label">Change Avatar</label>
                            <input type="file" class="form-control mb-3" id="edit-avatar" name="avatar">
                        </div>

                        {{-- Buttons --}}
                        <div class="d-grid gap-3">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                            <a href="{{ route('admin.user_management') }}" class="btn btn-outline-secondary">Back to Users List</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


    <script>
        const yearSelect = document.getElementById('edit-year');
        const courseSelect = document.getElementById('edit-course');

        function handleYearChange() {
            const selectedValue = yearSelect.value;

            // If year is "N/A", "incoming", null or empty, set course to N/A and disable it
            if (!selectedValue || selectedValue === '0' || selectedValue === 'incoming' || selectedValue === 'N/A') {
                courseSelect.value = '';  // Set course to N/A (empty)
                courseSelect.disabled = true;  // Disable the course selection
            } else {
                courseSelect.disabled = false;  // Enable course selection
            }
        }

        // Attach event listener
        yearSelect.addEventListener('change', handleYearChange);

        // Check on page load (in case the year is already set to N/A or other)
        handleYearChange();
    </script>
@endsection
