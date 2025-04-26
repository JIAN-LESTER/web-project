@extends('layouts.app')

@section('content')
<h1 class="mb-4">Users List</h1>

<a href="{{ route('admin.create') }}" class="btn btn-primary mb-4">Add User</a>

<form action="{{ route('admin.user_management') }}" method="GET" class="mt-3">
  <div class="input-group mb-3">
    <input type="text" name="search" class="input-field" placeholder="Search users..." value="{{ $search }}">
    <button class="btn btn-primary" type="submit">Search</button>
  </div>
</form>

<table class="table table-bordered table-striped">
  <thead class="table-light">
    <tr>
      <th></th>
      <th>User</th>
      <th>Role</th>
      <th>Course</th>
      <th>Year</th>
      <th>Status</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($users as $user)
    <tr>
      <td>
        @if($user->avatar)
          <img src="{{ asset('storage/' . $user->avatar) }}" class="rounded-circle" width="50" height="50">
        @else
          <img src="{{ asset('default-avatar.png') }}" class="rounded-circle" width="50" height="50">
        @endif
      </td>
      <td>{{ $user->name }} <br> {{ $user->email }}</td>
      <td>
        @if ($user->role == 'admin')
          <span class="badge bg-danger">Admin</span>
        @else
          <span class="badge bg-secondary">User</span>
        @endif
      </td>
      <td>{{ $user->course->course_name ?? 'N/A' }}</td>
      <td>{{ $user->year->year_level ?? 'N/A' }}</td>
      <td>
        @if($user->user_status == 'active')
          <span class="badge bg-success">Active</span>
        @else
          <span class="badge bg-danger">Inactive</span>
        @endif
      </td>
      <td class="d-flex gap-2 flex-wrap">
        <a href="{{ route('admin.show', $user->userID) }}" class="btn btn-sm btn-info text-white"><i class="fas fa-eye"></i> Show</a>
        <a href="{{ route('admin.edit', $user->userID) }}" class="btn btn-sm btn-success text-white">
          <i class="fas fa-edit"></i> Edit
        </a>
        <button class="btn btn-sm btn-danger" onclick="confirmDelete({{ $user->userID }})"><i class="fas fa-trash-alt"></i> Delete</button>
        <form id="delete-form-{{ $user->userID }}" action="{{ route('admin.destroy', $user->userID) }}" method="POST" style="display:none;">
          @csrf
          @method('DELETE')
        </form>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>

<div class="mt-3">
  {{ $users->appends(['search' => request('search')])->links('pagination::bootstrap-4') }}
</div>

<script>
function confirmDelete(userID) {
  Swal.fire({
    title: 'Are you sure?',
    text: "You won't be able to revert this!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085e6',
    cancelButtonColor: '#ff0000',
    confirmButtonText: 'Yes, delete it!'
  }).then((result) => {
    if (result.isConfirmed) {
      document.getElementById(`delete-form-${userID}`).submit();
    }
  });
}
</script>

@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            customClass: {
                container: 'my-swal-container'
            },
            didOpen: () => {
                document.querySelector('.swal2-container').style.zIndex = '10000000';
            }
        });
    });
</script>
@endif

@if(session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'warning',
            title: 'Something went wrong',
            text: '{{ session('error') }}',
            customClass: {
                container: 'my-swal-container'
            },
            didOpen: () => {
                document.querySelector('.swal2-container').style.zIndex = '10000000';
            }
        });
    });
</script>
@endif
@endsection
