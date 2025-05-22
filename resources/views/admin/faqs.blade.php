@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="{{ asset('admin/user_management.css') }}">

<div class="container-fluid users-container">
    <div class="users-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="users-title">FAQ Management</h1>
                <p class="users-subtitle">Manage questions, answers, and categories</p>
            </div>
            <div class="col-md-6 text-md-end">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFaqModal">
                    <i class="fas fa-plus-circle me-1"></i> Add New FAQ
                </button>
            </div>
        </div>
    </div>

    <div class="users-card">
        <div class="card-body">

            <!-- Live Search and Filter -->
            <div class="mb-3 d-flex flex-wrap gap-2">
                <input type="text" id="searchInput" class="form-control" placeholder="Search by question or category..." style="flex: 1 1 300px;">
                <select id="categoryFilter" class="form-select" style="flex: 0 0 200px;">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ strtolower($cat->category_name) }}">{{ $cat->category_name }}</option>
                    @endforeach
                </select>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="users-table-wrapper mt-4">
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
                        <tr id="noResultsRow" style="display: none;">
    <td colspan="4" class="text-center text-muted">No FAQs found.</td>
</tr>
                            <tr>
                                <td class="fw-semibold">{{ $faq->question }}</td>
                                <td style="white-space: pre-line;">
                                    @if (empty($faq->answer))
                                        <em class="text-muted">The AI will answer</em>
                                    @else
                                        {{ $faq->answer }}
                                    @endif
                                </td>
                                <td>{{ $faq->category->category_name ?? 'N/A' }}</td>
                                <td class="text-center">
                                    <div class="d-inline-flex gap-2">
                                        <button class="btn btn-sm btn-outline-primary" onclick="openEditModal({{ $faq->faqID }})">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('faqs.destroy', $faq->faqID) }}" method="POST" onsubmit="return confirm('Delete this FAQ?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No FAQs found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>Showing {{ $faqs->firstItem() ?? 0 }} to {{ $faqs->lastItem() ?? 0 }} of {{ $faqs->total() }} FAQs</div>
                <div class="pagination">
                    {{ $faqs->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add FAQ Modal -->
<div class="modal fade" id="addFaqModal" tabindex="-1" aria-labelledby="addFaqModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('faqs.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New FAQ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Question</label>
                        <input type="text" name="question" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Answer</label>
                        <textarea name="answer" rows="4" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Category</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->categoryID }}">{{ $cat->category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-primary" type="submit">Add FAQ</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editFaqModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editFaqForm" method="POST">
                @csrf @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit FAQ</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="text" name="question" id="editQuestion" class="form-control mb-3" placeholder="Question" required>
                    <textarea name="answer" id="editAnswer" rows="4" class="form-control mb-3" placeholder="Answer"></textarea>
                    <select name="category_id" id="editCategory" class="form-select" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->categoryID }}">{{ $cat->category_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-primary" type="submit">Update FAQ</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openEditModal(faqID) {
        fetch(`/faqs/${faqID}/edit`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('editQuestion').value = data.question;
                document.getElementById('editAnswer').value = data.answer;
                document.getElementById('editCategory').value = data.category_id;
                document.getElementById('editFaqForm').action = `/faqs/${faqID}`;
                new bootstrap.Modal(document.getElementById('editFaqModal')).show();
            });
    }

    // Live Filter
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('searchInput');
        const categoryFilter = document.getElementById('categoryFilter');

        function filterFaqs() {
    const search = searchInput.value.toLowerCase();
    const category = categoryFilter.value.toLowerCase();
    const rows = document.querySelectorAll('table tbody tr');
    let visibleCount = 0;

    rows.forEach(row => {
        const isNoResultRow = row.id === 'noResultsRow';
        if (isNoResultRow) return; // Skip placeholder row

        const question = row.cells[0].textContent.toLowerCase();
        const cat = row.cells[2].textContent.toLowerCase();

        const matchSearch = question.includes(search) || cat.includes(search);
        const matchCategory = !category || cat === category;

        const shouldShow = matchSearch && matchCategory;
        row.style.display = shouldShow ? '' : 'none';

        if (shouldShow) visibleCount++;
    });

    document.getElementById('noResultsRow').style.display = visibleCount === 0 ? '' : 'none';
}

        searchInput.addEventListener('input', filterFaqs);
        categoryFilter.addEventListener('change', filterFaqs);
    });
</script>
@endsection
