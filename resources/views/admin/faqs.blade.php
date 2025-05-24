@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="{{ asset('admin/user_management.css') }}">

<style>
/* Backdrop */
.upload-modal-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.5);
  z-index: 1040;
  opacity: 0;
  pointer-events: none;
  transition: opacity 0.3s ease;
}

.upload-modal-backdrop.show {
  opacity: 1;
  pointer-events: auto;
}

/* Modal */
.upload-modal {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%) scale(0.9);
  width: 100%;
  max-width: 480px;
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 15px 30px rgba(0,0,0,0.2);
  z-index: 1050;
  opacity: 0;
  pointer-events: none;
  transition: opacity 0.3s ease, transform 0.3s ease;
  outline: none;
}

.upload-modal.show {
  opacity: 1;
  pointer-events: auto;
  transform: translate(-50%, -50%) scale(1);
}

.upload-modal-content {
  display: flex;
  flex-direction: column;
  padding: 1.5rem 2rem;
}

/* Header */
.upload-modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid #eaeaea;
  padding-bottom: 0.5rem;
}

.upload-modal-title {
  font-size: 1.25rem;
  font-weight: 600;
  margin: 0;
}

.upload-modal-close {
  background: none;
  border: none;
  font-size: 1.25rem;
  cursor: pointer;
  color: #555;
  transition: color 0.2s ease;
}

.upload-modal-close:hover,
.upload-modal-close:focus {
  color: #d33;
  outline: none;
}

/* Body */
.upload-modal-body {
  margin-top: 1rem;
  display: flex;
  flex-direction: column;
  gap: 1.25rem;
}

/* Form group */
.form-group {
  display: flex;
  flex-direction: column;
}

.minimalist-label {
  font-weight: 600;
  margin-bottom: 0.25rem;
  color: #333;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.minimalist-input,
.minimalist-select,
textarea.minimalist-input {
  padding: 0.5rem 0.75rem;
  border: 1.5px solid #ccc;
  border-radius: 5px;
  font-size: 1rem;
  transition: border-color 0.3s ease;
  font-family: inherit;
  resize: vertical;
}

.minimalist-input:focus,
.minimalist-select:focus,
textarea.minimalist-input:focus {
  border-color: #007bff;
  outline: none;
}

/* Validation error */
.invalid-feedback {
  color: #d33;
  font-size: 0.875rem;
  margin-top: 0.25rem;
  min-height: 1.2em;
  visibility: hidden;
}

.is-invalid + .invalid-feedback {
  visibility: visible;
}

/* Footer */
.upload-modal-footer {
  margin-top: 1.5rem;
  display: flex;
  justify-content: flex-end;
  gap: 1rem;
}

.btn-minimalist {
  padding: 0.5rem 1.25rem;
  border-radius: 6px;
  font-weight: 600;
  font-size: 1rem;
  cursor: pointer;
  border: 2px solid transparent;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  transition: background-color 0.3s ease, border-color 0.3s ease;
  user-select: none;
}

.btn-minimalist i {
  font-size: 1.15rem;
}

.btn-upload {
  background-color: #007bff;
  color: white;
  border-color: #007bff;
}

.btn-upload:hover,
.btn-upload:focus {
  background-color: #0056b3;
  border-color: #0056b3;
  outline: none;
}

.btn-cancel {
  background-color: #e0e0e0;
  color: #555;
  border-color: #ccc;
}

.btn-cancel:hover,
.btn-cancel:focus {
  background-color: #cacaca;
  border-color: #b3b3b3;
  outline: none;
}
</style>

<div class="container-fluid users-container">
    <div class="users-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="users-title">FAQ Management</h1>
                <p class="users-subtitle">Manage questions, answers, and categories</p>
            </div>
            <div class="col-md-6 text-md-end">
           <button id="openAddFaqBtn" type="button" class="btn-minimalist btn-upload">
  <i class="fas fa-plus-circle"></i> Add FAQ
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
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="showEditFaqModal(); populateEditFaqModal({{ $faq->faqID }})">
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

<div class="upload-modal-backdrop" id="addFaqModalBackdrop" tabindex="-1" aria-hidden="true"></div>

<!-- Add FAQ Modal -->
<div class="upload-modal" id="addFaqModal" role="dialog" aria-modal="true" aria-labelledby="addFaqModalTitle" aria-describedby="addFaqModalDesc" tabindex="-1">
    <div class="upload-modal-content">
        <header class="upload-modal-header">
            <h4 class="upload-modal-title" id="addFaqModalTitle">Add New FAQ</h4>
            <button type="button" class="upload-modal-close" id="closeAddFaqModalBtn" aria-label="Close Add FAQ Modal">
                <i class="fas fa-times" aria-hidden="true"></i>
            </button>
        </header>
        <form action="{{ route('faqs.store') }}" method="POST" id="addFaqForm" novalidate>
            @csrf
            <section class="upload-modal-body" id="addFaqModalDesc">
                <div class="form-group">
                    <label for="question" class="minimalist-label">
                        <i class="fas fa-question-circle" aria-hidden="true"></i> Question <sup aria-hidden="true" style="color: red;">*</sup>
                    </label>
                    <input type="text" name="question" id="question" class="minimalist-input" required aria-required="true" aria-describedby="questionError" />
                    <div class="invalid-feedback" id="questionError" role="alert"></div>
                </div>
                <div class="form-group">
                    <label for="answer" class="minimalist-label">
                        <i class="fas fa-comment-dots" aria-hidden="true"></i> Answer
                    </label>
                    <textarea name="answer" id="answer" rows="4" class="minimalist-input" aria-describedby="answerError"></textarea>
                    <div class="invalid-feedback" id="answerError" role="alert"></div>
                </div>
                <div class="form-group">
                    <label for="category_id" class="minimalist-label">
                        <i class="fas fa-tags" aria-hidden="true"></i> Category <sup aria-hidden="true" style="color: red;">*</sup>
                    </label>
                    <select name="category" id="category_id" class="minimalist-select" required aria-required="true" aria-describedby="categoryError">
    <option value="" disabled selected>Select Category</option>
    @foreach($categories as $cat)
        <option value="{{ $cat->categoryID }}">{{ $cat->category_name }}</option>
    @endforeach
</select>
                    <div class="invalid-feedback" id="categoryError" role="alert"></div>
                </div>
            </section>
            <footer class="upload-modal-footer">
                <button type="button" class="btn-minimalist btn-cancel" id="cancelAddFaqBtn">
                    <i class="fas fa-times" aria-hidden="true"></i> Cancel
                </button>
                <button type="submit" class="btn-minimalist btn-upload">
                    <i class="fas fa-plus-circle" aria-hidden="true"></i> Add FAQ
                </button>
            </footer>
        </form>
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

    // Event listeners
    openAddFaqBtn?.addEventListener('click', showFaqModal);
    closeAddFaqModalBtn?.addEventListener('click', closeFaqModal);
    cancelAddFaqBtn?.addEventListener('click', closeFaqModal);
    addFaqModalBackdrop?.addEventListener('click', closeFaqModal);

    // ESC key to close FAQ modal
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && addFaqModal.classList.contains('show')) {
            closeFaqModal();
        }
    });
});

