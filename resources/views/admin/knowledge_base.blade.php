@extends('layouts.app')

@section('content')
<div class="container mt-4">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4 class="mb-0">Knowledge Base</h4>
      <a href="{{ route('kb.upload') }}" class="btn btn-primary btn-sm">Upload New Document</a>
    </div>

    <div class="card-body">
      <!-- Search bar -->
      <form action="{{ route('kb.search') }}" method="GET" class="mb-3">
        <div class="input-group">
          <input type="text" name="query" class="input-field" placeholder="Search documents by title or content..." value="{{ request('query') }}">
          <button class="btn btn-outline-secondary" type="submit">Search</button>
        </div>
      </form>

      <!-- Documents table -->
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
                <td>{{ $doc->kb_title }}</td>
                <td>{{ $doc->source }}</td>
                <td>{{ $doc->category->category_name ?? 'Uncategorized' }}</td>
                <td>{{ $doc->created_at->format('Y-m-d') }}</td>
                <td>
                  <a href="{{ route('kb.view', $doc->kbID) }}" class="btn btn-sm btn-info">View</a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center">No documents found.</td>
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
