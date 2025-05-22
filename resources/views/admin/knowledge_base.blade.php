@extends('layouts.app')

@section('content')
<!-- Include Google Fonts - Poppins -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<!-- Include Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<!-- Include Custom CSS -->
<link rel="stylesheet" href="{{ asset('admin/knowledge-base.css') }}">

<div class="container-fluid kb-container">
    <div class="kb-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="kb-title">Knowledge Base</h1>
                <p class="kb-subtitle">Centralized document repository for quick reference</p>
            </div>
            <div class="col-md-6 text-md-end">
                <div class="action-buttons">
                    <button class="btn upload-document-btn">
                        <i class="fas fa-upload"></i>Upload New Document
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="kb-card">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-12">
                    <form method="GET" action="{{ route('kb.search') }}" class="kb-search-form" id="searchForm">
                        <div class="search-wrapper">
                            <input type="text" name="query" class="form-control search-input" id="liveSearch"
                                   placeholder="Search documents by title or content..."
                                   value="{{ request('query') }}">
                            <button type="submit" class="search-button">Search</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="kb-table-wrapper">
                <table class="table kb-table">
                    <thead>
                        <tr>
                            <th class="sortable col-title" data-sort="title">
                                <div class="sort-header">
                                    Document Title <i class="fas fa-sort"></i>
                                </div>
                            </th>
                            <th class="sortable col-source" data-sort="source">
                                <div class="sort-header">
                                    Source <i class="fas fa-sort"></i>
                                </div>
                            </th>
                            <th class="sortable col-category" data-sort="category">
                                <div class="sort-header">
                                    Category <i class="fas fa-sort"></i>
                                </div>
                            </th>
                            <th class="sortable col-date" data-sort="date">
                                <div class="sort-header">
                                    Uploaded At <i class="fas fa-sort"></i>
                                </div>
                            </th>
                            <th class="col-actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($documents as $index => $doc)
                            <tr class="document-row" data-doc-id="{{ $doc->kbID }}" style="--i: {{ $index }}">
                                <td class="doc-title-cell">
                                    <div class="doc-title">
                                        <i class="fas fa-file-alt doc-icon"></i>
                                        {{ $doc->kb_title }}
                                    </div>
                                </td>
                                <td class="doc-source-cell">{{ $doc->source }}</td>
                                <td class="doc-category-cell">
                                    @php
                                        $categoryName = $doc->category->category_name ?? 'Uncategorized';
                                        $categoryClass = 'category-default';

                                        switch(strtolower($categoryName)) {
                                            case 'technical':
                                                $categoryClass = 'category-technical';
                                                break;
                                            case 'business':
                                                $categoryClass = 'category-business';
                                                break;
                                            case 'legal':
                                                $categoryClass = 'category-legal';
                                                break;
                                            case 'marketing':
                                                $categoryClass = 'category-marketing';
                                                break;
                                        }
                                    @endphp
                                    <span class="category-badge {{ $categoryClass }}">{{ $categoryName }}</span>
                                </td>
                                <td class="doc-date-cell">
                                    <div class="date-info">
                                        <div class="date-display">{{ $doc->created_at->format('M d, Y') }}</div>
                                        <div class="time-display">{{ $doc->created_at->format('h:i A') }}</div>
                                    </div>
                                </td>
                                <td class="doc-actions-cell">
                                    <div class="action-buttons">
                                        <a href="{{ route('kb.view', $doc->kbID) }}" class="btn-icon" data-bs-toggle="tooltip" data-bs-placement="top" title="View Document">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="no-data">
                                    <div class="empty-docs">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="empty-icon">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14 2 14 8 20 8"></polyline>
                                            <line x1="16" y1="13" x2="8" y2="13"></line>
                                            <line x1="16" y1="17" x2="8" y2="17"></line>
                                            <polyline points="10 9 9 9 8 9"></polyline>
                                        </svg>
                                        <h5>No documents found</h5>
                                        <p>No matching documents with the current search criteria</p>
                                        <button class="btn btn-primary mt-3 upload-document-btn">
                                            <i class="fas fa-upload me-2"></i>Upload New Document
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pagination-wrapper">
                <div class="pagination-info">
                    Showing {{ $documents->firstItem() ?? 0 }} to {{ $documents->lastItem() ?? 0 }} of {{ $documents->total() }} documents
                </div>
                <div class="pagination">
                    <!-- Left arrow -->
                    <a href="{{ $documents->previousPageUrl() }}"
                       class="page-link {{ $documents->onFirstPage() ? 'disabled' : '' }}">
                        <i class="fas fa-chevron-left"></i>
                    </a>

                    <!-- Page numbers -->
                    @for ($i = 1; $i <= $documents->lastPage(); $i++)
                        <a href="{{ $documents->url($i) }}"
                           class="page-link {{ $documents->currentPage() == $i ? 'active' : '' }}">
                            {{ $i }}
                        </a>
                    @endfor

                    <!-- Right arrow -->
                    <a href="{{ $documents->nextPageUrl() }}"
                       class="page-link {{ !$documents->hasMorePages() ? 'disabled' : '' }}">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Document Details Panel -->
