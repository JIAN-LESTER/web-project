<!-- resources/views/profile.blade.php -->
@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-4">
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold mb-4">Profile</h2>

        <div class="space-y-4">
            <div>
                <strong class="text-gray-700">Name:</strong>
                <p class="text-gray-900">{{ $user->name }}</p>
            </div>

            <div>
                <strong class="text-gray-700">Email:</strong>
                <p class="text-gray-900">{{ $user->email }}</p>
            </div>

            <div>
                <strong class="text-gray-700">Joined On:</strong>
                <p class="text-gray-900">{{ $user->created_at->format('F j, Y') }}</p>
            </div>

            <div>
                <strong class="text-gray-700">Phone:</strong>
                <p class="text-gray-900">{{ $user->phone ?? 'Not Provided' }}</p>
            </div>

            <div>
                <strong class="text-gray-700">Address:</strong>
                <p class="text-gray-900">{{ $user->address ?? 'Not Provided' }}</p>
            </div>
        </div>

        <div class="mt-6">
            <a href="{{ route('profile.edit') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Edit Profile</a>
        </div>
    </div>
</div>
@endsection
