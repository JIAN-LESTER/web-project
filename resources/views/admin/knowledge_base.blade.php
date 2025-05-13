@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('admin/knowledge-base.css') }}">
<div class="container mt-4">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4 class="mb-0">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="kb-icon">
          <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20"></path>
        </svg>
        Knowledge Base
      </h4>
      <a href="{{ route('kb.upload') }}" class="btn btn-primary btn-sm">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1">
          <path d="M12 5v14"></path>
          <path d="M5 12h14"></path>
        </svg>
        Upload New Document
      </a>
    </div>

    <div class="card-body">
      <form action="{{ route('kb.search') }}" method="GET" class="mb-3">
        <div class="search-container">
          <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
          </svg>
          <input type="text" name="query" class="form-control search-input" placeholder="Search documents by title or content..." value="{{ request('query') }}">
          <button class="btn btn-primary search-button" type="submit">Search</button>
        </div>
      </form>

      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead class="table-light">
            <tr>
              <th>Title</th>
              <th>Source</th>
              <th>Category</th>
              <th>Uploaded At</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($documents as $doc)
              <tr>
                <td>
                  <div class="document-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="file-icon">
                      <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                      <polyline points="14 2 14 8 20 8"></polyline>
                      <line x1="16" y1="13" x2="8" y2="13"></line>
                      <line x1="16" y1="17" x2="8" y2="17"></line>
                      <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                    {{ $doc->kb_title }}
                  </div>
                </td>
                <td>{{ $doc->source }}</td>
                <td>
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
                  <span class="category-tag {{ $categoryClass }}">{{ $categoryName }}</span>
                </td>
                <td>{{ $doc->created_at->format('M d, Y') }}</td>
                <td>
                  <a href="{{ route('kb.view', $doc->kbID) }}" class="btn btn-sm btn-info">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1">
                      <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                      <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                    View
                  </a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center py-4">
                  <div class="empty-state">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="empty-icon mb-3">
                      <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                      <polyline points="14 2 14 8 20 8"></polyline>
                      <line x1="16" y1="13" x2="8" y2="13"></line>
                      <line x1="16" y1="17" x2="8" y2="17"></line>
                      <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                    <h5>No documents found</h5>
                    <p class="text-muted">Try adjusting your search or upload a new document.</p>
                    <a href="{{ route('kb.upload') }}" class="btn btn-primary btn-sm mt-2">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1">
                        <path d="M12 5v14"></path>
                        <path d="M5 12h14"></path>
                      </svg>
                      Upload New Document
                    </a>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="d-flex justify-content-center mt-3">
        {{ $documents->links() }}
      </div>
    </div>
  </div>
</div>
@endsection