<div class="doc-details-panel" id="docDetailsPanel">
    <div class="doc-details-header">
        <h5 class="doc-details-title">Document Details</h5>
        <button type="button" class="doc-details-close" id="closeDocDetails">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div class="doc-details-content">
        <div class="doc-details-section">
            <h6 class="doc-details-section-title">Basic Information</h6>
            <div class="doc-detail-item">
                <span class="detail-label">Document ID:</span>
                <span class="detail-value highlight" id="panel-doc-id"></span>
            </div>
            <div class="doc-detail-item">
                <span class="detail-label">Title:</span>
                <span class="detail-value" id="panel-doc-title"></span>
            </div>
            <div class="doc-detail-item">
                <span class="detail-label">Source:</span>
                <span class="detail-value" id="panel-doc-source"></span>
            </div>
        </div>

        <div class="doc-details-section">
            <h6 class="doc-details-section-title">Classification</h6>
            <div class="doc-detail-item">
                <span class="detail-label">Category:</span>
                <span class="detail-value highlight" id="panel-doc-category"></span>
            </div>
            <div class="doc-detail-item">
                <span class="detail-label">Uploaded:</span>
                <span class="detail-value" id="panel-doc-date"></span>
            </div>
            <div class="doc-detail-item">
                <span class="detail-label">Size:</span>
                <span class="detail-value" id="panel-doc-size"></span>
            </div>
        </div>

        <div class="doc-details-section">
            <h6 class="doc-details-section-title">Description</h6>
            <div class="doc-description" id="panel-doc-description">
                <!-- Document description will be inserted here -->
            </div>
        </div>
    </div>
    <div class="doc-details-footer">
        <a href="#" class="btn btn-primary" id="panel-view-btn">View Document</a>
        <a href="#" class="btn btn-download" id="panel-download-btn">Download</a>
        <button type="button" class="btn btn-secondary" id="panelCloseBtn">Close</button>
    </div>
</div>
<div class="doc-details-backdrop" id="docDetailsBackdrop"></div>

