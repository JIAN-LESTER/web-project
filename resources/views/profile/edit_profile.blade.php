@extends('layouts.app')

@section('content')

    <div class="container-fluid">
        <h1 class="text-center mb-4">Edit Profile</h1>

        <div class="bg-white p-4 shadow rounded mx-auto" style="max-width: 700px;">
            <form action="{{ route('profile.update', $user->userID) }}" method="POST" enctype="multipart/form-data"
                class="p-4 rounded bg-white">
                @csrf
                @method('PUT')

                <div class="mb-3 text-center">
                    <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}"
                        alt="User Avatar" class="rounded-circle mb-3 border" width="150" height="150">
                </div>

                <div class="mb-3">
                    <label for="avatar" class="form-label">Upload Avatar</label>
                    <input type="file" name="avatar" id="avatar" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}"
                        required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control"
                        value="{{ old('email', $user->email) }}" required>
                </div>

                @if ($user->role === 'user')

                <div class="mb-3">
                    <label for="year" class="form-label">Year</label>
                    <select name="year_id" id="year" class="form-select">
                        <option value="">Select Year</option>
                        @foreach ($years as $year)
                            <option value="{{ $year->yearID }}" {{ old('year_id', $user->yearID) == $year->yearID ? 'selected' : '' }}>
                                {{ $year->year_level }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Course Dropdown -->
                <div class="mb-3">
                    <label for="course" class="form-label">Course</label>
                    <select name="course_id" id="course" class="form-select">
                        <option value="">Select Course</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->courseID }}" {{ old('course_id', $user->courseID) == $course->courseID ? 'selected' : '' }}>
                                {{ $course->course_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif
                <hr>
                <h5>Change Password (Optional)</h5>

                <div class="mb-3">
                    <label for="old_password" class="form-label">Current Password</label>
                    <input type="password" name="old_password" id="old_password" class="form-control">
                    @error('old_password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="new_password" class="form-label">New Password</label>
                    <input type="password" name="new_password" id="new_password" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                    <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                        class="form-control">
                </div>

                <button type="submit" class="btn btn-success">Update Profile</button>
                <a href="{{ route('profile') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>

    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}'
            });
        </script>
    @endif

@endsection