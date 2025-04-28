@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('admin/user_management.css') }}">

<div class="container-fluid user-management-container">
    <div class="user-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="user-title">Users Management</h1>
                <p class="user-subtitle">Manage accounts and user roles</p>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="{{ route('admin.create') }}" class="btn btn-primary add-user-btn">
                    <i class="fas fa-user-plus me-2"></i>Add New User
                </a>
            </div>
        </div>
    </div>

    <div class="user-card">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-lg-8 col-md-7">
                    <form action="{{ route('admin.user_management') }}" method="GET" class="user-search-form">
                        <div class="search-wrapper">
                            <input type="text" name="search" class="form-control search-input"
                                   placeholder="Search by name, email or role..."
                                   value="{{ $search }}">
                            <button type="submit" class="btn search-button">
                                <i class="fas fa-search"></i> Search
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col-lg-4 col-md-5">
                    <div class="dropdown filter-dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-filter me-2"></i>Filter Options
                        </button>
                        <ul class="dropdown-menu filter-menu" aria-labelledby="filterDropdown">
                            <li>
                                <div class="filter-section">
                                    <h6 class="filter-heading">Filter by Role</h6>
                                    <div class="form-check">
                                        <input class="form-check-input filter-check" type="checkbox" value="admin" id="adminCheck">
                                        <label class="form-check-label" for="adminCheck">Admin</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input filter-check" type="checkbox" value="user" id="userCheck">
                                        <label class="form-check-label" for="userCheck">User</label>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="filter-section">
                                    <h6 class="filter-heading">Filter by Status</h6>
                                    <div class="form-check">
                                        <input class="form-check-input filter-check" type="checkbox" value="active" id="activeCheck">
                                        <label class="form-check-label" for="activeCheck">Active</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input filter-check" type="checkbox" value="inactive" id="inactiveCheck">
                                        <label class="form-check-label" for="inactiveCheck">Inactive</label>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="filter-actions">
                                    <button type="button" class="btn" id="applyFilters">Apply Filters</button>
                                    <button type="button" class="btn" id="clearFilters">Clear</button>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table users-table">
                    <thead>
                        <tr>
                            <th class="th-avatar"></th>
                            <th class="th-user">User</th>
                            <th class="th-role">Role</th>
                            <th class="th-course">Course</th>
                            <th class="th-year">Year</th>
                            <th class="th-status">Status</th>
                            <th class="th-actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr class="user-row">
                            <td class="td-avatar">
                                @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" class="user-avatar" alt="{{ $user->name }}">
                                @else
                                <div class="avatar-placeholder">{{ substr($user->name, 0, 1) }}</div>
                                @endif
                            </td>
                            <td class="td-user">
                                <div class="user-info">
                                    <div class="user-name">{{ $user->name }}</div>
                                    <div class="user-email">{{ $user->email }}</div>
                                </div>
                            </td>
                            <td class="td-role">
                                @if ($user->role == 'admin')
                                <span class="badge role-admin">Admin</span>
                                @else
                                <span class="badge role-user">User</span>
                                @endif
                            </td>
                            <td class="td-course">{{ $user->course->course_name ?? 'N/A' }}</td>
                            <td class="td-year">{{ $user->year->year_level ?? 'N/A' }}</td>
                            <td class="td-status">
                                @if($user->user_status == 'active')
                                <span class="badge status-active">
                                    <i class="fas fa-circle status-icon"></i> Active
                                </span>
                                @else
                                <span class="badge status-inactive">
                                    <i class="fas fa-circle status-icon"></i> Inactive
                                </span>
                                @endif
                            </td>
                            <td class="td-actions">
                                <div class="action-buttons">
                                    <a href="{{ route('admin.show', $user->userID) }}" class="btn-action btn-view" data-bs-toggle="tooltip" data-bs-placement="top" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.edit', $user->userID) }}" class="btn-action btn-edit" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit User">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn-action btn-delete" onclick="confirmDelete({{ $user->userID }})" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete User">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                    <form id="delete-form-{{ $user->userID }}" action="{{ route('admin.destroy', $user->userID) }}" method="POST" style="display:none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pagination-wrapper">
                <div class="pagination-info">
                    Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} users
                </div>
                <div class="pagination">
                    <!-- Left arrow -->
                    <a href="{{ $users->previousPageUrl() }}"
                       class="page-link {{ $users->onFirstPage() ? 'disabled' : '' }}">
                        ←
                    </a>

                    <!-- Page numbers -->
                    @for ($i = 1; $i <= $users->lastPage(); $i++)
                        <a href="{{ $users->url($i) }}"
                           class="page-link {{ $users->currentPage() == $i ? 'active' : '' }}">
                            {{ $i }}
                        </a>
                    @endfor

                    <!-- Right arrow -->
                    <a href="{{ $users->nextPageUrl() }}"
                       class="page-link {{ !$users->hasMorePages() ? 'disabled' : '' }}">
                        →
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Delete confirmation
    window.confirmDelete = function(userID) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#6c5ce7',
            cancelButtonColor: '#ff4757',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            customClass: {
                confirmButton: 'swal-confirm-btn',
                cancelButton: 'swal-cancel-btn'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${userID}`).submit();
            }
        });
    }

    // Filter functionality
    document.getElementById('applyFilters').addEventListener('click', function() {
        const roleFilters = [];
        if (document.getElementById('adminCheck').checked) roleFilters.push('admin');
        if (document.getElementById('userCheck').checked) roleFilters.push('user');

        const statusFilters = [];
        if (document.getElementById('activeCheck').checked) statusFilters.push('active');
        if (document.getElementById('inactiveCheck').checked) statusFilters.push('inactive');

        const currentUrl = new URL(window.location.href);

        // Clear existing role and status params
        currentUrl.searchParams.delete('role');
        currentUrl.searchParams.delete('status');

        // Add selected filters
        roleFilters.forEach(role => {
            currentUrl.searchParams.append('role', role);
        });

        statusFilters.forEach(status => {
            currentUrl.searchParams.append('status', status);
        });

        window.location.href = currentUrl.toString();
    });

    document.getElementById('clearFilters').addEventListener('click', function() {
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.delete('role');
        currentUrl.searchParams.delete('status');
        window.location.href = currentUrl.toString();
    });

    // Add row hover effect
    const userRows = document.querySelectorAll('.user-row');
    userRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.classList.add('row-hover');
        });
        row.addEventListener('mouseleave', function() {
            this.classList.remove('row-hover');
        });
    });
});
</script>

@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#6c5ce7',
            customClass: {
                container: 'my-swal-container',
                confirmButton: 'swal-confirm-btn'
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
            confirmButtonColor: '#6c5ce7',
            customClass: {
                container: 'my-swal-container',
                confirmButton: 'swal-confirm-btn'
            },
            didOpen: () => {
                document.querySelector('.swal2-container').style.zIndex = '10000000';
            }
        });
    });
</script>
@endif
@endsection