<!-- Upload Document Modal - Minimalist Design -->
<div class="upload-modal-backdrop" id="uploadModalBackdrop"></div>
<div class="upload-modal" id="uploadModal">
    <div class="upload-modal-content">
        <div class="upload-modal-header">
            <h4 class="upload-modal-title">Upload Document</h4>
            <button type="button" class="upload-modal-close" id="closeUploadModalBtn">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="upload-modal-body">
            <div class="upload-progress-indicator">
                <i class="fas fa-cloud-upload-alt progress-icon"></i>
                <span class="progress-text">Add a new document to the knowledge base</span>
            </div>

            <form action="{{ route('kb.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                @csrf

                <div class="form-grid">
                    <div class="form-group">
                        <label for="kb_title" class="minimalist-label">
                            <i class="fas fa-file-text"></i>
                            Document Title
                        </label>
                        <input type="text" id="kb_title" name="kb_title" class="minimalist-input" required placeholder="Enter document title">
                        <div class="invalid-feedback" id="title-error"></div>
                    </div>

                    <div class="form-group">
                        <label for="category" class="minimalist-label">
                            <i class="fas fa-folder"></i>
                            Category
                        </label>
                        <select name="category" id="category" class="minimalist-select" required>
                            <option value="">Select Category</option>
                            @if(isset($categories) && count($categories) > 0)
                                @foreach($categories as $category)
                                    <option value="{{ $category->categoryID }}">{{ $category->category_name }}</option>
                                @endforeach
                            @endif
                        </select>
                        <div class="invalid-feedback" id="category-error"></div>
                    </div>

                    <div class="form-group file-upload-group">
                        <label class="minimalist-label">
                            <i class="fas fa-upload"></i>
                            Upload Document
                        </label>
                        <div class="file-upload-area">
                            <input type="file" id="document" name="document" class="file-input" accept=".pdf,.docx,.txt,.doc,.xlsx,.pptx" required>
                            <div class="file-upload-display">
                                <div class="upload-placeholder">
                                    <i class="fas fa-cloud-upload-alt upload-icon"></i>
                                    <span class="upload-text">Choose file or drag & drop</span>
                                    <span class="upload-subtitle">PDF, DOCX, TXT, DOC, XLSX, PPTX (max 10MB)</span>
                                </div>
                                <div class="file-selected" id="fileSelected" style="display: none;">
                                    <i class="fas fa-file-alt selected-icon"></i>
                                    <div class="file-info">
                                        <span class="file-name" id="selectedFileName"></span>
                                        <span class="file-size" id="selectedFileSize"></span>
                                    </div>
                                    <button type="button" class="remove-file" id="removeFile">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="invalid-feedback" id="file-error"></div>
                    </div>
                </div>
            </form>
        </div>
        <div class="upload-modal-footer">
            <button type="button" class="btn-minimalist btn-cancel" id="cancelUploadBtn">
                <i class="fas fa-times"></i>
                Cancel
            </button>
            <button type="submit" form="uploadForm" class="btn-minimalist btn-upload">
                <i class="fas fa-upload"></i>
                Upload Document
            </button>
        </div>
    </div>
</div>

<style>
/* ========================================
   MINIMALIST UPLOAD MODAL STYLING
   ======================================== */

/* Modal Backdrop */
.upload-modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.4);
    backdrop-filter: blur(3px);
    z-index: 1070;
    display: none;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.upload-modal-backdrop.show {
    display: block;
    opacity: 1;
}

/* Modal Container */
.upload-modal {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) scale(0.95);
    background-color: white;
    border-radius: 16px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
    width: 90%;
    max-width: 520px;
    z-index: 1080;
    display: none;
    overflow: hidden;
    font-family: 'Poppins', sans-serif;
    transition: all 0.3s ease;
    opacity: 0;
}

.upload-modal.show {
    display: block;
    opacity: 1;
    transform: translate(-50%, -50%) scale(1);
}

/* Modal Content Structure */
.upload-modal-content {
    display: flex;
    flex-direction: column;
    max-height: 85vh;
}

/* Modal Header */
.upload-modal-header {
    padding: 20px 24px 0 24px;
    position: relative;
    background: transparent;
    border: none;
}

.upload-modal-title {
    font-size: 18px;
    font-weight: 600;
    color: #2c3e50;
    margin: 0;
    font-family: 'Poppins', sans-serif;
}

.upload-modal-close {
    position: absolute;
    top: 16px;
    right: 20px;
    background: #f8f9fa;
    border: none;
    border-radius: 50%;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    color: #64748b;
    font-size: 14px;
    transition: all 0.2s ease;
    opacity: 1;
}

.upload-modal-close:hover {
    background: #e9ecef;
    transform: scale(1.05);
}

/* Modal Body */
.upload-modal-body {
    padding: 24px;
    overflow-y: auto;
    flex: 1;
}

/* Progress Indicator */
.upload-progress-indicator {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 24px;
    padding: 12px 16px;
    background: #f0f9ff;
    border-radius: 8px;
    border-left: 4px solid #0369a1;
}

.progress-icon {
    color: #0369a1;
    font-size: 16px;
}

