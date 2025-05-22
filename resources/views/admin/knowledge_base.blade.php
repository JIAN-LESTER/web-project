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
                    <h1 class="kb-title">Knowledge Base Management</h1>
                    <p class="kb-subtitle">Centralized document repository for quick reference</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="action-buttons">
                        <a href="{{ route('kb.upload') }}" class="btn">
                            <i class="fas fa-upload"></i>Upload New Document
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="kb-card">
            <div class="card-body">
            <div class="row mb-4">
    <div class="col-md-8">
        <form method="GET" action="{{ route('kb.search') }}" class="kb-search-form" id="searchForm">
            <div class="d-flex flex-wrap align-items-center gap-2">
                <input type="text" name="query" class="form-control"
                    placeholder="Search documents by title..." value="{{ request('query') }}"
                    style="flex: 1 1 300px; min-width: 200px;">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="fas fa-search me-1"></i> Search
                </button>
                <select name="category" class="form-select" style="flex: 0 0 180px;">
                    <option value="">All Categories</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->categoryID }}" {{ request('category') == $cat->categoryID ? 'selected' : '' }}>
                            {{ $cat->category_name }}
                        </option>
                    @endforeach
                </select>
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

                                            switch (strtolower($categoryName)) {
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
                                            <a href="{{ route('kb.view', $doc->kbID) }}" class="btn-icon"
                                                data-bs-toggle="tooltip" data-bs-placement="top" title="View Document">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            <button type="button" class="btn-icon" onclick="confirmDelete({{ $doc->kbID }})"
                                                data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Document">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>

                                        <form id="delete-form-{{ $doc->kbID }}" action="{{ route('kb.destroy', $doc->kbID) }}"
                                            method="POST" style="display:none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="no-data">
                                        <div class="empty-docs">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" class="empty-icon">
                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                <polyline points="14 2 14 8 20 8"></polyline>
                                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                                <polyline points="10 9 9 9 8 9"></polyline>
                                            </svg>
                                            <h5>No documents found</h5>
                                            <p>No matching documents with the current search criteria</p>
                                            <a href="{{ route('kb.upload') }}" class="btn btn-primary mt-3">
                                                <i class="fas fa-upload me-2"></i>Upload New Document
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pagination-wrapper">
                    <div class="pagination-info">
                        Showing {{ $documents->firstItem() ?? 0 }} to {{ $documents->lastItem() ?? 0 }} of
                        {{ $documents->total() }} documents
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

    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Delete Confirmation',
                text: "Are you sure you want to delete this document?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                customClass: {
                    title: 'custom-swal-title',
                    htmlContainer: 'custom-swal-text',
                    popup: 'custom-swal-popup'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>

    <style>
        /* Force the text color using !important */
        .custom-swal-title {
            color: #b30000 !important;
            /* Deep red */
            font-weight: bold;
        }

        .custom-swal-text {
            color: #444 !important;
            /* Dark gray */
            font-size: 16px;
        }

        .custom-swal-popup {
            background-color: #fffaf0 !important;
            /* Light ivory background */
            border-radius: 12px;
        }

        .swal-btn-confirm {
            background-color: #e60000 !important;
            /* Bright red */
            color: white !important;
            border: none !important;
            box-shadow: none !important;
            padding: 8px 20px;
            font-weight: bold;
        }

        /* Cancel Button Style */
        .swal-btn-cancel {
            background-color: #999 !important;
            /* Gray */
            color: white !important;
            border: none !important;
            box-shadow: none !important;
            padding: 8px 20px;
            font-weight: bold;
        }
    </style>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
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
                row.addEventListener('click', function (e) {
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
            const closePanel = function () {
                docDetailsPanel.classList.remove('show');
                docDetailsBackdrop.classList.remove('show');
            };

            // Add multiple ways to close the panel
            document.getElementById('closeDocDetails').addEventListener('click', closePanel);
            document.getElementById('panelCloseBtn').addEventListener('click', closePanel);
            docDetailsBackdrop.addEventListener('click', closePanel);

            // Add keyboard escape to close panel
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && docDetailsPanel.classList.contains('show')) {
                    closePanel();
                }
            });

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
                liveSearch.addEventListener('input', function () {
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
                liveSearch.form.addEventListener('submit', function (e) {
                    // Only prevent default if it's the live search form being submitted by pressing Enter
                    if (e.submitter === null || e.submitter === undefined) {
                        e.preventDefault();
                    }
                });
            }
        });
    </script>
@endsection