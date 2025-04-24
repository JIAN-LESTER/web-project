@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


<h1 class="mb-4">Users List</h1>

<a href="{{ route('admin.create') }}" class="btn btn-primary mb-4">Add User</a>

<form action="{{ route('admin.user_management') }}" method="GET" class="mt-3">
  <div class="input-group mb-3">
    <input type="text" name="search" class="form-control" placeholder="Search users..." value="{{ $search }}">
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
      <td><img src="{{ asset('storage/' . $user->avatar) }}" class="rounded-circle" width="50" height="50"></td>
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
      <td>
        <a href="{{ route('admin.show', $user->userID) }}" class="btn btn-sm btn-info text-white"><i class="fas fa-eye"></i> Show</a>
        <button 
  class="btn btn-sm btn-warning text-white edit-user-btn" 
  data-bs-toggle="modal" 
  data-bs-target="#editModal"
  data-id="{{ $user->userID }}"
  data-name="{{ $user->name }}"x
  data-email="{{ $user->email }}"
  data-role="{{ $user->role }}"
  data-course="{{ $user->courseID }}"
  data-year="{{ $user->yearID }}"
  data-action="{{ route('admin.update', $user->userID) }}"
>
  <i class="fas fa-edit"></i> Edit
</button>
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

<!-- Edit User Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="edit-user-form" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel">Edit User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

          <div class="mb-3">
            <label for="edit-name" class="form-label">Name</label>
            <input type="text" class="form-control" id="edit-name" name="name" required>
          </div>

          <div class="mb-3">
            <label for="edit-email" class="form-label">Email</label>
            <input type="email" class="form-control" id="edit-email" name="email" required>
          </div>

          <div class="mb-3">
            <label for="edit-role" class="form-label">Role</label>
            <select class="form-select" id="edit-role" name="role" required>
              <option value="admin">Admin</option>
              <option value="user">User</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="edit-course" class="form-label">Course</label>
            <select class="form-select" id="edit-course" name="course_id" required>
              @foreach ($courses as $course)
                <option value="{{ $course->courseId }}">{{ $course->course_name }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label for="edit-year" class="form-label">Year Level</label>
            <select class="form-select" id="edit-year" name="year_id" required>
              @foreach ($years as $year)
                <option value="{{ $year->yearID }}">{{ $year->year_level }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label for="edit-avatar" class="form-label">Avatar</label>
            <input type="file" class="form-control" id="edit-avatar" name="avatar">
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </form>
  </div>
</div>





    <!-- Display success message -->
    @if(session('success'))
    <script>
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
    </script>
    @endif

    @if(session('not_verified'))
    <script>
        Swal.fire({
            icon: 'warning',
            title: 'Email Not Verified',
            text: '{{ session('not_verified') }}',
            customClass: {
                container: 'my-swal-container'
            },
            didOpen: () => {
                document.querySelector('.swal2-container').style.zIndex = '10000000';
            }
        });
    </script>
    @endif

    @if(session('error'))
    <script>
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
    </script>
    @endif


<script>
    function confirmDelete(userID){
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

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const editButtons = document.querySelectorAll('.edit-user-btn');
    editButtons.forEach(button => {
      button.addEventListener('click', function () {
        const userID = this.getAttribute('data-id');
        const name = this.getAttribute('data-name');
        const email = this.getAttribute('data-email');
        const role = this.getAttribute('data-role');
        const courseID = this.getAttribute('data-course');
        const yearID = this.getAttribute('data-year');
        const actionURL = this.getAttribute('data-action');

        // Set the form action
        const form = document.getElementById('edit-user-form');
        form.action = actionURL;

        // Populate form fields
        document.getElementById('edit-name').value = name;
        document.getElementById('edit-email').value = email;
        document.getElementById('edit-role').value = role;
        document.getElementById('edit-course').value = courseID;
        document.getElementById('edit-year').value = yearID;
      });
    });
  });
</script>


    

@endsection