.progress-text {
    font-size: 13px;
    color: #0369a1;
    font-weight: 500;
}

/* Form Grid */
.form-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

/* Form Labels */
.minimalist-label {
    font-size: 13px;
    font-weight: 500;
    color: #374151;
    margin-bottom: 6px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.minimalist-label i {
    font-size: 12px;
    color: #64748b;
}

/* Form Inputs */
.minimalist-input,
.minimalist-select {
    padding: 12px 16px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
    font-family: 'Poppins', sans-serif;
    transition: all 0.2s ease;
    background: #ffffff;
    color: #374151;
}

.minimalist-input:focus,
.minimalist-select:focus {
    outline: none;
    border-color: #0F4C3A;
    box-shadow: 0 0 0 3px rgba(15, 76, 58, 0.1);
}

.minimalist-input::placeholder {
    color: #9ca3af;
    font-size: 13px;
}

/* Select Dropdown */
.minimalist-select {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23374151' stroke-width='2'%3E%3Cpolyline points='6,9 12,15 18,9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    background-size: 16px;
    padding-right: 40px;
}

/* File Upload Area */
.file-upload-group {
    grid-column: 1 / -1;
}

.file-upload-area {
    position: relative;
    border: 2px dashed #e5e7eb;
    border-radius: 8px;
    background: #fafafa;
    transition: all 0.2s ease;
    cursor: pointer;
}

.file-upload-area:hover {
    border-color: #0F4C3A;
    background: #f0f9f5;
}

.file-upload-area.dragover {
    border-color: #0F4C3A;
    background: #f0f9f5;
    box-shadow: 0 0 0 3px rgba(15, 76, 58, 0.1);
}

.file-input {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
    z-index: 2;
}

.file-upload-display {
    padding: 24px;
    text-align: center;
}

/* Upload Placeholder */
.upload-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
}

.upload-icon {
    font-size: 32px;
    color: #0F4C3A;
    margin-bottom: 8px;
}

.upload-text {
    font-size: 14px;
    font-weight: 500;
    color: #374151;
}

.upload-subtitle {
    font-size: 12px;
    color: #6b7280;
}

/* File Selected State */
.file-selected {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 20px;
    background: #f0f9ff;
    border: 1px solid #0369a1;
    border-radius: 8px;
    margin: 0;
}

.selected-icon {
    font-size: 20px;
    color: #0369a1;
}

.file-info {
    flex: 1;
    text-align: left;
}

.file-name {
    display: block;
    font-size: 14px;
    font-weight: 500;
    color: #0f172a;
    margin-bottom: 2px;
}

.file-size {
    display: block;
    font-size: 12px;
    color: #64748b;
}

.remove-file {
    background: transparent;
    border: none;
    color: #64748b;
    cursor: pointer;
    padding: 4px;
    border-radius: 4px;
    transition: all 0.2s ease;
}

.remove-file:hover {
    background: #e2e8f0;
    color: #ef4444;
}

/* Modal Footer */
.upload-modal-footer {
    padding: 20px 24px;
    background: #f8f9fa;
    border: none;
    display: flex;
    gap: 12px;
    justify-content: flex-end;
}

/* Minimalist Buttons */
.btn-minimalist {
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 500;
    font-family: 'Poppins', sans-serif;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.btn-cancel {
    background: transparent;
    color: #6b7280;
    border: 1px solid #e5e7eb;
}

.btn-cancel:hover {
    background: #f9fafb;
    color: #374151;
}

.btn-upload {
    background: #0F4C3A;
    color: white;
}

.btn-upload:hover {
    background: #0A3628;
    transform: translateY(-1px);
}

.btn-upload:disabled {
    background: #d1d5db;
    color: #9ca3af;
    cursor: not-allowed;
    transform: none;
}

/* Validation States */
.invalid-feedback {
    display: block;
    width: 100%;
    margin-top: 4px;
    font-size: 12px;
    color: #dc3545;
}

.is-invalid {
    border-color: #dc3545;
}

.is-invalid:focus {
    border-color: #dc3545;
    box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
}

/* Loading State */
.btn-upload.loading {
    position: relative;
    color: transparent;
}

.btn-upload.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 16px;
    height: 16px;
    border: 2px solid #ffffff;
    border-top: 2px solid transparent;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: translate(-50%, -50%) rotate(0deg); }
    100% { transform: translate(-50%, -50%) rotate(360deg); }
}

