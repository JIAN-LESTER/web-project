@extends('layouts.app')

@section('content').
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<!-- Include Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<!-- Include Custom CSS -->
<link rel="stylesheet" href="{{ asset('admin/user_management.css') }}">
<div class="container-fluid users-container">
    <div class="users-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="users-title">FAQ Management</h1>
                <p class="users-subtitle">Manage questions, answers, and categories</p>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="{{ route('faqs.create') }}" class="btn">
                    <i class="fas fa-plus-circle me-1"></i> Add New FAQ
                </a>
            </div>
        </div>
    </div>

    <div class="users-card">
        <div class="card-body">

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="users-table-wrapper">
                <table class="table users-table">
                    <thead>
                        <tr>
                            <th>Question</th>
                            <th>Answer</th>
                            <th>Category</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($faqs as $faq)
                        <tr>
                            <td class="fw-semibold">{{ $faq->question }}</td>
                            <td style="white-space: pre-line; word-wrap: break-word;">
                                @if (empty($faq->answer))
                                    <em class="text-muted">The AI will answer</em>
                                @else
                                    {{ $faq->answer }}
                                @endif
                            </td>
                            <td>{{ $faq->category->category_name ?? 'N/A' }}</td>
                            <td class="user-actions-cell">
                            <div class="action-buttons">
                                    <a href="{{ route('faqs.edit', $faq->faqID) }}" class="btn-icon" data-bs-toggle="tooltip" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('faqs.destroy', $faq->faqID) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this FAQ?');" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon" data-bs-toggle="tooltip" title="Delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="no-data">
                                <div class="empty-users text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="none" stroke="currentColor" stroke-width="2"
                                         stroke-linecap="round" stroke-linejoin="round" class="empty-icon">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                    </svg>
                                    <h5>No FAQs found</h5>
                                    <p>No available FAQs. Try adding one above.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pagination-wrapper">
    {{ $faqs->links() }}
</div>

            <div class="pagination-wrapper mt-3 d-flex justify-content-between align-items-center">
                <div class="pagination-info">
                    Showing {{ $faqs->firstItem() ?? 0 }} to {{ $faqs->lastItem() ?? 0 }} of {{ $faqs->total() }} FAQs
                </div>
                <div class="pagination">
                    <a href="{{ $faqs->previousPageUrl() }}"
                       class="page-link {{ $faqs->onFirstPage() ? 'disabled' : '' }}">
                        <i class="fas fa-chevron-left"></i>
                    </a>

                    @for ($i = 1; $i <= $faqs->lastPage(); $i++)
                        <a href="{{ $faqs->url($i) }}"
                           class="page-link {{ $faqs->currentPage() == $i ? 'active' : '' }}">
                            {{ $i }}
                        </a>
                    @endfor

                    <a href="{{ $faqs->nextPageUrl() }}"
                       class="page-link {{ !$faqs->hasMorePages() ? 'disabled' : '' }}">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
            </div>
      

        </div>
    </div>
</div>
@endsection
