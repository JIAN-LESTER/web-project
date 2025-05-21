@extends('layouts.app')

@section('content')

<div>
    <div class="text-center mb-4">
        <h1 class="fw-bold">Profile</h1>
    </div>

    <div class="card mx-auto shadow-sm" style="max-width: 700px;">
        <div class="card-body text-center">
            <img src="{{ asset('storage/' . ($user->avatar ?? 'avatars/default.png')) }}"
                alt="User Avatar"
                class="rounded-circle mb-3"
                width="150" height="150">

            <h5 class="mb-2"><strong>Name:</strong> {{ $user->name }}</h5>
            <h5 class="mb-2"><strong>Email:</strong> {{ $user->email }}</h5>
            @if ($user->role === 'user')
            <h5 class="mb-2"><strong>Year:</strong> {{ $user->year->year_level }}</h5>
            <h5 class="mb-2"><strong>Course:</strong> {{ $user->course->course_name }}</h5>
            @endif

            <h5 class="mb-0"><strong>Joined:</strong> {{ $user->created_at->format('F d, Y') }}</h5>
        </div>
    </div>

    <div class="text-center mt-3">
        <a href="{{ route('profile.edit', $user->userID) }}" class="btn btn-primary px-4">
            Edit Profile
        </a>
    </div>

    <?php
    $user = Auth::user();
    
    ?>

    <div class="text-center mt-2">
        @if ($user->role === 'user')
        <a href="{{ route('chatbot') }}" class="btn btn-secondary px-4">
        @else
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary px-4">
        @endif
      
            Back
        </a>
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
