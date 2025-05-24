@extends('layouts.app')

@section('content')
    <!-- Include Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Include Custom CSS -->
    <link rel="stylesheet" href="{{ asset('admin/faq_management.css') }}">

    <div class="container-fluid faqs-container">
        <div class="faqs-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="faqs-title">FAQ Management</h1>
                    <p class="faqs-subtitle">Manage questions, answers, and categories</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="action-buttons">
                        <button id="openAddFaqBtn" class="btn upload-document-btn">
                            <i class="fas fa-plus-circle"></i>Add FAQ
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="faqs-card">
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-lg-8">
                        <form method="GET" class="faqs-search-form" id="searchForm">
                            <div class="row g-2">
                                <div class="col-lg-8 col-md-7">
                                    <input type="text" id="searchInput" class="form-control"
                                        placeholder="Search by question or category...">
                                </div>
                                <div class="col-lg-4 col-md-5">
                                    <select id="categoryFilter" class="form-control category-filter">
                                        <option value="">All Categories</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ strtolower($cat->category_name) }}">{{ $cat->category_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="faqs-table-wrapper">
                    <table class="table faqs-table">
                        <thead>
                            <tr>
                                <th class="sortable col-question" data-sort="question">
                                    <div class="sort-header">
                                        Question <i class="fas fa-sort"></i>
                                    </div>
                                </th>
                                <th class="sortable col-answer" data-sort="answer">
                                    <div class="sort-header">
                                        Answer <i class="fas fa-sort"></i>
                                    </div>
                                </th>
                                <th class="sortable col-category" data-sort="category">
                                    <div class="sort-header">
                                        Category <i class="fas fa-sort"></i>
                                    </div>
                                </th>
                                <th class="col-actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($faqs as $faq)
                                <tr class="faq-row" data-faq-id="{{ $faq->faqID }}" style="--i: {{ $loop->index }}">
                                    <td class="faq-question-cell">
                                        <div class="faq-question">
                                            <i class="fas fa-question-circle faq-icon"></i>
                                            {{ $faq->question }}
                                        </div>
                                    </td>
                                    <td class="faq-answer-cell">
                                        <div class="faq-answer">
                                            @if (empty($faq->answer))
                                                <em class="ai-answer">The AI will answer</em>
                                            @else
                                                {{ Str::limit($faq->answer, 100) }}
                                            @endif
                                        </div>
                                    </td>
                                    <td class="faq-category-cell">
                                        <span class="category-badge category-default">
                                            {{ $faq->category->category_name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="faq-actions-cell">
                                        <div class="action-buttons">
                                            <button type="button" class="btn-icon"
                                                onclick="showEditFaqModal(); populateEditFaqModal({{ $faq->faqID }})"
                                                title="Edit FAQ">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('faqs.destroy', $faq->faqID) }}" method="POST"
                                                style="display: inline;" onsubmit="return confirmDelete()">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn-icon" title="Delete FAQ">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="no-data">
                                        <div class="empty-faqs">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" class="empty-icon">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                                                <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                            </svg>
                                            <h5>No FAQs found</h5>
                                            <p>No matching FAQs with the current search criteria</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                            <tr id="noResultsRow" style="display: none;">
                                <td colspan="4" class="no-data">
                                    <div class="empty-faqs">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="empty-icon">
                                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                                            <line x1="12" y1="9" x2="12" y2="13"></line>
                                            <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                        </svg>
                                        <h5>No matching FAQs</h5>
                                        <p>Try a different search term or category</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="pagination-wrapper">
                    <div class="pagination-info">
                        Showing {{ $faqs->firstItem() ?? 0 }} to {{ $faqs->lastItem() ?? 0 }} of {{ $faqs->total() }} FAQs
                    </div>
                    <div class="pagination">
                        <!-- Left arrow -->
                        <a href="{{ $faqs->previousPageUrl() }}"
                            class="page-link {{ $faqs->onFirstPage() ? 'disabled' : '' }}">
                            <i class="fas fa-chevron-left"></i>
                        </a>

                        <!-- Page numbers -->
                        @for ($i = 1; $i <= $faqs->lastPage(); $i++)
                            <a href="{{ $faqs->url($i) }}" class="page-link {{ $faqs->currentPage() == $i ? 'active' : '' }}">
                                {{ $i }}
                            </a>
                        @endfor

                        <!-- Right arrow -->
                        <a href="{{ $faqs->nextPageUrl() }}"
                            class="page-link {{ !$faqs->hasMorePages() ? 'disabled' : '' }}">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add FAQ Modal -->
    <div class="upload-modal-backdrop" id="addFaqModalBackdrop"></div>
    <div class="upload-modal" id="addFaqModal">
        <div class="upload-modal-content">
            <div class="upload-modal-header">
                <h4 class="upload-modal-title">Add New FAQ</h4>
                <button type="button" class="upload-modal-close" id="closeAddFaqModalBtn">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="upload-modal-body">
                <form action="{{ route('faqs.store') }}" method="POST" id="addFaqForm">
                    @csrf
                    <div class="form-grid">
                        <div class="form-group form-group-full">
                            <label for="question" class="minimalist-label">
                                <i class="fas fa-question-circle"></i>
                                Question <span class="required">*</span>
                            </label>
                            <input type="text" name="question" id="question" class="minimalist-input" required
                                placeholder="Enter the FAQ question">
                            <div class="invalid-feedback" id="questionError"></div>
                        </div>

                        <div class="form-group form-group-full">
                            <label for="answer" class="minimalist-label">
                                <i class="fas fa-comment-dots"></i>
                                Answer
                            </label>
                            <textarea name="answer" id="answer" rows="4" class="minimalist-input"
                                placeholder="Enter the answer (leave empty for AI to answer)"></textarea>
                            <div class="invalid-feedback" id="answerError"></div>
                        </div>

                        <div class="form-group">
                            <label for="category_id" class="minimalist-label">
                                <i class="fas fa-tags"></i>
                                Category <span class="required">*</span>
                            </label>
                            <select name="category" id="category_id" class="minimalist-select" required>
                                <option value="" disabled selected>Select Category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->categoryID }}">{{ $cat->category_name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="categoryError"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="upload-modal-footer">
                <button type="button" class="btn-minimalist btn-cancel" id="cancelAddFaqBtn">
                    <i class="fas fa-times"></i>
                    Cancel
                </button>
                <button type="submit" form="addFaqForm" class="btn-minimalist btn-upload">
                    <i class="fas fa-plus-circle"></i>
                    Add FAQ
                </button>
            </div>
        </div>
    </div>

    <!-- Edit FAQ Modal -->
    <div class="upload-modal-backdrop" id="editFaqModalBackdrop"></div>
    <div class="upload-modal" id="editFaqModal">
        <div class="upload-modal-content">
            <div class="upload-modal-header">
                <h4 class="upload-modal-title">Edit FAQ</h4>
                <button type="button" class="upload-modal-close" id="closeEditFaqModalBtn">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="upload-modal-body">
                <form action="" method="POST" id="editFaqForm">
                    @csrf
                    @method('PUT')
                    <div class="form-grid">
                        <div class="form-group form-group-full">
                            <label for="editQuestion" class="minimalist-label">
                                <i class="fas fa-question-circle"></i>
                                Question <span class="required">*</span>
                            </label>
                            <input type="text" name="question" id="editQuestion" class="minimalist-input" required
                                placeholder="Enter the FAQ question">
                            <div class="invalid-feedback" id="editQuestionError"></div>
                        </div>

                        <div class="form-group form-group-full">
                            <label for="editAnswer" class="minimalist-label">
                                <i class="fas fa-comment-dots"></i>
                                Answer
                            </label>
                            <textarea name="answer" id="editAnswer" rows="4" class="minimalist-input"
                                placeholder="Enter the answer (leave empty for AI to answer)"></textarea>
                            <div class="invalid-feedback" id="editAnswerError"></div>
                        </div>

                        <div class="form-group">
                            <label for="editCategory" class="minimalist-label">
                                <i class="fas fa-tags"></i>
                                Category <span class="required">*</span>
                            </label>
                            <select name="category" id="editCategory" class="minimalist-select" required>
                                <option value="" disabled>Select Category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->categoryID }}">{{ $cat->category_name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="editCategoryError"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="upload-modal-footer">
                <button type="button" class="btn-minimalist btn-cancel" id="cancelEditFaqBtn">
                    <i class="fas fa-times"></i>
                    Cancel
                </button>
                <button type="submit" form="editFaqForm" class="btn-minimalist btn-upload">
                    <i class="fas fa-save"></i>
                    Save Changes
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Elements for Add FAQ Modal
            const addFaqModal = document.getElementById('addFaqModal');
            const addFaqModalBackdrop = document.getElementById('addFaqModalBackdrop');
            const openAddFaqBtn = document.getElementById('openAddFaqBtn');
            const closeAddFaqModalBtn = document.getElementById('closeAddFaqModalBtn');
            const cancelAddFaqBtn = document.getElementById('cancelAddFaqBtn');
            const addFaqForm = document.getElementById('addFaqForm');

            // Show FAQ modal
            const showFaqModal = () => {
                addFaqModal.classList.add('show');
                addFaqModalBackdrop.classList.add('show');
                document.body.style.overflow = 'hidden';
            };

            // Close FAQ modal
            const closeFaqModal = () => {
                addFaqModal.classList.remove('show');
                addFaqModalBackdrop.classList.remove('show');
                document.body.style.overflow = '';
                resetFaqForm();
            };

            // Reset FAQ form
            const resetFaqForm = () => {
                addFaqForm.reset();
                clearFaqValidationErrors();
            };

            // Clear validation errors
            const clearFaqValidationErrors = () => {
                const errorFields = addFaqForm.querySelectorAll('.invalid-feedback');
                errorFields.forEach(field => field.textContent = '');
            };

            // Event listeners for Add FAQ Modal
            openAddFaqBtn?.addEventListener('click', showFaqModal);
            closeAddFaqModalBtn?.addEventListener('click', closeFaqModal);
            cancelAddFaqBtn?.addEventListener('click', closeFaqModal);
            addFaqModalBackdrop?.addEventListener('click', closeFaqModal);

            // Elements for Edit FAQ Modal
            const editFaqModal = document.getElementById('editFaqModal');
            const editFaqModalBackdrop = document.getElementById('editFaqModalBackdrop');
            const closeEditFaqModalBtn = document.getElementById('closeEditFaqModalBtn');
            const cancelEditFaqBtn = document.getElementById('cancelEditFaqBtn');
            const editFaqForm = document.getElementById('editFaqForm');

            // Show Edit FAQ modal
            window.showEditFaqModal = function () {
                editFaqModal.classList.add('show');
                editFaqModalBackdrop.classList.add('show');
                document.body.style.overflow = 'hidden';
            };

            // Close Edit FAQ modal
            const closeEditFaqModal = () => {
                editFaqModal.classList.remove('show');
                editFaqModalBackdrop.classList.remove('show');
                document.body.style.overflow = '';
                resetEditFaqForm();
            };

            // Reset Edit FAQ form
            const resetEditFaqForm = () => {
                editFaqForm.reset();
                clearEditFaqValidationErrors();
            };

            // Clear Edit validation errors
            const clearEditFaqValidationErrors = () => {
                const errorFields = editFaqForm.querySelectorAll('.invalid-feedback');
                errorFields.forEach(field => field.textContent = '');
            };

            // Populate modal with existing FAQ data
            window.populateEditFaqModal = function (faqID) {
                fetch(`/faqs/json/${faqID}`)
                    .then(response => {
                        if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
                        return response.json();
                    })
                    .then(data => {
                        console.log("FAQ data loaded:", data);
                        document.getElementById('editQuestion').value = data.question ?? '';
                        document.getElementById('editAnswer').value = data.answer ?? '';
                        document.getElementById('editCategory').value = data.category_id ?? '';
                        editFaqForm.action = `/faqs/update/${faqID}`;
                    })
                    .catch(error => {
                        console.error('Error loading FAQ:', error);
                    });
            };

            // Event listeners for Edit FAQ Modal
            closeEditFaqModalBtn?.addEventListener('click', closeEditFaqModal);
            cancelEditFaqBtn?.addEventListener('click', closeEditFaqModal);
            editFaqModalBackdrop?.addEventListener('click', closeEditFaqModal);

            // ESC key to close modals
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    if (addFaqModal.classList.contains('show')) {
                        closeFaqModal();
                    }
                    if (editFaqModal.classList.contains('show')) {
                        closeEditFaqModal();
                    }
                }
            });

            // Live Filter functionality
            const searchInput = document.getElementById('searchInput');
            const categoryFilter = document.getElementById('categoryFilter');

            function filterFaqs() {
                const search = searchInput.value.toLowerCase();
                const category = categoryFilter.value.toLowerCase();
                const rows = document.querySelectorAll('.faq-row');
                const noResultsRow = document.getElementById('noResultsRow');
                let visibleCount = 0;

                rows.forEach(row => {
                    const question = row.querySelector('.faq-question')?.textContent.toLowerCase() || '';
                    const answer = row.querySelector('.faq-answer')?.textContent.toLowerCase() || '';
                    const cat = row.querySelector('.category-badge')?.textContent.toLowerCase() || '';

                    const matchSearch = question.includes(search) || answer.includes(search) || cat.includes(search);
                    const matchCategory = !category || cat.includes(category);

                    const shouldShow = matchSearch && matchCategory;
                    row.style.display = shouldShow ? '' : 'none';

                    if (shouldShow) visibleCount++;
                });

                // Show/hide no results message
                if (noResultsRow) {
                    noResultsRow.style.display = visibleCount === 0 && (search || category) ? '' : 'none';
                }
            }

            // Add event listeners for filtering
            if (searchInput) {
                searchInput.addEventListener('input', filterFaqs);
            }
            if (categoryFilter) {
                categoryFilter.addEventListener('change', filterFaqs);
            }

            // Delete confirmation
            window.confirmDelete = function() {
                return confirm('Are you sure you want to delete this FAQ? This action cannot be undone.');
            };

            // Add sorting functionality
            const sortHeaders = document.querySelectorAll('.sortable');
            sortHeaders.forEach(header => {
                header.addEventListener('click', function () {
                    const sort = this.getAttribute('data-sort');

                    // Toggle sort direction
                    const currentDirection = this.getAttribute('data-direction') || 'asc';
                    const newDirection = currentDirection === 'asc' ? 'desc' : 'asc';

                    // Reset all headers
                    document.querySelectorAll('.sortable').forEach(el => {
                        el.removeAttribute('data-direction');
                        el.querySelector('i').className = 'fas fa-sort';
                    });

                    // Update this header
                    this.setAttribute('data-direction', newDirection);
                    this.querySelector('i').className = `fas fa-sort-${newDirection === 'asc' ? 'up' : 'down'}`;

                    // Here you can implement actual sorting or redirect with sort parameters
                    const currentUrl = new URL(window.location.href);
                    currentUrl.searchParams.set('sort', sort);
                    currentUrl.searchParams.set('direction', newDirection);
                    // window.location.href = currentUrl.toString();
                });
            });
        });
    </script>

@endsection