/* Focus States */
.btn-minimalist:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(15, 76, 58, 0.2);
}

/* Mobile Responsive */
@media (max-width: 576px) {
    .upload-modal {
        width: 95%;
        max-height: 90vh;
    }

    .upload-modal-body {
        padding: 16px;
    }

    .upload-modal-header {
        padding: 16px 16px 0 16px;
    }

    .upload-modal-footer {
        padding: 16px;
        flex-direction: column;
        gap: 8px;
    }

    .file-upload-display {
        padding: 16px;
    }

    .upload-icon {
        font-size: 24px;
    }

    .upload-text {
        font-size: 13px;
    }

    .upload-subtitle {
        font-size: 11px;
    }
}

/* Drag and Drop States */
.file-upload-area.drag-active {
    border-color: #0F4C3A;
    background: #f0f9f5;
    box-shadow: 0 0 0 3px rgba(15, 76, 58, 0.1);
}

/* Animation Classes */
@keyframes fadeInBackdrop {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes fadeInModal {
    from {
        opacity: 0;
        transform: translate(-50%, -50%) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translate(-50%, -50%) scale(1);
    }
}

/* Success State */
.upload-success {
    display: none;
    text-align: center;
    padding: 24px;
}

.upload-success.show {
    display: block;
}

.success-icon {
    font-size: 48px;
    color: #059669;
    margin-bottom: 16px;
}

.success-message {
    font-size: 16px;
    font-weight: 500;
    color: #374151;
    margin-bottom: 8px;
}

.success-subtitle {
    font-size: 14px;
    color: #6b7280;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Make table rows clickable to show details
    const documentRows = document.querySelectorAll('.document-row');
    const docDetailsPanel = document.getElementById('docDetailsPanel');
    const docDetailsBackdrop = document.getElementById('docDetailsBackdrop');

    documentRows.forEach(row => {
        row.addEventListener('click', function(e) {
            // Don't show panel if clicked on action buttons
            if (e.target.closest('.btn-icon') || e.target.closest('.action-buttons')) {
                return;
            }

            const docId = this.getAttribute('data-doc-id');
            const docTitle = this.querySelector('.doc-title')?.textContent.trim() || '';
            const docSource = this.querySelector('.doc-source-cell')?.textContent || '';
            const docCategory = this.querySelector('.category-badge')?.textContent || '';
            const docDate = this.querySelector('.date-display')?.textContent || '';
            const docTime = this.querySelector('.time-display')?.textContent || '';

            // Set panel content
            document.getElementById('panel-doc-id').textContent = docId;
            document.getElementById('panel-doc-title').textContent = docTitle;
            document.getElementById('panel-doc-source').textContent = docSource;
            document.getElementById('panel-doc-category').textContent = docCategory;
            document.getElementById('panel-doc-date').textContent = `${docDate} at ${docTime}`;
            document.getElementById('panel-doc-size').textContent = '1.2 MB'; // This would be dynamic in real implementation
            document.getElementById('panel-doc-description').textContent = 'This document contains detailed information about...'; // This would be dynamic

            // Set action button links
            document.getElementById('panel-view-btn').href = `/kb/view/${docId}`;
            document.getElementById('panel-download-btn').href = `/kb/download/${docId}`;

            // Show panel and backdrop
            docDetailsPanel.classList.add('show');
            docDetailsBackdrop.classList.add('show');
        });
    });

    // Close panel functionality
    const closePanel = function() {
        docDetailsPanel.classList.remove('show');
        docDetailsBackdrop.classList.remove('show');
    };

    // Add multiple ways to close the panel
    document.getElementById('closeDocDetails').addEventListener('click', closePanel);
    document.getElementById('panelCloseBtn').addEventListener('click', closePanel);
    docDetailsBackdrop.addEventListener('click', closePanel);

    // Modal elements
    const uploadModalBackdrop = document.getElementById('uploadModalBackdrop');
    const uploadModal = document.getElementById('uploadModal');
    const uploadBtns = document.querySelectorAll('.upload-document-btn');
    const closeUploadModalBtn = document.getElementById('closeUploadModalBtn');
    const cancelUploadBtn = document.getElementById('cancelUploadBtn');
    const uploadForm = document.getElementById('uploadForm');

    // File input elements
    const fileInput = document.getElementById('document');
    const fileUploadArea = document.querySelector('.file-upload-area');
    const uploadPlaceholder = document.querySelector('.upload-placeholder');
    const fileSelected = document.getElementById('fileSelected');
    const selectedFileName = document.getElementById('selectedFileName');
    const selectedFileSize = document.getElementById('selectedFileSize');
    const removeFileBtn = document.getElementById('removeFile');

    // Form validation
    const titleInput = document.getElementById('kb_title');
    const categorySelect = document.getElementById('category');

    // Modal functions
    const openUploadModal = function() {
        uploadModal.classList.add('show');
        uploadModalBackdrop.classList.add('show');
        document.body.style.overflow = 'hidden';
    };

    const closeUploadModal = function() {
        uploadModal.classList.remove('show');
        uploadModalBackdrop.classList.remove('show');
        document.body.style.overflow = '';
        resetForm();
    };

    const resetForm = function() {
        uploadForm.reset();
        hideFileSelected();
        clearValidationErrors();
    };

    // Event listeners for modal
    uploadBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            openUploadModal();
        });
    });

    closeUploadModalBtn.addEventListener('click', closeUploadModal);
    cancelUploadBtn.addEventListener('click', closeUploadModal);
    uploadModalBackdrop.addEventListener('click', closeUploadModal);

    // File input functionality
    const showFileSelected = function(file) {
        const fileSize = formatFileSize(file.size);
        selectedFileName.textContent = file.name;
        selectedFileSize.textContent = fileSize;

        uploadPlaceholder.style.display = 'none';
        fileSelected.style.display = 'flex';
    };

    const hideFileSelected = function() {
        uploadPlaceholder.style.display = 'flex';
        fileSelected.style.display = 'none';
    };

    const formatFileSize = function(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    };

    // File input events
    fileInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            const file = this.files[0];
            if (validateFile(file)) {
                showFileSelected(file);
            }
        }
    });

    removeFileBtn.addEventListener('click', function(e) {
        e.preventDefault();
        fileInput.value = '';
        hideFileSelected();
    });

    // Drag and drop functionality
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        fileUploadArea.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        fileUploadArea.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        fileUploadArea.addEventListener(eventName, unhighlight, false);
    });

    function highlight(e) {
        fileUploadArea.classList.add('drag-active');
    }

    function unhighlight(e) {
        fileUploadArea.classList.remove('drag-active');
    }

    fileUploadArea.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;

        if (files.length > 0) {
            const file = files[0];
            if (validateFile(file)) {
                fileInput.files = files;
                showFileSelected(file);
            }
        }
    }

    // File validation
    const validateFile = function(file) {
        const allowedTypes = ['application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'text/plain', 'application/msword', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.openxmlformats-officedocument.presentationml.presentation'];
        const maxSize = 10 * 1024 * 1024; // 10MB

        if (!allowedTypes.includes(file.type)) {
            showFieldError('file-error', 'Please select a valid file type (PDF, DOCX, TXT, DOC, XLSX, PPTX)');
            return false;
        }

        if (file.size > maxSize) {
            showFieldError('file-error', 'File size must be less than 10MB');
            return false;
        }

        clearFieldError('file-error');
        return true;
    };

    // Form validation
    const validateForm = function() {
        let isValid = true;

        // Clear previous errors
        clearValidationErrors();

        // Validate title
        if (!titleInput.value.trim()) {
            showFieldError('title-error', 'Document title is required');
            titleInput.classList.add('is-invalid');
            isValid = false;
        }

        // Validate category
        if (!categorySelect.value) {
            showFieldError('category-error', 'Please select a category');
            categorySelect.classList.add('is-invalid');
            isValid = false;
        }

        // Validate file
        if (!fileInput.files.length) {
            showFieldError('file-error', 'Please select a file to upload');
            isValid = false;
        }

        return isValid;
    };

    const showFieldError = function(errorId, message) {
        const errorElement = document.getElementById(errorId);
        if (errorElement) {
            errorElement.textContent = message;
        }
    };

    const clearFieldError = function(errorId) {
        const errorElement = document.getElementById(errorId);
        if (errorElement) {
            errorElement.textContent = '';
        }
    };

    const clearValidationErrors = function() {
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
    };

    // Real-time validation
    titleInput.addEventListener('blur', function() {
        if (!this.value.trim()) {
            this.classList.add('is-invalid');
            showFieldError('title-error', 'Document title is required');
        } else {
            this.classList.remove('is-invalid');
            clearFieldError('title-error');
        }
    });

    categorySelect.addEventListener('change', function() {
        if (!this.value) {
            this.classList.add('is-invalid');
            showFieldError('category-error', 'Please select a category');
        } else {
            this.classList.remove('is-invalid');
            clearFieldError('category-error');
        }
    });

    // Form submission
    uploadForm.addEventListener('submit', function(e) {
        e.preventDefault();

        if (validateForm()) {
            const submitBtn = document.querySelector('.btn-upload');
            const originalText = submitBtn.innerHTML;

            // Show loading state
            submitBtn.disabled = true;
            submitBtn.classList.add('loading');
            submitBtn.innerHTML = 'Uploading...';

            // Submit form
            this.submit();
        }
    });

    // Add keyboard escape to close panel and modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (docDetailsPanel.classList.contains('show')) {
                closePanel();
            }
            if (uploadModal.classList.contains('show')) {
                closeUploadModal();
            }
        }
    });

    // Add sorting functionality
    const sortHeaders = document.querySelectorAll('.sortable');
    sortHeaders.forEach(header => {
        header.addEventListener('click', function() {
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

            // Add actual sorting logic (you can implement AJAX or form submit here)
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('sort', sort);
            currentUrl.searchParams.set('direction', newDirection);
            window.location.href = currentUrl.toString();
        });
    });

    // Add live search functionality
    const liveSearch = document.getElementById('liveSearch');
    if (liveSearch) {
        liveSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('.document-row');

            rows.forEach(row => {
                const docTitle = row.querySelector('.doc-title')?.textContent.toLowerCase() || '';
                const docSource = row.querySelector('.doc-source-cell')?.textContent.toLowerCase() || '';
                const docCategory = row.querySelector('.category-badge')?.textContent.toLowerCase() || '';

                // Check if any field contains the search term
                const isMatch = docTitle.includes(searchTerm) ||
                               docSource.includes(searchTerm) ||
                               docCategory.includes(searchTerm);

                // Show/hide row based on match
                row.style.display = isMatch ? '' : 'none';
            });

            // Check if no results found
            const visibleRows = document.querySelectorAll('.document-row[style="display: none;"]');
            const tableBody = document.querySelector('.kb-table tbody');
            const noResultsRow = document.getElementById('noSearchResults');

            if (visibleRows.length === rows.length && searchTerm !== '') {
                // No matches found
                if (!noResultsRow) {
                    const newRow = document.createElement('tr');
                    newRow.id = 'noSearchResults';
                    newRow.innerHTML = `
                        <td colspan="5" class="no-data">
                            <div class="empty-docs">
                                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="empty-icon">
                                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                                    <line x1="12" y1="9" x2="12" y2="13"></line>
                                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                </svg>
                                <h5>No matching documents</h5>
                                <p>Try a different search term</p>
                            </div>
                        </td>
                    `;
                    tableBody.appendChild(newRow);
                }
            } else if (noResultsRow) {
                // Remove no results message if there are matches
                noResultsRow.remove();
            }
        });

        // Prevent form submission on enter key
        liveSearch.form.addEventListener('submit', function(e) {
            // Only prevent default if it's the live search form being submitted by pressing Enter
            if (e.submitter === null || e.submitter === undefined) {
                e.preventDefault();
            }
        });
    }
});
</script>
@endsection