</script>

<!-- Edit FAQ Modal -->
<div class="upload-modal-backdrop" id="addFaqModalBackdrop" tabindex="-1" aria-hidden="true"></div>

<div class="upload-modal" id="editFaqModal" role="dialog" aria-modal="true" aria-labelledby="editFaqModalTitle" aria-describedby="editFaqModalDesc" tabindex="-1">
    <div class="upload-modal-content">
        <header class="upload-modal-header">
            <h4 class="upload-modal-title" id="editFaqModalTitle">Edit FAQ</h4>
            <button type="button" class="upload-modal-close" id="closeEditFaqModalBtn" aria-label="Close Edit FAQ Modal">
                <i class="fas fa-times" aria-hidden="true"></i>
            </button>
        </header>
        <form action="" method="POST" id="editFaqForm" novalidate>
            @csrf
            @method('PUT')
            <section class="upload-modal-body" id="editFaqModalDesc">
                <div class="form-group">
                    <label for="editQuestion" class="minimalist-label">
                        <i class="fas fa-question-circle" aria-hidden="true"></i> Question <sup aria-hidden="true" style="color: red;">*</sup>
                    </label>
                    <input type="text" name="question" id="editQuestion" class="minimalist-input" required aria-required="true" aria-describedby="editQuestionError" />
                    <div class="invalid-feedback" id="editQuestionError" role="alert"></div>
                </div>
                <div class="form-group">
                    <label for="editAnswer" class="minimalist-label">
                        <i class="fas fa-comment-dots" aria-hidden="true"></i> Answer
                    </label>
                    <textarea name="answer" id="editAnswer" rows="4" class="minimalist-input" aria-describedby="editAnswerError"></textarea>
                    <div class="invalid-feedback" id="editAnswerError" role="alert"></div>
                </div>
                <div class="form-group">
                    <label for="editCategory" class="minimalist-label">
                        <i class="fas fa-tags" aria-hidden="true"></i> Category <sup aria-hidden="true" style="color: red;">*</sup>
                    </label>
                    <select name="category" id="editCategory" class="minimalist-select" required aria-required="true" aria-describedby="editCategoryError">
                        <option value="" disabled>Select Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->categoryID }}">{{ $cat->category_name }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback" id="editCategoryError" role="alert"></div>
                </div>
            </section>
            <footer class="upload-modal-footer">
                <button type="button" class="btn-minimalist btn-cancel" id="cancelEditFaqBtn">
                    <i class="fas fa-times" aria-hidden="true"></i> Cancel
                </button>
                <button type="submit" class="btn-minimalist btn-upload">
                    <i class="fas fa-save" aria-hidden="true"></i> Save Changes
                </button>
            </footer>
        </form>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const editFaqModal = document.getElementById('editFaqModal');
    const closeEditFaqModalBtn = document.getElementById('closeEditFaqModalBtn');
    const cancelEditFaqBtn = document.getElementById('cancelEditFaqBtn');
    const editFaqForm = document.getElementById('editFaqForm');

    // Show Edit FAQ modal
    window.showEditFaqModal = function () {
        editFaqModal.classList.add('show');
        document.body.style.overflow = 'hidden';
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

    // Optional: Combined call
    window.onEditFaqClick = function (faqID) {
        populateEditFaqModal(faqID);
        showEditFaqModal();
    };

    // Close Edit FAQ modal
    function closeEditFaqModal() {
        editFaqModal.classList.remove('show');
        document.body.style.overflow = '';
        resetEditFaqForm();
    }

    function resetEditFaqForm() {
        editFaqForm.reset();
        clearEditFaqValidationErrors();
    }

    function clearEditFaqValidationErrors() {
        const errorFields = editFaqForm.querySelectorAll('.invalid-feedback');
        errorFields.forEach(field => field.textContent = '');
    }

    closeEditFaqModalBtn?.addEventListener('click', closeEditFaqModal);
    cancelEditFaqBtn?.addEventListener('click', closeEditFaqModal);

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && editFaqModal.classList.contains('show')) {
            closeEditFaqModal();
        }
    });
});
</script>



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
