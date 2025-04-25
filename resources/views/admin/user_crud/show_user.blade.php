@extends('layouts.app')
@section('content')

<div class="container mt-5">
    <div class="text-center">
    <a href="{{ route('admin.user_management') }}" class="btn btn-secondary mb-4">Go Back</a>
    </div>

    
    <div class="card">
        <div class="card-body text-center">
        <img src="{{ asset('storage/' . ($user->avatar ?? 'avatars/storage/app/public/avatars/8yH5MdkfulUamccodv2j5CXZABgeyqhstahcZ8Rr.png')) }}" 
     alt="User Avatar" class="rounded-circle mb-3" width="150" height="150">

            <h1 class="card-title">Name: {{ $user->name }} {{ $user->last_name }}</h1>
            <h2 class="card-subtitle text-muted mb-3">Email: {{ $user->email }}</h2>
            <h2 class="card-subtitle text-muted mb-3">Role: <span style="text-transform:uppercase;">{{ $user->role }}</span></h2>
            <h2 class="card-subtitle text-muted mb-3">Year: {{ $user->year->year_level }}</h2>
            <h2 class="card-subtitle text-muted mb-3">Course: {{ $user->course->course_level }}</h2>


        </div>
    </div>
</div>

@endsection
